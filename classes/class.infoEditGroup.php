<?php

//CT new class - for lsiting and managing all pages
class cInfoEditGroup {
	var $permissions_below;
	var $cdm_pages;
	function __construct($vars=null){
		if (!empty($vars)){
			$this->Build($vars);
		}
	}

	function Load($permission_below=null, $active=1){
		global $cDB, $cErr, $cQueries;
			//CT permission_below should be used for sitemap - list of all pages that a person on a particular level can see
			// todo - match permissions

			$condition = " permission <= {$permission_below} AND active={$active}";
			$order_by = "title ASC";
			$query = $cDB->Query($cQueries->getMySqlInfoPage($condition, $order_by));
			
			$i = 0;				
			
			
			$vars = array();

			//get rows into array
			while($row = $cDB->FetchArray($query)) $vars[] = $row;
			$this->Build($vars);
			return true;
	}
	//batch save changes. has to be done in several parts...permission action, delete action.
	function Save($vars){
		global $cDB, $cQueries;
		if($vars['permission']){
			foreach($vars['permission'] as $permission => $select_id){
				//grouped in sub-arrays according to value
				//construct vars array
				$array = array();
				$array['permission'] = $permission;
				//construct matching condition
				$condition = "";
				$i=0;
				//append all the page ids as condition
				foreach ($select_id as $page_id){
					if($i > 0) $condition .= " OR ";
					$condition .= "page_id=\"{$page_id}\"";
					$i++;
				}
				//construct query
				$string = $cQueries->BuildUpdateQuery(DATABASE_PAGE, $array, $condition);
				// do the query. todo- make it return whether success
				$query = $cDB->Query($string);
			}
		}
		if(!empty($vars['action']) && !empty($vars['select_id'])){
			
			if($vars['action'] == "bin"){
				$array = array();
				$array['active'] = 0;
				//construct matching condition
				$condition = "";
				$i=0;
				//append all the page ids as condition
				foreach ($vars['select_id'] as $page_id){
					if($i > 0) $condition .= " OR ";
					$condition .= "page_id=\"{$page_id}\"";
					$i++;
				}
				//construct query
				$string = $cQueries->BuildUpdateQuery(DATABASE_PAGE, $array, $condition);
				// do the query. todo- make it return whether success
				$query = $cDB->Query($string);
			}
			elseif($vars['action']== "copy"){
				//CT todo
				//$string = "";
			}
			
		}

	}

	function PrepareActionDropdown(){
		global $p, $cUser;
		// CT add actions - delete, copy, change member_role
		//$vars = array("delete" => "Delete", "copy" => "Copy");
		$vars = array("bin" => "Put in bin");

		$select_name = "action";
		$output = $p->PrepareFormSelector($select_name, $vars, "-- Select --", null);
		return $output;
	}	

	function Build($vars){

      $cdm_pages = array();

        foreach($vars as $item) $cdm_pages[] = new cInfoEdit($item);
        $this->cdm_pages = $cdm_pages;  //
		if(sizeof($this->cdm_pages > 0)) return true;
		// if failed, just return
		return false;
	}
	//todo - display. should just list without controls


	
}

?>