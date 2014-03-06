<?php
include_once("includes/inc.global.php");
$p->site_section = SECTION_EMAIL;
$p->page_title = "Contact Us";

include("includes/inc.forms.php");

//
// First, we define the form
//
$form->addElement("static", null, "For more information on the " . SITE_LONG_TITLE . " or to find out how to become a member, please fill out our information request. Someone will get back to you soon. Please check our <A HREF=news.php>Events</A> page for our next New Member's Meeting if you would like to join our group!", null);
$form->addElement("static", null, null, null);
$form->addElement("text", "name", "Name");
$form->addElement("text", "email", "Email");
$form->addElement("text", "phone", "Phone");
$form->addElement("static", null, null, null);
$form->addElement("textarea", "message", "Your Message", array("cols"=>65, "rows"=>10, "wrap"=>"soft"));
$form->addElement("static", null, null, null);
$heard_from = array ("0"=>"(Select One)", "1"=>"Newspaper", "2"=>"Radio", "3"=>"Search Engine", "4"=>"Friend", "5"=>"Local Business", "6"=>"Article", "7"=>"Other");
$form->addElement("select", "how_heard", "How did you hear about us?", $heard_from);

$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Send");

//
// Define form rules
//
$form->addRule("name", "Enter your name", "required");
$form->addRule("email", "Enter your email address", "required");
$form->addRule("phone", "Enter your phone number", "required");


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
	
	$mailed = mail(EMAIL_ADMIN, SITE_SHORT_TITLE ." Contact Form", "From: ". $values["name"]. "\n". "Phone: ". $values["phone"] ."\n". "Heard From: ". $heard_from[$values["how_heard"]] ."\n\n". wordwrap($values["message"], 64) , "From:". $values["email"]);
	
	if($mailed)
		$output = "Thank you.";
	else
		$output = "There was a problem sending the email.  Are your sure you entered your email address correctly?";	
	$p->DisplayPage($output);
}



?>
