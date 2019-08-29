<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}


class cFeedback {	
	 private $feedback_id;
	 private $feedback_date;
	 private $status;
	 private $member_id_author;  // id
	 private $member_id_about;	// id
	 private $trade_id;
	 private $trade_description;
	 private $rating;
	 private $comment;
//	 private $context;			// indicates whether the author of this feedback was the BUYER or SELLER
	//private  $rebuttals;		// will be an object of class cRebuttalGroup, if rebuttals exist
	//private $category;			// category of the associated trade
	    /**
     * @param mixed $feedback_id
     *
     * @return self
     */
    public function setFeedbackId($feedback_id)
    {
        $this->feedback_id = $feedback_id;

        return $this;
    }

    /**
     * @param mixed $feedback_date
     *
     * @return self
     */
    public function setFeedbackDate($feedback_date)
    {
        $this->feedback_date = $feedback_date;

        return $this;
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
     * @param mixed $member_id_author
     *
     * @return self
     */
    public function setMemberIdAuthor($member_id_author)
    {
        $this->member_id_author = $member_id_author;

        return $this;
    }

    /**
     * @param mixed $member_id_about
     *
     * @return self
     */
    public function setMemberIdAbout($member_id_about)
    {
        $this->member_id_about = $member_id_about;

        return $this;
    }

    /**
     * @param mixed $trade_description
     *
     * @return self
     */
    public function setTradeDescription($trade_description)
    {
        $this->trade_description = $trade_description;

        return $this;
    } 
     /**
     * @param mixed $trade_category
     *
     * @return self
     */
    public function setTradeCategory($trade_category)
    {
        $this->trade_description = $trade_category;

        return $this;
    }    
    /**
     * @param mixed $trade_id
     *
     * @return self
     */
    public function setTradeId($trade_id)
    {
        $this->trade_id = $trade_id;

        return $this;
    }

    /**
     * @param mixed $rating
     *
     * @return self
     */
    public function setRating($rating)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * @param mixed $comment
     *
     * @return self
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @param mixed $context
     *
     * @return self
     */
    public function setContext($context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param mixed $rebuttals
     *
     * @return self
     */
    public function setRebuttals($rebuttals)
    {
        $this->rebuttals = $rebuttals;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getFeedbackId()
    {
        return $this->feedback_id;
    }

    /**
     * @return mixed
     */
    public function getFeedbackDate()
    {
        return $this->feedback_date;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getMemberIdAuthor()
    {
        return $this->member_id_author;
    }

    /**
     * @return mixed
     */
    public function getMemberIdAbout()
    {
        return $this->member_id_about;
    }

    /**
     * @return mixed
     */
    public function getTradeId()
    {
        return $this->trade_id;
    }

    /**
     * @return mixed
     */
    public function getTradeDescription()
    {
        return $this->trade_description;
    }

    /**
     * @return mixed
     */
    public function getTradeCategory()
    {
        return $this->trade_category;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @return mixed
     */
    public function getRebuttals()
    {
        return $this->rebuttals;
    }
	// not stored anywhere - purely for display
	public function showRatingAsStars(){
		switch ($this->getRating()) {
			case 1:
				$stars ='<i class="fas fa-star"></i><i class="far fa-star-o"></i><i class="far fa-star-o"></i><span>3 stars</span>';
				break;
			case 2:
				$stars ='<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="far fa-star"></i>';
				break;
			default:
				$stars ='<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>';
				break;
		}
		return "<span class=\"stars\">{$stars}</span>";
	}


	function __construct ($variables=null) { 
		if(!empty($variables)) $this->Build($variables);
	}
	


	function Build ($variables) { 

		// rather than passing them
		$this->setFeedbackId($variables['feedback_id']);
		$this->setFeedbackDate($variables['feedback_date']);
		$this->setStatus($variables['feedback_status']);
		$this->setMemberIdAuthor($variables['feedback_member_id_author']);
		$this->setMemberIdAbout($variables['feedback_member_id_about']);
		$this->setTradeId($variables['trade_id']);
		$this->setTradeDescription($variables['trade_description']);
		$this->setTradeCategory($variables['trade_category']);
		$this->setRating($variables['feedback_rating']);
		$this->setComment($variables['feedback_comment']);
		$this->setContext($variables['feedback_context']);
	}
/*	function VerifyTradeMembers() { // Prevent accidental or malicious entry of feedback in which
		global $cErr;					  // seller and buyer do not match up with the recorded trade.
		
		if ($this->member_about->member_id == $this->trade->member_from->member_id) {
			if ($this->member_author->member_id == $this->trade->member_to->member_id)
				return true;
		} elseif ($this->member_about->member_id == $this->trade->member_to->member_id) {
			if ($this->member_author->member_id == $this->trade->member_from->member_id)
				return true;
		} 
		
		$cErr->Error("Members do not match the trade selected.");
		include("redirect.php");	
	} */
	
	function SaveFeedback () {
		global $cDB, $cErr;
		
//		$this->VerifyTradeMembers();
		if($this->FindTradeFeedback($this->trade_id, $this->member_author->member_id)) {
			$cErr->Error("Cannot create duplicate feedback.");
			return false;
		}
		
		$insert = $cDB->Query("INSERT INTO ". DATABASE_FEEDBACK ."(feedback_date, status, member_id_author, member_id_about, trade_id, rating, comment) VALUES (now(), ". $cDB->EscTxt($this->status) .", ". $cDB->EscTxt($this->member_author->member_id) .", ". $cDB->EscTxt($this->member_about->member_id) .", ". $cDB->EscTxt($this->trade_id) .", ". $cDB->EscTxt($this->rating) .", ". $cDB->EscTxt($this->comment) .");");

		if(mysqli_affected_rows() == 1) {
			$this->feedback_id = mysqli_insert_id();	
			$query = $cDB->Query("SELECT feedback_date from ". DATABASE_FEEDBACK ." WHERE feedback_id=". $this->feedback_id .";");
			$row = $cDB->FetchArray($query);
			$this->feedback_date = $row[0];	
			return true;
		} else {
			return false;
		}	
	}
	
	function Load ($feedback_id) {
		global $cDB, $cErr;
		
		$query = $cDB->Query("SELECT feedback_date, 
			f.status as feedback_status, 
			f.member_id_author as feedback_member_id_author, 
			f.member_id_about as feedback_member_id_about, 
			trade_id, 
			f.rating as feedback_rating, 
			f.comment as feedback_comment
			FROM ".DATABASE_FEEDBACK." f WHERE  
			feedback_id='{$feedback_id}' limit 1;");
		
		while ($row = $cDB->FetchArray($query)) {		
			$this->Build ($row);
			//$rebuttal_group = new cFeedbackRebuttalGroup();
			//if($rebuttal_group->LoadRebuttalGroup($feedback_id))
			//	$this->rebuttals = $rebuttal_group;
			return true;
		} 
		// didnt enter loop so didn't return
		$cErr->Error("There was an error getting feedback.  ");
		//include("redirect.php");
		return false;
		
	}
	/*
	// CT hiding for now - for deletion - possible with load feedback
	function FindTradeFeedback ($trade_id, $member_id) {
		global $cDB;
		
		$query = $cDB->Query("SELECT feedback_id FROM ". DATABASE_FEEDBACK ." WHERE trade_id=". $cDB->EscTxt($trade_id) ." AND member_id_author=". $cDB->EscTxt($member_id) .";");
		
		if($row = $cDB->FetchArray($query))
			return $row[0];
		else
			return false;
	} */
	/*
	function DisplayFeedback () {
		return $this->RatingText() . "<BR>" . $this->feedback_date->StandardDate(). "<BR>". $this->Context() . "<BR>". $this->member_author->PrimaryName() ." (" . $this->member_author->member_id . ")" . "<BR>" . $this->category->description . "<BR>" . $this->comment;
	}
	*/
	function RatingText () {
		if ($this->rating == POSITIVE)
			return "Positive";
		elseif ($this->rating == NEGATIVE)
			return "Negative";
		else
			return "Neutral";
	}	
	
	function Context () {
		if ($this->context == SELLER)
			return "Seller";
		else
			return "Buyer";
	}
}
	



	
?>
