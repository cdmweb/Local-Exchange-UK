<?php

include_once("includes/inc.global.php");

$p->site_section = SITE_SECTION_OFFER_LIST;

include("includes/inc.forms.php");

//
// First, we define the form
//
if($_REQUEST["mode"] == "admin") {  // Administrator is editing a member's account
	$cUser->MustBeLevel(1);
	$member = new cMember;

	$member->LoadMember($_REQUEST["member_id"],true);
	//$form->addElement("header", null, "Edit Member " . $member->getAllNames());


	$form->addElement("html", "<TR></TR>");
	$form->addElement("hidden","mode","admin");
	$form->addElement("hidden","member_id",$_REQUEST["member_id"]);
	if($_REQUEST["member_id"] == "ADMIN") {
		$form->addElement("hidden","member_role","9");
	} else {
		$form->addElement("select", "member_role", "Member Role", array("0"=>"Member", "1"=>"Committee", "2"=>"Admin"));
	}
	$acct_types = array("S"=>"Single", "J"=>"Joint", "H"=>"Household", "O"=>"Organization", "B"=>"Business", "F"=>"Fund");
	$form->addElement("select", "account_type", "Account Type", $acct_types);
	$form->addElement("static", null, "Administrator Note", null);
	$form->addElement("textarea", "admin_note", null, array("cols"=>45, "rows"=>2, "wrap"=>"soft", "maxlength" => 100));
	$today = getdate();
	$options = array("language"=> "en", "format" => "dFY", "minYear"=>JOIN_YEAR_MINIMUM, "maxYear"=>	$today["year"]);
	$form->addElement("date", "join_date",	"Join Date", $options);	
	$options = array("language"=> "en", "format" => "dFY", "maxYear"=>$today["year"], "minYear"=>"1880"); 	
	// CT remove fields that we shouldnt have for GDPR
	//$form->addElement("date", "dob", "Date of Birth", $options);
	//$form->addElement("text", "mother_mn", "Mother's Maiden Name", array("size" => 20, "maxlength" => 30)); 	
	$form->addElement("static", null, null, null);		
	$update_text="How frequently should the member receive email updates?";
	$update2_text="Should the member confirm any payments made to him/her?";
} else {  // Member is editing own profile
	$cUser->MustBeLoggedOn();
	$member = $cUser;
	$form->addElement("html", "<TR></TR>");
	$form->addElement("hidden","member_id", $cUser->getMemberId());
	$form->addElement("hidden","mode","self");
	$update_text="How often would you like to receive email updates?";
	$update2_text="Do you wish to confirm payments that are made to you?";
}

$form->addElement("text", "first_name", "First Name", array("size" => 15, "maxlength" => 20));
$form->addElement("text", "mid_name", "Middle Name", array("size" => 10, "maxlength" => 20));
$form->addElement("text", "last_name", "Last Name", array("size" => 20, "maxlength" => 30));
$form->addElement("static", null, null, null); 

$form->addElement("text", "email", "Email Address", array("size" => 25, "maxlength" => 40));
$form->addElement("text", "phone1", "Primary Phone", array("size" => 20));
$form->addElement("text", "phone2", "Secondary Phone", array("size" => 20));
//$form->addElement("text", "fax", "Fax Number", array("size" => 20));
$form->addElement("static", null, null, null);
$frequency = array("0"=>"Never", "1"=>"Daily", "7"=>"Weekly", "30"=>"Monthly");
$form->addElement("select", "email_updates", $update_text, $frequency);

$confirmP = array("0"=>"Automatically Accept Payments", "1"=>"Confirm Payments");
$form->addElement("select", "confirm_payments", $update2_text, $confirmP);
$form->addElement("static", null, null, null);

$form->addElement("text", "address_street1", ADDRESS_LINE_1, array("size" => 25, "maxlength" => 30));
$form->addElement("text", "address_street2", ADDRESS_LINE_2, array("size" => 25, "maxlength" => 30));
$form->addElement("text", "address_city", ADDRESS_LINE_3, array("size" => 25, "maxlength" => 50));

// TODO: The State and Country codes should be Select Menus, and choices should be built
// dynamically using an internet database (if such exists).
$form->addElement("text", "address_state_code", STATE_TEXT, array("size" => 25, "maxlength" => 50));
$form->addElement("text", "address_post_code", ZIP_TEXT, array("size" => 10, "maxlength" => 20));
$form->addElement("text", "address_country", "Country", array("size" => 25, "maxlength" => 50));

/*[chris] Personal Profile bits */

if (SOC_NETWORK_FIELDS==true) {
	
	$form->addElement("static", null, null, null);
	$form->addElement("select", "age", "Age", $agesArr);
	$form->addElement("select", "sex", "Sex", $sexArr);
	$form->addElement("textarea", "about_me", 'About Me', array("cols"=>45, "rows"=>5, "wrap"=>"soft", "maxlength" => 300));
}

$form->addElement("static", null, null, null);
$form->addElement('submit', 'btnSubmit', 'Update');

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

