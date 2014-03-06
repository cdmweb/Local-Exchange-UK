<?php
include_once("includes/inc.global.php");
include_once("classes/class.trade.php");
$p->site_section = EXCHANGES;
$p->page_title = "Exchanges";

$cUser->MustBeLoggedOn();

$pending = new cTradesPending($_SESSION["user_login"]);

$list = "<A HREF=trades_pending.php><FONT SIZE=2>Invoices and Transactions Pending</a> (".$pending->numIn." require action)</FONT><P>";
$list .= "<A HREF=trade.php?mode=self><FONT SIZE=2>Record an Exchange</FONT></A><BR>";
$list .= "<A HREF=trade_history.php?mode=self><FONT SIZE=2>View My Balance and Exchange History</FONT></A><BR>";
$list .= "<A HREF=trades_to_view.php><FONT SIZE=2>View Another Member's Exchange History</FONT></A><BR>";
$list .= "<A HREF=feedback_all.php?mode=self><FONT SIZE=2>View My Feedback</FONT></A><BR>";
$list .= "<A HREF=feedback_to_view.php><FONT SIZE=2>View Another Member's Feedback</FONT></A><BR>";
$list .= "<A HREF=feedback_choose.php?mode=self><FONT SIZE=2>Leave Feedback for a Recent Exchange</FONT></A><BR>";
$list .= "<A HREF=timeframe_choose.php?action=trade_history_all><FONT SIZE=2>View All Trades in a Specified Time Period</FONT></A>";

$p->DisplayPage($list);

?>
