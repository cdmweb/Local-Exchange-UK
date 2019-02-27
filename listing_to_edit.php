<?php
include_once("includes/inc.global.php");
$p->site_section = LISTINGS;


$type = (isset($_REQUEST["type"])) ? $_REQUEST["type"] : "Offer";
$member_id = (isset($_REQUEST["member_id"])) ? $_REQUEST["member_id"] : "%";

$p->page_title = "Choose the ". $type ." listing to edit";

include("classes/class.listing.php");

$listings = new cTitleListGroup();

//$member = new cMember;

if($_REQUEST["mode"] == "admin") {
	$cUser->MustBeLevel(1);
	//$member->LoadMember($_REQUEST["member_id"]);
} else {
	$cUser->MustBeLoggedOn();
	//$member = $cUser;
}

$list = $listings->DisplayMemberListings($member_id, $type);

if($list == "")
	$list = "No ". $type ." listings found.";

$p->DisplayPage($list);

?>
