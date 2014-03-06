<?php

include_once("includes/inc.global.php");

$cUser->MustBeLoggedOn();
$p->site_section = LISTINGS;
$p->page_title = 'Delete '. $_REQUEST['type'] .'ed Listings';

include("classes/class.listing.php");
include("includes/inc.forms.php");
$message = "";

// First, need to change the default form template so checkbox comes before the label
$renderer->setElementTemplate('<TR><TD>{element}<!-- BEGIN required --><font> *</font><!-- END required --></FONT><!-- BEGIN error --><font color=RED size=2>   *{error}*</font><br /><!-- END error -->&nbsp;<FONT SIZE=2>{label}</FONT></TD></TR>');  

$form->addElement('hidden','type',$_REQUEST['type']);
$form->addElement('hidden','mode',$_REQUEST['mode']);


$member = new cMember;

if($_REQUEST["mode"] == "admin") {
    $cUser->MustBeLevel(1);
	$member->LoadMember($_REQUEST["member_id"]);
}
else {
	$member = $cUser;
}

$form->addElement('hidden','member_id',$member->member_id);

$title_list = new cTitleList($_REQUEST['type']);
$titles = $title_list->MakeTitleArray($member->member_id);

$listings_exist = false;

while (list($key, $title) = each ($titles)) {
	if($title != "") {
		$form->addElement('checkbox', $key, $title);
		$listings_exist=true;
	}
}

if ($listings_exist) {
	$form->addElement('static', null, null);
	$form->addElement('submit', 'btnSubmit', 'Delete');
} else {
	if($_REQUEST["mode"] == "self")
		$text = "You don't ";
	else
		$text = $member->PrimaryName() . " doesn't ";
		
	$message = $text ."currently have any ". strtolower($_REQUEST['type']) ."ed listings.";
}

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process('process_data', false);
} else {
   $p->DisplayPage($form->toHtml() ."<BR>". $message);  // just display the form
}

function process_data ($values) {
	global $p, $cErr, $titles, $member;
	$list = "";
	$deleted = 0;
	$listing = new cListing;
	while (list ($key, $value) = each ($values)) {
		$affected = 0;
		if(is_numeric($key))  // Two of the values are hidden fields.  Need to skip those.
			$affected = $listing->DeleteListing($titles[$key],$member->member_id,substr($_REQUEST['type'],0,1));

		$deleted += $affected;
	}
	
	if($deleted == 1) 
		$list .= "1 listing deleted.";
	elseif($deleted > 1)
		$list .= $deleted . " listings deleted.";	
	else
		$cErr->Error("There was an error deleting the listings. Did you check any boxes?");
		
   $p->DisplayPage($list);
}

?>
