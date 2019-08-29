<?php
include_once("includes/inc.global.php");
$cUser->MustBeLevel(1);

//$p->site_section = ADMINISTRATION;
$p->page_title = "For which member?";


// //$form->addElement("header", null, "For which member?");
// //$form->addElement("html", "<TR></TR>");
// $show_inactive = (!empty($_REQUEST["show_inactive"]))? true : false;
// $action = $_REQUEST["action"];
// $output = "";

// $get_string = "";
// if (isset($_REQUEST["get1"])) $get_string .= "&get1=" . $_REQUEST["get1"];
// if (isset($_REQUEST["get1val"])) $get_string .= "&get1val=" . $_REQUEST["get1val"];

// if(empty($show_inactive)){
// 	$output .= $p->Wrap("<strong>Show active members</strong> | <a href='member_choose.php?action={$action}&show_inactive=true{$get_string}'>Show all members</a>", "p", "small");
// }else{
// 	$output .= $p->Wrap("<a href='member_choose.php?action={$action}{$get_string}'>Show active members</a> | <strong>Show all members</strong>", "p", "small");
// }

$action = (!empty($_REQUEST["action"])) ? ($_REQUEST["action"]):null;
//can only hold one?
$values = (!empty($_REQUEST["values"])) ? ($_REQUEST["values"]):null;

if ($_POST["submit"]){
	$fieldArray = $_POST;
	processData($fieldArray);
}

$members = new cMemberGroup;

$condition = $members->makeActiveMemberFilter();
$order_by="p1.first_name ASC";
$members->Load($condition, $order_by);
//$members->Load($condition, $order_by);

$output .= "
	<form action=\"\" method=\"post\" name=\"\" id=\"\" class=\"layout2\">
		<input type=\"hidden\" id=\"action\" name=\"action\" value=\"{$action}\" />
		<input type=\"hidden\" id=\"extras\" name=\"location\" value=\"{$extras}\" />
		{$member_text}
		<p>
        	<label for=\"member_id\">
	            <span>Member *</span>
	            {$members->PrepareMemberDropdown()}
	        </label>
	    </p>	
		<p>
			<input name=\"submit\" id=\"submit\" value=\"Submit\" type=\"submit\" />
			* denotes a required field
		</p>
	</form>";


$p->DisplayPage($output);
	
// if ($form->validate()) { // Form is validated so processes the data
//    $form->freeze();
//  	$form->process("process_data", false);
// } else {  // Display the form
// 	$output .= $form->toHtml();
// 	
// }

function processData ($fieldArray) {
	//print_r($fieldArray);
	$redir_url="{$fieldArray['action']}.php?mode=admin&member_id={$fieldArray['member_id']}";
	//print_r($redir_url);
	//$redir_url="{$fieldArray['action']}.php?mode=admin&member_id={$$fieldArray['member_id']}";
  	include("redirect.php");
	exit;	
}

?>
