<?php

include_once("includes/inc.global.php");
	
include_once("classes/class.feedback.php");
	
$cUser->MustBeLoggedOn();
$member_id = (!empty($_REQUEST["member_id"])) ? $_REQUEST["member_id"] : $cUser->getMemberId();


//$member = (new cMemberConcise())->ConstructMember($member_id);
	
$p->site_section = SECTION_FEEDBACK;
$p->page_title = "Feedback for member (#{$member_id})";

//$output = "<h2>Feedback</h2>";
$feedback_group_as_seller = new cFeedbackGroup($member_id, "about");
//$feedback_group_as_seller->LoadFeedbackGroup($member_id, SELLER);

if(sizeof($feedback_group_as_seller->getFeedback()) > 0 ){
	$output .= $feedback_group_as_seller->Display();
} 
else{
	$output .= "<p>No feedback has been left for this member.</p>";
}

$output .= "<h2>Feedback left for others by #{$member_id}</h2>";

$feedback_group_as_buyer = new cFeedbackGroup($member_id, "author");
//$feedback_group_as_buyer->LoadFeedbackGroup();
if(sizeof($feedback_group_as_buyer->getFeedback()) > 0 ){
	$output .= $feedback_group_as_buyer->Display();
} 
else{
	$output .= "<p>No feedback has been left by this member.</p>";
}


$p->DisplayPage($output);
	
?>	
