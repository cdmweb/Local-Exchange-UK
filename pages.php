<?php

include_once("includes/inc.global.php");
/*
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
*/
//would like all the entities to be {entity}_id
$page_id = (!empty($_REQUEST["page_id"])) ? $_REQUEST["page_id"] : null;

$cInfo = new cInfo;
$cInfo->Load($page_id);

switch($cInfo->permission){
	case '3':
		$cUser->MustBeLevel(2); // Admin
	break;
	case '2':
		$cUser->MustBeLevel(1);// Committee
	break;
	case '1':
		$cUser->MustBeLoggedOn();// Members
	break;
}

if(!empty($cInfo->page_id)){
	$p->page_title = $cInfo->title;
	if($cUser->getMemberRole() > 1){
		$form_action = (!empty($_REQUEST["form_action"])) ? $_REQUEST["form_action"] : null;
		if($form_action == "update") {
			$output .= "<div class=\"response success\">Your changes have been saved.</div>";
		} elseif($form_action == "create"){
			$output .= "<div class=\"response success\">New page created.</div>";
		}
	}
	$output .=$cInfo->Display();
	$p->DisplayPage($output);

}else{
	$p->page_title = "Page not found (404)";
	$p->DisplayPage("The page you requested does not exist.");

}


?>