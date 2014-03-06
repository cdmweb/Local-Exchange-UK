<?php

include_once("includes/inc.global.php");

$cUser->MustBeLevel(1);

$p->site_section = EVENTS;
$p->page_title = "Create News Item";

include("classes/class.news.php");
include("includes/inc.forms.php");

//
// First, we define the form
//

$form->addElement("text", "title", "Title", array("size" => 35, "maxlength" => 100));
$today = getdate();
$options = array("language"=> "en", "format" => "dFY", "minYear" => $today["year"],"maxYear" => $today["year"]+5);
$form->addElement("date","expire_date", "Expires", $options);
$sequence = new cNewsGroup();
$sequence->LoadNewsGroup();
$form->addElement("select", "sequence","Sequence", $sequence->MakeNewsSeqArray());
//$form->addElement("static", null, "Description", null);
$form->addElement("textarea", "description", "Description", array("cols"=>65, "rows"=>5, "wrap"=>"soft"));

$form->addElement("submit", "btnSubmit", "Submit");

//
// Set up validation rules for the form
//
$form->addRule("title","Enter a title","required");
$form->addRule("description","Enter a description","required");
$form->registerRule("verify_future_date","function","verify_future_date");
$form->addRule("expire_date","Expiration must be for a future date","verify_future_date");
$form->registerRule("verify_valid_date","function","verify_valid_date");
$form->addRule("expire_date","Date is invalid","verify_valid_date");

//
// Then check if we are processing a submission or just displaying the form
//
if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {
   $p->DisplayPage($form->toHtml());  // just display the form
}

//
// The form has been submitted with valid data, so process it   
//
function process_data ($values) {
	global $p, $cUser,$cErr, $sequence;
	
	$date = $values['expire_date'];
	$expire_date = $date['Y'] . '/' . $date['F'] . '/' . $date['d'];
	$news = new cNews($values["title"], $values["description"], $expire_date, $values["sequence"]);
	$success = $news->SaveNewNews();	
	
	if ($success)
		$output = "News item saved.";
	else
		$output = "There was a problem saving the news item.";
		
	$p->DisplayPage($output);
	
}


//
// Custom validation functions
//

function verify_future_date ($element_name,$element_value) {
	global $form;

	$today = getdate();
	$date = $element_value;
	$date_str = $date["Y"] . "/" . $date["F"] . "/" . $date["d"];

	if ($date_str == $today["year"]."/1/1" and !$form->getElementValue("set_expire_date")) // date wasn"t changed by user, so no need to verify it
		return true;
	elseif (strtotime($date_str) <= strtotime("now")) // date is a past date
		return false;
	else
		return true;
}

function verify_valid_date ($element_name,$element_value) {
	$date = $element_value;
	return checkdate($date["F"],$date["d"],$date["Y"]);
}
