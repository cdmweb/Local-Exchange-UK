<?php
include_once("includes/inc.global.php");
include_once("classes/class.mail.php");
$mail = new cMail();

$p->page_title = "Email all Members";

$cUser->MustBeLevel(2);

//include("includes/inc.forms.php");

//
// First, we define the form
//
if ($_POST["submit"]){
	//$fieldArray = $_POST;
	$mail->Build($_POST);
	// TODO: preview. show content of what is to be sent

	$is_saved = 0;
	$is_saved = $mail->ProcessData();
		//redirect to page if saved
	if($is_saved){
		//redirect page if saved	
		$redir_url="admin_menu.php?form_action=contact_all";
  		include("redirect.php");
	} 
} else{
	
}
        $output = "<form action=\"/members/admin_contact_all.php\" method=\"post\" name=\"form\" id=\"form\" class=\"layout2\">
        	<input type=\"hidden\" id=\"from_address\" name=\"from_address\" value=\"admin@claratodd.com\" />
        	<input type=\"hidden\" id=\"from_name\" name=\"from_name\" value=\"CamLETS admin\" />
        	<input type=\"hidden\" id=\"to_address\" name=\"to_address\" value=\"clarabara@gmail.com\" />
        	<input type=\"hidden\" id=\"to_name\" name=\"to_name\" value=\"Clara Bara\" />
            <p>This email will go out to <em>ALL</em> members of {$site_settings->getKey('SITE_LONG_TITLE')}. Be sure that this is what you want to do.</p>
           <p>
                <label for=\"from\">
                    <span>From  *</span>
                    {from: self or admin}
                </label>
            </p>
            <p>
                <label for=\"subject\">
                    <span>Subject  *</span>
                    <input maxlength=\"200\" name=\"subject\" id=\"subject\" type=\"text\" value=\"{$mail->getSubject()}\">
                </label>
            </p>
            <p>
                <label for=\"message\">
                    <span>Message  *</span>
                    <textarea name=\"message\" id=\"message\" type=\"text\">{$mail->getMessage()}</textarea>
                </label>
            </p>
            <p class=\"summary\">
                <input name=\"submit\" id=\"submit\" class=\"button\" value=\"Send to all members\" type=\"submit\" />
                * denotes a required field
            </p>
        </form>";
/*form->addElement("static", null, "This email will go out to <i>ALL</i> members of ".SITE_LONG_TITLE.".", null);
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
	*/	
	$p->DisplayPage($output);




?>
