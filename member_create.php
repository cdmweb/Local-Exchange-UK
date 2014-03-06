<?php

include_once("includes/inc.global.php");

$cUser->MustBeLevel(1);
$p->site_section = SITE_SECTION_OFFER_LIST;

include("includes/inc.forms.php");

//
// First, we define the form
//
$form->addElement("header", null, "Create New Member");
$form->addElement("html", "<TR></TR>");

$form->addElement("text", "member_id", "Member ID", array("size" => 10, "maxlength" => 15));
$form->addElement("text", "password", "Password", array("size" => 10, "maxlength" => 15));
$form->addElement("select", "member_role", "Member Role", array("0"=>"Member", "1"=>"Committee", "2"=>"Admin"));
$acct_types = array("S"=>"Single", "J"=>"Joint", "H"=>"Household", "O"=>"Organization", "B"=>"Business", "F"=>"Fund");
$form->addElement("select", "account_type", "Account Type", $acct_types);
$form->addElement("static", null, "Administrator Note", null);
$form->addElement("textarea", "admin_note", null, array("cols"=>45, "rows"=>2, "wrap"=>"soft", "maxlength" => 100));

$today = getdate();
$options = array("language"=> "en", "format" => "dFY", "minYear"=>JOIN_YEAR_MINIMUM, "maxYear"=>$today["year"]);
$form->addElement("date", "join_date",	"Join Date", $options);	
$form->addElement("static", null, null, null);	

$form->addElement("text", "first_name", "First Name", array("size" => 15, "maxlength" => 20));
$form->addElement("text", "mid_name", "Middle Name", array("size" => 10, "maxlength" => 20));
$form->addElement("text", "last_name", "Last Name", array("size" => 20, "maxlength" => 30));
$form->addElement("static", null, null, null); 

$options = array("language"=> "en", "format" => "dFY", "maxYear"=>$today["year"], "minYear"=>"1880"); 
$form->addElement("date", "dob", "Date of Birth", $options);
$form->addElement("text", "mother_mn", "Mother's Maiden Name", array("size" => 20, "maxlength" => 30)); 
$form->addElement("static", null, null, null);
$form->addElement("text", "email", "Email Address", array("size" => 25, "maxlength" => 40));
$form->addElement("text", "phone1", "Primary Phone", array("size" => 20));
$form->addElement("text", "phone2", "Secondary Phone", array("size" => 20));
$form->addElement("text", "fax", "Fax Number", array("size" => 20));
$form->addElement("static", null, null, null);
$frequency = array("0"=>"Never", "1"=>"Daily", "7"=>"Weekly", "30"=>"Monthly");
$form->addElement("select", "email_updates", "How frequently should the member receive email updates?", $frequency);
$form->addElement("static", null, null, null);
$form->addElement("text", "address_street1", ADDRESS_LINE_1, array("size" => 25, "maxlength" => 50));
$form->addElement("text", "address_street2", ADDRESS_LINE_2, array("size" => 25, "maxlength" => 50));
$form->addElement("text", "address_city", ADDRESS_LINE_3, array("size" => 25, "maxlength" => 50));

// TODO: The State and Country codes should be Select Menus, and choices should be built
// dynamically using an internet database (if such exists).
$form->addElement("text", "address_state_code", STATE_TEXT, array("size" => 25, "maxlength" => 50));
$form->addElement("text", "address_post_code", ZIP_TEXT, array("size" => 10, "maxlength" => 20));
$form->addElement("text", "address_country", "Country", array("size" => 25, "maxlength" => 50));
$form->addElement("static", null, null, null);
$form->addElement('submit', 'btnSubmit', 'Create Member');

//
// Define form rules
//
$form->addRule('member_id', 'Enter a member id', 'required');
$form->addRule('password', 'Password not long enough', 'minlength', 7);
$form->addRule('first_name', 'Enter a first name', 'required');
$form->addRule('last_name', 'Enter a last name', 'required');
$form->addRule('address_city', 'Enter a ' . ADDRESS_LINE_3, 'required');
$form->addRule('address_state_code', 'Enter a ' . STATE_TEXT, 'required');
$form->addRule('address_post_code', 'Enter a '.ZIP_TEXT, 'required');
$form->addRule('address_country', 'Enter a country', 'required');

