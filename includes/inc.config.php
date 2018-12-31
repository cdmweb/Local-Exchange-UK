<?php
/* v1.0 note: a lot of these settings are now stored in MySQL and are configurable from the admin menu */

if (!isset($global) && $running_upgrade_script!=true)
{
	die(__FILE__." was included directly.  This file should only be included via inc.global.php.  Include() that one instead.");
}

if (file_exists("upgrade.php") && $running_upgrade_script!=true) {
	
	die("<font color='red'>The file 'upgrade.php' was located on this server.</font>
	<p>If you are in the process of upgrading, that's fine, please <a href=upgrade.php>Click here</a> to run the upgrade script.<p>If you are NOT in the process of upgrading then leaving this file on the server poses a serious security hazard. Please remove this file immediately.");
}

/**********************************************************/
/******************* SITE LOCATIONS ***********************/
 
// What is the domain name of the site?  
define ("SERVER_DOMAIN","localhost:8888");	// no http://

// What is the path to the site? This is null for many sites.
define ("SERVER_PATH_URL","/members");	// no ending slash

// The following only needs to be set if Pear has been
// installed manually by downloading the files
define ("PEAR_PATH", "/Applications/MAMP/PEAR"); // no ending slash

// Ok, then lets define some paths (no need to edit these)
define ("HTTP_BASE",SERVER_DOMAIN.SERVER_PATH_URL);
define ("CLASSES_PATH",$_SERVER["DOCUMENT_ROOT"].SERVER_PATH_URL."/classes/");
define ("IMAGES_PATH",SERVER_DOMAIN.SERVER_PATH_URL."/images/");
define ("STYLES_PATH",SERVER_DOMAIN.SERVER_PATH_URL."/styles/");
define ("UPLOADS_PATH",$_SERVER["DOCUMENT_ROOT"].SERVER_PATH_URL."/uploads/");


/**********************************************************/
/***************** DATABASE LOGIN  ************************/

define ("DATABASE_USERNAME","clarat2_leuser");
define ("DATABASE_PASSWORD","05dbI?uWkWBy");
//define ("DATABASE_NAME","clarat2_localexchange");
// local localexchange-1.02;
define ("DATABASE_NAME","localexchange-1.02");
define ("DATABASE_SERVER","localhost:8889/"); // often "localhost"

/**********************************************************/
/********************* SITE NAMES *************************/

// What is the name of the site?
define ("SITE_LONG_TITLE", "CamLETS Local Exchange and Trading Scheme");

// What is the short, friendly, name of the site?
define ("SITE_SHORT_TITLE", "CamLETS");

/**********************************************************/
/***************** FOR MAINTENANCE ************************/

// If you need to take the website down for maintenance (such
// as during an upgrade), set the following value to true
// and customize the message, if you like

define ("DOWN_FOR_MAINTENANCE", false);
define ("MAINTENANCE_MESSAGE", SITE_LONG_TITLE ." is currently down for maintenance.  Try back in a little while.");


/***************************************************************************************************/
/***************** 01-12-08 - 19-12-08 Chris Macdonald (chris@cdmweb.co.uk) ************************/

// The following preferences can be set to turn on/off any of the new features

/* Set the MINIMUM Permission Level a member must hold to be able to submit ANY and ALL HTML
 * 0 = Members, 1 = Committee, 2 = Admins 
 * Note: This group will be allowed to submit any HTML tags and will not be restricted by the 'Safe List' defined below */
define("HTML_PERMISSION_LEVEL",1);

// ... HTML Safe List - define the tags that you want to allow all other users (who are below HTML_PERMISSION_LEVEL) to submit
//  Note the format should be just the tag name itself WITHOUT brackets (i.e. 'table' and not '<table>')
$allowedHTML = array('em','i','b','a','br','ul','ol','li','center','img','p');
// [TODO] Taking this a step further we could also specify whether or not a tag is allowed with parameters - currently by default parameters are allowed  

// Should we remove any JavaScript found in incoming data? Yes we should.
define("STRIP_JSCRIPT",true);

// Member images are resized 'on-the-fly', keeping the original dimensions. Specify the maximum width the image is to be DOWN-sized to here.
define("MEMBER_PHOTO_WIDTH",200); // in pixels

// Do we want to UP-scale images that are smaller than MEMBER_PHOTO_WIDTH (may look a bit ugly and pixelated)?
define("UPSCALE_SMALL_MEMBER_PHOTO",false);

// The options available in the 'How old is you?' dropdown (trying to be as innocuous as possible here with the defaults (e.g. 40's)- but feel free to provide more specific options)
$agesArr = array('---','Under 18', '18-30','30\'s','40\'s','50\'s','60\'s','70\'s','Over 80','n/a',);

// The options available in the 'What Sex are you?' dropdown. At the time of writing (01-12-2008) the defaults should be fine
$sexArr = array("---", "Male","Female","n/a");

// Enable JavaScript bits on the Dropdown Member Select Box?
// This applies to the Transfer form; the idea is that it makes it simpler to find the member we're after if the dropdown list is lengthy
define("JS_MEMBER_SELECT",true);
// [TODO] Need to make this better - AJAX is probably the best method for this

// Give the option of searching Offers/Wants by KEYWORD?
define("KEYWORD_SEARCH_DIR",true);

// Allow members to Search the Members List? (Handy if the members list is long)
define("SEARCHABLE_MEMBERS_LIST",true);


// END 01-12-08 changes by chris

/**************************************************************/
/******************** SITE CUSTOMIZATION **********************/

// email addresses & phone number to be listed in the site
define ("EMAIL_FEATURE_REQUEST","admin@camlets.org.uk"); // (is this actually used anywhere???)
//define ("EMAIL_NOREPLY","admin@camlets.org.uk");
define ("EMAIL_ADMIN","admin@camlets.org.uk");

define ("PHONE_ADMIN","360-321-1234"); // an email address may be substituted...

// What should appear at the front of all pages?
// Titles will look like "PAGE_TITLE_HEADER - PAGE_TITLE", or something 
// like "Local Exchange - Member Directory";
define ("PAGE_TITLE_HEADER", SITE_SHORT_TITLE);

// What keywords should be included in all pages?
define ("SITE_KEYWORDS", "local currency,mutual credit,lets,exchange,". SITE_LONG_TITLE ."");

// Logo Graphic for Header
define ("HEADER_LOGO", "mosaic-110.jpg");

// Title Graphic for Header
define ("HEADER_TITLE", "localx_title.png");

// Logo for Home Page
define ("HOME_LOGO", "localx_black.png");

// Picture appearing left of logo on Home Page
define ("HOME_PIC", "localx_home.png");

// What content should be in the site header and footer?
//CT: todo - make nice

define ("PAGE_HEADER_CONTENT", "<div class=\"masthead\"><a href=\"index.php\" class=\"logo\"><img src=\"http://".HTTP_BASE."/images/". HEADER_LOGO ."\" alt=\"". SITE_SHORT_TITLE . " \"></a><div class=\"title\"><h1><a href=\"index.php\">" .  SITE_SHORT_TITLE ."</a></h1><div class=\"motto\">Cambridge's local exchange and trading scheme</div></div></div>");

define ("PAGE_FOOTER_CONTENT", "<p align=\"center\"><strong><a href=\"". SERVER_PATH_URL ."\">". SITE_LONG_TITLE ." </strong><br />Licensed under the <a href=\"http://www.gnu.org/copyleft/gpl.html\">GPL</a> &#8226; Local Exchange UK Ver. ".LOCALX_VERSION." <a href=\"http://". SERVER_DOMAIN . SERVER_PATH_URL ."/info/credits.php\">Credits</a></p>");



/**********************************************************/
/**************** DEFINE SIDEBAR MENU *********************/

$SIDEBAR = array (
	array("Home","index.php"),
	array("Information", "pages.php?id=7"), // old style info pages
// [CDM] uncomment line below to activate new style info pages 	
//  array("Information","pages.php?id=1"),
	array("News &amp; events","pages.php?id=84"),
	array("Offered","listings_found.php?type=Offer&keyword=&category=0&timeframe=14"),
	array("Wanted","listings_found.php?type=Want&keyword=&category=0&timeframe=14"),
	array("Member directory","member_directory.php"),
	array("Contact us","contact.php"),
	array("",""),
	array("My profile","member_profile_all_in_one.php"),
	array("My trades","exchange_menu.php"));

/**********************************************************/
/**************** DEFINE SITE SECTIONS ********************/

define ("EXCHANGES",0);
define ("LISTINGS",1);
define ("EVENTS",2);
define ("ADMINISTRATION",3);
define ("PROFILE",4);
define ("SECTION_FEEDBACK",5);
define ("SECTION_EMAIL",6);
define ("SECTION_INFO",7);
define ("SECTION_DIRECTORY",8);

$SECTIONS = array (
	array(0, "Exchanges", "exchange.gif"),
	array(1, "Listings", "listing.png"),
	array(2, "Events", "news.png"),
	array(3, "Administration", "admin.png"),
	array(4, "Events", "member.png"),
	array(5, "Feedback", "feedback.png"),
	array(6, "Email", "contact.png"),
	array(7, "Info", "info.png"),
	array(8, "Directory", "directory.png"));

/**********************************************************/
/******************* GENERAL SETTINGS *********************/

define ("UNITS", "Cams");  // This setting affects functionality, not just text displayed, so if you want to use hours/minutes this needs to read "Hours" exactly.  All other unit descriptions are ok, but receive no special treatment (i.e. there is no handling of "minutes").


/**************** Monthly fee related settings ********************/

define("SYSTEM_ACCOUNT_ID", "system");
$monthly_fee_exempt_list = array("ADMIN", SYSTEM_ACCOUNT_ID);

// End of monthly fee related settings.

define ("MAX_FILE_UPLOAD","5000000"); // Maximum file size, in bytes, allowed for uploads to the server
									 
// The following text will appear at the beggining of the email update messages
define ("LISTING_UPDATES_MESSAGE", "<h1>".SITE_LONG_TITLE."</h1>The following listings are new or updated.<p>Change how often you get these emails on your <a href=http://".HTTP_BASE."/member_edit.php?mode=self>Profile page</a>.");

// Should inactive accounts have their listings automatically expired?
// This can be a useful feature.  It is an attempt to deal with the 
// age-old local currency problem of new members joining and then not 
// keeping their listings up to date or using the system in any way.  
// It is designed so that if a member doesn't record a trade OR update 
// a listing in a given period of time (default is six months), their 
// listings will be set to expire and they will receive an email to 
// that effect (as will the admin).
define ("EXPIRE_INACTIVE_ACCOUNTS",false); 

// If above is set, after this many days, accounts that have had no
// activity will have their listings set to expire.  They will have 
// to reactiveate them individually if they still want them.
define ("MAX_DAYS_INACTIVE","180");  

// How many days in the future the expiration date will be set for
define ("EXPIRATION_WINDOW","15");	

// How long should expired listings hang around before they are deleted?
define ("DELETE_EXPIRED_AFTER","90"); 


// The following message is the one that will be emailed to the person 
// whose listings have been expired (a delicate matter).
define ("EXPIRED_LISTINGS_MESSAGE", "Hello,\n\nDue to inactivity, your ".SITE_SHORT_TITLE." listings have been set to automatically expire ". EXPIRATION_WINDOW ." days from now.\n\nIn order to keep the ".SITE_LONG_TITLE." system up to date and working smoothly for all members, we have developed an automatic system to expire listings for members who haven't recorded exchanges or updated their listings during a period of ".MAX_DAYS_INACTIVE." days. We want the directory to be up to date, so that members do not encounter listings that are out of date or expired. This works to everyone's advantage.\n\nWe apologize for any inconvenience this may cause you and thank you for your participation. If you have any questions or comments, or are unsure how to best use the system, please reply to this email message or call us at ".PHONE_ADMIN.".\n\nYou have ". EXPIRATION_WINDOW ." days to login to the system and reactivate listings that you would still like to have in the directory.  If you do not reactivate them during that timeframe, your listings will no longer appear in the directory, but will still be stored in the system for another ". DELETE_EXPIRED_AFTER ." days, during which time you can still edit and reactivate them.\n\n\nInstructions to reactivate listings:\n1) Login to the website\n2) Go to Update Listings\n3) Select Edit Offered (or Wanted) Listings\n4) Select the listing to edit\n5) Uncheck the box next to 'Should this listing be set to automatically expire?'\n6) Press the Update button\n7) Repeat steps 1-6 for all listings you wish to reactivate\n");

// The year your local currency started -- the lowest year shown
// in the Join Year menu option for accounts.
define ("JOIN_YEAR_MINIMUM", "2005");  

define ("DEFAULT_COUNTRY", "United Kingdom");
define ("DEFAULT_ZIP_CODE", "CB1"); // This is the postcode.
define ("DEFAULT_CITY", "Cambridge");
define ("DEFAULT_STATE", "Cambridgeshire");
define ("DEFAULT_PHONE_AREA", "01223");

// Should short date formats display month before day (US convention)?
define ("MONTH_FIRST", false);		

define ("PASSWORD_RESET_SUBJECT", "Your ". SITE_LONG_TITLE ." Account");
define ("PASSWORD_RESET_MESSAGE", "Your password for ". SITE_LONG_TITLE ." has been reset. If you did not request this reset, it is possible your account has been compromised, and you may want to contact the site administrator at ".PHONE_ADMIN.".\n\nYour user id and new password are listed at the end of this message. You can change the automatically generated password by going to the Member Profile section after you login.");
define ("NEW_MEMBER_SUBJECT", "Welcome to ". SITE_LONG_TITLE);
define ("NEW_MEMBER_MESSAGE", "Hello, and welcome to the ". SITE_LONG_TITLE ." community!\n\nA member account has been created for you at:\nhttp://".SERVER_DOMAIN.SERVER_PATH_URL."/member_login.php\n\nPlease login and create your Offered and Wanted Listings.  Your new user id and password are listed at the end of this message. You can change the automatically generated password by going to the Member Profile section after you login.\n\nThank you for joining us.");

/********************************************************************/
/************************* ADVANCED SETTINGS ************************/
// Normally, the defaults for the settings that follow don't need
// to be changed.

// What's the name and location of the stylesheet?
define ("SITE_STYLESHEET", "styles/style.css");

// How long should trades be listed on the "leave feedback for 
// a recent exchange" page?  After this # of days they will be
// dropped from that list.
define ("DAYS_REQUEST_FEEDBACK", "30"); 

// Is debug mode on? (display errors to the general UI?)
define ("DEBUG", false);

// Should adminstrative activity be logged?  Set to 0 for no logging; 1 to 
// log trades recorded by administrators; 2 to also log changes to member 
// settings (LEVEL 2 NOT YET IMPLEMENTED)
define ("LOG_LEVEL", 1);

// How many consecutive failed logins should be allowed before locking out an account?
// This is important to protect against dictionary attacks.  Don't set higher than 10 or 20.
define ("FAILED_LOGIN_LIMIT", 10);

// Are magic quotes on?  Site has not been tested with magic_quotes_runtime on, 
// so if you feel inclined to change this setting, let us know how it goes :-)
define ("MAGIC_QUOTES_ON",false);
set_magic_quotes_runtime (0);

// CSS-related settings.  If you'r looking to change colors, 
// best to edit the CSS rather than add to this...
$CONTENT_TABLE = array("id"=>"contenttable", "cellspacing"=>"0", "cellpadding"=>"3");

// System events are processes which only need to run periodically,
// and so are run at intervals rather than weighing the system
// down by running them each time a particlular page is loaded.
// System Event Codes (such as ACCOUNT_EXPIRATION) are defined in inc.global.php
// System Event Frequency (how many minutes between triggering of events)
$SYSTEM_EVENTS = array (
	ACCOUT_EXPIRATION => 1440);  // Expire accounts once a day (every 1440 minutes)

// The following relates to the create_db.php install script
// As of MySQL 5 'TYPE' has been deprecated in favour of 'ENGINE' for specifying the database engine to use. 
// I am reluctant to 'hard code' this change into the statements in create_db.php through fear of causing compatibility problems on servers running older versions 
// of MySQL, so for the purposes of backward compatibility I have included an easy way to default back to the old syntax here
$engineSyntax = 'ENGINE'; // Try 'TYPE' if you have problems running this script


/**********************************************************/
//	Everything below this line simply sets up the config.
//	Nothing should need to be changed, here.

if (PEAR_PATH != "")
	ini_set("include_path", PEAR_PATH .'/' . PATH_SEPARATOR . ini_get("include_path"));



if (DEFAULT_COUNTRY == "United States") {
    define ("ADDRESS_LINE_1", "Address Line 1");
    define ("ADDRESS_LINE_2", "Address Line 2");
    define ("ADDRESS_LINE_3", "City");
	define ("ZIP_TEXT", "Zip Code");
	define ("STATE_TEXT", "State");
}
// These are for other countries.  Change it according to your requirements.
else if (DEFAULT_COUNTRY == "United Kingdom") {
    define ("ADDRESS_LINE_1", "Street address");
    define ("ADDRESS_LINE_2", "Neighbourhood");
    define ("ADDRESS_LINE_3", "Town or City");
	define ("ZIP_TEXT", "Postcode");
	define ("STATE_TEXT", "County");
}

//CT: don't log deprecated errors - far too many of them! todd:fix
if (DEBUG) error_reporting(E_ALL);
else error_reporting(E_ALL  & ~E_DEPRECATED);

define("LOAD_FROM_SESSION",-1);  // Not currently in use

// URL to PHP page which handles redirects and such.
define ("REDIRECT_URL",SERVER_PATH_URL."/redirect.php");

//CT: put in form
define ("MEM_LIST_DISPLAY_EMAIL", true);
//CT NEW - used in mysql date formats - should match convention of the locale
//UK
define ("SHORT_DATE_FORMAT", "%e/%c/%y");
define ("LONG_DATE_FORMAT", "%c %M %Y");	

/*
//US
define ("SHORT_DATE_FORMAT", "%c/%e/%y"); 	
define ("LONG_DATE_FORMAT", "%M %e %Y");	
*/
//CT: if you want recaptcha protection for public forms, download securimage (simple php recaptcha) 
// from https://www.phpcaptcha.org/ to the /thirdparty directory, configure the inc.configure.
// Set RECAPTCHA_VALIDATION to true to start using it.
define ("RECAPTCHA_VALIDATION",true);
define ("RECAPTCHA_SRC","vendor/securimage/");

//CT: set to TRUE if you want error messages to show to administrators

