<?php
//CT for address labels
class cMemberLabel extends cMemberConcise {

    // helper function to return the month of expiry
    public function getMonthOfExpiry(){
        $month = date("F",strtotime($this->getExpireDate()));
        $month = strtoupper($month);
        return $month;
    }
    public function Display () {
        $string = "
            <div style=\"border: solid 2px #999; padding:1em; margin-bottom: 1em;\">
                <p>
                    #{$this->getMemberId()}
                </p>
                <p>
                    <strong>{$this->getDisplayName()}</strong>
                </p>
                <p>
                    {$this->getPrimaryPerson()->getAddressStreet1()}<br />
                    {$this->getPrimaryPerson()->getAddressStreet2()}<br />
                    {$this->getPrimaryPerson()->getAddressCity()}<br />
                    {$this->getPrimaryPerson()->getAddressStateCode()}, {$this->getPrimaryPerson()->getAddressPostCode()}<br />
                </p>
                <!--
                <p>
                    Telephone: {$this->getDisplayPhone()}
                    <div>Emails: {$this->getDisplayEmail()}</div>
                </p> -->
                <p><br /><small>
                    Renewal: {$this->getMonthOfExpiry()}</small>
                </p>
            </div>";        
        return $string; 

    }
}

?>