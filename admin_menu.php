<?php
include_once("includes/inc.global.php");
$p->site_section = ADMINISTRATION;
$p->page_title = "Administration Menu";

$cUser->MustBeLevel(1);

$query = $cDB->Query("SELECT sum(balance) from ". DATABASE_MEMBERS .";");
		
if($row = $cDB->FetchArray($query)) {
		$balance = $row[0];
}			
			
$list = $p->Wrap("Current balance for system is {$balance}", "p", "balance");

// enrolment
$menuArray = array();
$menuArray[] = $p->MenuItemArray("Create new member account", "member_edit.php");
$menuArray[] = $p->MenuItemArray("Edit a Member Account", "member_choose.php?action=member_edit");
$menuArray[] = $p->MenuItemArray("Add a Joint Member to an Existing Account", "member_choose.php?action=member_contact_create");
$menuArray[] = $p->MenuItemArray("Edit/Delete a Joint Member", "member_contact_to_edit.php");
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Enrolment", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");

// support
$menuArray = array();
$menuArray[] = $p->MenuItemArray("View members not logged in", "report_no_login.php");
$menuArray[] = $p->MenuItemArray("Member Going on Holiday", "member_choose.php?action=holiday");
$menuArray[] = $p->MenuItemArray("Edit a Member Photo", "photo_to_edit.php");
if ($cUser->getMemberRole() > 1) { // if admin 
	$menuArray[] = $p->MenuItemArray("Inactivate/Re-activate a Member Account", "member_choose.php?action=member_status_change");
	$menuArray[] = $p->MenuItemArray("Unlock Account and Reset Password", "member_unlock.php");
}
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Support", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");

// transactions
$menuArray = array();
if (!empty(OVRIDE_BALANCES) && $cUser->getMemberRole() > 1) {// Only display Override Balance link if it is turned on in config file
	$menuArray[] = $p->MenuItemArray("Edit balances", "balance_to_edit.php?action=balance_to_edit");
}
if ($cUser->getMemberRole() > 1) { // if admin 
	$menuArray[] = $p->MenuItemArray("Manage account restrictions", "member_choose.php?action=manage_restrictions");
	$menuArray[] = $p->MenuItemArray("Manage invoices for a member", "member_choose.php?action=trades_pending");
	$menuArray[] = $p->MenuItemArray("Record an exchange for a member", "member_choose.php?action=trade");
	$menuArray[] = $p->MenuItemArray("Reverse an Exchange that was Made in Error", "trade_reverse.php?action=trade_reverse");
	$menuArray[] = $p->MenuItemArray("Record Feedback for a Member", "member_choose.php?action=feedback_choose");
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Transactions", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");
}
// offered listings
$menuArray = array();
$menuArray[] = $p->MenuItemArray("New Offered Listing for a Member", "listing_edit.php?type=Offer&form_mode=admin");
$menuArray[] = $p->MenuItemArray("Edit a Member's Offered Listing", "member_choose.php?action=listing_to_edit&get1=type&get1val=Offer");
$menuArray[] = $p->MenuItemArray("Delete a Member's Offered Listing", "member_choose.php?action=listing_delete&get1=type&get1val=Offer");
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Listings", "h3");
$subtitle = $p->Wrap("Offers", "h4");
$list .= $p->Wrap($title . $subtitle . $menuHtml, "div", "col");

// wanted listings
$menuArray = array();
$menuArray[] = $p->MenuItemArray("New Wanted Listing for a Member", "listing_edit.php?type=Want&form_mode=admin");
$menuArray[] = $p->MenuItemArray("Edit a Member's Wanted Listing", "listing_edit.php?type=Want&form_mode=admin");
$menuArray[] = $p->MenuItemArray("Delete a Member's Wanted Listing", "member_choose.php?action=listing_delete&get1=type&get1val=Want");
$menuHtml = $p->Menu($menuArray);
$subtitle = $p->Wrap("Wants", "h4");
$list .= $p->Wrap($subtitle . $menuHtml, "div", "col");


// content
$menuArray = array();
$menuArray[] = $p->MenuItemArray("Create a new page", "pages_edit.php");
$menuArray[] = $p->MenuItemArray("Manage pages", "pages_manage.php");
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Content", "h3");
$subtitle = $p->Wrap("Information", "h4");
$list .= $p->Wrap($title . $subtitle . $menuHtml, "div", "col");

// news
$menuArray = array();
$menuArray[] = $p->MenuItemArray("Create a News Item", "news_create.php");
$menuArray[] = $p->MenuItemArray("Edit a News Item", "news_to_edit.php?");
$menuArray[] = $p->MenuItemArray("Upload an item", "newsletter_upload.php");
$menuArray[] = $p->MenuItemArray("Delete an item", "newsletter_delete.php");
$menuHtml = $p->Menu($menuArray);
$subtitle = $p->Wrap("News &amp; Events", "h4");
$list .= $p->Wrap($subtitle . $menuHtml, "div", "col");

// Monthly fees
$menuArray = array();
$ts = time();
if (!empty(TAKE_MONTHLY_FEE) && $cUser->getMemberRole() > 1) {

   // $list .= "<strong>Monthly fee</strong><p>";
   
   // File missing??
 //   $list .= "<a href='monthly_fee_list.php'>List of monthly fees</a><br>";
    // CID = Confirmation ID.
	$menuArray[] = $p->MenuItemArray("Take Monthly Fee", "take_monthly_fee.php?CID=$ts");
	$menuArray[] = $p->MenuItemArray("Refund Monthly Fee", "refund_monthly_fee.php?CID=$ts");

}
if (!empty(TAKE_SERVICE_FEE) && $cUser->getMemberRole() > 1) {
	$menuArray[] = $p->MenuItemArray("Take One-Off Service Charge", "service_charge.php?CID=$ts");
	$menuArray[] = $p->MenuItemArray("Refund One-Off Service Charge", "refund_service_charge.php");
	
}
$menuHtml = "{$p->Menu($menuArray)}";


$title = $p->Wrap("Admin Fees", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");

if ($cUser->getMemberRole() > 1) { // if admin 
	$menuArray = array();
	$menuArray[] = $p->MenuItemArray("Site settings", "settings.php");
	$menuArray[] = $p->MenuItemArray("Edit or delete listing category", "category_choose.php");
	$menuArray[] = $p->MenuItemArray("MySQL Backup", "mysqli_backup.php");
	$menuHtml = $p->Menu($menuArray);
	$title = $p->Wrap("System &amp; Reporting", "h3");
	$list .= $p->Wrap($title . $menuHtml, "div", "col");
}

if ($cUser->getMemberRole() > 1) { // if admin 
	$menuArray = array();
	$menuArray[] = $p->MenuItemArray("Send an Email to All Members", "admin_contact_all.php");
	$menuHtml = $p->Menu($menuArray);
	$title = $p->Wrap("Miscellanious", "h3");
	$list .= $p->Wrap($title . $menuHtml, "div", "col");
}

$p->DisplayPage($list);

?>