$form->registerRule('verify_unique_member_id','function','verify_unique_member_id');
$form->addRule('member_id','This ID is already being used','verify_unique_member_id');
$form->registerRule('verify_good_member_id','function','verify_good_member_id');
$form->addRule('member_id','Special characters are not allowed','verify_good_member_id');
$form->registerRule('verify_good_password','function','verify_good_password');
$form->addRule('password', 'Password must contain at least one number', 'verify_good_password');
$form->registerRule('verify_no_apostraphes_or_backslashes','function','verify_no_apostraphes_or_backslashes');
$form->addRule("password", "You have the right idea, but it's best not to use apostraphes or backslashes in passwords", "verify_no_apostraphes_or_backslashes");
$form->registerRule('verify_role_allowed','function','verify_role_allowed');
$form->addRule('member_role','You cannot assign a higher level of access than you have','verify_role_allowed');
$form->registerRule('verify_not_future_date','function','verify_not_future_date');
$form->addRule('join_date', 'Join date cannot be in the future', 'verify_not_future_date');
$form->addRule('dob', 'Birth date cannot be in the future', 'verify_not_future_date');
$form->registerRule('verify_reasonable_dob','function','verify_reasonable_dob');
$form->addRule('dob', 'A little young, don\'t you think?', 'verify_reasonable_dob');
$form->registerRule('verify_valid_email','function', 'verify_valid_email');
$form->addRule('email', 'Not a valid email address', 'verify_valid_email');
$form->registerRule('verify_phone_format','function','verify_phone_format');
$form->addRule('phone1', 'Phone format invalid', 'verify_phone_format');
$form->addRule('phone2', 'Phone format invalid', 'verify_phone_format');
$form->addRule('fax', 'Phone format invalid', 'verify_phone_format');


//
// Check if we are processing a submission or just displaying the form
//
if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {
	$today = getdate();
	$current_date = array("Y"=>$today["year"], "F"=>$today["mon"], "d"=>$today["mday"]);
	$defaults = array("password"=>$cUser->GeneratePassword(), "dob"=>$current_date, "join_date"=>$current_date, "account_type"=>"S", "member_role"=>"0", "email_updates"=>DEFAULT_UPDATE_INTERVAL, "address_state_code"=>DEFAULT_STATE, "address_country"=>DEFAULT_COUNTRY);
	$form->setDefaults($defaults);
   $p->DisplayPage($form->toHtml());  // just display the form
}

