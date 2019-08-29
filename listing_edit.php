<?php

include_once("includes/inc.global.php");  

$cUser->MustBeLoggedOn();

//listing with extras
$listing = new clistingEdit();



//safely get values
$is_loaded = false;

$form_action = "create";
if(!empty($_REQUEST["listing_id"])){
	$listing_id =  $cDB->EscTxt($_REQUEST['listing_id']);
	$form_action = "update";
	$condition = "p1.primary_member = 'Y' and m.status = 'A' AND listing_id={$listing_id}";
	$is_loaded = $listing->Load($condition);
	if(!$is_loaded){
		$cErr->Error("Cannot load id '{$listing_id}'");
		//$redir_url="index.php";
		//include("redirect.php");
	}
	// only allow committee and above to edit other people's ads
	if(($cUser->getMemberRole() == 0)&& ($listing->getMemberId() != $cUser->getMemberId())){
		$cErr->Error("You don't have permission to edit this listing");
		$redir_url="listing_detail.php?listing_id={$listing_id}";
	  	include("redirect.php");
	}
	$member_id = $listing->getMemberId();
	$type = $listing->getType();
	$typeDescription = $listing->getTypeDescription();

}



// //admin action... only for committee and above
// $form_mode = (!empty($_REQUEST["form_mode"]) && $cUser->getMemberRole()>0) ? $cDB->EscTxt($_REQUEST['form_mode']) : null;

// //load from id
// $is_loaded = false;
// if(!empty($listing_id) ){
// 	$condition = "p1.primary_member = 'Y' and 
//         m.status = 'A' AND listing_id={$listing_id}";
// 	$is_loaded = $listing->Load($condition);
// }



//allow extra controls
if(!empty($form_mode)) $listing->setFormMode($form_mode);

if(!empty($listing_id)){

	// CT user must match
	$page_title = "Edit '{$typeDescription}': {$listing->getTitle()}";
	//CT doesnt go through build function - todo - should it?
	$listing->setFormAction("update");
}else{
	//ct hack - just make sure only these 2 values possible
	//if($type == "W") $typeDescription = "Want";
	//else $type == "Offer";
	$typeDescription = ($type == "W") ? "Want" : "Offer";
	$page_title = "Create new '{$typeDescription}' listing";
	//CT doesnt go through build function - todo - should it?
	$listing->setFormAction("create");
}
	$p->page_title = $page_title;





// if form submitted
if ($_POST["submit"]){
	//build object from inputs
	$listing->Build($_POST);

	// error catching without PEAR is a bit of a faff, but cant use PEAR anymore.
	$error_message = "";
	if(strlen($listing->getTitle()) < 1) $error_message .= "Title is missing. ";
	if(empty($listing->getCategoryId())) $error_message .= "Category is missing. ";

	//check if errors and save
	$is_saved = 0;
	if(empty($error_message)) $is_saved = $listing->Save();
	else $cErr->Error($error_message);
	

	if($is_saved){
		//redirect page if saved	
		$redir_url="listing_detail.php?listing_id={$listing->getListingId()}&";
  		include("redirect.php");
	} 
}



//show form
$member_text ="";
//show member dropdown if in create mode for admin
if($listing->getFormMode()=="admin"){
	$member_text ="<p>
		<label for=\"title\">
			Member<br />
			{$listing->PrepareMemberDropdown()}
		</label>
	</p>";
} else{
	if(!empty($listing->getMemberId()) && $cUser->getMemberId() != $listing->getMemberId()){
		//if done on behalf of someone
		$member_text ="<p class=\"large\">For member {$listing->getMember()->getDisplayName()} (#{$listing->getMember()->getMemberId()})</p>";

	}
}
$output .= "
	<form action=\"/members/listing_edit.php?listing_id={$listing->page_id}\" method=\"post\" name=\"\" id=\"\" class=\"layout2\">
		<input type=\"hidden\" id=\"listing_id\" name=\"listing_id\" value=\"{$listing->getListingId()}\" />
		<input type=\"hidden\" id=\"form_action\" name=\"form_action\" value=\"{$listing->getFormAction()}\" />
		<input type=\"hidden\" id=\"form_mode\" name=\"form_mode\" value=\"{$listing->getFormMode()}\" />
		<!-- <input type=\"hidden\" id=\"active\" name=\"active\" value=\"1\" /> -->
		<input type=\"hidden\" id=\"active\" name=\"status\" value=\"{$listing->getStatus()}\" />
		{$member_text}
		<p>
			<label for=\"title\">
				Title *<br />
				<input maxlength=\"200\" name=\"title\" id=\"title\" type=\"text\" value=\"{$listing->getTitle()}\">
			</label>
		</p>

		<p>
			<label for=\"body\">Description <br />
				<textarea cols=\"80\" rows=\"20\" wrap=\"soft\" name=\"description\" id=\"description\">{$listing->getDescription()}</textarea>
			</label>
		</p>
		<p>
			<label for=\"rate\">
				Rate (and any other variants)<br />
				<input maxlength=\"50\" name=\"rate\" id=\"rate\" type=\"text\" value=\"{$listing->getRate()}\">
			</label>
		</p>		
		<p>
			<label for=\"body\">Category *<br />
				{$listing->PrepareCategoryDropdown()}
			</label>
		</p>			

		<p>
			<input name=\"submit\" id=\"submit\" value=\"Submit\" type=\"submit\" />
			* denotes a required field
		</p>
	</form>";


$p->DisplayPage($output);

?>
