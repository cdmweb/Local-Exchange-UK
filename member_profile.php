<?php
include_once("includes/inc.global.php");
$p->site_section = PROFILE;
$p->page_title = "Member Profile";

$cUser->MustBeLoggedOn();

$list .= "<A HREF=password_change.php><FONT SIZE=2>Change My Password</FONT></A><BR>";
$list .= "<A HREF=member_edit.php?mode=self><FONT SIZE=2>Edit My Personal Information</FONT></A><BR>";

if (ALLOW_IMAGES==true)
	$list .= "<A HREF=member_photo_upload.php?mode=self><FONT SIZE=2>Upload/Change Photo of Myself</FONT></A><BR>";

$list .= "<A HREF=member_contact_create.php?mode=self><FONT SIZE=2>Add a Joint Member to My Account</FONT></A><BR>";
$list .= "<A HREF=member_contact_choose.php><FONT SIZE=2>Edit/Delete a Joint Member</FONT></A><P>";

if (ALLOW_INCOME_SHARES==true)
	$list .= "<A HREF=income_ties.php><FONT SIZE=2>Manage Income Shares</FONT></A><p>";

/*[chris]*/
$list .= '<a href=member_summary.php?member_id='.$cUser->member_id.'><font size=2>View My Profile (as others see it)</font></a><p>';

$p->DisplayPage($list);

?>
