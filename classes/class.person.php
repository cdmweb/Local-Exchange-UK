<?php

class cPerson
{
	public $person_id;			
	public $member_id;
	public $primary_member;
	public $directory_list;
	public $first_name;
	public $last_name;
	public $mid_name;
	public $dob;
	public $mother_mn;
	public $email;
	public $phone1_area;
	public $phone1_number;
	public $phone1_ext;
	public $phone2_area;
	public $phone2_number;
	public $phone2_ext;
	public $fax_area;
	public $fax_number;
	public $fax_ext;
	public $address_street1;
	public $address_street2;
	public $address_city;
	public $address_state_code;
	public $address_post_code;
	public $address_country;
	public $age;
	public $sex;
	public $about_me;

	function cPerson($values=null) {
		if($values) {
			//CT: using proper constructor to avoid duplication
			$this->SetPerson($values);	
		}
	}

	function SaveNewPerson() {
		global $cDB, $cErr;

		$duplicate_exists = $cDB->Query("SELECT NULL FROM ".DATABASE_PERSONS." WHERE member_id=". $cDB->EscTxt($this->member_id) ." AND first_name". $cDB->EscTxt2($this->first_name) ." AND last_name". $cDB->EscTxt2($this->last_name) ." AND mother_mn". $cDB->EscTxt2($this->mother_mn) ." AND mid_name". $cDB->EscTxt2($this->mid_name) ." AND dob". $cDB->EscTxt2($this->dob) .";");
		
		if($row = mysql_fetch_array($duplicate_exists)) {
			$cErr->Error("Could not save new person. There is already a person in your account with the same name, date of birth, and mother's maiden name. If you received this error after pressing the Back button, try going back to the menu and starting again.");
			include("redirect.php");
		}
	
		$insert = $cDB->Query("INSERT INTO ".DATABASE_PERSONS." (member_id, primary_member, directory_list, first_name, last_name, mid_name, dob, mother_mn, email, phone1_area, phone1_number, phone1_ext, phone2_area, phone2_number, phone2_ext, fax_area, fax_number, fax_ext, address_street1, address_street2, address_city, address_state_code, address_post_code, address_country) VALUES (". $cDB->EscTxt($this->member_id) .",". $cDB->EscTxt($this->primary_member) .",". $cDB->EscTxt($this->directory_list) .",". $cDB->EscTxt($this->first_name) .",". $cDB->EscTxt($this->last_name) .",". $cDB->EscTxt($this->mid_name) .",". $cDB->EscTxt($this->dob) .",". $cDB->EscTxt($this->mother_mn) .",". $cDB->EscTxt($this->email) .",". $cDB->EscTxt($this->phone1_area) .",". $cDB->EscTxt($this->phone1_number) .",". $cDB->EscTxt($this->phone1_ext) .",". $cDB->EscTxt($this->phone2_area) .",". $cDB->EscTxt($this->phone2_number) .",". $cDB->EscTxt($this->phone2_ext) .",". $cDB->EscTxt($this->fax_area) .",". $cDB->EscTxt($this->fax_number) .",". $cDB->EscTxt($this->fax_ext) .",". $cDB->EscTxt($this->address_street1) .",". $cDB->EscTxt($this->address_street2) .",". $cDB->EscTxt($this->address_city) .",". $cDB->EscTxt($this->address_state_code) .",". $cDB->EscTxt($this->address_post_code) .",". $cDB->EscTxt($this->address_country).");");
		
		return $insert;
	}
			
	function SavePerson() {
		global $cDB, $cErr;
		
		/*[chris]*/ // Added store personal profile data
		$update = $cDB->Query("UPDATE ". DATABASE_PERSONS ." SET member_id=". $cDB->EscTxt($this->member_id) .", primary_member=". $cDB->EscTxt($this->primary_member) .", directory_list=". $cDB->EscTxt($this->directory_list) .", first_name=". $cDB->EscTxt($this->first_name) .", last_name=". $cDB->EscTxt($this->last_name) .", mid_name=". $cDB->EscTxt($this->mid_name) .", dob=". $cDB->EscTxt($this->dob) .", mother_mn=". $cDB->EscTxt($this->mother_mn) .", email=". $cDB->EscTxt($this->email) .", phone1_area=". $cDB->EscTxt($this->phone1_area) .", phone1_number=". $cDB->EscTxt($this->phone1_number) .", phone1_ext=". $cDB->EscTxt($this->phone1_ext) .", phone2_area=". $cDB->EscTxt($this->phone2_area) .", phone2_number=". $cDB->EscTxt($this->phone2_number) .", phone2_ext=". $cDB->EscTxt($this->phone2_ext) .", fax_area=". $cDB->EscTxt($this->fax_area) .", fax_number=". $cDB->EscTxt($this->fax_number) .", fax_ext=". $cDB->EscTxt($this->fax_ext) .", address_street1=". $cDB->EscTxt($this->address_street1) .", address_street2=". $cDB->EscTxt($this->address_street2) .", address_city=". $cDB->EscTxt($this->address_city) .", address_state_code=". $cDB->EscTxt($this->address_state_code) .", address_post_code=". $cDB->EscTxt($this->address_post_code) .", address_country=". $cDB->EscTxt($this->address_country).", about_me=". $cDB->EscTxt($this->about_me) .","."age=".  $cDB->EscTxt($this->age) .",". "sex=". $cDB->EscTxt($this->sex) . " WHERE person_id=". $cDB->EscTxt($this->person_id) .";");

		if(!$update)
			$cErr->Error("Could not save changes to '". $this->first_name ." ". $this->last_name ."'. Please try again later.");	
			
		return $update;
	}

