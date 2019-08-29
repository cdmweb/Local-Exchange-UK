<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

// include_once("class.category.php");
//include_once("class.feedback.php");

class cTrade {
	private $trade_id;
	private $trade_date;
	private $status;
	private $member_id_from;
	private $member_id_to;
	private $member_from;
	private $member_to;
	private $amount;
    private $category;       // CT - category object
    private $category_id;       // CT - just the id.
	private $category_name;		// CT - just the description is fine here - 1 string.
	private $description;
	private $type;
	private $feedback;	// CT object
    /**
     * @return mixed
     */
    public function getTradeId()
    {
        return $this->trade_id;
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
     * @return mixed
     */
    public function getTradeDate()
    {
        return $this->trade_date;
    }

    /**
     * @param mixed $trade_date
     *
     * @return self
     */
    public function setTradeDate($trade_date)
    {
        $this->trade_date = $trade_date;

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
    public function getMemberIdFrom()
    {
        return $this->member_id_from;
    }

    /**
     * @param mixed $member_id_from
     *
     * @return self
     */
    public function setMemberIdFrom($member_id_from)
    {
        $this->member_id_from = $member_id_from;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberIdTo()
    {
        return $this->member_id_to;
    }

    /**
     * @param mixed $member_id_to
     *
     * @return self
     */
    public function setMemberIdTo($member_id_to)
    {
        $this->member_id_to = $member_id_to;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberFrom()
    {
        return $this->member_from;
    }

    /**
     * @param mixed $member_from
     *
     * @return self
     */
    public function setMemberFrom($member_from)
    {
        $this->member_from = $member_from;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberTo()
    {
        return $this->member_to;
    }

    /**
     * @param mixed $member_to
     *
     * @return self
     */
    public function setMemberTo($member_to)
    {
        $this->member_to = $member_to;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     *
     * @return self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

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
    //     /**
    //  * @return mixed
    //  */
    // public function getCategoryName()
    // {
        
    //     return $this->category_name;
    // }

    // *
    //  * @param mixed $category_name
    //  *
    //  * @return self
     

    //    /**
    //  * @return mixed
    //  */
    // public function getCategoryId()
    // {
    //     return $this->category_id;
    // }

    // /**
    //  * @param mixed $category_id
    //  *
    //  * @return self
    //  */
    // public function setCategoryId($category_id)
    // {
    //     $this->category_id = $category_id;

    //     return $this;
    // }

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
    public function getFeedback()
    {
        return $this->feedback;
    }

    /**
     * @param mixed $feedback_rating
     *
     * @return self
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

	function __construct($variables=null) {
		if(!empty($variables)) {
			$this->Build($variables);
		}
	}
	function Build($variables) {	
		global $cErr;
		//$cErr->Error("row:"  . print_r($variables, true));
		if(!empty($variables['trade_status'])) $this->setStatus($variables['trade_status']);  // V for valid
		if(!empty($variables['trade_id'])) $this->setTradeId($variables['trade_id']);  // V for valid
		if(!empty($variables['trade_date'])) $this->setTradeDate($variables['trade_date']);  // V for valid
		if(!empty($variables['trade_amount'])) $this->setAmount($variables['trade_amount']);
		if(!empty($variables['trade_description'])) {
            $description = $variables['trade_description'];
            if($variables['trade_status'] == 'R'){
                $description .= " <span class=\"note\">[REVERSED]</span>";
            }
            $this->setDescription($description);
        }
		if(!empty($variables['feedback_id'])) {
			$feedback = new cFeedback($variables);
			$this->setFeedback($feedback);
		}
		if(!empty($variables['trade_member_id_from'])) {
			$this->setMemberIdFrom($variables['trade_member_id_from']);
			//load nice names etc
			//$this->setMemberFrom = new;
		}
		if(!empty($variables['trade_member_id_to'])) {
			$this->setMemberIdTo($variables['trade_member_id_to']);
			//load nice names etc
			//$this->setMemberTo = new;

		}
		if(!empty($variables['trade_type'])) $this->setType($variables['trade_type']);
        //`CT category object
        $category = new cCategory($variables);
		$this->setCategory($category);
	}
	
	/*function ShowTrade() {
		global $cDB;
		
		$content = $this->trade_id .", ". $this->trade_date .", ". $this->status .", ". $this->member_from->getMemberId() .", ". $this->member_id_to .", ". $this->amount .", ". $this->category->id .", ". $this->description .", ". $this->type;
		
		return $content;
	}*/

	function SaveTrade() {  // This function should never be called directly
		global $cDB, $cErr;
		
		$insert = $cDB->Query("INSERT INTO ". DATABASE_TRADES ." (trade_date, status, member_id_from, member_id_to, amount, category, description, type) VALUES (now(), {$this->getStatus()}, {$this->getMemberIdTo()}, {$this->getMemberIdFrom()}, {$this->getAmount()}, {$this->getCategory()->getCategoryId()}, {$this->getDescription()}, {$this->getType()});");

		if(mysqli_affected_rows() == 1) {
		
			$this->setTradeId(mysqli_insert_id());	
			$query = $cDB->Query("SELECT trade_id, trade_date from ". DATABASE_TRADES ." WHERE trade_id=\"{$this->getTradeId()}\";");
			
	        while($values = $cDB->FetchArray($query)) // Each of our SQL results
	        {
	            $this->setTradeDate($values['trade_date']);	  
	            return true;       
	        }
		} 
		return false;
	}
	
	function LoadTrade($trade_id) {
		global $cDB, $cErr,  $cQueries;
		//CT - efficiency - combine db calls. categories, feedback 
        $condition = "trade_id={$cDB->EscTxt($trade_id)}";
        $order = "";
		$query = $cDB->Query($cQueries->getMySqlTrade($condition));

		
		if($row = $cDB->FetchArray($query)) {		
			$this->Build($row);
/*
			$feedback = new cFeedback;
			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_from->getMemberId());
			if($feedback_id) {
				$this->feedback_buyer = new cFeedback;
				$this->feedback_buyer->LoadFeedback($feedback_id);
			}
			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_to->getMemberId());
			if($feedback_id) {
				$this->feedback_seller = new cFeedback;
				$this->feedback_seller->LoadFeedback($feedback_id);
			}*/
			
		} else {
			$cErr->Error("There was an error accessing the trades table.");
			//include("redirect.php");
		}				
	}

// 	//no need to call each line separately in a db call, you've got it! jsut used the array
// 	function Build($array) {
// 		global $cDB, $cErr;
		
// 		//$query = $cDB->Query("SELECT date_format(trade_date,'%Y-%m-%d'), status, member_id_from, member_id_to, amount, description, type, category FROM ".DATABASE_TRADES." WHERE trade_id=". $cDB->EscTxt($trade_id) .";");
		
// 		//if($row = $cDB->FetchArray($query)) {		
// 			$this->trade_id = $array['trade_id'];
// 			$this->trade_date = $array['trade_date'];
// 			$this->status = $array['status'];
// 			//doesnt appear to be needed, only ids are listed.
// 			//$this->member_from = new cMember;
// 			//CT - todo - create v small class extension to stop loading EVERYTHING for one fraking line
// 			//$this->member_from->ConstructMember(array('member_id' => $array['member_id_from']));
// 			//$this->member_to = new cMember;
// 			//$this->member_from->ConstructMember(array('member_id' => $array['member_id_to']));
			
// 			//$this->member_id_from = $this->member_from->getMemberId();
// 			//$this->member_id_to = $this->member_to->getMemberId();
// 			$this->member_id_from = $array['member_id_from'];
// 			$this->member_id_to = $array['member_id_to'];
// 			$this->amount = $array['amount'];
// 			$this->description = $cDB->UnEscTxt($array['description']);
// 			$this->category = $cDB->UnEscTxt($array['category']);
// 			$this->type = $array['type'];
// 			//$this->category = new cCategory();
// 			//$this->category->LoadCategory($array['category']);
			
// /*			$feedback = new cFeedback;
// 			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_from->getMemberId());
// 			if($feedback_id) {
// 				$this->feedback_buyer = new cFeedback;
// 				$this->feedback_buyer->LoadFeedback($feedback_id);
// 			}
// 			$feedback_id = $feedback->FindTradeFeedback($trade_id, $this->member_to->getMemberId());
// 			if($feedback_id) {
// 				$this->feedback_seller = new cFeedback;
// 				$this->feedback_seller->LoadFeedback($feedback_id);
// 			}
// */			
					
// 	}

	// It is very important that this function prevent the database from going out balance.
	function MakeTrade($reversed_trade_id=null) { 
		global $cDB, $cErr;
		
		if ($this->getAmount() <= 0 and $this->getType() != TRADE_REVERSAL) // Amount should be positive unless
			return false;									 // this is a reversal of a previous trade.
			
		if ($this->getAmount() >= 0 and $this->getType() == TRADE_REVERSAL)	 // And likewise.
			return false;
			
		
		if ($this->getMemberIdFrom() == $this->getMemberIdTo())
			return false;		// don't allow trade to self
		
		if ($this->getMemberFrom()->getRestriction()==1) { // This member's account has been restricted - they are not allowed to make outgoing trades
			
			return false;
		}
	
		$balances = new cBalancesTotal;
	
		// TODO: At some point, we should handle out-of-balance problems without shutting 
		// down all trades.  But for now, seems like a wonderfully simple solution.	
		//
		// [chris] Have added a few more methods for dealing with out-of-balance scenarios (admin can set his/her preferred method in inc.config.php)	
		// CT - TODO put this in the db and elsewhere
		if(!$balances->Balanced()) {
			
			if (OOB_EMAIL_ADMIN==true) // Admin wishes to receive an email notifying him/her when db is found to be out-of-balance
				$mailed = mail(EMAIL_ADMIN, "Database out of balance on ".SITE_LONG_TITLE."!", "Hi admin,\n\nWe thought you should know that whilst processing a trade the system detected that your trade database is out of balance! Obviously something has gone wrong somewhere along the line and we suggest you investigate the cause of this ASAP.\n\n" .  HTTP_BASE, EMAIL_FROM);
			
			switch(OOB_ACTION) { // How should we handle the out-of-balance event?
				
				case("FATAL"): // FATAL: The original method for dealing which is to abort the transaction
					
					$cErr->Error("The trade database is out of balance!  Please contact your administrator at ". EMAIL_ADMIN .".", ERROR_SEVERITY_HIGH);  

					//include("redirect.php");
					exit;  // Probably unnecessary...
					
				break;
				
				default: // SILENT: Just ignore the situation and don't burden the user with warnings/error messages
					
						// doing nothing...
						
				break;
			}
		}	

		// NOTE: Need table type InnoDB to do the following transaction-style statements.		
		$cDB->Query("SET AUTOCOMMIT=0");
		
		$cDB->Query("BEGIN");
		
		if($this->SaveTrade()) {
			
			$success1 = $this->getMemberFrom()->UpdateBalance(-($this->amount));
			$success2 = $this->getMemberTo()->UpdateBalance($this->amount);
			
			if(LOG_LEVEL > 0 and $this->getType() != TRADE_ENTRY) {//Log if enabled & not an ordinary trade
				$log_entry = new cLogEntry (TRADE, $this->getType(), $this->getTradeId());
				$success3 = $log_entry->SaveLogEntry();
			} else {
				$success3 = true;
			}
			
			if($reversed_trade_id) {  // If this is a trade reversal, need to mark old trade reversed
				$success4 = $cDB->Query("UPDATE ".DATABASE_TRADES." SET status='R', trade_date=trade_date WHERE trade_id=". $cDB->EscTxt($reversed_trade_id) .";");
			} else {
				$success4 = true;
			}

			if($success1 and $success2 and $success3 and $success4) {
				$cDB->Query('COMMIT');
				$cDB->Query("SET AUTOCOMMIT=1"); // Probably isn't necessary...
				return true;
			} else {
				$cDB->Query('ROLLBACK');
				$cDB->Query("SET AUTOCOMMIT=1"); // Probably isn't necessary...
				return false;
			}
		} else {
			$cDB->Query("SET AUTOCOMMIT=1"); // Probably isn't necessary...
			return false;
		}			
	}
	
	function ReverseTrade($description) { 	// This method allows administrators to reverse
		global $cUser;								// trades that were made in error.
		
		if($this->status == "R")
			return false;		// Can't reverse the same trade twice
			
		$new_trade = new cTrade;				
		$new_trade->setStatus("V");
		$new_trade->setMemberFrom($this->getMemberFrom());
		$new_trade->setMemberTo($this->getMemberTo());
		$new_trade->setAmount($this->getAmount());
		$new_trade->setCategory($this->getCategory());
		$string = "[Reversal of exchange #{$this->getTradeId()} from {$this->getTradeDate()} by admin {$cUser->getMemberId()}] {$description}";
		$new_trade->setDescription($string);
		$new_trade->setType("R");
		return $new_trade->MakeTrade($this->getTradeId());
	}
	
}

?>
