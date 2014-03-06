<?php
include_once("includes/inc.global.php");
$p->site_section = SECTION_EMAIL;
$p->page_title = "Email a Member";

$cUser->MustBeLoggedOn();

include("includes/inc.forms.php");

//
// First, we define the form
//

$form->addElement("hidden", "email_to", $_REQUEST["email_to"]);
$form->addElement("hidden", "member_to", $_REQUEST["member_to"]);
$member_to = new cMember;
$member_to->LoadMember($_REQUEST["member_to"]);
$form->addElement("static", null, "To: <I>". $_REQUEST["email_to"] . " (". $member_to->member_id .")</I>");
$form->addElement("text", "subject", "Subject: ", array('size' => 35, 'maxlength' => 100));
$form->addElement("select", "cc", "Would you like to receive a copy?", array("Y"=>"Yes", "N"=>"No"));

/*  The following code should work, and works on my server, but not on Open Access.  Bug?
$cc[] =& HTML_QuickForm::createElement('radio',null,null,'<FONT SIZE=2>Yes</FONT>','Y');
$cc[] =& HTML_QuickForm::createElement('radio',null,null,'<FONT SIZE=2>No</FONT>','N');
$form->addGroup($cc, "cc", 'Would you like to recieve a copy?');
*/

$form->addElement("static", null, null, null);
$form->addElement("textarea", "message", "Your Message", array("cols"=>65, "rows"=>10, "wrap"=>"soft"));

$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Send");

//
// Define form rules
//
$form->addRule("message", "Enter your message", "required");

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$form->setDefaults(array("cc"=>"Y"));
	$p->DisplayPage($form->toHtml());
}

//
// The form has been submitted with valid data, so process it   
//
function process_data ($values) {
	global $p, $cUser;
	
	if($values["cc"] == "Y") {
		$copy = "\r\nCc:". $cUser->person[0]->email;
    }
	else {
		$copy = "";
    }

    if(known_email_addressp($_REQUEST["email_to"])) {
	    $mailed = mail($_REQUEST["email_to"], SITE_SHORT_TITLE .": ". $values["subject"], wordwrap($values["message"], 64), "From:". $cUser->person[0]->email . $copy);
    }
    else {
        $mailed = false;
    }

	if($mailed) {
		$output = "Your message has been sent.";
    }
	else {
		$output = "There was a problem sending the email.  Please try again later.";	
    }

	$p->DisplayPage($output);
}


/**
 * Checks whether the given email address exists in the database.
 */
function known_email_addressp($email) {
    global $cDB;

    $email = $cDB->EscTxt($email);
    $sql = "SELECT person_id FROM " . DATABASE_PERSONS .
                                                 " WHERE email = $email";
    $r = $cDB->Query($sql);
    if($row = mysql_fetch_array($r)) {
        return true;
    }
    else {
        return false;
    }
}













?>
