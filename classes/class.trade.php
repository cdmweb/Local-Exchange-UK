<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.category.php");
include_once("class.feedback.php");

class cTrade {


	var $trade_id;
	var $trade_date;
	var $status;
	var $member_id_from;
	var $member_id_to;
	var $member_from;
	var $member_to;
	var $amount;
	var $category;		// CT - just the description is fine here - 1 string.
	var $description;
	var $type;
	var $feedback_buyer;	// added after trade completed; object of type cFeedback
	var $feedback_seller; // added after trade completed; object of type cFeedback

	function cTrade ($member_from=null, $member_to=null, $amount=null, $category=null, $description=null, $type='T') {
		if($member_from) {
			$this->status = 'V';  // Doesn't make sense for a new Trade to not be valid
			$this->amount = $amount;
			$this->description = $description;
			$this->member_from = $member_from;
			$this->member_to = $member_to;
			$this->type = $type;
			$this->category = new cCategory();
			$this->category->LoadCategory($category);
		}
	}
	
	function ShowTrade() {
		global $cDB;
		
		$content = $this->trade_id .", ". $this->trade_date .", ". $this->status .", ". $this->member_from->getMemberId() .", ". $this->member_id_to .", ". $this->amount .", ". $this->category->id .", ". $this->description .", ". $this->type;
		
		return $content;
	}

	function SaveTrade() {  // This function should never be called directly
		global $cDB, $cErr;
		
		$insert = $cDB->Query("INSERT INTO ". DATABASE_TRADES ." (trade_date, status, member_id_from, member_id_to, amount, category, description, type) VALUES (now(), ". $cDB->EscTxt($this->status) .", ". $cDB->EscTxt($this->member_from->getMemberId()) .", ". $cDB->EscTxt($this->member_id_to) .", ". $cDB->EscTxt($this->amount) .", ". $cDB->EscTxt($this->category->id) .", ". $cDB->EscTxt($this->description) .", ". $cDB->EscTxt($this->type) .");");

		if(mysqli_affected_rows() == 1) {
		
			$this->trade_id = mysqli_insert_id();	
			$query = $cDB->Query("SELECT trade_date from ". DATABASE_TRADES ." WHERE trade_id=". $this->trade_id .";");
			$row = mysqli_fetch_array($query);
			$this->trade_date = $row[0];	
			return true;
		} else {
			return false;
		}
	}
	
	function LoadTrade($trade_id) {
		global $cDB, $cErr;
		//CT - efficiency - combine db calls. categories, feedback comes free!
		$query = $cDB->Query("SELECT date_format(trade_date,'%Y-%m-%d'), status, trade_id, member_id_from, member_id_to, amount, t.description as description, type, c.description as category FROM ".DATABASE_TRADES." t left join t.category on t.category = c.category_id WHERE trade_id=". $cDB->EscTxt($trade_id) .";");
		
		if($row = mysqli_fetch_array($query)) {		
			$this->ConstructTrade($row);
/*
			$feedback = new cFeedback;
			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_from->getMemberId());
			if($feedback_id) {
				$this->feedback_buyer = new cFeedback;
				$this->feedback_buyer->LoadFeedback($feedback_id);
			}
			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_to->getMemberId());
			if($feedback_id) {
				$this->feedback_seller = new cFeedback;
				$this->feedback_seller->LoadFeedback($feedback_id);
			}*/
			
		} else {
			$cErr->Error("There was an error accessing the trades table.  Please try again later.");
			//include("redirect.php");
		}				
	}
	//no need to call each line separately in a db call, you've got it! jsut used the array
	function ConstructTrade($array) {
		global $cDB, $cErr;
		
		//$query = $cDB->Query("SELECT date_format(trade_date,'%Y-%m-%d'), status, member_id_from, member_id_to, amount, description, type, category FROM ".DATABASE_TRADES." WHERE trade_id=". $cDB->EscTxt($trade_id) .";");
		
		//if($row = mysqli_fetch_array($query)) {		
			$this->trade_id = $array['trade_id'];
			$this->trade_date = $array['trade_date'];
			$this->status = $array['status'];
			//doesnt appear to be needed, only ids are listed.
			//$this->member_from = new cMember;
			//CT - todo - create v small class extension to stop loading EVERYTHING for one fraking line
			//$this->member_from->ConstructMember(array('member_id' => $array['member_id_from']));
			//$this->member_to = new cMember;
			//$this->member_from->ConstructMember(array('member_id' => $array['member_id_to']));
			
			//$this->member_id_from = $this->member_from->getMemberId();
			//$this->member_id_to = $this->member_to->getMemberId();
			$this->member_id_from = $array['member_id_from'];
			$this->member_id_to = $array['member_id_to'];
			$this->amount = $array['amount'];
			$this->description = $cDB->UnEscTxt($array['description']);
			$this->category = $cDB->UnEscTxt($array['category']);
			$this->type = $array['type'];
			//$this->category = new cCategory();
			//$this->category->LoadCategory($array['category']);
			
/*			$feedback = new cFeedback;
			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_from->getMemberId());
			if($feedback_id) {
				$this->feedback_buyer = new cFeedback;
				$this->feedback_buyer->LoadFeedback($feedback_id);
			}
			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_to->getMemberId());
			if($feedback_id) {
				$this->feedback_seller = new cFeedback;
				$this->feedback_seller->LoadFeedback($feedback_id);
			}
*/			
					
	}

