<?php
include_once("includes/inc.global.php");
include("classes/class.uploads.php");

$cUser->MustBeLevel(1);

$p->site_section = EVENTS;
$p->page_title = "Upload a Newsletter";

$upload = new cUploadForm;
$output = $upload->DisplayUploadForm("newsletter_save.php", array("Description"));

$p->DisplayPage($output);
?>
