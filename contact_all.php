<?php
include_once("includes/inc.global.php");
$p->site_section = SECTION_EMAIL;
$p->page_title = "Email all Members";

$cUser->MustBeLevel(2);

include("includes/inc.forms.php");

//
// First, we define the form
//
$form->addElement("static", null, "This email will go out to <i>ALL</i> members of ".SITE_LONG_TITLE.".", null);
$form->addElement("static", null, null, null);
$form->addElement("text", "subject", "Subject", array("size" => 30, "maxlength" => 50));
$form->addElement("static", null, null, null);
$form->addElement("textarea", "message", "Your Message", array("cols"=>65, "rows"=>10, "wrap"=>"soft"));
$form->addElement("static", null, null, null);

$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Send");

//
// Define form rules
//
$form->addRule("subject", "Enter a subject", "required");
$form->addRule("message", "Enter your message", "required");

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$p->DisplayPage($form->toHtml());
}

//
// The form has been submitted with valid data, so process it   
//
function process_data ($values) {
	global $p, $heard_from;
	
	$output = "";
	$errors = "";
	$all_members = new cMemberGroup;
	$all_members->LoadMemberGroup();
	
	foreach($all_members->members as $member) {
		if($errors != "")
			$errors .= ", ";
		
		if($member->person[0]->email != "")
			$mailed = mail($member->person[0]->email, $values["subject"], wordwrap($values["message"], 64) , "From:". EMAIL_ADMIN);
		else
			$mailed = true;
		
		if(!$mailed)
			$errors .= $member->person[0]->email;
	}
	if($errors == "")
		$output .= "Your message has been sent to all members.";
	else
		$output .= "There were errors sending the email to the following email addresses:<BR>". $errors;	
		
	$p->DisplayPage($output);
}



?>
