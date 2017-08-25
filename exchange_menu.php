<?php
include_once("includes/inc.global.php");
include_once("classes/class.trade.php");
$p->site_section = EXCHANGES;
$p->page_title = "My Trades";

$cUser->MustBeLoggedOn();

$pending = new cTradesPending($_SESSION["user_login"]);

$list .= "<div class='balance'>Current balance: " . $cUser->balance . " " . UNITS . "</div> 
<div class='small'><a href='trade_history.php?mode=self'>Your trade history</a></div><br />";

$list .= "<div class='section-menu'>
	<h2>My Feedback</h2>
	<ul>
		<li><a href='feedback_all.php?mode=self'>View my feedback</a></li>
		<!-- <li><a href='feedback_to_view.php'>View someone else's feedback</a></li> -->
		<li><a href='trade_history.php?mode=self'>Leave Feedback for a Recent Trade</a></li>
	</ul>
	<h2>My Trades</h2>
	<ul>
		<li><a href='trades_pending.php'>My pending trades</a> (".$pending->numIn." require action)</li>
		<li><a href='trade.php?mode=self'>Record a new trade</a></li>
	</ul>
	</div>";

$p->DisplayPage($list);

?>