	// It is very important that this function prevent the database from going out balance.
	function MakeTrade($reversed_trade_id=null) { 
		global $cDB, $cErr;
		
		if ($this->amount <= 0 and $this->type != TRADE_REVERSAL) // Amount should be positive unless
			return false;									 // this is a reversal of a previous trade.
			
		if ($this->amount >= 0 and $this->type == TRADE_REVERSAL)	 // And likewise.
			return false;
			
		
		if ($this->member_from->getMemberId() == $this->member_id_to)
			return false;		// don't allow trade to self
		
		if ($this->member_from->getRestriction()==1) { // This member's account has been restricted - he is not allowed to make outgoing trades
			
			return false;
		}
	
		$balances = new cBalancesTotal;
	
		// TODO: At some point, we should handle out-of-balance problems without shutting 
		// down all trades.  But for now, seems like a wonderfully simple solution.	
		//
		// [chris] Have added a few more methods for dealing with out-of-balance scenarios (admin can set his/her preferred method in inc.config.php)	
		
		if(!$balances->Balanced()) {
			
			if (OOB_EMAIL_ADMIN==true) // Admin wishes to receive an email notifying him/her when db is found to be out-of-balance
				$mailed = mail(EMAIL_ADMIN, "Database out of balance on ".SITE_LONG_TITLE."!", "Hi admin,\n\nWe thought you should know that whilst processing a trade the system detected that your trade database is out of balance! Obviously something has gone wrong somewhere along the line and we suggest you investigate the cause of this ASAP.\n\n" .  HTTP_BASE, EMAIL_FROM);
			
			switch(OOB_ACTION) { // How should we handle the out-of-balance event?
				
				case("FATAL"): // FATAL: The original method for dealing which is to abort the transaction
					
					$cErr->Error("The trade database is out of balance!  Please contact your administrator at ". EMAIL_ADMIN .".", ERROR_SEVERITY_HIGH);  

					//include("redirect.php");
					exit;  // Probably unnecessary...
					
				break;
				
				default: // SILENT: Just ignore the situation and don't burden the user with warnings/error messages
					
						// doing nothing...
						
				break;
			}
		}	

		// NOTE: Need table type InnoDB to do the following transaction-style statements.		
		$cDB->Query("SET AUTOCOMMIT=0");
		
		$cDB->Query("BEGIN");
		
		if($this->SaveTrade()) {
			
			$success1 = $this->member_from->UpdateBalance(-($this->amount));
			$success2 = $this->member_to->UpdateBalance($this->amount);
			
			if(LOG_LEVEL > 0 and $this->type != TRADE_ENTRY) {//Log if enabled & not an ordinary trade
				$log_entry = new cLogEntry (TRADE, $this->type, $this->trade_id);
				$success3 = $log_entry->SaveLogEntry();
			} else {
				$success3 = true;
			}
			
			if($reversed_trade_id) {  // If this is a trade reversal, need to mark old trade reversed
				$success4 = $cDB->Query("UPDATE ".DATABASE_TRADES." SET status='R', trade_date=trade_date WHERE trade_id=". $cDB->EscTxt($reversed_trade_id) .";");
			} else {
				$success4 = true;
			}

			if($success1 and $success2 and $success3 and $success4) {
				$cDB->Query('COMMIT');
				$cDB->Query("SET AUTOCOMMIT=1"); // Probably isn't necessary...
				return true;
			} else {
				$cDB->Query('ROLLBACK');
				$cDB->Query("SET AUTOCOMMIT=1"); // Probably isn't necessary...
				return false;
			}
		} else {
			$cDB->Query("SET AUTOCOMMIT=1"); // Probably isn't necessary...
			return false;
		}			
	}
	
