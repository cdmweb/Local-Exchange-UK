<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$cUser->Logout();

$list = "You are now logged out.<P>";
$list .= "You can login at any time by clicking on the \"Login\" link at the
               bottom of the left menu.";

$p->DisplayPage($list);

?>
