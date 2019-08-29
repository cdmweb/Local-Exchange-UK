<?php
//CT holdit class for fragments of db query for re-use. but messy - liable to change when I figure out what I am doing
class cQueries
{
    //fragments
	public $mysql_member_display_name = "concat(p1.first_name, \" \", p1.last_name, if(m.account_type=\"J\" and p2.directory_list=\"Y\" and p2.first_name is not null, concat(\" and \", p2.first_name, \" \", p2.last_name),\"\")) as display_name";
    
    public $mysql_member_display_phone = "concat(p1.phone1_number, if(m.account_type=\"J\" and p2.phone1_number is not null, concat(\" (\", p1.first_name, \")\",\", \", p2.phone1_number, \" (\", p2.first_name, \")\"), \"\")) as display_phone";

    public $mysql_member_display_email = "concat(\"<a href='mailto:\", p1.email, \"'>\", p1.email, \"</a>\", if(m.account_type=\"J\" and p1.email is not null, concat(\", <a href='mailto:\", p2.email, \"'>\", p2.email, \"</a>\"), \"\")) as display_email";

    // CT use it in summary page 
    public $mysql_feedback_counts = "COUNT(CASE WHEN `rating` = 1 THEN 1 END) AS feedback_negative, COUNT(CASE WHEN `rating` = 2 THEN 1 END) AS feedback_neutral, COUNT(CASE WHEN `rating` = 3 THEN 1 END) AS feedback_positive, COUNT(1) AS feedback_total ";

 // CT use in all calls that show display name
    public $mysql_joins_display_name =  "left JOIN " . DATABASE_PERSONS . " p1 ON m.member_id=p1.member_id 
        LEFT JOIN (select * from " . DATABASE_PERSONS . " person where person.primary_member = \"N\" 
        AND directory_list= 'Y') p2 ON p1.member_id=p2.member_id ";

