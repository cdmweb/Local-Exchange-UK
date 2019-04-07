<?php
if (!isset($global))
{
	die(__FILE__." was included directly.  This file should only be included via inc.global.php.  Include() that one instead.");
}

class cDatabase
{
	var $isConnected;
	var $db_link;
	//CT counters for stats - to show improvements in db efficiency
	var $count_connection;
	var $count_query;

	function Database() {
		$this->isConnected = false;
		//CT init counters
		$this->count_connection = 0;
		$this->count_query = 0;
	}

	// function Connect()
	// {

	// 	if ($this->isConnected){
	// 		return;
	// 	}
	// 	$link = ($GLOBALS["___mysqli_ston"] = mysqli_connect(DATABASE_SERVER,DATABASE_USERNAME,DATABASE_PASSWORD)) or die("Problem occur in connection");  

	// 	//$db = ((bool)mysqli_query($link, "USE " . info));  
	// 	$this->db_link = $link;
	// 	$this->isConnected=true;
	// 	// CT iterate
	// 	$this->count_connection++;
	// 	//print("Connection" . $this->count_connection);
	// }

	function Connect()
	{
		if(!empty(DATABASE_PORT)){
			$db_link = mysqli_connect(DATABASE_SERVER,DATABASE_USERNAME,DATABASE_PASSWORD, DATABASE_NAME, DATABASE_PORT);
		} else{
			$db_link = mysqli_connect(DATABASE_SERVER,DATABASE_USERNAME,DATABASE_PASSWORD, DATABASE_NAME);
		}
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		} else{
	//$db = ((bool)mysqli_query($link, "USE " . info));  
			$this->db_link = $db_link;
			$this->isConnected=true;
			// CT iterate
			$this->count_connection++;
			//print("Connection" . $this->count_connection);

		}
		
	}
	function Disconnect()
	{
		if ($this->isConnected){
			mysqli_close($db_link);
			$this->isConnected=false;
			//echo "disconnected";
		}
	}

	function Query($thequery)
	{
		//CT: 
		global $cErr;
		// CT iterate

		if (!$this->isConnected)
			$this->Connect();

		$ret = mysqli_query($this->db_link, $thequery);

		//CT: why is this not a resource?
		//echo(gettype($ret));
		$retmessage ="";
		if(gettype($ret) == "resource") {
			//ct debug
			$retmessage = "| R: " . mysqli_num_rows($this->db_link);
		} 

		$this->count_query++;
		$cErr->Error("Q.{$this->count_query}: {$thequery} {$retmessage}");

		return $ret;
		//CT: uncomment when finishing demo

//		       or die ("Query failed: ".mysqli_errno() . ": " . mysqli_error()); // TODO: fix error messages
		//$this->Disconnect();
		//showMessage($this->NumRows($ret));
		
	}

	function FetchArray($thequery)
	{
		if(empty($thequery)) return false;
		//potential backward compatibility 
		return mysqli_fetch_array($thequery);
	}

	function FetchObject($thequery)
	{
		if(empty($thequery)) return false;
		return mysqli_fetch_object($this->$db_link, $thequery);
	}

	function AffectedRows()
	{
		return mysqli_affected_rows($this->$db_link);
	}

	function NumRows($thequery)
	{
		if (!$this->isConnected)
			$this->Connect();

		$result = $this->Query($thequery);

		return mysqli_num_rows($this->$db_link);
	}

	function MakeSimpleTable($theQuery)
	{
		$query = $this->Query($theQuery);

		/* Printing results in HTML */
		$table = "<TABLE>\n";
		while ($line = mysqli_fetch_array($query, mysqli_ASSOC)) {
			$table .= "\t<TR>\n";
			foreach ($line as $col_value)
			{
				$table .= "\t\t<TD>$col_value</TD>\n";
			}
			$table .= "\t</TR>\n";
		}
		$table .= "</TABLE>\n";

		return $table;
	}

/*
	function EscTxt($text) {
		if($text) {
			if(MAGIC_QUOTES_ON) 
				return "'". $text ."'";
			else 
				return "'". addslashes($text) ."'";
		} else {
			return "null";
		}
	}

	function EscTxt2($text) {  // TODO: Rename to EscQueryTxt() and update through site
		if($text) {
			if(MAGIC_QUOTES_ON) 
				return "='". $text ."'";
			else 
				return "='". addslashes($text) ."'";
		} else {
			return " IS NULL";
		}
	}
*/
   // CT make safe query - now that member object is only populated with the field that the function needs, opportunity to nullify data by accident 
    function BuildUpdateQueryStringFromArray($array){
        $string = "";
        //name value pair in array creates a Update query set statement
        foreach ($array as $name => $value) {
            if(!is_null($value)) {
                if(empty($string)) {
                    $string .= "SET ";
                }else{
                    $string .= ", ";
                }
                $string .= "{$name}={$this->EscTxt($value)}";
            }
         }
        //print($string);
        return $string;
    }

	/* A HTML screening function, an optional additional security step for data being submitted for storage in MySQL */
	function ScreenHTML($var) {
		
		global $cUser,$allowedHTML;
		
		// ct using the htmlpurifier library
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$clean_html = $purifier->purify($var);
	
		return $var;
	}

	
	
	/* Takes an individual HTML tag and checks it
			$allowed - an Array containing exceptions (e.g. em, i) */
	function ProcessHTMLTag($data,$allowed) {
		
		// ending tags
		if (preg_match("/^\/([a-z0-9]+)/i", $data, $matches)){
			$name = StrToLower($matches[1]);
			if (in_array($name, array_keys($allowed))){
				return '</'.$name.'>';
			}else{
				
				return '';
			}
		}

		// starting tags
		if (preg_match("/^([a-z0-9]+)(.*?)(\/?)$/i", $data, $matches)){
			$name = StrToLower($matches[1]);
			$body = $matches[2];
			$ending = $matches[3];
			if (in_array($name, array_keys($allowed))){
				$params = "";
				preg_match_all("/ ([a-z0-9]+)=\"(.*?)\"/i", $body, 
					$matches_2, PREG_SET_ORDER);
				preg_match_all("/ ([a-z0-9]+)=([^\"\s]+)/i", $body,
					$matches_1, PREG_SET_ORDER);
				$matches = array_merge($matches_1, $matches_2);
			
				foreach($matches as $match){
					$pname = StrToLower($match[1]);
					//if (in_array($pname, $allowed[$name]) || in_array("*",$allowed[$name])){
						$params .= " $pname=\"$match[2]\"";
					//}
		
				}
				
				return '<'.$name.$params.$ending.'>';
		}
		else
			return '';
		}
	}
	
// CT cleanup - just use mysqli_real_escape_string  
	
    function EscTxt($text) {
    	return mysqli_real_escape_string($this->db_link, $text);
    }


    function EscTxt2($text) {
        if( !empty($text)) {
            if(get_magic_quotes_gpc()) {
                $text = stripslashes($text);
            }

            return "='" . mysqli_real_escape_string($this->db_link, $text) . "'";
        } else if(is_numeric($text)) {
            return "='$text'";
        } else {
            return " IS NULL";
        }
    }


	function UnEscTxt($text) {
		return stripslashes($text);
	}	



}


$cDB = new cDatabase;
?>
