<?php
class cMemberIncomeTies extends cMember {
	
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

?>