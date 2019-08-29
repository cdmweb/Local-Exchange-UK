<?php
include_once("includes/inc.global.php");

global $cErr, $cDB;

$cErr->SaveErrors();

if (!isset($redir_url))
{
	$redir_url = (isset($_REQUEST['location'])) ? $cDB->EscTxt($_REQUEST['location']) : null;
}
/*
if (!isset($redir_type))
{
	$redir_type = (isset($_GET['type']))? $_GET['type'] : null;
}

if (!isset($redir_item))
{
	$redir_item = (isset($_GET['item'])) $_GET['item'] : null;
}
*/
if (!empty($redir_url))	// a specific URL was requested.  Go there regardless of other variables.
{
	header("location:".$redir_url);
	exit;
}
/*
if (!empty($redir_type) && !empty($redir_item))
{
	header("location:" . HTTP_BASE[$redir_type]."?item=".$redir_item);
	exit;
}

if (!empty($redir_type))	// $item not specified
{
	header("location:" . HTTP_BASE[$redir_type]);
	exit;
}
*/

// dunno where to go.  Go home.
header("location:" . HTTP_BASE . "/member_dashboard.php");
exit;


?>
