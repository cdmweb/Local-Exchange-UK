<?php

class cPerson
{
	private $person_id;			
	private $member_id;
	private $primary_member;
	private $directory_list;
	private $first_name;
	private $last_name;
	//private $mid_name;
	//private $dob;
	//private $mother_mn;
	private $email;
	//private $phone1_area;
	private $phone1_number;
	//private $phone1_ext;
	//private $phone2_area;
	//private $phone2_number;
	//private $phone2_ext;
	//private $fax_area;
	//private $fax_number;
	//private $fax_ext;
	private $address_street1;
	private $address_street2;
	private $address_city;
	private $address_state_code;
    private $address_post_code;
    private $safe_post_code;
	private $address_country;
	private $age;
	private $sex;
	private $about_me;

    public function cPerson($values=null) {
        if ($values) {
            $this->ConstructPerson($values);
        }
    }
	public function SaveNewPerson() {
		global $cDB, $cErr;

		$duplicate_exists = $cDB->Query("SELECT NULL FROM ".DATABASE_PERSONS." WHERE member_id=". $cDB->EscTxt($this->member_id) ." AND first_name". $cDB->EscTxt2($this->first_name) ." AND last_name". $cDB->EscTxt2($this->last_name) ." AND mother_mn". $cDB->EscTxt2($this->mother_mn) ." AND mid_name". $cDB->EscTxt2($this->mid_name) ." AND dob". $cDB->EscTxt2($this->dob) .";");
		
		if($row = mysqli_fetch_array($duplicate_exists)) {
			$cErr->Error("Could not save new person. There is already a person in your account with the same name, date of birth, and mother's maiden name. If you received this error after pressing the Back button, try going back to the menu and starting again.");
			include("redirect.php");
		}
	
		$insert = $cDB->Query("INSERT INTO ".DATABASE_PERSONS." (member_id, primary_member, directory_list, first_name, last_name, mid_name, dob, mother_mn, email, phone1_area, phone1_number, phone1_ext, phone2_area, phone2_number, phone2_ext, fax_area, fax_number, fax_ext, address_street1, address_street2, address_city, address_state_code, address_post_code, address_country) VALUES (". $cDB->EscTxt($this->member_id) .",". $cDB->EscTxt($this->primary_member) .",". $cDB->EscTxt($this->directory_list) .",". $cDB->EscTxt($this->first_name) .",". $cDB->EscTxt($this->last_name) .",". $cDB->EscTxt($this->mid_name) .",". $cDB->EscTxt($this->dob) .",". $cDB->EscTxt($this->mother_mn) .",". $cDB->EscTxt($this->email) .",". $cDB->EscTxt($this->phone1_area) .",". $cDB->EscTxt($this->phone1_number) .",". $cDB->EscTxt($this->phone1_ext) .",". $cDB->EscTxt($this->phone2_area) .",". $cDB->EscTxt($this->phone2_number) .",". $cDB->EscTxt($this->phone2_ext) .",". $cDB->EscTxt($this->fax_area) .",". $cDB->EscTxt($this->fax_number) .",". $cDB->EscTxt($this->fax_ext) .",". $cDB->EscTxt($this->address_street1) .",". $cDB->EscTxt($this->address_street2) .",". $cDB->EscTxt($this->address_city) .",". $cDB->EscTxt($this->address_state_code) .",". $cDB->EscTxt($this->address_post_code) .",". $cDB->EscTxt($this->address_country).");");
		
		return $insert;
	}
			
