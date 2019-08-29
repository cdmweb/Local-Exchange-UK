<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}



class cCategory
{
	private $parent_category_id;
	private $category_id;
	private $category_name;
   /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * @param mixed $category_id
     *
     * @return self
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getParentCategoryId()
    {
        return $this->parent_category_id;
    }

    /**
     * @param mixed $parent_category_id
     *
     * @return self
     */
    public function setParentCategoryId($parent_category_id)
    {
        $this->parent_category_id = $parent_category_id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * @param mixed $description
     *
     * @return self
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;

        return $this;
    }	
	function  __construct($vars=null) {
		if(!empty($vars)) {
			$this->Build($vars);
		}
	}	
	
	function SaveNewCategory() {
		global $cDB, $cErr;

		$insert = $cDB->Query("INSERT INTO ". DATABASE_CATEGORIES ."(parent_id, description) VALUES (". $cDB->EscTxt($this->getParentCategoryId()) .", ". $cDB->EscTxt($this->getCategoryName()) .");");
		
		if(mysqli_affected_rows() == 1) {
			$this->setCategoryId(mysqli_insert_id());
			return true;
		} else {
			return false;
		}
	}
	
	function SaveCategory() {
		global $cDB;
		
		$update = $cDB->Query("UPDATE ". DATABASE_CATEGORIES ." SET parent_id=". $cDB->EscTxt($this->getParentCategoryId()) .", description=". $cDB->EscTxt($this->getCategoryName()) ." WHERE category_id=". $cDB->EscTxt($this->getCategoryId()) .";");
		
		return $update;
	}
	
	function Load($category_id=null) {
		global $cDB, $cErr, $cQueries;
		if(empty($category_id)) $category_id = "%";
		// select description for this code
		$condition = "category_id LIKE {$category_id}";
		$order_by = "description";
		$query = $cDB->Query($cQueries->getMySqlCategory($condition, $order_by));
		
		while($vars = $cDB->FetchArray($query)){
			$this->Build($vars); 
			return true;
		}
		return false;
	}

	function Build($vars){
		if (!empty($vars['category_id'])) $this->setCategoryId($vars['category_id']);
		if (!empty($vars['parent_id'])) $this->setParentCategoryId($vars['parent_id']);
		if (!empty($vars['category_name'])) $this->setCategoryName($vars['category_name']);
	}

	function DeleteCategory() {
		global $cDB, $cErr;
	
		$delete = $cDB->Query("DELETE FROM ".DATABASE_CATEGORIES." WHERE category_id=". $cDB->EscTxt($this->getCategoryId()));
		
		if(mysqli_affected_rows() == 1) {
			//unset($this);	
			return true;
		} else {
			$cErr->Error("Could not delete category code '".$this->getCategoryId()."'.");
			include("redirect.php");
		}
	}
	//debug - print out category
	function PrepareOutput() {
		$output = "<p>{$this->$this->getCategoryId()}, {$this->getParentCategoryId()}, {$this->getCategoryNameWrap()}</p>";
		return $output;		
	}

	
	function HasListings() {
		$listings = new cListingGroup(OFFER_LISTING);
		if($listings->LoadListingGroup(null, $this->getCategoryId()))
			return true;	
			
		$listings = new cListingGroup(WANT_LISTING);
		if($listings->LoadListingGroup(null, $this->getCategoryId()))
			return true;	
			
		return false;		
	}	
} 
// cCategory
/*
class cCategoryList {
	var $category;	//Will be an array of object class cCategory

	function Load() {	
		global $cDB, $cErr, $cQueries;
		$condition = "1";
		$order_by = "description";
		$query = $cDB->Query($cQueries->getMySqlCategory($condition, $order_by));
*/
		/* 
		if($active_only) {
			$query = $cDB->Query("SELECT DISTINCT ".DATABASE_CATEGORIES.".category_id, ".DATABASE_CATEGORIES.".description FROM ".DATABASE_CATEGORIES.", ".DATABASE_LISTINGS." WHERE ".DATABASE_LISTINGS.".category_code =".DATABASE_CATEGORIES.".category_id AND status='". ACTIVE ."' AND type LIKE ". $cDB->EscTxt($type) ." ORDER BY ". DATABASE_CATEGORIES .".description;");
		} else {
			$query = $cDB->Query("SELECT category_id, description FROM ".DATABASE_CATEGORIES." ORDER BY description;");
		}
		*/
/*		
		$i = 0;
		while($row = $cDB->FetchArray($query))
		{
			$this->category[$i] = new cCategory;
			$this->category[$i]->Build($row[0]);
			$i += 1;
		}

		if($i == 0) {
			if ($redirect) {
				$cErr->Error("Error accessing a category record.");
				//include("redirect.php");			
			} else {
				return false;
			}
		}	
		return true;	
	}

	
	function MakeCategoryArray() {	
		$array = array();
		
		if(!empty($this->Load())) {
			foreach($this->category as $category) {
				$array[$category->category_id] = $category->description;
			}
		}
		
		return $array;
	}
}
*/

?>
