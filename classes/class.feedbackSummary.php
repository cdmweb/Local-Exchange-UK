<?php

//CT slimline version for summary page
class cFeedbackSummary {

	private $total;
	private $percent_positive;
	private $num_negative;
	private $num_neutral;
	private $num_positive;

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed $total
     *
     * @return self
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercentPositive()
    {
        return $this->percent_positive;
    }

    /**
     * @param mixed $percentage_positive
     *
     * @return self
     */
    public function setPercentPositive($percent_positive)
    {
        $this->percent_positive = $percent_positive;

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

   
    function __construct($values=null){
    	if($values != null){
    		$this->Build($values);
    	}
    }
    // load db feedback for a member_id
	function Load ($member_id) {
		global $cDB, $cErr, $site_settings, $cQueries;
		$query = $cDB->Query("SELECT 
            {$cQueries->mysql_feedback_counts}
			FROM ". DATABASE_FEEDBACK . " f WHERE member_id_about = {$member_id}");
		while($row = $cDB->FetchArray($query)) {
			$this->Build($row);
			break;
		}
		
	}

	
	function Build ($values) {
		$this->setNumNegative($values['feedback_negative']);
		$this->setNumNeutral($values['feedback_neutral']);
		$this->setNumPositive($values['feedback_positive']);
		$this->setTotal($values['feedback_positive']);
        //calculate rating...should this be average rating instead?
        if($this->getNumPositive() > 0 && $this->getTotal() > 0){
            $this->setPercentPositive(number_format($this->getNumPositive() /  $this->getTotal() * 100, 0));
        } else {
            $this->setPercentPositive(0);
        }
		
	}

	function Display () {
        //summary element for use on summary page
        return (empty($this->getTotal())) ? "No feedback yet" : "<a href='feedback_all.php?mode=other&member_id={$member_id}'>{$this->getPercentPositive()}% positive</a> ({$this->getTotal()} total, {$this->getNumNegative()} negative &amp; {$this->getNumNeutral()} neutral)";
	}
	
}

?>