	function LoadPerson($who)
	{
		global $cDB, $cErr;
		
		/*[chris]*/ // Added fetch personal profile data
		$query = $cDB->Query("SELECT member_id, primary_member, directory_list, first_name, last_name, mid_name, dob, mother_mn, email, phone1_area, phone1_number, phone1_ext, phone2_area, phone2_number, phone2_ext, fax_area, fax_number, fax_ext, address_street1, address_street2, address_city, address_state_code, address_post_code, address_country, about_me, age, sex FROM ".DATABASE_PERSONS." WHERE person_id=". $cDB->EscTxt($who));
		
		if($row = mysql_fetch_array($query))
		{
			//pass it on
			$this->SetPerson($row);		
		}
		else 
		{
			$cErr->Error("There was an error accessing this person (".$who.").  Please try again later.");
			include("redirect.php");
		}		
	}
	//CT: should there be a getter too for full object?
	public function SetPerson($array)
    {
    	//CT grabs values directly out of full result array passed to it. you can pass a partial set, as long as you respect name of members of array
    	//$this->person = array();

		$this->setMemberId($array['member_id']);
		$this->setPrimaryMember($array['primary_member']);
		$this->setDirectoryList($array['directory_list']);
		$this->setFirstName($array['first_name']);
		$this->setLastName($array['last_name']);
		$this->setMidName($array['mid_name']);
		$this->setDob($array['dob']);
		$this->setMotherMn($array['mother_mn']);
		$this->setPhone1Area($array['phone1_area']);
		$this->setPhone1Number($array['phone1_number']);
		$this->setPhone1Ext($array['phone1_ext']);
		$this->setPhone2Area($array['phone2_area']);
		$this->setPhone2Number($array['phone2_number']);
		$this->setPhone2Ext($array['phone2_ext']);
		$this->setFaxArea($array['fax_area']);
		$this->setFaxNumber($array['fax_number']);
		$this->setPhone2Ext($array['fax_ext']);
		$this->setAddressStreet1($array['address_street1']);
		$this->setAddressStreet2($array['address_street2']);
		$this->setAddressCity($array['address_city']);
		$this->setAddressStateCode($array['address_state_code']);
		$this->setAddressPostCode($array['address_post_code']);
		$this->setAddressCountry($array['address_country']);
		// CT Chris's social vars
		$this->setAge($array['age']);
		$this->setSex($array['sex']);
		$this->setAboutMe($array['about_me']);

    }

	/**
     * @return mixed
     */
    public function getPersonId()
    {
        return $this->person_id;
    }

