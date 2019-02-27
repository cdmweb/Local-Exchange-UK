<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->Logout();
$string = "location:{HTTP_BASE}/member_login.php";
$string = $p->ReplacePlaceholders($string);

header($string);
	exit;	

?>
