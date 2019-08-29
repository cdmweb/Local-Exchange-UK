<?php
/*CT TODO needs cleanup. doesnt work right now. */
class cTradesPending {
	
	var $numPending = 0; // Total num trades pending
	
	var $numIn = 0; // Number of trades directed TO us that we must act on
	var $numOut = 0; // Number of trades sent FROM us that we are awaiting action on
	
	var $numToPay = 0; // Num Invoices we need to pay
	var $numToConfirm = 0; // Num payments we need to confirm
	var $numToBePayed = 0; // Num invoices awaiting payment on
	var $numToHaveConfirmed = 0; // Num payments awaiting confirmation on
	
	function  __construct($memberID) {
		
		global $cDB;
		
		// Get all trades involving this memberID that are currently marked as Open
		$query = $cDB->query("SELECT * from trades_pending where (member_id_to=".$cDB->EscTxt($memberID)." or
			member_id_from=".$cDB->EscTxt($memberID).") and status='O';");
			
		if (!$query || mysqli_num_rows($query)<1) // None found = none pending!
			return;
			
		$num_results = mysqli_num_rows($query);
		
		for ($i=0;$i<$num_results;$i++) {
			
			$row = $cDB->FetchArray($query);
		
			// Is this - An Invoice TO memberID that hasn't yet been acted on?
			if ($row["typ"]=="I" && $row["member_id_to"]==$memberID && $row["member_to_decision"]==1) {
		
				$this->numToPay += 1;
			}
			
			// Is this - A Payment TO memberID that hasn't yet been acted on?
			if ($row["typ"]=="T" && $row["member_id_to"]==$memberID && $row["member_to_decision"]==1) {
	
				$this->numToConfirm += 1;
			}
			
			// Is this - An Invoice FROM memberID that hasn't yet been acted on?
			if ($row["typ"]=="I" && $row["member_id_from"]==$memberID && $row["member_from_decision"]==1) {
			
				$this->numToBePayed += 1;
			
			}
			
			// Is this - An Payment FROM memberID that hasn't yet been acted on?
			if ($row["typ"]=="T" && $row["member_id_from"]==$memberID && $row["member_from_decision"]==1) {
				
				$this->numToHaveConfirmed += 1;
			}
			
		}
		
		$this->numIn = $this->numToPay + $this->numToConfirm;
		$this->numOut = $this->numToBePayed + $this->numToHaveConfirmed;
		$this->numPending = $this->numIn + $this->numOut;
	}

}
?>