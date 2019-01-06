<?php

include_once("includes/inc.global.php");
$p->site_section = SECTION_DIRECTORY;
$p->page_title = "Members";

$cUser->MustBeLoggedOn();

//include_once("classes/class.listing.php");

//[chris] Search function
if (SEARCHABLE_MEMBERS_LIST==true) {
	
	$output = "<form action='member_directory.php' method='get'>";
	$output .= "Member ID: <input type='text' name='uID' size='4' value='".$_REQUEST["uID"]."'>
		<br>Name (all or part): <input type='text' name='uName' value='".$_REQUEST["uName"]."'>
		<br>Location (Neighbourhood or town): <input type='text' name='uLoc' value='".$_REQUEST["uLoc"]."'>";
	
	$orderBySel = array();
	$orderBySel["".$_REQUEST["orderBy"].""]='selected';
	
	$output .= "<br>Order by: <select name='orderBy'>
	<option value='idA' ".$orderBySel["idA"].">Membership No.</option>
		<option value='fl' ".$orderBySel["fl"].">First Name</option>
		<option value='lf' ".$orderBySel["lf"].">Last Name</option>
		<option value='nh' ".$orderBySel["nh"].">Neighbourhood</option>
		<option value='loc' ".$orderBySel["loc"].">Town</option>
		<option value='pc' ".$orderBySel["pc"].">PostCode</option>
		<option value='balance' ".$orderBySel["balance"].">Balance</option>
		</select>";
	$output .= "<p><input type='submit' value='Search'></form>"; 
}

$output .=
"<table class=\"tabulated\">
	<tr>
		<th class='id' colspan='2'>Member</th>
		<th>Contact</th>";
$output .="<th>" . ADDRESS_LINE_2 . ",<br /> " . ADDRESS_LINE_3 . "</th>
		<th>" . ZIP_TEXT . "</th>";

if (MEM_LIST_DISPLAY_BALANCE==true || $cUser->getMemberRole() >= 1)  {   
	$output .= "<th class='units balance'>Balance</th>";

}

$output .= "</tr>";

//Phones (comma separated with first name in parentheses for non-primary phones)
//Emails (comma separated with first name in parentheses for non-primary emails)

$member_list = new cMemberGroup();
//$member_list->LoadMemberGroup();

// How should results be ordered?


// SQL condition string

function order($orderBy){
	switch($orderBy) {
		
		case("pc"):
			
			return 'p1.address_post_code asc';
			
		break;
		
		case("nh"):
			
			return 'p1.address_street2 asc';
			
		break;
		
		case("loc"):
			
			return 'p1.address_city asc';
			
		break;
		
		case("fl"):
			
			return 'p1.first_name, p1.last_name';
			
		break;
		
		case("idD"):
			
			return 'm.member_id desc';
			
		break;
		
		case("lf"):
			
			return 'p1.last_name, p1.first_name';
			
		break;
		case("balance"):
			
			return 'm.balance asc';
			
		break;
		
		default:
			
			return 'm.member_id asc';
			
	}
}

$condition = '';
function buildCondition(&$condition,$wh) { // Add a clause to the SQL condition string
	
//	if (strlen($condition)>0)
	$condition .= " AND ";
	
	$condition .= " ".$wh. " ";	
}

if ($_REQUEST["uID"]) // We' re searching for a specific member ID in the SQL
	buildCondition($condition,"m.member_id='".trim($_REQUEST["uID"])."'");

if ($_REQUEST["uName"]) { // We're searching for a specific username in the SQL
	
	$uName = trim($_REQUEST["uName"]);

	// Does it look like we've been provided with a first AND last name?
	$uName = explode(" ",$uName);
	foreach($uName as $n){
		$nameSrch = "p1.first_name like '%".trim($n)."%'";
		$nameSrch .= " or p1.last_name like '%".trim($n)."%'";
		//CT: searching joint member too as an improvement
		$nameSrch .= " or p2.first_name like '%".trim($n)."%'";
		$nameSrch .= " or p2.last_name like '%".trim($n)."%'";
	}	
	
	buildCondition($condition,"(".$nameSrch.")");
}

if ($_REQUEST["uLoc"]) // We're searching for a specific Location in the SQL
	buildCondition($condition,"(p1.address_post_code like '%".trim($_REQUEST["uLoc"])."%' OR p1.address_street2 like '%".trim($_REQUEST["uLoc"])."%' OR p1.address_city like '%".trim($_REQUEST["uLoc"])."%' OR p1.address_country like '%".trim($_REQUEST["uLoc"])."%')");

