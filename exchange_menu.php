<?php
include_once("includes/inc.global.php");
include_once("classes/class.trade.php");
$p->site_section = EXCHANGES;
$p->page_title = "Exchanges";

$cUser->MustBeLoggedOn();

$pending = new cTradesPending($_SESSION["user_login"]);


$menuArray = array();
$menuArray[] = $p->MenuItemArray("Invoices and trades pending", "trades_pending.php");
$menuArray[] = $p->MenuItemArray("Record an exchange", "trade.php?mode=self");
$menuArray[] = $p->MenuItemArray("View my balance and trade history", "trade_history.php?mode=self");
$menuArray[] = $p->MenuItemArray("View my feedback", "feedback_all.php?mode=self");
$menuArray[] = $p->MenuItemArray("Leave Feedback for a Recent Exchange", "feedback_choose.php?mode=self");
$menuArray[] = $p->MenuItemArray("View All Trades in a Specified Time Period", "timeframe_choose.php?action=trade_history_all");
$menuHtml = $p->Menu($menuArray);
//$title = $p->Wrap("Wanted listings", "h3");
$output .= $p->Wrap($menuHtml, "div", "col");

$p->DisplayPage($output);

?>