	function ReverseTrade($description) { 	// This method allows administrators to reverse
		global $cUser;								// trades that were made in error.
		
		if($this->status == "R")
			return false;		// Can't reverse the same trade twice
			
		$new_trade = new cTrade;				
		$new_trade->status = "V";
		$new_trade->member_from = $this->member_from;
		$new_trade->member_to = $this->member_to;
		$new_trade->amount = -$this->amount;
		$new_trade->category = $this->category;
		$new_trade->description = "[Reversal of exchange #". $this->trade_id." from ". $this->trade_date." by admin '". $cUser->member_id ."'] ". $description;
		$new_trade->type = "R";
		return $new_trade->MakeTrade($this->trade_id);
	}
}

class cTradeGroup {
	var $trade;   	// an array of cTrade objects
	var $member_id;
	var $from_date;
	var $to_date;
	
	function cTradeGroup($member_id="%", $from_date=LONG_LONG_AGO, $to_date=FAR_FAR_AWAY) {
		$this->member_id = $member_id;
		$this->from_date = $from_date;
		$this->to_date = $to_date;
	}
	
	function LoadTradeGroup($type = "all") {
		global $cDB, $cErr;
		
		$to_date = strtotime("+1 days", strtotime($this->to_date));
		

        // Ignore monthly fees.
       
        $trade_type = TRADE_MONTHLY_FEE;
        $trade_type_refund = TRADE_MONTHLY_FEE_REVERSAL;


		$gf = (SHOW_GLOBAL_FEES !=true) ? "AND type !='S' AND type != '{$trade_type}' AND type != '{$trade_type_refund}'" : "";
       	
		$query = $cDB->Query("SELECT date_format(trade_date,'%Y-%m-%d') as trade_date, status, trade_id, member_id_from, member_id_to, amount, t.description as description, type, c.description as category FROM ".DATABASE_TRADES." t left join categories c on t.category = c.category_id WHERE (member_id_from LIKE ". $cDB->EscTxt($this->member_id) ." OR member_id_to LIKE ". $cDB->EscTxt($this->member_id) .") AND trade_date > ". $cDB->EscTxt($this->from_date) ." AND trade_date < ". $cDB->EscTxt(date("Ymd", $to_date)) ." {$gf} ORDER BY trade_date DESC;");

    

		// instantiate new cTrade objects and load them

		$i=0;

		while($row = mysqli_fetch_array($query)) // Each of our SQL results
		{
			//echo $row['balance'];
			$this->trade[$i] = new cTrade;	
			$this->trade[$i]->ConstructTrade($row); 	
			$i++;
		}
		
		
		if($i == 0)
			return false;
		else
			return true;
	}
	
	function DisplayTradeGroup() {
		global $cDB, $cUser, $p;
		
		//$output = "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH=\"100%\"><TR BGCOLOR=\"#d8dbea\"><TD><FONT SIZE=2><B>Date</B></FONT></TD><TD><FONT SIZE=2><B>From</B></FONT></TD><TD><FONT SIZE=2><B>To</B></FONT></TD><TD ALIGN=RIGHT><FONT SIZE=2><B>". UNITS ."&nbsp;</B></FONT></TD><TD><FONT SIZE=2><B>&nbsp;Description</B></FONT></TD></TR>";
		//CT restructured so its got a running total and structure like a bank statement
		$output = "
			<tr>
				<th>Date</th>
				<th>From</th>
				<th>To</th>
				<th>Category</th>
				<th>Description</th>
				<th class='units'>Amount</th>
			</tr>";
		
		if(empty($this->trade)) return $p->Wrap($output, "table", "tabulated");   // No trades yet, presumably
		
		$i=0;
		foreach($this->trade as $trade) {
/*
            // Ignore monthly fees.
            if ($trade->type == TRADE_MONTHLY_FEE or
                                    $trade->type == TRADE_MONTHLY_FEE_REVERSAL)
            {
                continue;
            }
*/
			$hname = "t{$trade->trade_id}";			
			$statusclass = "credit";
			$from = "<a href='trade_history.php?mode=other&member_id={$trade->member_id_from}#{$hname}'>{$trade->member_id_from}</a>";
			$to = "<a href='trade_history.php?mode=other&member_id={$trade->member_id_to}#{$hname}'>{$trade->member_id_to}</a>";
			$out = "{$trade->amount}";
			
			if($trade->type == TRADE_REVERSAL or $trade->status == TRADE_REVERSAL){
				$statusclass = "reversal";
			}
				
			
			//CT: use css styles not html colors - cleaner
			$rowclass = ($i % 2) ? "even" : "odd";	
			
			$trade_date = new cDateTime($trade->trade_date);
				
			
			$output .= "<tr class='{$rowclass} {$statusclass}' id='{$hname}'><td>{$trade_date->ShortDate()}</td><td>{$from}</td><td>{$to}</td><td>{$cDB->UnEscTxt($trade->category)}</td><td>{$cDB->UnEscTxt($trade->description)}</td><td class='units'>{$out}</td></tr>";
			$i+=1;
		}
		
		return $p->Wrap($output, "table", "tabulated");
	}	
	
