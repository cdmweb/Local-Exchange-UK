<?php
class cFeedbackRebuttal {
	var $rebuttal_id;
	var $rebuttal_date;
	var $feedback_id;
	var $member_author;
	var $comment;

	function __construct ($feedback_id=null, $member_id=null, $comment=null) {
		if($feedback_id) {
			$this->feedback_id = $feedback_id;
			$this->member_author = new cMember;
			$this->member_author->LoadMember($member_id);
			$this->comment = $comment;
		}
	}
	
	function SaveRebuttal () {
		global $cDB, $cErr;
		
		$insert = $cDB->Query("INSERT INTO ". DATABASE_REBUTTAL ."(rebuttal_date, member_id, feedback_id, comment) VALUES (now(), ". $cDB->EscTxt($this->member_author->member_id) .", ". $cDB->EscTxt($this->feedback_id) .", ". $cDB->EscTxt($this->comment) .");");

		if(mysqli_affected_rows() == 1) {
			$this->rebuttal_id = mysqli_insert_id();	
			$query = $cDB->Query("SELECT rebuttal_date from ". DATABASE_REBUTTAL ." WHERE rebuttal_id=". $cDB->EscTxt($this->rebuttal_id) .";");
			$row = $cDB->FetchArray($query);
			$this->rebuttal_date = $row[0];	
			return true;
		} else {
			return false;
		}	
	}
	
	function LoadRebuttal ($rebuttal_id) {
		global $cDB, $cErr;
		
		$query = $cDB->Query("SELECT rebuttal_date, feedback_id, member_id, comment FROM ".DATABASE_REBUTTAL." WHERE rebuttal_id=". $cDB->EscTxt($rebuttal_id) .";");
		
		if($row = $cDB->FetchArray($query)) {		
			$this->rebuttal_id = $rebuttal_id;		
			$this->rebuttal_date = new cDateTime($row[0]);
			$this->feedback_id = $row[1];
			$this->member_author = new cMember; 
			$this->member_author->LoadMember($row[2]);
			$this->comment = $cDB->UnEscTxt($row[3]);

			return true;
		} else {
			$cErr->Error("There was an error accessing the rebuttal table.");
			//include("redirect.php");
		}		
	}
}	


?>