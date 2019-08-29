<?php


class cFeedbackRebuttalGroup {
	var $rebuttals;		// will be an array of cFeedbackRebuttal objects
	var $feedback_id;
	
	function LoadRebuttalGroup($feedback_id) {
		global $cDB, $cErr;
		
		$this->feedback_id = $feedback_id;
		$query = $cDB->Query("SELECT rebuttal_id FROM ".DATABASE_REBUTTAL." WHERE feedback_id=". $cDB->EscTxt($feedback_id) ." ORDER by rebuttal_date;");		
	
		$i=0;
		while($row = $cDB->FetchArray($query))
		{
			$this->rebuttals[$i] = new cFeedbackRebuttal;			
			$this->rebuttals[$i]->LoadRebuttal($row[0]);
			$i += 1;
		}
		
		if($i == 0)
			return false;
		else
			return true;
	}
	
	function DisplayRebuttalGroup($member_about) {
		$output = "";
		foreach($this->rebuttals as $rebuttal) {
			if($member_about == $rebuttal->member_author->member_id)
				$output .= "<BR><B>Reply: </B>";
			else
				$output .= "<BR><B>Follow Up: </B>";
				
			$output .= $rebuttal->comment;
		}		
		return $output;
	}




    
}

?>