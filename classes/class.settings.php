<?php
/* 
	* class.settings.php <chris@cdmweb.co.uk>
	*
	* This class was added to handle site settings stored in MySQL.
	*
	* The MySQL method of storing settings was introduced in Version 1.0, prior to this the inc.config.php file stored all settings
	*
	* The file inc.config.php still handles some basic settings, but settings that the Administrator may wish to tinker with are now stored in MySQL and are accessible via admin.php. Doing this also negates the need for the webmaster to copy across so many settings from inc.config.php when upgrading to a new version 
*/
	
if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

class cSettings {
	private $strings; // Current site settings are stored here
	public $theSettings; // Current site settings are stored here
	//public $currentVar; // Current site settings are stored here
	
	
	// Constructor - we want to get current site settings
	function cSettings() {
		//$this->getCurrent();
		//if (($this->getStrings())){
			$this->LoadSettings();
		//}
		//print_r($this->strings);
	}

    public function getStrings()
    {
        //return $this->strings;
        return $this->strings;
    }

    /**
     * @param mixed $person
     *
     * @return self
     */
    public function setStrings($array)
    {
    	//CT grabs values directly out of full result array passed to it. you can pass a partial set, as long as you respey name of members of array
        $this->strings = $array;
    } 


	// Get and store current site settings
	public function getCurrent() {
		
		$this->retrieve();
		//print_r("called ret");
		//print_r($this->strings);
		//$this->current = Array();
		
		// Store current settings in easily accessible constants
		
		$stngs = $this->theSettings;
		
		$sql_data = array();
		
			
		foreach($stngs as $s => $ss) {
				
				if ($ss->typ=='bool') {
					
					if (strtolower($ss->current_value)=='false') {
						$ss->current_value = "";
						
					}
					else
						$ss->current_value = 1;
			
					define("".$ss->name."",((boolean) $ss->current_value));	
				}
				else if ($ss->typ=='int')
					define("".$ss->name."",((int) $ss->current_value));
				else
					define("".$ss->name."","".$ss->current_value."");
		}

	}
	//CT 
	public function getKey($keyname){
		//print($keyname);
		//print($this->getStrings());
		if(is_null($this->getStrings()[$keyname])){
			return $keyname;
		}
		return $this->getStrings()[$keyname];
	}

	// Retrieve current settings
	public function LoadSettings() {
	
		global $cDB;
		
		//$this->theSettings = Array();
		//$this->strings = Array();
		
		$q = "select name, current_value, default_value, typ from settings";
		
		$result = $cDB->Query($q);
		
		if (!$result)
			return false;
		
		$num_results = mysqli_num_rows($result);
		//foreach $row = mysqli_fetch_object($result)
		if ($num_results>0) {
			
			for ($i=0;$i<$num_results;$i++) {
				$row = mysqli_fetch_object($result);
				
				//CT would like to get the strings vars typed - like boolean, string, etc
				$this->strings[$row->name] = (!empty($row->current_value)) ? $row->current_value :  $row->default_value;
				//$this->theSettings[] = $row;
			}
		
		}
		
	}
	
	public function split_options($wh) {
		
		$options = explode(",",$wh);
		
		return $options;
	}
	
	// Save new settings
	public function update() {
		
		global $cDB;
		
		$this->retrieve();
		
		$stngs = $this->theSettings;
		
		$sql_data = array();
		
		foreach($stngs as $s => $ss) {
			
				$sql_data[''.$ss->name.''] = ''.$_REQUEST["".$ss->name.""].'';
		}
		
		foreach ($sql_data as $column => $value) {
			
			$result = $cDB->Query("update settings set current_value=".$cDB->EscTxt($value)." where name=".$cDB->EscTxt($column)."");
			
			if (!$result)
				$cErr->Error("Update failed ".mysqli_error());

				return "<font color=red>Update failed!</font>".mysqli_error();
		}
		
		$this->getCurrent(); // Refresh settings in current memory with new updated settings
		
		return "<div class='message'>Settings updated successfully.</font>";
	}
}

$site_settings = new cSettings();
?>