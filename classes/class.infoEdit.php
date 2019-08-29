<?php 
class cInfoEdit extends cInfo {
	//whether create or edit
	var $form_action; 


	function Build($vars) {
		parent::Build($vars);
		//add extra class
		if($vars['form_action']) $this->form_action = $vars['form_action'];
	}
	function PreparePermissionDropdown($page_id=null){
		global $p, $cUser;
		$vars = array("0" => "Guests", "1" => "Members", "2" => "Committee");
		// add extra option if user is an admin 
		if($cUser->getMemberRole() > 1) {
			$vars[3] = "Administrators";
		}
		$select_name = "permission";
		//if used in context of batch page controls
		if(!empty($page_id)) $select_name .= "_{$page_id}";
		$output = $p->PrepareFormSelector($select_name, $vars, null, $this->permission);
		return $output;
	}
	
	function PrepareCheckbox(){
		return "<input type=\"checkbox\" id=\"select_id[]\" name=\"select_id[]\" value=\"{$this->page_id}\" />";
	}

	function Display(){

		$output = "
		<form action=\"/members/pages_edit.php?page_id={$this->page_id}\" method=\"post\" name=\"\" id=\"\" class=\"layout2\">
			<input type=\"hidden\" id=\"page_id\" name=\"page_id\" value=\"{$this->page_id}\" />
			<input type=\"hidden\" id=\"form_action\" name=\"form_action\" value=\"{$this->form_action}\" />
			<!-- <input type=\"hidden\" id=\"active\" name=\"active\" value=\"1\" /> -->
			<input type=\"hidden\" id=\"active\" name=\"active\" value=\"{$this->active}\" />
			<input type=\"hidden\" id=\"member_id_author\" name=\"member_id_author\" value=\"{$this->member_id_author}\" />
			<p>
				<label for=\"permission\">This page can be seen by<br />
					{$this->PreparePermissionDropdown()}
				</label>
			</p>
			<p>
				<label for=\"title\">
					Title *<br />
					<input maxlength=\"200\" name=\"title\" id=\"title\" type=\"text\" value=\"{$this->title}\">
				</label>
			</p>
			<p>
				<label for=\"body\">Content *<br />
					<textarea cols=\"80\" rows=\"20\" wrap=\"soft\" name=\"body\" id=\"body\">{$this->body}</textarea>
				</label>
			</p>
			<p>
				<input name=\"submit\" id=\"submit\" value=\"Submit\" type=\"submit\" />
				* denotes a required field
			</p>
		</form>";
		return $output;

	}
	function Save () {
		global $p, $cErr, $cDB, $cQueries;
		$isSuccess = 0;
		//can handle both create and update
		if($this->form_action == "update"){
			//construct vars array
			$vars = array();
			$vars['title'] = $this->title;
			$vars['body'] = $this->body;
			$vars['member_id_author'] = $this->member_id_author;
			$vars['permission'] = $this->permission;
			//construct matching condition
			$condition = "page_id=\"{$this->page_id}\"";
			//construct query
			$query = $cDB->BuildUpdateQuery(DATABASE_PAGE, $vars, $condition);
			// do the query.
			$isSuccess = $cDB->Query($query);
		} 
		elseif($this->form_action == "create"){
			//CT returns last used id for display
			$last_used_id = $cDB->QueryReturnId("INSERT INTO `lets_cdm_pages`(`title`, `body`, `active`, `permission`, `member_id_author`) VALUES (
				\"{$cDB->EscTxt($this->title)}\",
				\"{$cDB->EscTxt($this->body)}\",
				1,
				{$this->permission},
				\"{$this->member_id_author}\")");
			if(is_int($last_used_id)){
				$this->page_id = $last_used_id;
				$isSuccess = 1;
			}
		}
		//something else?
		
		//$isSuccess = $cDB->Query("UPDATE ". DATABASE_PAGE . " SET `title`=\"{$cDB->EscTxt($this->title)}\" `updated_at`=CURRENT_TIMESTAMP WHERE `id`=\"{$this->page_id}\");
		
		return $isSuccess;
		
	}
}

?>