<?php
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
				$member_author = "<a href='member_detail.php?member_id={$feedback->getMemberIdAuthor()}'>{$feedback->getMemberIdAuthor()}</a>";
			}
			$member_about = $feedback->getMemberIdAbout();
			if($this->getMemberId() != $feedback->getMemberIdAbout()) {
				$member_about = "<a href='member_detail.php?member_id={$feedback->getMemberIdAbout()}'>{$feedback->getMemberIdAbout()}</a>";
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
?>