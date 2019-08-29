<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}


class cListing
{
	private $title;
	private $description;
	//private $category_id; // category code
	private $rate;
	private $status;
	private $posting_date; // the date a listing was created or last modified
	private $expire_date;
	private $reactivate_date;
	private $type; 
	private $type_code; 
	//CT new - titles can't be unique, we are humans. use id for lookup
	private $listing_id; 

	private $category; // object category
	private $member; // object when needed
	private $member_id; 
	private $member_display_name; 
    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return self
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     *
     * @return self
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     *
     * @return self
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     *
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostingDate()
    {
        return $this->posting_date;
    }

    /**
     * @param mixed $posting_date
     *
     * @return self
     */
    public function setPostingDate($posting_date)
    {
        $this->posting_date = $posting_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getExpireDate()
    {
        return $this->expire_date;
    }

    /**
     * @param mixed $expire_date
     *
     * @return self
     */
    public function setExpireDate($expire_date)
    {
        $this->expire_date = $expire_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReactivateDate()
    {
        return $this->reactivate_date;
    }

    /**
     * @param mixed $reactivate_date
     *
     * @return self
     */
    public function setReactivateDate($reactivate_date)
    {
        $this->reactivate_date = $reactivate_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
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
     * @return mixed
     */
    public function getTypeCode()
    {
        return $this->type_code;
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
    public function getListingId()
    {
        return $this->listing_id;
    }

    /**
     * @param mixed $listing_id
     *
     * @return self
     */
    public function setListingId($listing_id)
    {
        $this->listing_id = $listing_id;

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
        //print_r($member);
        return $this;
    }


    /**
     * @return mixed
     */
    public function getMember()
    {
        return $this->member;
    }

    /**
     * @param mixed $member
     *
     * @return self
     */
    public function setMember($member)
    {
        $this->member = $member;
        //print_r($member);
        return $this;
    }
    
    /**
     * @return mixed
     */
    public function getMemberDisplayName()
    {
        return $this->member_display_name;
    }

    /**
     * @param mixed $member_display_name
     *
     * @return self
     */
    public function setMemberDisplayName($member_display_name)
    {
        $this->member_display_name = $member_display_name;
        //print_r($member);
        return $this;
    }

	function __construct($values=null) {
		if(!empty($values)) {
			$this->Build($values);
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

	// lookup on listing, not title as it was...because people want to change titles
	function Load($condition, $order_by="listing_id ASC")
	{
		global $cDB, $cErr, $cQueries;

	
        //CT ha only one should be returned
		$query = $cDB->Query($cQueries->getMySqlListing($condition, $order_by));
		// CT: todo - consolidate query with 
	
		if($values = $cDB->FetchArray($query)) {
            $this->Build($values);
            $this->DeactivateReactivate();
            return false;
        }
		else  {
            $cErr->Error("There was an error accessing the listing  {$listing_id}");	
            return false;
		}
		
	}
	function Build($values)
	{
		global $cDB, $cErr;
		
		// select all offer data and populate the variables
		//$query = $cDB->Query("SELECT description, category_code, member_id, rate, status, posting_date, expire_date, reactivate_date FROM ".DATABASE_LISTINGS." WHERE title=".$cDB->EscTxt($title)." AND member_id=" . $cDB->EscTxt($member_id) . " AND type=". $cDB->EscTxt($type_code) .";");
		
		if(!empty($values))
		{		
			//TODO: make propeer getters and setters

			if(!empty($values['title'])) $this->setTitle($values['title']);
			if(!empty($values['description'])) $this->setDescription($values['description']);
			if(!empty($values['rate'])) $this->setRate($cDB->UnEscTxt($values['rate']));
			if(!empty($values['status'])) $this->setStatus($values['status']);
			if(!empty($values['posting_date'])) $this->setPostingDate($values['posting_date']);
			if(!empty($values['expire_date'])) $this->setExpireDate($values['expire_date']);
			if(!empty($values['reactivate_date'])) $this->setReactivateDate($values['reactivate_date']);
			if(!empty($values['type'])) $this->setType($this->TypeDesc($values['type']));
		
			//if(!empty($values['category_id'])) $this->category_id=$values['category_id'];
			if(!empty($values['listing_id'])) $this->listing_id=$values['listing_id'];
			/*	
			$this->category = new cCategory();
			$this->category->LoadCategory($values['category_id']);*/
            $member = new cMemberSummary;
			if(!empty($values['member_id'])) {
                $this->setMemberId($values['member_id']);
                $member->Build($values);
                
            }
            $this->setMember($member);
            //name change - display_name > member_display_name
			if(!empty($values['display_name'])) $this->setMemberDisplayName=$values['display_name'];
            // for display of address summary in ads

            if(!empty($values['category_id'])) $category = new cCategory($values);

		} else{
			$category = new cCategory();
		}
		$this->setCategory($category);
				
		
		
		
		//$this->DeactivateReactivate();
	}
	
	function DeactivateReactivate() {
		if($this->getReactivateDate()) {
			$reactivate_date = new cDateTime($this->getReactivateDate());
			if ($this->status == INACTIVE and $reactivate_date->Timestamp() <= strtotime("now")) {
				$this->status = ACTIVE;
				$this->reactivate_date = null;
				$this->SaveListing();
			}
		}
		if($this->expire_date) {
			$expire_date = new cDateTime($this->getExpireDate());
			if ($this->status <> EXPIRED and $expire_date->Timestamp() <= strtotime("now")) {
				$this->status = EXPIRED;
				$this->SaveListing();
			}
		}
	}
}

?>
