<?php
include_once("includes/inc.global.php");

$cUser->MustBeLoggedOn();
$p->site_section = LISTINGS;
$p->page_title = "Download Directory";

include_once("classes/class.directory.php");
include("includes/inc.forms.php");

$form->addElement("static", null, "Click on the button below and you will be prompted to open or save a printer-friendly (PDF) version of the directory. If you don't already have it, you can download Adobe Acrobat from <A HREF=\"http://www.tucows.com/preview/194959.html\">here</A>.", null);
$form->addElement("static", null, null, null);
$form->addElement("static", null, "Note that <u>older versions of Acrobat may not be able to read this file</u>.  If you get an error message when trying to download, try upgrading <A HREF=\"http://www.tucows.com/preview/194959.html\">Acrobat</A>. If you have Windows 2000 or XP, you can upgrade to the newest version, but if you have Windows 98, you will want version 6.0.", null);
$form->addElement("static", null, null, null);
$form->addElement("submit", "btnSubmit", "Download");

if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$p->DisplayPage($form->toHtml());
}

function process_data ($values) {
	global $p;

	$dir = new cDirectory();
	$dir->DownloadDirectory();

	$list = "Download complete.";
	$p->DisplayPage($list);
}
?>
