<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

include("includes/inc.forms.php");

$form->addElement("header", null, "Reset Password");
$form->addElement("html", "<TR></TR>");

$form->addElement("text", "member_id", "Enter your Member ID");
$form->addElement("text", "email", "Enter the Email Address for your Account");

$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Reset Password");

$form->registerRule('verify_email','function','verify_email');
$form->addRule('email','Address or member id is incorrect','verify_email');
$form->addElement("static", null, null, null);
$form->addElement("static", 'contact', "If you cannot remember your member id or email address, please <A HREF=contact.php>contact</A> us.", null);

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
	
	$list = "Your password has been reset.  You can change the new password after you login by going into the Member Profile section of the web site.<P>";
	$mailed = mail($values['email'], PASSWORD_RESET_SUBJECT, PASSWORD_RESET_MESSAGE . "\n\nNew Password: ". $password, EMAIL_FROM);
	if($mailed)
		$list .= "The new password has been sent to your email address.";
	else
		$list .= "<I>However, the attempt to email the new password failed.  This is most likely due to a technical problem.  Contact your administrator at ". PHONE_ADMIN ."</I>.";	
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
