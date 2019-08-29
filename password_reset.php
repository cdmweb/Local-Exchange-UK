<?php
include_once("includes/inc.global.php");

$p->page_title = "Forgot password";

//include("includes/inc.forms.php");

// $form->addElement("header", null, "Reset Password");
// $form->addElement("html", "<TR></TR>");

// $form->addElement("text", "member_id", "Enter your Member ID");
// $form->addElement("text", "email", "Enter the Email Address for your Account");

// $form->addElement("static", null, null, null);
// $form->addElement("submit", "btnSubmit", "Reset Password");

// $form->registerRule('verify_email','function','verify_email');
// $form->addRule('email','Address or member id is incorrect','verify_email');
// $form->addElement("static", null, null, null);
// $form->addElement("static", 'contact', "If you cannot remember your member id or email address, please <A HREF=contact.php>contact</A> us.", null);

// if ($form->validate()) { // Form is validated so processes the data
//    $form->freeze();
//  	$form->process("process_data", false);
// } else {  // Display the form
// 	$p->DisplayPage($form->toHtml());
// }

if ($_POST["submit"]){
	// $vars = array();
	// $vars['old_passwd'] = $_POST["old_passwd"];
	// $vars['new_passwd'] = $_POST["new_passwd"];
	// $vars['rpt_passwd'] = $_POST["rpt_passwd"];
	$is_saved = 0;
	$is_saved = process_data();
		//redirect to page if saved
	if($is_saved){
		//display success message if saved	
		//$redir_url="member_detail.php?member_id={$member->getMemberId()}&form_action=saved";
  		//include("redirect.php");
	} 

	

	//process_data ($$vars);
} 
$output = displayPasswordResetForm();
$p->DisplayPage($output);  // just display the form



function displayPasswordResetForm() { // TODO: Should use SaveMember and should reset $this->password
        global $cDB, $cErr;
        //CT todo - use template.
        $output = "
        <form action=\"/members/password_reset.php\" method=\"post\" name=\"form\" id=\"form\" class=\"layout2\">
            <p>Please fill in the details below to reset your password for your account. If you cannot remember your member ID or email address, please <a href=\"contact.php\">contact our support team for help</a>.</p>
            <p>
                <label for=\"member_id\">
                    <span>Member ID  *</span>
                    <input maxlength=\"20\" name=\"member_id\" id=\"member_id\" type=\"text\" value=\"\">
                </label>
            </p>
            <p>
                <label for=\"email\">
                    <span>Email  *</span>
                    <input maxlength=\"50\" name=\"email\" id=\"email\" type=\"text\" value=\"\">
                </label>
            </p>
            
            <p class=\"summary\">
                <input name=\"submit\" id=\"submit\" value=\"Submit\" type=\"submit\" />
                * denotes a required field
            </p>
        </form>";
        return $output;
        
    }

function process_data () {
	global $cErr;
	
	$errors = array();

	$member_id = $_POST['member_id'];
	$email = $_POST['email'];

	//check for obvious errors in form
	if(!verify_member_id($member_id)) {
		$errors['member_id'] = "Enter your member id.";
	}
	if(!verify_email($email)) {
		$errors['email'] = "Enter your email address.";
	}
     if(sizeof($errors) > 0) {

        //CT todo: highlight the form elements from keys
        foreach($errors as $key => $error) {
            $cErr->Error($error);
        }
        return false;
    }
    //$cErr->Error("no errors");
    //return Save(); 

    // if you've got here, so far so good - no errors found

	$member = new cMember;
	if (!$member->VerifyMemberExists($member_id, $email)){
		$cErr->Error('Not found with that member id and email combination.');
	}else{
		$password = $member->GeneratePassword();
		$member->ChangePassword($password); // This will bomb out if the password change fails
		$member->UnlockAccount();
		
		$list = "Your password has been reset. ";
		$mailed = mail($values['email'], PASSWORD_RESET_SUBJECT, PASSWORD_RESET_MESSAGE . "\n\nNew Password: ". $password, EMAIL_FROM);
		if($mailed)
			$list .= "The new password has been sent to your email address.";
		else
			$list .= "<em>The attempt to email the new password failed.  Contact your administrator at ". EMAIL_ADMIN ."</em>.";	
		$p->DisplayPage($list);
	}

	
}


// function verify_form() {
// 	// global $form;
// 	//$member = new cMember;
// 		//verify old, new, show messages and then reset password.
// 	$errors = array();
// 	if(verify_member_id($_POST['member_id'])) $errors['member_id'] = "please enter your member id.";
// 	if(verify_email($_POST['email'])) $errors['email'] = "please enter your email address.";

//         if(sizeof($errors) > 0) {

//         //CT todo: highlight the form elements from keys
//         foreach($errors as $key => $error) {
//             $cErr->Error($error);
//         }
//         return false;
//     }
//     //$cErr->Error("no errors");
//     return $this->Save();      

// //if no errors, go to process data. put safety around process data. $errors 
// // 	if(!$member->VerifyMemberExists($form->getElementValue("member_id")))
// // 		return false;  // Don't want to try to load member if member_id invalid, 
// // 							// because of inappropriate error message.
		
// // 	$member->LoadMember($form->getElementValue("member_id"));

// // 	if($element_value == $member->getPrimaryPerson()->getEmail())
// // 		return true;
// // 	else
// // 		return false;
// // 
// }

function verify_member_id($member_id) {
	if(strlen($member_id >= 4) && strlen($member_id < 10)) {
		return true;
	}
	return false;
}
function verify_email($email) {
	return false;
}


?>
