<?php
include_once("includes/inc.global.php");
$p->site_section = LISTINGS;
$p->page_title = $_REQUEST["type"] ."ed Listings";

include("classes/class.listing.php");
include("includes/inc.forms.php");

$form->addElement("hidden","type", $_REQUEST["type"]);
$form->addElement("static", null, "Select the category and time frame to view and press Continue. Or to view all listings at once, just press Continue. If you would like to print or download the complete directory, click <A HREF=directory.php>here</A>.", null);
$form->addElement("static", null, null, null);
$category_list = new cCategoryList();
$categories = $category_list->MakeCategoryArray(ACTIVE, substr($_REQUEST["type"],0,1));
$categories[0] = "(View All Categories)";
$form->addElement("select", "category", "Category", $categories);
$text = "New/updated in last ";
$form->addElement("select", "timeframe", "Time Frame", array("0"=>"(View All Listings)", "3"=>$text ."3 days", "7"=>$text ."week", "14"=>$text ."2 weeks", "30"=>$text ."month", "90"=>$text ."3 months"));

if (KEYWORD_SEARCH_DIR==true)
	$form->addElement("text","keyword","Keyword");

$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Continue");

//$form->registerRule('verify_selection','function','verify_selection');
//$form->addRule('category', 'Choose a category', 'verify_selection');

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$p->DisplayPage($form->toHtml());
}

function process_data ($values) {
	global $p;

	header("location:http://".HTTP_BASE."/listings_found.php?type=".$_REQUEST["type"]."&keyword=".$_REQUEST["keyword"]."&category=".$values["category"]."&timeframe=".$_REQUEST["timeframe"]);
	exit;
}

function verify_selection ($z, $selection) {
	if($selection == "0")
		return false;
	else
		return true;
}


?>
