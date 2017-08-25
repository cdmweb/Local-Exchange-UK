<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

if($cUser->IsLoggedOn())
{
	header("location:http://".HTTP_BASE."/member_profile_all_in_one.php");
	exit;
}
else 
{
	$list = $cUser->UserLoginPage();
}

$p->DisplayPage($list);

?>
