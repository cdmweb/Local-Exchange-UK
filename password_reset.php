<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;
$p->page_title = "Reset Password";


include("includes/inc.forms.php");

$form->addElement("text", "member_id", "Member ID", array("size" => 20, "maxlength" => 30));
$form->addElement("text", "email", "The email address for your account.", array("size" => 20, "maxlength" => 30));

$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Reset Password");

$form->registerRule('verify_email','function','verify_email');
$form->addRule('member_id', 'Enter a member id', 'required');
$form->addRule('email', 'Enter an email', 'required');
$form->addRule('email','Address or member id is incorrect','verify_email');
$form->addElement("static", null, null, null);
$form->addElement("static", 'contact', "For any other issues, please <a href='contact.php'>contact us</a>", null);

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$p->DisplayPage($form->toHtml());
}

function process_data ($values) {
	global $p;
	
	$member = new cMember;
	$member->LoadMember($values["member_id"]);

	$password = $member->GeneratePassword();
	$member->ChangePassword($password); // This will bomb out if the password change fails
	$member->UnlockAccount();
	
	$list = "<p>Your password has been reset";
	$mailed = mail($values['email'], PASSWORD_RESET_SUBJECT, PASSWORD_RESET_MESSAGE . "\n\nNew Password: ". $password, EMAIL_FROM);
	if($mailed)
		$list .= ". Please check your email inbox.";
	else
		$list .= ", but there was a technical problem and the email could not sent.  Contact the administator at ". EMAIL_ADMIN ."</i>.";	
	$p->DisplayPage($list);
}

function verify_email($element_name,$element_value) {
	global $form;
	$member = new cMember;

	if(!$member->VerifyMemberExists($form->getElementValue("member_id")))
		return false;  // Don't want to try to load member if member_id invalid, 
							// because of inappropriate error message.
		
	$member->LoadMember($form->getElementValue("member_id"));

	if($element_value == $member->person[0]->email)
		return true;
	else
		return false;
}

?>
