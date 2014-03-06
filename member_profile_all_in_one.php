<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->MustBeLoggedOn();

$list = "<H2>Welcome to ". SITE_SHORT_TITLE .", ". $cUser->person[0]->first_name ."!</H2>";
$list .= "Please choose from the following options, or navigate using the buttons on the sidebar to the left.<P>";

$list .= "<STRONG>Member Settings</STRONG><P>";
$list .= "<A HREF=password_change.php><FONT SIZE=2>Change My Password</FONT></A><BR>";
$list .= "<A HREF=member_edit.php?mode=self><FONT SIZE=2>Edit My Personal Information</FONT></A><BR>";
$list .= "<A HREF=member_contact_create.php?mode=self><FONT SIZE=2>Add a Joint Member to My Account</FONT></A><BR>";
$list .= "<A HREF=member_contact_choose.php><FONT SIZE=2>Edit a Joint Member</FONT></A><P>";

$list .= "<STRONG>Offered Listings</STRONG><P>";
$list .= "<A HREF=listings.php?type=Offer><FONT SIZE=2>View Offered Listings</FONT></A><BR>";
$list .= "<A HREF=listing_create.php?type=Offer><FONT SIZE=2>Create New Offer Listing</FONT></A><BR>";
$list .= "<A HREF=listing_to_edit.php?type=Offer><FONT SIZE=2>Edit Offered Listings</FONT></A><BR>";
$list .= "<A HREF=listing_delete.php?type=Offer><FONT SIZE=2>Delete Offered Listings</FONT></A><P>";

$list .= "<STRONG>Wanted Listings</STRONG><P>";
$list .= "<A HREF=listings.php?type=Want><FONT SIZE=2>View Wanted Listings</FONT></A><BR>";
$list .= "<A HREF=listing_create.php?type=Want><FONT SIZE=2>Create New Want Listing</FONT></A><BR>";
$list .= "<A HREF=listing_to_edit.php?type=Want><FONT SIZE=2>Edit Wanted Listings</FONT></A><BR>";
$list .= "<A HREF=listing_delete.php?type=Want><FONT SIZE=2>Delete Wanted Listings</FONT></A><P>";

$list .= "<STRONG>Exchanges</STRONG><P>";
$list .= "<A HREF=trade.php><FONT SIZE=2>Record an Exchange</FONT></A><BR>";
$list .= "<A HREF=trade_history.php?mode=self><FONT SIZE=2>View My Balance and Exchange History</FONT></A><BR>";
$list .= "<A HREF=trades_to_view.php><FONT SIZE=2>View Another Member's Exchange History</FONT></A><P>";

if ($cUser->member_role > 0) {
	$list .= "<STRONG>Administration</STRONG><P>";
	$list .= "<A HREF=member_create.php><FONT SIZE=2>Create a New Member Account</FONT></A><BR>";
	$list .= "<A HREF=member_to_edit.php><FONT SIZE=2>Edit a Member Account</FONT></A><BR>";
	$list .= "<A HREF=member_contact_create.php?mode=admin><FONT SIZE=2>Add a Joint Member to an Existing Account</FONT></A><BR>";
	$list .= "<A HREF=member_contact_to_edit.php><FONT SIZE=2>Edit a Joint Member</FONT></A><BR>";
}
if ($cUser->member_role > 1) {
	$list .= "<A HREF=trade_reverse.php><FONT SIZE=2>Reverse an Exchange that was Made in Error</FONT></A><BR>";
}

$p->DisplayPage($list);

?>
