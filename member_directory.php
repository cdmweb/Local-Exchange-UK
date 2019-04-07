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

	$members = new cMemberGroup();
	$members->Load($condition, $order);


	foreach($members->getMembers() as $member) {
		// RF next condition is a hack to disable display of inactive members
		if($member->getStatus() != "I" || SHOW_INACTIVE_MEMBERS==true)  { // force display of inactive members off, unless specified otherwise in config file
		
				
		//CT all members on the page, use javascript to filter

		//CT: use css styles not html colors - cleaner
		$rowclass = ($i % 2) ? "even" : "odd";

		//$postcode = $member->getPrimaryPerson()->getAddressPostCode());
		
		$output .="<tr class='{$rowclass}'>
		   <td>{$member->MemberLink()}</td>
		   <td>{$member->getDisplayName()}</td>
		   <td>{$member->getDisplayPhone()}";
		if (MEM_LIST_DISPLAY_EMAIL==true)  {   
			$output .= "<div>{$member->getDisplayEmail()}</div>";
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
	
	 } // end loop to force display of inactive members off

} 

// $output .= "</TABLE>";
// RF display active accounts 
$output .= "</table><div class='summary'>Total of {$i} active accounts.</div>";

//$p->DisplayPage($output); 
$p->DisplayPage($output); 

include("includes/inc.events.php");
?>
