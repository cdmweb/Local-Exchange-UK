<?php

if (!isset($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

// include_once("class.listing.php");
// include_once("class.category.php");

//group
class cCategoryGroup
{
	private $categories; // array of category

	function  __construct($vars=null) {
		if($vars) {
			$this->Build($vars);
		} 
	}
	public function Load($condition, $order_by="description ASC") {
		global $cDB, $cErr, $cQueries;
		$query = $cDB->Query($cQueries->getMySqlCategory($condition, $order_by));

		$vars = array();
		while($row = $cDB->FetchArray($query)) $vars[] = $row;
		//print_r($vars);
		$this->Build($vars);
	}
	public function Build($vars){

		$categories = array();
		$i =0;
		while($i < sizeof($vars)) {
			$categories[] = new cCategory($vars[$i]);
			$i++;
		}
		//print_r($categories);
		$this->setCategories($categories);
	}

	public function PrepareOutput(){
		$string = "";
		foreach ($this->getCategories() as $category) $string .= $category->PrepareOutput();
		return $string;
	}
	
	// getters and setters
    /**
     * @return mixed
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @param mixed $categories
     *
     * @return self
     */
    public function setCategories($categories)
    {
        $this->categories = $categories;

        return $this;
    }

    function PrepareCategoryDropdown($selector_name="category_id", $selected_id){
		global $p, $cUser;

		$array = array();


		foreach($this->getCategories() as $category) {
			//print_r($category->getCategoryName());
			$array[$category->getCategoryId()] = $category->getCategoryName();

		}
		//$selector_id, $array, $label_none=null, $selected_id=null, $css_class=null
        return $p->PrepareFormSelector($selector_name, $array, "-- Select category --", $selected_id);
		// $vars = $categories->MakeCategoryArray();

		// //print_r($vars);
		// // add extra option if user is an admin 
		// //print_r($vars);
		// $select_name = "category_id";
		// //if used in context of batch page controls
		// //if(!empty($category_id)) $select_name .= "_{$category_id}";
		// $output = $p->PrepareFormSelector($select_name, $vars, "-- Select category --", $this->getCategory());
		// return $output;
	}

    public function MakeCategoryArray() {	
    	$this->Load();
		$array = array();


		foreach($this->getCategories() as $category) {
			//print_r($category->getCategoryName());
			$array[$category->getCategoryId()] = $category->getCategoryName();

		}

		
		return $array;
	}
}

?>
