<?php

/************************************************************
This file includes necesary class files and other include files.
It also defines global constants, and kicks off the session. 
It should be included by all pages in the site.  It does not
need to be edited for site installation, and in fact should
only be modified with care.
************************************************************/

/*********************************************************/
/******************* GLOBAL CONSTANTS ********************/

// These constants should only be changed with extreme caution
define("LOGGED_OUT","!");
define("GO_BACK","< Back");
define("GO_NEXT","Next >");
define("GO_FINISH","Finish");
define("REDIRECT_ON_ERROR", true);
define("FIRST", true);
define("LONG_LONG_AGO", "1970-01-01");
define("FAR_FAR_AWAY", "2040-01-01");
define("ACTIVE","A");
define("INACTIVE","I");
define("EXPIRED","E");
define("DISABLED","D");
define("LOCKED","L");
define("BUYER","B");
define("SELLER","S");
define("POSITIVE","3");
define("NEGATIVE","1");
define("NEUTRAL","2");
define ("OFFER_LISTING_HEADING", "Offered Listings");
define ("OFFER_LISTING", "Offer");
define ("OFFER_LISTING_CODE", "O");
define ("WANT_LISTING_HEADING", "Wanted Listings");
define ("WANT_LISTING", "Want");
define ("WANT_LISTING_CODE", "W");
define("DAILY",1);
define("WEEKLY",7);
define("MONTHLY",30);
define("NEVER",0);

// The following constants are used for logging. Add new categories if
// needed, but edit existing ones with caution.
define("TRADE","T"); // Logging event category
define("TRADE_BY_ADMIN","A");
define("TRADE_ENTRY","T");
define("TRADE_REVERSAL","R");
define("TRADE_MONTHLY_FEE", "M");
define("TRADE_MONTHLY_FEE_REVERSAL", "N");
define("FEEDBACK","F"); // Logging event category
define("FEEDBACK_BY_ADMIN","A");
define("ACCOUT_EXPIRATION","E"); // Logging event category - System Event
define("DAILY_LISTING_UPDATES","D"); // Logging event category - System Event
define("WEEKLY_LISTING_UPDATES","W"); // Logging event category - System Event
define("MONTHLY_LISTING_UPDATES","M"); // Logging event category - System Event

/*********************************************************/
define("LOCALX_VERSION", "2.0.alpha-claratee-1");

/**********************************************************/
/***************** DATABASE VARIABLES *********************/

define ("DATABASE_LISTINGS","lets_listings");
define ("DATABASE_PERSONS","lets_person");
define ("DATABASE_MEMBERS","lets_member");
define ("DATABASE_TRADES","lets_trades");
define ("DATABASE_LOGINS","lets_logins");
define ("DATABASE_LOGGING","lets_admin_activity");
define ("DATABASE_USERS","lets_member");
define ("DATABASE_CATEGORIES", "lets_categories");
define ("DATABASE_FEEDBACK", "lets_feedback");
define ("DATABASE_REBUTTAL", "lets_feedback_rebuttal");
define ("DATABASE_NEWS", "lets_news");
define ("DATABASE_UPLOADS", "lets_uploads");
define ("DATABASE_SESSION", "lets_session");
define ("DATABASE_SETTINGS", "lets_settings");
define ("DATABASE_PAGE", "lets_cdm_pages");

/*********************************************************/
// This section is deprecated.  It has been relocated to 
// inc.config.php, and would be removed but for a bunch of
// references to the following two, now bogus, values...

// TODO: Clean up all references and remove the two lines below
define ("SITE_SECTION_DEFAULT",-1);		
define ("SITE_SECTION_OFFER_LIST",0); 
/*********************************************************/


$global = ""; 	// $global lets other includes know that 
					// inc.global.php has been included

include_once("inc.config-local.php");

/* ct third party cleaner for html - prevent xss atttack. */
require_once DOCUMENT_BASE .  '/vendor/htmlpurifier/library/HTMLPurifier.auto.php';




/* Initial session handling code starts */
//CT not writing to db - doesnt
//require_once("session_handler.php");
session_name("LOCAL_EXCHANGE");
session_start();
//ob_start();
/* Initial session handling code ends */

//CT TODO: tidy and consolidate 
//CT campaign against spaghetti code...linking all classes from here
//HELPERS
include_once(CLASSES_PATH ."class.logging.php");
include_once(CLASSES_PATH ."class.datetime.php");
include_once(CLASSES_PATH ."class.error.php");
include_once(CLASSES_PATH ."class.site.php");
include_once(CLASSES_PATH ."class.queries.php");
include_once(CLASSES_PATH ."class.database.php");
include_once(CLASSES_PATH ."class.settings.php");

include_once(CLASSES_PATH ."class.basic.php");

//INFO PAGES
include_once(CLASSES_PATH ."class.info.php");
include_once(CLASSES_PATH ."class.infoEdit.php");
include_once(CLASSES_PATH ."class.infoEditGroup.php");

//PERSON
include_once(CLASSES_PATH ."class.person.php");
include_once(CLASSES_PATH ."class.personSecondary.php");



//FEEDBACK
include_once(CLASSES_PATH ."class.feedback.php");
include_once(CLASSES_PATH ."class.feedbackSummary.php");
include_once(CLASSES_PATH ."class.feedbackGroup.php");
include_once(CLASSES_PATH ."class.feedbackRebuttal.php");
include_once(CLASSES_PATH ."class.feedbackRebuttalGroup.php");

//CATEGORY
include_once(CLASSES_PATH ."class.category.php");
include_once(CLASSES_PATH ."class.categoryGroup.php");
//LISTING
include_once(CLASSES_PATH ."class.listing.php");
include_once(CLASSES_PATH ."class.listingEdit.php");
include_once(CLASSES_PATH ."class.listingGroup.php");
include_once(CLASSES_PATH ."class.listingGroupEdit.php");
// TRADE
include_once(CLASSES_PATH ."class.trade.php");
include_once(CLASSES_PATH ."class.tradePending.php");
include_once(CLASSES_PATH ."class.tradeSummary.php");
include_once(CLASSES_PATH ."class.tradeGroup.php");

//MEMBERS
include_once(CLASSES_PATH ."class.member.php");
include_once(CLASSES_PATH ."class.memberSummary.php");
include_once(CLASSES_PATH ."class.memberConcise.php");
include_once(CLASSES_PATH ."class.memberSelf.php");
include_once(CLASSES_PATH ."class.memberEdit.php");
include_once(CLASSES_PATH ."class.memberLabel.php");
include_once(CLASSES_PATH ."class.memberIncomeTies.php");
include_once(CLASSES_PATH ."class.memberGroup.php");
include_once(CLASSES_PATH ."class.memberGroupMenu.php");
include_once(CLASSES_PATH ."class.login_history.php");
$cUser = new cMemberSelf();
$cUser->RegisterWebUser();
//global $site_settings;
include_once(CLASSES_PATH ."class.page.php");



// The following is necessary because of a PHP 4.4 bug with passing references
error_reporting( E_ALL & ~E_NOTICE );

// For maintenance, see inc.config.php
if(DOWN_FOR_MAINTENANCE and !$running_upgrade_script) {
	$p->DisplayPage(MAINTENANCE_MESSAGE);
	exit;
}

// [chris] Uncomment this line to surpress non-fatal Warning and Notice errors
//error_reporting(E_ALL &~ (E_NOTICE | E_WARNING));	
//CT: todo - put somewhere better. create site class
function showMessage($msg){
	echo "<p>" . $msg . "</p>";
}


?>
