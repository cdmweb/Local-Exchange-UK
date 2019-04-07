<?php

include_once("includes/inc.global.php");

$p->site_section = LISTINGS;
$p->page_title = "Choose Category";

include("includes/inc.forms.php");
include_once("classes/class.category.php");

//
// Define form elements
//
$cUser->MustBeLevel(2);

$categories = new cCategoryList;
$category_list = $categories->MakeCategoryArray();
unset($category_list[0]);

$form->addElement("select", "category", "Which Category?", $category_list);
$form->addElement("static", null, null, null);

$buttons[] = &HTML_QuickForm::createElement('submit', 'btnEdit', 'Edit');
$buttons[] = &HTML_QuickForm::createElement('submit', 'btnDelete', 'Delete');
$form->addGroup($buttons, null, null, '&nbsp;');

//
// Define form rules
//


//
// Then check if we are processing a submission or just displaying the form
//
if ($form->validate()) { // Form is validated so processes the data
   $form->freeze();
 	$form->process("process_data", false);
} else {
   $p->DisplayPage($form->toHtml());  // just display the form
}

function process_data ($values) {
	global $p, $cErr;
	
	if(!empty($values["btnDelete"])) {
		$category = new cCategory;
		$category->LoadCategory($values["category"]);
		if($category->HasListings()) {
			$output = "This category still has listings in it.  You will need to move these listings to new categories or delete them before you can delete this category.  Note that the listings could be temporarily inactive or expired, in which case they will not show in the offered/wanted lists.<P>";

			$output .= "Listings in this category:<BR>";
			$listings = new cListingGroup(OFFER_LISTING);
			$listings->LoadListingGroup(null, $values["category"]);
			foreach($listings->listing as $listing)
				$output .= "OFFERED: ". $listing->description ." (". $listing->member_id .")<BR>"; 
				
			$listings = new cListingGroup(WANT_LISTING);
			$listings->LoadListingGroup(null, $values["category"]);
			foreach($listings->listing as $listing)
				$output .= "WANTED: ". $listing->description ." (". $listing->member_id .")<BR>";			
		} else {
			if($category->DeleteCategory())
				$output = "The category has been deleted.";
		}
	} else {
		header("location:".HTTP_BASE."/category_edit.php?category_id=". $values["category"]);
		exit;	
	}
	
	$p->DisplayPage($output);
}

//
// Form rule validation functions
//


?>
