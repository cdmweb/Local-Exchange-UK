<?php

include_once("includes/inc.global.php");
include("classes/class.uploads.php");
$p->site_section = EVENTS;
$p->page_title = "Newsletters";

$output = "<P><BR>";

$newsletters = new cUploadGroup("N");
$newsletters->LoadUploadGroup();

$i=0;

foreach($newsletters->uploads as $newsletter) {
	if($i == 0) {
		$i = 1;
		$output .= '<B>Latest Newsletter:</B> '. $newsletter->DisplayURL();
	} else {
		if($i == 1) {
			 $output .= '<P><BR><B>Archives:</B><BR><UL>';
			 $i = 2;
		}
		$output .= '<LI>'. $newsletter->DisplayURL() .'</LI>';
	}
}

if ($i == 0)
	$output .= "No newsletters have yet been posted.";
else
	$output .= "</UL>";

$p->DisplayPage($output);

?>
