<?php
include_once("includes/inc.global.php");
//include_once("class.trade.php");
include_once("classes/class.listing.php");
$p->site_section = PROFILE;

$cUser->MustBeLoggedOn();
//default to current user if not set

$isLoaded = false;
$member = new cMemberSummary();
$member_id = (!empty($_REQUEST["member_id"]))? $cDB->EscTxt($_REQUEST["member_id"]) : $member_id=$cUser->getMemberId();
if(!empty($member_id)){
    $condition = "m.member_id=\"{$member_id}\" AND m.status=\"A\"";
    $member->Load($condition);
    if(!empty($member->getMemberId())) $isLoaded = true; 
}    
if(!$isLoaded){
    $cErr->Error("member ID {$member_id} was not found");
    include("redirect.php");
}

$output = "";
//if($cUser->getMemberRole() > 1){
    $form_action = (!empty($_REQUEST["form_action"])) ? $_REQUEST["form_action"] : null;
    if($form_action == "saved") {
        $output .= "<div class=\"response success\">Your changes have been saved.</div>";
    } 
//}

$isLoaded = false;
$member = new cMemberSummary();
$listing_id = $cDB->UnEscTxt($_REQUEST['listing_id']);
if(!empty($listing_id)){
    $condition = "listing_id={$listing_id} AND m.status='A'";
    $listing->Load($condition);
    if(!empty($listing->__get('listing_id'))) $isLoaded = true; 
}    

$member = new cMemberSummary;
$condition = " p1.primary_member = 'Y' and m.member_id='{$member_id}' and m.status='A'";

//$order_by = "p1.first_name ASC";

$member->Load($condition);
if($member)

//$cErr->Error(print_r($member, true));
//$status_label = ($member->getStatus() == "I") ? " - Inactive" : "";
$p->page_title = "{$member->getDisplayName()} (#{$member_id})";



// $output .= $member->DisplaySummaryMember();

		$stats = new cTradeSummary();
        $stats->Load($member_id);
