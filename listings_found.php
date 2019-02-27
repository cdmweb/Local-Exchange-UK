<?php

include_once("includes/inc.global.php");
$p->site_section = LISTINGS;

include_once("classes/class.listing.php");
/* make slightly safer by at least having a catch - too much trust on query strings! */
if($_REQUEST["type"] == WANT_LISTING){
	$type = WANT_LISTING;
}else{
	$type = OFFER_LISTING;
}
if(!empty($_REQUEST["member_id"])){
	$member_id=$_REQUEST["member_id"];
}else{
	$member_id = null;
}

if($_REQUEST["category"] == "0")
	$category = "%";
else
	$category = $_REQUEST["category"];
	
if($_REQUEST["timeframe"] == "0") {
	$since = new cDateTime(LONG_LONG_AGO);
	$timeframe = "";
} 
else {
	$since = new cDateTime("-". $_REQUEST["timeframe"] ." days");
	$timeframe = $_REQUEST["timeframe"];
}

$keyword = $_REQUEST["keyword"];


// show ids only if logged in AND on a generla listing page
if ($cUser->IsLoggedOn() && empty($member_id)){
	$show_ids = true;
}
else {
	$show_ids = false;
}

// instantiate new cOffer objects and load them
$listings = new cListingGroup($_GET["type"]);

$listings->LoadListingGroup(null, $category, $member_id, $since->MySQLTime());

$lID = 0;

if ($listings->listing && KEYWORD_SEARCH_DIR==true && strlen($_GET["keyword"])>0) { // Keyword specified
	
		foreach($listings->listing as $l) { // Check ->title and ->description etc against Keyword
			
			$mem = $l->member;
			$pers = $l->member->getPrimaryPerson();
			
			$match = false;
	
			if (strpos(strtolower($l->title), strtolower($_GET["keyword"]))>-1) { // Offer title
				
				$match = true;
			}
			
			if (strpos(strtolower($l->description), strtolower($_GET["keyword"]))>-1) { // Offer description
				
				$match = true;
			}
			
			if ($cUser->IsLoggedOn()) { // Search is only performed on these params if the user is logged in
				
				if (strpos(strtolower($pers->getFirstName()), strtolower($_GET["keyword"]))>-1) { // Member First Name
					
					$match = true;
				}
				
				if (strpos(strtolower($pers->getLastName()), strtolower($_GET["keyword"]))>-1) { // Member Last Name
					
					$match = true;
				}
				
				if (strpos(strtolower($mem->getMemberId()), strtolower($_GET["keyword"]))>-1) { // Member ID
					
					$match = true;
				}
			
				if (strpos(strtolower($pers->getAddressPostCode()), strtolower($_GET["keyword"]))>-1) { // Postcode
					
					$match = true;
				}
			}
			
			if ($match!=true) {
				
				unset($listings->listing[$lID]);
			}
			
			$lID += 1;
	}
}
// CT construct title
if($type == WANT_LISTING){
	$page_title = WANT_LISTING_HEADING;
}else{
	$page_title = OFFER_LISTING_HEADING;
}
$matchingSearchText = "Search " . $page_title;
if(!empty($member_id)){
	$matchingSearchText .= " for member";
	//$p->page_title = $_REQUEST["type"] ."ed Listings";
	
}
if(!empty($timeframe)){
	$matchingSearchText .= " in Last " . $timeframe . " Days";
	//$p->page_title = $_REQUEST["type"] ."ed Listings";
}
if(!empty($category)&&!$category=="%"){
	$matchingSearchText .= " in category " . $category;
	//$p->page_title = $_REQUEST["type"] ."ed Listings";
}
if(!empty($keyword)){
	$matchingSearchText .= " matching keyword '" . $keyword . "'";
	//$p->page_title = $_REQUEST["type"] ."ed Listings";
}

$p->page_title =$page_title;

$output .= "<div class=''>{$matchingSearchText}</div>";
$output .= "<div class=''><a href='listings.php?type={$type}'>Change search</a></div>";
$output .= $listings->DisplayListingGroup($show_ids);


$p->DisplayPage($output); 

include("includes/inc.events.php");

?>
