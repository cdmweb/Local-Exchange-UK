<?php

include_once("includes/inc.global.php");
include_once("classes/class.info.php");
//include("includes/inc.forms.php");

//must be committee member and above
$cUser->MustBeLevel(1);


$page_id = (!empty($_GET["page_id"])) ? $_GET["page_id"] : null;
$cInfoEdit = new cInfoEdit();

if(!empty($page_id)){
	$cInfoEdit->Load($page_id);
	$p->page_title = "Edit page '". $cInfoEdit->title ."'";
	//CT doesnt go through build function - todo - should it?
	$cInfoEdit->form_action = "update";
}else{
	$p->page_title = "Create new page";
	//CT doesnt go through build function - todo - should it?
	$cInfoEdit->form_action = "create";
}


if ($_POST["submit"]){
	$vars = array();
	$vars['page_id'] = $_POST["page_id"];
	$vars['form_action'] = $_POST["form_action"];
	$vars['active'] = $_POST["active"];
	$vars['title'] = $_POST["title"];
	$vars['body'] = $_POST["body"];
	$vars['permission'] = $_POST["permission"];
	$vars['member_id_author'] = $cUser->getMemberId();
	$cInfoEdit->Build($vars);

	//TODO: less hacky approcach. 
	$error_message = "";
	// error - no title
	if(strlen($cInfoEdit->title) < 1) $error_message .= "Title is missing. ";

	// error - no content set
	if(strlen($cInfoEdit->body) < 1)  $error_message .= "Content is missing. ";

	$is_saved = 0;
	if(empty($error_message)) {
		$is_saved = $cInfoEdit->Save();
	} else{
		$cErr->Error($error_message);
	}
	//return message success or fail	
	
	//redirect to page if saved
	if(!empty($is_saved)){
		header("location:" . HTTP_BASE . "/pages.php?page_id={$cInfoEdit->page_id}&form_action={$cInfoEdit->form_action}");
	} 
}
//show form
$output .= $cInfoEdit->Display();


$p->DisplayPage($output);
