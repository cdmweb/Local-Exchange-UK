<?php
class cPersonSecondary extends cPerson {

    // CT todo: this is messy - fix
    public function Build($field_array=null) 
    {
        //CT grabs values directly out of full result array passed to it. you can pass a partial set
        $this->setPrimaryMember('N');        
        if(isset($field_array['member_id'])) $this->setMemberId($field_array['member_id']);
        if(isset($field_array['p2_primary_member']))$this->setPrimaryMember($field_array['p2_primary_member']);
        if(isset($field_array['p2_person_id']))$this->setPersonId($field_array['p2_person_id']);
        if(isset($field_array['p2_directory_list']))$this->setDirectoryList($field_array['p2_directory_list']);
        if(isset($field_array['p2_first_name']))$this->setFirstName($field_array['p2_first_name']);
        if(isset($field_array['p2_last_name']))$this->setLastName($field_array['p2_last_name']);
        if(isset($field_array['p2_mid_name']))$this->setMidName($field_array['p2_mid_name']);
        if(isset($field_array['p2_dob']))$this->setDob($field_array['p2_dob']);
        if(isset($field_array['p2_email']))$this->setEmail($field_array['p2_email']);
        if(isset($field_array['p2_mother_mn']))$this->setMotherMn($field_array['p2_mother_mn']);
        if(isset($field_array['p2_phone1_area']))$this->setPhone1Area($field_array['p2_phone1_area']);
        if(isset($field_array['p2_phone1_number']))$this->setPhone1Number($field_array['p2_phone1_number']);
        if(isset($field_array['p2_phone1_ext']))$this->setPhone1Ext($field_array['p2_phone1_ext']);
        if(isset($field_array['p2_phone2_area']))$this->setPhone2Area($field_array['p2_phone2_area']);
        if(isset($field_array['p2_phone2_number']))$this->setPhone2Number($field_array['p2_phone2_number']);
        if(isset($field_array['p2_phone2_ext']))$this->setPhone2Ext($field_array['p2_phone2_ext']);
        if(isset($field_array['p2_fax_area']))$this->setFaxArea($field_array['p2_fax_area']);
        if(isset($field_array['p2_fax_number']))$this->setFaxNumber($field_array['p2_fax_number']);
        if(isset($field_array['p2_fax_ext']))$this->setPhone2Ext($field_array['p2_fax_ext']);
        if(isset($field_array['p2_directory_list']))$this->setDirectoryList($field_array['p2_directory_list']);
        // CT Chris's social vars
        if(isset($field_array['p2_age']))$this->setAge($field_array['p2_age']);
        if(isset($field_array['p2_sex']))$this->setSex($field_array['p2_sex']);
        if(isset($field_array['p2_about_me']))$this->setAboutMe($field_array['p2_about_me']);
    }
}

?>