<?php
	include_once("includes/inc.global.php");
	
	$cUser->MustBeLoggedOn();
	$p->site_section = EXCHANGES;
	$p->page_title = "Exchange History";

	include_once("classes/class.trade.php");
	
	$from = new cDateTime($_REQUEST["from"]);
	$to = new cDateTime($_REQUEST["to"]);
	
	$output = "<B>For period from ". $from->ShortDate() ." to ". $to->ShortDate() ."</B><P>";	
	$vars = array("%", $_REQUEST["from"], $_REQUEST["to"]);
	$trade_group = new cTradeGroup();
	$trade_group->Load($vars);
	$output .= $trade_group->Display();
	
	$p->DisplayPage($output);
	

	
?>
	