$order=$_REQUEST["orderBy"];

$query = $cDB->Query("SELECT 
	m.balance as balance, 
	concat(p1.first_name, \" \", p1.last_name, if(p2.first_name is not null, concat(\" and \", p2.first_name, \" \", p2.last_name),\"\")) as all_names,
	p1.first_name as first_name, 
	p1.last_name as last_name, 
	p1.email as email, 
	p2.email as p2_email, 
	p2.first_name as p2_first_name, 
	p2.mid_name as p2_mid_name, 
	p2.last_name as p2_last_name, 
	p1.phone1_number as phone1_number, 
	p1.primary_member as primary_member, 
	p2.primary_member as p2_primary_member, 
	p2.phone1_number as p2_phone1_number, 
	p1.address_street2 as address_street2, 
	p1.address_city as address_city,
	p1.address_post_code as address_post_code, 
	m.member_id as member_id,  
	m.account_type as account_type 
	FROM member m 
	left JOIN person p1 ON m.member_id=p1.member_id 
	left JOIN (select * from person where person.primary_member = 'N') p2 on p1.member_id=p2.member_id 
	where p1.primary_member = 'Y' and m.status = 'A' {$condition} 
	ORDER BY " . order($order));
//$query = $cDB->Query("SELECT m.balance as balance, p1.first_name as first_name, p1.last_name as last_name, p1.email as email, p2.email as p2_email, p2.first_name as p2_first_name, p2.mid_name as p2_mid_name, p2.last_name as p2_last_name, p1.phone1_number as phone1_number, p1.phone2_number as phone2_number, p1.primary_member as primary_member, p2.primary_member as p2_primary_member, p2.phone1_number as p2_phone1_number,p2.phone2_number as p2_phone2_number, p1.address_street2 as address_street2, p1.address_city as address_city,p1.address_post_code as address_post_code, m.member_id as member_id, m.account_type as account_type, m.account_type as account_type FROM member m left JOIN person p1 ON m.member_id=p1.member_id left JOIN (select * from person where  person.primary_member = 'N') p2 on p1.member_id=p2.member_id where p1.primary_member = 'Y' and m.status = 'A' order by m.member_id");
$i=0;

while($row = mysql_fetch_array($query)) // Each of our SQL results
{
	//echo $row['balance'];
	$member_list->members[$i] = new cMember;	
	$member_list->members[$i]->ConstructMember($row); 	
	$i++;
}
		
$i=0;

if($member_list->members) {

	foreach($member_list->members as $member) {
		// RF next condition is a hack to disable display of inactive members
		if($member->getStatus() != "I" || SHOW_INACTIVE_MEMBERS==true)  { // force display of inactive members off, unless specified otherwise in config file
		
			if($member->getAccountType() != "F") {  // Don't display fund accounts
				
		//CT all members on the page, use javascript to filter

				//CT: use css styles not html colors - cleaner
				$rowclass = ($i % 2) ? "even" : "odd";
	
				//$postcode = $member->getPrimaryPerson()->getAddressPostCode());
				
				$output .="<tr class='{$rowclass}'>
				   <td>{$member->MemberLink()}</td>
				   <td>{$member->getAllNames()}</td>
				   <td>{$member->AllPhones()}";
				if (MEM_LIST_DISPLAY_EMAIL==true)  {   
					$output .= "<div>{$member->AllEmails()}</div>";
				}
				$output .="</td><td>{$member->getPrimaryPerson()->getAddressStreet2()}";
				if (!empty(trim($member->getPrimaryPerson()->getAddressStreet2())) AND !empty(trim($member->getPrimaryPerson()->getAddressCity()))){
					$output .= ", ";
				}
				$output .= "{$member->getPrimaryPerson()->getAddressCity()}</td>
					<td>{$member->getPrimaryPerson()->getSafePostCode()}</td>";
		
				
				//if (MEM_LIST_DISPLAY_BALANCE==true || $cUser->member_role >= 1){
					$output .= "<td class='units balance'>{$member->getBalance()}</td>";
				//}
				$output .= "</tr>";
				$i+=1;
		 }
	 } // end loop to force display of inactive members off
}
} 

// $output .= "</TABLE>";
// RF display active accounts 
$output .= "</table><div class='summary'>Total of {$i} active accounts.</div>";

//$p->DisplayPage($output); 
$p->DisplayPage($output); 

include("includes/inc.events.php");
?>
