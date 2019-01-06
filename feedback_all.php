<?php

include_once("includes/inc.global.php");
	
include("classes/class.feedback.php");
	
$cUser->MustBeLoggedOn();
$member = new cMember;
if($_REQUEST["mode"] == "other"){
	$member->LoadMember($_REQUEST["member_id"]);
}
else{
	$member=$cUser;
}
$member_id = $member->getMemberId();
	
$p->site_section = SECTION_FEEDBACK;
$p->page_title = "Feedback for {$member->getAllNames()} (#{$member_id})";

$feedbackgrp = new cFeedbackGroup;
$feedbackgrp->LoadFeedbackGroup($member_id);

if (isset($feedbackgrp->feedback)) {
	$output = $feedbackgrp->DisplayFeedbackTable($member_id);
} else  {
	if($_REQUEST["mode"] == "self")
		$output = "You don't have any feedback yet.";
	else
		$output = "This member does not have any feedback yet.";
}

$p->DisplayPage($output);
	
?>	
