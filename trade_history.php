<?php
	include_once("includes/inc.global.php");
	
	$cUser->MustBeLoggedOn();
	$p->site_section = EXCHANGES;

	$member = new cMember;
	$member->LoadMember($_REQUEST["member_id"]);
	$member_id = $member->getMemberId();

	$status_label = ($member->getMemberId() = "I") ? "- Inactive" : "";
	if($_REQUEST["mode"] == "self") {
		$p->page_title .= "My Trade History";
	} else {
		$p->page_title = "Trade history for {$member->getAllNames()} (#{$member_id}{$status_label})";
	}


	include("classes/class.trade.php");
	if ($cUser->getMemberRole() ){
		$output = $p->Wrap("Quick edit: <a href=\"member_edit.php?mode=admin&member_id={$member_id}\">[Profile]</a> <a href=\"listings_found.php?type=Offer&mode=admin&member_id={$member_id}\">[Offers]</a> <a href=\"listings_found.php?type=Want&mode=admin&member_id={$member_id}\">[Wants]</a> <a href=\"member_edit.php?mode=admin&member_id={$member_id}\">[Joint Member]</a>", "div", "admin-actions");
	}
	
	
	if ($member->getBalance() > 0)
		$cssClass = "positive";
	else
		$cssClass = "negative";
		
	
	
	$output .= $p->Wrap($p->Wrap("Current Balance: ", "span", "label") . $p->Wrap($member->getBalance() . " ". UNITS, "span", "value ". $cssClass), "p", "large");	

	$trade_group = new cTradeGroup($member->getMemberId());
	$trade_group->LoadTradeGroup("individual");
	//$output .= $trade_group->DisplayTradeGroupUser($member->getBalance());
	$output .= $trade_group->DisplayTradeGroupUser();
	
	$p->DisplayPage($output);
	

	
?>
	
