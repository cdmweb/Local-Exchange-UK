<?php
include_once("includes/inc.global.php");
$p->site_section = SITE_SECTION_OFFER_LIST;
$cUser->MustBeLevel(1);

include("includes/inc.forms.php");

$form->addElement("header", null, "Choose Joint Member to Edit");
$form->addElement("html", "<TR></TR>");
    
$sel =& $form->addElement("hierselect", "member", "Choose Member and Person");
$items = new cMemberGroupMenu;
$items->LoadMemberGroup();
$items->MakeMenuArrays();
$sel->setMainOptions($items->id);
$sel->setSecOptions($items->name);

$form->addElement("static", null, null, null);
$buttons[] = &HTML_QuickForm::createElement('submit', 'btnEdit', 'Edit');
$buttons[] = &HTML_QuickForm::createElement('submit', 'btnDelete', 'Delete');
$form->addGroup($buttons, null, null, '&nbsp;');


if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {  // Display the form
	$p->DisplayPage($form->toHtml());
}

function process_data ($values) {
	global $p, $items;
	
	$member = $values["member"];
	if(isset($values["btnDelete"])) {
		header("location:http://".HTTP_BASE."/member_contact_delete.php?mode=admin&person_id=". $items->person_id[$member[0]][$member[1]]);
		exit;	
	} else {
		header("location:http://".HTTP_BASE."/member_contact_edit.php?mode=admin&member_id=".$items->id[$member[0]] ."&person_id=". $items->person_id[$member[0]][$member[1]]);
		exit;	
	}
}

?>
