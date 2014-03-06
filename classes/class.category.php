<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.listing.php");

class cCategory
{
	var $id;
	var $parent;
	var $description;
	
	function cCategory($description=null, $parent=null) {
		if($description) {
			$this->description = $description;
			$this->parent = $parent;
		}
	}	
	
	function SaveNewCategory() {
		global $cDB, $cErr;

		$insert = $cDB->Query("INSERT INTO ". DATABASE_CATEGORIES ."(parent_id, description) VALUES (". $cDB->EscTxt($this->parent) .", ". $cDB->EscTxt($this->description) .");");
		
		if(mysql_affected_rows() == 1) {
			$this->id = mysql_insert_id();
			return true;
		} else {
			return false;
		}
	}
	
	function SaveCategory() {
		global $cDB;
		
		$update = $cDB->Query("UPDATE ". DATABASE_CATEGORIES ." SET parent_id=". $cDB->EscTxt($this->parent) .", description=". $cDB->EscTxt($this->description) ." WHERE category_id=". $cDB->EscTxt($this->id) .";");
		
		return $update;
	}
	
	function LoadCategory($id) {
		global $cDB, $cErr;
	
		// select description for this code
		$query = $cDB->Query("SELECT parent_id, description FROM ".DATABASE_CATEGORIES." WHERE category_id=". $cDB->EscTxt($id) .";");
		
		if($row = mysql_fetch_array($query)) {		
			$this->id = $id;
			$this->parent = $row[0];
			$this->description = $row[1];
		} else {
			$cErr->Error("There was an error accessing category code '".$id."'.  Please try again later.");
			include("redirect.php");
		}			
	}

	function DeleteCategory() {
		global $cDB, $cErr;
	
		$delete = $cDB->Query("DELETE FROM ".DATABASE_CATEGORIES." WHERE category_id=". $cDB->EscTxt($this->id));
		
		if(mysql_affected_rows() == 1) {
			unset($this);	
			return true;
		} else {
			$cErr->Error("There was an error deleting category code '".$id."'.  Please try again later.");
			include("redirect.php");
		}
	}

	function ShowCategory() {
		$output = $this->id .", ". $this->description . "<BR>";
		
		return $output;		
	}
	
	function HasListings() {
		$listings = new cListingGroup(OFFER_LISTING);
		if($listings->LoadListingGroup(null, $this->id))
			return true;	
			
		$listings = new cListingGroup(WANT_LISTING);
		if($listings->LoadListingGroup(null, $this->id))
			return true;	
			
		return false;		
	}	
} // cCategory

class cCategoryList {
	var $category;	//Will be an array of object class cCategory

	function LoadCategoryList($active_only=false, $type="%", $redirect=false) {	
		global $cDB, $cErr;
		
		if($active_only) {
			$query = $cDB->Query("SELECT DISTINCT ".DATABASE_CATEGORIES.".category_id, ".DATABASE_CATEGORIES.".description FROM ".DATABASE_CATEGORIES.", ".DATABASE_LISTINGS." WHERE ".DATABASE_LISTINGS.".category_code =".DATABASE_CATEGORIES.".category_id AND status='". ACTIVE ."' AND type LIKE ". $cDB->EscTxt($type) ." ORDER BY ". DATABASE_CATEGORIES .".description;");
		} else {
			$query = $cDB->Query("SELECT category_id, description FROM ".DATABASE_CATEGORIES." ORDER BY description;");
		}
		
		$i = 0;
		while($row = mysql_fetch_array($query))
		{
			$this->category[$i] = new cCategory;
			$this->category[$i]->LoadCategory($row[0]);
			$i += 1;
		}

		if($i == 0) {
			if ($redirect) {
				$cErr->Error("There was an error accessing a category record.  Please try again later.");
				include("redirect.php");			
			} else {
				return false;
			}
		}	
		return true;	
	}
	
	function MakeCategoryArray($active_only=false, $type="%") {	
		$array["0"] = "";
		
		if($this->LoadCategoryList($active_only, $type)) {
			foreach($this->category as $category) {
				$array[$category->id] = $category->description;
			}
		}
		
		return $array;
	}

}

?>
