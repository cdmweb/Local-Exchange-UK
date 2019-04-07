<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->MustBeLoggedOn();

$p->page_title = "My dashboard";
$list = $p->Wrap("Hola, ". $cUser->getDisplayName() . "!", "h3");

//todo: message for restricted?
if ($cUser->AccountIsRestricted()) $list .= LEECH_NOTICE;

$string = '
<!--START include allinone_menu -->
<div class="col">
	<h3>Member settings</h3>
	<ul>
		<li><a href="member_summary.php?member_id={{member_id}}">View my profile</a></li>
		<li><a href="member_edit.php?member_id={{member_id}}">Edit my personal information</a></li>
		<li><a href="member_contact_create.php?member_id={{member_id}}">Add a joint member to my account</a></li>
		<li><a href="member_contact_choose.php?member_id={{member_id}}">Edit a joint member</a></li>
		<li><a href="password_change.php">Change my password</a></li>
	</ul>
</div>

<div class="col">
	<h3>Offered listings</h3>
	<ul>
		<li><a href="listings_found.php?type=Offer&member_id={{member_id}}">View offered listings</a></li>
		<li><a href="listing_create.php?type=Offer&member_id={{member_id}}">Create new offer listing</a></li>
		<li><a href="listing_to_edit.php?type=Offer&member_id={{member_id}}">Edit offered listings</a></li>
		<li><a href="listing_delete.php?type=Offer&member_id={{member_id}}">Delete offered listings</a></li>
	</ul>
</div>

<div class="col">
	<h3>Wanted listings</h3>
	<ul>
		<li><a href="listings_found.php?type=Want&member_id={{member_id}}">View wanted listings</a></li>
		<li><a href="listing_create.php?type=Want&member_id={{member_id}}">Create new wanted listing</a></li>
		<li><a href="listing_to_edit.php?type=Want&member_id={{member_id}}">Edit wanted listings</a></li>
		<li><a href="listing_delete.php?type=Want&member_id={{member_id}}">Delete wanted listings</a></li>
	</ul>
</div>
<div class="col">
	<h3>Exchanges</h3>
	<ul>
		<li><a href="trade.php">Record an exchange</a></li>
		<li><a href="trade_history.php?member_id={{member_id}}">View my balance and exchange history</a></li>
		<li><a href="trades_to_view.php">View another member\'s exchange history</a></li>
	</ul>
</div>
<!--END include allinone_menu -->
';
$variables = new stdClass();
$variables = (object) [
    'member_id' => $cUser->getMemberId()
];


$list .= $p->ReplacePlaceholders($string, $variables);


$p->DisplayPage($list);

?>
