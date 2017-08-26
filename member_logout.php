<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->Logout();

//CT: forward to the login page
header("location:http://".HTTP_BASE."/member_login.php");
exit;

?>
