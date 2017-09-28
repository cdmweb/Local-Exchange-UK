<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.person.php");
include_once("Text/Password.php");

class cMember
{
	//CT: format as public
	public $person;  // this will be an array of cPerson class objects
	public $member_id;
	public $password;
	public $member_role;
	public $security_q;
	public $security_a;
	public $status;
	public $member_note;
	public $admin_note;
	public $join_date;
	public $expire_date;
	public $away_date;
	public $account_type;
	public $email_updates;
	public $balance;
	public $confirm_payments;
	public $restriction;

	function cMember($values=null) {
		if ($values) {
			$this->SetMember($values);
		}
	}
	/* CT getters and setters

    /**
     * @return mixed
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param mixed $person
     *
     * @return self
     */
    public function setPerson($array, $n=0)
    {
    	//CT grabs values directly out of full result array passed to it. you can pass a partial set, as long as you respey name of members of array
    	//$this->person = array();
		$this->person[$n] = new cPerson;			// instantiate new cPerson objects and set them
		$this->person[$n]->SetPerson($array);
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




	function SaveNewMember() {
		global $cDB, $cErr;	
		
		/* [chris] adjusted to store 'confirm_payments' preference */
		$insert = $cDB->Query("INSERT INTO ".DATABASE_MEMBERS." (member_id, password, member_role, security_q, security_a, status, member_note, admin_note, join_date, expire_date, away_date, account_type, email_updates, confirm_payments, balance) VALUES (". $cDB->EscTxt($this->member_id) .",sha(". $cDB->EscTxt($this->password) ."),". $cDB->EscTxt($this->member_role) .",". $cDB->EscTxt($this->security_q) .",". $cDB->EscTxt($this->security_a) .",". $cDB->EscTxt($this->status) .",". $cDB->EscTxt($this->member_note) .",". $cDB->EscTxt($this->admin_note) .",". $cDB->EscTxt($this->join_date) .",". $cDB->EscTxt($this->expire_date) .",". $cDB->EscTxt($this->away_date) .",". $cDB->EscTxt($this->account_type) .",". $cDB->EscTxt($this->email_updates) .",". $cDB->EscTxt($this->confirm_payments) .",". $cDB->EscTxt($this->balance) .");");

		return $insert;
	}

	function RegisterWebUser()
	{	
//		if (isset($_SESSION["user_login"]) and $_SESSION["user_login"] != LOGGED_OUT) {
		if (isset($_SESSION["user_login"])) {
			$this->member_id = $_SESSION["user_login"];
			$this->LoadMember($_SESSION["user_login"]);

            // Session regeneration added to boost server-side security.
            session_regenerate_id();
		}
        // Then next block has been inactivated due to security concerns.
		else {
			//$this->LoginFromCookie();
		}		
	}
	
	function LoginFromCookie()
	{
/*
		if (isset($_COOKIE["login"]) && isset($_COOKIE["pass"]))
		{
			$this->Login($_COOKIE["login"], $_COOKIE["pass"], true);
		}
*/
        return false;
	}

	function IsLoggedOn()
	{
//		if (isset($_SESSION["user_login"]) and $_SESSION["user_login"] != LOGGED_OUT)
		if (isset($_SESSION["user_login"]))
			return true;
		else
			return false;
	}

	function Login($user, $pass, $from_cookie=false) {
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
	
	function ValidatePassword($pass) {
		global $cDB;

		$query = $cDB->Query("SELECT member_id, password, member_role FROM ".DATABASE_USERS." WHERE member_id = ". $cDB->EscTxt($this->member_id) ." AND (password=sha(". $cDB->EscTxt($pass) .") OR password=". $cDB->EscTxt($pass) .");");	
		
		if($row = mysql_fetch_array($query))
			return true;
		else
			return false;
	}
	function UnlockAccount() {
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
	
	function DeactivateMember() {
		if($this->status == ACTIVE) {
			$this->status = INACTIVE;
			return $this->SaveMember();
		} else {
			return false;	
		}
	}
	
	function ReactivateMember() {
		if($this->status != ACTIVE) {
			$this->status = ACTIVE;
			return $this->SaveMember();
		} else {
			return false;	
		}
	}
	
	function ChangePassword($pass) { // TODO: Should use SaveMember and should reset $this->password
		global $cDB, $cErr;
		
		$update = $cDB->Query("UPDATE ". DATABASE_MEMBERS ." SET password=sha(". $cDB->EscTxt($pass) .") WHERE member_id=". $cDB->EscTxt($this->member_id) .";");
		
		if($update) {
			return true;
		} else {
			$cErr->Error("There was an error updating the password. Please try again later.");
			include("redirect.php");
		}
	}
	
	function GeneratePassword() {  
		return Text_Password::create(6) . chr(rand(50,57));
	}

	function DoLoginStuff($user, $pass)
	{
		global $cDB;
		
		//setcookie("login",$user,time()+60*60*24*1,"/");
		//setcookie("pass",$pass,time()+60*60*24*1,"/");

		$this->LoadMember($user);
		$_SESSION["user_login"] = $user;
	}

	function UserLoginPage() // A free-standing login page
	{
		$output = "<DIV STYLE='width=60%; padding: 5px;'><FORM ACTION=".SERVER_PATH_URL."/login.php METHOD=POST>
					<INPUT TYPE=HIDDEN NAME=action VALUE=login>
					<INPUT TYPE=HIDDEN NAME=location VALUE='".$_SERVER["REQUEST_URI"]."'>
					<TABLE class=NoBorder><TR><TD ALIGN=LEFT>Member ID:</TD><TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=12 NAME=user></TD></TR>
					<TR><TD ALIGN=LEFT>Password:</TD><TD ALIGN=LEFT><INPUT TYPE=PASSWORD SIZE=12 NAME=pass></TD></TR></TABLE>
					<DIV align=LEFT><INPUT TYPE=SUBMIT VALUE='Login'></DIV>
					</FORM></DIV>
					<BR>
					If you don't have an account, please contact us to join.
					<BR>";	
		return $output;
	}

	function UserLoginLogout() {
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

	function MustBeLoggedOn()
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


	function Logout() {
        setcookie(session_name(), session_id(), time() - 42000, '/');
		$_SESSION = array();
        session_destroy();
	}

	function MustBeLevel($level) {
		global $p;
		$this->MustBeLoggedOn(); // seems prudent to check first.

		if ($this->member_role<$level)
		{
			$page = "<DIV Class='AccessDenied'>I'm sorry, but the action attempted was unable to be processed because you don't have permissions.<BR><BR>If you would like your access level increased, please <a href='mailto:".EMAIL_ADMIN."'>email the admin</a> and ask.</DIV>";
			$p->DisplayPage($page);
			exit;

		}

	}
	
	function AccountIsRestricted() {
		
		if ($this->restriction==1)
			return true;
		
		return false;
	}

	function LoadMember($member, $redirect=true) {
		global $cDB, $cErr;

		//
		// select all Member data and populate the properties
		//
		/*[chris] adjusted to retrieve 'confirm_payments' */
		$query = $cDB->Query("SELECT member_id, password, member_role, security_q, security_a, status, member_note, admin_note, join_date, expire_date, away_date, account_type, email_updates, balance, confirm_payments, restriction FROM ".DATABASE_MEMBERS." WHERE member_id=". $cDB->EscTxt($member));
		
		if($row = mysql_fetch_array($query))
		{		
			$this->SetMember($row);
		}
		else
		{
			if ($redirect) {
				$cErr->Error("There was an error accessing this member (".$member.").  Please try again later.");
				include("redirect.php");
			}
			return false;
		}	
						
		//
		// Select associated person records and load into person object array
		//

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
		}
		return true;
	}
	function SetMember($array){
		$this->setPerson($array);  // this will be an array of cPerson class objects
		$this->setMemberId($member_id);  
		$this->setPassword($password);  
		$this->setMemberRole($member_role);  
		$this->setSecurityQ($security_q);  
		$this->setSecurityA($security_a);  
		$this->setStatus($status);  
		$this->setMemberId($member_id);  
		$this->setMemberNote($member_note);  
		$this->setAdminNote($admin_note);  
		$this->setJoinDate($join_date);  
		$this->setExpireDate($expire_date);  
		$this->setAwayDate($away_date);  
		$this->setAccountType($account_type);  
		$this->setEmailUpdates($email_updates);  
		$this->setBalance($balance);  
		$this->setRestriction($restriction);  
	}

	function ShowMember()
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
	
	function UpdateBalance($amount) {
		$this->balance += $amount;
		return $this->SaveMember();
	}
	
	function SaveMember() {
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
	
	function PrimaryName () {
		return $this->person[0]->first_name . " " . $this->person[0]->last_name;
	}
	
	function VerifyPersonInAccount($person_id) { // Make sure hacker didn't manually change URL
		global $cErr;
		foreach($this->person as $person) {
			if($person->person_id == $person_id)
				return true;
		}
		$cErr->Error("Invalid person id in URL.  This break-in attempt has been reported.",ERROR_SEVERITY_HIGH);
		include("redirect.php");
	}
	
	function PrimaryAddress () {
		if($this->person[0]->address_street1 != "") {
			$address = $this->person[0]->address_street1 . ", ";
			if($this->person[0]->address_street2 != "")
				$address .= $this->person[0]->address_street2 . ", ";
		} else {
			$address = "";
		}
		
		return $address . $this->person[0]->address_city;
	}
	
	function AllNames () {
		foreach($this->person as $person) {
			if($person->primary_member == "Y") {
				$names = $person->first_name ." ". $person->last_name;
			} else {
				$names .= ", ". $person->first_name ." ". $person->last_name;
			}	
		}
		return $names;
	}

	function AllPhones () {
		$phones = "";
		$reg_phones[]="";
		$fax_phones[]="";
		foreach($this->person as $person) {
			if($person->primary_member == "Y") {
				if($person->phone1_number != "") {
					$phones .= $person->DisplayPhone(1);
					$reg_phones[] = $person->DisplayPhone(1);
				}
				if($person->phone2_number != "") {
					$phones .= ", ". $person->DisplayPhone(2);
					$reg_phones[] = $person->DisplayPhone(2);
				}
				if($person->fax_number != "") {
					$phones .= ", ". $person->DisplayPhone("fax"). " (Fax)";
					$fax_phones[] = $person->DisplayPhone("fax");
				}
			} else {
				if($person->phone1_number != ""){ 
					$phones .= ", ". $person->DisplayPhone(1). " (". $person->first_name .")";
					$reg_phones[] = $person->DisplayPhone(1);
				}
				if($person->phone2_number != "") {
					$phones .= ", ". $person->DisplayPhone(2). " (". $person->first_name .")";
					$reg_phones[] = $person->DisplayPhone(2);
				}
				if($person->fax_number != "") {
					$phones .= ", ". $person->DisplayPhone("fax"). " (". $person->first_name ."'s Fax)";
					$fax_phones[] = $person->DisplayPhone("fax");
				}
			}	
		}
		return $phones;		
	}
	
	function AllEmails () {
		$emails='';
		foreach($this->person as $person) {
			if(!empty($person->email)){
				$email = "<a href='email.php?email_to={$person->email}&member_to={$this->member_id}'>11{$person->email}</a>";
				if ($person->primary_member == "Y") {
					$emails .="{$email}";
				} else{
					$emails .=", {$email} ({$person->first_name})";
				}
			}
		}
		return $emails;	
	}
	
	function VerifyMemberExists($member_id) {
		global $cDB;
	
		$query = $cDB->Query("SELECT NULL FROM ".DATABASE_MEMBERS." WHERE member_id=". $cDB->EscTxt($member_id));
		
		if($row = mysql_fetch_array($query))
			return true;
		else
			return false;
	}
	
	function MemberLink () {
		return "<A HREF=member_summary.php?member_id=". $this->member_id .">". $this->member_id ."</A>";
	}
	
	/*[chris] this function looks up the image for member ($mID) and places it in a HTML img tag */
	function DisplayMemberImg($mID,$typ=false) {
		
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
		
		$num_results = mysql_num_rows($query);
		
		if ($num_results>0) {
			
			$row = mysql_fetch_array($query);
			$imgLoc = 'uploads/'. stripslashes($row["filename"]);
	
			return 	"<img src='".$imgLoc."'><BR>";	
		}
		else
			return 	" ";
	}
	
	function DisplayMember () {
		
		/*[CDM] Added in image, placed all this in 2 column table, looks tidier */
		
		global $cDB,$agesArr,$sexArr;
		
		$output .= "<table width=100%><tr valign=top><td width=50%>";
		
		$output .= "<STRONG>Member:</STRONG> ". $this->PrimaryName() . " (". $this->MemberLink().")"."<BR>";
		$stats = new cTradeStats($this->member_id);
		$output .= "<STRONG>Activity:</STRONG> ";
		if ($stats->most_recent == "")
			$output .= "No exchanges yet<BR>";
		else		
			$output .= '<A HREF="trade_history.php?mode=other&member_id='. $this->member_id .'">'. $stats->total_trades ." exchanges total</A> for a sum of ". $stats->total_units . " ". strtolower(UNITS) . ", last on ". $stats->most_recent->ShortDate() ."<BR>";
		$feedbackgrp = new cFeedbackGroup;
		$feedbackgrp->LoadFeedbackGroup($this->member_id);
		if(isset($feedbackgrp->feedback)) {
			$output .= "<b>Feedback:</b> <A HREF=feedback_all.php?mode=other&member_id=". $this->member_id . ">" . $feedbackgrp->PercentPositive() . "% positive</A> (" . $feedbackgrp->TotalFeedback() . " total, " . $feedbackgrp->num_negative ." negative & " . $feedbackgrp->num_neutral . " neutral)<BR>";		
		}

		$joined = new cDateTime($this->join_date);
		$output .= "<STRONG>Joined:</STRONG> ". $joined->ShortDate() ."<BR>";

		if($this->person[0]->email != "")
			$output .= "<STRONG>Email:</STRONG> ". "<A HREF=email.php?email_to=". $this->person[0]->email ."&member_to=". $this->member_id .">". $this->person[0]->email ."</A><BR>";	
		if($this->person[0]->phone1_number != "")
			$output .= "<STRONG>Primary Phone:</STRONG> ". $this->person[0]->DisplayPhone("1") ."<BR>";
		if($this->person[0]->phone2_number != "")
			$output .= "<STRONG>Secondary Phone:</STRONG> ". $this->person[0]->DisplayPhone("2") ."<BR>";						
		if($this->person[0]->fax_number != "")
			$output .= "<STRONG>Fax:</STRONG> ". $this->person[0]->DisplayPhone("fax") ."<BR>";	
		if($this->person[0]->address_street2 != "") {
            $output .= "<STRONG>" . ADDRESS_LINE_2 . ": </STRONG>" .
                           $this->person[0]->address_street2 . "<BR>";
        }
		if($this->person[0]->address_city != "") {
            $output .= "<STRONG>" . ADDRESS_LINE_3 . ": </STRONG>" .
                           $this->person[0]->address_city . "<BR>";
        }
		if($this->person[0]->address_post_code != "") {
            $output .= "<STRONG>" . ZIP_TEXT . ": </STRONG>" .
                           $this->person[0]->address_post_code . "<BR>";
        }


		foreach($this->person as $person) {
			if($person->primary_member == "Y")
				continue;	// Skip the primary member, since we already displayed above
		
			if($person->directory_list == "Y") {
				$output .= "<BR><STRONG>Joint Member:</STRONG> ". $person->first_name ." ". $person->last_name ."<BR>";
				if($person->email != "")
					$output .= "<STRONG>". $person->first_name ."'s Email:</STRONG> ". "<A HREF=email.php?email_to=". $person->email ."&member_to=". $this->member_id .">". $person->email ."</A><BR>";				
				if($person->phone1_number != "")
					$output .= "<STRONG>". $person->first_name ."'s Phone:</STRONG> ". $person->DisplayPhone("1") ."<BR>";
				if($person->phone2_number != "")
					$output .= "<STRONG>". $person->first_name ."'s Secondary Phone:</STRONG> ". $person->DisplayPhone("2") ."<BR>";						
				if($person->fax_number != "")
				$output .= "<STRONG>". $person->first_name ."'s Fax:</STRONG> ". $person->DisplayPhone("fax") ."<BR>";				
			}
		}		
	
	$output .= "</td><td width=50% align=center>";
		
	$output .= cMember::DisplayMemberImg($this->member_id);	
		
	$output .= "</td></tr></table>";
	
	if (SOC_NETWORK_FIELDS==true) {
	
		$output .= "<p><STRONG><I>PERSONAL INFORMATION</I></STRONG><P>";
		
		$pAge = (strlen($this->person[0]->age)<1) ? 'Unspecified' : $agesArr[$this->person[0]->age];
		$pSex = (!$this->person[0]->sex) ? 'Unspecified' : $sexArr[$this->person[0]->sex];
		$pAbout = (!stripslashes($this->person[0]->about_me)) ? '<em>No description supplied.</em>' : stripslashes($this->person[0]->about_me);
		
		$output .= "<STRONG>Age:</STRONG> ".$pAge."<br>";
		
		$output .= "<STRONG>Sex:</STRONG> ".$pSex."<p>";
		
		$output .= "<STRONG>About Me:</STRONG><p> ".$pAbout."<br>";
	}

	return $output;	
	}
	
	function MakeJointMemberArray() {
		global $cDB;
		
		$names = array();
		foreach ($this->person as $person) {
			if($person->primary_member != 'Y') {
				$names[$person->person_id] = $person->first_name ." ". $person->last_name;
				}
		}
		
		return $names;	
	}		
	
	function DaysSinceLastTrade() {
		global $cDB;
	
		$query = $cDB->Query("SELECT max(trade_date) FROM ". DATABASE_TRADES ." WHERE member_id_to=". $cDB->EscTxt($this->member_id) ." OR member_id_from=". $cDB->EscTxt($this->member_id) .";");
		
		$row = mysql_fetch_array($query);
		
		if($row[0] != "")
			$last_trade = new cDateTime($row[0]);
		else
			$last_trade = new cDateTime($this->join_date);

		return $last_trade->DaysAgo();
	}
	
	function DaysSinceUpdatedListing() {
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
	
	function MakeIDArray() {
		global $cDB, $cErr;
		
		$ids="";		
		if($this->members) {
			foreach($this->members as $member) {
					$ids[$member->member_id] = $member->PrimaryName() ." (". $member->member_id .")";
			}		
		}
		
		return $ids;	
	}	
	
	function MakeNameArray() {
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
	
	function DoNamePicker() {
		
		$tmp = '<script src=includes/autocomplete.js></script>';
		
		$mems = $this->MakeNameArray();
		
		$tmp .= "<select name=member_to>
			<option id=0 value=0>".count($mems)." matching members...</option>";
		
		foreach($mems as $key=>$value) {
			
			$tmp .= "<option id='".$key."' value='".$key."'>".$value."</option>";
		}
		
		$tmp .= "</select>";
//		$form->addElement("select", "member_to", "...", $name_list->MakeNameArray());
		$tmp .= '<input type=text size=20 name=picker value="Member search" onKeyUp="autoComplete(this,document.all.member_to,\'text\')"
			onFocus="this.value=\'\'">
			<!--<input type=button value="Update Dropdown List">-->';
		return $tmp;
	}
	
	// Use of this function requires the inclusion of class.listing.php
	function EmailListingUpdates($interval) {
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
	function ExpireListings4InactiveMembers() {
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
				
				if($offered_exist or $wanted_exist)	{
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

	function MakeMenuArrays() {
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
	
	function Balanced() {
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
	
	function getTie($member_id) {
		
		global $cDB;
		
		$q = "select * from income_ties where member_id=".$cDB->EscTxt($member_id)." limit 0,1";
		$result = $cDB->query($q);
		
		if (!$result)
			return false;
		
		$row = mysql_fetch_object($result);
		
		return $row;
	}
	
	function saveTie($data) {
		
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
	
	function deleteTie($member_id) {
		
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

$cUser = new cMember();
$cUser->RegisterWebUser();

?>
