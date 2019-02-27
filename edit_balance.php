<?php

include_once("includes/inc.global.php");

$p->site_section = SITE_SECTION_OFFER_LIST;

include("includes/inc.forms.php");
	

//
// First, we define the form
//

$cUser->MustBeLevel(2);

if (OVRIDE_BALANCES!=true) // Provision for overriding member balances has been turned off, return to the admin menu
	header("location:".HTTP_BASE."/admin_menu.php");
	
$member = new cMember;
$member->LoadMember($_REQUEST["member_id"]);
	
$form->addElement("header", null, "Edit Member " . $member->person[0]->first_name . " " . $member->person[0]->last_name."'s Balance");
$form->addElement("hidden","member_id",$_REQUEST["member_id"]);
$form->addElement("text", "balance1", "Value (before decimal point, 8 digit max)", array("size" => 6, "maxlength" => 8));
$form->addElement("text", "balance2", "Value (after decimal point, 2 digit max)", array("size" => 1, "maxlength" => 2));	
$form->addElement('submit', 'btnSubmit', 'Update Balance');

$balance = explode(".",$member->balance);

$current_values["balance1"] = $balance[0];
$current_values["balance2"] = $balance[1];
$form->setDefaults($current_values);
//
// Check if we are processing a submission or just displaying the form
//
if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Otherwise we need to load the existing values
	$member = new cMember;
	if($_REQUEST["mode"] == "admin") {
        $cUser->MustBeLevel(1);
		$member->LoadMember($_REQUEST["member_id"]);
    }
	else {
		$member = $cUser;
    }
			
   $p->DisplayPage($form->toHtml());  // display the form
}

//
// The form has been submitted with valid data, so process it   
//
function process_data ($values) {
	global $p, $cUser,$cErr, $cDB;
	
	$balance = trim($values["balance1"]).".".trim($values["balance2"]);
	
	$q = 'UPDATE member set balance='.$cDB->EscTxt($balance).' where member_id='.$cDB->EscTxt($values["member_id"]).'';
//	echo $q;
	$success = $cDB->Query($q);
	
	if ($success)
		$output = "This member's balance has now been set to '$balance'. <a href=balance_to_edit.php>Edit another member's balance?</a>";
	else
		$output = "There was a problem updated this member's balance.";
		
	$p->DisplayPage($output);
}