    /**
     * @param mixed $person_id
     *
     * @return self
     */
    public function setPersonId($person_id)
    {
        $this->person_id = $person_id;

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
    public function getPrimaryMember()
    {
        return $this->primary_member;
    }

    /**
     * @param mixed $primary_member
     *
     * @return self
     */
    public function setPrimaryMember($primary_member)
    {
        $this->primary_member = $primary_member;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDirectoryList()
    {
        return $this->directory_list;
    }

    /**
     * @param mixed $directory_list
     *
     * @return self
     */
    public function setDirectoryList($directory_list)
    {
        $this->directory_list = $directory_list;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     *
     * @return self
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     *
     * @return self
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMidName()
    {
        return $this->mid_name;
    }

    /**
     * @param mixed $mid_name
     *
     * @return self
     */
    public function setMidName($mid_name)
    {
        $this->mid_name = $mid_name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param mixed $dob
     *
     * @return self
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMotherMn()
    {
        return $this->mother_mn;
    }

    /**
     * @param mixed $mother_mn
     *
     * @return self
     */
    public function setMotherMn($mother_mn)
    {
        $this->mother_mn = $mother_mn;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone1Area()
    {
        return $this->phone1_area;
    }

    /**
     * @param mixed $phone1_area
     *
     * @return self
     */
    public function setPhone1Area($phone1_area)
    {
        $this->phone1_area = $phone1_area;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone1Number()
    {
        return $this->phone1_number;
    }

    /**
     * @param mixed $phone1_number
     *
     * @return self
     */
    public function setPhone1Number($phone1_number)
    {
        $this->phone1_number = $phone1_number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone1Ext()
    {
        return $this->phone1_ext;
    }

    /**
     * @param mixed $phone1_ext
     *
     * @return self
     */
    public function setPhone1Ext($phone1_ext)
    {
        $this->phone1_ext = $phone1_ext;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone2Area()
    {
        return $this->phone2_area;
    }

    /**
     * @param mixed $phone2_area
     *
     * @return self
     */
    public function setPhone2Area($phone2_area)
    {
        $this->phone2_area = $phone2_area;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone2Number()
    {
        return $this->phone2_number;
    }

    /**
     * @param mixed $phone2_number
     *
     * @return self
     */
    public function setPhone2Number($phone2_number)
    {
        $this->phone2_number = $phone2_number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPhone2Ext()
    {
        return $this->phone2_ext;
    }

    /**
     * @param mixed $phone2_ext
     *
     * @return self
     */
    public function setPhone2Ext($phone2_ext)
    {
        $this->phone2_ext = $phone2_ext;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFaxArea()
    {
        return $this->fax_area;
    }

    /**
     * @param mixed $fax_area
     *
     * @return self
     */
    public function setFaxArea($fax_area)
    {
        $this->fax_area = $fax_area;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFaxNumber()
    {
        return $this->fax_number;
    }

    /**
     * @param mixed $fax_number
     *
     * @return self
     */
    public function setFaxNumber($fax_number)
    {
        $this->fax_number = $fax_number;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFaxExt()
    {
        return $this->fax_ext;
    }

    /**
     * @param mixed $fax_ext
     *
     * @return self
     */
    public function setFaxExt($fax_ext)
    {
        $this->fax_ext = $fax_ext;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressStreet1()
    {
        return $this->address_street1;
    }

    /**
     * @param mixed $address_street1
     *
     * @return self
     */
    public function setAddressStreet1($address_street1)
    {
        $this->address_street1 = $address_street1;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressStreet2()
    {
        return $this->address_street2;
    }

    /**
     * @param mixed $address_street2
     *
     * @return self
     */
    public function setAddressStreet2($address_street2)
    {
        $this->address_street2 = $address_street2;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressCity()
    {
        return $this->address_city;
    }

    /**
     * @param mixed $address_city
     *
     * @return self
     */
    public function setAddressCity($address_city)
    {
        $this->address_city = $address_city;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressStateCode()
    {
        return $this->address_state_code;
    }

    /**
     * @param mixed $address_state_code
     *
     * @return self
     */
    public function setAddressStateCode($address_state_code)
    {
        $this->address_state_code = $address_state_code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressPostCode()
    {
        return $this->address_post_code;
    }

    /**
     * @param mixed $address_post_code
     *
     * @return self
     */
    public function setAddressPostCode($address_post_code)
    {
        $this->address_post_code = $address_post_code;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressCountry()
    {
        return $this->address_country;
    }

    /**
     * @param mixed $address_country
     *
     * @return self
     */
    public function setAddressCountry($address_country)
    {
        $this->address_country = $address_country;

        return $this;
    }

    /**
     * @param mixed $address_country
     *
     * @return self
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @param mixed $address_country
     *
     * @return self
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * @param mixed $address_country
     *
     * @return self
     */
    public function setAboutMe($about_me)
    {
        $this->about_me = $about_me;

        return $this;
    }		
	
	function DeletePerson() {
		global $cDB, $cErr;
		
		if($this->primary_member == 'Y') {
			$cErr->Error("Cannot delete primary member!");	
			return false;
		} 
		
		$delete = $cDB->Query("DELETE FROM ".DATABASE_PERSONS." WHERE person_id=". $cDB->EscTxt($this->person_id));
		
		unset($this->person_id);
		
		if (mysql_affected_rows() == 1) {
			return true;
		} else {
			$cErr->Error("Error deleting joint member.  Please try again later.");
		}
		
	}
							
	function ShowPerson()
	{
		$output = $this->person_id . ", " . $this->member_id . ", " . $this->primary_member . ", " . $this->directory_list . ", " . $this->first_name . ", " . $this->last_name . ", " . $this->mid_name . ", " . $this->dob . ", " . $this->mother_mn . ", " . $this->email . ", " . $this->phone1_area . ", " . $this->phone1_number . ", " . $this->phone1_ext . ", " . $this->phone2_area . ", " . $this->phone2_number . ", " . $this->phone2_ext . ", " . $this->fax_area . ", " . $this->fax_number . ", " . $this->fax_ext . ", " . $this->address_street1 . ", " . $this->address_street2 . ", " . $this->address_city . ", " . $this->address_state_code . ", " . $this->address_post_code . ", " . $this->address_country;
		
		return $output;
	}

	function Name() {
		return $this->first_name . " " .$this->last_name;	
	}
			
	function DisplayPhone($type)
	{
		global $cErr;

		switch ($type)
		{
			case "1":
				$phone_area = $this->phone1_area;
				$phone_number = $this->phone1_number;
				$phone_ext = $this->phone1_ext;
				break;
			case "2":
				$phone_area = $this->phone2_area;
				$phone_number = $this->phone2_number;
				$phone_ext = $this->phone2_ext;
				break;
			case "fax":
				$phone_area = $this->fax_area;
				$phone_number = $this->fax_number;
				$phone_ext = $this->fax_ext;
				break;								
			default:
				$cErr->Error("Phone type does not exist.");
				return "ERROR";
		}
/*		
		if($phone_number != "") {
			if($phone_area != "" and $phone_area != DEFAULT_PHONE_AREA)
				$phone = "(". $phone_area .") ";
			else
				$phone = "";
				
			$phone .= substr($phone_number,0,3) ."-". substr($phone_number,3,4);
			if($phone_ext !="")
				$phone .= " Ext. ". $phone_ext;
		} else {
			$phone = "";
		}
*/
        $phone = $phone_number;
		
		return $phone;
	}
}

// TODO: cPerson should use this class instead of a text field
class cPhone {
	var $area;
	var $prefix;
	var $suffix;
	var $ext;
	
	function cPhone($phone_str=null) { // this constructor attempts to break down free-form phone #s
		if($phone_str) {						// TODO: Use reg expressions to shorten this thing
			$ext = "";
			$phone_str = strtolower($phone_str);
			if ($loc = strpos($phone_str, "x")) {
				$ext = substr($phone_str, $loc+1, 10);
				$phone_str = substr($phone_str, 0, $loc); // strip extension off the main string
				$ext = ereg_replace("t","",$ext);
				$ext = ereg_replace("\.","",$ext);
				$ext = ereg_replace(" ","",$ext);
				if(!is_numeric($ext))
					$ext = "";
			}
			$phone_str = ereg_replace("\(","",$phone_str);
			$phone_str = ereg_replace("\)","",$phone_str);
			$phone_str = ereg_replace("-","",$phone_str);
			$phone_str = ereg_replace("\.","",$phone_str);
			$phone_str = ereg_replace(" ","",$phone_str);
			$phone_str = ereg_replace("e","",$phone_str);


			if(strlen($phone_str) == 7) {
				$this->area = DEFAULT_PHONE_AREA;
				$this->prefix = substr($phone_str,0,3);
				$this->suffix = substr($phone_str,3,4);
				$this->ext = $ext;
			} elseif (strlen($phone_str) == 10) {
				$this->area = substr($phone_str,0,3);
				$this->prefix = substr($phone_str,3,3);
				$this->suffix = substr($phone_str,6,4);
				$this->ext = $ext;				
			} else {
				return false;			
			}
		}
	}
	
	function TenDigits() {
		return $this->area . $this->prefix . $this->suffix;
	}
	
	function SevenDigits() {
		return $this->prefix . $this->suffix;
	}
	
}


/**
 * Temporary phone class for UK.  This is to be used in place of all instances
 * of "cPhone".
 */
class cPhone_uk {
	var $area;
	var $prefix;
	var $suffix;
	var $ext;
    var $number;

	// this constructor attempts to break down free-form phone #s
	function cPhone_uk($phone_str=null) { 
        // TODO: Use reg expressions to shorten this thing
		if( !empty($phone_str)) {						
            $tmp = preg_replace("/[^\d]/", "", $phone_str);

            // Most UK phone numbers when written without the areacode are 8
            // digits.
            if(strlen($tmp) >= 8) { 
                $this->number = $phone_str;
                $this->ext = "";
                $this->area = DEFAULT_PHONE_AREA;

                // We are not using them.  But they are checked in
                // verify_phone_number()
                $this->prefix = $this->suffix = true;
            }
            else {
                return false;
            }
        }
	}
	
	function TenDigits() {
		return $this->number;
	}
	
	function SevenDigits() {
		return $this->number;
	}

    

    
}

?>
