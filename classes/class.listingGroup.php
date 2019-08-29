<?php 

//default class for displaying and filtering active listings
class cListingGroup extends cBasic
	{
	//todo: getters and setters
	private $listings;  // array of objects of type cListing
	private $num_listings;  // number of active offers
	//filters 
	private $type;
	private $tileframe;
	private $category_id;
	private $member_id;

	// function __construct($values=null) {
	// 	if(!empty($values)) $this->Build($values);
	// }

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
				
		// // todo - keywords
		// $condition = "p1.primary_member = 'Y' and 
		// m.status = 'A' and l.member_id LIKE \"{$cDB->EscTxt($member_id)}\" AND l.category_code=c.category_id AND c.category_id LIKE \"{$category_id}\" AND l.type=\"{$type_code}\"";

		// todo - keywords
		$condition = "p1.primary_member = 'Y' AND m.status = 'A' AND l.member_id LIKE \"{$member_id}\" AND l.category_code=c.category_id AND c.category_id LIKE \"{$category_id}\" AND l.type=\"{$type_code}\"";

		// show listings that are outside of expiry window
		$condition .= " AND (l.expire_date IS NULL OR l.expire_date > CURDATE() OR (l.expire_date < CURDATE() AND l.reactivate_date < CURDATE())) and l.status != 'I'";

		if(!empty($timeframe)){ 
			$condition .= " AND l.posting_date > CURDATE() - INTERVAL {$timeframe} DAY";
		} 
		return $condition;

	}

		// todo - keywords

	function Load($condition)
	{
		global $cDB, $cEr, $cQueries;

		// st removed the status option - will use in a extends class. this one only shows ones out of expiry window.
		//$status = "%";

		$order_by = "c.description ASC, l.posting_date DESC, l.member_id ASC";
		
		$query = $cDB->Query($cQueries->getMySqlListing($condition, $order_by));


		// instantiate new cOffer objects and load them
		$i = 0;		
		$vars = array();
		while ($row = $cDB->FetchArray($query)){
			$vars[] = $row;			
		}

		if(sizeof($vars) == 0) {
			return false;
		}
		$this->Build($vars);
		return true;
	}
	function Build($vars){
		$listings = array();
		foreach($vars as $value){
			//var_dump($value);
			$listing = new cListing($value);
			$listings[] = $listing;	
			//var_dump($listing->getListingId());
		}
		if(sizeof($listings)==0) return false;
		$this->setListings($listings);
		return true;
	}


	//CT: made work on id number
	function ListingLink($listing_id, $title) {
		global $p;
		$link = HTTP_BASE."/listing_detail.php?listing_id={$listing_id}";
		//return $p->Link($text, $link);
		return $p->Link("{$title}", $link);
	}


	//CT 
	function PrepareSelectorCategory($selected_id=null) {

		$categories = new cCategoryGroup;
		$categories->Load(1);
		
		global $p;
		//$vars[]=array(value, description);
		// prepare vars to be in the right format for the generic builder
		$vars=array();
		foreach($categories->getCategories() as $category){
			//add element
			$vars[$category->getCategoryId()] = $category->getCategoryName();
		}
		//wrap in select
		$string = $p->PrepareFormSelector('category_id', $vars, 'All categories', $selected_id);
		return $string;		
	}
	function PrepareSelectorTimeframe($selected_id=null){
		global $p;
		//$vars[]=array(value, description);
		// prepare vars to be in the right format for the generic builder
		$vars=array();
		$vars['3'] = 'Updated in last 3 days';
		$vars['7'] = 'Updated in last week';
		$vars['14'] = 'Updated in last two weeks';
		$vars['30'] = 'Updated in last month';
		$vars['90'] = 'Updated in last 3 months';

		//wrap in select
		$output = $p->PrepareFormSelector('timeframe', $vars, 'All time', $selected_id);
		return $output;
	}

	function PrepareInputKeywords($keywords=null){
		global $p;
		//$vars[]=array(value, description);
		// prepare vars to be in the right format for the generic builder

		$output = $p->WrapFormElement('text', 'keywords', 'Keywords', $keywords, '');
		return $output;
	}

	//ct todo: default to the current setting of form
	function DisplayFilterForm($type_code, $category_id, $member_id, $timeframe, $keywords){
		//global $c
		// standardize the nulls
		$category_id = (!empty($category_id)) ? $category_id : "";
		$member_id = (!empty($member_id)) ? $member_id : "";
		$timeframe = (!empty($timeframe)) ? $timeframe : "";
		$keywords = (!empty($keywords)) ? $keywords : "";

		//print($type_code .  $category_id .  $member_id .  $timeframe .$keywords);

		
		$output = "
			<form class=\"layout1 summary\" action=\"listings.php\" method=\"get\" name=\"form1\" id=\"form1\">
				<input type=\"hidden\" name=\"type\" id=\"type\" value=\"{$type}\" />
				<input type=\"hidden\" name=\"member_id\" id=\"member_id\" value=\"{$member_id}\" />
				<p class=\"l_text\">
					<label>
						<span>Category:</span>
						{$this->PrepareSelectorCategory($category_id)}
					</label>
				</p>
				<p class=\"l_text\">
					<label>
						<span>Timeframe:</span>
						{$this->PrepareSelectorTimeframe($timeframe)}
					</label>
				</p>
				<p class=\"l_text\">
					{$this->PrepareInputKeywords($keywords)}
				</p>
				<input name=\"button\" value=\"Search\" type=\"submit\" />
			</form>


			";

		return $output;
	}


	function Display($show_ids=false)
	{
		
		global $cUser,$cDB, $p;
	
		$output = "";
		$current_cat = "";
		$i = 0;
		
		if(!empty($this->getListings())) {
			foreach($this->getListings() as $listing) {
			
				if($current_cat != $listing->getCategoryName()) {
					if($i>0) $output .= "</ul>"; // end the last unordered list
					$current_cat = $listing->getCategoryName();
						
					$output .= "<h3>{$current_cat}</h3>
								<ul class='listing'>";
					
				}
				// CT construct details
				$details = "";
				$memInfo = "";
				$listing_id = $listing->getListingId();
				$title = $listing->getTitle();
				if ($cUser->IsLoggedOn()){
					//$details .= "<strong>" . $this->ListingLink($listing->__get('listing_id'), $listing->__get('title')) . "</strong>";
					$details .= "<strong>{$this->ListingLink($listing_id, $title)}</strong> ";
					//safe postdoce and address
					
					//CT show member and member link - hide on member page
					if ($show_ids == true) {
						$member = $listing->getMember();
						$location="location";
						//$location="sd";
						$memInfo .= " (". $member->getDisplayName() . " " . $member->MemberLink() .  ") " . $location . ".";
					}
				} else{
					$details .= $listing->getTitle();
				}
				$details .= $listing->getDescription(); 

				
				
				$output .= "<li>{$details} {$memInfo}";


				if (SHOW_RATE_ON_LISTINGS==true && $listing->getRate()) {
			
					$output .= " <span class='rate'>{$listing->getRate()} " .UNITS. ".</span>";
				}
				if (SHOW_DATE_ON_LISTINGS==true) {
			
					$output .= " <span class='date'><!-- List id: {$listing->getListingId()}. Posted: -->{$listing->getPostingDate()}</span>";
				}

				$output .= "</li>";
			
						
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
		    /**
     * @param mixed $title
     *
     * @return self
     */

/**
     * @return mixed
     */
    public function getNumListings()
    {
        return $this->num_listings;
    }

    /**
     * @param mixed $num_listings
     *
     * @return self
     */
    public function setNumListings($num_listings)
    {
        $this->num_listings = $num_listings;

        return $this;
    }

    /**
     * @param mixed $type
     *
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param mixed $type_code
     *
     * @return self
     */
    public function setTypeCode($type_code)
    {
        $this->type_code = $type_code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTileframe()
    {
        return $this->tileframe;
    }

    /**
     * @param mixed $tileframe
     *
     * @return self
     */
    public function setTileframe($tileframe)
    {
        $this->tileframe = $tileframe;

        return $this;
    }

    /**
     * @param mixed $category_id
     *
     * @return self
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * @param mixed $member_id
     *
     * @return self
     */
    public function setMemberId($member_id)
    {
        $this->member_id = $member_id;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getListings()
    {
        return $this->listings;
    }

    /**
     * @param mixed $listing
     *
     * @return self
     */
    public function setListings($listings)
    {
        $this->listings = $listings;

        return $this;
    }

    

}



//CT neeeded?
// class cTitleListGroup extends cListingGroup  
// // ct this is messy
// {									
// 	function ListingLink($listing_id, $title) {
// 		global $p;
// 		$link = HTTP_BASE."/listing_edit.php?listing_id={$listing_id}";
// 		//return $p->Link($text, $link);
// 		return $p->Link("$title", $link);
// 	}
// 	function TypeCode($type) {
// 		if(strcasecmp($type,OFFER_LISTING) == 0){
// 			return OFFER_LISTING_CODE;
// 		}else {
// 			return WANT_LISTING_CODE;	
// 		}		
// 	}

	
// 	function Display($show_ids=true, $active_only=false) {
// 		global $cUser,$cDB, $p;
// 		//$titles=MakeTitleArray($member_id);
// 		//$query = $cDB->Query("SELECT title FROM ".DATABASE_LISTINGS." WHERE member_id=". $cDB->EscTxt($member->member_id) ." AND type=". $cDB->EscTxt($this->type_code) ." ORDER BY title;");
// 		$typeCode=$this->TypeCode($type);
// 		print($typeCode);
// 		$this->Load(null, null, $member_id, null, true, null, $typeCode);
// 		//print($member_id);

	
// 		$output = "";
// 		$current_cat = "";
// 		$i = 0;
// 		//print(sizeof($this->listing));
// 		if(isset($this->listing)) {
// 			$output .= "<ul class='listing'>";
// 			foreach($this->listing as $listing) {
// 				$output .= "<li>" . $this->ListingLinkEdit($listing->type, $listing->title, $listing->member_id) . "</li>";
// 				$i++;	
// 			}
// 			$output .= "</ul>"; // end the last unordered list
// 			$output .= $p->Wrap($i . " listings found.", "p");
	
// 		} 
// 		return $output;
// 	}
	




// }


?>