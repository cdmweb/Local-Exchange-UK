<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}


class cListing extends cBasic
{
    
	// added via default __get at build
    private $member_id; 
    private $listing_id; 
    private $title;
	private $description;
    private $category_id; // category code
    private $category_name; // category name
	private $rate;
	private $status;
	private $posting_date; // the date a listing was created or last modified
	private $expire_date;
	private $reactivate_date;
    private $type; 
	private $member_display_name; 
    //toggled on type...
    //private $type_description; 
    // set as object
    private $member; // object when needed
    

    //retuns lie a get, dependent on type field - not stored
	function getTypeDescription() {
        //CT language property...
        $type = $this->getType();
        if($type == OFFER_LISTING_CODE) $type_description = OFFER_LISTING;
        elseif($type == WANT_LISTING_CODE) $type_description = WANT_LISTING;
		return $type_description;			
	}
    function Build($variables){
        parent::Build($variables);
        //print_r($variables);
        //$this->__set('type_description', $this->TypeDesc($this->getType()));
        //lazy load of vars        
        $member = new cMemberSummary;
        if(!empty($values['member_id'])) $member->Build($variables);
        $this->__set('member', $member);
    }

	// lookup on listing, not title as it was...because people want to change titles
	function Load($condition, $order_by="listing_id ASC") {
        global $cQueries;
        $query = $cQueries->getMySqlListing($condition, $order_by);
        //var_dump($query);
        return $this->LoadAndBuild($query);
	}

	/* CT remove? */
    /*
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
    */


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
    public function getCategoryId()
    {
        return $this->category_id;
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
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param mixed $category_id
     *
     * @return self
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;

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

        return $this;
    }
}

?>
