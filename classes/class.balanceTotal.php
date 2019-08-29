<?php
//CT TODO - merge with member group? todo where should this go...system balance
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


?>