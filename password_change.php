<?php

include_once("includes/inc.global.php");

$cUser->MustBeLoggedOn();
//$p->site_section = SITE_SECTION_OFFER_LIST;


$p->page_title = "Change my password";

//
// Define form elements
//
/*

$list = $p->Wrap('Your password must be at least 7 characters long (the longer the better) and include at least one number. <a href="https://www.wikihow.tech/Create-a-Secure-Password target="_blank">Tips on how to create a secure password</a>', 'p');
$form->addElement('html', '<TR></TR>');
$options = array('size' => 10, 'maxlength' => 15);
$form->addElement('password', 'old_passwd', 'Old Password',$options);
$form->addElement('password', 'new_passwd', 'Choose a New Password',$options);
$form->addElement('password', 'rpt_passwd', 'Repeat the New Password',$options);
$form->addElement('submit', 'btnSubmit', 'Change Password');

//
// Define form rules
//
$form->addRule('old_passwd', 'Enter your current password', 'required');
$form->addRule('new_passwd', 'Enter a new password', 'required');
$form->addRule('rpt_passwd', 'You must re-enter the new password', 'required');
$form->addRule('new_passwd', 'Password not long enough', 'minlength', 7);
$form->registerRule('verify_passwords_equal','function','verify_passwords_equal');
$form->addRule('new_passwd', 'Passwords are not the same', 'verify_passwords_equal');
$form->registerRule('verify_old_password','function','verify_old_password');
$form->addRule('old_passwd', 'Password is incorrect', 'verify_old_password');
$form->registerRule('verify_good_password','function','verify_good_password');
$form->addRule('new_passwd', 'Passwords must contain at least one number', 'verify_good_password');

$list .= $form->toHtml();
//*/
//	Display or process the form
//


if ($_POST["submit"]){
	// $vars = array();
	// $vars['old_passwd'] = $_POST["old_passwd"];
	// $vars['new_passwd'] = $_POST["new_passwd"];
	// $vars['rpt_passwd'] = $_POST["rpt_passwd"];
	verifyForm();

	//process_data ($$vars);
} 
$output = displayPasswordForm();
$p->DisplayPage($output);  // just display the form

function verifyForm(){
	//verify old, new, show messages and then reset password.
	$errors = array();
	if(verify_old_password($_POST['old_passwd'])) $errors[] = "your old password does not match.";
	if(verify_new_password($_POST['new_passwd'])) $errors[] = "Please enter a new password.";
	if(verify_rpt_password($_POST['rpt_passwd'])) $errors[] = "Your new password does not match.";

}
function displayPasswordForm() { // TODO: Should use SaveMember and should reset $this->password
        global $cDB, $cErr;
        //CT todo - use template.
        $output = "
        <form action=\"/members/password_change.php\" method=\"post\" name=\"form\" id=\"form\" class=\"layout2\">
            <p>Your password must be at least 8 characters long and include at least one number, uppercase letter and lowercase letter. <a href=\"https://www.wikihow.tech/Create-a-Secure-Password\" target=\"_blank\">Tips on how to create a secure password</a></p>
            <p>
                <label for=\"old_passwd\">
                    <span>Old Password  *</span>
                    <input maxlength=\"200\" name=\"old_passwd\" id=\"old_passwd\" type=\"password\" value=\"\">
                </label>
            </p>
            <hr />
            <p>
                <label for=\"new_passwd\">
                    <span>New password  *</span>
                    <input maxlength=\"200\" name=\"new_passwd\" id=\"new_passwd\" type=\"password\" value=\"\">
                </label>
            </p>
            <p>
                <label for=\"rpt_passwd\">
                    <span>Repeat new password  *</span>
                    <input maxlength=\"200\" name=\"rpt_passwd\" id=\"rpt_passwd\" type=\"password\" value=\"\">
                </label>
            </p>
            
            <p class=\"summary\">
                <input name=\"submit\" id=\"submit\" value=\"Submit\" type=\"submit\" />
                * denotes a required field
            </p>
        </form>";
        return $output;
        
    }


function process_data ($values) {
	global $p, $cUser;
	
	if($cUser->ChangePassword($values['new_passwd']))
		$list = 'Password successfully changed.';
	else
		$list = 'There was an error changing the password.';
	$p->DisplayPage($list);
}

function verify_email($email) {
	global $cUser;
	if($cUser->ValidatePassword($element_value))
		return true;
	else
		return false;
}
function verify_member_id($member_id) {
	global $cUser;
	if($cUser->ValidatePassword($element_value))
		return true;
	else
		return false;
}

function verify_good_password($element_name,$element_value) {
	$i=0;
	$length=strlen($element_value);
	
	while($i<$length) {
		if(ctype_digit($element_value{$i}))
			return true;	
		$i+=1;
	}
	
	return false;
}


function verify_passwords_equal() {
	global $form;

	if ($form->getElementValue('new_passwd') != $form->getElementValue('rpt_passwd'))
		return false;
	else
		return true;
}

?>
