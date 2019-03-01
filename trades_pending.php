<?
include_once("includes/inc.global.php");
include_once("classes/class.datetime.php");
include("classes/class.trade.php");

/*
 An explanation of different member_decisions statuses in the trades_pending database...
 
 1 = Member hasn't made a decision regarding this trade - either it is Open or it has been Fulfilled (see 'status' column)
 2 = Member has removed this trade from his own records
 3 = Member has rejected this trade
 4 = Member has accepted that this trade has been rejected
 
*/
$p->site_section = EXCHANGES;
//CT allow admins to see this page
$mode = (isset($_REQUEST["mode"]) && $_REQUEST["mode"] == "admin") ? "admin" : "self";  // Administrator is editing a member's account

if($mode == "admin"){
	$cUser->MustBeLevel(2);	
	$member_id = $_REQUEST["member_id"];
	$p->page_title = "Exchanges Pending for #" . $_REQUEST["member_id"];
	//$form->addElement("header", null, "Edit Member " . $member->getAllNames());
}
else{
	$cUser->MustBeLoggedOn();
	$member_id = $_SESSION["user_login"];
	$p->page_title = "Exchanges Pending";
}

$pending = new cTradesPending($member_id);

$list = "<em>NOTE that only transactions currently pending approval from one member or the other are displayed here. To view your complete
	Exchange History please <a href=trade_history.php?mode=self>click here</a>.</em><p><a href='trades_pending.php?mode={$mode}&member_id={$member_id}'>Summary</a>

| <a href='trades_pending.php?action=incoming&mode={$mode}&member_id={$member_id}'>Payments to Confirm ({$pending->numToConfirm})</a>";

if (MEMBERS_CAN_INVOICE==true) // No point displaying invoice stats if invoicing has been disabled
	$list .= " | <a href='trades_pending.php?action=outgoing&mode={$mode}&member_id={$member_id}'>Invoices to pay ({$pending->numToPay})</a>";

$list .= " | <a href='trades_pending.php?action=payments_sent&mode={$mode}&member_id={$member_id}'>Sent Payments ({$pending->numToHaveConfirmed})</a>";

if (MEMBERS_CAN_INVOICE==true) // ditto
	$list .= "| <a href='trades_pending.php?action=invoices_sent&mode={$mode}&member_id={$member_id}'>Sent Invoices ({$pending->numToBePayed})</a>";

$list = $p->Wrap($list, "p", "quickmenu");
function initTradeTable() {
	
	$output = "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3 WIDTH=\"100%\"><TR BGCOLOR=\"#d8dbea\"><TD><FONT SIZE=2><B>Date</B></FONT></TD><TD><FONT SIZE=2><B>From</B></FONT></TD><TD><FONT SIZE=2><B>To</B></FONT></TD><TD ALIGN=RIGHT><FONT SIZE=2><B>". UNITS ."&nbsp;</B></FONT></TD><TD><FONT SIZE=2><B>&nbsp;Description</B></FONT></TD><td><font size=2><b>Action</td></font></td></TR>";
	
	return $output;
}

function closeTradeTable() {
	
	return "</table>";
}

