<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.category.php");
include_once("class.feedback.php");

class cListing
{
	var $member; // this will be an object of class cMember
	var $title;
	var $description;
	var $category; // category name
	var $category_id; // category id
	var $rate;
	var $status;
	var $posting_date; // the date a listing was created or last modified
	var $expire_date;
	var $reactivate_date;
	var $type; 
	var $type_code; 


	function cListing($member=null, $values=null) {
		if(!empty($member) && !empty($values)) {
			$this->member = $member;
			$this->title = $values['title'];
			$this->description = $values['description'];
			$this->rate = $values['rate'];
			$this->expire_date = $values['expire_date'];
			$this->type = $values['type'];
			$this->reactivate_date = null;
			$this->status = $values['status'];
			$this->category = $values['category'];
			$this->category_id->$values['category_id'];
		} 
		
	}	

	function TypeCode($type) {

		if($type == OFFER_LISTING)
			return OFFER_LISTING_CODE;
		else
			return WANT_LISTING_CODE;			
	}

	function TypeDesc($type_code) {
		if($type_code == OFFER_LISTING_CODE)
			return OFFER_LISTING;
		else
			return WANT_LISTING;			
	}

	function SaveNewListing() {
		global $cDB, $cErr;		

		$insert = $cDB->Query("INSERT INTO ".DATABASE_LISTINGS." (title, description, category_code, member_id, rate, status, expire_date, reactivate_date, type) VALUES (". $cDB->EscTxt($this->title) .",". $cDB->EscTxt($this->description) .",". $cDB->EscTxt($this->category->id) .",". $cDB->EscTxt($this->member->member_id) .",". $cDB->EscTxt($this->rate) .",". $cDB->EscTxt($this->status) .",". $cDB->EscTxt($this->expire_date) .",". $cDB->EscTxt($this->reactivate_date) .",". $cDB->EscTxt($this->TypeCode($this->type)) .");");	

		return $insert;
	}			
		
	function SaveListing($update_posting_date=true) {
		global $cDB, $cErr;			
		
		if(!$update_posting_date)
			$posting_date = ", posting_date=posting_date";
		else
			$posting_date = "";

		$update = $cDB->Query("UPDATE ".DATABASE_LISTINGS." SET title=". $cDB->EscTxt($this->title) .", description=". $cDB->EscTxt($this->description) .", category_code=". $this->category_code .", rate=". $cDB->EscTxt($this->rate) .", status=". $cDB->EscTxt($this->status) .", expire_date=". $cDB->EscTxt($this->expire_date) .", reactivate_date=". $cDB->EscTxt($this->reactivate_date) . $posting_date ." WHERE title=". $cDB->EscTxt($this->title) ." AND member_id=". $cDB->EscTxt($this->member_id) ." AND type=". $cDB->EscTxt($this->TypeCode($this->type)) .";");	

		return $update;
	}
	
	function DeleteListing($title,$member_id,$type_code) {
		global $cDB, $cErr;
		
		$query = $cDB->Query("DELETE FROM ". DATABASE_LISTINGS ." WHERE title=".$cDB->EscTxt($title)." AND member_id=". $cDB->EscTxt($member_id) ." AND type=".  $cDB->EscTxt($type_code) .";");

		return mysql_affected_rows();
	}
							