$form->registerRule('verify_role_allowed','function','verify_role_allowed');
$form->addRule('member_role','You cannot assign a higher level of access than you have','verify_role_allowed');
$form->registerRule('verify_role_allowed1', 'function','verify_role_allowed1');
$form->addRule('member_role', 'You cannot modify the member role of a higher level account', 'verify_role_allowed1');

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
//$form->addRule('fax', 'Phone format invalid', 'verify_phone_format');


//
// Check if we are processing a submission or just displaying the form
//
if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Otherwise we need to load the existing values
	//$member = new cMember;
	if($_REQUEST["mode"] == "admin") {
        $cUser->MustBeLevel(1);
		//$member->LoadMember($_REQUEST["member_id"], true);
    }
	else {
		//$member->LoadMember($cUser->getMemberId(), true);
    }

	$primaryPerson = $member->getPrimaryPerson();		
	$current_values = array (
		"member_id"=>$member->getMemberId(), 
		"first_name"=>$primaryPerson->getFirstName(), 
		"mid_name"=>$primaryPerson->getMidName(), 
		"last_name"=>$primaryPerson->getLastName(), 
		"email"=>$primaryPerson->getEmail(), 
		"phone1"=>$primaryPerson->DisplayPhone(1), 
		"phone2"=>$primaryPerson->DisplayPhone(2), 
//		"fax"=>$primaryPerson->DisplayPhone("fax"), 
		"email_updates"=>$member->getEmailUpdates(), 
		"address_street1"=>$primaryPerson->getAddressStreet1(), 
		"address_street2"=>$primaryPerson->getAddressStreet2(), 
		"address_city"=>$primaryPerson->getAddressCity(), 
		"address_state_code"=>$primaryPerson->getAddressStateCode(), 
		"address_post_code"=>$primaryPerson->getAddressPostCode(), 
		"address_country"=>$primaryPerson->getAddressCountry(), 
		"age"=>$primaryPerson->getAge(), 
		"sex"=>$primaryPerson->getSex(), 
		"about_me"=>$primaryPerson->getAboutMe(),
		"confirm_payments"=>$member->getConfirmPayments()
	);
	// Load defaults for extra fields visible by administrators
	if($_REQUEST["mode"] == "admin") {
        $cUser->MustBeLevel(1);

		$current_values["member_role"] = $member->getMemberRole();
		$current_values["account_type"] = $member->getAccountType();
		$current_values["admin_note"] = $member->getAdminNote();
		$current_values["join_date"] = array ('d'=>substr($member->getJoinDate(),8,2),'F'=>date('n',strtotime($member->getJoinDate())),'Y'=>substr($member->getJoinDate(),0,4));
		//$current_values["mother_mn"] = $primaryPerson->getMotherMn();
		/*
		if ($member->person[0]->dob) {		
			$current_values["dob"] = array ('d'=>substr($member->person[0]->dob,8,2),'F'=>date('n',strtotime($member->person[0]->dob)),'Y'=>substr($member->person[0]->dob,0,4));  // Using 'n' due to a bug in Quickform
		} else { // If date of birth was left empty originally, display default date
			$today = getdate();
			$current_values["dob"] = array ('d'=>$today['mday'],'F'=>$today['mon'],'Y'=>$today['year']);
		}	
		*/	
	}
		
	$form->setDefaults($current_values);
	$status_label = ($member->getStatus() == "I") ? " - Inactive" : "";
	//$p->page_title = "Member details for {$member->getAllNames()} (#{$member_id}{$status_label})";

	if($_REQUEST["mode"] == "admin") {
		$page_title = "Edit profile for " . $member->getAllNames() . " (#{$member->getMemberId()}{$status_label})";
	} else{
		$page_title = "Edit my profile";
	}
   
   $p->page_title = $page_title;
   $p->DisplayPage($form->toHtml());  // display the form
}

