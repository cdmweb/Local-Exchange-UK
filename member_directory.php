<?php

include_once("includes/inc.global.php");
$p->site_section = SECTION_DIRECTORY;
$p->page_title = "Member Directory";

$cUser->MustBeLoggedOn();

//include_once("classes/class.listing.php");

//[chris] Search function
if (SEARCHABLE_MEMBERS_LIST==true) {
	
	$output = "<form action=member_directory.php method=get>";
	$output .= "Member ID: <input type=text name=uID size=4 value='".$_REQUEST["uID"]."'>
		<br>Name (all or part): <input type=text name=uName value='".$_REQUEST["uName"]."'>
		<br>Location (e.g. ".DEFAULT_CITY."): <input type=text name=uLoc value='".$_REQUEST["uLoc"]."'>";
	
	$orderBySel = array();
	$orderBySel["".$_REQUEST["orderBy"].""]='selected';
	
	$output .= "<br>Order by: <select name='orderBy'>
	<option value='idA' ".$orderBySel["idA"].">Membership No.</option>
		<option value='fl' ".$orderBySel["fl"].">First Name</option>
		<option value='lf' ".$orderBySel["lf"].">Last Name</option>
		<option value='nh' ".$orderBySel["nh"].">Neighbourhood</option>
		<option value='loc' ".$orderBySel["loc"].">Town</option>
		<option value='pc' ".$orderBySel["pc"].">PostCode</option>
		</select>";
	$output .= "<p><input type=submit value='Search'></form>"; 
}

$output .=
"<table class=\"tabulated\">
	<tr>
		<th class='id' colspan='2'>Member</th>
		<th>Phone</th>";
	if (MEM_LIST_DISPLAY_EMAIL==true OR $cUser->member_role >= 1)  {   
		$output .= "<th>Email</th>";
	}
	$output .="
		<th>" . ADDRESS_LINE_2 . ",<br /> " . ADDRESS_LINE_3 . "</th>
		<th>" . ZIP_TEXT . "</th>";

if (MEM_LIST_DISPLAY_BALANCE==true || $cUser->member_role >= 1)  {   
	$output .= "<th>Balance</th>";

}
$output .= "</tr>";

//Phones (comma separated with first name in parentheses for non-primary phones)
//Emails (comma separated with first name in parentheses for non-primary emails)

$member_list = new cMemberGroup();
//$member_list->LoadMemberGroup();

// How should results be ordered?
switch($_REQUEST["orderBy"]) {
	
	case("pc"):
		
		$orderBy = 'ORDER BY address_post_code asc';
		
	break;
	
	case("nh"):
		
		$orderBy = 'ORDER BY address_street2 asc';
		
	break;
	
	case("loc"):
		
		$orderBy = 'ORDER BY address_city asc';
		
	break;
	
	case("fl"):
		
		$orderBy = 'ORDER BY first_name, last_name';
		
	break;
	
	case("idD"):
		
		$orderBy = 'ORDER BY member_id desc';
		
	break;
	
	case("lf"):
		
		$orderBy = 'ORDER BY last_name, first_name';
		
	break;
	
	default:
		
		$orderBy = 'ORDER BY member_id asc';
		
	break;
}

// SQL condition string
$condition = '';

function buildCondition(&$condition,$wh) { // Add a clause to the SQL condition string
	
//	if (strlen($condition)>0)
		$condition .= " AND ";
	
	$condition .= " ".$wh. " ";	
}

if ($_REQUEST["uID"]) // We' re searching for a specific member ID in the SQL
	buildCondition($condition,"member.member_id='".trim($_REQUEST["uID"])."'");

if ($_REQUEST["uName"]) { // We're searching for a specific username in the SQL
	
	$uName = trim($_REQUEST["uName"]);

	// Does it look like we've been provided with a first AND last name?
	$uName = explode(" ",$uName);
	
	$nameSrch = "person.first_name like '%".trim($uName[0])."%'";
	
	if ($uName[1]) { // surname provided
		
		$nameSrch .= " OR person.last_name like '%".trim($uName[1])."%'";
		
	}
	else // No surname, but term entered may be surname so apply to that too
		$nameSrch .= " OR person.last_name like '%".trim($uName[0])."%'";
	
	
	buildCondition($condition,"(".$nameSrch.")");
}

