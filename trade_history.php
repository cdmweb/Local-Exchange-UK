<?php
	include_once("includes/inc.global.php");
	include_once("classes/class.trade.php");

	$cUser->MustBeLoggedOn();
	$p->site_section = EXCHANGES;

	

	//if ($_REQUEST["mode"] == "admin" || $_REQUEST["mode"] == "other") {
	if ($_REQUEST["member_id"] && $cUser->getMemberId() != $_REQUEST["member_id"]) {
		$member = new cMember;
		$member->LoadMember($_REQUEST["member_id"]);
		$member_id = $member->getMemberId();

		
	}else {
		$member = $cUser;
		$member_id = $member->getMemberId();
		$page_title .= "My Trade History";
	} 
	$output = "<p><a href='member_summary.php?member_id={$member_id}'>Profile</a> | <a href='trade_history.php?member_id={$member_id}'>Trade history</a></p>";

	$status_label = ($member->getStatus() == "I") ? " - Inactive" : "";
	$p->page_title = "{$member->getAllNames()} (#{$member_id}{$status_label}) Trade History";


	if ($cUser->getMemberRole() ){
		$string = file_get_contents(TEMPLATES_PATH . '/menu_quick_edit.php', TRUE);
		$output .= $p->ReplaceVarInString($string, '$member_id', $member_id);
	}	
	
	
	$cssClass = ($member->getBalance() > 0) ? "positive" : "negative";
		
	
	
	$output .= $p->Wrap($p->Wrap("Current Balance: ", "span", "label") . $p->Wrap($member->getBalance() . " ". UNITS, "span", "value ". $cssClass), "p", "large");	

	$trade_group = new cTradeGroup($member->getMemberId());
	$trade_group->LoadTradeGroup("individual");
	//$output .= $trade_group->DisplayTradeGroupUser($member->getBalance());
	$output .= $trade_group->DisplayTradeGroupUser();
	
	$p->DisplayPage($output);
	

	
?>
	
