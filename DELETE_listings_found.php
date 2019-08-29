<?php

include_once("includes/inc.global.php");
$p->site_section = LISTINGS;

include_once("classes/class.listing.php");

// CT - saves a lot of fuss for GDPR for users revealing too much info in the ads if we just dont allow non-members to see ads!
$cUser->MustBeLoggedOn();

//form - done via GET so results can be linked to
//CT this is messy, can we fix? very english-centric
$search_title = "";
/* make slightly safer by at least having a catch - too much trust on query strings! */
if($_REQUEST["type_code"] == WANT_LISTING_CODE){
	$type = WANT_LISTING;
	$type_code = WANT_LISTING_CODE;
}else{
	$type = OFFER_LISTING;
	$type_code = OFFER_LISTING_CODE;
}

$search_title .= "'". $type . "' listings";

if(!empty($_REQUEST["member_id"]) and $_REQUEST["member_id"] !="%") {
	$member_id = $_REQUEST["member_id"];
	$search_title .= ", member id " . $member_id;
} else{
	$member_id = "%";
}
if(!empty($_REQUEST["category_id"]) and $_REQUEST["category_id"] !="%") {
	$category_id = $_REQUEST["category_id"];
	// todo change to english
	$search_title .= ", category " . $category_id;
} else{
	$category_id = "%";
}

if(!empty($_REQUEST["keyword"])) {
	$keyword = $_REQUEST["keyword"];
	// todo change to english
	$search_title .= ", keywords " . $keyword;
} else{
	$keyword = "%";
}



if(!empty($_REQUEST["timeframe"]) or $_REQUEST["timeframe"] != "0"){
	$timeframe = $_REQUEST["timeframe"];
	$search_title .= " in last " . $timeframe . " days";

} else{
	$timeframe = null;
	$search_title .= ", all time";
}




// instantiate new cOffer objects and load them
$listings = new cListingGroup();

$listings->Load($member_id, $category_id, $keyword, $timeframe, $type_code);

$lID = 0;
/*
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
*/

$p->page_title =$search_title;

//$output .= "<p>Directories: <a href=\"directory.php\">Printable directory (opt-in)</a></p>";
$output .= $listings->DisplayFilterForm($type_code, $category_id, $member_id, $timeframe, $keywords);
$output .= $listings->Display();


$p->DisplayPage($output); 

include("includes/inc.events.php");

?>