if ($_REQUEST["uLoc"]) // We're searching for a specific Location in the SQL
	buildCondition($condition,"(person.address_post_code like '%".trim($_REQUEST["uLoc"])."%' OR person.address_street2 like '%".trim($_REQUEST["uLoc"])."%' OR person.address_city like '%".trim($_REQUEST["uLoc"])."%' OR person.address_country like '%".trim($_REQUEST["uLoc"])."%')");
	
// DEBUG: 
//ECHO "SELECT ".DATABASE_MEMBERS.".member_id FROM ". DATABASE_MEMBERS .",". DATABASE_PERSONS." WHERE ". DATABASE_MEMBERS .".member_id=". DATABASE_PERSONS.".member_id AND primary_member='Y' ".$condition." $orderBy";

// Do search in SQL
//$query = $cDB->Query("SELECT ".DATABASE_MEMBERS.".member_id FROM ". DATABASE_MEMBERS .",". DATABASE_PERSONS." WHERE ". DATABASE_MEMBERS .".member_id=". DATABASE_PERSONS.".member_id AND primary_member='Y' ".$condition." $orderBy;");

$query = $cDB->Query("SELECT m.balance as balance, p1.first_name as first_name, p1.last_name as last_name, p1.email as email, p2.email as p2_email, p2.first_name as p2_first_name, p2.last_name as p2_last_name, p1.phone1_number as phone1_number, p1.primary_member as primary_member, p2.primary_member as p2_primary_member, p2.phone1_number as p2_phone1_number, p1.address_street2 as address_street2, p1.address_city as address_city,p1.address_post_code as address_post_code, m.member_id as member_id, m.account_type as account_type, m.account_type as account_type FROM member m left JOIN person p1 ON m.member_id=p1.member_id left JOIN (select * from person where  person.primary_member = 'N') p2 on p1.member_id=p2.member_id where p1.primary_member = 'Y' and m.status = 'A' order by m.member_id");
$i=0;

while($row = mysql_fetch_array($query)) // Each of our SQL results
{
	//echo $row['balance'];
	$member_list->members[$i] = new cMember;	
	$member_list->members[$i]->SetMember($row); 	
	if ($row['account_type']=='J'){
		$person2Array = array(
			'first_name'=>$row['p2_first_name'],
			'last_name'=>$row['p2_last_name'],
			'phone1_number'=>$row['p2_phone1_number'],
			'email'=>$row['p2_email'],
			'primary_member'=>$row['p2_primary_member']
		);
		$member_list->members[$i]->setPerson($person2Array, 1);
	}

	$i++;
}
		
$i=0;

if($member_list->members) {

	foreach($member_list->members as $member) {
		// RF next condition is a hack to disable display of inactive members
		if($member->status != "I" || SHOW_INACTIVE_MEMBERS==true)  { // force display of inactive members off, unless specified otherwise in config file
		
			if($member->account_type != "F") {  // Don't display fund accounts
				
		//CT all members on the page, use javascript to filter

				//CT: use css styles not html colors - cleaner
				$rowclass = ($i % 2) ? "even" : "odd";
				//$rowclass .= ($member->primary_member != 'Y') ? " joint" : "";
				//$name = $member->AllNames();
				//$showIfJoint = ($member->primary_member != 'Y') ? "<br />(Joint Member)" : "";
				$postcode = SafePostcode($member->person[0]->address_post_code);
				$output .="<tr class='{$rowclass}'>
				   <td>{$member->MemberLink($name)}</td>
				   <td>{$member->AllNames()}</td>
				   <td>{$member->AllPhones()}</td>";
				if (MEM_LIST_DISPLAY_EMAIL==true || $cUser->member_role > 1)  {   
					$output .= "<td>{$member->AllEmails()}</td>";
				}
				$output .="<td>{$member->person[0]->address_street2}";
				if (!empty(trim($member->person[0]->address_street2)) AND !empty(trim($member->person[0]->address_city))){
					$output .= ", ";
				}
				$output .= "{$member->person[0]->address_city}</td>
					<td>{$postcode}</td>
			   ";
		
				
				//if (MEM_LIST_DISPLAY_BALANCE==true || $cUser->member_role >= 1){
					$output .= "<td>{$member->balance}</td>";
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

$p->DisplayPage($output); 

include("includes/inc.events.php");
?>
