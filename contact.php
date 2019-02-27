<?php //session_start(); ?> 
<?php
include_once("includes/inc.global.php");
if(RECAPTCHA_VALIDATION) include_once(RECAPTCHA_SRC. "securimage.php");

$p->site_section = SECTION_EMAIL;
$p->page_title = "Contact Us";


include("includes/inc.forms.php");

//CT:simplify the form! and protect with captcha against bots
//TODO: move over to google's recaptcha
//
// First, we define the form
//
//$form->addElement("header", null, "Contact us");
$form->addElement("static", null, "<p>Ask for help directly or find out more about " . SITE_SHORT_TITLE . " and how to join in our <a href='pages.php?id=7'>information pages</a>.</p>");

$form->addElement("text", "name", "Your name");
$form->addElement("text", "email", "Your email");
//$form->addElement("text", "phone", "Phone");
//$form->addElement("static", null, null, null);
$form->addElement("textarea", "message", "Your message", array("cols"=>65, "rows"=>10, "wrap"=>"soft"));
$form->addElement("static", null, null, null);
//$heard_from = array ("0"=>"(Select One)", "1"=>"Newspaper", "2"=>"Radio", "3"=>"Search Engine", "4"=>"Friend", "5"=>"Local Business", "6"=>"Article", "7"=>"Other");
//$form->addElement("select", "how_heard", "How did you hear about us?", $heard_from);
//if($recaptchaenabled){
//CT: include recaptcha if set
/*
if(RECAPTCHA_VALIDATION) $form->addElement("static", null, "<img id=\"captcha\" src=\"".RECAPTCHA_SRC."securimage_show.php\" alt=\"CAPTCHA Image\" /> <button style=\"font-size:1.2em\" onclick=\"document.getElementById('captcha').src = '".RECAPTCHA_SRC."securimage_show.php?' + Math.random(); return false\" title=\"Load another captcha image\">&#x21bb;</button><br />Enter the text you see *: <input type=\"text\" name=\"captcha_code\" id=\"captcha_code\" size=\"10\" maxlength=\"6\" />", null);
//}
*/

//$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Send");

//
// Define form rules
//
$form->addRule("name", "Enter your name", "required");
$form->addRule("email", "Enter your email", "required");
$form->addRule("message", "Enter a message", "required");
//$form->addRule("phone", "Enter your phone number", "required");
$form->registerRule('checkmail', 'function', 'checkEmail');
$form->addRule('email', 'Enter a valid email', 'checkmail', true);

//$output = "<p>Ask for help directly or find out more about " . SITE_SHORT_TITLE . " and how to join in our <a href='pages.php?id=7'>information pages</a>.</p>";

//$output .= $form->toHtml();
if ($form->validate()) { // Form is validated so processes the data
	if (RECAPTCHA_VALIDATION) $securimage = new Securimage();
	//$p->page_msg .= $_POST['captcha_code'];
	//echo "secureimage" . $securimage->check($_POST['captcha_code'];
	if ((RECAPTCHA_VALIDATION) && $securimage->check($_POST['captcha_code']) == false) {
		//$form->addElement("static", null, "<div class='error'>Captcha is missing or incorrect.</div>", null);
		$p->DisplayPage($form->toHtml());
		//$p->DisplayPage("something went wrong");
	}else{	
		$form->freeze();
		$form->process("process_data", $POST);
	}
} else{
	$p->page_content = $form->toHtml();
}
$p->DisplayPage();

//CT: check email
function checkEmail($element_name,$element_value, $domainCheck = false)
{
	$email=$element_value;
    if (preg_match('/^[a-zA-Z0-9\._-]+\@(\[?)[a-zA-Z0-9\-\.]+'.
                   '\.([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/', $email)) {
        if ($domainCheck && function_exists('checkdnsrr')) {
            list (, $domain)  = explode('@', $email);
            if (checkdnsrr($domain, 'MX') || checkdnsrr($domain, 'A')) {
                return true;
            }
            return false;
        }
        return true;
    }
    return false;
}

//
// The form has been submitted with valid data, so process it   
//

function process_data ($values) {
	global $p;
	// send mail and check for errors
	$mailed = mail(EMAIL_ADMIN, "[" . SITE_SHORT_TITLE ."] Contact form", "From: ". $values["name"]."\n\n". wordwrap($values["message"], 64) , "From:". $values["email"]);
	if(isset($mailed) && $mailed==true){
		$output = "Thank you, your message has been sent. We'll get back to you soon.";
	}else{
		$output .= "There was a problem sending the email.";	
	}
	$output .= " <a href=\"javascript:location.reload()\">Go back</a>";
	$p->page_content = $output;
	//$p->DisplayPage();
}



?>
