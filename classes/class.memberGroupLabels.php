<?php
//CT remove...
//just for labels - 
class cMemberGroupLabels extends cMemberGroup{

    public function Build($vars){
        $members = array();
        foreach($vars as $field) // Each result
        {
            $members[] = new cMemberLabel($field);
        }
        $this->setmembers($members);  // this will be an array of cmembers
    }
    public function Display(){
       
        $string = "";
        $i = 1;
        foreach ($this->getMembers() as $member) {
            //CT todo fix
            $pre = (($i % 2)) ? "<tr>" : "";
            $post = (!($i % 2)) ? "</tr>" : "";
            
            $string .= "{$pre}<td width=\"50%\">{$member->Display()}</td>{$post}";
            
            $i++;
        }
        //add missing row finisher
        if (!($i % 2)) $string .= "<td></td></tr>";
        return "<table border=\"3\" cell-padding=\"3\" width=\"100%\">{$string}</table>";
    }
}
?>