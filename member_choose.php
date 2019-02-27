<?php
include_once("includes/inc.global.php");
$cUser->MustBeLevel(1);

$p->site_section = ADMINISTRATION;
$p->page_title = "For which member?";

include("includes/inc.forms.php");

//$form->addElement("header", null, "For which member?");
//$form->addElement("html", "<TR></TR>");
$show_inactive = (!empty($_REQUEST["show_inactive"]))? true : false;
$action = $_REQUEST["action"];
$form->addElement("hidden", "action", $_REQUEST["action"]);
$output = "";

$get_string = "";
if (isset($_REQUEST["get1"])) $get_string .= "&get1=" . $_REQUEST["get1"];
if (isset($_REQUEST["get1val"])) $get_string .= "&get1val=" . $_REQUEST["get1val"];

if(empty($show_inactive)){
	$output .= $p->Wrap("<strong>Show active members</strong> | <a href='member_choose.php?action={$action}&show_inactive=true{$get_string}'>Show all members</a>", "p", "small");
}else{
	$output .= $p->Wrap("<a href='member_choose.php?action={$action}{$get_string}'>Show active members</a> | <strong>Show all members</strong>", "p", "small");
}


if(isset($_REQUEST["get1"])) {
	$form->addElement("hidden", "get1", $_REQUEST["get1"]);
	$form->addElement("hidden", "get1val", $_REQUEST["get1val"]);
}

$ids = new cMemberGroup;
$ids->LoadMemberGroup($show_inactive,true);

	
$form->addElement("select", "member_id", "Member", $ids->MakeIDArray());
$form->addElement("static", null, null, null);
$form->addElement('submit', 'btnSubmit', 'Submit');

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$output .= $form->toHtml();
	$p->DisplayPage($output);
}

function process_data ($values) {
	global $cUser;
	
	if(isset($_REQUEST["get1"]))
		$get_string = "&". $_REQUEST["get1"] ."=". $_REQUEST["get1val"];
	else
		$get_string = "";
		
	header("location:".HTTP_BASE."/". $_REQUEST["action"] .".php?mode=admin&member_id=".$values["member_id"] . $get_string);
	exit;	
}

?>
