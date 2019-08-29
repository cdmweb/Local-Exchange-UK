<?php

class cMemberEdit extends cMember {
    
    var $form_action;

    public function getFormAction()
    {
        return $this->formAction;
    }

    public function setFormAction($form_action)
    {
        $this->formAction = $form_action;
        
    }
    public function UnlockAccount() {
        $history = new cLoginHistory;
        $has_logged_on = $history->LoadLoginHistory($this->member_id);
        if($has_logged_on) {
            $consecutive_failures = $history->consecutive_failures;
            $history->consecutive_failures = 0;  // Set count back to zero whether locked or not
            $history->SaveLoginHistory();   
        } 
        
        if($this->status == LOCKED) {
            $this->status = ACTIVE;
            if($this->Save()) {
                return $consecutive_failures;
            }           
        }
        return false;
    }
    
    public function DeactivateMember() {
        if($this->status == ACTIVE) {
            $this->status = INACTIVE;
            return $this->Save();
        } else {
            return false;   
        }
    }
    
    public function ReactivateMember() {
        if($this->status != ACTIVE) {
            $this->status = ACTIVE;
            return $this->Save();
        } else {
            return false;   
        }
    }


    //helper function to build dropdown for editing user
    function PrepareMemberRoleDropdown($member_id=null){
        global $p, $cUser;
        //CT what user can do on the site
        $all_array = MEMBER_ROLE_ARRAY;
        $field_array = array();
        $i=0;
        while($i <= $cUser->getMemberRole()){
            $field_array[$i] = $all_array[$i];
            $i++;
        }
        $select_name = "member_role";
        //if used in context of batch page controls
        if(!empty($member_id)) $select_name .= "_{$member_id}";
        //print($this->getMemberRole());
        $output = $p->PrepareFormSelector($select_name, $field_array, null, $this->getMemberRole());
        return $output;
    }
    //helper function to build dropdown for editing user
    function PrepareAccountTypeDropdown($member_id=null){
        global $p, $cUser;
        //CT account_type controls certain functions and visibility
        $field_array = ACCOUNT_TYPE_ARRAY;

        $select_name = "account_type";
        //if used in context of batch page controls
        if(!empty($member_id)) $select_name .= "_{$member_id}";
        $string = $p->PrepareFormSelector($select_name, $field_array, null, $this->getAccountType());
        return $string;
    }    
    //helper function to build dropdown for editing user
    function PrepareAgeRangeDropdown(){
        global $p, $cUser;
        //CT account_type controls certain functions and visibility
        $field_array = AGE_ARRAY;

        $select_name = "age";
        //print_r($this->getPrimaryPerson()->getAge());
        $select_id = (!empty($this->getPrimaryPerson()) && !empty($this->getPrimaryPerson()->getAge())) ? $this->getPrimaryPerson()->getAge() : '8';
        //print_r($select_id);
        //if used in context of batch page controls
        $string = $p->PrepareFormSelector($select_name, $field_array, "-- Select age range --", $select_id, 'dropdown_auto');
        return $string;
    }

    //helper function to build dropdown for editing user
    function PrepareStatusDropdown($member_id=null){
        global $p, $cUser;
        //CT account_type controls certain functions and visibility
        $field_array = array(
            "A" => "Active", 
            "I" => "Inactive");
        $select_name = "status";
        //if used in context of batch page controls
        if(!empty($member_id)) $select_name .= "_{$member_id}";
        $string = $p->PrepareFormSelector($select_name, $field_array, null, $this->getActive());
        return $string;
    }
        //helper function to build dropdown for editing user
    function PrepareGenderDropdown(){
        global $p, $cUser;
        //CT account_type controls certain functions and visibility
        $field_array = array(
            "0" => "Would rather not say",
            "m" => "Male", 
            "f" => "Female",
        );
        $select_name = "sex";
        //if used in context of batch page controls
        $select_id = ($this->getPrimaryPerson()) ? $this->getPrimaryPerson()->getSex() : null;

        $string = $p->PrepareFormSelector($select_name, $field_array, "-- Select gender --", $select_id);
        return $string;
    }

          //CT todo - put this somewhere for reuse..
    public function FormatLabelValue($label, $value){
        return "<p class='line'><span class='label'>{$label}: </span><span class='value'>{$value}</span></p>";
    }

    function Build($field_array){
        parent::Build($field_array);
        if(isset($field_array['form_action'])) $this->setFormAction($field_array['form_action']);
        //sneak in the default values
        if(empty($this->getPrimaryPerson()->getAddressCity())) $this->getPrimaryPerson()->setAddressCity(DEFAULT_CITY);
        if(empty($this->getPrimaryPerson()->getAddressStateCode())) $this->getPrimaryPerson()->setAddressStateCode(DEFAULT_STATE);
        if(empty($this->getPrimaryPerson()->getAddressPostcode())) $this->getPrimaryPerson()->setAddressPostcode(DEFAULT_ZIP_CODE);
    }

    function checkDateString($date_string){
        list($year, $month, $day) = explode("-", $date_string);
        //use php function to check that its an actual date 
        if(!checkDate($month, $day, $year)) return false;
        return true;
    }

    function validateDates(){
        $errors = array();
        if(!$this->checkDateString($this->getJoinDate())) {
            $errors['join_date']="Join date is not set properly.";
        }
        if(!$this->checkDateString($this->getExpireDate())) {
            $errors['expire_date']="Expire date is not set properly.";
        }
        if(sizeof($errors) > 0) return $errors;
        if(date("Y-m-d") < $this->getJoinDate()) $errors['join_date']="Join date cannot be in the future.";
        if($this->getExpireDate() < $this->getJoinDate()) $errors['expire_date']="Expire date cannot be before join date.";
        return $errors;
        
    }