//
// The form has been submitted with valid data, so process it   
//
function process_data ($values) {
	
	global $p, $cUser,$cErr, $today;
	$list = "";
	$member = new cMember;
	$member->ConstructMember($values);

	if($_REQUEST["mode"] == "admin") {
        $cUser->MustBeLevel(1);
		//$member->ConstructMember($_REQUEST["member_id"], true);
    }
	else {
		//$member = $cUser;
   
    }
/*
	$member = new cMember;
	if($_REQUEST["mode"] == "admin") {
        $cUser->MustBeLevel(1);
		$member->LoadMember($_REQUEST["member_id"], true);
    }
	else {
		$member = $cUser;
   
    }
*/
	if($_REQUEST["mode"] == "admin") {
        $cUser->MustBeLevel(1);
		
		$member->setConfirmPayments(htmlspecialchars($values["confirm_payments"]));
	
		$member->setMemberRole(htmlspecialchars($values["member_role"]));
		$member->setAccountType(htmlspecialchars($values["account_type"]));
		$member->setAdminNote(htmlspecialchars($values["admin_note"]));
		//$member->getPrimaryPerson()->setMotherMn(htmlspecialchars($values["mother_mn"]));
		
		// [chris] fixed problem with passing this ARRAY to htmlspecialchars()...
		$date = $values['join_date'];
		
		// ... pass to htmlspecialchars() here instead [chris]
		$member->setJoinDate(htmlspecialchars($date['Y'] . '/' . $date['F'] . '/' . $date['d']));
		
		/* // [chris] ditto re htmlspecialchars() [see comment above]
		$date = $values['dob'];

		$dob = $date['Y'] . '/' . $date['F'] . '/' . $date['d'];
		
		// ... pass to htmlspecialchars() here instead [chris]
		$dob = htmlspecialchars($dob);
		
		if($dob != $today['year']."/".$today['mon']."/".$today['mday']) { 
			$member->person[0]->dob = $dob; 
		} // if date left as default (today's date), we don't want to set it
		*/
	} 
	$member->setConfirmPayments(htmlspecialchars($values["confirm_payments"]));
	$member->setEmailUpdates(htmlspecialchars($values["emailUpdates"]));
	
    // TODO: Add ability to temporarily disable an account (vacation) or to
    // disable altogether (left 4th Corner).  Also add ability for user to add
    // a personal note.
	$member->getPrimaryPerson()->setFirstName(htmlspecialchars($values["first_name"]));
	$member->getPrimaryPerson()->setMidName(htmlspecialchars($values["mid_name"]));
	$member->getPrimaryPerson()->setLastName(htmlspecialchars($values["last_name"]));
	$member->getPrimaryPerson()->setEmail(htmlspecialchars($values["email"]));
	$member->getPrimaryPerson()->setAddressStreet1(htmlspecialchars($values["address_street1"]));
	$member->getPrimaryPerson()->setAddressStreet2(htmlspecialchars($values["address_street2"]));
	$member->getPrimaryPerson()->setAddressCity(htmlspecialchars($values["address_city"]));
	$member->getPrimaryPerson()->setAddressStateCode(htmlspecialchars($values["address_state_code"]));
	$member->getPrimaryPerson()->setAddressPostCode(htmlspecialchars($values["address_post_code"]));
	$member->getPrimaryPerson()->setAddressCountry(htmlspecialchars($values["address_country"]));	

	$phone = new cPhone_uk($values['phone1']);
	$member->getPrimaryPerson()->setPhone1Area($phone->area);
	$member->getPrimaryPerson()->setPhone1Number($phone->SevenDigits());
	$member->getPrimaryPerson()->setPhone1Ext($phone->ext);
	$phone = new cPhone_uk($values['phone2']);
	$member->getPrimaryPerson()->setPhone2Area($phone->area);
	$member->getPrimaryPerson()->setPhone2Number($phone->SevenDigits());
	$member->getPrimaryPerson()->setPhone2Ext($phone->ext);
	//$phone = new cPhone_uk($values['fax']);
	//$member->getPrimaryPerson()->setFaxArea($fax->area);
	//$member->getPrimaryPerson()->setFaxNumber($fax->SevenDigits());
	//$member->getPrimaryPerson()->setFaxExt($fax->ext);
	/*[chris]*/
	if (SOC_NETWORK_FIELDS==true) {
	
		$member->getPrimaryPerson()->setAge(htmlspecialchars($values["age"]));
		$member->getPrimaryPerson()->setSex(htmlspecialchars($values["sex"]));
		$member->getPrimaryPerson()->setAboutMe(htmlspecialchars($values["about_me"]));
	}
	
	if($member->SaveMember()) {
		$list .= "Changes saved."; 
	} else {
		$cErr->Error("There was an error saving the member. Please try again later.");
	}
   $p->DisplayPage($list);
}
//
// The following functions verify form data
//

// TODO: All my validation functions should go into a new cFormValidation class

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
	if($element_value > $cUser->getMemberRole())
		return false;
	else
		return true;
}


/**
 * You cannot downgrade an account that has higher privileges than you.
 */
function verify_role_allowed1($element_name,$element_value) {
	global $cUser, $member;

	if ($member->getMemberRole() > $cUser->getMemberRole() &&
          $element_value != $member->getMemberRole()) {
		return false;
    }
	else {
		return true;
    }
}

//CT this is not used - scary data to have for GDPR
function verify_reasonable_dob($element_name,$element_value) {
	global $today;
	$date = $element_value;
	$date_str = $date['Y'] . '/' . $date['F'] . '/' . $date['d'];

	if ($date_str == $today['year']."/".$today['mon']."/".$today['mday']) 
		// date wasn't changed by user, so no need to verify it
		return true;
	elseif ($today['year'] - $date['Y'] < 3)  // A little young to be trading, presumably a mistake
		return false;
	else
		return true;
}

function verify_good_password($element_name,$element_value) {
	$i=0; $upper=false; $lower=false; $number=false; $punct=false;
	$length=strlen($element_value);
	
	while($i<$length) {
		if(ctype_upper($element_value{$i}))
			$upper=true;
		if(ctype_lower($element_value{$i}))
			$lower=true;
		if(ctype_punct($element_value{$i}))
			$punct=true;
		if(ctype_digit($element_value{$i}))
			$number=true;	
		$i+=1;
	}
	
	if($upper and $lower and ($number or $punct))
		return true;
	else
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