    // here for reuse - optimised for contact details
    function getMySqlMemberConcise($condition, $order_by){

    	$query = "SELECT  
            m.member_id as member_id,  
            {$this->mysql_member_display_name},
            {$this->mysql_member_display_phone},
            {$this->mysql_member_display_email},
            p1.address_street1 as address_street1, 
            p1.address_street2 as address_street2, 
            p1.address_city as address_city,
            p1.address_state_code as address_state_code,
            p1.address_country as address_country,
            p1.address_post_code as address_post_code, 
            m.balance as balance,
            m.account_type as account_type, 
            m.restriction as restriction, 
            m.expire_date as expire_date  
            FROM " . DATABASE_MEMBERS . " m 
            left JOIN " . DATABASE_PERSONS . " p1 ON m.member_id=p1.member_id 
            left JOIN (select * from " . DATABASE_PERSONS . " person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id WHERE {$condition} ORDER BY {$order_by}";

            return $query;
    }

    function getMySqlPerson($condition){
        $query = "SELECT
            person_id, 
            member_id, 
            primary_member, 
            directory_list, 
            first_name, 
            last_name, 
            mid_name, 
            dob, 
            mother_mn, 
            email, 
            phone1_area, 
            phone1_number, 
            phone1_ext, 
            phone2_area, 
            phone2_number, 
            phone2_ext, 
            fax_area, 
            fax_number, 
            fax_ext, 
            address_street1, 
            address_street2, 
            address_city, 
            address_state_code, 
            address_post_code, 
            address_country, 
            about_me, 
            age, 
            sex 
            FROM ".DATABASE_PERSONS." WHERE {$condition}";
        return $query;
    }
    /*
       // here for reuse - but theres got to be a better way
    function getMySqlMember($condition){
    	$query = "SELECT m.balance as balance,                 
            m.status as status, 
            m.admin_note as admin_note, 
            m.member_role as member_role, 
			m.join_date as join_date, 
            m.account_type as account_type,
			m.expire_date as expire_date, 
            m.restriction as restriction, 
            m.away_date as away_date, 
            m.member_id as member_id, 
            m.account_type as account_type,                 
            p1.person_id as person_id, 
            p1.email as email, 
            p1.phone1_number as phone1_number, 
            p1.address_street1 as address_street1, 
            p1.address_street2 as address_street2, 
            p1.address_city as address_city, 
            p1.address_state_code as address_state_code, 
            p1.address_post_code as address_post_code, 
            p1.address_country as address_country, 
            p1.age as age, 
            p1.sex as sex, 
            p1.about_me as about_me,                 
            p2.person_id as p2_person_id, 
            p2.email as p2_email, 
            p2.first_name as p2_first_name, 
            p2.last_name as p2_last_name, 
            p2.primary_member as p2_primary_member, 
            p2.phone1_number as p2_phone1_number, 
            p2.about_me as about_me,
            {$this->mysql_member_display_name}
            FROM " . DATABASE_MEMBERS . " m 
            {$this->mysql_joins_display_name}
            WHERE {$condition} 
            ";
            return $query;
    }
    */
    function getMySqlMember($condition){
    	$query = "SELECT m.balance as balance,                 
			m.join_date as join_date, 
            m.member_role as member_role, 
            m.admin_note as admin_note, 
            m.account_type as account_type,
			m.expire_date as expire_date, 
            m.away_date as away_date, 
            m.member_id as member_id, 
            p1.primary_member as primary_member, 
            p1.directory_list as directory_list, 
            p1.person_id as person_id, 
            p1.first_name as first_name, 
            p1.last_name as last_name, 
            p1.email as email, 
            p1.phone1_number as phone1_number, 
            p1.phone2_number as phone2_number, 
            p1.age as age, 
            p1.sex as sex, 
            p1.about_me as about_me,                 
            p1.address_street1 as address_street1, 
            p1.address_street2 as address_street2,                 
            p1.address_city as address_city,                 
            p1.address_state_code as address_state_code,                 
            p1.address_post_code as address_post_code,                 
            p1.address_country as address_country,   
            p2.primary_member as p2_primary_member, 
            p2.directory_list as p2_directory_list, 
            p2.person_id as p2_person_id,   
            p2.first_name as p2_first_name, 
            p2.last_name as p2_last_name, 
            p2.email as p2_email, 
            p2.phone1_number as p2_phone1_number, 
            p2.phone2_number as p2_phone2_number, 
            p2.about_me as p2_about_me,
            {$this->mysql_feedback_counts},
            {$this->mysql_member_display_name},
            {$this->mysql_member_display_phone},
            {$this->mysql_member_display_email},
            i.filename as photo                
            FROM " . DATABASE_MEMBERS . " m
            {$this->mysql_joins_display_name}
            LEFT JOIN " . DATABASE_UPLOADS . " i ON i.title=concat('mphoto_', m.member_id)
            LEFT JOIN " . DATABASE_FEEDBACK . " f ON m.member_id=f.member_id_about 
            WHERE {$condition}
        ";
        return $query;
    }
    //CT this is really simple - load what you need to take action
    function getMySqlMemberSelf($condition){
    	$query = "SELECT m.balance as balance, 
            m.member_role as member_role, 
            m.status as status, 
            m.account_type as account_type, 
            m.expire_date as expire_date, 
            m.away_date as away_date, 
            m.restriction as restriction, 
            {$this->mysql_member_display_name}
            FROM 
            " . DATABASE_MEMBERS . " m 
            {$this->mysql_joins_display_name} WHERE {$condition}";
    	return $query;
    }
    function getMySqlTradeSummary($condition){
        $query = "SELECT 
            COUNT(trade_date) as trade_total_count, 
            SUM(amount) as trade_total_amount, 
            (SELECT trade_date 
            FROM ".DATABASE_TRADES." 
            WHERE member_id_from 
            LIKE \"{$member_id}\" 
            OR member_id_to 
            LIKE \"{$member_id}\" 
            AND NOT type=\"R\" 
            AND NOT status=\"R\" 
            ORDER BY trade_date DESC LIMIT 1) as trade_last_date 
            FROM ".DATABASE_TRADES." t
            WHERE {$condition}";
        return $query;
    }
    function getMySqlTrade($condition){
        $query="SELECT 
            t.trade_date as trade_date, 
            t.status as trade_status, 
            t.trade_id as trade_id, 
            t.member_id_from as trade_member_id_from, 
            t.member_id_to as trade_member_id_to, 
            t.amount as trade_amount, 
            t.description as trade_description, 
            t.type as trade_type, 
            t.category as category_id, 
            c.description as category_name,
            f.feedback_id as feedback_id, 
            f.member_id_about as feedback_member_id_about, 
            f.comment as feedback_comment, 
            f.rating as feedback_rating
            FROM ".DATABASE_TRADES." t 
            LEFT JOIN ".DATABASE_CATEGORIES." c ON t.category = c.category_id
            LEFT JOIN ".DATABASE_FEEDBACK." f ON t.trade_id = f.trade_id WHERE {$condition} 
            ORDER BY trade_date DESC;";
        return $query;
    }
    function getMySqlListing($condition, $order_by){
        $query="SELECT 
            l.member_id as member_id, 
            l.listing_id as listing_id, 
            l.title as title, 
            l.type as type, 
            l.description as description, 
            l.rate as rate,  
            l.posting_date as posting_date, 
            l.status as status, 
            l.expire_date as expire_date, 
            l.reactivate_date as reactivate_date,
            l.member_id as member_id,
            {$this->mysql_member_display_name},
            p1.address_post_code as address_post_code,
            p1.address_street2 as address_street2,
            l.category_code as category_id,
            c.description as category_name
            FROM ".DATABASE_LISTINGS." l 
            LEFT JOIN ".DATABASE_MEMBERS." m ON l.member_id=m.member_id
            LEFT JOIN ".DATABASE_CATEGORIES." c ON c.category_id=l.category_code 
            {$this->mysql_joins_display_name}
            WHERE {$condition} ORDER BY {$order_by}";
        return $query;
    }
    function getMySqlInfoPage($condition, $order_by){
        $query="SELECT page_id, title, body, active, permission, member_id_author, created_at, updated_at FROM ".DATABASE_PAGE." WHERE {$condition} ORDER BY {$order_by}";
        return $query;
    }
    function getMySqlCategory($condition, $order_by){
        $query="SELECT 
            c.category_id as category_id, 
            c.parent_id as parent_id, 
            c.description as category_name 
            FROM ".DATABASE_CATEGORIES." c WHERE {$condition} ORDER BY {$order_by}";
        return $query;

    }
    
}


$cQueries = new cQueries;
?>
