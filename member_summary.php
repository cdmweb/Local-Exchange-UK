<?php
include_once("includes/inc.global.php");
//include_once("class.trade.php");
include_once("classes/class.listing.php");
$p->site_section = PROFILE;

$cUser->MustBeLoggedOn();
//$member_id = $cUser->getMemberId();
if(!empty($_REQUEST["member_id"])){
	$member_id = $cDB->EscTxt($_REQUEST["member_id"]);
} else{
	$member_id=$cUser->getMemberId();
}

$member = new cMemberSummary; 
$member->Load($member_id);
//$cErr->Error(print_r($member, true));
//$status_label = ($member->getStatus() == "I") ? " - Inactive" : "";
$p->page_title = "{$member->getDisplayName()} (#{$member_id})";

$output = "<p><a href='member_summary.php?member_id={$member_id}'>Profile</a> | <a href='trade_history.php?member_id={$member_id}'>Trade history</a></p>";
 if ($cUser->getMemberRole() ){
 	$string = file_get_contents(TEMPLATES_PATH . '/menu_quick_edit.php', TRUE);
 	$output .= $p->ReplaceVarInString($string, '$member_id', $member_id);
 }
// $output .= $member->DisplaySummaryMember();

		$stats = new cTradeStatsCT($member_id);
        $feedbackgrp = new cFeedbackGroup($member_id);
        //$feedbackgrp->LoadFeedbackGroup($member_id);
        $variables = new stdClass();
        $variables = (object) [
            'member_id' => $member_id,
            'member_display_name' => $member->getDisplayName(),
            'avatar_image' => $member->getMemberImage(),
            'member_balance' => "{$member->getBalance()} " . strtolower(UNITS),
            'member_since' => $member->getJoinDate(),
            'member_activity' => (empty($stats->getTradeLastDate())) ? "No exchanges yet" : "<a href=\"trade_history.php?member_id={$member_id}\">{$stats->getTradeTotalCount()} exchanges total</a> for a sum of {$stats->getTradeTotalAmount()} ". strtolower(UNITS) . ", last traded on ". $stats->getTradeLastDate(),
            'member_location' => $member->getPrimaryPerson()->getAddressStreet2() . ", " .$member->getPrimaryPerson()->getSafePostCode(),
            'member_feedback' => (empty($feedbackgrp->num_total)) ? "No feedback yet" : "<a href='feedback_all.php?mode=other&member_id={$member_id}'>{$feedbackgrp->PercentPositive()}% positive</a> ({$feedbackgrp->num_total} total, {$feedbackgrp->num_negative} negative &amp; {$feedbackgrp->num_neutral} neutral)",
       ];
        $string=file_get_contents(TEMPLATES_PATH . '/member_summary.php', TRUE);
        $output = $p->ReplacePlaceholders($string, $variables);
       
       //CT strings for display, not meaning
        $pName = "{$member->getPrimaryPerson()->getFirstName()} {$member->getPrimaryPerson()->getLastName()}";
        $pAge = (empty($member->getPrimaryPerson()->getAge())) ? '-' : $agesArr[$member->getPrimaryPerson()->getAge()];
        $pGender = (empty($member->getPrimaryPerson()->getSex())) ? '-' : $sexArr[$member->getPrimaryPerson()->getSex()];
        $pAbout = (empty($member->getPrimaryPerson()->getAboutMe())) ? '<em>No description supplied.</em>' : stripslashes($member->getPrimaryPerson()->getAboutMe());

        //member;
        $variables = new stdClass();
        $variables = (object) [
            'person_name' => "{$pName}",
            'person_age' => "{$pAge}",
            'person_gender' => "{$pGender}",
            'person_about_me' => "{$pAbout}", 
            'person_email' => "{$member->getPrimaryPerson()->getEmail()}",
            'person_phone' => "{$member->getPrimaryPerson()->getAllPhones()}"
        ];
        $string=file_get_contents(TEMPLATES_PATH . '/person_summary.php', TRUE);
        $output .= $p->ReplacePlaceholders($string, $variables);

        // CT TODO make better this is aweful
        // append secondary member if exists
        if ($member->getAccountType() == "J" && $member->getSecondaryPerson()->getDirectoryList() == "Y"){
            //CT strings for display, not meaning
            $pName = "{$member->getSecondaryPerson()->getFirstName()} {$member->getSecondaryPerson()->getLastName()}";

            $variables = new stdClass();
            $variables = (object) [
               'person_name' => "{$pName}",
               'person_email' => "{$member->getSecondaryPerson()->getEmail()}",
               'person_phone' => "{$member->getSecondaryPerson()->getAllPhones()}"
            ];
            $string='
				<h3>Joint Member</h3>
				<div class="group contact">
					<p class="line">
						<span class="label">Name: </span>
						<span class="value">{{person_name}}</span>
					</p>		<p class="line">
						<span class="label">Email: </span>
						<span class="value"><a href="mailto:{{member_email}}" class="normal">{{person_email}}</a></span>
					</p>
					<p class="line">
						<span class="label">Phone: </span>
						<span class="value">{{person_phone}}</span>
					</p>
				</div>	
            ';
            $output .= $p->ReplacePlaceholders($string, $variables);
        }

 if(!empty($member_id)){
 	// CT show offers
	$output .= $p->Wrap(OFFER_LISTING_HEADING, "h2");
	$listings = new cListingGroup(OFFER_LISTING);
	$listings->LoadListingGroup(null, null, $member_id, null, null, null);

	$output .= $listings->DisplayListingGroup();
	// 	// CT Show want
	$output .= $p->Wrap(WANT_LISTING_HEADING, "h2");
	$listings = new cListingGroup(WANT_LISTING);
	$listings->LoadListingGroup(null, null, $member_id, null, null, null);
	$output .= $listings->DisplayListingGroup();
 } 

$p->DisplayPage($output); 

?>