    // CT not using pear - so a bit clunky. Sorry!
    function ProcessData(){
        global $p, $cUser, $cErr;
        $errors = array();
            //validate...
        if(empty($this->getMemberId())){
            $errors['member_id'] = "Member ID is missing.";
        } else{
            if($this->getFormAction() == 'create' && $this->VerifyMemberExists($this->getMemberId())){
                    $errors['member_id'] ="Member ID is not unique. Please check the member directory for the ones in use.";
            }
        }
        
        //CT todo: check min and max length also
        if(empty($this->getPrimaryPerson()->getFirstName())){
            $errors['first_name'] = "First name is missing.";
        }           
        //CT todo: check min and max length
        if(empty($this->getPrimaryPerson()->getLastName())){
            $errors['last_name'] = "Last name is missing.";
        }

        //CT todo: check min and max length
        if(empty($this->getPrimaryPerson()->getAddressStreet2())){
            $errors['address_street2'] = ADDRESS_LINE_2 . " is missing.";
        }            //CT todo: check min and max length
        if(empty($this->getPrimaryPerson()->getAddressCity())){
            $errors['address_city'] = ADDRESS_LINE_3 . "is missing.";
        }
        if(empty($this->getPrimaryPerson()->getAddressPostCode())){
            $errors['address_post_code'] = ZIP_TEXT . " is missing.";
        }

        //CT todo: check date format, not in future
        // join date must be in past. 
        //hack for user experience in errors
        $date_errors = $this->validateDates();
        $errors = array_merge($errors, $date_errors);



        //CT todo: email proper format if set
                    //if(empty($this->getPrimaryPerson()->getEmail())){
                    //    $errors['email'] = "Email not set properly";
                    //}

        
        if(sizeof($errors) > 0) {

            //CT todo: highlight the form elements from keys
            foreach($errors as $key => $error) {
                $cErr->Error($error);
            }
            return false;
        }
        //$cErr->Error("no errors");
        return $this->Save();        
    }  

    public function Save() {
        global $cDB, $cUser, $cErr; 
        //exit the action if not logged in
        $cUser->MustBeLoggedOn();  
        //$cErr->Error("save data");    
        //Rejigged for safety
        $field_array = array();

        //only allow committee and up to make changes to these fields
        if($cUser->getMemberRole()>0){
            if (!empty($this->getMemberRole())) $field_array["member_role"] = $this->getMemberRole();
            if (!empty($this->getAccountType())) $field_array["account_type"] = $this->getAccountType();
            if (!empty($this->getAdminNote())) $field_array["admin_note"] = $this->getAdminNote();
            if (!empty($this->getJoinDate())) $field_array["join_date"] = $this->getJoinDate();
            if (!empty($this->getExpireDate())) $field_array["expire_date"] = $this->getExpireDate();
            if (!empty($this->getRestriction())) $field_array["restriction"] = $this->getRestriction();
            if (!empty($this->getStatus())) $field_array["status"] = $this->getStatus();
        }

        //only allow user themself and committee to make changes to these fields, and execute
        if($this->getMemberId() == $cUser->getMemberId() || $cUser->getMemberRole()>0){
            if (!empty($this->getMemberNote())) $field_array["member_note"] = $this->getMemberNote();
            if (!empty($this->getEmailUpdates())) $field_array["email_updates"] = $this->getEmailUpdates();
            //$field_array["password"] = $this->getPassword();
            //$field_array["security_q"] = $this->getSecurityQ();
            //$field_array["security_a"] = $this->getSecurityA();
            if (!empty($this->getStatus())) $field_array["status"] = $this->getStatus();
            if (!empty($this->getConfirmPayments())) $field_array["confirm_payments"] = $this->getConfirmPayments();

            $condition = "`member_id`=\"{$this->getMemberId()}\""; 
        
            $is_success = 0;
            //can handle both create and update

            if($this->getFormAction() == "create"){

                //TODO -
                $field_array["member_id"] = $this->getMemberId();
                $field_array["status"] =  "A";

                //temporary password - user should reset when they log in
                $password = $this->GeneratePassword();
                $field_array["password"] =  password_hash($password, PASSWORD_DEFAULT);

                $string_query = $cDB->BuildInsertQuery(DATABASE_MEMBERS, $field_array);
                //TODO - wirtie insert
                $is_success = $cDB->Query($string_query);

                if(!$is_success) {
                    //report and return on fail
                    $cErr->Error("Could not create the member '". $this->getMemberId() ."'.");
                    return false;
                }
                $is_success = $this->getPrimaryPerson()->Save($this->getFormAction());
                return $is_success;
            } else{
                //ct if update - whould be default action

                $string_query = $cDB->BuildUpdateQuery(DATABASE_MEMBERS, $field_array, $condition);
                //print_r($string_query);
                $is_success = $cDB->Query($string_query);  

                if(!$is_success) {
                    //report and return on fail
                    $cErr->Error("Could not save changes to member '". $this->getMemberId() ."'.");
                    return false;
                }
                //CT don't save the secondary member here, just the primary
                $is_success = $this->getPrimaryPerson()->Save($this->getFormAction());
                return $is_success; 
            } 
        }
    }
}

?>