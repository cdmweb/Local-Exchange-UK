<?php

include_once("includes/inc.global.php");
$p->site_section = PROFILE;

// bugfix RF 090905 added logged in check
$cUser->MustBeLoggedOn();

$member = new cMember; 
$member->LoadMember($_REQUEST["member_id"]);
$member_id = $member->getMemberId();
$status_label = ($member->getStatus() == "I") ? "- Inactive" : "";

$p->page_title = "Member details for {$member->getAllNames()} (#{$member_id}{$status_label})";


include_once("classes/class.listing.php");
if ($cUser->getMemberRole() ){
	$output = $p->Wrap("Quick edit: <a href=\"member_edit.php?mode=admin&member_id={$member_id}\">[Profile]</a> <a href=\"listings_found.php?type=Offer&mode=admin&member_id={$member_id}\">[Offers]</a> <a href=\"listings_found.php?type=Want&mode=admin&member_id={$member_id}\">[Wants]</a> <a href=\"member_edit.php?mode=admin&member_id={$member_id}\">[Joint Member]</a>", "div", "admin-actions");
}
$output .= "{$member->DisplayMember()}";
/*
$output = "<STRONG><I>CONTACT INFORMATION</I></STRONG><P>";
$output .= $member->DisplayMember();
*/
//$output .= $p->Wrap("Offered", "h3");

if(!empty($_REQUEST["member_id"])){
	// CT show offers
	$output .= $p->Wrap(OFFER_LISTING_HEADING, "h2");
	$listings = new cListingGroup(OFFER_LISTING);
	$listings->LoadListingGroup(null, null, $_REQUEST["member_id"], null, null, null);
	$output .= $listings->DisplayListingGroup();
	// CT Show want
		$output .= $p->Wrap(WANT_LISTING_HEADING, "h2");
	$listings = new cListingGroup(WANT_LISTING);
	$listings->LoadListingGroup(null, null, $_REQUEST["member_id"], null, null, null);
	$output .= $listings->DisplayListingGroup();
} 
/*
$output .= $p->Wrap("Wanted Listing", "h3");
$listings = new cListingGroup(WANT_LISTING);
$listings->LoadListingGroup(null, null, $_REQUEST["member_id"]);
$output .= $listings->DisplayListingGroup();
*/
$p->DisplayPage($output); 

?>
