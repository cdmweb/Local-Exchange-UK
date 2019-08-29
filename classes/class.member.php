<?php



//include_once("Text/Password.php");



class cMember
{
    private $member_id;
    private $is_nested; //if set to true, don't cross ref other classes - prevent circuclar logic ;
	//private $password;
    private $member_role;
	private $security_q;
	private $security_a;
	private $status;
	private $member_note;
	private $admin_note;
	private $join_date;
	private $expire_date;
	private $away_date;
	private $account_type;
	private $email_updates;
	private $balance;
	private $confirm_payments;
	private $restriction;
   //CT: extra properties
    private $person;  // array of cPerson objects
    private $display_name; // ct for display only
    private $display_location;  // hack - safe location for reuse


    // function __construct($values=null) {
    //     if ($values) {
    //         $this->Build($values);
    //     }
    // }
    function __construct($is_nested=false) {
        $this->setIsNested($is_nested);
    }
	/* CT getters and setters


    /**
     * @param mixed $person
     *
     * @return self
     */
    
    
    public function setPerson($person)
    {
        $this->person = new cPerson($person);
    }  
    //get full array of persons
    public function getPerson()
    {
        //return $this->primary_person;
        return $this->person;
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMemberRole()
    {
        //print('member role' . $this->member_role);
        return $this->member_role;
    }

    /**
     * @param mixed $member_role
     *
     * @return self
     */
    public function setMemberRole($member_role)
    {
        $this->member_role = $member_role;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecurityQ()
    {
        return $this->security_q;
    }

    /**
     * @param mixed $security_q
     *
     * @return self
     */
    public function setSecurityQ($security_q)
    {
        $this->security_q = $security_q;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSecurityA()
    {
        return $this->security_a;
    }

    /**
     * @param mixed $security_a
     *
     * @return self
     */
    public function setSecurityA($security_a)
    {
        $this->security_a = $security_a;

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
    public function getMemberNote()
    {
        return $this->member_note;
    }

    /**
     * @param mixed $member_note
     *
     * @return self
     */
    public function setMemberNote($member_note)
    {
        $this->member_note = $member_note;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdminNote()
    {
        return $this->admin_note;
    }

    /**
     * @param mixed $admin_note
     *
     * @return self
     */
    public function setAdminNote($admin_note)
    {
        $this->admin_note = $admin_note;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getJoinDate()
    {
        return $this->join_date;
    }

    /**
     * @param mixed $join_date
     *
     * @return self
     */
    public function setJoinDate($join_date)
    {
        $this->join_date = $join_date;

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
    public function getAwayDate()
    {
        return $this->away_date;
    }

    /**
     * @param mixed $away_date
     *
     * @return self
     */
    public function setAwayDate($away_date)
    {
        $this->away_date = $away_date;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccountType()
    {
        return $this->account_type;
    }

    /**
     * @param mixed $account_type
     *
     * @return self
     */
    public function setAccountType($account_type)
    {
        $this->account_type = $account_type;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmailUpdates()
    {
        return $this->email_updates;
    }

    /**
     * @param mixed $email_updates
     *
     * @return self
     */
    public function setEmailUpdates($email_updates)
    {
        $this->email_updates = $email_updates;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param mixed $balance
     *
     * @return self
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

 /**
     * @param mixed $restriction
     *
     * @return self
     */
    public function setConfirmPayments($confirm_payments)
    {
        $this->confirm_payments = $confirm_payments;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getConfirmPayments()
    {
        return $this->confirm_payments;
    }
    /**
     * @return mixed
     */
    public function getRestriction()
    {
        return $this->restriction;
    }

    /**
     * @param mixed $restriction
     *
     * @return self
     */
    public function setRestriction($restriction)
    {
        $this->restriction = $restriction;

        return $this;
    }

    //these are used as helpers - not associated with a property of object

    public function getStatusLabel()
    {
        
        $status = $this->getStatus();
        if($status == 'I'){
            $label= "Inactive";

        } else{
            $label= "Active";
        }
        return $label;
    }
/**
     * @return mixed
     */
    public function getDisplayLocation()
    {
        return $this->display_location;
    }

    /**
     * @param mixed $display_location
     *
     * @return self
     */
    public function setDisplayLocation($display_location)
    {
        $this->display_location = $display_location;

        return $this;
    }

        /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }    
    /**
     * @param mixed $display_name
     *
     * @return self
     */
    public function setDisplayName($display_name)
    {
          $this->display_name = $display_name;

        return $this;
    }

    /* these are pseudogetters and setters for convenience */
    public function getPrimaryPerson()
    {
        //return $this->primary_person;
        return $this->person[0];
    }

    /**
     * @param mixed $person
     *
     * @return self
     */

    public function setPrimaryPerson($field_array=null)
    {
        //CT grabs values directly out of full result array passed to it. you can pass a partial set, as long as you respey name of members of array
        // if nothing passed, just instantiates it like the standard construct
        $primaryPerson = new cPerson();
        if($field_array) $primaryPerson->Build($field_array);
        $this->person[0] = $primaryPerson;
        return $this;
    } 
    /* gets secondary person from array */
    public function getSecondaryPerson()
    {
        //print(!empty($this->person[1]));
        if (!empty($this->person[1])){
            //print_r($this->person[1]->getFirstName());
            return $this->person[1];
        }
        return null;
    }

    public function setSecondaryPerson($field_array=null)
    {
        //print('gets to set secondperson');
        if($field_array['account_type']=='J'){
            $secondPerson = new cPersonSecondary($field_array);
            $this->person[1] = $secondPerson;           // instantiate new cSecondPerson    objects and set them
            //
        }
        return $this;
    }



//CT whats this for?
    /**
     * @return mixed
     */
    public function getIsNested()
    {
        return $this->is_nested;
    }

    /**
     * @param mixed $is_nested
     *
     * @return self
     */
    public function setIsNested($is_nested)
    {
        $this->is_nested = $is_nested;

        return $this;
    }





	public function RegisterWebUser()
	{	
        //print_r($_SESSION["user_login"]);
//		if (isset($_SESSION["user_login"]) and $_SESSION["user_login"] != LOGGED_OUT) {
		if (isset($_SESSION["user_login"])) {
            $member_id = $_SESSION["user_login"];
			$this->setMemberId($member_id);
			$this->Load($member_id);

            // Session regeneration added to boost server-side security.
            session_regenerate_id();
		}
        // Then next block has been inactivated due to security concerns.
		else {
			//$this->LoginFromCookie();
		}		
	}
	
	

	
	/* replaced by getter for restriction */
    /* DELETE
	public function AccountIsRestricted() {
		
		if ($this->restriction==1)
			return true;
		
		return false;
	}
    */
    //CT rebuilt to be..not so dangerous. do not load passwords and such into memory
    public function Load($condition) {
		global $cDB, $cErr, $cQueries;
        //ct clean?
        //$member_id = $cDB->EscTxt($member_id);
		// populate

        
        $query = $cDB->Query("{$cQueries->getMySqlMember($condition)}");

		if($row = $cDB->FetchArray($query))
		{	
            //$cErr->Error(print_r($row, true));
			$this->Build($row);
		}
		else
		{
            //CT - moved error message out of the redirect - don't you wnat to see errors even if not redirected?
            $cErr->Error("Error accessing member.");

			if ($redirect) {
				include("redirect.php");
			}
			return false;
		}	
		return true;
	}




	public function Build($values){
        global $cDB;
		if (isset($values['member_id']))  $this->setMemberId($cDB->EscTxt($values['member_id']));  
		if (isset($values['password']))   $this->setPassword($values['password']);  
        if (isset($values['member_role']))$this->setMemberRole($values['member_role']);  
		if (isset($values['security_q'])) $this->setSecurityQ($values['security_q']);  
		if (isset($values['security_q'])) $this->setSecurityA($values['security_a']);  
		if (isset($values['status']))     $this->setStatus($values['status']);  
		if (isset($values['member_note']))$this->setMemberNote($cDB->EscTxt($values['member_note']));  
		if (isset($values['admin_note'])) $this->setAdminNote($cDB->EscTxt($values['admin_note']));  
		if (isset($values['join_date']))  $this->setJoinDate($values['join_date']);  
		if (isset($values['expire_date']))$this->setExpireDate($values['expire_date']);  
		if (isset($values['away_date']))  $this->setAwayDate($values['away_date']);  
		if (isset($values['account_type']))$this->setAccountType($values['account_type']);  
		if (isset($values['email_updates']))$this->setEmailUpdates($values['email_updates']);  
		if (isset($values['balance'])) $this->setBalance($values['balance']);  
        if (isset($values['confirm_payments']))$this->setConfirmPayments($values['confirm_payments']);  
        if (isset($values['restriction']))$this->setRestriction($values['restriction']); 
        if (isset($values['display_name']))$this->setDisplayName($values['display_name']); 
        //CT extra bits - just pass the whole thing in to get sorted
        $this->setTradeStats = new cTradeSummary($values);

        $this->setPrimaryPerson($values);  // this will be an array of cPerson class objects
        if(isset($values['account_type']) and $values['account_type']=='J'){
            //print('should set');
            $this->setSecondaryPerson($values);  // this will be an array of cPerson class objects
        }

        //CT hack - cleanup. Just dont know where to put it yet :(
        $display_location = "";
        if(!empty($this->getPrimaryPerson()->getAddressStreet2())){
            $display_location .= $this->getPrimaryPerson()->getAddressStreet2() . ", ";
        }
        if(!empty($this->getPrimaryPerson()->getAddressCity())){
            $display_location .= $this->getPrimaryPerson()->getAddressCity() . ", ";
        }
        $display_location .= $this->getPrimaryPerson()->getSafePostCode();
        $this->setDisplayLocation($display_location);
	}
/*
	public function ShowMember()
	{
		$output = "Member Data:<BR>";
		$output .= $this->member_id . ", " . $this->password . ", " . $this->member_role . ", " . $this->security_q . ", " . $this->security_a . ", " . $this->status . ", " . $this->member_note . ", " . $this->admin_note . ", " . $this->join_date . ", " . $this->expire_date . ", " . $this->away_date . ", " . $this->account_type . ", " . $this->email_updates . ", " . $this->balance . "<BR><BR>";
		
		$output .= "Person Data:<BR>";
		
		foreach($this->person as $person)
		{
			$output .= $person->ShowPerson();
			$output .= "<BR><BR>";
		}			
						
		return $output;
	}		
*/	
	public function UpdateBalance($amount) {
		$this->balance += $amount;
		return $this->Save();
	}
	

    public function makeExpireRelativeDate() {
        $sentence = "";
        $now = date("Y-m-d");

        $datetime1 = new DateTime($now);
        $datetime2 = new DateTime($this->getExpireDate());
        $interval = $datetime1->diff($datetime2);
        //return $interval->format('%R%a days');
        $interval =  $interval->format('%R%a');
        $string = "";
        if (substr($interval, 0, 1) == "+"){
            if(substr($interval,1)<30){
                $classname = "positive";
                $interval =  "Expires in " . substr($interval,1) . " days";
                $string = "<span class=\"expiry {$classname}\">$interval</span>";
            }
        } else{
            $classname = "negative";
            $interval =   "Expired " . substr($interval,1) . " days ago";
            $string = "<span class=\"expiry {$classname}\">$interval</span>";
        }
        
 
        return $string;
    }

	
	public function VerifyPersonInAccount($person_id) { // Make sure hacker didn't manually change URL
		global $cErr;
		foreach($this->person as $person) {
			if($person->getPersonId() == $person_id)
				return true;
		}
		$cErr->Error("Invalid person id in URL.  This break-in attempt has been reported.",ERROR_SEVERITY_HIGH);
		include("redirect.php");
	}
/*
	public function makeLinkEmailForm($email){
        //return "<a href='mailto:{$email}' class='normal'>{$email}</a>";
        return "<a href='mailto:{$email}' class='normal'>{$email}</a>";
    }
	public function AllEmails () {
        $emails='';
		foreach ($this->person as $person) {
			if(!empty($person->getEmail())){
                //$email = $this->makeLinkEmailForm($person->getEmail());
                $email = $person->getEmail();
	            if($person->getPrimaryMember() != "Y") {
	                $emails .= ", ";
	            } 	
                //$emails .= "{$email}'";    
                $emails .= "<a href='mailto:{$email}'>{$email}</a>";    
			}
        }
			
		return $emails;	
	}
	
*/
    //can be used to check for duplicates on member creation, or verify email and member_id combo for password reset
	public function VerifyMemberExists($member_id, $email=null) {
		global $cDB;
        $condition = "m.member_id=\"{$member_id}\"";

        if (empty($email)) $condition .= "AND m.email=\"{$email}\""; 
        $field_array = array('m.member_id'=>'member_id');
        $order_by = "member_id ASC LIMIT 1";
	    $string_query = $cDB->BuildSelectQuery(DATABASE_MEMBERS . " m", $field_array, "", $condition, $order_by);
		$query = $cDB->Query($string_query);
        //return
		if($row = $cDB->FetchArray($query)) return true;
		else return false;
	} 
        // ct not using Pear. 
    // todo: wehen user logs in for the first time, redirect to change password.
    // this is used on password reset and new user flows
    public function GeneratePassword() {  
        //CT This is at least actually random
        $length = "8";
        $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $str = "";
        $max = mb_strlen($keyspace, '8bit') - 1;
        if ($max < 1) {
            throw new Exception('$keyspace must be at least two characters long');
        }
        for ($i = 0; $i < $length; ++$i) {
            $str .= $keyspace[random_int(0, $max)];
        }
        return $str;
    }
	
	public function MemberLink($text=null) {
        global $p;
        if (empty($text)) $text = "#" . $this->member_id; //pass in name, or use member number if not there
        $link = "member_detail.php?member_id=". $this->member_id;
		return $p->Link($text, $link);
	}
	
	
	//CT todo - put this somewhere for reuse..
	public function FormatLabelValue($label, $value){
		return "<p class='line'><span class='label'>{$label}: </span><span class='value'>{$value}</span></p>";
	}



    public function MakeJointMemberArray() {
        global $cDB;
        
        $names = array();
        foreach ($this->person as $person) {
            if($person->primary_member != 'Y') {
                $names[$person->person_id] = $person->first_name ." ". $person->last_name;
                }
        }
        
        return $names;  
    }   
 
	
		
	/*
	public function DaysSinceLastTrade() {
		global $cDB;
	
		$query = $cDB->Query("SELECT max(trade_date) FROM ". DATABASE_TRADES ." WHERE member_id_to=". $cDB->EscTxt($this->member_id) ." OR member_id_from=". $cDB->EscTxt($this->member_id) .";");
		
		$row = $cDB->FetchArray($query);
		
		if($row[0] != "")
			$last_trade = new cDateTime($row[0]);
		else
			$last_trade = new cDateTime($this->join_date);

		return $last_trade->DaysAgo();
	}*/
	
	public function DaysSinceUpdatedListing() {
		global $cDB;
	
		$query = $cDB->Query("SELECT max(posting_date) FROM ". DATABASE_LISTINGS ." WHERE member_id=". $cDB->EscTxt($this->member_id) .";");
		
		$row = $cDB->FetchArray($query);
		
		if($row[0] != "")
			$last_update = new cDateTime($row[0]);
		else
			$last_update = new cDateTime($this->join_date);

		return $last_update->DaysAgo();
	}	
}

?>