//
// The form has been submitted with valid data, so process it   
//
function process_data ($values) {
	global $p, $cUser,$cErr, $today;
	$list = "";

	// Following are default values for which this form doesn't allow input
	$values['security_q'] = "";
	$values['security_a'] = "";
	$values['status'] = "A";
	$values['member_note'] = "";
	$values['expire_date'] = "";
	$values['away_date'] = "";
	$values['balance'] = 0;
	$values['primary_member'] = "Y";
	$values['directory_list'] = "Y";

	$date = $values['join_date'];
	$values['join_date'] = $date['Y'] . '/' . $date['F'] . '/' . $date['d'];
	$date = $values['dob'];
	$values['dob'] = $date['Y'] . '/' . $date['F'] . '/' . $date['d'];
	if($values['dob'] == $today['year']."/".$today['mon']."/".$today['mday'])
		$values['dob'] = ""; // if birthdate was left as default, set to null
	
	$phone = new cPhone_uk($values['phone1']);
	$values['phone1_area'] = $phone->area;
	$values['phone1_number'] = $phone->SevenDigits();
	$values['phone1_ext'] = $phone->ext;
	$phone = new cPhone_uk($values['phone2']);
	$values['phone2_area'] = $phone->area;
	$values['phone2_number'] = $phone->SevenDigits();
	$values['phone2_ext'] = $phone->ext;	
	$phone = new cPhone_uk($values['fax']);
	$values['fax_area'] = $phone->area;
	$values['fax_number'] = $phone->SevenDigits();
	$values['fax_ext'] = $phone->ext;	


	$new_member = new cMember($values);
	$new_person = new cPerson($values);

	if($created = $new_person->SaveNewPerson()) 
		$created = $new_member->SaveNewMember();

	if($created) {
		$list .= "Member created. Click <A HREF=member_create.php>here</A> to create another member account.<P>Or if you would like to add a joint member to this account (such as a spouse), click <A HREF=member_contact_create.php?mode=admin&member_id=". $values["member_id"] .">here</A>.<P>";
		if($values['email'] == "") {
			$list .= "Since the new member does not have an email address, he/she needs to be notified of the member id ('". $values["member_id"]. "') and password ('". $values["password"] ."').";	
		} else {
			$mailed = mail($values['email'], NEW_MEMBER_SUBJECT, NEW_MEMBER_MESSAGE . "\n\nMember ID: ". $values['member_id'] ."\n". "Password: ". $values['password'], EMAIL_FROM);
			if($mailed)
				$list .= "An email has been sent to '". $values["email"] ."' containing the new user id and password.";
			else
				$list .= "An attempt to email the new member information failed.  This is most likely due to a technical problem.  You may want to contact your administrator at ". PHONE_ADMIN .". <I>Since the email failed, the new member needs to be notified of the member id ('". $values["member_id"]. "') and password ('". $values["password"] ."').</I>";	 
		}
	} else {
		$cErr->Error("There was an error saving the member. Please try again later.");
	}
   $p->DisplayPage($list);
}
//
// The following functions verify form data
//

// TODO: All my validation functions should go into a new cFormValidation class

function verify_unique_member_id ($element_name,$element_value) {
	$member = new cMember();
	
	return !($member->LoadMember($element_value, false));
}

function verify_good_member_id ($element_name,$element_value) {
	if(ctype_alnum($element_value)) { // it's good, so return immediately & save a little time
		return true;
	} else {
		$member_id = ereg_replace("\_","",$element_value);
		$member_id = ereg_replace("\-","",$member_id);
		$member_id = ereg_replace("\.","",$member_id);
		if(ctype_alnum($member_id))  // test again now that we've stripped the allowable special chars
			return true;		
	}
}

function verify_role_allowed($element_name,$element_value) {
	global $cUser;
	if($element_value > $cUser->member_role)
		return false;
	else
		return true;
}
		
function verify_reasonable_dob($element_name,$element_value) {
	global $today;
	$date = $element_value;
	$date_str = $date['Y'] . '/' . $date['F'] . '/' . $date['d'];
//	echo $date_str ."=".$today['year']."/".$today['mon']."/".$today['mday'];

	if ($date_str == $today['year']."/".$today['mon']."/".$today['mday']) 
		// date wasn't changed by user, so no need to verify it
		return true;
	elseif ($today['year'] - $date['Y'] < 3)  // A little young to be trading, presumably a mistake
		return false;
	else
		return true;
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

function verify_no_apostraphes_or_backslashes($element_name,$element_value) {
	if(strstr($element_value,"'") or strstr($element_value,"\\"))
		return false;
	else
		return true;
}

function verify_not_future_date ($element_name,$element_value) {
	$date = $element_value;
	$date_str = $date['Y'] . '/' . $date['F'] . '/' . $date['d'];

	if (strtotime($date_str) > strtotime("now"))
		return false;
	else
		return true;
}

// TODO: This simplistic function should ultimately be replaced by this class method on Pear:
// 		http://pear.php.net/manual/en/package.mail.mail-rfc822.intro.php
function verify_valid_email ($element_name,$element_value) {
	if ($element_value=="")
		return true;		// Currently not planning to require this field
	if (strstr($element_value,"@") and strstr($element_value,"."))
		return true;	
	else
		return false;
	
}

function verify_phone_format ($element_name,$element_value) {
	$phone = new cPhone_uk($element_value);
	
	if($phone->prefix)
		return true;
	else
		return false;
}

?>
