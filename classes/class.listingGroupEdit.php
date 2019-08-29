<?php 

//for group edit listing - management
class cListingGroupEdit extends cListingGroup
	{

	function makeFilterCondition($member_id=null, $category_id=null, $since=null, $timeframe=null, $type_code=null){
		global $cDB;
		if($category_id == null){
			$category_id = "%";
		}

		if(empty($member_id)){
			$member_id = "%";
		}
			
		if(empty($timeframe)){ 
			$timeframe = null;
			//$since = ;
		} 
		// default to offers
		if(empty($type_code)) {
			$type_code = "O";
		}
				
		

		// todo - keywords
		$condition = "p1.primary_member = 'Y' AND m.status = 'A' AND l.member_id LIKE \"{$member_id}\" AND l.category_code=c.category_id AND c.category_id LIKE \"{$category_id}\" AND l.type=\"{$type_code}\"";

		// show listings that are outside of expiry window
		//$condition .= " AND (l.expire_date IS NULL OR l.expire_date > CURDATE() OR (l.expire_date < CURDATE() AND l.reactivate_date < CURDATE()))";

		// if(!empty($timeframe)){ 
		// 	$condition .= " AND l.posting_date > CURDATE() - INTERVAL {$timeframe} DAY";
		// } 
		return $condition;

	}
	//though this is per listing, its only for context of group
	function PrepareCheckbox($listing_id, $selected=false){
		$selectedText = ($selected) ? "selected" : "";
		return "<input type=\"checkbox\" name=\"select_id[]\" value=\"{$listing_id}\" selected=\"{$selectedText}\" />";
	}
	function PrepareActionDropdown(){
        global $p;
        //same as listingEdit status - but with added "delete"
        // relabelled "expire" and "active" to make more robust and uderstandable - "hide" and "show"
        $vars = array("D" => "Delete", "E" => "Hide", "A" => "Show");
        $select_name = "action";
        $output = $p->PrepareFormSelector("action", $vars, "-- Select action --", null);
        return $output;
    }
	function InactivateAll($reactivate_date) {
		global $cErr;
		
		if (!isset($this->listing))
			return true;
		
		foreach($this->getListing() as $listing)	{
			$current_reactivate = new cDateTime($listing->getReactivateDate(), false);
			if(($listing->getReactivateDate() == null or $current_reactivate->Timestamp() < $reactivate_date->Timestamp()) and $listing->status != EXPIRED) {
				$listing->getReactivateDate($reactivate_date->MySQLDate());
				$listing->getStatus(INACTIVE);
				$success = $listing->SaveListing();
				
				if(!$success)
					$cErr->Error("Could not inactivate listing: '".$listing->getTitle() ."'");
			}
		}
		return true;
	}

	function ExpireAll($expire_date) {
		global $cErr;
		
		if (empty($this->getListing()))
			return true;
		
		foreach($this->getListing() as $listing)	{
			$listing->getExpireDate($expire_date->MySQLDate());
			$success = $listing->SaveListing(false);
				
			if(!$success)
				$cErr->Error("Could not expire listing: '".$listing->getTitle()."'");
		}
		return true;
	}	
		// todo - keywords

	function Display($show_ids=true)
	{
		
		global $cUser,$cDB, $p;
	
		$output = "";
		$current_cat = "";
		$i = 0;
		//print_r($this->getListing());
		if(!empty($this->getListing())) {
			foreach($this->getListing() as $listing) {
			
				
				// CT construct details
				$details = "";
				$memInfo = "";
				$details .=  $this->ListingLink($listing->getListingId(), $listing->getTitle());
				
				$output .= "<li>{$details} <a href=\"edit\"</li>";
			
						
				// Rate
				
			
				
				$i++;	
			}
			$output .= "</ul><br />"; // end the last unordered list
			$output .= $p->Wrap($i . " items found.", "p");
	
		} 
		if($i==0)
			$output = $p->Wrap("No items found.", "p");
								
		return $output;		
	}

}



?>