<?php
	include_once("includes/inc.global.php");
	include_once("classes/class.trade.php");

	$cUser->MustBeLoggedOn();
	$p->site_section = EXCHANGES;

	//if ($_REQUEST["mode"] == "admin" || $_REQUEST["mode"] == "other") {
	$member_id = (!empty($_REQUEST["member_id"])) ? $_REQUEST["member_id"] : $cUser->getMemberId();

	$member = new cMemberConcise();
	$member->Load($member_id);	
	 
	//print_r($member_id);
	$output = "<p><a href='member_summary.php?member_id={$member_id}'>Profile</a> | <a href='trade_history.php?member_id={$member_id}'>Trade history</a></p>";

	$status_label = ($member->getStatus() == "I") ? " - Inactive" : "";
	$p->page_title = "{$member->getDisplayName()} (#{$member_id}{$status_label}) Trade History";


	if ($cUser->getMemberRole() ){
		$string = file_get_contents(TEMPLATES_PATH . '/menu_quick_edit.php', TRUE);
		$output .= $p->ReplaceVarInString($string, '$member_id', $member_id);
	}	
	
	
	$cssClass = ($member->getBalance() > 0) ? "positive" : "negative";
		
	
	
	$output .= $p->Wrap($p->Wrap("Current Balance: ", "span", "label") . $p->Wrap($member->getBalance() . " ". UNITS, "span", "value ". $cssClass), "p", "large");	

	$trade_group = new cTradeGroup($member->getMemberId());
	$trade_group->LoadTradeGroup();
	//$output .= $trade_group->DisplayTradeGroupUser($member->getBalance());
	$output .= $trade_group->DisplayTradeGroup();
	
	$p->DisplayPage($output);
	

	
?>
	
