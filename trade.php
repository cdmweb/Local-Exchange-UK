<?php

include_once("includes/inc.global.php");

$p->site_section = EXCHANGES;
$p->page_title = "Record an Exchange";

//include_once("classes/class.trade.php");
//include("includes/inc.forms.validation.php");

//
// Define form elements
//
$member = new cMemberConcise;

// if($cUser->member_id == "ADMIN") {
// 	$p->DisplayPage("I'm sorry, you cannot record exchanges while logged in as the ADMIN account.  This is a special account for administration purposes only.<p>To create member accounts go to the <a href=admin_menu.php>Administration menu</a>.");	
// 	exit;
// }

if($_REQUEST["form_mode"] == "admin") {
	$cUser->MustBeLevel(1);
	//$member->LoadMember($_REQUEST["member_id"]);
	//$p->page_title .= " for ". $member->getDisplayName();
} else {
	$cUser->MustBeLoggedOn();
	$member = $cUser;
}
if($_REQUEST["action"] == "transfer") {
	
} elseif($_REQUEST["action"] == "invoice") {
	
}		
	
// $form->addElement('hidden', 'member_id', $member->getMemberId());
// $form->addElement('hidden', 'mode', $_REQUEST["mode"]);
// $form->addElement("html", "<TR></TR>");  // TODO: Move this to the header

// if (MEMBERS_CAN_INVOICE==true) // Invoicing turned on in config, so let member choose

// 	$form->addElement("select", "typ", "Transaction Type", array('null'=>"-- Select transaction type --",'transfer'=>"Transfer",'invoice'=>"Invoice"));
// else // Invoicing turned off, this form now only functions to transfer money
// 	$form->addElement('hidden', 'typ', 0);	
	

$members = new cMemberGroup;
$condition = $members->makeActiveMemberFilter();
$order_by="p1.first_name ASC";
$members->Load($condition, $order_by);
//if (JS_MEMBER_SELECT==true)
$dropdown_member_from = $members->PrepareMemberDropdown(null, "member_id_from");
$dropdown_member_to = $members->PrepareMemberDropdown(null, "member_id_to");

$categories = new cCategoryGroup;
//no condition
$categories->Load(1);
$dropdown_category = $categories->PrepareCategoryDropdown(null, "category_id");


$units = UNITS;

$output .= "
	<form action=\"\" method=\"post\" name=\"\" id=\"\" class=\"layout2\">
		<input type=\"hidden\" id=\"action\" name=\"action\" value=\"{$action}\" />
		<input type=\"hidden\" id=\"extras\" name=\"location\" value=\"{$extras}\" />
		{$member_text}
		<p>
        	<label for=\"member_id\">
	            <span>From member *</span>
	            {$dropdown_member_from}
	        </label>
	    </p>
	    <p>
        	<label for=\"member_id\">
	            <span>To member *</span>
	            {$dropdown_member_to}
	        </label>
	    </p>
	    <p>
        	<label for=\"categoty_id\">
	            <span>Category *</span>
	            {$dropdown_category}
	        </label>
	    </p>	
	   	<p>
        	<label for=\"amount\">
	            <span>" . UNITS . "</span>
	            input
	        </label>
	    </p>	
	    	   	<p>
        	<label for=\"member_id\">
	            <span>Description</span>
	            txt area
	        </label>
	    </p>	
		<p>
			<input name=\"submit\" id=\"submit\" value=\"Submit\" type=\"submit\" />
			* denotes a required field
		</p>
	</form>";



$p->DisplayPage($output);  // just display the form
// }
	//$form->addElement("html","<tr><td>To Member ".$name_list->DoNamePicker()."</td></tr>");
//else
	//$form->addElement("select", "member_to", "Transfer to Member", $name_list->MakeNameArray());

// $category_list = new cCategoryList();
// $form->addElement('select', 'category', 'Category', $category_list->MakeCategoryArray());
// $form->addElement("text", "units", "# of ". UNITS ."", array('size' => 5, 'maxlength' => 10));
// if(UNITS == "Hours") {
// 	$form->addElement("text","minutes","# of Minutes",array('size'=>2,'maxlength'=>2));
// }
// $form->addElement('static', null, 'Enter a Brief Description of the Exchange', null);
// $form->addElement('textarea', 'description', null, array('cols'=>50, 'rows'=>4, 'wrap'=>'soft'));
// $form->addElement('submit', 'btnSubmit', 'Submit');

