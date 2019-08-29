<?php

include_once("includes/inc.global.php");

$cUser->MustBeLoggedOn();
$p->site_section = LISTINGS;


//init
$isLoaded = false;
$listing = new cListing();
$listing_id = $cDB->UnEscTxt($_REQUEST['listing_id']);
if(!empty($listing_id)){
	$condition = "listing_id={$listing_id} AND m.status='A'";
	$listing->Load($condition);
	if(!empty($listing->getListingId())) $isLoaded = true; 
}
// halt if loaded
if(!$isLoaded){
	$cErr->Error("Listing ID {$listing_id} was not found");
	include("redirect.php");
}
$member_id=$listing->__get('member_id');
//bloody messy
$member = new cMemberSummary;
$condition = " p1.primary_member = 'Y' and m.member_id='{$member_id}'";

$member->Load($condition); 

//$mLocation = $member->getDisplayEmail() . ", " .$member->getDisplayPhone();
$stats = new cTradeSummary();
$condition="member_id_from 
            LIKE \"{$member_id}\" 
            OR member_id_to 
            LIKE \"{$member_id}\" 
            AND NOT type=\"R\" 
            AND NOT status=\"R\"";
$stats->Load($condition);

$output = "";

$form_action = $cDB->UnEscTxt($_REQUEST['form_action']);
if($form_action == "update") {
    $output .= "
    <div class=\"response success\">
    	Your changes have been saved.
    </div>";
} elseif($form_action == "create"){
    $output .= "
    <div class=\"response success\">
    	New listing created.
    </div>";
}
$adminElements ="";
//allow edit by the logged in user on self, or committee.
if($cUser->getMemberRole() > 0 || $cUser->getMemberId() == $member_id){
    $output .= "
    <div>
    	<a href=\"listing_edit.php?listing_id={$listing->getListingId()}\" class=\"button edit\">
    		<i class=\"fas fa-pencil-alt\"></i> edit
    	</a>
    </div>";
}
	$output .= "<p class='large'>{$listing->getDescription()}</p>";

//$array[]=$this->makeLabelArray($title, $value))
$metadata = "<div class=\"columns2\">".
		$p->WrapLabelValue("Type", $listing->getTypeDescription()) . 
		$p->WrapLabelValue("Category", $listing->getCategoryName()) . 
		$p->WrapLabelValue("Rate", $listing->getRate()) . 
		$p->WrapLabelValue("Listing ID", $listing->getListingId()) . 
		$p->WrapLabelValue("Last update", $listing->getPostingDate()) . 
	"</div>"; 
$hidden = "<!-- ".
		$p->WrapLabelValue("Status", $listing->getStatus()) . 
		$p->WrapLabelValue("Expires", $listing->getExpireDate()) . 
		$p->WrapLabelValue("Reactivation Date", $listing->getReactivateDate()) . 

		"-->"; 

$output .= $metadata . $hidden;
//$output .=$listing->TableFromArray($array);
//TODO - make into little summary object
$output .="<br /><!--START include member_summary -->
<div class=\"profile-wrap\">
	<div class=\"profile-inner\">
		<div class=\"profile-text\">
			<h4><a href=\"member_detail.php?member_id={$member->getMemberId()}\">{$member->getDisplayName()}</a></h4>
			<p>{$member->getDisplayLocation()}</p>
			<p>Phone: {$member->getDisplayPhone()} / Email: {$member->getDisplayEmail()}</p>
			<p>
			<!-- <span class=\"label\">Activity: </span> -->
			<span class=\"value\">{$stats->Display()}</span>
			</p>

			<p><!-- <span class=\"label\">Feedback: </span> -->
			<span class=\"value\">{$member->getFeedback()->Display()}</span></p>
		</div>
		<div class=\"profile-avatar\">
			<a href=\"member_detail.php?member_id={$member->getMemberId()}\"><img src=\"{$member->getPhoto()}\" alt=\"{$member->getDisplayName()}\" class=\"avatar\" /></a>
		</div>
	</div>
</div>	            	
<!--END include member_summary -->

";

$p->page_title = "{$listing->getTypeDescription()}: {$listing->getTitle()}";


$p->DisplayPage($output);

include("includes/inc.events.php");

?>
