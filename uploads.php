<?php

include_once("includes/inc.global.php");
include("classes/class.uploads.php");
$p->site_section = EVENTS;
$p->page_title = "Uploads";

$output = "<P><BR>";

$uploadGroup = new cUploadGroupCT();
$uploadGroup->LoadUploadGroup();

$i=0;
$groupTitle="";
foreach($uploadGroup->uploads as $upload) {

	$block="";
	$block .=$p->Wrap($upload->DisplayURL(),"span", "col");
	$block .=$p->Wrap($upload->filename,"span", "col");
	$block .=$p->Wrap($upload->upload_date->ShortDate(),"span", "col shortdate");
	$block .=$p->Wrap($upload->note,"span", "col");
	if($groupTitle != $upload->type_text) {
		$groupTitle = $upload->type_text;
		$output .= $p->Wrap($groupTitle, "h2");
	}
	$output .= $p->Wrap($block, "p", "line");
	$i++;
}

if ($i == 0)
	$output .= "Nothing has been uploaded";
else
	$output .= "</UL>";

$p->DisplayPage($output);

?>
