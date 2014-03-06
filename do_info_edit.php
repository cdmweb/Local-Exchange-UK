<?php

include_once("includes/inc.global.php");
include("classes/class.info.php");
include("includes/inc.forms.php");

$cUser->MustBeLevel(1);

$p->site_section = EVENTS;

$pg = cInfo::LoadOne($_REQUEST["id"]);

$p->page_title = "Edit '". $pg["title"] ."'";

//
// First, we define the form
//

$form->addElement("hidden","id",$_REQUEST["id"]);
$form->addElement("text", "title", "Title", array("size" => 35, "maxlength" => 100));
$form->addElement("textarea", "description", "Content", array("cols"=>65, "rows"=>5, "wrap"=>"soft"));

$form->addElement("submit", "btnSubmit", "Submit");

//
// Set up validation rules for the form
//
$form->addRule("title","Enter a title","required");
$form->addRule("description","Enter some body text","required");

//
// Then check if we are processing a submission or just displaying the form
//
if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {
	
	$current_values = array ("title"=>$pg["title"], "description"=>$pg["body"]);
	
	$form->setDefaults($current_values);
   $p->DisplayPage($form->toHtml());  // just display the form
}

//
// The form has been submitted with valid data, so process it   
//
function process_data ($values) {
	global $p, $cErr, $cDB;
	$q = 'UPDATE cdm_pages set date='.time().', title='.$cDB->EscTxt($values["title"]).', body='.$cDB->EscTxt($values["description"]).' where id='.$cDB->EscTxt($values["id"]).'';
	$success = $cDB->Query($q);
	
	if ($success)
		$output = "Changes saved.";
	else
		$output = "There was a problem saving the page.";
		
	$p->DisplayPage($output);
	
}
