<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.person.php");
include_once("class.site.php");
include_once("class.queries.php");

include_once("Text/Password.php");


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
     * @return mixed
     */
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
    public function setPrimaryPerson($array)
    {
    	//CT grabs values directly out of full result array passed to it. you can pass a partial set, as long as you respey name of members of array
        $this->person[0] = new cPerson($array);
    }    

    public function getSecondaryPerson()
    {
        return $this->person[1];
    }

    public function setSecondaryPerson($array)
    {
        if($array['account_type']=='J'){
        	$this->person[1] = new cSecondPerson($array);            // instantiate new cSecondPerson 	 objects and set them
        }
        
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




	public function SaveNewMember() {
		global $cDB, $cErr;	
		



		/* [chris] adjusted to store 'confirm_payments' preference */
        /* ct removed mothers maiden, fax, do this for legibility*/
                

        $hash = password_hash($plainTextPassword, PASSWORD_DEFAULT);
		$insert = $cDB->Query("INSERT INTO ".DATABASE_MEMBERS." (member_id, password, member_role, security_q, security_a, status, member_note, admin_note, join_date, expire_date, away_date, account_type, email_updates, confirm_payments, balance) VALUES (
                {$this->getMemberId()},
                {$hash},
                {$this->getSecurityQ()},
                {$this->getSecurityA()},
                {$this->getStatus()},
                {$this->getMemberNote()},
                {$this->getAdminNote()},
                {$this->getJoinDate()},
                {$this->getExpireDate()},
                {$this->getAwayDate()}
                {$this->getAccountType()}
                {$this->getEmailUpdates()},
                {$this->getConfirmPayments()},
                {$this->getBalance()}
            ");

		return $insert;
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
	
	public function LoginFromCookie()
	{
/*
		if (isset($_COOKIE["login"]) && isset($_COOKIE["pass"]))
		{
			$this->Login($_COOKIE["login"], $_COOKIE["pass"], true);
		}
*/
        return false;
	}

	public function IsLoggedOn()
	{
//		if (isset($_SESSION["user_login"]) and $_SESSION["user_login"] != LOGGED_OUT)
		if (isset($_SESSION["user_login"]))
			return true;
		else
			return false;
	}

	public function UnlockAccount() {
		$history = new cLoginHistory;
		$has_logged_on = $history->LoadLoginHistory($this->member_id);
		if($has_logged_on) {
			$consecutive_failures = $history->consecutive_failures;
			$history->consecutive_failures = 0;  // Set count back to zero whether locked or not
			$history->SaveLoginHistory();	
		} 
		
		if($this->status == LOCKED) {
			$this->status = ACTIVE;
			if($this->SaveMember()) {
				return $consecutive_failures;
			}			
		}
		return false;
	}
	
	public function DeactivateMember() {
		if($this->status == ACTIVE) {
			$this->status = INACTIVE;
			return $this->SaveMember();
		} else {
			return false;	
		}
	}
	
	public function ReactivateMember() {
		if($this->status != ACTIVE) {
			$this->status = ACTIVE;
			return $this->SaveMember();
		} else {
			return false;	
		}
	}
	
	
	public function AccountIsRestricted() {
		
		if ($this->restriction==1)
			return true;
		
		return false;
	}
    //CT this is a really dangerous function that was at the heart of most member objects - 
    //why load and pass around password and all the other stuff if you don't need to? Defer to LoadQuickMember for most things
    public function Load($member_id) {
		global $cDB, $cErr;
        //ct clean?
        $member_id = $cDB->EscTxt($member_id);
		// populate

        $condition = "p1.primary_member = 'N' and m.member_id='{$member_id}'";
        
        $query = $cDB->Query("{$cQueries->getMySqlMemberSummary()} WHERE {$condition}");
    

		if($row = $cDB->FetchArray($query))
		{	
            //$cErr->Error(print_r($row, true));
			$this->Build($row);
		}
		else
		{
            //CT - moved error message out of the redirect - don't you wnat to see errors even if not redirected?
            $cErr->Error("Error accessing member (".$member_id.").");

			if ($redirect) {
				include("redirect.php");
			}
			return false;
		}	
		return true;
	}



	public function Build($array){
		if (!empty($array['member_id']))  $this->setMemberId($array['member_id']);  
		if (!empty($array['password']))   $this->setPassword($array['password']);  
        if (!empty($array['member_role']))$this->setMemberRole($array['member_role']);  
		if (!empty($array['security_q'])) $this->setSecurityQ($array['security_q']);  
		if (!empty($array['security_q'])) $this->setSecurityA($array['security_a']);  
		if (!empty($array['status']))     $this->setStatus($array['status']);  
		if (!empty($array['member_note']))$this->setMemberNote($array['member_note']);  
		if (!empty($array['admin_note'])) $this->setAdminNote($array['admin_note']);  
		if (!empty($array['join_date']))  $this->setJoinDate($array['join_date']);  
		if (!empty($array['expire_date']))$this->setExpireDate($array['expire_date']);  
		if (!empty($array['away_date']))  $this->setAwayDate($array['away_date']);  
		if (!empty($array['account_type']))$this->setAccountType($array['account_type']);  
		if (!empty($array['email_updates']))$this->setEmailUpdates($array['email_updates']);  
		if (!empty($array['balance']))    $this->setBalance($array['balance']);  
        if (!empty($array['confirm_payments']))$this->setConfirmPayments($array['confirm_payments']);  
        if (!empty($array['restriction']))$this->setRestriction($array['restriction']); 
        //CT extra bits - just pass the whole thing in to get sorted
        $this->setTradeStats = new cTradeStatsCT();

        $this->setPrimaryPerson($array);  // this will be an array of cPerson class objects
        if(!empty($array['account_type']) and $array['account_type']=='J'){
            $this->setSecondaryPerson($array);  // this will be an array of cPerson class objects
        }

	}

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
	
	public function UpdateBalance($amount) {
		$this->balance += $amount;
		return $this->SaveMember();
	}
	
// CT TODO MAKE WORK
	public function SaveMember() {
		global $cDB, $cErr;				
		// [chris] included 'confirm_payments' preference
        // CT this will be tidied later - make safe query - now that member object is only populated with the field that the function needs		
        $fieldArray = array();
        $fieldArray["password"] = $this->getPassword();
        $fieldArray["member_role"] = $this->getMemberRole();
        $fieldArray["security_q"] = $this->getSecurityQ();
        $fieldArray["security_a"] = $this->getSecurityA();
        $fieldArray["status"] = $this->getStatus();
        $fieldArray["member_note"] = $this->getMemberNote();
        $fieldArray["admin_note"] = $this->getAdminNote();
        $fieldArray["join_date"] = $this->getJoinDate();
        $fieldArray["expire_date"] = $this->getExpireDate();
        $fieldArray["away_date"] = $this->getAwayDate();
        $fieldArray["account_type"] = $this->getAccountType();
        $fieldArray["email_updates"] = $this->getEmailUpdates();
        $fieldArray["balance"] = $this->getBalance();
        $fieldArray["confirm_payments"] = $this->getConfirmPayments();
        $fieldArray["restriction"] = $this->getRestriction();

        $string = $cDB->BuildUpdateQueryStringFromArray($fieldArray);
        //print($string);


		$update = $cDB->Query("UPDATE ".DATABASE_MEMBERS. " {$string} WHERE member_id=". $cDB->EscTxt($this->member_id) .";");	

		if(!$update)
			$cErr->Error("Could not save changes to member '". $this->member_id ."'.");

		foreach($this->person as $person) {
			$person->SavePerson();
		}
				
		return $update;	
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
	public function VerifyMemberExists($member_id) {
		global $cDB;
	
		$query = $cDB->Query("SELECT NULL FROM ".DATABASE_MEMBERS." WHERE member_id=". $cDB->EscTxt($member_id));
		
		if($row = $cDB->FetchArray($query))
			return true;
		else
			return false;
	}
	
	public function MemberLink($text=null) {
        global $p;
        if (empty($text)) $text = "#" . $this->member_id; //pass in name, or use member number if not there
        $link = "member_summary.php?member_id=". $this->member_id;
		return $p->Link($text, $link);
	}
	
	public function getMemberImage() {
        
        if (ALLOW_IMAGES!=true) // Images are turned off in config
            return null;
            
        global $cDB;
        
        $query = $cDB->Query("SELECT filename FROM ".DATABASE_UPLOADS." WHERE title="."\"mphoto_".$this->getMemberId()."\" limit 0,1;");
        //$query = $cDB->Query("SELECT filename FROM ".DATABASE_UPLOADS." WHERE title=".$cDB->EscTxt("mphoto_".$mID));
            //CT TODO makes work
        while($values = $cDB->FetchArray($query)) // Each of our SQL results
        {
            return UPLOADS_PATH . stripslashes($values["filename"]);         
        }
        //none found
        return IMAGES_PATH . "user-placeholder.svg"; 
    }
    // CT pass in image title. todo - fix the filetype??
    public function DisplayMemberImgFromTitle($mImage) {
        
        if (ALLOW_IMAGES!=true or empty($mImage)) {// Images are turned off in config of no iage
            //echo("oops");
            return "";
        }
        $imgLoc = UPLOADS_PATH . stripslashes($mImage);
    
        return  "<img src='".$imgLoc."' width='90' style='width:90px'>";    

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
	
	public function DaysSinceLastTrade() {
		global $cDB;
	
		$query = $cDB->Query("SELECT max(trade_date) FROM ". DATABASE_TRADES ." WHERE member_id_to=". $cDB->EscTxt($this->member_id) ." OR member_id_from=". $cDB->EscTxt($this->member_id) .";");
		
		$row = $cDB->FetchArray($query);
		
		if($row[0] != "")
			$last_trade = new cDateTime($row[0]);
		else
			$last_trade = new cDateTime($this->join_date);

		return $last_trade->DaysAgo();
	}
	
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

class cMemberGroup {
    //CT this should be private 
    public $members;
    // public function __construct($values=null)
    // {
    //     if(!empty($values)) {
    //         $this->Build($values);
    //     }

    //     return $this;
    // }
    public function getMembers()
    {
        return $this->members;
    }
    /**
     * @param mixed $join_date
     *
     * @return self
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    public function addMember($member)
    {
        $members = $this->getMembers();
        //add another
        $members[] = $member;
        $this->setMembers($members);
        return $this;
    }
    

    public function Build($members){

        //CT 
        $this->setmembers($members);  // this will be an array of cmembers


    }

    function Load ($condition=null, $order=null) {
        global $cDB, $cErr, $cQueries;

        // CT by default exclude non-active and fund accounts
        if(empty($condition)){
            $condition = " p1.primary_member = 'Y' and m.status = 'A' and m.account_type != 'F'";
        }
        
        $query = $cDB->Query("SELECT {$cQueries->getMySqlMemberConcise()} WHERE {$condition} ORDER BY " . order($order));
        
        $i=0;
        //CT TODO makes work
        while($row = $cDB->FetchArray($query)) // Each of our SQL results
        {
            $member = new cMemberConcise($row);
            //$cErr->Error($i);
            $this->addMember($member);    
            $i++;
        }

        return ($i == 0) ? false : true;     
    }   
    
    public function MakeIDArray() {
        global $cDB, $cErr;
        
        $ids="";        
        $ids[""] = "-- Select member --";
        foreach($this->getMembers() as $member) {
            // make options label

            $labeltext = ($member->getStatus() != "A") ? " - INACTIVE" : "";

            
          $ids[$member->getMemberId()] = $member->getDisplayName() . " (#" . $member->getMemberId() . $labeltext . ")";
        }       
        
        return $ids;    
    }   
    
    public function MakeNameArray() {
        global $cDB, $cErr;
        
        $names["0"] = "";
        
        if($this->members) {
            foreach($this->members as $member) {
                foreach ($member->person as $person) {          
                    $names[$member->getMemberId() ."?". $person->getPersonId()] = $person->getFirstName() ." ". $person->getLastName() ." (". $member->getMemberId() .")";
                }
            }   
        
            array_multisort($names);// sort purely by person name (instead of member, person)
        }
        
        return $names;      
    }   
    
    public function DoNamePicker() {
        
        $tmp = '<script src=includes/autocomplete.js></script>';
        
        $mems = $this->MakeNameArray();
        
        $tmp .= "<select name=member_to>
            <option id=0 value=0>".count($mems)." matching members...</option>";
        
        foreach($mems as $key=>$value) {
            
            $tmp .= "<option id='".$key."' value='".$key."'>".$value."</option>";
        }
        
        $tmp .= "</select>";
//      $form->addElement("select", "member_to", "...", $name_list->MakeNameArray());
        $tmp .= '<input type=text size=20 name=picker value="Member search" onKeyUp="autoComplete(this,document.all.member_to,\'text\')"
            onFocus="this.value=\'\'">
            <!--<input type=button value="Update Dropdown List">-->';
        return $tmp;
    }
    
    // Use of this function requires the inclusion of class.listing.php
    public function EmailListingUpdates($interval) {
        if(empty($this->getMembers())) {
            if(!$this->Load())
                return false;
        }

        $listings = new cListingGroup(OFFER_LISTING);
        $since = new cDateTime("-". $interval ." days");
        $listings->LoadListingGroup(null,null,null,$since->MySQLTime());
        $offered_text = $listings->DisplayListingGroup(true);
        $listings = new cListingGroup(WANT_LISTING);
        $listings->LoadListingGroup(null,null,null,$since->MySQLTime());
        $wanted_text = $listings->DisplayListingGroup(true);
        
        $email_text = "";
        if($offered_text != "No listings found.")
            $email_text .= "<h2>Offered Listings</h2><br>". $offered_text ."<p><br>";
        if($wanted_text != "No listings found.")
            $email_text .= "<h2>Wanted Listings</h2><br>". $wanted_text;
        if(!$email_text)
            return; // If no new listings, don't email
        
        $email_text = "<html><body>". LISTING_UPDATES_MESSAGE ."<p><br>".$email_text. "</body></html>";
            
        if ($interval == '1')
            $period = "day";
        elseif ($interval == '7')
            $period = "week";
        else
            $period = "month";          
        
        foreach($this->members as $member) {                        
            if($member->getEmailUpdates() == $interval and $member->getPrimaryPerson[0]->getEmail()) {
                mail($member->getPrimaryPerson->getEmail(), SITE_SHORT_TITLE .": New and updated listings during the last ". $period, wordwrap($email_text, 64), "From:". EMAIL_ADMIN ."\nMIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1"); 
            }
        
        }
    
    }
    
    // Use of this function requires the inclusion of class.listing.php
    public function ExpireListings4InactiveMembers() {
        if(!isset($this->members)) {
            if(!$this->Load())
                return false;
        }
        
        foreach($this->members as $member) {
            if($member->DaysSinceLastTrade() >= MAX_DAYS_INACTIVE
            and $member->DaysSinceUpdatedListing() >= MAX_DAYS_INACTIVE) {
                $offer_listings = new cListingGroup(OFFER_LISTING);
                $want_listings = new cListingGroup(WANT_LISTING);
                
                $offered_exist = $offer_listings->LoadListingGroup(null, null, $member->member_id, null, false);
                $wanted_exist = $want_listings->LoadListingGroup(null, null, $member->member_id, null, false);
                
                if($offered_exist or $wanted_exist) {
                    $expire_date = new cDateTime("+". EXPIRATION_WINDOW ." days");
                    if($offered_exist)
                        $offer_listings->ExpireAll($expire_date);
                    if($wanted_exist)
                        $want_listings->ExpireAll($expire_date);
                
                    if($member->person[0]->email != null) {
                        mail($member->person[0]->email, "Important information about your ". SITE_SHORT_TITLE ." account", wordwrap(EXPIRED_LISTINGS_MESSAGE, 64), "From:". EMAIL_ADMIN); 
                        $note = "";
                        $subject_note = "";
                    } else {
                        $note = "\n\n***NOTE: This member does not have an email address in the system, so they will need to be notified by phone that their listings have been inactivated.";
                        $subject_note = " (member has no email)";
                    }
                    
                    mail(EMAIL_ADMIN, SITE_SHORT_TITLE ." listings expired for ". $member->member_id. $subject_note, wordwrap("All of this member's listings were automatically expired due to inactivity.  To turn off this feature, see inc.config.php.". $note, 64) , "From:". EMAIL_ADMIN);
                }
            }
        }
    }
}


class cMemberGroupMenu extends cMemberGroup {		
	var $id;
	var $name;
	var $person_id;

	public function MakeMenuArrays() {
		global $cDB, $cErr;
		
		$i = 0;
		$j = 0;	
		foreach($this->members as $member) {
			foreach ($member->person as $person) {
				$this->id[$i] = $member->member_id;
				$this->name[$i][$j] = $person->first_name." ".$person->last_name;
				$this->person_id[$i][$j] = $person->person_id;						
				$j += 1;
			}
			$i += 1;
		}
		
		if($i <> 0)
			return true;
		else 
			return false;
	}
}

class cBalancesTotal {
	var $balance;
	
	public function Balanced() {
		global $cDB, $cErr;
		
		$query = $cDB->Query("SELECT sum(balance) from ". DATABASE_MEMBERS .";");
		
		if($row = $cDB->FetchArray($query)) {
			$this->balance = $row[0];
			
			if($row[0] == 0)
				return true;
			else
				return false;
		} else {
			$cErr->Error("Could not query database for balance information. Please try again later.");
			return false;
		}		
	}
}

class cIncomeTies extends cMember {
	
	public function getTie($member_id) {
		
		global $cDB;
		
		$q = "select * from income_ties where member_id=".$cDB->EscTxt($member_id)." limit 0,1";
		$result = $cDB->Query($q);
		
		if (!$result)
			return false;
		
		$row = $cDB->FetchObject($result);
		
		return $row;
	}
	
	public function saveTie($data) {
		
		global $cDB;
		
		if (!cIncomeTies::getTie($data["member_id"])) { // has no tie, INSERT row
			
			$q = "insert into income_ties set member_id=".$cDB->EscTxt($data["member_id"]).",
				 tie_id=".$cDB->EscTxt($data["tie_id"]).", percent=".$cDB->EscTxt($data["amount"])."";
				
		}
		else { // has a tie, UPDATE row
			
				$q = "update income_ties set tie_id=".$cDB->EscTxt($data["tie_id"]).", percent=".$cDB->EscTxt($data["amount"])." where member_id=".$cDB->EscTxt($data["member_id"])."";
		}
		
		$result = $cDB->Query($q);
		
		if (!$result)
			return "Error saving Income Share.";
			
		return "Income Share saved successfully.";
	}
	
	public function deleteTie($member_id) {
		
		global $cDB;
		
			if (!cIncomeTies::getTie($member_id)) { // has no tie to delete!
			
				return "No Income Share to delete!";
		}
		
		$q = "delete from income_ties where member_id=".$cDB->EscTxt($member_id)."";
		
		$result = $cDB->Query($q);
		
		if (!$result)
			return "Error deleting income Share.";
		
		return "Income Share deleted successfully.";
	}
	
}
//CT this is the most complicated version of the cMember - 
// for the public detail page for member. Images, feedback, activity etc
class cMemberSummary extends cMember {
    //extra properties
    private $display_name; // ct for display only
    private $display_location; // ct for display only
    private $stats; // ct activity summary object
    
    // public function __construct($values=null) {
    //     global $cErr;
    //     if(!empty($values)){
    //         //refers to standard parent
    //         $this->Build($values);
    //         $this->setDisplayName($values['display_name']);          
    //         //$this->setDisplayLocation($values['display_location']);          
    //     }
    // }
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
    /**
     * @return mixed
     */
    public function getDisplayLocation()
    {
        return $this->display_location;
    }
    /**
     * @param mixed $all_first_names
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
    public function getStats()
    {
        return $this->stats;
    }    
    /**
     * @param mixed $stats
     *
     * @return self
     */
    public function setStats($stats)
    {
         $this->stats = $stats;
    } 

    //CT load member from db
    public function Load($member_id) {
        global $cDB, $cErr, $cQueries;
        //clean it - needed?
        $member_id = $cDB->EscTxt($member_id);

        //CT composite all the summary/profile calls together for efficiency
        //TODO - put stats in here
        $condition = " p1.primary_member = 'Y' and m.member_id='{$member_id}' and m.status='A'";
        
        $query = $cDB->Query("SELECT {$cQueries->getMySqlMemberSummary()} WHERE {$condition} LIMIT 1");

        //CT this is a loop but there should only be 1
        while($values = $cDB->FetchArray($query)) // Each of our SQL results
        {
            //$cErr->Error(print_r($row, true));
            $this->Build($values);
            $this->setDisplayName($values['display_name']);          
            return true;
        }
        return false;
    }
/*
//        public function DisplayMember () {
        
//         /*[CDM] Added in image, placed all this in 2 column table, looks tidier */
        
//         global $cDB, $agesArr, $sexArr, $p;

//         $stats = new cTradeStatsCT($this->getMemberId());
//         $jointText = ($this->getAccountType() == "J") ? " (Joint account)" : "";
        
//         $statsText = (empty($stats->most_recent)) ? "No exchanges yet" : '<a href="trade_history.php?mode=other&member_id='. $this->getMemberId() .'">'. $stats->total_trades ." exchanges total</a> for a sum of ". $stats->total_units . " ". strtolower(UNITS) . ", last traded on ". $stats->most_recent;
//        $locationText = $this->getPrimaryPerson()->getAddressStreet2() . ", " . $this->getPrimaryPerson()->getAddressCity() . ", " .$this->getPrimaryPerson()->getSafePostCode();
// //$locationText = $this->getPrimaryPerson()->getAddressStreet2() . ", " . $this->getPrimaryPerson()->getAddressCity() . ", " .$this->getPrimaryPerson()->getSafePostCode();
// //$locationText = "location placeholder";

//         $feedbackgrp = new cFeedbackGroupCT;
//         $feedbackgrp->LoadFeedbackGroup($this->getMemberId());
//         $feedbackText = (empty($feedbackgrp->num_total)) ? "No feedback yet" : "<a href='feedback_all.php?mode=other&member_id={$this->member_id}'>{$feedbackgrp->PercentPositive()}% positive</a> ({$feedbackgrp->num_total} total, {$feedbackgrp->num_negative} negative &amp; {$feedbackgrp->num_neutral} neutral)";
        
//         $output .= $this->DisplayMemberImg($this->getMemberId());
//         $block = $this->FormatLabelValue("Location", $locationText);
        
//         //activity;
//         $block = $this->FormatLabelValue("Balance", "{$this->balance} " . strtolower(UNITS));
//         $block .= $this->FormatLabelValue("Activity", "{$statsText}");
//         $block .= $this->FormatLabelValue("Feedback", "{$feedbackText}");
//         $output .= $p->Wrap($block, "div", "group activity");

//         if (SOC_NETWORK_FIELDS==true) {
//             $pAge = (empty($this->getPrimaryPerson()->getAge())) ? 'Unspecified' : $agesArr[$this->getPrimaryPerson()->getAge()];
//             $pSex = (empty($this->getPrimaryPerson()->getSex())) ? 'Unspecified' : $sexArr[$this->getPrimaryPerson()->getSex()];
//             $pAbout = (empty($this->getPrimaryPerson()->getAboutMe())) ? '<em>No description supplied.</em>' : stripslashes($this->getPrimaryPerson()->getAboutMe());
//             $block = "";
//             $block .= $this->FormatLabelValue("Age", $pAge);
//             $block .= $this->FormatLabelValue("Gender", $pSex);
//             $block .= $this->FormatLabelValue("About me", $pAbout);
//             $output .= $p->Wrap($block, "div", "group social");
            
//      //       $output .= "<STRONG>Sex:</STRONG> ".$pSex."<p>";
            
//      //       $output .= "<STRONG>About Me:</STRONG><p> ".$pAbout."<br>";
//         }
//         //contact
//         $block = "";
//         if(!empty($this->getPrimaryPerson()->getEmail())){
//             $block .= $this->FormatLabelValue("Email", $this->makeLinkEmailForm($this->getPrimaryPerson()->getEmail()));
//         }
//         if(!empty($this->getPrimaryPerson()->DisplayPhone("1"))){
//             $block .= $this->FormatLabelValue("Phone", $this->getPrimaryPerson()->DisplayPhone("1"));
//         }
//         if(!empty($this->getPrimaryPerson()->DisplayPhone("2"))){
//             $block .= $this->FormatLabelValue("Secondary Phone", $this->getPrimaryPerson()->DisplayPhone("2"));
//         }
//         if(!empty($this->getPrimaryPerson()->DisplayPhone("fax"))){
//             $block .= $this->FormatLabelValue("Fax", $this->getPrimaryPerson()->DisplayPhone("fax"));
//         }
//         $output .= $p->Wrap($block, "div", "group contact");
//         //secondary
//         //TODO - directory list - is it really a choice?
//         $block = "";
//         //echo $this->getSecondaryPerson()->getDirectoryList();
//         if(!empty($this->getSecondaryPerson())){
//             $block .= $this->FormatLabelValue("Joint member", "{$this->getSecondaryPerson()->getFirstName()} {$this->getSecondaryPerson()->getLastName()}");

//             if(!empty($this->getSecondaryPerson()->getEmail())){
//                 $block .= $this->FormatLabelValue("{$this->getSecondaryPerson()->getFirstName()}'s email", $this->makeLinkEmailForm($this->getSecondaryPerson()->getEmail()));
//             }
//             if(!empty($this->getSecondaryPerson()->DisplayPhone("1"))){
//                 $block .= $this->FormatLabelValue("{$this->getSecondaryPerson()->getFirstName()}'s phone", $this->getSecondaryPerson()->DisplayPhone("1"));
//             }
//             if(!empty($this->getSecondaryPerson()->DisplayPhone("2"))){
//                 $block .= $this->getSecondaryPerson("{$this->getSecondaryPerson()->getFirstName()}'s secondary phone", $this->getSecondaryPerson()->DisplayPhone("2"));
//             }
//             if(!empty($this->getPrimaryPerson()->DisplayPhone("fax"))){
//                 $block .= $this->FormatLabelValue("{$this->getSecondaryPerson()->getFirstName()}'s fax", $this->getPrimaryPerson()->DisplayPhone("fax"));
//             }
//             $output .= $p->Wrap($block, "div", "group joint");
//         }
//         //metadata
//         //$join_date=new cDateTime($this->getJoinDate());
//         //$expire_date=new cDateTime($this->getExpireDate());
//         $block = "";
//         //$block .= $this->FormatLabelValue("Joined", $p->FormatLongDate($this->getJoinDate()));
//         //$block .= $this->FormatLabelValue("Renewal", $p->FormatLongDate($this->getExpireDate()));
//         $output .= $p->Wrap($block, "div", "group metadata");

//     return $output; 
//     }
    // public function DisplaySummaryMember () {
        
        
    //     global $cDB, $agesArr, $sexArr, $p;
        


    //     $stats = new cTradeStatsCT($this->getMemberId());
    //     $feedbackgrp = new cFeedbackGroupCT;
    //     $feedbackgrp->LoadFeedbackGroup($this->member_id);
    //     $variables = new stdClass();
    //     $variables = (object) [
    //         'member_id' => $this->getMemberId(),
    //         'member_display_name' => $this->getDisplayName(),
    //         'avatar_image' => $this->getMemberImage(),
    //         'member_balance' => "{$this->getBalance()} " . strtolower(UNITS),
    //         'member_since' => $this->getJoinDate(),
    //         'member_activity' => (empty($stats->most_recent)) ? "No exchanges yet" : '<a href="trade_history.php?mode=other&member_id='. $this->member_id .'">'. $stats->total_trades ." exchanges total</a> for a sum of ". $stats->total_units . " ". strtolower(UNITS) . ", last traded on ". $stats->most_recent,
    //         'member_location' => $this->getPrimaryPerson()->getAddressStreet2() . ", " .$this->getPrimaryPerson()->getSafePostCode(),
    //         'member_feedback' => (empty($feedbackgrp->num_total)) ? "No feedback yet" : "<a href='feedback_all.php?mode=other&member_id={$this->member_id}'>{$feedbackgrp->PercentPositive()}% positive</a> ({$feedbackgrp->num_total} total, {$feedbackgrp->num_negative} negative &amp; {$feedbackgrp->num_neutral} neutral)",
    //    ];
    //     //CT strings for display, not meaning
    //     $pName = "{$this->getPrimaryPerson()->getFirstName()} {$this->getPrimaryPerson()->getLastName()}";
    //     $pAge = (empty($this->getPrimaryPerson()->getAge())) ? '-' : $agesArr[$this->getPrimaryPerson()->getAge()];
    //     $pGender = (empty($this->getPrimaryPerson()->getSex())) ? '-' : $sexArr[$this->getPrimaryPerson()->getSex()];
    //     $pAbout = (empty($this->getPrimaryPerson()->getAboutMe())) ? '<em>No description supplied.</em>' : stripslashes($this->getPrimaryPerson()->getAboutMe());

    //     //member;
    //     $string=file_get_contents(TEMPLATES_PATH . '/member_summary.php', TRUE);
    //     $output = $p->ReplacePlaceholders($string, $variables);
       
    //     $variables = new stdClass();
    //     $variables = (object) [
    //         'person_name' => "{$pName}",
    //         'person_age' => "{$pAge}",
    //         'person_gender' => "{$pGender}",
    //         'person_about_me' => "{$pAbout}", 
    //         'person_email' => "{$this->getPrimaryPerson()->getEmail()}",
    //         'person_phone' => "{$this->getPrimaryPerson()->getAllPhones()}"
    //     ];
    //     $string=file_get_contents(TEMPLATES_PATH . '/person_summary.php', TRUE);
    //     $output .= $p->ReplacePlaceholders($string, $variables);

    //     // append secondary member if exists
    //     if ($this->getAccountType() == "J" && $this->getSecondaryPerson()->getDirectoryList() == "Y"){
    //         //CT strings for display, not meaning
    //         $pName = "{$this->getSecondaryPerson()->getFirstName()} {$this->getSecondaryPerson()->getLastName()}";
    //         $pAge = (empty($this->getSecondaryPerson()->getAge())) ? 'Unspecified' : $agesArr[$this->getSecondaryPerson()->getAge()];
    //         $pGender = (empty($this->getSecondaryPerson()->getSex())) ? 'Unspecified' : $sexArr[$this->getSecondaryPerson()->getSex()];
    //         $pAbout = (empty($this->getSecondaryPerson()->getAboutMe())) ? '<em>No description supplied.</em>' : stripslashes($this->getSecondaryPerson()->getAboutMe());

    //         $variables = new stdClass();
    //         $variables = (object) [
    //             'person_name' => "{$pName}",
    //             'person_age' => "{$pAge}",
    //             'person_gender' => "{$pGender}",
    //             'person_about_me' => "{$pAbout}", 
    //             'person_email' => "{$this->getSecondaryPerson()->getEmail()}",
    //             'person_phone' => "{$this->getSecondaryPerson()->getAllPhones()}"
    //         ];
    //         $string=file_get_contents(TEMPLATES_PATH . '/person_summary.php', TRUE);
    //         $output .= "<h3>Joint member</h3>";
    //         $output .= $p->ReplacePlaceholders($string, $variables);
    //     }

    //     return $output; 
    // }
}


//CT this is used for listings - where minimal of data needs to be loaded.
class cMemberConcise extends cMember {
    //extra properties
    private $display_name; // ct = for display only
    private $display_location; // ct or display only
    private $display_phone; // ct or display only
    private $display_email; // ct or display only
    
    // public function __construct($values=null) {
    //     global $cErr;
    //     if(!empty($values)){
    //         //refers to parent. todo: fix
    //         $this->Build($values);
    //         $this->setDisplayName($values['display_name']);          
    //         $this->setDisplayPhone($values['display_phone']);          
    //         $this->setDisplayEmail($values['display_email']);          
    //     }
    // }
    public function setDisplayName($display_name)
    {
        $this->display_name = $display_name;
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
     * @param mixed $display_phone
     *
     * @return self
     */
    public function setDisplayPhone($display_phone)
    {
        $this->display_phone = $display_phone;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getDisplayPhone()
    {
        return $this->display_phone;
    }
    /**
     * @param mixed $display_email
     *
     * @return self
     */
    public function setDisplayEmail($display_email)
    {
        $this->display_email = $display_email;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getDisplayEmail()
    {
        return $this->display_email;
    }

    public function setDisplayLocation($display_location)
    {
        $this->display_location = $display_location;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getDisplayLocation()
    {
        return $this->display_location;
    }


    public function Load($member_id) {
        global $cDB, $cErr, $cQueries;
        //clean it - needed?
        $member_id = $cDB->EscTxt($member_id);
        //if(is_null($condition)){
        $condition = "p1.primary_member = 'Y' and m.member_id='{$member_id}' and m.status = 'A'";
        $query = $cDB->Query("SELECT {$cQueries->getMySqlMemberConcise()} WHERE {$condition}");


        $i=0;
        //CT TODO makes work
        while($values = $cDB->FetchArray($query)) // Each of our SQL results
        {
            //CT add a few extras after builds. TODO - improve
            $this->Build($values);
            $this->setDisplayName($values['display_name']);          
            $this->setDisplayPhone($values['display_phone']);          
            $this->setDisplayEmail($values['display_email']);          
            $i++;
        }

        if (empty($i)){
            $cErr->Error("Error accessing member (".$member.").");
            if ($redirect) {
                include("redirect.php");
            }
            return false;
        }
        return true;
    }
    public function DisplaySummaryMember () {
        
        
        global $cDB, $agesArr, $sexArr, $p;
        
        $feedbackgrp = new cFeedbackGroupCT;
        $feedbackgrp->LoadFeedbackGroup($this->member_id);
        $variables = new stdClass();
        $variables = (object) [
            'member_id' => $this->getMemberId(),
            'display_name' => $this->getDisplayName(),
            'member_balance' => "{$this->getBalance()} " . strtolower(UNITS),
            'member_age' => "{$this->getPrimaryPerson()->getAge()}",
            'member_email' => $this->AllEmails(),
            'member_phone' => $this->AllPhones(),
            'member_since' => $this->getJoinDate(),
            'member_type' => ($this->getAccountType() == "J") ? " (Joint account)" : "",
            'member_activity' => (empty($stats->most_recent)) ? "No exchanges yet" : '<a href="trade_history.php?mode=other&member_id='. $this->member_id .'">'. $stats->total_trades ." exchanges total</a> for a sum of ". $stats->total_units . " ". strtolower(UNITS) . ", last traded on ". $stats->most_recent,
            'member_location' => $this->getPrimaryPerson()->getAddressStreet2() . ", " .$this->getPrimaryPerson()->getSafePostCode(),
            'member_location' => $this->getPrimaryPerson()->getAddressStreet2() . ", " .$this->getPrimaryPerson()->getSafePostCode(),
            'member_feedback' => (empty($feedbackgrp->num_total)) ? "No feedback yet" : "<a href='feedback_all.php?mode=other&member_id={$this->member_id}'>{$feedbackgrp->PercentPositive()}% positive</a> ({$feedbackgrp->num_total} total, {$feedbackgrp->num_negative} negative &amp; {$feedbackgrp->num_neutral} neutral)",
            'member_about' => "{$this->getPrimaryPerson()->getAboutMe()}"
        ];

        
        //activity;
        $string=file_get_contents(TEMPLATES_PATH . '/member_summary.php', TRUE);
        $output = $p->ReplacePlaceholders($string, $variables);
        return $output; 
    }
}


//CT just load what you need of current user
class cMemberSelf extends cMember {
    private $display_name;
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



    // public function __construct($values=null) {
    //     if(!empty($values)){
    //         //refers to parent
    //         $this->Build($values);
    //         $this->setDisplayName($values['display_name']);
    //     }
    // }
    public function Load($member_id) {
        global $cDB, $cErr, $cQueries;
        //
        if(empty($member_id)) return false;
        //CT barebones
        $member_id = $cDB->EscTxt($member_id);
        $condition = "p1.primary_member = 'Y' and m.member_id='{$member_id}' and m.status = 'A'";
        $query = $cDB->Query("SELECT {$cQueries->getMySqlMemberSelf()} WHERE {$condition} LIMIT 1");

        $i=0;
        while($row = $cDB->FetchArray($query))
        {       
            $this->Build($row);
            $this->setDisplayName($row['display_name']);
            $i++;
        }
        if(empty($i)){
            $cErr->Error("There was a problem loading your member details (".$member_id.").");
            return false;
        }
        return true;              
    }
    public function Login($member_id, $pass, $from_cookie=false) {
        global $cDB,$cErr;
        $login_history = new cLoginHistory();
        /*        $query = $cDB->Query("SELECT member_id, member_role 
        FROM ".DATABASE_USERS." WHERE member_id = " . $cDB->EscTxt($member_id) . " 
        AND (password=sha('". $cDB->EscTxt($pass) ."') 
        OR password='". $cDB->EscTxt($pass) ."') 
        and status = 'A';");    */  
        $query = $cDB->Query("SELECT member_id, status
            FROM ".DATABASE_USERS." WHERE member_id = '{$cDB->EscTxt($member_id)}'
            AND (password=sha('{$cDB->EscTxt($pass)}') OR password='{$cDB->EscTxt($pass)}');");
        while($row = $cDB->FetchArray($query)) {
            if($row['status'] == "L"){
                $error = "Your account has been locked due to too many unsuccessful login attempts. Contact the administrator for help for help";
            } else{
                // successs!
                $this->Load($member_id);
                $_SESSION["user_login"] = $member_id;   
                return true;
            }
        }
        if(!isset($error)) $error = "Your details were incorrect or you don't have an account.";
        $cErr->Error($error);


        $login_history->RecordLoginFailure($member_id, $status);
        return false;    
    }
    
    public function ValidatePassword($pass) {
        global $cDB;
        $query = $cDB->Query("SELECT member_id, member_role 
            FROM ".DATABASE_USERS." WHERE member_id = ". $cDB->EscTxt($this->member_id) ." 
            AND (password=sha({$cDB->EscTxt($pass)}) OR password={$cDB->EscTxt($pass)});");  
        
        return (empty($cDB->FetchArray($query))) ? true : false;
    }

    public function ChangePassword($pass) { // TODO: Should use SaveMember and should reset $this->password
        global $cDB, $cErr;
        
        $update = $cDB->Query("UPDATE ". DATABASE_MEMBERS ." SET password=sha(". $cDB->EscTxt($pass) .") WHERE member_id=". $cDB->EscTxt($this->member_id) .";");
        
        if($update) {
            return true;
        } else {
            $cErr->Error("There was an error updating the password.");
            include("redirect.php");
        }
    }
    
    public function GeneratePassword() {  
        return Text_Password::create(8) . chr(rand(50,57));
    }
    /*
        public function DoLoginStuff($member_id)
        {
            global $cDB;
            //setcookie("login",$user,time()+60*60*24*1,"/");
            //setcookie("pass",$pass,time()+60*60*24*1,"/");

            $this->LoadMember($member_id);
            $_SESSION["user_login"] = $member_id;
        }
    */
    public function UserLoginPage() // A free-standing login page
    {
        global $p;
        $string = file_get_contents(TEMPLATES_PATH . '/form_login.php', TRUE);
        return $p->ReplacePlaceholders($string);
    }



    public function MustBeLoggedOn()
    {
        global $p, $cErr;
        
        if ($this->IsLoggedOn())
            return true;
        
        // user isn't logged on, but is in a section of the site where they should be logged on.
        $_SESSION['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
        $cErr->SaveErrors();
        header("location:" . HTTP_BASE . "/login_redirect.php");
                
        exit;
    }


    public function Logout() {
        setcookie(session_name(), session_id(), time() - 42000, '/');
        $_SESSION = array();
        session_destroy();
    }

    public function MustBeLevel($level) {
        global $p;
        $this->MustBeLoggedOn(); // seems prudent to check first.

        if ($this->getMemberRole()<$level)
        {
            $page = "<p class='AccessDenied'>You don't have permissions for this action.  <a href='mailto:".EMAIL_ADMIN."'>Contact the admin</a> to raise your permissions</p>";
            $p->DisplayPage($page);
            exit;

        }

    }

}


$cUser = new cMemberSelf();
$cUser->RegisterWebUser();

?>