	function LoadListing($title,$member_id,$type)
	{
		global $cDB, $cErr;
		//print("type" . $type);

		$category='%';

		// select all offer data and populate the variables

		$queryString = "SELECT 
		m.member_id as member_id, 
		m.balance as balance,
		l.title as title, 
		l.type as type, 
		l.description as description, 
		l.rate as rate,  
		l.posting_date as posting_date, 
		l.status as status, 
		l.expire_date as expire_date, 
		l.reactivate_date as reactivate_date,
		concat(p1.first_name, \" \", p1.last_name, if(p2.first_name is not null, concat(\" and \", p2.first_name, \" \", p2.last_name),\"\")) as all_names,
		p1.address_post_code as address_post_code,
		p1.address_street2 as address_street2,
		l.category_code as category_code,
		c.description as category
		FROM ".DATABASE_LISTINGS." l 
		left JOIN person p1 ON l.member_id=p1.member_id
		left JOIN (select * from person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id
		left JOIN member m ON l.member_id=m.member_id
		LEFT JOIN categories c ON c.category_id=l.category_code 
		WHERE p1.primary_member = 'Y' and 
		m.status = 'A' and title LIKE ". $cDB->EscTxt($title) ." AND l.type='". $this->TypeCode($type) ."' AND l.member_id LIKE ". $cDB->EscTxt($member_id);

		$query = $cDB->Query($queryString . " ORDER BY l.type, c.description, l.title, l.member_id;");
		// CT: todo - consolidate query with 
	
		if($values = mysql_fetch_array($query))
		{		

			$this->ConstructListing($values);
		}
		else 
		{
			$cErr->Error("There was an error accessing the ".$cDB->EscTxt($title)." listing for ".$member_id.". Please contact" . EMAIL);
			//include("redirect.php");
		}		
		
		$this->DeactivateReactivate();
	}
	function ConstructListing($values)
	{
		global $cDB, $cErr;
		
		// select all offer data and populate the variables
		//$query = $cDB->Query("SELECT description, category_code, member_id, rate, status, posting_date, expire_date, reactivate_date FROM ".DATABASE_LISTINGS." WHERE title=".$cDB->EscTxt($title)." AND member_id=" . $cDB->EscTxt($member_id) . " AND type=". $cDB->EscTxt($type_code) .";");
		
		if($values)
		{		

			$this->title=$values['title'];
			$this->description=$cDB->UnEscTxt($values['description']);
			$this->member_id=$values['member_id'];
			$this->rate=$cDB->UnEscTxt($values['rate']);
			$this->status=$values['status'];
			$this->posting_date=$values['posting_date'];
			$this->expire_date=$values['expire_date'];
			$this->reactivate_date=$values['reactivate_date'];
			$this->type=$this->TypeDesc($values['type']);
			//$this->type_code=$values['type'];
			$this->category=$values['category'];
			$this->category_code=$values['category_code'];
			/*	
			$this->category = new cCategory();
			$this->category->LoadCategory($values['category_id']);*/
		}
				
		
		// load member associated with member_id
		$this->member = new cMember;
		$this->member->ConstructMember($values);
		//$this->member->LoadMember($member_id);
		
		//$this->DeactivateReactivate();
	}
	
	function DeactivateReactivate() {
		if($this->reactivate_date) {
			$reactivate_date = new cDateTime($this->reactivate_date);
			if ($this->status == INACTIVE and $reactivate_date->Timestamp() <= strtotime("now")) {
				$this->status = ACTIVE;
				$this->reactivate_date = null;
				$this->SaveListing();
			}
		}
		if($this->expire_date) {
			$expire_date = new cDateTime($this->expire_date);
			if ($this->status <> EXPIRED and $expire_date->Timestamp() <= strtotime("now")) {
				$this->status = EXPIRED;
				$this->SaveListing();
			}
		}
	}
			
	function ShowListing()
	{
		$output = $this->type . "ed Data:<BR>";
		$output .= $this->title . ", " . $this->description . ", " . $this->category->id . ", " . $this->member->member_id . ", " . $this->rate . ", " . $this->status . ", " . $this->posting_date . ", " . $this->expire_date . ", " . $this->reactivate_date . "<BR><BR>";
		$output .= $this->member->ShowMember();
		
		return $output;
	}
	
	function DisplayListing()
	{
		global $p;
		$output = "";
		if(!empty($this->description)){
//			$output .= $p->WrapLabelValue($this->type, $this->description);
			$output .= $p->Wrap($this->description, "p", "large");
		}
		$output .= $p->WrapLabelValue("Type", $this->type);
		$output .= $p->WrapLabelValue("Category", $this->category);
		$output .= $p->WrapLabelValue("Status", $this->status);
		$output .= $p->WrapLabelValue("expires", $this->expire_date);
		$output .= $p->WrapLabelValue("reactivate on", $this->reactivate_date);
		if(!empty($this->rate)) {
			$output .= $p->WrapLabelValue("Rate", $this->rate . " " . UNITS);
		}
		if(SHOW_DATE_ON_LISTINGS) {
			$posting_date = $this->posting_date;
			$output .= $p->WrapLabelValue("Date posted", $p->FormatShortDate($posting_date));
			//$output .= $p->WrapLabelValue("Date posted", $this->posting_date);
		}
		$output .= $this->member->DisplaySummaryMember();
		return $output;	
	}
}

		class cListingGroup
		{
			//var $title;
			var $listing;  // this will be an array of objects of type cListing
			var $num_listings;  // number of active offers
			var $type;
			var $type_code;
			
			function cListingGroup($type) {
				$this->type = $type;
				if($type == OFFER_LISTING)
					$this->type_code = OFFER_LISTING_CODE;
				else
					$this->type_code = WANT_LISTING_CODE;		
			}
			
			function InactivateAll($reactivate_date) {
				global $cErr;
				
				if (!isset($this->listing))
					return true;
				
				foreach($this->listing as $listing)	{
					$current_reactivate = new cDateTime($listing->reactivate_date, false);
					if(($listing->reactivate_date == null or $current_reactivate->Timestamp() < $reactivate_date->Timestamp()) and $listing->status != EXPIRED) {
						$listing->reactivate_date = $reactivate_date->MySQLDate();
						$listing->status = INACTIVE;
						$success = $listing->SaveListing();
						
						if(!$success)
							$cErr->Error("Could not inactivate listing: '".$listing->title."'");
					}
				}
				return true;
			}
			
			function ExpireAll($expire_date) {
				global $cErr;
				
				if (!isset($this->listing))
					return true;
				
				foreach($this->listing as $listing)	{
					$listing->expire_date = $expire_date->MySQLDate();
					$success = $listing->SaveListing(false);
						
					if(!$success)
						$cErr->Error("Could not expire listing: '".$listing->title."'");
				}
				return true;
			}	
			
			function LoadListingGroup($title=null, $category=null, $member_id=null, $since=null, $include_expired=false, $status=null, $type="O")
			{
				global $cDB, $cErr;

				if(empty($title))
					$this->title = "%";
				else
					$this->title = $title;
					
				if($category == null)
					$category = "%";

				if($status == null)
					$status = "%";

				

				
				//$type = "%";
					
				if(empty($member_id))
					$member_id = "%";
					
				if($since == null) 
					$since = "19990101000000";
					
				if($include_expired)
					$expired = "";
				else
					// if ($this->status <> EXPIRED and $expire_date->Timestamp() <= strtotime("now")) {
					// 	$this->status = EXPIRED;
					// 	//$this->SaveListing();
					// }
					$expired = " AND (l.expire_date is null or (STR_TO_DATE(l.expire_date, '%Y-%m-%d') > CURDATE()) or (STR_TO_DATE(l.reactivate_date, '%Y-%m-%d') < CURDATE())) ";
					
				//select all the member_ids for this $title

		$queryString = "SELECT 
		m.member_id as member_id, 
		m.balance as balance,
		l.title as title, 
		l.type as type, 
		l.description as description, 
		l.rate as rate,  
		l.posting_date as posting_date, 
		l.status as status, 
		l.expire_date as expire_date, 
		l.reactivate_date as reactivate_date,
        concat(p1.first_name, \" \", p1.last_name, if(p2.first_name is not null, concat(\" and \", p2.first_name, \" \", p2.last_name),\"\")) as all_names,
		p1.address_post_code as address_post_code,
		p1.address_street2 as address_street2,
		l.category_code as category_code,
		c.description as category
		FROM ".DATABASE_LISTINGS." l 
		left JOIN person p1 ON l.member_id=p1.member_id
		left JOIN (select * from person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id
		left JOIN member m ON l.member_id=m.member_id
		LEFT JOIN categories c ON c.category_id=l.category_code 
		WHERE p1.primary_member = 'Y' and 
		m.status = 'A' and 
		l.status LIKE ". $cDB->EscTxt($category). " and l.member_id LIKE ". $cDB->EscTxt($member_id). " and  title LIKE ". $cDB->EscTxt($this->title) ." AND l.category_code=c.category_id AND c.category_id LIKE ". $cDB->EscTxt($category) ." AND l.type='". $this->type_code ."' AND l.member_id LIKE ". $cDB->EscTxt($member_id) ." AND l.posting_date >= '". $since ."'". $expired;

		$queryList = $cDB->Query($queryString . " ORDER BY l.type, c.description, l.title, l.member_id;");


		// instantiate new cOffer objects and load them
		$i = 0;
		$this->num_listings = 0;
				
		while($row = mysql_fetch_array($queryList))
		{
			$this->listing[$i] = new cListing;			
			//$this->listing[$i]->LoadListing($row[1],$row[0],$this->type_code);
			//print_r($row);
			$this->listing[$i]->ConstructListing($row);
			if($this->listing[$i]->status == 'A')
			{
				$this->num_listings += 1;
			}
			$i += 1;
		}

		if($i == 0) {
			return false;
		}
		
		return true;
	}
	//CT todo: make work on id number, not title
	function ListingLink($type, $title, $member_id) {
		global $p;
		$link = "http://".HTTP_BASE."/listing_detail.php?type={$type}&title=". urlencode($title) ."&member_id={$member_id}";
		//return $p->Link($text, $link);
		return $p->Link("$title", $link);
	}
	function DisplayListingGroup($show_ids=true, $active_only=true)
	{
		/*[chris]*/ // made some changes to way listings displayed, for better or for worse...
		
		global $cUser,$cDB, $p;
	
		$output = "";
		$current_cat = "";
		$i = 0;
		//print(sizeof($this->listing));
		if(isset($this->listing)) {
			foreach($this->listing as $listing) {
			
					
				if($current_cat != $listing->category) {
					if($i>0) $output .= "</ul>"; // end the last unordered list
					$current_cat = $listing->category;
						
					$output .= "<h3>{$listing->category}</h3>";
					$output .= "<ul class='listing'>";
					

				}
				// CT construct details
				$details = "";
				$memInfo = "";
				if ($cUser->IsLoggedOn()){
					$details .= "<strong>" . $this->ListingLink($listing->type, $listing->title, $listing->member_id) . "</strong>";
					//safe postdoce and address
					$location="{$listing->member->getPrimaryPerson()->getAddressStreet2()}, {$listing->member->getPrimaryPerson()->getSafePostCode()}";
					//CT show member and member link - hide on member page
					if ($show_ids) $memInfo .= " (". $listing->member->getAllNames() . " " . $listing->member->MemberLink() .  ") " . $location . ".";
				} else{
					$details .= $listing->title;
					$memInfo = "";
				}
				$details .= " " . $listing->description; 

				
				
				$output .= "<li>{$details} {$memInfo}";


				if (SHOW_RATE_ON_LISTINGS==true && $listing->rate) {
			
					$output .= " <span class='rate'>" . $listing->rate." ".UNITS. ".</span>";
				}
				if (SHOW_DATE_ON_LISTINGS==true) {
			
					$output .= " <span class='date'>" . $p->FormatShortDate($listing->posting_date) . "</span>";
				}

				$output .= "</li>";
			
						
				// Rate
				
			
				
				$i++;	
			}
			$output .= "</ul>"; // end the last unordered list
			$output .= $p->Wrap($i . " listings found.", "p");
	
		} 
		if($i==0)
			$output = $p->Wrap("No listings found.", "p");
	
								
		return $output;		
	}

}




class cTitleList  
// This class circumvents the cListing class for performance reasons
{
	var $type;
	var $type_code;  // TODO: 'type' needs to be its own class which would include 'type_code'
	var $items_per_page;  // Not using yet...
	var $current_page;   // Not using yet...

	function cTitleList($type) {
		$this->type = $type;
		if($type == OFFER_LISTING)
			$this->type_code = OFFER_LISTING_CODE;
		else
			$this->type_code = WANT_LISTING_CODE;
	}	
									
	function MakeTitleArray($member_id="%") {
		global $cDB, $cErr;

		$query = $cDB->Query("SELECT DISTINCT title FROM ".DATABASE_LISTINGS." WHERE member_id LIKE ". $cDB->EscTxt($member_id) . " AND type=". $cDB->EscTxt($this->type_code) .";");

		$i=0;		
		while($row = mysql_fetch_array($query))
		{
			$titles[$i]= $cDB->UnEscTxt($row[0]);
			$i += 1;
		}
		
		if ($i == 0)
			$titles[0]= "";
		
		return $titles;
	}	
// CT no longer needed
	/*
	function DisplayMemberListings($member) {
		global $cDB;

		$query = $cDB->Query("SELECT title FROM ".DATABASE_LISTINGS." WHERE member_id=". $cDB->EscTxt($member->member_id) ." AND type=". $cDB->EscTxt($this->type_code) ." ORDER BY title;");
		
		$output = "";
		$current_cat = "";
		while($row = mysql_fetch_array($query)) {
			$output .= "<A HREF=listing_edit.php?title=" . urlencode($cDB->UnEscTxt($row[0])) ."&member_id=".$member->member_id ."&type=". $this->type ."&mode=" . $_REQUEST["mode"] ."><FONT SIZE=2>". $cDB->UnEscTxt($row[0]) ."</FONT></A><BR>";
		}

		return $output;
	}
	*/

}


?>
