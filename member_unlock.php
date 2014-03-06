<?php
include_once("includes/inc.global.php");

$cUser->MustBeLevel(2);
$p->site_section = ADMINISTRATION;
$p->page_title = "Unlock Account and Reset Password";

include("includes/inc.forms.php");

$form->addElement("static", 'contact', "This form will both unlock an account (if it is locked) and reset the member's password.  Then it will email the new password to the member.  You may want to make sure the member's email is still current.", null);
$form->addElement("static", null, null);
$ids = new cMemberGroup;
$ids->LoadMemberGroup();
$form->addElement("select", "member_id", "Choose the Member Account", $ids->MakeIDArray());

$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Unlock and Reset");
$form->addElement("radio", "emailTyp", "", "Send 'Password Reset' email","pword");
$form->addElement("radio", "emailTyp", "", "Send 'Welcome' Email","welcome");

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$p->DisplayPage($form->toHtml());
}

function process_data ($values) {
	global $p;
	
	$list = "";
	$member = new cMember;
	$member->LoadMember($values["member_id"]);

	if($consecutive_failures = $member->UnlockAccount()) {
		$list .= "This member account had been locked due to ". $consecutive_failures ." consecutive login failures.  It has been unlocked.  If the number of attempts is more than 10 or 20,you may want to contact your administrator at ". PHONE_ADMIN ."</I>, because it could indicate someone is trying to hack into the system.<P>";
	}


	$password = $member->GeneratePassword();
	$member->ChangePassword($password); // This will bomb out if the password change fails
	
	$list .= "The password has been reset";
	
	if ($_REQUEST["emailTyp"]=='welcome') {
		
		$mailed = mail($member->person[0]->email, NEW_MEMBER_SUBJECT, NEW_MEMBER_MESSAGE . "\n\nMember ID: ". $member->member_id ."\n". "Password: ". $password, EMAIL_FROM);
			
		$whEmail = "'Welcome'";
	}
	else {
		$mailed = mail($member->person[0]->email, PASSWORD_RESET_SUBJECT, PASSWORD_RESET_MESSAGE . "\n\nMember ID: ". $member->member_id ."\nNew Password: ". $password, EMAIL_FROM);
		
		$whEmail = "'Password Reset'";
	}

	if($mailed)
		$list .= " and a $whEmail email has been sent to the member's email address (". $member->person[0]->email .").";
	else
		$list .= ". <I>However, the attempt to email the new password failed.  This is most likely due to a technical problem.  Contact your administrator at ". PHONE_ADMIN ."</I>.";	
	$p->DisplayPage($list);
}

?>