	function DisplayTradeGroupUser($runningbalance=null) {
		global $cDB, $cUser, $p;
		
		//CT restructured so its got a running total and structure like a bank statement
		$output = "
			<tr>
				<th>Date</th>
				<th>Traded with</th>
				<th>Category</th>
				<th>Description</th>
				<th class='units'>In</th>
				<th class='units'>Out</th>";
		if(!empty($runningbalance)){
			$output .= "<th class='units balance'>Balance</th>";
		}
				
			$output .= "</tr>";
		
		if(empty($this->trade)) return $p->Wrap($output, "table", "tabulated");   // No trades yet, presumably
		
		$i=0;
		foreach($this->trade as $trade) {

			
/*
            // Ignore monthly fees.
            if ($trade->type == TRADE_MONTHLY_FEE or
                                    $trade->type == TRADE_MONTHLY_FEE_REVERSAL)
            {
                continue;
            }
*/			$hname = "t{$trade->trade_id}";			
            $currentbalance = number_format((float)$runningbalance, 2, '.', '');
			//echo($trade->member_id_to . " > " . $this->member_id . "<br />");
			if ($trade->member_id_to == $this->member_id)
			{
				if(!empty($runningbalance)){
					$runningbalance = $runningbalance - $trade->amount;
				}

				$statusclass = "credit";
				$tradewith = "<a href='trade_history.php?mode=other&member_id={$trade->member_id_from}#{$hname}'>{$trade->member_id_from}</a>";
				$out = "";
				$in = "{$trade->amount}";
			}
			else
			{				
				if(!empty($runningbalance)){
					$runningbalance = $runningbalance + $trade->amount;
				}
				$statusclass = "debit";
				$tradewith = "<a href='trade_history.php?mode=other&member_id={$trade->member_id_to}#{$hname}'>{$trade->member_id_to}</a>";
				$out = "{$trade->amount}";
				$in = "";
			}
			if($trade->type == TRADE_REVERSAL or $trade->status == TRADE_REVERSAL){
				$statusclass = "reversal";
			}
				
			
			//CT: use css styles not html colors - cleaner
			$rowclass = ($i % 2) ? "even" : "odd";	
			
			$trade_date = new cDateTime($trade->trade_date);				
			
			$output .= "<tr class='{$rowclass} {$statusclass}' id='{$hname}'><td>{$trade_date->ShortDate()}</td><td>{$tradewith}</td><td>{$cDB->UnEscTxt($trade->category)}</td><td>{$cDB->UnEscTxt($trade->description)}</td><td class='units'>{$in}</td><td class='units'>{$out}</td>";
			if(!empty($runningbalance)){
				$output .= "<td class='units balance'>{$currentbalance}</td></tr>";
			}
			
			$i+=1;
		}
		
		return $p->Wrap($output, "table", "tabulated");
	}
	
	function MakeTradeArray() {
		$trades = "";
		if($this->trade) {
			foreach($this->trade as $trade) {
				if($trade->type != "R" and $trade->status != "R") {
					$trades[$trade->trade_id] = "#". $trade->trade_id ." - ". $trade->amount ." ". UNITS . " FROM ". $trade->member_from->member_id ." TO ". $trade->member_id_to ." ON ". $trade->trade_date;
				}
			}
		}
		
		return $trades;
	}
}

class cTradeStats extends cTradeGroup {
	var $total_trades = 0;
	var $total_units = 0;
	var $most_recent = ""; // Will be an object of class cDateTime
	
