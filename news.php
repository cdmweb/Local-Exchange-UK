<?php
include_once("includes/inc.global.php");
include("classes/class.news.php");
include("classes/class.uploads.php");

$p->site_section = EVENTS;
$p->page_title = "News and Events";

$output = "<P><BR>";

$news = new cNewsGroup();
$news->LoadNewsGroup();
$newstext = $news->DisplayNewsGroup();
if($newstext != "")
	$output .= $newstext;
else
	$output .= "There are no current news items.<P>";

$newsletters = new cUploadGroup("N");

if($newsletters->LoadUploadGroup()) {
	$output .= "<I>To read the latest ". SITE_SHORT_TITLE . " newsletter, go <A HREF=newsletters.php>here</A>.</I>";
}

$p->DisplayPage($output);


?>
