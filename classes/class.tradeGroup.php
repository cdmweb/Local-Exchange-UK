<?php
class cTradeGroup {
	private $trades;   	// an array of cTrade objects
	private $member_id;
	private $from_date;
	private $to_date;
	private $filter_type;
	//CT getters and setters
   public function getTrades()
    {
        return $this->trades;
    }

    /**
     * @param mixed $trades
     *
     * @return self
     */
    public function setTrades($trades)
    {
        $this->trades = $trades;

        return $this;
    }
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
    //
        public function getFromDate()
    {
        return $this->from_date;
    }

    /**
     * @param mixed $from_date
     *
     * @return self
     */
    public function setFromDate($from_date)
    {
        $this->from_date = $from_date;

        return $this;
    }
    //
        public function getToDate()
    {
        return $this->to_date;
    }

    /**
     * @param mixed $to_date
     *
     * @return self
     */
    public function setToDate($to_date)
    {
        $this->to_date = $to_date;

        return $this;
    }
    //
        public function getFilterype()
    {
        return $this->filter_type;
    }

    /**
     * @param mixed $filter_type
     *
     * @return self
     */
    public function setFilterType($filter_type)
    {
        $this->filter_type = $filter_type;

        return $this;
    }


	function __construct($values=null) {
		if(!empty($values)){
            $this->Build($values);
            return $this;
        }
	}

	function Build($values) {

		$this->setMemberId($values['member_id']);
		//CT pass in - hack 
		//$trades = array();
		$this->setTrades($values);
	}
	
	
	function Load($member_id="%", $from_date=LONG_LONG_AGO, $to_date=null) {

		global $cDB, $cErr, $site_settings, $cQueries;

		//$to_date = strtotime("+1 days", strtotime($this->getToDate()));
		$this->setMemberId($member_id);

		//$cErr->Error('load' . $this->getMemberId());
        // not sure if this is doing it correct but this somehow makes epoch time
        $from_date = date("Ymd", $from_date);
        // this should be far-far away time
        if(empty($to_date)){
            $to_date = date("Ymd", time());
        }
        $trade_type_refund = TRADE_REVERSAL;
        $trade_type = TRADE_MONTHLY_FEE;
        $trade_type_monthly_refund = TRADE_MONTHLY_FEE_REVERSAL;

        /*$condition ="(member_id_from LIKE \"{$member_id}\" 
        OR member_id_to LIKE \"{$member_id}\")
        AND trade_date > {$from_date} AND trade_date < {$to_date}"; */
        $condition ="(member_id_from LIKE \"{$member_id}\" 
        OR member_id_to LIKE \"{$member_id}\")
        AND trade_date > {$from_date} AND trade_date < {$to_date} AND type != '{$trade_type_refund}'";
        if(SHOW_GLOBAL_FEES !=true){
            $condition .= " AND type !='S' AND type != '{$trade_type}' AND type != '{$trade_type_monthly_refund}'";
        }
       
		$query = $cDB->Query($cQueries->getMySqlTrade($condition));

    	$trades = array();
		// instantiate new cTrade objects and load them
		while($row = $cDB->FetchArray($query)) // Each of our SQL results
		{
			//echo $row['balance'];
			$trade = new cTrade;	
			$trade->Build($row); 
			$trades[] = $trade;	
		}
		$this->setTrades($trades);
		//$cErr->Error(print_r($this->getTrades(), true));
		if(sizeof($trades) > 0) return true;
		// if no trades
		return false;
	}
	function Display($runningbalance=null) {
		global $cDB, $cUser, $p;
		
		//CT restructured so its got a running total and structure like a bank statement
		$output = "
			<tr>
				<th>Date</th>
                <th class='units'>Amount</th>
				<th>From</th>
				<th>To</th>
				<th>Category</th>
				<th>Description</th>
				";

		if(!empty($runningbalance)){
			$output .= "<th class='units balance'>Balance</th>";
		}
				
			$output .= "</tr>";
		
		if(empty($this->trades)) return $p->Wrap('No trades?' . $output, "table", "tabulated");   // No trades yet, presumably
		
		$i=0;
		foreach($this->getTrades() as $trade) {

			$hname = "t{$trade->getTradeId()}";			
            $currentbalance = number_format((float)$runningbalance, 2, '.', '');
            $feedback_link = (!empty($trade->getFeedback())) ? "<div class='trade-feedback'>From #{$trade->getMemberIdFrom()}: {$trade->getFeedback()->showRatingAsStars()} &quot;{$trade->getFeedback()->getComment()}&quot; </div>" : "";
            $amount = "{$trade->getAmount()}";
            $traded_from = "<a href='member_detail.php?member_id={$trade->getMemberIdFrom()}#{$hname}'>#{$trade->getMemberIdFrom()}</a>";
            $traded_to = "<a href='member_detail.php?member_id={$trade->getMemberIdTo()}#{$hname}'>#{$trade->getMemberIdTo()}</a>";

			if ($trade->getMemberIdTo() == $this->getMemberId())
			{
				if(!empty($runningbalance)){
					$runningbalance = $runningbalance - $trade->getAmount();
				}

				$statusclass = "credit";
				//$traded_from = "<a href='member_detail.php?member_id={$trade->getMemberIdFrom()}#{$hname}'>{$trade->getMemberIdFrom()}</a>";
				//$traded_to = "{$trade->getMemberIdTo()}";

			}
			else
			{				
				if(!empty($runningbalance)){
					$runningbalance = $runningbalance + $trade->getAmount();
				}
                $amount = "-{$amount}";
				$statusclass = "debit";
				//$traded_from = "{$trade->getMemberIdFrom()}";
				//$traded_to = "<a href='member_detail.php?member_id={$trade->getMemberIdTo()}#{$hname}'>{$trade->getMemberIdTo()}</a>";
			}
			//print_r($trade->getStatus());
			$reversalText = "";
			if($trade->getStatus() == TRADE_REVERSAL){
				$statusclass = "{$statusclass} reversal";
				$reversalText = " (reversed)";

			}
				
			
			//CT: use css styles not html colors - cleaner
			$rowclass = ($i % 2) ? "even" : "odd";	

			
			//CT todo: format
			$trade_date = $trade->getTradeDate();				
			$rb = (!empty($runningbalance)) ? "<td class='units balance'>{$currentbalance}</td>" : "";

			$output .= "<tr class='{$rowclass} {$statusclass}' id='{$hname}'>
				<td>{$trade_date}</td>
                <td class='units'>{$amount}</td>
				<td>{$traded_from}</td>
				<td>{$traded_to}</td>
				<td>{$trade->getCategory()->getCategoryName()}</td>
				<td><span class=\"metadata\">trade id: {$trade->getTradeId()}{$reversalText}</span>{$trade->getDescription()}{$feedback_link}</td>
				{$rb}</tr>
				";
				
			
			
			$i++;
		}
        $output = $p->Wrap($output, "table", "tabulated");
        $output .= "<br /><p>{$i} items found.</p>";
		return $output;
	}
	
	function MakeTradeArray() {
		$trades = "";
		if($this->trade) {
			foreach($this->trade as $trade) {
				if($trade->type != "R" and $trade->status != "R") {
					$trades[$trade->trade_id] = "#". $trade->trade_id ." - ". $trade->amount ." ". UNITS . " FROM ". $trade->member_from->member_id ." TO ". $trade->member_id_to ." ON ". $trade->trade_date;
				}
			}
		}
		
		return $trades;
	}
}


?>