// //
// // Define form rules
// //
// //$form->addRule('description', 'Enter a description', 'required');
// $form->registerRule('verify_not_self','function','verify_not_self');
// $form->addRule('member_to', 'You cannot transfer to yourself', 'verify_not_self');
// $form->registerRule('verify_selection','function','verify_selection');
// $form->addRule('category', 'Choose a category', 'verify_selection');
// $form->addRule('member_to', 'Choose a member', 'verify_selection');
// $form->addRule('description', 'Description too long - maximum length is 255 characters', 'verify_max255');

// if(UNITS == "Hours") {
// 	$form->registerRule('verify_whole_hours','function','verify_whole_hours');
// 	$form->addRule('units', 'Hours entered must be a positive, whole number', 'verify_whole_hours');
// 	$form->registerRule('verify_even_minutes','function','verify_even_minutes');
// 	$form->addRule('minutes', 'Enter 15, 30, or 45 (or other numbers in 3 minute increments)', 'verify_even_minutes');
// } else {
// 	$form->registerRule('verify_valid_units','function','verify_valid_units');
// 	$form->addRule('units', 'Enter a positive number with no more than two decimal points', 'verify_valid_units');
// }


// //
// // Then check if we are processing a submission or just displaying the form
// //
// if ($form->validate()) { // Form is validated so processes the data
//    $form->freeze();
//  	$form->process('process_data', false);
// } else {
//    $p->DisplayPage($form->toHtml());  // just display the form
// }

