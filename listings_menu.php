<?php
include_once("includes/inc.global.php");
$p->site_section = LISTINGS;
$p->page_title = "Update Listings";

$cUser->MustBeLoggedOn();

$list = "<STRONG>Offered Listings</STRONG><P>";
$list .= "<A HREF=listing_create.php?type=Offer&mode=self><FONT SIZE=2>Create New Offer Listing</FONT></A><BR>";
$list .= "<A HREF=listing_to_edit.php?type=Offer&mode=self><FONT SIZE=2>Edit Offered Listings</FONT></A><BR>";
$list .= "<A HREF=listing_delete.php?type=Offer&mode=self><FONT SIZE=2>Delete Offered Listings</FONT></A><P>";

$list .= "<STRONG>Wanted Listings</STRONG><P>";
$list .= "<A HREF=listing_create.php?type=Want&mode=self><FONT SIZE=2>Create New Want Listing</FONT></A><BR>";
$list .= "<A HREF=listing_to_edit.php?type=Want&mode=self><FONT SIZE=2>Edit Wanted Listings</FONT></A><BR>";
$list .= "<A HREF=listing_delete.php?type=Want&mode=self><FONT SIZE=2>Delete Wanted Listings</FONT></A><P>";

$list .= "<STRONG>Miscellaneous</STRONG><P>";
$list .= "<A HREF=holiday.php?mode=self><FONT SIZE=2>Going on Holiday</FONT></A><BR>";

$p->DisplayPage($list);

?>
