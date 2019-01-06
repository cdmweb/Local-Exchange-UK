<?php

include_once("includes/inc.global.php");

$cUser->MustBeLoggedOn();
$p->site_section = LISTINGS;
//$p->page_title = $cDB->UnEscTxt($_GET['title']);

include("classes/class.listing.php");

$listing = new cListing();
//$listing->LoadListing($cDB->UnEscTxt($_GET['title']), $_GET['member_id'], substr($_GET['type'],false,true));
//$output = $listing->DisplayListing();
//$output = "hello";
$title = $cDB->UnEscTxt($_GET['title']);
$member_id = $_GET['member_id'];
$type = $_GET['type'];
//print($title . $member_id. $type);
$listing->LoadListing($title, $member_id, $type);
$output = "";
if ($cUser->getMemberRole()){
	$output = $p->Wrap("Quick edit: <a href=\"member_edit.php?mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Profile]</a> <a href=\"listings_found.php?type=Offer&mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Offers]</a> <a href=\"member_edit.php?mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Wants]</a> <a href=\"member_edit.php?mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Joint Member]</a>", "div", "admin-actions");
}

$output .= $listing->DisplayListing();
//$output = $listing->DisplayListing();


 $p->page_title = $title . " ({$listing->type})";


$p->DisplayPage($output);

include("includes/inc.events.php");

?>
