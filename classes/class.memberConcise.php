<?php 
//CT this is display only class used for listings - where minimal of data needs to be loaded
class cMemberConcise extends cMember {
    //extra properties
    private $display_name; // ct = for display only
    private $display_location; // ct or display only
    private $display_phone; // ct or display only
    private $display_email; // ct or display only
 
    public function __construct($values=null){
        if(!empty($values)){
            $this->Build($values);
        }else{
            $this->setPrimaryPerson();
        }
    }

    public function Build($values){
        //print_r($values);
        parent::Build($values);
        $this->setDisplayName($values['display_name']);          
        $this->setDisplayPhone($values['display_phone']);          
        $this->setDisplayLocation($values['display_location']);          
        $this->setDisplayEmail($values['display_email']); 
    }
    public function setDisplayName($display_name)
    {
        $this->display_name = $display_name;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->display_name;
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

    public function setDisplayLocation($display_location)
    {
        $this->display_location = $display_location;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getDisplayLocation()
    {
        return $this->display_location;
    }


    public function Load($condition, $order_by="m.member_id") {
        global $cDB, $cErr, $cQueries;
        //clean it - needed?
        $query = $cDB->Query("{$cQueries->getMySqlMemberConcise($condition, $order_by)} limit 1");


        $i=0;
        //CT TODO makes work
        while($values = $cDB->FetchArray($query)) // Each of our SQL results
        {
            //CT add a few extras after builds. TODO - improve
            $this->Build($values);
            $this->setDisplayName($values['display_name']);          
            $this->setDisplayPhone($values['display_phone']);          
            $this->setDisplayLocation($values['display_location']);          
            $this->setDisplayEmail($values['display_email']);          
            $i++;
        }

        if (empty($i)){
            $cErr->Error("Error accessing member (".$member.").");
            if ($redirect) {
                include("redirect.php");
            }
            return false;
        }
        return true;
    }
    
    public function Display () {
        $string = "
            <h3>{$this->MemberLink()} {$this->getDisplayName()}</h3>
            <p>
                Telephone: {$this->getDisplayPhone()}
                <div>Emails: {$this->getDisplayEmail()}</div>
            </p>{$this->getDisplayLocation()}</p>";        
        return $string; 

    }
    
}
?>