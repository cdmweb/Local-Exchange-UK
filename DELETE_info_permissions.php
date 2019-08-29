<?php

include_once("includes/inc.global.php");
include_once("classes/class.info.php");
include("includes/inc.forms.php");

$cUser->MustBeLevel(2); // Wouldn't make sense to allow anyone below the top Admin level to edit page permissions 

$p->site_section = EVENTS;

$p->page_title = "Edit Information Page Permissions";

$output = "*The Permissions level denotes the MINIMUM level a user must be to view the page. So, a page set with 'Committee' permissions would be visible to Committee AND Admin users, but not to those below Committee level (i.e. Members and Guests). Setting the permissions to 'Guests' effectively removes the permissions from that page and renders it visible to anyone and everyone.";

$pgs = cInfo::LoadPages();

if ($_REQUEST["process"]==true) {
	
	foreach($pgs as $pg) {
	
		$q = 'UPDATE cdm_pages set permission='.$_REQUEST["p".$pg["id"]].' where id='.$cDB->EscTxt($pg["id"]).'';
		
		$cDB->Query($q);
	}
	
	$output = "Info page permissions updated successfully.";
	
	$p->DisplayPage($output);

	exit;
}

if ($pgs) {
	
	$output .= "<form method=POST><input type=hidden name=process value=true>";
	
	$output .= "<table width=70%>";
		
	foreach($pgs as $pg) {
		
		$output .= "<tr><td>ID#".$pg["id"]."</td><td>".stripslashes($pg["title"])."</td><td>".doPermissionsSelect($pg)."</td></tr>";
	}
	
	$output .= "</table><p>";
	$output .= "<input type=submit value=\"Update Permissions\"></form>";
}
else
	$output .= "No info pages found!";
	
$p->DisplayPage($output);

function doPermissionsSelect($p) {
	
	$pTexts = Array("Guests","Members","Committee","Admin");
		
	$tmp = "<select name=p".$p["id"].">";
	
	foreach($pTexts as $id => $value) {
		
		$tmp .= "<option value=".$id." ";
		
		if ($p["permission"]==$id)
			$tmp .= "selected";
			
		$tmp .= ">".$value."</option>";
	}
	
	$tmp .= "</select>";
	
	return $tmp;
	
}

function permission2text($p) {
	
	if (!$p)
		$p = 0;
		
	$pTexts = Array("Guests","Members","Committee","Admin");
	
	return $pTexts[$p];
}