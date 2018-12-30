<?php
	include_once("includes/inc.global.php");
	
	$cUser->MustBeLoggedOn();
	$p->site_section = EXCHANGES;

	$member = new cMember;

	if($_REQUEST["mode"] == "self") {
		$member = $cUser;
		$p->page_title .= "<a href='member_profile_all_in_one.php'>My profile</a> / ";
	} else {
		$member->LoadMember($_REQUEST["member_id"]);
		$p->page_title = "<a href='member_directory.php'>Members</a> / <a href='member_summary.php?member_id={$member->GetMemberId()}'>{$member->AllNames()}</a> / ";
	}





	$p->page_title .= "Exchange History";

	include("classes/class.trade.php");
	
	
	
	if ($member->getBalance() > 0)
		$cssClass = "positive";
	else
		$cssClass = "negative";
		
	
	
	$list = $p->Wrap($p->Wrap("Current Balance: ", "span", "label") . $p->Wrap($member->getBalance() . " ". UNITS, "span", "value ". $cssClass), "p", "balance");	

	$trade_group = new cTradeGroup($member->getMemberId());
	$trade_group->LoadTradeGroup("individual");
	$list .= $trade_group->DisplayTradeGroupUser($member->getBalance());
	
	$p->DisplayPage($list);
	

	
?>
	
