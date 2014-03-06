<?php

include_once("includes/inc.global.php");
include("classes/class.info.php");
include("includes/inc.forms.php");

$cUser->MustBeLevel(2);

$p->site_section = EVENTS;

$p->page_title = "Manage Account Restrictions";

$output = "Restrictions can be placed on accounts if you feel they are over-using the services of others and not
 offering enough back in return.<p>";

$query = $cDB->Query("SELECT * FROM ". DATABASE_MEMBERS .",". DATABASE_PERSONS." WHERE ". DATABASE_MEMBERS .".member_id=". DATABASE_PERSONS.".member_id". $exclusions. " AND primary_member='Y' ORDER BY first_name, last_name;");

$members  = array();
		
$i=0;

while($row = mysql_fetch_array($query))
{
			$members[$i] =$row;
			
			$i += 1;
}

$restrictedM = Array();
$okM = Array();

foreach($members as $m) {
	
	if ($m["restriction"]==1)
		$restrictedM[] = $m;
	else
		$okM[] = $m;
}

if ($_REQUEST["process"]) {
	
	$typ = '';
	
	if ($_REQUEST["doRestrict"])
		$typ = 'restrict';
	else if ($_REQUEST["liftRestrict"])
		$typ = 'lift';
		

	switch($typ) {
		
		case("restrict"):
		
		if (!$_REQUEST["ok"]) {
				
				$output .= "Error: No member ID specified.";
				
				break;
			}
			
			$member = new cMember;
			$member->LoadMember($_REQUEST["ok"]);
	
			$query = $cDB->Query("UPDATE ". DATABASE_MEMBERS ." set restriction=1 WHERE member_id=".$cDB->EscTxt($_REQUEST["ok"])."");
			
			if (!$query)
				$output .= "Error: could not impose restrictions on this account.<p>MySQL Said: ".mysql_error();
			else {
				$output .= "Restrictions have been imposed 
				on member id '".$_REQUEST["ok"]."'";
				
				$mailed = mail($member->person[0]->email, "Access Restricted on ".SITE_LONG_TITLE."", LEECH_EMAIL_URLOCKED , EMAIL_FROM);
			
			}
			
		break;
		
		case("lift"):
			
			if (!$_REQUEST["restricted"]) {
				
				$output .= "Error: No member ID specified.";
				
				break;
			}
			
			$member = new cMember;
			$member->LoadMember($_REQUEST["restricted"]);
	
			$query = $cDB->Query("UPDATE ". DATABASE_MEMBERS ." set restriction=0 WHERE member_id=".$cDB->EscTxt($_REQUEST["restricted"])."");
			
			if (!$query)
				$output .= "Error: could not lift restrictions on this account.<p>MySQL Said: ".mysql_error();
			else {
				$output .= "Restrictions have been lifted 
				on member id '".$_REQUEST["restricted"]."'";
				
				$mailed = mail($member->person[0]->email, "Account Restrictions lifted on ".SITE_LONG_TITLE."", LEECH_EMAIL_URUNLOCKED , EMAIL_FROM);
			}
			
		break;
	}
	$p->DisplayPage($output);
	
	exit;
}

$output .= "<form method=POST><input type=hidden name=process value='actionOnMember'>";

$output .= "<font color=green>Non-Restricted</font> Members<br>";
$output .= "<select name=ok>";

foreach($okM as $key => $m) {

	$output .= "<option value='".$m["member_id"]."'>".$m["first_name"]." ".$m["last_name"]."</option>";
}

$output .= "</select>";
$output .= "<input name='doRestrict' type=submit value='Impose Restriction'>";

$output .= "<p><font color=red>Restricted</font> Members<br>";
$output .= "<select name=restricted>";

foreach($restrictedM as $key => $m) {

	$output .= "<option value='".$m["member_id"]."'>".$m["first_name"]." ".$m["last_name"]."</option>";
}

$output .= "</select>";
$output .= "<input name='liftRestrict' type=submit value='Lift Restriction'>";


$output .= "</form>";

$p->DisplayPage($output);
?>