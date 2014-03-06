<?php
include_once("includes/inc.global.php");
$p->site_section = LISTINGS;
$p->page_title = "Create Listings";

$cUser->MustBeLoggedOn();

$list = "<STRONG>Offered Listings</STRONG><P>";
$list .= "<A HREF=listing_create.php?type=Offer><FONT SIZE=2>Create New Offer Listing</FONT></A><BR>";
$list .= "<A HREF=listing_to_edit.php?type=Offer><FONT SIZE=2>Edit Offered Listings</FONT></A><BR>";
$list .= "<A HREF=listing_delete.php?type=Offer><FONT SIZE=2>Delete Offered Listings</FONT></A><P>";

$list .= "<STRONG>Wanted Listings</STRONG><P>";
$list .= "<A HREF=listing_create.php?type=Want><FONT SIZE=2>Create New Want Listing</FONT></A><BR>";
$list .= "<A HREF=listing_to_edit.php?type=Want><FONT SIZE=2>Edit Wanted Listings</FONT></A><BR>";
$list .= "<A HREF=listing_delete.php?type=Want><FONT SIZE=2>Delete Wanted Listings</FONT></A><P>";

$p->DisplayPage($list);

?>