function process_data ($values) {
	global $p, $member, $cErr, $cUser;
	$list = "";
	
	if(UNITS == "Hours") {
		if($values['minutes'] > 0)
			$values['units'] = $values['units'] + ($values['minutes'] / 60);
	}
	
	if(!($values['units'] > 0)) {
		$cErr->Error("No units were entered to exchange!");
		include("redirect.php");
	}
	
	$member_to_id = substr($values['member_to'],0, strpos($values['member_to'],"?")); // TODO:
	$member_to = new cMember;
	$member_to->LoadMember($member_to_id);
	
	if ($_REQUEST["mode"] == "admin") {
        $cUser->MustBeLevel(1);

        // record that trade was entered by an admin & log if logging enabled
		$type = TRADE_BY_ADMIN;
    }
	else {
		$type = TRADE_ENTRY;  // regular trade
    }
	
	/*
	[chris] For transaction approval
	*/
	$type_of_transaction = $POST["typ"];
	//CT making types of transaction explicit and via post
	if (!empty($type_of_transaction) || $member_to->getConfirmPayments()==1) { // Member wishes to confirm payments made to him OR this is an invoice
		
		if (htmlspecialchars($values['units']) >= 0 && $member_to_id != $member->getMemberId()) {
			
			global $cDB;
			
			if ($type_of_transaction=='transfer') { // This is a payment
				
				if ($member->restriction==1) {
					$list .= "<p>".LEECH_NOTICE;
				}
				else if ($cDB->Query("INSERT INTO trades_pending (trade_date, member_id_from, member_id_to, amount, category, description, typ) VALUES (now(), ". 	$cDB->EscTxt($member->getMemberId()) .", ". $cDB->EscTxt($member_to_id) .", ". $cDB->EscTxt($values["units"]) .", ". $cDB->EscTxt($values["category"]) .", ". 	$cDB->EscTxt($values["description"]) .", \"T\");")) {
					
					$mailed = mail($member_to->person[0]->email, "Payment Received on ".SITE_LONG_TITLE."", "Hi ".$member_to_id.",\n\nJust letting you know that you have received a new payment from ".$member->getMemberId()."\n\nAs you have elected to confirm all payments made to you, please log into your account now and confirm or reject this payment using the following URL...\n\nhttp://".SERVER_DOMAIN.SERVER_PATH_URL."/trades_pending.php?action=incoming", EMAIL_FROM);
			
					$list .= $member_to_id." has been notified that you wish to transfer ". $values['units'] ." ". strtolower(UNITS) ." to him/her.<p>This member has opted to confirm all transactions made to him/her. Once the member accepts this transaction your payment will be actioned and you will be invited to leave Feedback for this member.<p>
							Would you like to <A HREF=trade.php?mode=".$_REQUEST["mode"]."&member_id=". $_REQUEST["member_id"].">record another</A> exchange?";
				}
				else
					$list .= "Trade failed!  Please try again later.";	
			}
			else if ($type_of_transaction=='invoice') {
				
				if (MEMBERS_CAN_INVOICE!=true) // Invoicing is turned off, user has no right to be here!
					$list .= "Sorry, the Invoicing facility has been disabled by the site administrator.";	
				else if ($cDB->Query("INSERT INTO trades_pending (trade_date, member_id_from, member_id_to, amount, category, description, typ) VALUES (now(), ". 	$cDB->EscTxt($member->getMemberId()) .", ". $cDB->EscTxt($member_to_id) .", ". $cDB->EscTxt($values["units"]) .", ". $cDB->EscTxt($values["category"]) .", ". 	$cDB->EscTxt($values["description"]) .", \"I\");")) {
					
					$mailed = mail($member_to->person[0]->email, "Invoice Received on ".SITE_LONG_TITLE."", "Hi ".$member_to_id.",\n\nJust letting you know that you have received a new Invoice from ".$member->getMemberId()."\n\nPlease log into your account now to pay or reject this invoice using the following URL...\n\nhttp://".SERVER_DOMAIN.SERVER_PATH_URL."/trades_pending.php?action=outgoing", EMAIL_FROM);
			
					$list .= $member_to_id." has been sent an invoice for ". $values['units'] ." ". strtolower(UNITS) .".<p> You will be informed when the member pays this invoice and will be invited to leave Feedback for this member.<p>
						Would you like to <A HREF=trade.php?mode=".$_REQUEST["mode"]."&member_id=". $_REQUEST["member_id"].">record another</A> exchange?";
			}
			else
					$list .= "Trade failed!  Please try again later.";	
			}
			
		}
	}
	else { // Make the trade
		
		$trade = new cTrade($member, $member_to, htmlspecialchars($values['units']), htmlspecialchars($values['category']), htmlspecialchars($values['description']), $type);
		
		$status = $trade->MakeTrade();
		
		if(!$status) {
			$list .= "Trade failed!  Please try again later.";

			if ($member->restriction==1)
				$list .= LEECH_NOTICE;
		}
		else
			$list .= "You have transferred ". $values['units'] ." ". strtolower(UNITS) ." to ". $member_to_id .".  Would you like to <A HREF=trade.php?mode=".$_REQUEST["mode"]."&member_id=". $_REQUEST["member_id"].">record another</A> exchange?<P>Or would you like to leave <A HREF=feedback.php?mode=". $_REQUEST["mode"] ."&author=". $member->getMemberId() ."&about=". $member_to_id ."&trade_id=". $trade->trade_id .">feedback</A> for this member?";
		
		// Has the recipient got an income tie set-up? If so, we need to transfer a percentage of this elsewhere...
	/*	
			$recipTie = cIncomeTies::getTie($member_to_id);
			
			if ($recipTie && ALLOW_INCOME_SHARES==true) {
				
				$member_to = new cMember;
				$member_to->LoadMember($member_to_id);
	
				$theAmount = round(($values['units']*$recipTie->percent)/100);
				
				$charity_to = new cMember;
				$charity_to->LoadMember($recipTie->tie_id);
	
				$trade2 = new cTrade($member_to, $charity_to, htmlspecialchars($theAmount), htmlspecialchars(12), htmlspecialchars("Donation from ".$member_to_id.""), 'T');
		
				$status = $trade2->MakeTrade();
			}
	*/
	}
	
   $p->DisplayPage($list);
}
/* 
function verify_not_self($element_name,$element_value) {
	global $member;
	$member_id = substr($element_value,0, strpos($element_value,"?"));
	if ($member_id == $member->getMemberId())
		return false;
	else
		return true;
}

function verify_valid_units($element_name,$element_value) { 
	if ($element_value < 0)
		return false; 
	elseif ($element_value * 100 != floor($element_value * 100)) 
		return false;	// allow no more than two decimal points
	else
		return true;
}

function verify_even_minutes ($z, $minutes) { // verifies # of minutes entered represents an evenly
	if($minutes/60*1000 == floor($minutes/60*1000)) 	// divisible fraction w/ no more than 3
		return true;												//	decimal points
	else
		return false;
}

function verify_whole_hours ($z, $hours) {
	if(abs(floor($hours)) != $hours)
		return false;
	else
		return true;
}
*/

?>
