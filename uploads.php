<?php

include_once("includes/inc.global.php");
include_once("classes/class.uploads.php");
$p->site_section = EVENTS;
$p->page_title = "Uploads";

$output = "";

$uploadGroup = new cUploadGroupCT();
$uploadGroup->LoadUploadGroup();

$i=0;
$groupTitle="";

$output = "<table class='layout1'>
	<tr>
		<th>Name</th>
		<th>Published</th>
	</tr>";
foreach($uploadGroup->uploads as $upload) {
	$output .= "<tr>
					<td>{$upload->DisplayURL()}</td>
					<td>{$upload->upload_date}</td>

				</tr>";
	$i++;
}
$output .= "</table>";

if ($i == 0){
	$output .= "Nothing has been uploaded";
}else{
	$p->DisplayPage($output);
}
?>
