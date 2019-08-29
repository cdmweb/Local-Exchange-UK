<?php
class cTradeSummary{
	//CT rebuilt
	private $member_id;
	private $trade_total_count;
	private $trade_total_amount;
	private $trade_date_last; //short date

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
    public function getMemberId()
    {
        return $this->member_id;
    }
    /**
     * @param mixed $total_trades
     *
     * @return self
     */
    public function setTradeTotalCount($trade_total_count)
    {
        $this->trade_total_count = $trade_total_count;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTradeTotalCount()
    {
        return $this->trade_total_count;
    }

    /**
     * @param mixed $total_trades
     *
     * @return self
     */
    public function setTradeTotalAmount($trade_total_amount)
    {
        $this->trade_total_amount = $trade_total_amount;

        return $this;
    }
        /**
     * @return mixed
     */
    public function getTradeTotalAmount()
    {
        return $this->trade_total_amount;
    }


    /**
     * @param mixed $most_recent
     *
     * @return self
     */
    public function setTradeLastDate($trade_last_date)
    {
        $this->trade_last_date = $trade_last_date;

        return $this;
    }


        /**
     * @return mixed
     */
    public function getTradeLastDate()
    {
        return $this->trade_last_date;
    }

	//
	function  __construct ($values=null) {
		//global $cDB, $site_settings;
		//$this->setMemberId($member_id);
		//$this->Load($member_id);
	}

	public function Load($condition){
		global $cDB, $site_settings, $cQueries;
       // $this->setMemberId($member_id);

		$query = $cDB->Query($cQueries->getMySqlTradeSummary($condition));
		while($row = $cDB->FetchArray($query)) {
			$this->Build($row);
			return true;
		}
		return false;
	}
	public function Build($variables){
            $this->setTradeTotalCount($variables['trade_total_count']);
			$this->setTradeTotalAmount($variables['trade_total_amount']);
			$this->setTradeLastDate($variables['trade_last_date']);
	}
    function Display () {
        //summary element for use on summary page
        return (empty($this->getTradeLastDate())) ? "No exchanges yet" : "<a href=\"trade_history.php?member_id={$this->getMemberId()}\">{$this->getTradeTotalCount()} exchanges total</a> for a sum of {$this->getTradeTotalAmount()} ". strtolower(UNITS) . ", last traded on ". $this->getTradeLastDate();
    }

}
?>