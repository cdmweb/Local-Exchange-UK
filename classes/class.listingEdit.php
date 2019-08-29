<?php 

class cListingEdit extends cListing {

	private $form_action; 
	private $form_mode; 

		    /**
     * @param mixed $form_action
     *
     * @return self
     */

/**
     * @return mixed
     */
    public function getFormAction()
    {
        return $this->form_action;
    }

    /**
     * @param mixed $form_action
     *
     * @return self
     */
    public function setFormAction($form_action)
    {
        $this->form_action = $form_action;

        return $this;
    }
    	    /**
     * @param mixed $form_action
     *
     * @return self
     */

/**
     * @return mixed
     */
    public function getFormMode()
    {
        return $this->form_mode;
    }

    /**
     * @param mixed $form_mode
     *
     * @return self
     */
    public function setFormMode($form_mode)
    {
        $this->form_mode = $form_mode;

        return $this;
    }


	function Build($vars) {
		parent::Build($vars);
		//add extra class
		//if($vars['form_action']) $this->setFormAction($vars['form_action']);
	}

    function PrepareStatusDropdown($listing_id){
        global $p;
        $vars = array("I" => "Inactive", "A" => "Active");
        $select_name = "status";
        //if used in context of batch page controls
        if(!empty($page_id)) $select_name .= "_{$listing_id}";
        $output = $p->PrepareFormSelector($select_name, $vars, null, $this->getStatus());
        return $output;
    }
    //includes the category making gubbins
	function PrepareCategoryDropdown(){
		global $p, $cUser;
		$categories = new cCategoryGroup();
        $categories->Load(1);
        //PrepareCategoryDropdown($selector_name="category_id", $selected_id)
        return $categories->PrepareCategoryDropdown("category_id", $this->getCategoryId());
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
	function PrepareMemberDropdown(){
		global $p, $cUser;
		$member_group = new cMemberGroup();
		$member_group->Load($member_group->makeActiveMemberFilter());

		return $member_group->PrepareMemberDropdown($this->getMemberId());
	}	
		

	public function Save() {

		///tod - adapt
        global $cDB, $cUser, $cErr; 
        //exit the action if not logged in
        $cUser->MustBeLoggedOn();  
        //$cErr->Error("save data");    
        //Rejigged for safety
        $field_array = array();


        //only allow user themself and committee to make changes to these fields, and execute. Doublecheck!!!
        if($this->getMember()->getMemberId() == $cUser->getMemberId() || $cUser->getMemberRole() > 0){

            $field_array["status"]=$this->getStatus();           
            //$field_array["status"]=$this->getReactivateDate();           
			$field_array["title"]=$this->getTitle();
			$field_array["description"]=$this->getDescription();
			$field_array["category_code"]=$this->getCategoryId();
			$field_array["rate"]=$this->getRate();
			//$field_array["posting_date"]=now; //this should be automatic - leave it to the db
			$field_array["reactivate_date"]=$this->getReactivateDate();
			$field_array["type"]=$this->getType();
			$field_array["member_id"]=$this->getMemberId();
        
            $is_success = 0;
            //can handle both create and update
            if($this->getFormAction() == "update"){
            	$condition = "`member_id`=\"{$this->getMemberId()}\" AND listing_id = \"{$this->getListingId()}\""; 

                $string_query = $cDB->BuildUpdateQuery(DATABASE_LISTINGS, $field_array, $condition);
                //do the update
                $is_success = $cDB->Query($string_query);  

                if(!$is_success) {
                    //report and return on fail
                    $cErr->Error("Could not save changes to listing '". $this->getListingId() ."'.");
                    return false;
                }
                //CT don't save the secondary member here, just the primary
                return $is_success; 
            } 
            else{
                //TODO -
                $field_array["status"] =  "A";

                //temporary password - user should reset when they log in

                $string_query = $cDB->BuildInsertQuery(DATABASE_LISTINGS, $field_array);
                //TODO - wirtie insert
                $is_success = $cDB->Query($string_query);

                if(!$is_success) {
                    //report and return on fail
                    $cErr->Error("Could not create the listing.");
                    return false;
                }
                return $is_success;
            }
        }
    }







	
	function DeleteListing($title,$member_id,$type_code) {
		global $cDB, $cErr;
		
		$query = $cDB->Query("DELETE FROM ". DATABASE_LISTINGS ." WHERE title=".$cDB->EscTxt($title)." AND member_id=". $cDB->EscTxt($member_id) ." AND type=".  $cDB->EscTxt($type_code) .";");

		return mysqli_affected_rows();
	}
}

?>