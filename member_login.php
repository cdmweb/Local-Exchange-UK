<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

if($cUser->IsLoggedOn())
{
	$list = "Welcome to ". SITE_LONG_TITLE .", ". $cUser->PrimaryName() ."!";
	
	if ($cUser->AccountIsRestricted())
		$list .= "hi<p>".LEECH_NOTICE;
}
else 
{
	$list = $cUser->UserLoginPage();
}

$p->DisplayPage($list);

?>