	public function SavePerson() {
		global $cDB, $cErr;
		
		/*[chris]*/ // Added store personal profile data
        //print("stuff" .$this->getPersonId());
        //CT - converted to array so we dont have to set fiedls that are not present
        $fieldArray = Array();
        $fieldArray["member_id"] = $this->getMemberId();
        $fieldArray["primary_member"]=$this->getPrimaryMember(); 
        $fieldArray["directory_list"]=$this->getDirectoryList(); 
        $fieldArray["first_name"]=$this->getFirstName(); 
        $fieldArray["last_name"]=$this->getLastName(); 
        $fieldArray["mid_name"]=$this->getMidName(); 
        $fieldArray["dob"]=$this->getDob(); 
        $fieldArray["mother_mn"]=$this->getMotherMn(); 
        $fieldArray["email"]=$this->getEmail(); 
        $fieldArray["phone1_area"]=$this->getPhone1Area(); 
        $fieldArray["phone1_number"]=$this->getPhone1Number(); 
        $fieldArray["phone1_ext"]=$this->getPhone1Ext(); 
        $fieldArray["phone2_area"]=$this->getPhone2Area(); 
        $fieldArray["phone2_number"]=$this->getPhone2Number(); 
        $fieldArray["phone2_ext"]=$this->getPhone2Ext(); 
        $fieldArray["fax_area"]=$this->getFaxArea(); 
        $fieldArray["fax_number"]=$this->getFaxNumber(); 
        $fieldArray["fax_ext"]=$this->getFaxExt; 
        $fieldArray["address_street1"]=$this->getAddressStreet1; 
        $fieldArray["address_street2"]=$this->getAddressStreet2; 
        $fieldArray["address_city"]=$this-> getAddressCity(); 
        $fieldArray["address_state_code"]=$this->getAddressStateCode(); 
        $fieldArray["address_post_code"]=$this->getAddressPostcode();
        $fieldArray["address_country"]=$this->getAddressCountry(); 
        $fieldArray["about_me"]=$this->getAboutMe();
        $fieldArray["age"]=$this->getAge();
        $fieldArray["sex"]=$this->getSex();

        
        $string = $cDB->BuildUpdateQueryStringFromArray($fieldArray);

        $update = $cDB->Query("UPDATE ".DATABASE_PERSONS. " {$string} WHERE person_id=". $cDB->EscTxt($this->getPersonId()) .";");  

		if(!empty($update))
			$cErr->Error("Could not save changes to '". $this->first_name ." ". $this->last_name ."'. Please try again later.");	
			
		return $update;
	}

	public function LoadPerson($who)
	{
		global $cDB, $cErr;
		
		/*[chris]*/ // Added fetch personal profile data
		$query = $cDB->Query("SELECT person_id, member_id, primary_member, directory_list, first_name, last_name, mid_name, dob, mother_mn, email, phone1_area, phone1_number, phone1_ext, phone2_area, phone2_number, phone2_ext, fax_area, fax_number, fax_ext, address_street1, address_street2, address_city, address_state_code, address_post_code, address_country, about_me, age, sex FROM ".DATABASE_PERSONS." WHERE person_id=". $cDB->EscTxt($who));
		
		if($row = mysqli_fetch_array($query))
		{
			//pass it on
			$this->ConstructPerson($row);		
		}
		else 
		{
			$cErr->Error("There was an error accessing this person (".$who.").  Please try again later.");
			include("redirect.php");
		}		
	}
	//CT: todo - fixit! should be __Construct
	private function ConstructPerson($array=null) 
    {
    	//CT grabs values directly out of full result array passed to it. you can pass a partial set

        $this->setPersonId($array['person_id']);
        $this->setMemberId($array['member_id']);
        $this->setPrimaryMember($array['primary_member']);
        $this->setDirectoryList($array['directory_list']);
        $this->setFirstName($array['first_name']);
        $this->setLastName($array['last_name']);
        //$this->setMidName($array['mid_name']);
        //$this->setDob($array['dob']);
        $this->setEmail($array['email']);
        //$this->setMotherMn($array['mother_mn']);
        //$this->setPhone1Area($array['phone1_area']);
        $this->setPhone1Number($array['phone1_number']);
        //$this->setPhone1Ext($array['phone1_ext']);
        //$this->setPhone2Area($array['phone2_area']);
        //$this->setPhone2Number($array['phone2_number']);
        //$this->setPhone2Ext($array['phone2_ext']);
        //$this->setFaxArea($array['fax_area']);
        //$this->setFaxNumber($array['fax_number']);
        //$this->setFaxExt($array['fax_ext']);
        $this->setAddressStreet1($array['address_street1']);
        $this->setAddressStreet2($array['address_street2']);
        $this->setAddressCity($array['address_city']);
        $this->setAddressStateCode($array['address_state_code']);
        $this->setAddressPostCode($array['address_post_code']);
        $this->setSafePostCode($array['address_post_code']);
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
        print('getPersonId' . $this->person_id);
        return $this->person_id;
    }

