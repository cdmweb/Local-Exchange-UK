<?php
	include_once("includes/inc.global.php");
	include_once("classes/class.trade.php");

	$cUser->MustBeLoggedOn();
	$p->site_section = EXCHANGES;

	//if ($_REQUEST["mode"] == "admin" || $_REQUEST["mode"] == "other") {
	$member_id = (!empty($_REQUEST["member_id"])) ? $_REQUEST["member_id"] : $cUser->getMemberId();

	$member = new cMemberConcise();
	$member->Load($member_id);	
	 
	$status_label = ($member->getStatus() == "I") ? " - Inactive" : "";
	$p->page_title = "{$member->getDisplayName()} (#{$member_id}{$status_label}) Trade History";	
	
	
	$cssClass = ($member->getBalance() > 0) ? "positive" : "negative";
		
	$output .= $p->Wrap($p->Wrap("Current balance: ", "span", "label") . $p->Wrap($member->getBalance() . " ". UNITS . ".", "span", "value ". $cssClass), "p", " summary");	
	
	$trades = new cTradeGroup();
	$trades->Load($member_id);
	//$output .= $trade_group->DisplayTradeGroupUser($member->getBalance());
	$output .= $trades->Display();
	
	$p->DisplayPage($output);
	

	
?>
	
