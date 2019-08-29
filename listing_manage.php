<?php

include_once("includes/inc.global.php");

$cUser->MustBeLoggedOn();
//todo - get memberid from query string in admin mode
$member_id = $cUser->getMemberId();
if ($_REQUEST["type"] == WANT_LISTING_CODE) {
	$type = WANT_LISTING_CODE;
	$type_description = WANT_LISTING;
} 
if ($_REQUEST["type"] == OFFER_LISTING_CODE) {
	$type = OFFER_LISTING_CODE;
	$type_description = OFFER_LISTING;
} 

$p->page_title = "Manage your '". $type_description ."' listings";

if ($_POST["submit"]){
	$status = array();
	// CT arrange permission array according to role selection - makes it easier to process mysql
	$validate=false;
	if(!empty($_POST["action"])){
		foreach($_POST["select_id"] as $select_id){
			// CT if first one...you know this as validate== false
			if (!$validate) {
				$validate=true;
				$condition="";
			}else{
				$condition .=" OR ";
			}
			$condition .= "listing_id={$select_id}";

			//var_dump($select_id);

			//$value=$_POST["permission_" . $page->page_id];
			//$status[$_POST["status_" . $listing->getListing()]][] = $listing->getListing();
		}
		if (!$validate) {
			$cErr->Error("No listings selected.");
		}else{
			//$key}`=\"{$this->EscTxt($value)}
			if($_POST["action"] == "D") $string_query = $cDB->BuildDeleteQuery(DATABASE_LISTINGS, $condition);
			else {
				$array = array("status" => $_POST["action"]);
				$string_query = $cDB->BuildUpdateQuery(DATABASE_LISTINGS, $array, $condition);
			}
			$is_success = $cDB->Query($string_query);
			if(!$is_success) {
				$cErr->Error("couldn't save");
			}
		}

	}else{
		$cErr->Error("No action selected");
	}
	
	//$listings->Save($vars);
}
//$listings->Load($cUser->getMemberRole());



$listings = new clistingGroupEdit();
//Load($member_id=null, $category_id=null, $since=null, $timeframe=null, $type_code=null)
$condition = $listings->makeFilterCondition($member_id, null, null, null, $type_code);
$listings->Load($condition);



$i=0;
$row_output =  "";
foreach($listings->getListings() as $listing) {
	//stripy columns
	$className= ($i%2) ? "even" : "odd";
	if($listing->getStatus()=="E" OR $listing->getStatus()=="I") $className .= " expired";
	$listing_id = $listing->getListingId();
	//<td>{$listing->PrepareStatusDropdown($listing_id)}</td>	
	$row_output .=  "
		<tr class=\"{$className}\">
			<td>{$listings->PrepareCheckbox($listing_id)}</td>
			<td><div class=\"text\"><a href=\"listing_detail.php?listing_id={$listing_id}\">{$listing->getTitle()} </a></div><span class=\"metadata\">listing_id: {$listing_id}</span></td>
			<td><div class=\"text\">{$listing->getCategoryName()}</div></td>
			<td><div class=\"text\">{$listing->getPostingDate()}</div></td>
			<td><a href=\"listing_edit.php?listing_id={$listing_id}\" class=\"button edit\"><i class=\"fas fa-pencil-alt\"></i> edit</a> </td>			
		</tr>";
	$i++;
}

$output .= "
	<!-- START bulk form pages -->
	<form method=\"post\">
		<table class=\"tabulated\">
			<tr>
				<th></th>
				<th>Title</th>
				<th>Category</th>
				<th>Updated</th>
				<th></th>
			</tr>
			{$row_output}
		</table>
		<p><label>Bulk actions: {$listings->PrepareActionDropdown()}</label><span class=\"metadata\">{$i} items found</span></p>
		<p><input id=\"submit\" name=\"submit\" type=\"submit\" value=\"Apply changes\"></p>
	</form>
	<!-- END bulk form pages -->
";

$p->DisplayPage($output);