    /**
     * @param mixed $person_id
     *
     * @return self
     */
    public function setPersonId($person_id)
    {
        //print($person_id);
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
    public function getSafePostCode()
    {
        return $this->safe_post_code;
    }

    /**
     * @param mixed $address_post_code
     *
     * @return self
     */
    public function setSafePostCode($zip)
    {
        if (DEFAULT_COUNTRY == "United Kingdom"){
            $postParts = preg_split("([ /-/_])", $zip);
            $zip = $postParts[0];
            // CT: hack. just in case postcode has been put in without spaces or other dividers
            if (strlen($zip) > 4) {
                $zip = substr($zip, 0, 3);
            }
        } 
        $this->safe_post_code = $zip;

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
     * @return mixed
     */
    public function getAge()
    {
        return $this->age;
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
     * @return mixed
     */
    public function getSex()
    {
        return $this->sex;
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
     * @return mixed
     */
    public function getAboutMe()
    {
        return $this->about_me;
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
		
		$delete = $cDB->Query("DELETE FROM ".DATABASE_PERSONS." WHERE person_id=". $cDB->EscTxt($this->getPersonId()));
		
		unset($this->person_id);
		
		if (mysqli_affected_rows() == 1) {
			return true;
		} else {
			$cErr->Error("Error deleting joint member.");
		}
		
	}
							
	function ShowPerson()
	{
		$output = $this->getPersonId() . ", " . $this->getMemberId() . ", " . $this->getPrimaryMember() . ", " . $this->getDirectoryList() . ", " . $this->getFirstName() . ", " . $this->getLastName() . ", " . $this->getPersonId() . ", " . $this->dob . ", " . $this->mother_mn . ", " . $this->email . ", " . $this->phone1_area . ", " . $this->phone1_number . ", " . $this->phone1_ext . ", " . $this->phone2_area . ", " . $this->phone2_number . ", " . $this->phone2_ext . ", " . $this->fax_area . ", " . $this->fax_number . ", " . $this->fax_ext . ", " . $this->address_street1 . ", " . $this->address_street2 . ", " . $this->address_city . ", " . $this->address_state_code . ", " . $this->address_post_code . ", " . $this->address_country;
		
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
				$phone_area = $this->getPhone1Area();
				$phone_number = $this->getPhone1Number();
				$phone_ext = $this->getPhone1Ext();
				break;
			case "2":
                $phone_area = $this->getPhone2Area();
                $phone_number = $this->getPhone2Number();
                $phone_ext = $this->getPhone2Ext();
				break;
			case "fax":
                $phone_area = $this->getFaxArea();
                $phone_number = $this->getFaxNumber();
                $phone_ext = $this->getFaxExt();
				break;								
			default:
				$cErr->Error("Phone type does not exist.");
				return "ERROR";
		}

        $phone = $phone_number;
		
		return $phone_number;
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
class cSecondPerson extends cPerson {
    public function cSecondPerson($values=null) {
        if ($values) {
            $this->ConstructSecondPerson($values);
        }
    }
  
    private function ConstructSecondPerson($array=null) 
    {
        //CT grabs values directly out of full result array passed to it. you can pass a partial set
        $this->setMemberId($array['member_id']);
        $this->setPrimaryMember('N');
        $this->setDirectoryList($array['p2_directory_list']);
        $this->setPersonId($array['p2_person_id']);
        $this->setFirstName($array['p2_first_name']);
        $this->setLastName($array['p2_last_name']);
        $this->setMidName($array['p2_mid_name']);
        $this->setDob($array['p2_dob']);
        $this->setEmail($array['p2_email']);
        $this->setMotherMn($array['p2_mother_mn']);
        $this->setPhone1Area($array['p2_phone1_area']);
        $this->setPhone1Number($array['p2_phone1_number']);
        $this->setPhone1Ext($array['p2_phone1_ext']);
        $this->setPhone2Area($array['p2_phone2_area']);
        $this->setPhone2Number($array['p2_phone2_number']);
        $this->setPhone2Ext($array['p2_phone2_ext']);
        $this->setFaxArea($array['p2_fax_area']);
        $this->setFaxNumber($array['p2_fax_number']);
        $this->setPhone2Ext($array['p2_fax_ext']);
        $this->setDirectoryList($array['p2_directory_list']);
        // CT Chris's social vars
        $this->setAge($array['p2_age']);
        $this->setSex($array['p2_sex']);
        $this->setAboutMe($array['p2_about_me']);

    }
}

?>
