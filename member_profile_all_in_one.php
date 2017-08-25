<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;
$p->page_title = "My Profile and Listings";

$cUser->MustBeLoggedOn();
$list ="";
//CT: put account restricted message here. todo: combine with more universal message function
//CT: neaten up the html, make it easier to read
	//my profile - todo - show content and controls on page
$list .= "<p>Welcome to ". SITE_SHORT_TITLE .", ". $cUser->person[0]->first_name ."!</p>";
if ($cUser->AccountIsRestricted()){
	$list .= "<p>" .LEECH_NOTICE . "</p>";
}

$list .= "<div class='balance'>Current balance: " . $cUser->balance . " " . UNITS . "</div> 
<div class='small'><a href='trade_history.php?mode=self'>Your trade history</a></div>";
$list .= "<h2>My profile</h2>
	<ul>
		<li><a href='password_change.php'>Change my password</a></li>
		<li><a href='member_summary.php?member_id=" .$cUser->member_id. "'>View my profile (as others would wee it)</a></li>
		<li><a href='member_edit.php?mode=self'>Edit my personal information</a></li>
	</ul>";
//offers
$list .= "<h2>My offers</h2>
	<ul>
		<li><a href='listing_create.php?type=Offer'>Create a new offer listing</a></li>
		<li><a href='listing_to_edit.php?type=Offer'>Edit offered listings</a></li>
		<li><a href='listing_delete.php?type=Offer'>Delete offered listing</a></li>
	</ul>";
//wants
$list .= "<h2>My wants</h2>
	<ul>
		<li><a href='listing_create.php?type=Want'>Create a new want listing</a></li>
		<li><a href='listing_to_edit.php?type=Want'>Edit wanted listings</a></li>
		<li><a href='listing_delete.php?type=Want'>Delete wanted listings</a></li>
	</ul>";
//wants
$list .= "<h2>Need a break?</h2>
	<p>If you are going on holiday - or just need a break - you can 
	<a href='/holiday.php?mode=self'>temporarily inactivate Listings</a></p>";

$p->DisplayPage($list);

?>
