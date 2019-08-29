<?php
//CT this is the most complicated version of the cMember - 
// for the public detail page for member. Images, feedback, activity etc
class cMemberSummary extends cMember {
    //extra properties
    private $photo;
    private $display_email; // ct for display only
    private $display_phone; // ct for display only
    private $stats; // ct activity summary object
    private $feedback; // ct feedback object
    /**
     * @return mixed
     */
    public function getPhoto()
    {
        return $this->photo;
    }    
    /**
     * @param mixed $photo
     *
     * @return self
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getFeedback()
    {
        return $this->feedback;
    }    
    /**
     * @param mixed $feedback
     *
     * @return self
     */
    public function setFeedback($feedback)
    {
        $this->feedback = $feedback;

        return $this;
    }

    /**
     * @param mixed $display_phone
     *
     * @return self
     */
    public function setDisplayPhone($display_phone)
    {
        $this->display_phone = $display_phone;

        return $this;
    }
    /**
     * @return mixed
     */
    public function getDisplayPhone()
    {
        return $this->display_phone;
    }
    /**
     * @param mixed $display_email
     *
     * @return self
     */
    public function setDisplayEmail($display_email)
    {
        $this->display_email = $display_email;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getDisplayEmail()
    {
        return $this->display_email;
    }
    /**
     * @return mixed
     */
    public function getStats()
    {
        return $this->stats;
    }    
    /**
     * @param mixed $stats
     *
     * @return self
     */
    public function setStats($stats)
    {
         $this->stats = $stats;
    } 

    //CT load member from db
    public function Load($condition) {
        global $cDB, $cErr, $cQueries;
        //clean it - needed?

        //CT composite all the summary/profile calls together for efficiency
        //TODO - put stats in here
        
        $query = $cDB->Query("{$cQueries->getMySqlMember($condition)} LIMIT 1");

        //CT this is a loop but there should only be 1
        while($values = $cDB->FetchArray($query)) // Each of our SQL results
        {
            //$cErr->Error(print_r($row, true));
            $this->Build($values);
            // CT extra bits 
            $feedback = new cFeedbackSummary($values);
            $this->setFeedback($feedback);          
            $this->setDisplayName($values['display_name']); 
            $this->setDisplayEmail($values['display_email']); 
            $this->setDisplayPhone($values['display_phone']);          
           //CT choose photo. todo: fix filetype, this is weird.
            if(!empty($values['photo'])){
                $photo = UPLOADS_PATH . stripslashes($values["photo"]);
            }else{
                $photo = IMAGES_PATH . "user-placeholder.svg";
            } 
            $this->setPhoto($photo); 

            return true;
        }
        return false;
    }

    public function Display () {
    $string = "
        <h3>{$this->MemberLink()} {$this->getDisplayName()}</h3>
        <p>{$this->getDisplayLocation()}</p>";        
    return $string; 

    }
}
?>