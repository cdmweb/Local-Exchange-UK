<?php
include_once("includes/inc.global.php");
$p->site_section = ADMINISTRATION;
$p->page_title = "Administration Menu";

$cUser->MustBeLevel(1);

$query = $cDB->Query("SELECT sum(balance) from ". DATABASE_MEMBERS .";");
		
if($row = mysql_fetch_array($query)) {
		$balance = $row[0];
}			
			
$list = "<STRONG>Current Balance is: $balance</STRONG><P>";

$list .= "<table border=0 cellpadding=5>";

$list .= "<tr valign=top>";
$list .= "<td>";
$list .= "<STRONG>Accounts</STRONG><P>";
$list .= "<A HREF=member_create.php><FONT SIZE=2>Create a New Member Account</FONT></A><BR>";
$list .= "<A HREF=member_to_edit.php><FONT SIZE=2>Edit a Member Account</FONT></A><BR>";
if ($cUser->member_role > 1) {
	$list .= "<A HREF=photo_to_edit.php><FONT SIZE=2>Edit a Member Photo</FONT></A> <font color=red>New!</font><BR>";
}
if ($cUser->member_role > 1) {
	$list .= "<A HREF=member_choose.php?action=member_status_change&inactive=Y><FONT SIZE=2>Inactivate/Re-activate a Member Account</FONT></A><BR>";
}
$list .= "<A HREF=member_contact_create.php?mode=admin><FONT SIZE=2>Add a Joint Member to an Existing Account</FONT></A><BR>";
$list .= "<A HREF=member_contact_to_edit.php><FONT SIZE=2>Edit/Delete a Joint Member</FONT></A><BR>";

if ($cUser->member_role > 1) {
	$list .= "<A HREF=member_unlock.php><FONT SIZE=2>Unlock Account and Reset Password</FONT></A><BR>";
}
echo OVRIDE_BALANCES;

if (OVRIDE_BALANCES==true && $cUser->member_role > 1) // Only display Override Balance link if it is turned on in config file
	$list .= "<A HREF=balance_to_edit.php><FONT SIZE=2>Override Member Account Balance</FONT></A><BR>";

if ($cUser->member_role>1)
	$list .= "<a href=manage_restrictions.php><font size=2>Manage Account Restrictions</font></a> <font color=red>New!</font>";
	
$list .= "</td><td>";


if ($cUser->member_role > 1) {
	$list .= "<STRONG>Exchanges</STRONG><P>";
	$list .= "<A HREF=member_choose.php?action=trade><FONT SIZE=2>Record an Exchange for a Member</FONT></A><BR>";
	$list .= "<A HREF=trade_reverse.php><FONT SIZE=2>Reverse an Exchange that was Made in Error</FONT></A><BR>";
	$list .= "<A HREF=member_choose.php?action=feedback_choose><FONT SIZE=2>Record Feedback for a Member</FONT></A><P>";
}
$list .= "</td></tr>";
$list .= "<tr valign=top><td>";
$list .= "<strong>Listings</strong><p>";

$list .= "<em>Offers</em><P>";
$list .= "<A HREF=listing_create.php?type=Offer&mode=admin><FONT SIZE=2>Create a New Offer Listing for a Member</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_to_edit&get1=type&get1val=Offer><FONT SIZE=2>Edit a Member's Offered Listing</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_delete&get1=type&get1val=Offer><FONT SIZE=2>Delete a Member's Offered Listing</FONT></A><P>";

$list .= "<em>Wants</em><P>";
$list .= "<A HREF=listing_create.php?type=Want&mode=admin><FONT SIZE=2>Create a New Want Listing for a Member</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_to_edit&get1=type&get1val=Want><FONT SIZE=2>Edit a Member's Wanted Listing</FONT></A><BR>";
$list .= "<A HREF=member_choose.php?action=listing_delete&get1=type&get1val=Want><FONT SIZE=2>Delete a Member's Wanted Listing</FONT></A><P>";

$list .= "<em>Miscellaneous</em><P>";
$list .= "<A HREF=member_choose.php?action=holiday><FONT SIZE=2>Member Going on Holiday</FONT></A>";
if ($cUser->member_role > 1) {
	$list .= "<BR><A HREF=category_create.php><FONT SIZE=2>Create a New Listing Category</FONT></A><BR>";
	$list .= "<A HREF=category_choose.php><FONT SIZE=2>Edit/Delete Listing Category</FONT></A>";
}
$list .= "<P>";
$list .= "</td><td>";

$list .= "<p><STRONG>Content</STRONG><P>";
$list .= "<em>Information Pages</em><p>";
$list .= "<A HREF=create_info.php><FONT SIZE=2>Create a New Info Page</FONT></A><BR>";
$list .= "<A HREF=edit_info.php><FONT SIZE=2>Edit Info Pages</FONT></A><BR>";
$list .= "<A HREF=delete_info.php><FONT SIZE=2>Delete an Info Page</FONT></A><BR>";
$list .= "<A HREF=info_permissions.php><FONT SIZE=2>Edit Info Page Permissions</FONT></A> <font size=2 color=red>New!</font> <BR>";
$list .= "<A HREF=info_url.php><FONT SIZE=2>See Info Page URL's</FONT></A><p>";

$list .= "<em>News & Events</em><p>";
$list .= "<A HREF=news_create.php><FONT SIZE=2>Create a News Item</FONT></A><BR>";
$list .= "<A HREF=news_to_edit.php><FONT SIZE=2>Edit a News Item</FONT></A><BR>";
$list .= "<A HREF=newsletter_upload.php><FONT SIZE=2>Upload a Newsletter</FONT></A><BR>";
$list .= "<A HREF=newsletter_delete.php><FONT SIZE=2>Delete Newsletters</FONT></A><BR>";

$list .= "</td></tr>";

$list .= "<tr valign=top><td>";

$list .= "<strong>Admin Fees</strong><p>";

if (TAKE_MONTHLY_FEE && $cUser->member_role > 1) {
    $ts = time();

   // $list .= "<strong>Monthly fee</strong><p>";
   
   // File missing??
 //   $list .= "<a href='monthly_fee_list.php'>List of monthly fees</a><br>";
    // CID = Confirmation ID.
    $list .= "<a href='take_monthly_fee.php?CID=$ts'>
                <font size=2>Take Monthly Fee</font></a><br>";
    $list .= "<a href='refund_monthly_fee.php'>
                <font size=2>Refund Monthly Fee</font></a><p>";
}

if (TAKE_SERVICE_FEE==true && $cUser->member_role > 1) {
	
	$list .= "<p><a href='service_charge.php?CID=$ts'>
                <font size=2>Take One-Off Service Charge</font></a> <font color=red>New!</font><br>
                <a href='refund_service_charge.php'>
                <font size=2>Refund One-Off Service Charge</font></a> <font color=red>New!</font><p>";
}
$list .= "</td><td>";

$list .= "<STRONG>System & Reporting</STRONG><P>";
if ($cUser->member_role > 1) {
	$list .= "<A HREF=settings.php><FONT SIZE=2>Site Settings</FONT></A> <font color=red>New!</font><BR>";
	$list .= "<A HREF=mysql_backup.php><FONT SIZE=2>MySQL Backup</FONT></A> <font color=red>New!</font><BR>";

	$list .= "<A HREF=contact_all.php><FONT SIZE=2>Send an Email to All Members</FONT></A><BR>";
}
$list .= "<A HREF=report_no_login.php><FONT SIZE=2>View Members Not Yet Logged In</FONT></A><BR><p>";

$list .= "</td></tr></table>";

$p->DisplayPage($list);

?>
