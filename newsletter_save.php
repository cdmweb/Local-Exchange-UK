<?php
include_once("includes/inc.global.php");
include("classes/class.uploads.php");

$cUser->MustBeLevel(1);

$p->site_section = EVENTS;
$p->page_title = "Newsletter Uploaded";

$upload = new cUpload("N", $_REQUEST["Description"]);
if($upload->SaveUpload())
	$output = "File uploaded.";
else
	$output = "There was a problem uploading the file.";

$p->DisplayPage($output);
?>
