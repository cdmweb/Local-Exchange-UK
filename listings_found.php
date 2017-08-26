<?php
// var $tmp;

include_once("includes/inc.global.php");
$tmp = (strtolower($_REQUEST["type"])) ? "Offered" : "Wanted";

$p->site_section = LISTINGS;

//CT: tidy this up for readabiity and change
//if(strtolower($_REQUEST["type"]) = "offer"){
$tmp = (strtolower($_REQUEST["type"])) ? "Offered" : "Wanted";
$p->page_title = $tmp . " Listings";

include_once("classes/class.listing.php");

if($_REQUEST["category"] == "0")
	$category = "%";
else
	$category = $_REQUEST["category"];
//CT: better handling of errors - prevent the from happening :)
if(is_numeric($_REQUEST["timeframe"]) && intval($_REQUEST["timeframe"])) {
//if($_REQUEST["timeframe"] !="0") {
	$since = new cDateTime("-". $_REQUEST["timeframe"] ." days");
	$tmp = " in the last " . $_REQUEST["timeframe"] . " days";
}else {	
	$since = new cDateTime(LONG_LONG_AGO);
	$tmp = ", all time";
}
$p->page_title .= $tmp;


if ($cUser->IsLoggedOn())
	$show_ids = true;
else
	$show_ids = false;

// instantiate new cOffer objects and load them
$listings = new cListingGroup($_GET["type"]);
			
$listings->LoadListingGroup(null, $category, null, $since->MySQLTime());

$lID = 0;

if ($listings->listing && KEYWORD_SEARCH_DIR==true && strlen($_GET["keyword"])>0) { // Keyword specified
	
		foreach($listings->listing as $l) { // Check ->title and ->description etc against Keyword
			
			$mem = $l->member;
			$pers = $l->member->person[0];
			
			$match = false;
	
			if (strpos(strtolower($l->title), strtolower($_GET["keyword"]))>-1) { // Offer title
				
				$match = true;
			}
			
			if (strpos(strtolower($l->description), strtolower($_GET["keyword"]))>-1) { // Offer description
				
				$match = true;
			}
			
			if ($cUser->IsLoggedOn()) { // Search is only performed on these params if the user is logged in
				
				if (strpos(strtolower($pers->first_name), strtolower($_GET["keyword"]))>-1) { // Member First Name
					
					$match = true;
				}
				
				if (strpos(strtolower($pers->last_name), strtolower($_GET["keyword"]))>-1) { // Member Last Name
					
					$match = true;
				}
				
				if (strpos(strtolower($mem->member_id), strtolower($_GET["keyword"]))>-1) { // Member ID
					
					$match = true;
				}
			
				if (strpos(strtolower($pers->address_post_code), strtolower($_GET["keyword"]))>-1) { // Postcode
					
					$match = true;
				}
			}
			
			if ($match!=true) {
				
				unset($listings->listing[$lID]);
			}
			
			$lID += 1;
	}
}
$output = "<p><a href='listings.php?type=Offer'>Change search criteria</a></p>";

$output .= $listings->DisplayListingGroup($show_ids);

$p->DisplayPage($output); 

include("includes/inc.events.php");

?>