function displayTrade($t,$typ) {
		
		global $cDB, $mode, $member_id;
		
		$fcolor = "#554f4f";
		//$fcolor = '#FFFFFF';
		
		if ($t["status"]=='O') {
		
			$bgcolor = "white";
			
			if ($typ=='P')
				//$actionTxt = '';
				$actionTxt = "<a href=trades_pending.php?action=confirm&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">Accept Payment</a> | <a href=trades_pending.php?action=reject&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">Reject</a>";
			else if ($typ=='I')
//				$actionTxt = '';
				$actionTxt = "<a href=trades_pending.php?action=confirm&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">Pay Invoice</a> | 
					<a href=trades_pending.php?action=reject&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">Reject</a>";
			else if ($typ=='TBC') {
				
				if ($t["member_to_decision"]==3) {
					$bgcolor = 'red';
					$actionTxt = "<font size=2 color=\"".$fcolor."\">".$t["member_id_to"]." has rejected this transaction. <br>
					 <a href=trades_pending.php?action=resend&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">[ Resend Payment ]</a> | 
					<a href=trades_pending.php?action=accept_rejection&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">[ Remove this Notice ]</a></font>";
				}
				else
					$actionTxt = "<font size=2 color=\"".$fcolor."\">Awaiting Confirmation by ".$t["member_id_to"]."...</font>";
			}
			else if ($typ=='TBP') {
				
				if ($t["member_to_decision"]==3) {
					$bgcolor = 'red';
					$actionTxt = "<font size=2 color=\"".$fcolor."\">".$t["member_id_to"]." has rejected this transaction. <br>
					<a href=trades_pending.php?action=resend&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">[ Resend Invoice ]</a> | 
					<a href=trades_pending.php?action=accept_rejection&mode={$mode}&member_id={$member_id}&tid=".$t["id"].">[ Remove this Notice ]</a></font>";
				}
				else
					$actionTxt = "<font size=2 color=\"".$fcolor."\">Awaiting Payment from ".$t["member_id_to"]."...</font>";
			}
		}
		else {
			$bgcolor = "green";
			$fcolor = "#ffffff";
			
			if ($typ=='P')
				$actionTxt = "<font size=2 color=\"".$fcolor."\">Payment Accepted!</font>";
			else if ($typ=='I')
				$actionTxt = "<font size=2 color=\"".$fcolor."\">Invoice Paid!</font>";
			else if ($typ=='TBC')
				$actionTxt = "<font size=2 color=\"".$fcolor."\">".$t["member_id_to"]." has confirmed!</font>";
			else if ($typ=='TBP')
				$actionTxt = "<font size=2 color=\"".$fcolor."\">".$t["member_id_to"]." has paid this invoice!</font>";
			
			$actionTxt .= " <font color=\"".$fcolor."\">--</font> <a href=trades_pending.php?&mode={$mode}&member_id={$member_id}&action=remove&tid=".$t["id"]."><font size=2 color=\"".$fcolor."\">[ Remove this Notice ]</font></a>";
		}
			
		if ($typ=='P')
			$output .= "<TR VALIGN=TOP BGCOLOR=". $bgcolor ."><TD><FONT SIZE=2 COLOR=".$fcolor.">". $t["trade_date"]."</FONT></TD><TD><FONT SIZE=2 		COLOR=".$fcolor.">". $t["member_id_from"] ."</FONT></TD><TD><FONT SIZE=2 COLOR=".$fcolor.">".$t["member_id_to"]."</FONT></TD><TD ALIGN=RIGHT><FONT SIZE=2 COLOR=".$fcolor.">". $t["amount"] ."&nbsp;</FONT></TD><TD><FONT SIZE=2 COLOR=".$fcolor.">". $cDB->UnEscTxt($t["description"]) ."</FONT></TD>
				<td>$actionTxt</td>
				</TR>";
		else
				$output .= "<TR VALIGN=TOP BGCOLOR=". $bgcolor ."><TD><FONT SIZE=2 COLOR=".$fcolor.">". $t["trade_date"]."</FONT></TD><TD><FONT SIZE=2 		COLOR=".$fcolor.">". $t["member_id_from"] ."</FONT></TD><TD><FONT SIZE=2 COLOR=".$fcolor.">".$t["member_id_to"]."</FONT></TD><TD ALIGN=RIGHT><FONT SIZE=2 COLOR=".$fcolor.">". $t["amount"] ."&nbsp;</FONT></TD><TD><FONT SIZE=2 COLOR=".$fcolor.">". $cDB->UnEscTxt($t["description"]) ."</FONT></TD>
				<td>$actionTxt</td>
				</TR>";
				
		return $output;
}

function doTrade($t) {
	global $member_id;
	$member_to = new cMember;
	
	if ($t["typ"]=='T')
		$member_to->LoadMember($member_id);
	else
		$member_to->LoadMember($t["member_id_from"]);
		
	$member = new cMember;
	
	if ($t["typ"]=='T')
		$member->LoadMember($t["member_id_from"]);
	else
		$member->LoadMember($member_id);
		
	$trade = new cTrade($member, $member_to, htmlspecialchars($t['amount']), htmlspecialchars($t['category']), 		htmlspecialchars($t['description']), 
		"T");
	
	$status = $trade->MakeTrade();
	
	if(!$status)
		return false;
	else {
		
			// Has the recipient got an income tie set-up? If so, we need to transfer a percentage of this elsewhere...
		
			$recipTie = cIncomeTies::getTie($member_to->member_id);
			
			if ($recipTie) {
				
				$theAmount = round(($t['amount']*$recipTie->percent)/100);
				
				$charity_to = new cMember;
				$charity_to->LoadMember($recipTie->tie_id);
	
				$trade = new cTrade($member_to, $charity_to, htmlspecialchars($theAmount), htmlspecialchars(12), htmlspecialchars("Donation from ".$member_to->member_id.""), 'T');
		
				$status = $trade->MakeTrade();
			}
			
		return true;
	}
}

