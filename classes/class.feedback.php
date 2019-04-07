<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

require_once("class.trade.php");

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
			$stars ='<i class="fas fa-star"></i><i class="far fa-star-o"></i><i class="far fa-star-o"></i>';
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
		if(!empty($variables)) $this->build($variables);
	}
	


	function build ($variables) { 

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
			$this->build ($row);
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
	
	function DisplayFeedback () {
		return $this->RatingText() . "<BR>" . $this->feedback_date->StandardDate(). "<BR>". $this->Context() . "<BR>". $this->member_author->PrimaryName() ." (" . $this->member_author->member_id . ")" . "<BR>" . $this->category->description . "<BR>" . $this->comment;
	}
	
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
	
class cFeedbackGroup {
	//private $member_id;
	private $member_id;		// for convenience
	private $context;		// Buyer or Seller or Both
//	private $since_date;
	private $num_positive;
	private $num_negative;
	private $num_neutral;
	private $percentage_positive;
	private $percentage_negative;
	private $percentage_neutral;
	private $feedback;		// will be an array of cFeedback objects
	

/**
  
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
    public function getContext()
    {
        return $this->context;
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
     * @return mixed
     */
    public function getSinceDate()
    {
        return $this->since_date;
    }

    /**
     * @param mixed $since_date
     *
     * @return self
     */
    public function setSinceDate($since_date)
    {
        $this->since_date = $since_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumPositive()
    {
        return $this->num_positive;
    }

    /**
     * @param mixed $num_positive
     *
     * @return self
     */
    public function setNumPositive($num_positive)
    {
        $this->num_positive = $num_positive;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumNegative()
    {
        return $this->num_negative;
    }

    /**
     * @param mixed $num_negative
     *
     * @return self
     */
    public function setNumNegative($num_negative)
    {
        $this->num_negative = $num_negative;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumNeutral()
    {
        return $this->num_neutral;
    }

    /**
     * @param mixed $num_neutral
     *
     * @return self
     */
    public function setNumNeutral($num_neutral)
    {
        $this->num_neutral = $num_neutral;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercentagePositive()
    {
        return $this->percentage_positive;
    }

    /**
     * @param mixed $percentage_positive
     *
     * @return self
     */
    public function setPercentagePositive($percentage_positive)
    {
        $this->percentage_positive = $percentage_positive;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercentageNegative()
    {
        return $this->percentage_negative;
    }

    /**
     * @param mixed $percentage_negative
     *
     * @return self
     */
    public function setPercentageNegative($percentage_negative)
    {
        $this->percentage_negative = $percentage_negative;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercentageNeutral()
    {
        return $this->percentage_neutral;
    }

    /**
     * @param mixed $percentage_neutral
     *
     * @return self
     */
    public function setPercentageNeutral($percentage_neutral)
    {
        $this->percentage_neutral = $percentage_neutral;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * @param mixed $feedback
     *
     * @return self
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    
  

    function __construct($member_id, $context="about"){
		$this->setContext($context);
		$this->setMemberId($member_id);
		// ct load feedback from constructor - probably never built without loading
		$this->Load($member_id);
    }
    // load db feedback for a member_id
	function Load ($member_id) {
		global $cDB, $cErr, $site_settings;

		// CT choose whether feedback is for someone or left by someone
		$context_field = ($this->getContext() == "about") ? "member_id_about" : "member_id_author";
		//$cErr->Error($this->getContext());
		$query = $cDB->Query("SELECT f.feedback_id as feedback_id, 
			date_format(feedback_date, \"{$site_settings->getKey('SHORT_DATE')}\") as feedback_date, 
			f.member_id_author feedback_member_id_author, 
			f.member_id_about as feedback_member_id_about, 
			f.trade_id as trade_id, 
			f.rating as feedback_rating, 
			f.comment as feedback_comment,
			t.description as trade_description, 
			c.description as trade_category
			FROM ". DATABASE_FEEDBACK . " f 
			LEFT JOIN ".DATABASE_TRADES." t on f.trade_id=t.trade_id
			LEFT JOIN ".DATABASE_CATEGORIES." c on c.category_id=t.category
			WHERE {$context_field} LIKE \"{$member_id}\"
			ORDER BY f.feedback_date desc");
		// CT init
		$feedback_list = array();
		$num_positive = 0;
		$num_negative = 0;
		$num_neutral = 0;
		$i=0;
		while($row = $cDB->FetchArray($query))
		{
			$feedback = new cFeedback($row);
			//$cErr->Error(print_r($row, true));
			$feedback->setContext($context);
	
			if($feedback->getRating() == 3) $num_positive++;
			if($feedback->getRating() == 1) $num_negative++;
			if($feedback->getRating() == 2) $num_neutral++;

			$feedback_list[] = $feedback;
			//$cErr->Error(print_r($feedback, true));
			$i++;
		}

		$this->setNumPositive($num_positive);
		$this->setNumNegative($num_negative);
		$this->setNumNeutral($num_neutral);
		$this->setFeedback($feedback_list);

		
		//$cErr->Error($i);
		if(sizeof($this->getFeedback()) > 0) return true;
		return false;
		
	}
	
	function PercentPositive() {
		return number_format(($this->getNumPositive() / ($this->getNumPositive() + $this->getNumNegative() + $this->getNumNeutral())) * 100, 0); 
	}
	
	function TotalFeedback() {
		return $this->getNumPositive() + $this->getNumNegative() + $this->getNumNeutral();
	}
	
	function Display() {		
		$output = "<table class='tabulated'>
			<tr>
				<th>Buyer</th>
				<th>Seller</th>
				<th>Feedback</th>
				<th>Trade</th>
			</tr>";
	
		
		
		$i=0;
		foreach($this->getFeedback() as $feedback) {
			$rowclass = ($i % 2) ? "even" : "odd";	
			$member_author = $feedback->getMemberIdAuthor();
			if($this->getMemberId() != $feedback->getMemberIdAuthor()) {
				$member_author = "<a href='member_summary.php?member_id={$feedback->getMemberIdAuthor()}'>{$feedback->getMemberIdAuthor()}</a>";
			}
			$member_about = $feedback->getMemberIdAbout();
			if($this->getMemberId() != $feedback->getMemberIdAbout()) {
				$member_about = "<a href='member_summary.php?member_id={$feedback->getMemberIdAbout()}'>{$feedback->getMemberIdAbout()}</a>";
			}
			//feeback visual
			$stars=$feedback->showRatingAsStars();

			$trade_description = $feedback->getTrade()->getDescription();
			if($feedback->getRating() == NEGATIVE)
				$rowclass .= " negative";
			elseif ($feedback->getRating() == POSITIVE)
				$rowclass .= " positive";
			else
				$rowclass .= " neutral";;
				
				
			$output .= "<tr class='$rowclass'>
				<td>{$member_author}</td>
				<td>{$member_about}</td>
				<td>{$stars} {$feedback->getComment()}</td>
				<td>{$feedback->getTradeId()}{$feedback->getTradeId()}";
			/*
			if(isset($feedback->rebuttals))
				$output .= $feedback->rebuttals->DisplayRebuttalGroup($feedback->member_about->getMemberId()); // TODO: Shouldn't have to pass this value, should incorporate into cFeedbackRebuttal
			
			if($feedback->rating != POSITIVE) {

				if ($member_viewing == $feedback->member_about->getMemberId())
					$text="Reply";
				elseif ($member_viewing == $feedback->member_author->getMemberId())
					$text="Follow up";

				$output .= "<br /><a href='feedback_reply.php?feedback_id={$feedback->feedback_id}&mode=self&author={$member_viewing}&about={$feedback->member_author->getMemberId()}''>{$text}</a> "; 
			}
			*/
			$output .= "</td></tr>";
			$i++;
		}	
		return $output ."</table>";
	}

	
}
// //CT efficient stats - do everything in mysql
// class cFeedbackGroupCT extends cFeedbackGroup {
// 	var $num_total;

// 	function LoadFeedbackGroup ($member_id, $context=null, $since_date=LONG_LONG_AGO) {
// 		global $cDB, $cErr;
				
// 		$this->member_id = $member_id;
// 		$this->since_date = $since_date;
// 		$this->num_positive = 0;
// 		$this->num_negative = 0;
// 		$this->num_neutral = 0;
// 		$this->num_total = 0;
		
// 		//CT this is kinda what it should be		
// 		//$query = "SELECT (case WHEN rating='3' THEN 'positive' WHEN rating='2' THEN 'neutral' WHEN rating='1' THEN 'negative'  END) AS rating_text, count(*) as num FROM ".DATABASE_FEEDBACK;
// 		$query = "SELECT rating, count(*) as num FROM ".DATABASE_FEEDBACK;
		
// 		if($context == BUYER)
// 			$query .= ", ". DATABASE_TRADES ." WHERE member_id_to=member_id_about AND";
// 		elseif ($context == SELLER) 
// 			$query .= ", ". DATABASE_TRADES ." WHERE member_id_from=member_id_about AND";
// 		else
// 			$query .= " WHERE";
		
// 		$query .= " feedback_date >= '{$this->since_date}' AND member_id_about='{$this->member_id}' AND status='A' GROUP BY rating;";
// 		//echo $query;
		
// 		$query = $cDB->Query($query);
// 		if(mysqli_num_rows($query) < 1) return false;

// 		while($row = $cDB->FetchArray($query))
// 		{
// 			switch ($row['rating']) {
// 				case POSITIVE:
// 					$this->num_positive = $row['num'];
// 					break;
// 				case NEUTRAL:
// 					$this->num_neutral = $row['num'];
// 					break;
// 				case NEGATIVE:
// 				default:
// 					$this->num_negative = $row['num'];
// 					break;
// 			}
// 		}
// 		$this->num_total = $this->num_negative + $this->num_neutral + $this->num_positive;

// 		return true;
// 	}
// 	function PercentPositive() {
// 		return number_format($this->num_positive / $this->num_total * 100, 0); 
// 	}
// }

class cFeedbackRebuttal {
	var $rebuttal_id;
	var $rebuttal_date;
	var $feedback_id;
	var $member_author;
	var $comment;

	function __construct ($feedback_id=null, $member_id=null, $comment=null) {
		if($feedback_id) {
			$this->feedback_id = $feedback_id;
			$this->member_author = new cMember;
			$this->member_author->LoadMember($member_id);
			$this->comment = $comment;
		}
	}
	
	function SaveRebuttal () {
		global $cDB, $cErr;
		
		$insert = $cDB->Query("INSERT INTO ". DATABASE_REBUTTAL ."(rebuttal_date, member_id, feedback_id, comment) VALUES (now(), ". $cDB->EscTxt($this->member_author->member_id) .", ". $cDB->EscTxt($this->feedback_id) .", ". $cDB->EscTxt($this->comment) .");");

		if(mysqli_affected_rows() == 1) {
			$this->rebuttal_id = mysqli_insert_id();	
			$query = $cDB->Query("SELECT rebuttal_date from ". DATABASE_REBUTTAL ." WHERE rebuttal_id=". $cDB->EscTxt($this->rebuttal_id) .";");
			$row = $cDB->FetchArray($query);
			$this->rebuttal_date = $row[0];	
			return true;
		} else {
			return false;
		}	
	}
	
	function LoadRebuttal ($rebuttal_id) {
		global $cDB, $cErr;
		
		$query = $cDB->Query("SELECT rebuttal_date, feedback_id, member_id, comment FROM ".DATABASE_REBUTTAL." WHERE rebuttal_id=". $cDB->EscTxt($rebuttal_id) .";");
		
		if($row = $cDB->FetchArray($query)) {		
			$this->rebuttal_id = $rebuttal_id;		
			$this->rebuttal_date = new cDateTime($row[0]);
			$this->feedback_id = $row[1];
			$this->member_author = new cMember; 
			$this->member_author->LoadMember($row[2]);
			$this->comment = $cDB->UnEscTxt($row[3]);

			return true;
		} else {
			$cErr->Error("There was an error accessing the rebuttal table.");
			//include("redirect.php");
		}		
	}
}	

class cFeedbackRebuttalGroup {
	var $rebuttals;		// will be an array of cFeedbackRebuttal objects
	var $feedback_id;
	
	function LoadRebuttalGroup($feedback_id) {
		global $cDB, $cErr;
		
		$this->feedback_id = $feedback_id;
		$query = $cDB->Query("SELECT rebuttal_id FROM ".DATABASE_REBUTTAL." WHERE feedback_id=". $cDB->EscTxt($feedback_id) ." ORDER by rebuttal_date;");		
	
		$i=0;
		while($row = $cDB->FetchArray($query))
		{
			$this->rebuttals[$i] = new cFeedbackRebuttal;			
			$this->rebuttals[$i]->LoadRebuttal($row[0]);
			$i += 1;
		}
		
		if($i == 0)
			return false;
		else
			return true;
	}
	
	function DisplayRebuttalGroup($member_about) {
		$output = "";
		foreach($this->rebuttals as $rebuttal) {
			if($member_about == $rebuttal->member_author->member_id)
				$output .= "<BR><B>Reply: </B>";
			else
				$output .= "<BR><B>Follow Up: </B>";
				
			$output .= $rebuttal->comment;
		}		
		return $output;
	}




    
}
	
?>
