<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;
	
$cUser->MustBeLevel(2);
include("includes/inc.forms.php");

if (OVRIDE_BALANCES!=true) // Provision for overriding member balances has been turned off, return to the admin menu
	header("location:" .HTTP_BASE."/admin_menu.php");
	
$form->addElement("header", null, "Choose Member whose Balance you wish to Edit");
$form->addElement("html", "<TR></TR>");

$ids = new cMemberGroup;
$ids->LoadMemberGroup(null,true);

$form->addElement("select", "member_id", "Member", $ids->MakeIDArray());
$form->addElement("static", null, null, null);
$form->addElement('submit', 'btnSubmit', 'Edit Balance');

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$p->DisplayPage($form->toHtml());
}

function process_data ($values) {
	global $cUser;
	header("location:".HTTP_BASE."/edit_balance.php?mode=admin&member_id=".$values["member_id"]);
	exit;	
}

?>
