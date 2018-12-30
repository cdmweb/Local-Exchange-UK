<?php

include_once("includes/inc.global.php");
$p->site_section = PROFILE;

// bugfix RF 090905 added logged in check
$cUser->MustBeLoggedOn();

$member = new cMemberSummaryView;
$member->LoadMember($_REQUEST["member_id"]);

$p->page_title = "<a href='member_directory.php'>Members</a> / {$member->AllNames()}";

include_once("classes/class.listing.php");
		if ($cUser->getMemberRole()){
			$output = $p->Wrap("Admin actions: <a href=\"member_edit.php?mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Edit Member Account]</a> <a href=\"member_edit.php?mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Edit Offered Listings]</a> <a href=\"member_edit.php?mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Edit Wanted Listings]</a> <a href=\"member_edit.php?mode=admin&member_id=" . $_REQUEST['member_id'] . "\">[Add/edit Joint Member]</a>", "div", "admin");
		}
$output .= "{$member->DisplayMember()}";
/*
$output = "<STRONG><I>CONTACT INFORMATION</I></STRONG><P>";
$output .= $member->DisplayMember();
*/
$output .= $p->Wrap("Listings", "h2");
//$output .= $p->Wrap("Offered", "h3");
$listings = new cListingGroupCT();
$listings->LoadListingGroup(null, null, $_REQUEST["member_id"]);
$output .= $listings->DisplayListingGroup();
/*
$output .= $p->Wrap("Wanted Listing", "h3");
$listings = new cListingGroup(WANT_LISTING);
$listings->LoadListingGroup(null, null, $_REQUEST["member_id"]);
$output .= $listings->DisplayListingGroup();
*/
$p->DisplayPage($output); 

?>
