<?php
include_once("includes/inc.global.php");
$p->site_section = LISTINGS;
$p->page_title = "Choose the ". $_REQUEST["type"] ." Listing to Edit";

include("classes/class.listing.php");

$listings = new cTitleList($_GET['type']);

$member = new cMember;

if($_REQUEST["mode"] == "admin") {
	$cUser->MustBeLevel(1);
	$member->LoadMember($_REQUEST["member_id"]);
} else {
	$cUser->MustBeLoggedOn();
	$member = $cUser;
}

$list = $listings->DisplayMemberListings($member);

if($list == "")
	$list = "You don't currently have any ". $_GET['type'] ." listings.";

$p->DisplayPage($list);

?>