switch($_REQUEST["action"]) {
	
	case("resend"): 
	
		$q = "SELECT * FROM trades_pending where id=".$cDB->EscTxt($_GET["tid"])." limit 0,1";
	
		$result = $cDB->Query($q);
		
		if ($result && mysqli_num_rows($result)>0) { // Trade Exists
			
			$row = mysqli_fetch_array($result);
			
			// Do we have permission to act on this trade?
			if ($row["member_id_from"]!=$member_id) {
				
				$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
				
				break;
			}
			
			// Check this is not a 'still Open' trade
			if ($row["status"]!='O') {
				
				$list .= "<em>Sorry, only Open trades can be rejected or resent.</em>";
				break;
			}
			
			if ($row["member_to_decision"]!=3) {
				
				$list .= "<em>This member hasn't rejected this transaction!</em>";
				break;
			}
			
			$q = "UPDATE trades_pending set member_to_decision=1 where id=".$cDB->EscTxt($row["id"])."";
			
			if ($cDB->Query($q))
				$list .= "Transaction re-submitted successfully.";
			else
				$list .= "<em>Error updating the database.</em>";
		}
		else
			$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
		
	break;
		
	case("accept_rejection"): 
	
		$q = "SELECT * FROM trades_pending where id=".$cDB->EscTxt($_GET["tid"])." limit 0,1";
	
		$result = $cDB->Query($q);
		
		if ($result && mysqli_num_rows($result)>0) { // Trade Exists
			
			$row = mysqli_fetch_array($result);
			
			// Do we have permission to act on this trade?
			if ($row["member_id_from"]!=$member_id) {
				
				$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
				
				break;
			}
			
			// Check this is not a 'still Open' trade
			if ($row["status"]!='O') {
				
				$list .= "<em>Sorry, only Open trades can be rejected.</em>";
				break;
			}
			
			if ($row["member_to_decision"]!=3) {
				
				$list .= "<em>This member hasn't rejected this transaction!</em>";
				break;
			}
			
			$q = "UPDATE trades_pending set member_from_decision=4 where id=".$cDB->EscTxt($row["id"])."";
			
			if ($cDB->Query($q))
				$list .= "Transaction removed successfully.";
			else
				$list .= "<em>Error updating the database.</em>";
		}
		else
			$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
		
	break;
	
	case("reject"): 
	
		$q = "SELECT * FROM trades_pending where id=".$cDB->EscTxt($_GET["tid"])." limit 0,1";
	
		$result = $cDB->Query($q);
		
		if ($result && mysqli_num_rows($result)>0) { // Trade Exists
			
			$row = mysqli_fetch_array($result);
			
			// Do we have permission to act on this trade?
			if ($row["member_id_to"]!=$member_id) {
				
				$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
				
				break;
			}
			
			// Check this is not a 'still Open' trade
			if ($row["status"]!='O') {
				
				$list .= "<em>This trade is no longer Open and therefore cannot be rejected.</em>";
				break;
			}
			
			if ($row["typ"]=='T' && $row["member_id_to"]==$member_id) { // We want to reject the payment!
				
				$q = "UPDATE trades_pending set member_to_decision=3 where id=".$cDB->EscTxt($row["id"])."";
			}
	
			else if ($row["typ"]=='I' && $row["member_id_to"]==$member_id) { // We don't want to pay this invoice!
				
				$q = "UPDATE trades_pending set member_to_decision=3 where id=".$cDB->EscTxt($row["id"])."";
			}
			
			if ($cDB->Query($q))
				$list .= "Member ".$row["member_id_from"]." has been informed that you have rejected this transaction.";
			else
				$list .= "<em>Error updating the database.</em>";
		}
		else
			$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
		
	break;
	
	case("remove"): 
	
		$q = "SELECT * FROM trades_pending where id=".$cDB->EscTxt($_GET["tid"])." limit 0,1";
	
		$result = $cDB->Query($q);
		
		if ($result && mysqli_num_rows($result)>0) { // Trade Exists
			
			$row = mysqli_fetch_array($result);
			
			// Do we have permission to act on this trade?
			if ($row["member_id_from"]!=$member_id && $row["member_id_to"]!=$member_id) {
				
				$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
				
				break;
			}
			
			// Check this is not a 'still Open' trade
			if ($row["status"]=='O') {
				
				$list .= "<em>This trade is currently marked as Open and thus cannot be removed until the required action has been taken.</em>";
				break;
			}
			
			if ($row["typ"]=='T' && $row["member_id_from"]==$member_id) { // Our sent payment has finally been confirmed!
				
				$q = "UPDATE trades_pending set member_from_decision=2 where id=".$cDB->EscTxt($row["id"])."";
			}
			else if ($row["typ"]=='T' && $row["member_id_to"]==$member_id) { // We have confirmed receipt of a payment!
				
				$q = "UPDATE trades_pending set member_to_decision=2 where id=".$cDB->EscTxt($row["id"])."";
			}
			else if ($row["typ"]=='I' && $row["member_id_from"]==$member_id) { // Our invoice has finally been paid!
				
				$q = "UPDATE trades_pending set member_from_decision=2 where id=".$cDB->EscTxt($row["id"])."";
			}		
			else if ($row["typ"]=='I' && $row["member_id_to"]==$member_id) { // We have now paid this invoice!
				
				$q = "UPDATE trades_pending set member_to_decision=2 where id=".$cDB->EscTxt($row["id"])."";
			}
			
			if ($cDB->Query($q))
				$list .= "Transaction removed successfully.";
			else
				$list .= "<em>Error updating the database.</em>";
		}
		else
			$list .= "<em>This trade does not exist or you do not have permission to act on it.</em>";
		
	break;
	
	case("confirm"):
	
		$q = "SELECT * FROM trades_pending where id=".$cDB->EscTxt($_GET["tid"])." limit 0,1";
	
		$result = $cDB->Query($q);
		
		if ($result && mysqli_num_rows($result)>0) { // Trade Exists
			
			$row = mysqli_fetch_array($result);
			
			if ($row["status"]!='O') {
				
				$list .= "<em>This trade has already been confirmed and is now closed.</em>";
				break;
			}
			
			/* What is the nature of the trade - Payment or Invoice? */
		
				if ($row["typ"]=='T') { // Payment - we are confirming receipt of incoming
					
					// Check we are the intended recipient
					if ($row["member_id_to"]!=$member_id)
						
						$list .= "<em>You do not have permission to confirm this trade.</em>";
					else { // Action the trade
						
							if (!doTrade($row))
								$list .= "<font color=red>Error confirming payment.</font>";
							else {
								
								$cDB->Query("UPDATE trades_pending set status=".$cDB->EscTxt('F')." where id=".$cDB->EscTxt($_GET["tid"])."");
								$list .= "<em>You have accepted a payment of ".$row["amount"]." ".UNITS." from ".$row["member_id_from"]."</em>";
						}
					}
				}
				
				else if ($row["typ"]=='I') { // Invoice - we are sending a payment
				
						// Check we are the intended recipient of the invoice
					if ($row["member_id_to"]!=$member_id)
						
						$list .= "<em>You do not have permission to confirm this trade.</em>";
					else { // Action the trade
							/*
							$goingFrom = $_SESSION["user_login"];
							$goingTo = $row["member_id_from"];
							
							$row["member_id_to"] = $goingTo;
							$row["member_id_from"] = $goingFrom;
							*/
							if (!doTrade($row)) {
								
								$member = new cMember;
								$member->LoadMember($member_id);
								if ($member->restriction==1) {
									$list .= LEECH_NOTICE;
								}
								else
									$list .= "<font color=red>Error sending payment.</font>";
							}
							else {
								
								$cDB->Query("UPDATE trades_pending set status=".$cDB->EscTxt('F')." where id=".$cDB->EscTxt($_GET["tid"])."");
								$list .= "<em>You have sent a payment of ".$row["amount"]." ".UNITS." to ".$row["member_id_from"]."</em>";
						}
					}
				}
			}
			
		
			else // This trade doesn't exist in the database!
				$list .= "<em>You have elected to confirm a non-existant trade!</em>";
	
	
	break;
	
	case("incoming"):
	
		$list .= "<b>The following Incoming Payments require your confirmation...</b><p>";
		
		/*
		$cDB->Query("INSERT INTO trades_pending (trade_date, member_id_from, member_id_to, amount, category, description, typ) VALUES (now(), ". 	$cDB->EscTxt($member->member_id) .", ". $cDB->EscTxt($member_to_id) .", ". $cDB->EscTxt($values["units"]) .", ". $cDB->EscTxt($values["category"]) .", ". 	$cDB->EscTxt($values["description"]) .", \"T\");");
		*/
		
		$q = "SELECT * FROM trades_pending where member_id_to=".$cDB->EscTxt($member_id)." and typ='T' and member_to_decision = 1";
	
		$result = $cDB->Query($q);
	
		if ($result) {
			
			$list .= initTradeTable();
			
			for($i=0;$i<mysqli_num_rows($result);$i++) {
				
				$row = mysqli_fetch_array($result);
				$list .= displayTrade($row,'P');
			}
			
			$list .= closeTradeTable();
		}
		else
			$list .= "<em>None found!</em>";
	
	break;
	
	case("outgoing"):
	
		$list .= "<b>The following Invoices need paying...</b><p>";
		
		$q = "SELECT * FROM trades_pending where member_id_to=".$cDB->EscTxt($member_id)." and typ='I' and member_to_decision = 1";
	
		$result = $cDB->Query($q);
	
		if ($result) {
			
			$list .= initTradeTable();
			
			for($i=0;$i<mysqli_num_rows($result);$i++) {
				
				$row = mysqli_fetch_array($result);
				$list .= displayTrade($row,'I');
			}
			
			$list .= closeTradeTable();
		}
		else
			$list .= "<em>None found!</em>";
	
	
	break;
	
	
	case("payments_sent"):
	
		$list .= "<b>You are awaiting confirmation of the following Payments...</b><p>";
		
		/*
		$cDB->Query("INSERT INTO trades_pending (trade_date, member_id_from, member_id_to, amount, category, description, typ) VALUES (now(), ". 	$cDB->EscTxt($member->member_id) .", ". $cDB->EscTxt($member_to_id) .", ". $cDB->EscTxt($values["units"]) .", ". $cDB->EscTxt($values["category"]) .", ". 	$cDB->EscTxt($values["description"]) .", \"T\");");
		*/
		
		$q = "SELECT * FROM trades_pending where member_id_from=".$cDB->EscTxt($member_id)." and typ='T' and member_from_decision = 1";
	
		$result = $cDB->Query($q);
	
		if ($result) {
			
			$list .= initTradeTable();
			
			for($i=0;$i<mysqli_num_rows($result);$i++) {
				
				$row = mysqli_fetch_array($result);
				
				$list .= displayTrade($row,'TBC');
			}
			
			$list .= closeTradeTable();
		}
		else
			$list .= "<em>None found!</em>";
	
	break;
	
	
	case("invoices_sent"):
	
		$list .= "<b>You are awaiting payment for the following Invoices...</b><p>";
		
		/*
		$cDB->Query("INSERT INTO trades_pending (trade_date, member_id_from, member_id_to, amount, category, description, typ) VALUES (now(), ". 	$cDB->EscTxt($member->member_id) .", ". $cDB->EscTxt($member_to_id) .", ". $cDB->EscTxt($values["units"]) .", ". $cDB->EscTxt($values["category"]) .", ". 	$cDB->EscTxt($values["description"]) .", \"T\");");
		*/
		
		$q = "SELECT * FROM trades_pending where member_id_from=".$cDB->EscTxt($member_id)." and typ='I' and member_from_decision = 1";
	
		$result = $cDB->Query($q);
	
		if ($result) {
			
			$list .= initTradeTable();
			
			for($i=0;$i<mysqli_num_rows($result);$i++) {
				
				$row = mysqli_fetch_array($result);
				
				$list .= displayTrade($row,'TBP');
			}
			
			$list .= closeTradeTable();
		}
		else
			$list .= "<em>None found!</em>";
	
	break;
	
	default:
		
		if (MEMBERS_CAN_INVOICE==true)
			$list .= "I need to pay ".$pending->numToPay." Invoices<br>";
		
		$list .= "I need to confirm ".$pending->numToConfirm." Incoming Payments<p>";
		
		if (MEMBERS_CAN_INVOICE==true)
			$list .= "I am awaiting payment for ".$pending->numToBePayed." Invoices<br>";
	
		$list .= "I am awaiting confirmation of ".$pending->numToHaveConfirmed." Outgoing Payments<p>";
	
break;
}

$p->DisplayPage($list);
?>