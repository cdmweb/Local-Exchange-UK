<?php

include_once("includes/inc.global.php");
$p->site_section = SECTION_DIRECTORY;
$p->page_title = "Members";

$cUser->MustBeLoggedOn();



//[chris] Search function
//[CT] built on it for readability

	$vars=array();
	$vars['member_id'] = 'Membership id';
	$vars['first_name'] = 'First Name';
	$vars['last_name'] = 'Last Name';
	$vars['address_street2'] = 'Neighbourhood';
	$vars['address_post_code'] = 'Postcode';
	$vars['address_city'] = 'Town/City';
	$vars['balance'] = 'Balance';
	$vars['expiry_date'] = 'Expiry date';

		//select fom element
		$order_by_selector = $p->PrepareFormSelector('order', $vars, null, $_REQUEST["order"]);

	$output = "
	<form class=\"layout1 summary\" action=\"member_directory.php\" method=\"get\" name=\"form1\" id=\"form1\">

		<p class=\"l_text\">
			<label>
				<span>Filter by:</span>
				<input type='text' name='filter' id='filter' placeholder='Name/s, neighbourhood or postcode' value='".$_REQUEST["filter"]."'>
			</label>
		</p>
		<p class=\"l_text\">
			<label>
				<span>Order by:</span>
				{$order_by_selector}
			</label>
		</p>
		<input name=\"submit\" value=\"Go\" type=\"submit\" />
	</form>


	";

	$members = new cMemberGroup();
	$condition = $members->makeActiveMemberFilter();
	//$members->Load($condition, $order);
	if(!empty($_REQUEST['filter'])){
		//split by commas or spaces
		$filters = explode( ", ", $_REQUEST['filter']);
		if(sizeof($filters) == 1) $filters = explode( ",", $_REQUEST['filter']);
		if(sizeof($filters) == 1) $filters = explode( " ", $_REQUEST['filter']);


		foreach ($filters as $key => $like) {
			$condition .= ($key==0) ? " AND " : " OR ";
			$condition .= "p1.first_name LIKE '{$like}' OR ";
			$condition .= "p2.first_name LIKE '{$like}' OR ";
			$condition .= "p1.last_name LIKE '{$like}' OR ";
			$condition .= "p2.last_name LIKE '{$like}' OR ";
			$condition .= "p1.address_street2 LIKE '{$like}' OR ";
			$condition .= "p1.address_city LIKE '{$like}'";
		}
	}
	$order_by = "m.member_id";
	if(!empty($_REQUEST['order'])){
		$order = $_REQUEST['order'];
		switch ($order) {
			case 'first_name':
				$order_by = "p1.first_name";
				break;
			case 'last_name':
				$order_by = "p1.last_name";
				break;
			case 'address_street2':
				$order_by = "p1.address_street2";
				break;
			case 'address_city':
				$order_by = "p1.address_city";
				break;
			case 'address_post_code':
				$order_by = "p1.address_post_code";
				break;
			case 'expiry_date':
				$order_by = "m.expiry_date";
				break;
			case 'balance':
				$order_by = "m.balance";
				break;


		}
		
		
	}

	$members->Load($condition, $order_by);

	$row_output = "";
	foreach($members->getMembers() as $member) {
		//CT: use css styles not html colors - cleaner
		$rowclass = ($i % 2) ? "even" : "odd";

		//$postcode = $member->getPrimaryPerson()->getAddressPostCode());
		
		$row_output .="<tr class='{$rowclass}'>
		   <td><a href=\"\">{$member->MemberLink()}</a></td>
		   <td>{$member->getDisplayName()}</td>
		   <td>{$member->getDisplayPhone()}";
		if (MEM_LIST_DISPLAY_EMAIL==true)  {   
			$row_output .= "<div>{$member->getDisplayEmail()}</div>";
		}
		$row_output .="</td><td>{$member->getPrimaryPerson()->getAddressStreet2()}";
		if (!empty(trim($member->getPrimaryPerson()->getAddressStreet2())) AND !empty(trim($member->getPrimaryPerson()->getAddressCity()))){
			$row_output  .= ", ";
		}
		$row_output .= "{$member->getPrimaryPerson()->getAddressCity()}</td>
			<td>{$member->getPrimaryPerson()->getSafePostCode()}</td>";

		
		//if (MEM_LIST_DISPLAY_BALANCE==true || $cUser->member_role >= 1){
			$row_output .= "<td class='units balance'>{$member->getBalance()}</td>";
		//}
		$row_output .= "</tr>";
		$i+=1;
	
	 } // end loop to force display of inactive members off


$output .="<table class=\"tabulated\">
	<tr>
		<th class='id' colspan='2'>Member</th>
		<th>Contact</th>
		<th>" . ADDRESS_LINE_2 . ",<br /> " . ADDRESS_LINE_3 . "</th>
		<th>" . ZIP_TEXT . "</th>";

if (MEM_LIST_DISPLAY_BALANCE==true || $cUser->getMemberRole() >= 1)  {   
	$output .= "<th class='units balance'>Balance</th>";

}
$output .= "</tr>
			{$row_output}
		</table>
		<div class='summary'>Total of {$i} active accounts.</div>";

//$p->DisplayPage($output); 
$p->DisplayPage($output); 

include("includes/inc.events.php");
?>
