<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.person.php");
include_once("class.site.php");
include_once("Text/Password.php");


class cMember
{
	//CT: format as public
	private $person;  // array of cPerson objects
	//private $secondary_person;  // secondary joint membership
	private $member_id;
	private $password;
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

	function cMember($values=null) {
		if ($values) {
			$this->ConstructMember($values);
		}
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




	public function SaveNewMember() {
		global $cDB, $cErr;	
		



		/* [chris] adjusted to store 'confirm_payments' preference */
        /* ct removed mothers maiden, fax, do this for legibility*/
                

        $hash = password_hash($plainTextPassword, PASSWORD_DEFAULT);
		$insert = $cDB->Query("INSERT INTO ".DATABASE_MEMBERS." (member_id, password, member_role, security_q, security_a, status, member_note, admin_note, join_date, expire_date, away_date, account_type, email_updates, confirm_payments, balance) VALUES (
                {$this->getMemberRole()},
                {$hash},
                {$this->getMemberRole()},
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
//		if (isset($_SESSION["user_login"]) and $_SESSION["user_login"] != LOGGED_OUT) {
		if (isset($_SESSION["user_login"])) {
			$this->setMemberId($_SESSION["user_login"]);
			$this->LoadMember($_SESSION["user_login"]);

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

	public function Login($user, $pass, $from_cookie=false) {
		global $cDB,$cErr;
		
		$login_history = new cLoginHistory();
//echo "SELECT member_id, password, member_role FROM ".DATABASE_USERS." WHERE member_id = " . $cDB->EscTxt($user) . " AND (password=sha(". $cDB->EscTxt($pass) .") OR password=". $cDB->EscTxt($pass) .") and status = 'A';";
		$query = $cDB->Query("SELECT member_id, password, member_role FROM ".DATABASE_USERS." WHERE member_id = " . $cDB->EscTxt($user) . " AND (password=sha(". $cDB->EscTxt($pass) .") OR password=". $cDB->EscTxt($pass) .") and status = 'A';");			
		if($row = mysql_fetch_array($query)) {
			$login_history->RecordLoginSuccess($user);
			$this->DoLoginStuff($user, $row["password"]);	// using pass from db since it's encrypted, and $pass isn't, if it was entered in the browser.
			return true;
		} elseif (!$from_cookie) {
			$query = $cDB->Query("SELECT NULL FROM ".DATABASE_USERS." WHERE status = 'L' and member_id=". $cDB->EscTxt($user) .";");
			if($row = mysql_fetch_array($query)) {
				$cErr->Error("Your account has been locked due to too many unsuccessful login attempts. You will need to contact us to have your account unlocked.");
			} else {
				$cErr->Error("Password or member id is incorrect.  Please try again, or go <A HREF=password_reset.php>here</A> to have your password reset.", ERROR_SEVERITY_INFO);
			}
			$login_history->RecordLoginFailure($user);
			return false;
		}	
		return false;
	}
	
	public function ValidatePassword($pass) {
		global $cDB;

		$query = $cDB->Query("SELECT member_id, password, member_role FROM ".DATABASE_USERS." WHERE member_id = ". $cDB->EscTxt($this->member_id) ." AND (password=sha(". $cDB->EscTxt($pass) .") OR password=". $cDB->EscTxt($pass) .");");	
		
		if($row = mysql_fetch_array($query))
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
	
	public function ChangePassword($pass) { // TODO: Should use SaveMember and should reset $this->password
		global $cDB, $cErr;
		
		$update = $cDB->Query("UPDATE ". DATABASE_MEMBERS ." SET password=sha(". $cDB->EscTxt($pass) .") WHERE member_id=". $cDB->EscTxt($this->member_id) .";");
		
		if($update) {
			return true;
		} else {
			$cErr->Error("There was an error updating the password. Please try again later.");
			include("redirect.php");
		}
	}
	
	public function GeneratePassword() {  
		return Text_Password::create(8) . chr(rand(50,57));
	}

	public function DoLoginStuff($user, $pass)
	{
		global $cDB;
		
		//setcookie("login",$user,time()+60*60*24*1,"/");
		//setcookie("pass",$pass,time()+60*60*24*1,"/");

		$this->LoadMember($user);
		$_SESSION["user_login"] = $user;
	}

	public function UserLoginPage() // A free-standing login page
	{
		global $p;
        $output = $p->WrapFormElement('hidden', 'action', '', 'login');
        $output .= $p->WrapFormElement('hidden', 'location', '', $_SERVER['REQUEST_URI']);
        $output .= $p->WrapFormElement('text', 'user', 'Member ID');
        $output .= $p->WrapFormElement('password', 'pass', 'Password');
        $output .= $p->WrapFormElement('submit', 'submit', '', 'Log in');

        $output = $p->WrapForm($output, SERVER_PATH_URL."/login.php", "post", "login");
        $output .= $p->Wrap("If you don't have an account, please contact us to join. <a href='/members/password_reset.php'>Forgot your password?</a>", "p");

        //return $output;
        $output .= "<DIV STYLE='width=60%; padding: 5px;'><FORM ACTION=".SERVER_PATH_URL."/login.php METHOD=POST>
                    <INPUT TYPE=HIDDEN NAME=action VALUE=login>
                    <INPUT TYPE=HIDDEN NAME=location VALUE='".$_SERVER["REQUEST_URI"]."'>
                    <TABLE class=NoBorder><TR><TD ALIGN=LEFT>Member ID:</TD><TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=12 NAME=user></TD></TR>
                    <TR><TD ALIGN=LEFT>Password:</TD><TD ALIGN=LEFT><INPUT TYPE=PASSWORD SIZE=12 NAME=pass></TD></TR></TABLE>
                    <DIV align=LEFT><INPUT TYPE=SUBMIT VALUE='Login'></DIV>
                    </FORM></DIV>
                    <BR>
                    If you don't have an account, please contact us to join. <a href='/members/password_reset.php'>Forgot your password?</a>
                    <BR>";  
		return $output;
	}

	public function UserLoginLogout() {
		if ($this->IsLoggedOn())
		{
			//$output = "<FONT SIZE=1><A HREF='".SERVER_PATH_URL."/member_logout.php'>Logout</A>&nbsp;&nbsp;&nbsp;";
			$output = "<A HREF='".SERVER_PATH_URL."/member_logout.php'>Logout</A>&nbsp;&nbsp;&nbsp;";
		} else {
			//$output = "<FONT SIZE=1><A HREF='".SERVER_PATH_URL."/member_login.php'>Login</A>&nbsp;&nbsp;&nbsp;";
			$output = "<A HREF='".SERVER_PATH_URL."/member_login.php'>Login</A>&nbsp;&nbsp;&nbsp;";
		}

		return $output;		
	}

	public function MustBeLoggedOn()
	{
		global $p, $cErr;
		
		if ($this->IsLoggedOn())
			return true;
		
		// user isn't logged on, but is in a section of the site where they should be logged on.
		$_SESSION['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
		$cErr->SaveErrors();
		header("location:http://".HTTP_BASE."/login_redirect.php");
				
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
	
	public function AccountIsRestricted() {
		
		if ($this->restriction==1)
			return true;
		
		return false;
	}
    //CT this is a really dangerous function that was at the heart of most member objects - 
    //why load and pass around password and all the other stuff if you don't need to? Defer to LoadQuickMember for most things
	public function LoadMember($member, $redirect=false) {
		global $cDB, $cErr;

		//
		// select all Member data and populate the properties
		//
		/*[chris] adjusted to retrieve 'confirm_payments' */
		//CT efficiency full object 
        $query = $cDB->Query("SELECT 
                m.balance as balance, 
                m.password as password, 
                m.member_role as member_role, 
                m.security_q as security_q, 
                m.security_a as security_a, 
                m.status as status, 
                m.admin_note as admin_note, 
                m.join_date as join_date, 
                m.expire_date as expire_date, 
                m.email_updates as email_updates, 
                m.restriction as restriction, 
                p1.first_name as first_name, 
                p1.last_name as last_name, 
                p1.email as email, 
                p1.person_id as person_id, 
                p2.person_id as p2_person_id, 
                p2.email as p2_email, 
                p2.first_name as p2_first_name, 
                p2.mid_name as p2_mid_name, 
                p2.last_name as p2_last_name, 
                p1.phone1_number as phone1_number, 
                p1.primary_member as primary_member, 
                p2.primary_member as p2_primary_member, 
                p2.phone1_number as p2_phone1_number, 
                p1.address_street1 as address_street1, 
                p1.address_street2 as address_street2, 
                p1.address_city as address_city, 
                p1.address_state_code as address_state_code, 
                p1.address_post_code as address_post_code, 
                p1.address_country as address_country, 
                p1.directory_list as directory_list, 
                p2.directory_list as p2_directory_list, 
                m.member_id as member_id, 
                m.account_type as account_type, 
                m.confirm_payments as confirm_payments, 
                p1.age as age, 
                p1.sex as sex, 
                p1.about_me as about_me 
                FROM member m 
                left JOIN person p1 ON m.member_id=p1.member_id 
                left JOIN (select * from person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id where p1.primary_member = 'Y' and m.member_id=". $cDB->EscTxt($member));


		if($row = mysql_fetch_array($query))
		{	
            //$cErr->Error(print_r($row, true));
			$this->ConstructMember($row);
		}
		else
		{
            //CT - moved error message out of the redirect - don't you wnat to see errors even if not redirected?
            $cErr->Error("There was an error accessing this member (".$member.").  Please try again later.");

			if ($redirect) {
				include("redirect.php");
			}
			return false;
		}	
		//CT efficiency - wrap up the 'person' in complex join in 1 call, not 3, above. TODO: remove this
		//
		// Select associated person records and load into person object array
		//
        /*
		$query = $cDB->Query("SELECT person_id FROM ".DATABASE_PERSONS." WHERE member_id=". $cDB->EscTxt($member) ." ORDER BY primary_member DESC, last_name, first_name");
		$i = 0;
		
		while($row = mysql_fetch_array($query))
		{
			$this->person[$i] = new cPerson;			// instantiate new cPerson objects and load them
			$this->person[$i]->LoadPerson($row[0]);
			$i += 1;
		}

		if($i == 0)
		{
			if ($redirect) {
				$cErr->Error("There was an error accessing a person record for (".$member.").  Please try again later.");
				include("redirect.php");			
			}
			return false;
		}*/

		return true;
	}
	public function ConstructMember($array){
		//print_r($array);

		$this->setPrimaryPerson($array);  // this will be an array of cPerson class objects
        if($array['account_type']=='J'){
            $this->setSecondaryPerson($array);  // this will be an array of cPerson class objects
        }
		$this->setMemberId($array['member_id']);  
		$this->setPassword($array['password']);  
		$this->setMemberRole($array['member_role']);  
		$this->setSecurityQ($array['security_q']);  
		$this->setSecurityA($array['security_a']);  
		$this->setStatus($array['status']);  
		$this->setMemberId($array['member_id']);  
		$this->setMemberNote($array['member_note']);  
		$this->setAdminNote($array['admin_note']);  
		$this->setJoinDate($array['join_date']);  
		$this->setExpireDate($array['expire_date']);  
		$this->setAwayDate($array['away_date']);  
		$this->setAccountType($array['account_type']);  
		$this->setEmailUpdates($array['email_updates']);  
		$this->setBalance($array['balance']);  
        $this->setRestriction($array['restriction']);  
        //$this->setDirectoryList($array['directory_list']);  
        //$this->setAge($array['age']);  
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
	
	public function SaveMember() {
		global $cDB, $cErr;				
		
		// [chris] included 'confirm_payments' preference
		$update = $cDB->Query("UPDATE ".DATABASE_MEMBERS." SET password=". $cDB->EscTxt($this->password) .", member_role=". $cDB->EscTxt($this->member_role) .", security_q=". $cDB->EscTxt($this->security_q) .", security_a=". $cDB->EscTxt($this->security_a) .", status=". $cDB->EscTxt($this->status) .", member_note=". $cDB->EscTxt($this->member_note) .", admin_note=". $cDB->EscTxt($this->admin_note) .", join_date=". $cDB->EscTxt($this->join_date) .", expire_date=". $cDB->EscTxt($this->expire_date) .", away_date=". $cDB->EscTxt($this->away_date) .", account_type=". $cDB->EscTxt($this->account_type) .", email_updates=". $cDB->EscTxt($this->email_updates) .", confirm_payments=".$cDB->EscTxt($this->confirm_payments).", balance=". $cDB->EscTxt($this->balance) ." WHERE member_id=". $cDB->EscTxt($this->member_id) .";");	

		if(!$update)
			$cErr->Error("Could not save changes to member '". $this->member_id ."'. Please try again later.");

		foreach($this->person as $person) {
			$person->SavePerson();
		}
				
		return $update;	
	}
	
	public function PrimaryName () {
		return $this->getPrimaryPerson()->getFirstName() . " " . $this->getPrimaryPerson()->getLastName();
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
	
	public function PrimaryAddress () {
        $address="";
		if(!empty($this->person[0]->getAddressStreet1())) {
			$address = $this->person[0]->getAddressStreet1();
			if(!empty($this->person[0]->getAddressStreet2()))
				$address .=   ", " . $this->person[0]->getAddressStreet2();
		} 
		
		return $address . ", " . $this->person[0]->getAddressCity();
	}
	
	public function AllNames ($lastfirst=false) {
		//can reverse appearance of name if lastfirst set
		$names = "";
		foreach ($this->person as $person) {
			if ($person->getPrimaryMember() != "Y" && !empty($person->getFirstName())) $names .= " &amp; ";
			if($lastfirst) {
				$n = "{$person->getLastName()}, {$person->getFirstName()}";
			} else{
				$n = "{$person->getFirstName()} {$person->getLastName()}";
			}
            //$names .= "<span class='name'>{$n}</span>";
            $names .= "{$n}";
			//$names .= "{$person->getFirstName()}  {$person->getLastName()}";
		}
		return $names;
	}
    //CT - firstnames
    public function AllFirstNames () {
        //can reverse appearance of name if lastfirst set
        $names = "";
        foreach ($this->person as $person) {
            if ($person->getPrimaryMember() != "Y" && !empty($person->getFirstName())) $names .= " and ";
            $n = "{$person->getFirstName()}";
            //$names .= "<span class='name'>{$n}</span>";
            $names .= "{$n}";
            //$names .= "{$person->getFirstName()}  {$person->getLastName()}";
        }
        return $names;
    }
    public function AllPhones () {
		$phones = "";
		foreach ($this->person as $person) {
            //$isSecondary = ($p->getPrimaryMember() != "Y"); 
			if(!empty($person->getPhone1Number())) {
				if ($person->getPrimaryMember() == "Y"){
                    //$phones .= "<span class='phone'>{$person->getPhone1Number()}</span>";
                    $phones .= "{$person->getPhone1Number()}";
				} else{
                    //$phones .=", <span class='phone'>{$person->getPhone1Number()} ({$person->getFirstName()})</span>";
                    $phones .=", {$person->getPhone1Number()} ({$person->getFirstName()})";
				}
			}
			if(!empty($person->getPhone2Number())) {
				$phones .= ", ". $person->getPhone2Number();
                if ($person->getPrimaryMember() != "Y") $phones .= " ({$person->getFirstName()})";              
				//$reg_phones[] = $person->getPhone2Number();
			}
			if(!empty($person->getFaxNumber())) {
				$phones .= ", ". $person->getFaxNumber();
                if ($person->getPrimaryMember() != "Y") {
                    $phones .= " ({$person->getFirstName()}'s fax')";
                } else{
                    $phones .= "(fax)";
                }
				//$fax_phones[] = $person->getFaxNumber();
			}
		
		}
		return $phones;		
	}
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
	
	public function VerifyMemberExists($member_id) {
		global $cDB;
	
		$query = $cDB->Query("SELECT NULL FROM ".DATABASE_MEMBERS." WHERE member_id=". $cDB->EscTxt($member_id));
		
		if($row = mysql_fetch_array($query))
			return true;
		else
			return false;
	}
	
	public function MemberLink($text=null) {
        if (empty($text)) $text = $this->member_id; //pass in name, or use member number if not there
		return "<a href='member_summary.php?member_id=". $this->member_id ."''>". $text ."</a>";
	}
	
	/*[chris] this function looks up the image for member ($mID) and places it in a HTML img tag */
	public function DisplayMemberImg($mID,$typ=false) {
        
        if (ALLOW_IMAGES!=true) // Images are turned off in config
            return " ";
            
        global $cDB;
        
        // note: the 'typ' param has been deprecated since new method introduced for resizing imgs
        /*
        if ($typ=='thumb') {
            $pH = MEMBER_PHOTO_HEIGHT_THUMB;
            $pW = MEMBER_PHOTO_WIDTH_THUMB;
        }
        else {
            
            $pH = MEMBER_PHOTO_HEIGHT;
            $pW = MEMBER_PHOTO_WIDTH;
        }
        */
        $query = $cDB->Query("SELECT filename FROM ".DATABASE_UPLOADS." WHERE title=".$cDB->EscTxt("mphoto_".$mID)." limit 0,1;");
        //$query = $cDB->Query("SELECT filename FROM ".DATABASE_UPLOADS." WHERE title=".$cDB->EscTxt("mphoto_".$mID));
        
        $num_results = mysql_num_rows($query);
        
        if ($num_results>0) {
            
            $row = mysql_fetch_array($query);
            $imgLoc = 'http://cam.letslink.org/members/uploads/' . stripslashes($row["filename"]);
    
            return  "<img src='".$imgLoc."'>";    
        }
        else
            return  " ";
    }
    // CT pass in image title. todo - fix the filetype??
    public function DisplayMemberImgFromTitle($mImage) {
        
        if (ALLOW_IMAGES!=true or empty($mImage)) {// Images are turned off in config of no iage
            //echo("oops");
            return "";
        }
        $imgLoc = 'http://cam.letslink.org/members/uploads/' . stripslashes($mImage);
    
        return  "<img src='".$imgLoc."' width='90' style='width:90px'>";    

    }
	//CT todo - put this somewhere for reuse..
	public function FormatLabelValue($label, $value){
		return "<p class='line'><span class='label'>{$label}: </span><span class='value'>{$value}</span></p>";
	}


	public function DisplayMember () {
		
		/*[CDM] Added in image, placed all this in 2 column table, looks tidier */
		
		global $cDB, $agesArr, $sexArr, $p;

		$stats = new cTradeStatsCT($this->getMemberId());
		$jointText = ($this->getAccountType() == "J") ? " (Joint account)" : "";
		
        $statsText = (empty($stats->most_recent)) ? "No exchanges yet" : '<a href="trade_history.php?mode=other&member_id='. $this->member_id .'">'. $stats->total_trades ." exchanges total</a> for a sum of ". $stats->total_units . " ". strtolower(UNITS) . ", last traded on ". $stats->most_recent;
        $locationText = $this->getPrimaryPerson()->getAddressStreet2() . ", " . $this->getPrimaryPerson()->getAddressCity() . ", " .$this->getPrimaryPerson()->getSafePostCode();

		$feedbackgrp = new cFeedbackGroupCT;
		$feedbackgrp->LoadFeedbackGroup($this->member_id);
		$feedbackText = (empty($feedbackgrp->num_total)) ? "No feedback yet" : "<a href='feedback_all.php?mode=other&member_id={$this->member_id}'>{$feedbackgrp->PercentPositive()}% positive</a> ({$feedbackgrp->num_total} total, {$feedbackgrp->num_negative} negative &amp; {$feedbackgrp->num_neutral} neutral)";
        
        $output .= cMember::DisplayMemberImg($this->member_id);
        $block = $this->FormatLabelValue("Location", $locationText);
		
        //activity;
        $block = $this->FormatLabelValue("Balance", "{$this->balance} " . strtolower(UNITS));
		$block .= $this->FormatLabelValue("Activity", "{$statsText}");
		$block .= $this->FormatLabelValue("Feedback", "{$feedbackText}");
        $output .= $p->Wrap($block, "div", "group activity");

        if (SOC_NETWORK_FIELDS==true) {
            $pAge = (empty($this->getPrimaryPerson()->getAge())) ? 'Unspecified' : $agesArr[$this->getPrimaryPerson()->getAge()];
            $pSex = (empty($this->getPrimaryPerson()->getSex())) ? 'Unspecified' : $sexArr[$this->getPrimaryPerson()->getSex()];
            $pAbout = (empty($this->getPrimaryPerson()->getAboutMe())) ? '<em>No description supplied.</em>' : stripslashes($this->getPrimaryPerson()->getAboutMe());
            $block = "";
            $block .= $this->FormatLabelValue("Age", $pAge);
            $block .= $this->FormatLabelValue("Gender", $pSex);
            $block .= $this->FormatLabelValue("About me", $pAbout);
            $output .= $p->Wrap($block, "div", "group social");
            
     //       $output .= "<STRONG>Sex:</STRONG> ".$pSex."<p>";
            
     //       $output .= "<STRONG>About Me:</STRONG><p> ".$pAbout."<br>";
        }
        //contact
        $block = "";
        if(!empty($this->getPrimaryPerson()->getEmail())){
            $block .= $this->FormatLabelValue("Email", $this->makeLinkEmailForm($this->getPrimaryPerson()->getEmail()));
        }
        if(!empty($this->getPrimaryPerson()->DisplayPhone("1"))){
            $block .= $this->FormatLabelValue("Phone", $this->getPrimaryPerson()->DisplayPhone("1"));
        }
        if(!empty($this->getPrimaryPerson()->DisplayPhone("2"))){
            $block .= $this->FormatLabelValue("Secondary Phone", $this->getPrimaryPerson()->DisplayPhone("2"));
        }
        if(!empty($this->getPrimaryPerson()->DisplayPhone("fax"))){
            $block .= $this->FormatLabelValue("Fax", $this->getPrimaryPerson()->DisplayPhone("fax"));
        }
        $output .= $p->Wrap($block, "div", "group contact");
        //secondary
        //TODO - directory list - is it really a choice?
        $block = "";
        //echo $this->getSecondaryPerson()->getDirectoryList();
        if(!empty($this->getSecondaryPerson())){
            $block .= $this->FormatLabelValue("Joint member", "{$this->getSecondaryPerson()->getFirstName()} {$this->getSecondaryPerson()->getLastName()}");

            if(!empty($this->getSecondaryPerson()->getEmail())){
                $block .= $this->FormatLabelValue("{$this->getSecondaryPerson()->getFirstName()}'s email", $this->makeLinkEmailForm($this->getSecondaryPerson()->getEmail()));
            }
            if(!empty($this->getSecondaryPerson()->DisplayPhone("1"))){
                $block .= $this->FormatLabelValue("{$this->getSecondaryPerson()->getFirstName()}'s phone", $this->getSecondaryPerson()->DisplayPhone("1"));
            }
            if(!empty($this->getSecondaryPerson()->DisplayPhone("2"))){
                $block .= $this->getSecondaryPerson("{$this->getSecondaryPerson()->getFirstName()}'s secondary phone", $this->getSecondaryPerson()->DisplayPhone("2"));
            }
            if(!empty($this->getPrimaryPerson()->DisplayPhone("fax"))){
                $block .= $this->FormatLabelValue("{$this->getSecondaryPerson()->getFirstName()}'s fax", $this->getPrimaryPerson()->DisplayPhone("fax"));
            }
            $output .= $p->Wrap($block, "div", "group joint");
        }
        //metadata
        $join_date=new cDateTime($this->getJoinDate());
        $expire_date=new cDateTime($this->getExpireDate());
        $block = "";
        $block .= $this->FormatLabelValue("Joined", "{$join_date->ShortDate()}");
        $block .= $this->FormatLabelValue("Renewal", "{$expire_date->ShortDate()}");
        $output .= $p->Wrap($block, "div", "group metadata");

	return $output;	
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
		
		$row = mysql_fetch_array($query);
		
		if($row[0] != "")
			$last_trade = new cDateTime($row[0]);
		else
			$last_trade = new cDateTime($this->join_date);

		return $last_trade->DaysAgo();
	}
	
	public function DaysSinceUpdatedListing() {
		global $cDB;
	
		$query = $cDB->Query("SELECT max(posting_date) FROM ". DATABASE_LISTINGS ." WHERE member_id=". $cDB->EscTxt($this->member_id) .";");
		
		$row = mysql_fetch_array($query);
		
		if($row[0] != "")
			$last_update = new cDateTime($row[0]);
		else
			$last_update = new cDateTime($this->join_date);

		return $last_update->DaysAgo();
	}	
}

class cMemberGroup {
    var $members;
    
    function LoadMemberGroup ($active_only=TRUE, $non_members=FALSE) {
        global $cDB;
                
        if($active_only)
            $exclusions = " AND status in ('A','L')";
        else
            $exclusions = null;
            
        if(!$non_members)
            $exclusions .= " AND member_role != '9'";
        
        $query = $cDB->Query("SELECT ".DATABASE_MEMBERS.".member_id FROM ". DATABASE_MEMBERS .",". DATABASE_PERSONS." WHERE ". DATABASE_MEMBERS .".member_id=". DATABASE_PERSONS.".member_id". $exclusions. " AND primary_member='Y' ORDER BY first_name, last_name;");
        
        $i=0;
        while($row = mysql_fetch_array($query))
        {
            $this->members[$i] = new cMember;           
            $this->members[$i]->LoadMember($row[0]);
            $i += 1;
        }
        
        if($i == 0)
            return false;
        else
            return true;        
    }   
    
    public function MakeIDArray() {
        global $cDB, $cErr;
        
        $ids="";        
        if($this->members) {
            foreach($this->members as $member) {
                    $ids[$member->member_id] = $member->PrimaryName() ." (". $member->member_id .")";
            }       
        }
        
        return $ids;    
    }   
    
    public function MakeNameArray() {
        global $cDB, $cErr;
        
        $names["0"] = "";
        
        if($this->members) {
            foreach($this->members as $member) {
                foreach ($member->person as $person) {          
                    $names[$member->member_id ."?". $person->person_id] = $person->first_name ." ". $person->last_name ." (". $member->member_id .")";
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
        if(!isset($this->members)) {
            if(!$this->LoadMemberGroup())
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
            if($member->email_updates == $interval and $member->person[0]->email) {
                mail($member->person[0]->email, SITE_SHORT_TITLE .": New and updated listings during the last ". $period, wordwrap($email_text, 64), "From:". EMAIL_ADMIN ."\nMIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1"); 
            }
        
        }
    
    }
    
    // Use of this function requires the inclusion of class.listing.php
    public function ExpireListings4InactiveMembers() {
        if(!isset($this->members)) {
            if(!$this->LoadMemberGroup())
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
		
		if($row = mysql_fetch_array($query)) {
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
		$result = $cDB->query($q);
		
		if (!$result)
			return false;
		
		$row = mysql_fetch_object($result);
		
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
//CT adjusted 
class cMemberSummaryView extends cMember {
    public function cMemberSummaryView($values=null) {
        if(!empty($values)){
            $this->ConstructMember();
        }
    }
    public function LoadMember($member, $redirect=true) {
        global $cDB, $cErr;
//CT efficiency 
        $query = $cDB->Query("SELECT 
                m.balance as balance, 
                m.status as status, 
                m.join_date as join_date, 
                m.expire_date as expire_date, 
                p1.first_name as first_name, 
                p1.last_name as last_name, 
                p1.email as email, 
                p1.person_id as person_id, 
                p2.person_id as p2_person_id, 
                p2.email as p2_email, 
                p2.first_name as p2_first_name, 
                p2.last_name as p2_last_name, 
                p1.phone1_number as phone1_number, 
                p1.primary_member as primary_member, 
                p2.primary_member as p2_primary_member, 
                p1.phone1_number as p1_phone1_number, 
                p2.phone1_number as p2_phone1_number, 
                p1.address_street2 as address_street2, 
                p1.address_city as address_city, 
                p1.address_state_code as address_state_code, 
                p1.address_post_code as address_post_code, 
                p1.directory_list as directory_list, 
                p2.directory_list as p2_directory_list, 
                m.member_id as member_id, 
                p1.age as age, 
                p1.sex as sex, 
                p1.about_me as about_me 
                FROM member m 
                left JOIN person p1 ON m.member_id=p1.member_id 
                left JOIN (select * from person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id where p1.primary_member = 'Y' and m.member_id=". $cDB->EscTxt($member));

        //$query = $cDB->Query("SELECT m.balance as balance, p1.first_name as first_name, p1.last_name as last_name, p1.email as email, p2.email as p2_email, p2.first_name as p2_first_name, p2.last_name as p2_last_name, p1.phone1_number as phone1_number, p1.primary_member as primary_member, p2.primary_member as p2_primary_member, p2.phone1_number as p2_phone1_number, p1.address_street2 as address_street2, p1.address_city as address_city,p1.address_post_code as address_post_code, p1.age as age, p1.about_me as about_me, m.member_id as member_id, m.account_type as account_type, DATE_FORMAT(join_date, '".SHORT_DATE_FORMAT."') as join_date, DATE_FORMAT(expire_date, '".SHORT_DATE_FORMAT."') as expire_date FROM member m left JOIN person p1 ON m.member_id=p1.member_id left JOIN (select * from person where  person.primary_member = 'N') p2 on p1.member_id=p2.member_id where p1.primary_member = 'Y' and m.status = 'A' and m.member_id = '{$member}' ");
        //$query = $cDB->Query("SELECT member_id, join_date, expire_date, away_date, account_type, email_updates, balance, confirm_payments, restriction FROM ".DATABASE_MEMBERS." WHERE member_id=". $cDB->EscTxt($member));
        
        if($row = mysql_fetch_array($query))
        {       
            //$cErr->Error(print_r($row, true));
            $this->ConstructMember($row);
        }
        else
        {
            if ($redirect) {
                $cErr->Error("There was an error accessing this member (".$member.").  Please try again later.");
                include("redirect.php");
            }
            return false;
        }               
    }
}
//CT adjusted small, just load what you need of current user
class cMemberUser extends cMember {
    public function cMemberUser($values=null) {
        if(!empty($values)){
            $this->ConstructMember();
        }
    }
    public function LoadMember($member, $redirect=false) {
        global $cDB, $cErr;
        //CT barebones
/*        $query = $cDB->Query("SELECT 
                m.balance as balance, 
                m.status as status, 
                m.member_role as member_role, 
                m.expire_date as expire_date, 
                p1.first_name as first_name, 
                p1.last_name as last_name, 
                p2.first_name as p2_first_name, 
                p2.last_name as p2_last_name, 
                m.member_id as member_id, 
                FROM member m 
                left JOIN person p1 ON m.member_id=p1.member_id 
                left JOIN (select * from person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id where p1.primary_member = 'Y' and m.member_id=". $cDB->EscTxt($member));
*/        
    $query = $cDB->Query("SELECT 
                m.balance as balance, 
                m.status as status, 
                m.member_role as member_role, 
                m.expire_date as expire_date, 
                m.member_id as member_id
                FROM member m 
                where m.member_id=". $cDB->EscTxt($member));
        
        if($row = mysql_fetch_array($query))
        {       
            $this->ConstructMember($row);
            return true;
        }
        else
        {
            $cErr->Error("There was an error accessing this member (".$member.").  Please try again later.");
            return false;
        }               
    }
}


$cUser = new cMemberUser();
$cUser->RegisterWebUser();

?>
