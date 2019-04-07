<?php
//CT holdit class for fragments of db query for re-use. but messy - liable to change when I figure out what I am doing
class cQueries
{
	public $mysql_member_display_name = "concat(p1.first_name, \" \", p1.last_name, if(m.account_type=\"J\" and p2.directory_list=\"Y\" and p2.first_name is not null, concat(\" and \", p2.first_name, \" \", p2.last_name),\"\")) as display_name";
    
    public $mysql_member_display_phone = "concat(p1.phone1_number, if(m.account_type=\"J\" and p2.phone1_number is not null, concat(\" (\", p1.first_name, \")\",\", \", p2.phone1_number, \" (\", p2.first_name, \")\"), \"\")) as display_phone";

    public $mysql_member_display_email = "concat(\"<a href='mailto:\", p1.email, \"'>\", p1.email, \"</a>\", if(m.account_type=\"J\" and p1.email is not null, concat(\", <a href='mailto:\", p2.email, \"'>\", p2.email, \"</a>\"), \"\")) as display_email";



    // here for reuse - but theres got to be a better way
    function getMySqlMemberConcise(){

    	$query = "m.balance as balance, 
            m.account_type as account_type, 
            p1.address_street2 as address_street2, 
            p1.address_city as address_city,
            p1.address_post_code as address_post_code, 
            m.member_id as member_id,  
            m.account_type as account_type, 
            {$this->mysql_member_display_name},
            {$this->mysql_member_display_phone},
            {$this->mysql_member_display_email}
            FROM member m 
            left JOIN person p1 ON m.member_id=p1.member_id 
            left JOIN (select * from person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id ";

            return $query;
    }
       // here for reuse - but theres got to be a better way
    function getMySqlMember(){
    	$query = "m.balance as balance,                 
            m.status as status, 
            m.admin_note as admin_note, 
			m.join_date as join_date, 
            m.account_type as account_type,
			m.expire_date as expire_date, 
            m.restriction as restriction, 
            m.away_date as away_date, 
            m.member_id as member_id, 
            m.account_type as account_type,                 
            p1.email as email, 
            p1.phone1_number as phone1_number, 
            p1.address_street2 as address_street2, 
            p1.address_city as address_city, 
            p1.address_state_code as address_state_code, 
            p1.address_post_code as address_post_code, 
            p1.address_country as address_country, 
            p1.age as age, 
            p1.sex as sex, 
            p1.about_me as about_me,                 
            p2.email as p2_email, 
            p2.first_name as p2_first_name, 
            p2.last_name as p2_last_name, 
            p2.primary_member as p2_primary_member, 
            p2.phone1_number as p2_phone1_number, 
            p2.about_me as about_me                 
            FROM member 
            left JOIN person p1 ON m.member_id=p1.member_id 
            left JOIN (select * from person where person.primary_member = 'N' and directory_list= 'Y') p2 on p1.member_id=p2.member_id ";

            return $query;
    }
    function getMySqlMemberSummary(){
    	$query = "m.balance as balance,                 
			m.join_date as join_date, 
            m.account_type as account_type,
			m.expire_date as expire_date, 
            m.away_date as away_date, 
            m.member_id as member_id, 
            p1.first_name as first_name, 
            p1.last_name as last_name, 
            p1.email as email, 
            p1.phone1_number as phone1_number, 
            p1.phone2_number as phone2_number, 
            p1.age as age, 
            p1.sex as sex, 
            p1.about_me as about_me,                 
            p1.address_street2 as address_street2,                 
            p1.address_city as address_city,                 
            p1.address_state_code as address_state_code,                 
            p1.address_post_code as address_post_code,                 
            p1.address_country as address_country,                 
            p2.first_name as p2_first_name, 
            p2.last_name as p2_last_name, 
            p2.email as p2_email, 
            p2.phone1_number as p2_phone1_number, 
            p2.phone2_number as p2_phone2_number, 
            p2.about_me as p2_about_me,
            p2.directory_list as p2_directory_list,
            {$this->mysql_member_display_name}
            FROM member m
            left JOIN person p1 ON m.member_id=p1.member_id 
            left JOIN (select * from person where person.primary_member = 'N' and directory_list= 'Y') p2 on p1.member_id=p2.member_id ";
        return $query;
    }
    //CT this is really simple - load what you need to take action
    function getMySqlMemberSelf(){
    	$query = "m.balance as balance, 
            m.member_role as member_role, 
            m.status as status, 
            m.account_type as account_type, 
            m.expire_date as expire_date, 
            m.away_date as away_date, 
            m.restriction as restriction, 
            {$this->mysql_member_display_name}
            FROM 
            member m 
            left JOIN person p1 ON m.member_id=p1.member_id 
            left JOIN (select * from person 
                where person.primary_member = \"N\") p2 on p1.member_id=p2.member_id";
    	return $query;
    }

}



$cQueries = new cQueries;
?>
