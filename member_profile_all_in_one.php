<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->MustBeLoggedOn();

$p->page_title = "My dashboard";
$list = $p->Wrap("Hola, ". $cUser->AllFirstNames() . "!", "h3");

//todo: message for restricted?
if ($cUser->AccountIsRestricted()) $list .= LEECH_NOTICE;

$menuArray = array();

$menuArray[] = $p->MenuItemArray("View my profile", "member_summary.php?member_id=" . $cUser->getMemberId());
$menuArray[] = $p->MenuItemArray("Edit my personal information", "member_edit.php?mode=self");
$menuArray[] = $p->MenuItemArray("Add a joint member to my account", "member_contact_create.php?mode=self");
$menuArray[] = $p->MenuItemArray("Edit a joint member", "member_contact_choose.php");
$menuArray[] = $p->MenuItemArray("Change my password", "password_change.php");
$menuHtml = $p->Menu($menuArray);
//CT - rewrote page so that its easier to read and manage, plus modern html
$title = $p->Wrap("Member Settings", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");

/*
//core and above
if ($cUser->getMemberRole() > 0) {
	$menuArray = array();
	$menuArray[] = $p->MenuItemArray("Create a new member account", "member_create.php");
	$menuArray[] = $p->MenuItemArray("Edit a member account", "member_to_edit.php");
	$menuArray[] = $p->MenuItemArray("Add a joint member to an existing account", "member_contact_create.php?mode=admin");
	$menuArray[] = $p->MenuItemArray("Edit a joint member", "member_contact_to_edit.php");
	//admin
	if ($cUser->getMemberRole() > 1) {
		$menuArray[] = $p->MenuItemArray("Reverse an exchange that was made in error", "trade_reverse.php");
	}
	$menuHtml = $p->Menu($menuArray);
	$title = $p->Wrap("Administration", "h3");
	$list .= $p->Wrap($title . $menuHtml, "div", "col");
}
*/
$menuArray = array();
$menuArray[] = $p->MenuItemArray("View offered listings", "listings_found.php?type=Offer&member_id=" . $cUser->getMemberId());
$menuArray[] = $p->MenuItemArray("Create new offer listing", "listing_create.php?type=Offer");
$menuArray[] = $p->MenuItemArray("Edit offered listings", "listing_to_edit.php?type=Offer");
$menuArray[] = $p->MenuItemArray("Delete offered listings", "listing_delete.php?type=Offer");
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Offered listings", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");


$menuArray = array();
$menuArray[] = $p->MenuItemArray("View wanted listings", "listings.php?type=Want");
$menuArray[] = $p->MenuItemArray("Create new wanted listing", "listing_create.php?type=Want");
$menuArray[] = $p->MenuItemArray("Edit wanted listings", "listing_to_edit.php?type=Want");
$menuArray[] = $p->MenuItemArray("Delete wanted listings", "listing_delete.php?type=Want");
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Wanted listings", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");

$menuArray = array();
$menuArray[] = $p->MenuItemArray("Record an exchange", "trade.php");
$menuArray[] = $p->MenuItemArray("View my balance and exchange history", "trade_history.php?mode=self");
$menuArray[] = $p->MenuItemArray("View another member's exchange history", "trades_to_view.php");
$menuHtml = $p->Menu($menuArray);
$title = $p->Wrap("Exchanges", "h3");
$list .= $p->Wrap($title . $menuHtml, "div", "col");

$p->DisplayPage($list);

?>
