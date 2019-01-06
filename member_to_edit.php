<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->MustBeLevel(1);
include("includes/inc.forms.php");
$p->page_title = "Choose Member to Edit";
//hack - filter non-active members by default
$show_inactive = (!empty($_REQUEST["show_inactive"]))? true : false;
$output = "";
if(empty($show_inactive)){
	$output .= $p->Wrap("<strong>List active only</strong> | <a href='member_to_edit.php?show_inactive=true'>List all</a>", "p");
}else{
	$output .= $p->Wrap("<a href='member_to_edit.php'>List active only</a> | <strong>List all</strong>", "p");
}

$ids = new cMemberGroup;
$ids->LoadMemberGroup($show_inactive,true);

$form->addElement("select", "member_id", "Member", $ids->MakeIDArray(true));
$form->addElement("static", null, null, null);
$form->addElement('submit', 'btnSubmit', 'Edit');

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$output .= $form->toHtml();
	$p->DisplayPage($output);
}

function process_data ($values) {
	global $cUser;
	header("location:http://".HTTP_BASE."/member_edit.php?mode=admin&member_id=".$values["member_id"]);
	exit;	
}

?>
