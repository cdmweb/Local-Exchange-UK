<?php
	include_once("includes/inc.global.php");
	
	$cUser->MustBeLoggedOn();
	$p->site_section = EXCHANGES;
	$p->page_title = "Exchange History";

	include("classes/class.trade.php");
	
	$member = new cMember;
	
	if($_REQUEST["mode"] == "self") {
		$member = $cUser;
	} else {
		$member->LoadMember($_REQUEST["member_id"]);
		$p->page_title .= " for ".$member->PrimaryName();
	}
	
	if ($member->balance > 0)
		$color = "#4a5fa4";
	else
		$color = "#554f4f";
		
	
	
	$list = "<B>Current Balance: </B><FONT COLOR=". $color .">". $member->balance . " ". UNITS ."</FONT><P>";	

	$trade_group = new cTradeGroup($member->member_id);
	$trade_group->LoadTradeGroup("individual");
	$list .= $trade_group->DisplayTradeGroup();
	
	$p->DisplayPage($list);
	

	
?>
	
