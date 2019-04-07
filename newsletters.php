<?php

include_once("includes/inc.global.php");
include("classes/class.uploads.php");
$p->site_section = EVENTS;
$p->page_title = "Newsletters";

$output = "<P><BR>";

$newsletters = new cUploadGroup("N");
$newsletters->LoadUploadGroup();

$i=0;
$cErr->Error(print_r($newsletters, true));
while($newsletter = $newsletters->uploads){
	
	// if($i == 0) {
	// 	$i++;
	// 	$output .= "<p><strong>Latest Newsletter:</strong> {$newsletter->DisplayURL()}</p>
	// 				<h3>Archives</h3>
	// 				<ul>
	// 				";
	// } 
	// else {
	// 	$output .= "<li>{$newsletter->DisplayURL()}</li>";
	// }
}
$output .= ($i == 0) ? "<p>No newsletters found.</p>" : "</ul>";

$p->DisplayPage($output);

?>
