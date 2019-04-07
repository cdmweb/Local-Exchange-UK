<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.category.php");
include_once("class.feedback.php");

class cTrade {
	private $trade_id;
	private $trade_date;
	private $status;
	private $member_id_from;
	private $member_id_to;
	private $member_from;
	private $member_to;
	private $amount;
	private $category_id;		// CT - just the id.
	private $category_name;		// CT - just the description is fine here - 1 string.
	private $description;
	private $type;
	private $feedback;	// CT object
    /**
     * @return mixed
     */
    public function getTradeId()
    {
        return $this->trade_id;
    }

    /**
     * @param mixed $trade_id
     *
     * @return self
     */
    public function setTradeId($trade_id)
    {
        $this->trade_id = $trade_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTradeDate()
    {
        return $this->trade_date;
    }

    /**
     * @param mixed $trade_date
     *
     * @return self
     */
    public function setTradeDate($trade_date)
    {
        $this->trade_date = $trade_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberIdFrom()
    {
        return $this->member_id_from;
    }

    /**
     * @param mixed $member_id_from
     *
     * @return self
     */
    public function setMemberIdFrom($member_id_from)
    {
        $this->member_id_from = $member_id_from;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberIdTo()
    {
        return $this->member_id_to;
    }

    /**
     * @param mixed $member_id_to
     *
     * @return self
     */
    public function setMemberIdTo($member_id_to)
    {
        $this->member_id_to = $member_id_to;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberFrom()
    {
        return $this->member_from;
    }

    /**
     * @param mixed $member_from
     *
     * @return self
     */
    public function setMemberFrom($member_from)
    {
        $this->member_from = $member_from;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberTo()
    {
        return $this->member_to;
    }

    /**
     * @param mixed $member_to
     *
     * @return self
     */
    public function setMemberTo($member_to)
    {
        $this->member_to = $member_to;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     *
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        
        return $this->category_name;
    }

    /**
     * @param mixed $category_name
     *
     * @return self
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;

        return $this;
    }
       /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     *
     * @return self
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * @param mixed $feedback_rating
     *
     * @return self
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

	function __construct($variables=null) {
		if(!empty($variables)) {
			$this->ConstructTrade($variables);
		}
	}
	function ConstructTrade($variables) {	
		global $cErr;
		//$cErr->Error("row:"  . print_r($variables, true));
		if(!empty($variables['trade_status'])) $this->setStatus($variables['trade_status']);  // V for valid
		if(!empty($variables['trade_id'])) $this->setTradeId($variables['trade_id']);  // V for valid
		if(!empty($variables['trade_date'])) $this->setTradeDate($variables['trade_date']);  // V for valid
		if(!empty($variables['trade_amount'])) $this->setAmount($variables['trade_amount']);
		if(!empty($variables['trade_description'])) $this->setDescription($variables['trade_description']);
		if(!empty($variables['feedback_id'])) {
			$feedback = new cFeedback($variables);
			$this->setFeedback($feedback);
		}
		if(!empty($variables['trade_member_id_from'])) {
			$this->setMemberIdFrom($variables['trade_member_id_from']);
			//load nice names etc
			//$this->setMemberFrom = new;
		}
		if(!empty($variables['trade_member_id_to'])) {
			$this->setMemberIdTo($variables['trade_member_id_to']);
			//load nice names etc
			//$this->setMemberTo = new;

		}
		if(!empty($variables['trade_type'])) $this->setType($variables['trade_type']);
		if(!empty($variables['category_description'])) $this->setCategoryName($variables['category_description']);
		if(!empty($variables['category_id'])) $this->setCategoryId($variables['category_id']);
	}
	
	function ShowTrade() {
		global $cDB;
		
		$content = $this->trade_id .", ". $this->trade_date .", ". $this->status .", ". $this->member_from->getMemberId() .", ". $this->member_id_to .", ". $this->amount .", ". $this->category->id .", ". $this->description .", ". $this->type;
		
		return $content;
	}

	function SaveTrade() {  // This function should never be called directly
		global $cDB, $cErr;
		
		$insert = $cDB->Query("INSERT INTO ". DATABASE_TRADES ." (trade_date, status, member_id_from, member_id_to, amount, category, description, type) VALUES (now(), {$this->getStatus()}, {$this->getMemberIdTo()}, {$this->getMemberIdFrom()}, {$this->getAmount()}, {$this->getCategory()->getCategoryId()}, {$this->getDescription()}, {$this->getType()});");

		if(mysqli_affected_rows() == 1) {
		
			$this->setTradeId(mysqli_insert_id());	
			$query = $cDB->Query("SELECT trade_id, trade_date from ". DATABASE_TRADES ." WHERE trade_id=\"{$this->getTradeId()}\";");
			
	        while($values = $cDB->FetchArray($query)) // Each of our SQL results
	        {
	            $this->setTradeDate($values['trade_date']);	  
	            return true;       
	        }
		} 
		return false;
	}
	
	function LoadTrade($trade_id) {
		global $cDB, $cErr,  $site_settings;
		//CT - efficiency - combine db calls. categories, feedback 
		$query = $cDB->Query("SELECT 
			date_format(trade_date, '{$site_settings->getKey('SHORT_DATE')}') as trade_date, 
			t.status as trade_status, 
			t.trade_id as trade_id, 
			t.member_id_from as trade_member_id_from, 
			t.member_id_to as trade_member_id_to, 
			t.amount as trade_amount, 
			t.description as trade_description, 
			t.type as trade_type, 
			t.category as category_id, 
			c.description as category_description,
			f.feedback_id as feedback_id, 
			f.member_id_about as feedback_member_id_about, 
			f.comment as feedback_comment, 
			f.rating as feedback_rating 
			FROM ".DATABASE_TRADES." t 
			LEFT JOIN ". DATABASE_CATEGORIES . " c ON t.category = c.category_id
			LEFT JOIN ". DATABASE_FEEDBACK . " f ON t.trade_id = f.trade_id 
			WHERE trade_id={$cDB->EscTxt($trade_id)};");

		
		if($row = $cDB->FetchArray($query)) {		
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
			$cErr->Error("There was an error accessing the trades table.");
			//include("redirect.php");
		}				
	}

// 	//no need to call each line separately in a db call, you've got it! jsut used the array
// 	function ConstructTrade($array) {
// 		global $cDB, $cErr;
		
// 		//$query = $cDB->Query("SELECT date_format(trade_date,'%Y-%m-%d'), status, member_id_from, member_id_to, amount, description, type, category FROM ".DATABASE_TRADES." WHERE trade_id=". $cDB->EscTxt($trade_id) .";");
		
// 		//if($row = $cDB->FetchArray($query)) {		
// 			$this->trade_id = $array['trade_id'];
// 			$this->trade_date = $array['trade_date'];
// 			$this->status = $array['status'];
// 			//doesnt appear to be needed, only ids are listed.
// 			//$this->member_from = new cMember;
// 			//CT - todo - create v small class extension to stop loading EVERYTHING for one fraking line
// 			//$this->member_from->ConstructMember(array('member_id' => $array['member_id_from']));
// 			//$this->member_to = new cMember;
// 			//$this->member_from->ConstructMember(array('member_id' => $array['member_id_to']));
			
// 			//$this->member_id_from = $this->member_from->getMemberId();
// 			//$this->member_id_to = $this->member_to->getMemberId();
// 			$this->member_id_from = $array['member_id_from'];
// 			$this->member_id_to = $array['member_id_to'];
// 			$this->amount = $array['amount'];
// 			$this->description = $cDB->UnEscTxt($array['description']);
// 			$this->category = $cDB->UnEscTxt($array['category']);
// 			$this->type = $array['type'];
// 			//$this->category = new cCategory();
// 			//$this->category->LoadCategory($array['category']);
			
// /*			$feedback = new cFeedback;
// 			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_from->getMemberId());
// 			if($feedback_id) {
// 				$this->feedback_buyer = new cFeedback;
// 				$this->feedback_buyer->LoadFeedback($feedback_id);
// 			}
// 			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_to->getMemberId());
// 			if($feedback_id) {
// 				$this->feedback_seller = new cFeedback;
// 				$this->feedback_seller->LoadFeedback($feedback_id);
// 			}
// */			
					
// 	}

	// It is very important that this function prevent the database from going out balance.
	function MakeTrade($reversed_trade_id=null) { 
		global $cDB, $cErr;
		
		if ($this->getAmount() <= 0 and $this->getType() != TRADE_REVERSAL) // Amount should be positive unless
			return false;									 // this is a reversal of a previous trade.
			
		if ($this->getAmount() >= 0 and $this->getType() == TRADE_REVERSAL)	 // And likewise.
			return false;
			
		
		if ($this->getMemberIdFrom() == $this->getMemberIdTo())
			return false;		// don't allow trade to self
		
		if ($this->getMemberFrom()->getRestriction()==1) { // This member's account has been restricted - they are not allowed to make outgoing trades
			
			return false;
		}
	
		$balances = new cBalancesTotal;
	
		// TODO: At some point, we should handle out-of-balance problems without shutting 
		// down all trades.  But for now, seems like a wonderfully simple solution.	
		//
		// [chris] Have added a few more methods for dealing with out-of-balance scenarios (admin can set his/her preferred method in inc.config.php)	
		// CT - TODO put this in the db and elsewhere
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
			
			$success1 = $this->getMemberFrom()->UpdateBalance(-($this->amount));
			$success2 = $this->getMemberTo()->UpdateBalance($this->amount);
			
			if(LOG_LEVEL > 0 and $this->getType() != TRADE_ENTRY) {//Log if enabled & not an ordinary trade
				$log_entry = new cLogEntry (TRADE, $this->getType(), $this->getTradeId());
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
		$new_trade->setStatus("V");
		$new_trade->setMemberFrom($this->getMemberFrom());
		$new_trade->setMemberTo($this->getMemberTo());
		$new_trade->setAmount($this->getAmount());
		$new_trade->setCategory($this->getCategory());
		$string = "[Reversal of exchange #{$this->getTradeId()} from {$this->getTradeDate()} by admin {$cUser->getMemberId()}] {$description}";
		$new_trade->setDescription($string);
		$new_trade->setType("R");
		return $new_trade->MakeTrade($this->getTradeId());
	}
	
}

class cTradeGroup {
	private $trades;   	// an array of cTrade objects
	private $member_id;
	private $from_date;
	private $to_date;
	private $filter_type;
	//CT getters and setters
   public function getTrades()
    {
        return $this->trades;
    }

    /**
     * @param mixed $trades
     *
     * @return self
     */
    public function setTrades($trades)
    {
        $this->trades = $trades;

        return $this;
    }
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     *
     * @return self
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;

        return $this;
    }
    //
        public function getFromDate()
    {
        return $this->from_date;
    }

    /**
     * @param mixed $from_date
     *
     * @return self
     */
    public function setFromDate($from_date)
    {
        $this->from_date = $from_date;

        return $this;
    }
    //
        public function getToDate()
    {
        return $this->to_date;
    }

    /**
     * @param mixed $to_date
     *
     * @return self
     */
    public function setToDate($to_date)
    {
        $this->to_date = $to_date;

        return $this;
    }
    //
        public function getFilterype()
    {
        return $this->filter_type;
    }

    /**
     * @param mixed $filter_type
     *
     * @return self
     */
    public function setFilterType($filter_type)
    {
        $this->filter_type = $filter_type;

        return $this;
    }


	function __construct($member_id='%') {
		
		$this->constructTradeGroup($member_id);
		return $this;
	}

	function constructTradeGroup($member_id) {

		$this->setMemberId($member_id);

		//CT pass in - hack 
		$this->setFromDate(LONG_LONG_AGO);
		$this->setToDate(FAR_FAR_AWAY);
		$trades = array();
		//$this->setTrades($trades);
	}
	
	
	function LoadTradeGroup() {
		global $cDB, $cErr, $site_settings;
		
		$to_date = strtotime("+1 days", strtotime($this->getToDate()));
		
		$cErr->Error('load' . $this->getMemberId());
        // Ignore monthly fees.
       
        $trade_type = TRADE_MONTHLY_FEE;
        $trade_type_refund = TRADE_MONTHLY_FEE_REVERSAL;


		$gf = (SHOW_GLOBAL_FEES !=true) ? "AND type !='S' AND type != '{$trade_type}' AND type != '{$trade_type_refund}'" : "";
       	
		//$query = $cDB->Query("SELECT date_format(trade_date,'%Y-%m-%d') as trade_date, status, trade_id, member_id_from, member_id_to, amount, t.description as description, type, c.description as category FROM ".DATABASE_TRADES." t left join categories c on t.category = c.category_id WHERE (member_id_from LIKE ". $cDB->EscTxt($this->member_id) ." OR member_id_to LIKE ". $cDB->EscTxt($this->member_id) .") AND trade_date > ". $cDB->EscTxt($this->from_date) ." AND trade_date < ". $cDB->EscTxt(date("Ymd", $to_date)) ." {$gf} ORDER BY trade_date DESC;");

		$query = $cDB->Query("SELECT 
			date_format(trade_date, '{$site_settings->getKey('SHORT_DATE')}') as trade_date, 
			t.status as trade_status, 
			t.trade_id as trade_id, 
			t.member_id_from as trade_member_id_from, 
			t.member_id_to as trade_member_id_to, 
			t.amount as trade_amount, 
			t.description as trade_description, 
			t.type as trade_type, 
			t.category as category_id, 
			c.description as category_description,
			f.feedback_id as feedback_id, 
			f.member_id_about as feedback_member_id_about, 
			f.comment as feedback_comment, 
			f.rating as feedback_rating 
			FROM ".DATABASE_TRADES." t 
			LEFT JOIN ".DATABASE_CATEGORIES." c ON t.category = c.category_id
			LEFT JOIN ".DATABASE_FEEDBACK." f ON t.trade_id = f.trade_id 
			WHERE (member_id_from LIKE \"{$this->getMemberId()}\" OR member_id_to LIKE \"{$this->getMemberId()}\") 
			{$gf} 
			ORDER BY t.trade_date DESC;");

    	$trades = array();
		// instantiate new cTrade objects and load them
		while($row = $cDB->FetchArray($query)) // Each of our SQL results
		{
			//echo $row['balance'];
			$trade = new cTrade;	
			$trade->ConstructTrade($row); 
			$trades[] = $trade;	
		}
		$this->setTrades($trades);
		//$cErr->Error(print_r($this->getTrades(), true));
		if(sizeof($trades) > 0) return true;
		// if no trades
		return false;
	}
	
	// function DisplayTradeGroup() {
	// 	global $cDB, $cUser, $p;
		
	// 	//$output = "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH=\"100%\"><TR BGCOLOR=\"#d8dbea\"><TD><FONT SIZE=2><B>Date</B></FONT></TD><TD><FONT SIZE=2><B>From</B></FONT></TD><TD><FONT SIZE=2><B>To</B></FONT></TD><TD ALIGN=RIGHT><FONT SIZE=2><B>". UNITS ."&nbsp;</B></FONT></TD><TD><FONT SIZE=2><B>&nbsp;Description</B></FONT></TD></TR>";
	// 	//CT restructured so its got a running total and structure like a bank statement
	// 	$output = "
	// 		<tr>
	// 			<th>Date</th>
	// 			<th>From</th>
	// 			<th>To</th>
	// 			<th>Category</th>
	// 			<th>Description</th>
	// 			<th class='units'>Amount</th>
	// 		</tr>";
		
	// 	if(empty($this->trades)) return $p->Wrap('No trades?' . $output, "table", "tabulated");   // No trades yet, presumably
		
	// 	$i=0;
	// 	foreach($this->trades as $trade) {

 //            // Ignore monthly fees.
 //            if ($trade->type == TRADE_MONTHLY_FEE or
 //                                    $trade->type == TRADE_MONTHLY_FEE_REVERSAL)
 //            {
 //                continue;
 //            }

	// 		$hname = "t{$trade->trade_id}";			
	// 		$statusclass = "credit";
	// 		$from = "<a href='trade_history.php?mode=other&member_id={$trade->member_id_from}#{$hname}'>{$trade->member_id_from}</a>";
	// 		$to = "<a href='trade_history.php?mode=other&member_id={$trade->member_id_to}#{$hname}'>{$trade->member_id_to}</a>";
	// 		$out = "{$trade->amount}";
			
	// 		if($trade->type == TRADE_REVERSAL or $trade->status == TRADE_REVERSAL){
	// 			$statusclass = "reversal";
	// 		}
				
			
	// 		//CT: use css styles not html colors - cleaner
	// 		$rowclass = ($i % 2) ? "even" : "odd";	
			
	// 		$trade_date = new cDateTime($trade->trade_date);
				
			
	// 		$output .= "<tr class='{$rowclass} {$statusclass}' id='{$hname}'><td>{$trade_date->ShortDate()}</td><td>{$from}</td><td>{$to}</td><td>{$cDB->UnEscTxt($trade->category)}</td><td>{$cDB->UnEscTxt($trade->description)}</td><td class='units'>{$out}</td></tr>";
	// 		$i+=1;
	// 	}
		
	// 	return $p->Wrap($output, "table", "tabulated");
	// }	
	
	function DisplayTradeGroup($runningbalance=null) {
		global $cDB, $cUser, $p;
		
		//CT restructured so its got a running total and structure like a bank statement
		$output = "
			<tr>
				<th>Date</th>
				<th>From</th>
				<th>To</th>
				<th>Category</th>
				<th>Description</th>
				<th class='units'>Out</th>
				<th class='units'>In</th>
				";

		if(!empty($runningbalance)){
			$output .= "<th class='units balance'>Balance</th>";
		}
				
			$output .= "</tr>";
		
		if(empty($this->trades)) return $p->Wrap('No trades?' . $output, "table", "tabulated");   // No trades yet, presumably
		
		$i=0;
		foreach($this->getTrades() as $trade) {

			$hname = "t{$trade->getTradeId()}";			
            $currentbalance = number_format((float)$runningbalance, 2, '.', '');
			//echo($trade->member_id_to . " > " . $this->member_id . "<br />");

            //$feedback_link = (!empty($trade->getFeedback())) ? "<div class='trade-feedback'><a href=\"feedback_all.php?member_id={$trade->getMemberIdTo()}#f{$trade->getFeedback()->getFeedbackId()}\"> {$trade->getFeedback()->showRatingAsStars()} &quot;{$trade->getFeedback()->getComment()}&quot; </a></div>" : "";
            $feedback_link = (!empty($trade->getFeedback())) ? "<div class='trade-feedback'>{$trade->getFeedback()->showRatingAsStars()} &quot;{$trade->getFeedback()->getComment()}&quot; </div>" : "";
			if ($trade->getMemberIdTo() == $this->getMemberId())
			{
				if(!empty($runningbalance)){
					$runningbalance = $runningbalance - $trade->getAmount();
				}

				$statusclass = "credit";
				$traded_from = "<a href='member_summary.php?member_id={$trade->getMemberIdFrom()}#{$hname}'>{$trade->getMemberIdFrom()}</a>";
				$traded_to = "{$trade->getMemberIdTo()}";
				$out = "";
				$in = "{$trade->getAmount()}";
			}
			else
			{				
				if(!empty($runningbalance)){
					$runningbalance = $runningbalance + $trade->getAmount();
				}
				$statusclass = "debit";
				$traded_from = "{$trade->getMemberIdFrom()}";
				$traded_to = "<a href='member_summary.php?member_id={$trade->getMemberIdTo()}#{$hname}'>{$trade->getMemberIdTo()}</a>";
				$out = "{$trade->getAmount()}";
				$in = "";
			}
			if($trade->getStatus() == TRADE_REVERSAL){
				$statusclass = "reversal";
			}
				
			
			//CT: use css styles not html colors - cleaner
			$rowclass = ($i % 2) ? "even" : "odd";	
			
			//CT todo: format
			$trade_date = $trade->getTradeDate();				
			$rb = (!empty($runningbalance)) ? "<td class='units balance'>{$currentbalance}</td>" : "";

			$output .= "<tr class='{$rowclass} {$statusclass}' id='{$hname}'>
				<td>{$trade_date}</td>
				<td>{$traded_from}</td>
				<td>{$traded_to}</td>
				<td>{$trade->getCategoryName()}</td>
				<td><span class=\"trade_id\">#{$trade->getTradeId()}</span>{$trade->getDescription()}{$feedback_link}</td>
				<td class='units'>{$out}</td>
				<td class='units'>{$in}</td>
				{$rb}</tr>
				";
				
			
			
			$i++;
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

// class cTradeStats extends cTradeGroup {
// 	private $total_trades;
// 	private $total_units;
// 	private $most_recent; // Will be an object of class cDateTime
	

// 	/**
//      * @return mixed
//      */
//     public function getTotalTrades()
//     {
//         return $this->total_trades;
//     }

//     /**
//      * @param mixed $total_trades
//      *
//      * @return self
//      */
//     public function setTotalTrades($total_trades)
//     {
//         $this->total_trades = $total_trades;

//         return $this;
//     }
// 	/**
//      * @return mixed
//      */
//     public function getTotalUnits()
//     {
//         return $this->total_units;
//     }

//     /**
//      * @param mixed $total_units
//      *
//      * @return self
//      */
//     public function setTotalUnits($total_units)
//     {
//         $this->total_units = $total_units;

//         return $this;
//     }	
//     /**
//      * @return mixed
//      */
//     public function getMostRecent()
//     {
//         return $this->most_recent;
//     }

//     /**
//      * @param mixed $most_recent
//      *
//      * @return self
//      */
//     public function setMostRecent($most_recent)
//     {
//         $this->most_recent = $most_recent;

//         return $this;
//     }





// 	function  __construct ($member_id="%", $from_date=LONG_LONG_AGO, $to_date=FAR_FAR_AWAY) {
// 		$this->cTradeGroup($member_id, $from_date, $to_date);
		
// 		if(!$this->LoadTradeGroup())
// 			return;
		
// 		$this->setTotalTrades(0);
// 		$i = 0;

// 		foreach($this->trade as $trade) {
// 			if ($trade->getType() == TRADE_REVERSAL or $trade->getStatus() == TRADE_REVERSAL)
// 				continue; // skip reversed trades
				
// 			$this->total_trades += 1;
// 			$this->total_units += $trade->amount;
			
// 			if($this->most_recent == "") {
// 				$this->most_recent = new cDateTime($trade->trade_date);
// 			} elseif ($this->most_recent->MySQLDate() < $trade->trade_date) {
// 				$this->most_recent->Set($trade->trade_date);
// 			}	
// 		}
// 	}

// }
class cTradeStatsCT{
	//CT rebuilt
	private $member_id;
	private $trade_total_count;
	private $trade_total_amount;
	private $trade_date_last; //short date

    /**
     * @param mixed $member_id
     *
     * @return self
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }
    /**
     * @param mixed $total_trades
     *
     * @return self
     */
    public function setTradeTotalCount($trade_total_count)
    {
        $this->trade_total_count = $trade_total_count;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTradeTotalCount()
    {
        return $this->trade_total_count;
    }

    /**
     * @param mixed $total_trades
     *
     * @return self
     */
    public function setTradeTotalAmount($trade_total_amount)
    {
        $this->trade_total_amount = $trade_total_amount;

        return $this;
    }
        /**
     * @return mixed
     */
    public function getTradeTotalAmount()
    {
        return $this->trade_total_amount;
    }


    /**
     * @param mixed $most_recent
     *
     * @return self
     */
    public function setTradeLastDate($trade_last_date)
    {
        $this->trade_last_date = $trade_last_date;

        return $this;
    }


        /**
     * @return mixed
     */
    public function getTradeLastDate()
    {
        return $this->trade_last_date;
    }

	//
	function  __construct ($member_id) {
		//global $cDB, $site_settings;
		$this->setMemberId($member_id);
		//$this->Load($member_id);
	}

	public function Load($member_id){
		global $cDB, $site_settings;
		$query = $cDB->Query("SELECT 
			COUNT(trade_date) as trade_total_count, 
			SUM(amount) as trade_total_amount, 
			date_format((select trade_date 
				from ".DATABASE_TRADES." 
				WHERE member_id_from LIKE \"{$member_id}\" OR member_id_to LIKE \"{$member_id}\" AND NOT type=\"R\" AND NOT status=\"R\" ORDER BY trade_date DESC LIMIT 1), '{$site_settings->getKey('SHORT_DATE')}') as trade_last_date 
			FROM ".DATABASE_TRADES." t
			WHERE member_id_from LIKE \"{$member_id}\" OR member_id_to LIKE \"{$member_id}\" AND NOT type=\"R\" AND NOT status=\"R\";
		");
		while($row = $cDB->FetchArray($query)) {
			$this->Build($row);
			return true;

		}
		return false;
	}
	public function Build($variables){
			$this->setTradeTotalCount($variables['trade_total_count']);
			$this->setTradeTotalAmount($variables['trade_total_amount']);
			$this->setTradeLastDate($variables['trade_last_date']);
	}

}

/*CT write this for php7 */
class cTradesPending {
	
	var $numPending = 0; // Total num trades pending
	
	var $numIn = 0; // Number of trades directed TO us that we must act on
	var $numOut = 0; // Number of trades sent FROM us that we are awaiting action on
	
	var $numToPay = 0; // Num Invoices we need to pay
	var $numToConfirm = 0; // Num payments we need to confirm
	var $numToBePayed = 0; // Num invoices awaiting payment on
	var $numToHaveConfirmed = 0; // Num payments awaiting confirmation on
	
	function  __construct($memberID) {
		
		global $cDB;
		
		// Get all trades involving this memberID that are currently marked as Open
		$query = $cDB->query("SELECT * from trades_pending where (member_id_to=".$cDB->EscTxt($memberID)." or
			member_id_from=".$cDB->EscTxt($memberID).") and status='O';");
			
		if (!$query || mysqli_num_rows($query)<1) // None found = none pending!
			return;
			
		$num_results = mysqli_num_rows($query);
		
		for ($i=0;$i<$num_results;$i++) {
			
			$row = $cDB->FetchArray($query);
		
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



    // /**
    //  * @param mixed $trade_id
    //  *
    //  * @return self
    //  */
    // public function setTradeId($trade_id)
    // {
    //     $this->trade_id = $trade_id;

    //     return $this;
    // }

    // /**
    //  * @param mixed $trade_date
    //  *
    //  * @return self
    //  */
    // public function setTradeDate($trade_date)
    // {
    //     $this->trade_date = $trade_date;

    //     return $this;
    // }

    // /**
    //  * @param mixed $status
    //  *
    //  * @return self
    //  */
    // public function setStatus($status)
    // {
    //     $this->status = $status;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_id_from
    //  *
    //  * @return self
    //  */
    // public function setMemberIdFrom($member_id_from)
    // {
    //     $this->member_id_from = $member_id_from;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_id_to
    //  *
    //  * @return self
    //  */
    // public function setMemberIdTo($member_id_to)
    // {
    //     $this->member_id_to = $member_id_to;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_from
    //  *
    //  * @return self
    //  */
    // public function setMemberFrom($member_from)
    // {
    //     $this->member_from = $member_from;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_to
    //  *
    //  * @return self
    //  */
    // public function setMemberTo($member_to)
    // {
    //     $this->member_to = $member_to;

    //     return $this;
    // }

    // /**
    //  * @param mixed $amount
    //  *
    //  * @return self
    //  */
    // public function setAmount($amount)
    // {
    //     $this->amount = $amount;

    //     return $this;
    // }

    // /**
    //  * @param mixed $category
    //  *
    //  * @return self
    //  */
    // public function setCategory($category)
    // {
    //     $this->category = $category;

    //     return $this;
    // }

    // /**
    //  * @param mixed $description
    //  *
    //  * @return self
    //  */
    // public function setDescription($description)
    // {
    //     $this->description = $description;

    //     return $this;
    // }

    // /**
    //  * @param mixed $type
    //  *
    //  * @return self
    //  */
    // public function setType($type)
    // {
    //     $this->type = $type;

    //     return $this;
    // }

    // /**
    //  * @param mixed $feedback_buyer
    //  *
    //  * @return self
    //  */
    // public function setFeedbackBuyer($feedback_buyer)
    // {
    //     $this->feedback_buyer = $feedback_buyer;

    //     return $this;
    // }

    // /**
    //  * @param mixed $feedback_seller
    //  *
    //  * @return self
    //  */
    // public function setFeedbackSeller($feedback_seller)
    // {
    //     $this->feedback_seller = $feedback_seller;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getTrades()
    // {
    //     return $this->trades;
    // }

    // /**
    //  * @param mixed $trades
    //  *
    //  * @return self
    //  */
    // public function setTrades($trades)
    // {
    //     $this->trades = $trades;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getMemberId()
    // {
    //     return $this->member_id;
    // }

    // /**
    //  * @param mixed $member_id
    //  *
    //  * @return self
    //  */
    // public function setMemberId($member_id)
    // {
    //     $this->member_id = $member_id;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getFromDate()
    // {
    //     return $this->from_date;
    // }

    // /**
    //  * @param mixed $from_date
    //  *
    //  * @return self
    //  */
    // public function setFromDate($from_date)
    // {
    //     $this->from_date = $from_date;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getToDate()
    // {
    //     return $this->to_date;
    // }

    // /**
    //  * @param mixed $to_date
    //  *
    //  * @return self
    //  */
    // public function setToDate($to_date)
    // {
    //     $this->to_date = $to_date;

    //     return $this;
    // }

    // /**
    //  * @return mixed
    //  */
    // public function getFilterType()
    // {
    //     return $this->filter_type;
    // }

    // /**
    //  * @param mixed $filter_type
    //  *
    //  * @return self
    //  */
    // public function setFilterType($filter_type)
    // {
    //     $this->filter_type = $filter_type;

    //     return $this;
    // }

    // /**
    //  * @param mixed $total_trades
    //  *
    //  * @return self
    //  */
    // public function setTotalTrades($total_trades)
    // {
    //     $this->total_trades = $total_trades;

    //     return $this;
    // }

    // /**
    //  * @param mixed $total_units
    //  *
    //  * @return self
    //  */
    // public function setTotalUnits($total_units)
    // {
    //     $this->total_units = $total_units;

    //     return $this;
    // }

    // /**
    //  * @param mixed $most_recent
    //  *
    //  * @return self
    //  */
    // public function setMostRecent($most_recent)
    // {
    //     $this->most_recent = $most_recent;

    //     return $this;
    // }

    // /**
    //  * @param mixed $trade_id
    //  *
    //  * @return self
    //  */
    // public function setTradeId($trade_id)
    // {
    //     $this->trade_id = $trade_id;

    //     return $this;
    // }

    // /**
    //  * @param mixed $trade_date
    //  *
    //  * @return self
    //  */
    // public function setTradeDate($trade_date)
    // {
    //     $this->trade_date = $trade_date;

    //     return $this;
    // }

    // /**
    //  * @param mixed $status
    //  *
    //  * @return self
    //  */
    // public function setStatus($status)
    // {
    //     $this->status = $status;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_id_from
    //  *
    //  * @return self
    //  */
    // public function setMemberIdFrom($member_id_from)
    // {
    //     $this->member_id_from = $member_id_from;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_id_to
    //  *
    //  * @return self
    //  */
    // public function setMemberIdTo($member_id_to)
    // {
    //     $this->member_id_to = $member_id_to;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_from
    //  *
    //  * @return self
    //  */
    // public function setMemberFrom($member_from)
    // {
    //     $this->member_from = $member_from;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_to
    //  *
    //  * @return self
    //  */
    // public function setMemberTo($member_to)
    // {
    //     $this->member_to = $member_to;

    //     return $this;
    // }

    // /**
    //  * @param mixed $amount
    //  *
    //  * @return self
    //  */
    // public function setAmount($amount)
    // {
    //     $this->amount = $amount;

    //     return $this;
    // }

    // /**
    //  * @param mixed $category_id
    //  *
    //  * @return self
    //  */
    // public function setCategoryId($category_id)
    // {
    //     $this->category_id = $category_id;

    //     return $this;
    // }

    // /**
    //  * @param mixed $category_name
    //  *
    //  * @return self
    //  */
    // public function setCategoryName($category_name)
    // {
    //     $this->category_name = $category_name;

    //     return $this;
    // }

    // /**
    //  * @param mixed $description
    //  *
    //  * @return self
    //  */
    // public function setDescription($description)
    // {
    //     $this->description = $description;

    //     return $this;
    // }

    // /**
    //  * @param mixed $type
    //  *
    //  * @return self
    //  */
    // public function setType($type)
    // {
    //     $this->type = $type;

    //     return $this;
    // }

    // /**
    //  * @param mixed $feedback_rating
    //  *
    //  * @return self
    //  */
    // public function setFeedbackRating($feedback_rating)
    // {
    //     $this->feedback_rating = $feedback_rating;

    //     return $this;
    // }

    // /**
    //  * @param mixed $trades
    //  *
    //  * @return self
    //  */
    // public function setTrades($trades)
    // {
    //     $this->trades = $trades;

    //     return $this;
    // }

    // /**
    //  * @param mixed $member_id
    //  *
    //  * @return self
    //  */
    // public function setMemberId($member_id)
    // {
    //     $this->member_id = $member_id;

    //     return $this;
    // }

    // /**
    //  * @param mixed $from_date
    //  *
    //  * @return self
    //  */
    // public function setFromDate($from_date)
    // {
    //     $this->from_date = $from_date;

    //     return $this;
    // }

    // /**
    //  * @param mixed $to_date
    //  *
    //  * @return self
    //  */
    // public function setToDate($to_date)
    // {
    //     $this->to_date = $to_date;

    //     return $this;
    // }

    // /**
    //  * @param mixed $filter_type
    //  *
    //  * @return self
    //  */
    // public function setFilterType($filter_type)
    // {
    //     $this->filter_type = $filter_type;

    //     return $this;
    // }

    // /**
    //  * @param mixed $total_trades
    //  *
    //  * @return self
    //  */
    // public function setTotalTrades($total_trades)
    // {
    //     $this->total_trades = $total_trades;

    //     return $this;
    // }

    // /**
    //  * @param mixed $total_units
    //  *
    //  * @return self
    //  */
    // public function setTotalUnits($total_units)
    // {
    //     $this->total_units = $total_units;

    //     return $this;
    // }

    // /**
    //  * @param mixed $most_recent
    //  *
    //  * @return self
    //  */
    // public function setMostRecent($most_recent)
    // {
    //     $this->most_recent = $most_recent;

    //     return $this;
    // }
}

?>
