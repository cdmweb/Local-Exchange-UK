<?php

class cMemberGroupMenu extends cMemberGroup {       
    var $member_id;
    var $name;
    var $person_id;

    public function MakeMenuArrays() {
        global $cDB, $cErr;
        
        $i = 0;
        $j = 0; 
        foreach($this->members as $member) {
            foreach ($member->person as $person) {
                $this->id[$i] = $member->member_id;
                $this->name[$i][$j] = $person->first_name." ".$person->last_name;
                $this->person_id[$i][$j] = $person->person_id;                      
                $j += 1;
            }
            $i += 1;
        }
        
        if($i <> 0)
            return true;
        else 
            return false;
    }
}
?>