	function cTradeStats ($member_id="%", $from_date=LONG_LONG_AGO, $to_date=FAR_FAR_AWAY) {
		$this->cTradeGroup($member_id, $from_date, $to_date);
		
		if(!$this->LoadTradeGroup())
			return;
		
		foreach($this->trade as $trade) {
			if ($trade->type == TRADE_REVERSAL or $trade->status == TRADE_REVERSAL)
				continue; // skip reversed trades
				
			$this->total_trades += 1;
			$this->total_units += $trade->amount;
			
			if($this->most_recent == "") {
				$this->most_recent = new cDateTime($trade->trade_date);
			} elseif ($this->most_recent->MySQLDate() < $trade->trade_date) {
				$this->most_recent->Set($trade->trade_date);
			}	
		}
	}

}
class cTradeStatsCT{
	//ct todo - make less hacky. as part of member result...
	var $total_trades;
	var $total_units;
	var $most_recent; //short date

	// bit messy in code - but v efficient. Does stats in one call, directy from the mysql
	function cTradeStatsCT ($member_id, $from_date=LONG_LONG_AGO, $to_date=FAR_FAR_AWAY) {
		global $cDB;
		$query = $cDB->Query("SELECT COUNT(trade_date), SUM(amount), DATE_FORMAT(trade_date, '".SHORT_DATE_FORMAT."') FROM (SELECT trade_date, amount FROM ".DATABASE_TRADES." WHERE (member_id_from LIKE ". $cDB->EscTxt($member_id) ." OR member_id_to LIKE ". $cDB->EscTxt($member_id) .") AND trade_date > ". $cDB->EscTxt($from_date) ." AND trade_date < ". $cDB->EscTxt($to_date) ." AND NOT type='R' AND NOT status='R' ORDER BY trade_date DESC) as t1;");
		if (!$query || mysqli_num_rows($query)<1) // None found = none pending!
			return;
		// loop - should just be once
		while($row = mysqli_fetch_array($query)) {
			$this->total_trades = $row[0];
			$this->total_units = $row[1];
			$this->most_recent = $row[2];
		}
	}

}

/*[chris] Trades Pending */
class cTradesPending {
	
	var $numPending = 0; // Total num trades pending
	
	var $numIn = 0; // Number of trades directed TO us that we must act on
	var $numOut = 0; // Number of trades sent FROM us that we are awaiting action on
	
	var $numToPay = 0; // Num Invoices we need to pay
	var $numToConfirm = 0; // Num payments we need to confirm
	var $numToBePayed = 0; // Num invoices awaiting payment on
	var $numToHaveConfirmed = 0; // Num payments awaiting confirmation on
	
	function cTradesPending($memberID) {
		
		global $cDB;
		
		// Get all trades involving this memberID that are currently marked as Open
		$query = $cDB->query("SELECT * from trades_pending where (member_id_to=".$cDB->EscTxt($memberID)." or
			member_id_from=".$cDB->EscTxt($memberID).") and status='O';");
			
		if (!$query || mysqli_num_rows($query)<1) // None found = none pending!
			return;
			
		$num_results = mysqli_num_rows($query);
		
		for ($i=0;$i<$num_results;$i++) {
			
			$row = mysqli_fetch_array($query);
		
			// Is this - An Invoice TO memberID that hasn't yet been acted on?
			if ($row["typ"]=="I" && $row["member_id_to"]==$memberID && $row["member_to_decision"]==1) {
		
				$this->numToPay += 1;
			}
			
			// Is this - A Payment TO memberID that hasn't yet been acted on?
			if ($row["typ"]=="T" && $row["member_id_to"]==$memberID && $row["member_to_decision"]==1) {
	
				$this->numToConfirm += 1;
			}
			
			// Is this - An Invoice FROM memberID that hasn't yet been acted on?
			if ($row["typ"]=="I" && $row["member_id_from"]==$memberID && $row["member_from_decision"]==1) {
			
				$this->numToBePayed += 1;
			
			}
			
			// Is this - An Payment FROM memberID that hasn't yet been acted on?
			if ($row["typ"]=="T" && $row["member_id_from"]==$memberID && $row["member_from_decision"]==1) {
				
				$this->numToHaveConfirmed += 1;
			}
			
		}
		
		$this->numIn = $this->numToPay + $this->numToConfirm;
		$this->numOut = $this->numToBePayed + $this->numToHaveConfirmed;
		$this->numPending = $this->numIn + $this->numOut;
	}
}

?>
