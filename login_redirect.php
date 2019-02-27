<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$string = file_get_contents(TEMPLATES_PATH . '/form_login.php', TRUE);
$string = $p->ReplacePropertiesInString($string);

$p->DisplayPage($string);

?>
