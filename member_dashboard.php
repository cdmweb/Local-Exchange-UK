<?php
//dashboard page - instead of member_profile
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->MustBeLoggedOn();
//todo - could do masquerade for buddies and admins?
$member_id=$cUser->getMemberId();

$p->page_title = "My dashboard";
$list = $p->Wrap("Hola, ". $cUser->getDisplayName() . "!", "h3");

//todo: message for restricted?
if (!is_null($cUser->getRestriction())) $list .= LEECH_NOTICE;


// offered
//$member_id = $_REQUEST["member_id"];

// $listings = new cListingGroupEdit();
// //$listings->Load($member_id, $category_id, $keyword, $timeframe, $type_code);

// $listings->Load($cUser->getMemberId(), "%","%", 0, OFFER_LISTING_CODE);



// 	{$listings->Display()}
// 	";
$pos_neg = ($cUser->getbalance() > 0) ? "positive" : "negative";
$units = UNITS;
$output .= "
	<!--START include allinone_menu -->
	Page design: Stats. List all offers, list all wants here. 
	Last activity on your account. 
	invoices outstanding, to pay. status. 
	future: recommedned trading partners. velocity of trading.

	<div class=\"summary\"><span class=\"label\">Current balance: </span> <span class=\"value {$pos_neg}\">{$cUser->getbalance()}</span> {$units}. &nbsp;<a href=\"trade_history.php?member_id={$member_id}\" class=\"\">Your exchange history</a></div>
	<div class=\"col\">
		<h3>Member settings</h3>
		<ul>
			<li><a href=\"member_detail.php?member_id={$member_id}\">View my profile</a></li>
			<li><a href=\"member_edit.php?member_id={$member_id}\">Edit my personal information</a></li>
			<li><a href=\"member_contact_create.php?member_id={$member_id}\">Add a joint member to my account</a></li>
			<li><a href=\"member_contact_choose.php?member_id={$member_id}\">Edit a joint member</a></li>
			<li><a href=\"password_change.php\">Change my password</a></li>
		</ul>
	</div>

	<div class=\"col\">
		<h3>Exchanges</h3>
		<ul>
			<li><a href=\"trade.php?action=transfer\">Transfer '". UNITS . "' to another member</a></li>
			<li><a href=\"trade.php?action=invoice\">Invoice another member</a></li>
			<li><a href=\"trade_history.php?member_id={$member_id}\">View my exchange history</a></li>
			<!-- <li><a href=\"trades_to_view.php\">View another member\'s exchange history</a></li> -->
		</ul>
	</div>
	<div class=\"col\">
		<h3>My Offers</h3>
		<ul>
			<li><a href=\"listing_manage.php?type=". OFFER_LISTING_CODE . "&member_id={$member_id}\">Manage '". OFFER_LISTING . "' listings</a></li>
			<li><a href=\"listing_edit.php?type=". OFFER_LISTING_CODE . "\">Add '". OFFER_LISTING . "' listing</a></li>
		</ul>
	</div>";

$output .= "
	<div class=\"col\">
		<h3>My Wants</h3>
		<ul>
			<li><a href=\"listing_manage.php?type=". WANT_LISTING_CODE . "&member_id={$member_id}\">Manage '".WANT_LISTING."' listings</a></li>
			<li><a href=\"listing_edit.php?type=". WANT_LISTING_CODE . "\">Add '". WANT_LISTING . "' listing</a></li>
		</ul>
	</div>";

$output .= "<div class=\"col\">
	<h3>Trades</h3>
	<ul>
		<li>Feedback stuff</li>
	</ul>
	</div>
	<!--END include allinone_menu -->
	";


$p->DisplayPage($output);

?>
