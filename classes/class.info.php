<?php

class cInfo {
		var $page_id; 
		var $title;
		var $body;
		var $date; //should be created_date
		var $active;
		var $permission; 

		//CT new

		var $created_at; 
		var $updated_at;
		var $member_id_author; // should be stored in a different table with updates 

		//CT put in standard construct and load functions for completeness
		//CT rewrite
		function __construct($vars=null){
			//can pass vars directly
			if(!empty($vars)) $this->Build($vars);
		}
		function Load($page_id, $active=1) {

			global $cDB, $cErr, $cQueries;
			// hould use page_id. by default dont show inactive ones
			$condition = "page_id={$page_id} AND active={$active}";
			$order_by = "page_id ASC";

			$query = $cDB->Query($cQueries->getMySqlInfoPage($condition, $order_by));
	
			while($row = $cDB->FetchArray($query)) {
				$this->Build($row);
				return true;
			}
			return false; //failed
		}
		function Build($vars) {
			global $cDB, $cErr;
			//TODO: CT vars on class should use getters and setters
			if($vars['page_id']) $this->page_id = $vars['page_id'];
			if(isset($vars['title'])) $this->title = $vars['title'];
			if(isset($vars['body'])) $this->body = $this->tidyHTML($vars['body']);
			if(isset($vars['date'])) $this->date = $vars['date'];
			if(isset($vars['active'])) $this->active = $vars['active'];
			if(isset($vars['permission'])) $this->permission = $vars['permission'];
			//if($vars['created_at']) $this->created_at = $vars['created_at'];
			if(isset($vars['created_at'])) $this->created_at = $vars['created_at'];
			if(isset($vars['updated_at'])) $this->updated_at = $vars['updated_at'];
			if(isset($vars['member_id_author'])) $this->member_id_author = $vars['member_id_author'];
		}

		function tidyHTML($html) {
			global $cDB;
			return $cDB->ScreenHTML($html);
		}
		function getExtract($extract_length=80){

			$extract = strip_tags($this->body);
			$extract = trim($extract);
			return substr($extract, 0, $extract_length) . "...";
		}
		function Display(){
			global $cUser, $p;
			$string = "";
			$clean_text = $this->tidyHTML($this->body);
			//CT show page
			if(!empty($this->page_id)){
				if ($cUser->getMemberRole() > 0){
					//CT move edit button to page class
					$string.= "<div class=\"edit\"><a href=\"pages_edit.php?page_id={$this->page_id}\" class=\"edit\"><i class=\"fas fa-pencil-alt\"></i> edit</a></div>";
				}
				//CT put this in permissions object
				switch($this->permission){
					case '0':
						$role_string="";
					break;
					case '1':
						$role_string="This page can only be seen when logged in.";
					break;
					case '2':
						$role_string="This page can only be seen by committee and admins.";
					break;
					case '3':
						$role_string="This page can only be seen by admins.";
					break;
				}
				$authorstring = ($cUser->IsLoggedOn()) ? " by #{$this->member_id_author}" : "";
				$string .= "<div class=\"content\">{$clean_text}</div>";
				$string .= "<div class=\"metadata left\">{$role_string}</div><div class=\"metadata\"> Page last updated on {$this->updated_at}{$authorstring}</div>";
			}
			return $string;
		}

}