//        $feedbackSummary = new cFeedbackSummary;
//       $feedbackSummary->Load($member_id);

        

        $mBalance = $member->getBalance() . " " . strtolower(UNITS);
 
        //CT todo: date format
        $mSince = $member->getJoinDate();
        
        //CT person
        //print_r($member->getPrimaryPerson());
        $pName = "{$member->getPrimaryPerson()->getFirstName()} {$member->getPrimaryPerson()->getLastName()}";
        //$agesArr = array("Rather not say","18-30", "30s", "40s" , "50s", "60s", "70s", "Over 80");
        echo($member->getPrimaryPerson()->getAge());
        $pAge = (empty($member->getPrimaryPerson()->getAge())) ? 'Rather not say' : AGE_ARRAY[$member->getPrimaryPerson()->getAge()];
        // todo make in property for localisation
        switch ($member->getPrimaryPerson()->getSex()){
            case "f" :
                $pSex =  'Female';
            break;
            case "m" :
                $pSex =  'Male';
            break;
            default:
                $pSex =  'Rather not say';
        }
        $pAbout = (empty($member->getPrimaryPerson()->getAboutMe())) ? '<em>No description supplied.</em>' : "\"" .stripslashes($member->getPrimaryPerson()->getAboutMe()) . "\"";
        $pEmail = "<a href=\"mailto:{$member->getPrimaryPerson()->getEmail()}\" class=\"normal\">{$member->getPrimaryPerson()->getEmail()}</a>";
        $pPhones = $member->getPrimaryPerson()->getAllPhones();
        $renewal = "{$member->getExpireDate()} {$member->makeExpireRelativeDate()}";
        //CT template

        // CT TODO make better this is aweful
        // append secondary member if exists
        
        
        $joint_member_text ="";
        //print_r($member->getSecondaryPerson()->getDirectoryList());
        if ($member->getAccountType() == "J" && $member->getSecondaryPerson()->getDirectoryList() == "Y"){
            
            //CT strings for display, not meaning
            $pName = "{$member->getSecondaryPerson()->getFirstName()} {$member->getSecondaryPerson()->getLastName()}";

            $joint_member_text ="
                <h3>Joint Member</h3>
                <div class=\"group contact\">
                    <p class=\"line\">
                        <span class=\"label\">Name: </span>
                        <span class=\"value\">{$pName}</span>
                    </p>        <p class=\"line\">
                        <span class=\"label\">Email: </span>
                        <span class=\"value\"><a href=\"mailto:{$member->getSecondaryPerson()->getEmail()}\" class=\"normal\">{$member->getSecondaryPerson()->getEmail()}</a></span>
                    </p>
                    <p class=\"line\">
                        <span class=\"label\">Phone: </span>
                        <span class=\"value\">{$member->getSecondaryPerson()->getAllPhones()}</span>
                    </p>
                </div>  
            ";
        } 
        // but yucky...but gets it done
        include_once (TEMPLATES_PATH . '/menu_quick_edit.php');

        $output .="
            
            <div class=\"profile-wrap detail\">
                <div class=\"profile-inner\">
                    <div class=\"profile-text\">
                        <!--START include member_summary -->

                        <div class=\"member-summary\">    
                            
                            <div class=\"group basic\">
                                <p class=\"line\">
                                    <span class=\"label\">Location: </span>
                                    <span class=\"value\">{$member->getDisplayLocation()}</span>
                                </p>    
                                <p class=\"line\">
                                    <span class=\"label\">Member since: </span>
                                    <span class=\"value\">{$mSince}</span>
                                </p>
                                <p class=\"line\">
                                    <span class=\"label\">Renewal date: </span>
                                    <span class=\"value\">{$renewal}</span>
                                </p>
                            </div>      
                            <div class=\"group activity\">
                                <p class=\"line\">
                                    <span class=\"label\">Balance: </span>
                                    <span class=\"value\">{$mBalance}</span>
                                </p>        
                                <p class=\"line\">
                                    <span class=\"label\">Activity: </span>
                                    <span class=\"value\">{$stats->Display()}</span>
                                </p>
                                <p class=\"line\">
                                    <span class=\"label\">Feedback: </span>
                                    <span class=\"value\">{$member->getFeedback()->Display()}</span>
                                </p>
                            </div>
                        </div>
                        <!--END include member_summary -->
                        <!--START include person_summary -->

                        <div class=\"person-summary\">    
                            <div class=\"group contact\">
                                <p class=\"line\">
                                    <span class=\"label\">Name: </span>
                                    <span class=\"value\">{$pName}</span>
                                </p>        
                                <p class=\"line\">
                                    <span class=\"label\">Email: </span>
                                    <span class=\"value\">{$pEmail}</span>
                                </p>
                                <p class=\"line\">
                                    <span class=\"label\">Phone: </span>
                                    <span class=\"value\">{$pPhones}</span>
                                </p>
                            </div>  
                            <div class=\"group social\">
                                <p class=\"line\">
                                    <span class=\"label\">Age: </span>
                                    <span class=\"value\">{$pAge}</span>
                                </p>
                                <p class=\"line\">
                                    <span class=\"label\">Gender: </span>
                                    <span class=\"value\">{$pSex}</span>
                                </p>
                            </div>

                        </div>
                        <!--END include person_summary -->
                        {$joint_member_text}
                    </div>
                    <div class=\"profile-avatar\">
                        
                        <img src=\"{$member->getPhoto()}\" alt=\"{$member->getDisplayName()}\" />
                    </div>
                </div>
                <div class=\"profile-about\">
                   {$pAbout}
                </div>

            </div>
        ";
            

        

 if(!empty($member_id)){
 	// CT show offers
	$output .= "<h2>" . OFFER_LISTING_HEADING . "</h2>";
    
	$listings = new cListingGroup();
    // Load($member_id, $category_id, $since, $include_expired, $status, $type)
    $condition = $listings->makeFilterCondition($member_id, null, null, null, null, OFFER_LISTING);
	$listings->Load($condition);

	$output .= $listings->Display(false);
	// 	// CT Show want
    $output .= "<h2>" . WANT_LISTING_HEADING . "</h2>";
	$listings = new cListingGroup();
    $condition = $listings->makeFilterCondition($member_id, null, null, null, null, WANT_LISTING);
    $listings->Load($condition);
	$output .= $listings->Display(false);
 } 

$p->DisplayPage($output); 

?>
