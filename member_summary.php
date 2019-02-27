<?php

include_once("includes/inc.global.php");
include_once("classes/class.listing.php");
$p->site_section = PROFILE;

// bugfix RF 090905 added logged in check
$cUser->MustBeLoggedOn();

$member = new cMember; 
$member->LoadMember($_REQUEST["member_id"], 2);
$member_id = $member->getMemberId();
$status_label = ($member->getStatus() == "I") ? " - Inactive" : "";
$p->page_title = "{$member->getAllNames()} (#{$member_id}{$status_label})";

$output = "<p><a href='member_summary.php?member_id={$member_id}'>Profile</a> | <a href='trade_history.php?member_id={$member_id}'>Trade history</a></p>";
if ($cUser->getMemberRole() ){
	$string = file_get_contents(TEMPLATES_PATH . '/menu_quick_edit.php', TRUE);
	$output .= $p->ReplaceVarInString($string, '$member_id', $member_id);
}
$output .= "{$member->DisplayMember()}";


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

$p->DisplayPage($output); 

?>
