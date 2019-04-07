<?php

if (empty($global))
{
	die(__FILE__." was included without inc.global.php being included first.  Include() that file first, then you can include ".__FILE__);
}

include_once("class.listing.php");

class cCategories
{
	var $categories;
	function  __construct($cat=null) {
		$this->$categories=$cat;
	}
	function LoadCategories() {
		global $cDB, $cErr, $cCategory;
		$query = $cDB->Query("SELECT * FROM ".DATABASE_CATEGORIES." order by description;");
		while($row=$cDB->FetchArray($query)) {
			$this->categories = new cCategory($row);
		}

	}
}	
	
}

?>
