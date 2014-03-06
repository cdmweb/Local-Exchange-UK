<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;

$output = "In order to access this section of the site, you need to be logged on.<BR><BR>If you already have an account, please log in below:<BR><BR><CENTER><DIV STYLE='width=60%; padding: 5px;'><FORM ACTION=".SERVER_PATH_URL."/login.php METHOD=POST><INPUT TYPE=HIDDEN NAME=action VALUE=login><INPUT TYPE=HIDDEN NAME=location VALUE='".$_SESSION["REQUEST_URI"]."'><TABLE class=NoBorder><TR><TD ALIGN=RIGHT>Member ID:</TD><TD ALIGN=LEFT><INPUT TYPE=TEXT SIZE=12 NAME=user></TD></TR><TR><TD ALIGN=RIGHT>Password:</TD><TD ALIGN=LEFT><INPUT TYPE=PASSWORD SIZE=12 NAME=pass></TD></TR></TABLE><DIV align='right'><INPUT TYPE=SUBMIT VALUE='Login'></DIV></FORM></DIV></CENTER><BR>If you don't have an account, please <A HREF=contact.php>contact</A> us to join.<BR>";

$p->DisplayPage($output);

?>
