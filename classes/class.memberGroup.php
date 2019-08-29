<?php

class cMemberGroup {
    //CT this should be private 
    public $members;
    // public function __construct($values=null)
    // {
    //     if(!empty($values)) {
    //         $this->Build($values);
    //     }

    //     return $this;
    // }
    public function getMembers()
    {
        return $this->members;
    }
    /**
     * @param mixed $join_date
     *
     * @return self
     */
    public function setMembers($members)
    {
        $this->members = $members;

        return $this;
    }

    public function Build($field_array){
        $members = array();

        foreach($field_array as $field) // Each result
        {
            $members[] = new cMemberConcise($field);
        }
        $this->setmembers($members);  // this will be an array of cmembers
    }

    function makeActiveMemberFilter(){
        return "p1.primary_member = 'Y' and m.status = 'A' and m.account_type != 'F'";
    }
    function makeAllMemberFilter(){
        return "p1.primary_member = 'Y' AND m.account_type != 'F'";
    }
    function makeInactiveMemberFilter(){
        return "p1.primary_member = 'Y' AND m.account_type = 'I'";
    }
    function Load($condition, $order_by="m.member_id ASC") {
        global $cDB, $cErr, $cQueries;
        
        $string_query = $cQueries->getMySqlMemberConcise($condition, $order_by);

        $query = $cDB->Query($string_query);
        $i=0;
        $field_array = array();
        while($row = $cDB->FetchArray($query)) $field_array[] = $row;            
        //build from vars
 
        $this->Build($field_array);
    }   
    

    function PrepareMemberDropdown($member_id=null,$select_name = "member_id"){
        global $p, $cUser;
        $array = array();
        foreach($this->getMembers() as $member) {
            //print_r($category->getCategoryName());
            $array[$member->getMemberId()] = $member->getDisplayName() . " (#" . $member->getMemberId() .")";
        }
        $output = $p->PrepareFormSelector($select_name, $array, "-- Select member --", $member_id);
        return $output;
    }   

    
    // Use of this function requires the inclusion of class.listing.php
    public function EmailListingUpdates($interval) {
        //load members if not loaded
        if(empty($this->getMembers())) {
            $condition=$this->makeActiveMemberFilter();
            if(!$this->Load($condition)){
                return false;
            }
        }

        $listings = new cListingGroup(OFFER_LISTING);
        $since = new cDateTime("-". $interval ." days");
        $listings->LoadListingGroup(null,null,null,$since->MySQLTime());
        $offered_text = $listings->DisplayListingGroup(true);
        $listings = new cListingGroup(WANT_LISTING);
        $listings->LoadListingGroup(null,null,null,$since->MySQLTime());
        $wanted_text = $listings->DisplayListingGroup(true);
        
        $email_text = "";
        if($offered_text != "No listings found.")
            $email_text .= "<h2>Offered Listings</h2><br>". $offered_text ."<p><br>";
        if($wanted_text != "No listings found.")
            $email_text .= "<h2>Wanted Listings</h2><br>". $wanted_text;
        if(!$email_text)
            return; // If no new listings, don't email
        
        $email_text = "<html><body>". LISTING_UPDATES_MESSAGE ."<p><br>".$email_text. "</body></html>";
            
        if ($interval == '1')
            $period = "day";
        elseif ($interval == '7')
            $period = "week";
        else
            $period = "month";          
        
        foreach($this->members as $member) {                        
            if($member->getEmailUpdates() == $interval and $member->getPrimaryPerson[0]->getEmail()) {
                mail($member->getPrimaryPerson->getEmail(), SITE_SHORT_TITLE .": New and updated listings during the last ". $period, wordwrap($email_text, 64), "From:". EMAIL_ADMIN ."\nMIME-Version: 1.0\n" . "Content-type: text/html; charset=iso-8859-1"); 
            }
        
        }
    
    }
    
    // Use of this function requires the inclusion of class.listing.php
    public function ExpireListings4InactiveMembers() {
        if(empty($this->getMembers())) {
            $condition=$this->makeActiveMemberFilter();
            if(!$this->Load($condition)){
                return false;
            }
        }
        
        foreach($this->members as $member) {
            if($member->DaysSinceLastTrade() >= MAX_DAYS_INACTIVE
            and $member->DaysSinceUpdatedListing() >= MAX_DAYS_INACTIVE) {
                $offer_listings = new cListingGroup(OFFER_LISTING);
                $want_listings = new cListingGroup(WANT_LISTING);
                
                $offered_exist = $offer_listings->LoadListingGroup(null, null, $member->member_id, null, false);
                $wanted_exist = $want_listings->LoadListingGroup(null, null, $member->member_id, null, false);
                
                if($offered_exist or $wanted_exist) {
                    $expire_date = new cDateTime("+". EXPIRATION_WINDOW ." days");
                    if($offered_exist)
                        $offer_listings->ExpireAll($expire_date);
                    if($wanted_exist)
                        $want_listings->ExpireAll($expire_date);
                
                    if($member->person[0]->email != null) {
                        mail($member->person[0]->email, "Important information about your ". SITE_SHORT_TITLE ." account", wordwrap(EXPIRED_LISTINGS_MESSAGE, 64), "From:". EMAIL_ADMIN); 
                        $note = "";
                        $subject_note = "";
                    } else {
                        $note = "\n\n***NOTE: This member does not have an email address in the system, so they will need to be notified by phone that their listings have been inactivated.";
                        $subject_note = " (member has no email)";
                    }
                    
                    mail(EMAIL_ADMIN, SITE_SHORT_TITLE ." listings expired for ". $member->member_id. $subject_note, wordwrap("All of this member's listings were automatically expired due to inactivity.  To turn off this feature, see inc.config.php.". $note, 64) , "From:". EMAIL_ADMIN);
                }
            }
        }
    }
    public function Display(){
        $string = "";
        foreach ($this->getMembers() as $member) {
            $string .= $member->Display();
        }
        return $string;
    }
}

?>