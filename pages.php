<?php

include_once("includes/inc.global.php");
include("classes/class.info.php");

if ($_GET["destroySess"]==1) {
	
	if ($_GET["confirm"]==1) {
		session_destroy();
		echo "Session Destroyed. <a href=index.php>Continue</a>";
	}
	else {
		echo "Really Destroy Session? <a href=pages.php?destroySess=1&confirm=1>Yes</a> | <a href=javascript:history.back(1)>No (Go back)</a>";
	}
	
	exit;
}

$pg = cInfo::LoadOne($_REQUEST["id"]);

if ($pg["permission"]==3) // Admin
	$cUser->MustBeLevel(2);
else if ($pg["permission"]==2) // Committe
	$cUser->MustBeLevel(1);
else if ($pg["permission"]==1) // Members
	$cUser->MustBeLoggedOn();

$p->site_section = SECTION_INFO;
//CT show page
if($pg){
	$p->page_title = stripslashes($pg["title"]);
	if ($cUser->getMemberRole>0){
		$p->page_content .= '<div class=\"edit\"><a href=do_info_edit.php?id='.$_REQUEST["id"].'>[Edit]</a></div>';
	}
	$p->page_content .= stripslashes($pg["body"]);
}else{
	$p->page_title = "The page you requested does not exist.";
	$p->AddError("Page with ID " . $_REQUEST["id"] . " does not exist");


}
$p->DisplayPage();

?>