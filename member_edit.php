<?php

include_once("includes/inc.global.php");
$cUser->MustBeLoggedOn();

//if user themselves or a comittee or above
$member = new cMemberEdit;


// must always pass in a member id or be in admin mode for page to work
$member_id = (!empty($_REQUEST['member_id'])) ? $cDB->EscTxt($_REQUEST['member_id']) : null;
if($member_id != $cUser->getMemberId()) $cUser->MustBeLevel(1);

$is_loaded = false;
if(!empty($member_id)){
    $condition = "m.member_id = {$member_id}";
    $member->Load($condition);
    if($member_id == $member->getMemberId()) $is_loaded = true;
}
if($is_loaded == false){
    $cErr->Error("Member cannot be found");
    $redir_url="member_detail.php?member_id={$member_id}";
    include("redirect.php");
} 

//cannpt create right now
$form_mode = (!empty($_REQUEST["form_mode"]) && $cUser->getMemberRole()>0) ? $cDB->EscTxt($_REQUEST['form_mode']) : null;
 //cannpt create right now



	$page_title = "Edit profile for {$member->getDisplayName()} ({$member->getMemberId()})";
// }else{
// 	$page_title = "Create new member";
// }

if ($_POST["submit"]){
	$fieldArray = $_POST;
	// set into object
	//print_r($fieldArray);
	$member->Build($fieldArray);

	$is_saved = 0;
	$is_saved = $member->ProcessData();
		//redirect to page if saved
	if($is_saved){
		//redirect page if saved	
		$redir_url="member_detail.php?member_id={$member->getMemberId()}&form_action=saved";
  		include("redirect.php");
	} 
} else{
	$member_id = $cDB->EscTxt($_GET['member_id']);
	//print('member' .$member_id);
	if (!empty($member_id)){
		if($cUser->getMemberId() == $member_id || $cUser->getMemberRole() > 0){
			$member->setFormAction('update');
		} 
	} else{
		$cUser->MustBeLevel(2);
		$array= array(
			'form_action'=>'create', 
			'status'=>"A",
			'join_date'=>date('Y-m-d'),
			'expire_date'=>date('Y-m-d', strtotime('+1 years'))
		);
		$member->Build($array);
	}
}
$adminElements="";


        //CT euch, so make safe
        //if edit
        
        //remove - put inline
/*        $person_id = $member->getPrimaryPerson()->getPersonId();
        $first_name = ;
        $last_name = $member->getPrimaryPerson()->getLastName();
        $about_me = $member->getPrimaryPerson()->getAboutMe();
        $email = $member->getPrimaryPerson()->getEmail();
        $phone1_number = $member->getPrimaryPerson()->getPhone1Number();
        $phone2_number = $member->getPrimaryPerson()->getPhone2Number();
        $address_street1 = $member->getPrimaryPerson()->getAddressStreet1();
        $address_street2 = $member->getPrimaryPerson()->getAddressStreet2();
        $address_city = $member->getPrimaryPerson()->getAddressCity();
        $address_state_code = $member->getPrimaryPerson()->getAddressStateCode();
        $address_post_code = $member->getPrimaryPerson()->getAddressPostCode();*/
        if($member->getFormAction() == "update"){    
            $member_role_elements = "
                <p>
                    <label for=\"member_role\">
                        <span>Member role *</span>
                        {$member->PrepareMemberRoleDropdown()}
                    </label>
                </p>";
        }else{

            $member_role_elements = "<input type=\"hidden\" id=\"member_role\" name=\"member_role\" value=\"0\" />";
        }


        if($cUser->getMemberRole()>0){
            if($member->getFormAction()== 'create'){
                $member_id_elements = "<p>
                        <label for=\"member_id\">
                            <span>Member Id  *</span>
                            <input maxlength=\"20\" name=\"member_id\" id=\"member_id\" type=\"text\" value=\"{$member->getMemberId()}\" autofocus />
                            Check on member list for last used
                        </label>
                    </p>";
            }else{
                $member_id_elements = "<input type=\"hidden\" id=\"member_id\" name=\"member_id\" value=\"{$member->getMemberId()}\" />";
            }
             $adminElements .= "
            
            <h3>Account</h3>
            {$member_id_elements}
            {$member_role_elements}
            <p>
                <label for=\"account_type\">
                    <span>Account type *</span>
                    {$member->PrepareAccountTypeDropdown()}
                </label>
            </p>
            <!-- <p>
                <label for=\"restriction\">
                    <span>Restriction *</span>
                    

                    {true false?}
                </label>
            </p> -->
            <p>
                <label for=\"join_date\">
                     <span>Join date  *</span>
                     <input type=\"text\" id=\"join_date\" name=\"join_date\" value=\"{$member->getJoinDate()}\" maxlength=\"10\" /> Format as YYYY-MM-DD
                </label>
            </p>
            <p>
                <label for=\"expire_date\">
                     <span>Expire date  *</span>
                     <input type=\"text\" id=\"expire_date\" name=\"expire_date\" value=\"{$member->getExpireDate()}\" maxlength=\"10\" /> Format as YYYY-MM-DD
                </label>
            </p>
            <p>
                <label for=\"admin_note\">
                    <span>Admin note</span>
                    <textarea name=\"admin_note\" id=\"admin_note\">{$member->getAdminNote()}</textarea>
                </label>
            </p>

             ";
        } else{
             $adminElements .= "
             {$member_id_text}
            <p>
                <label for=\"member_role\">
                    <span>Member role </span>
                    {$member->getMemberRole()}
                </label>
            </p>
            <p>
                <label for=\"account_type\">
                    <span>Account type </span>
                    {$member->getAccountType()}
                </label>
            </p>
            <!-- <p>
                <label for=\"restriction\">
                    <span>Restriction </span>
                    

                    {true false?}
                </label>
            </p> -->
            <p>
                <label for=\"join_date\">
                     <span>Join date </span>
                     {$member->getJoinDate()}
                </label>
            </p>
            <p>
                <label for=\"expire_date\">
                     <span>Expire date </span>
                     {$member->getExpireDate()}
                </label>
            </p>
            

             ";
        }

        //CT todo - use template.
        $output = "
        <form action=\"/members/member_edit.php?member_id={$member->getMemberId()}\" method=\"post\" name=\"form\" id=\"form\" class=\"layout2\">
            <input type=\"hidden\" id=\"person_id\" name=\"person_id\" value=\"{$member->getPrimaryPerson()->getPersonId()}\" />
            <input type=\"hidden\" id=\"form_action\" name=\"form_action\" value=\"{$member->getFormAction()}\" />
            <input type=\"hidden\" id=\"status\" name=\"status\" value=\"{$member->getStatus()}\" />
            
            {$adminElements}
            <h3>About you</h3>
            <p>Tell us a bit about yourself (primary member of the account). This is a good chance to introduce yourself to the community, what you generally offer and what you are interested in.</p>
           <p>
                <label for=\"first_name\">
                    <span>First name  *</span>
                    <input maxlength=\"200\" name=\"first_name\" id=\"first_name\" type=\"text\" value=\"{$member->getPrimaryPerson()->getFirstName()}\">
                </label>
            </p>
            <p>
                <label for=\"last_name\">
                    <span>Last name  *</span>
                    <input maxlength=\"200\" name=\"last_name\" id=\"last_name\" type=\"text\" value=\"{$member->getPrimaryPerson()->getLastName()}\">
                </label>
            </p>
            <p>
                <label for=\"about_me\">
                    <span>About you</span>
                    <textarea name=\"about_me\" id=\"about_me\">{$member->getPrimaryPerson()->getAboutMe()}</textarea>
                </label>
            </p>
            <p>
                <label for=\"age\">
                    <span>Age range *</span>
                    {$member->PrepareAgeRangeDropdown()} 
                </label>
            </p>
            <p>
                <label for=\"gender\">
                    Gender you identify as <br />
                    {$member->PrepareGenderDropdown()}
                </label>
            </p>
            <h3>How you'd like to be contacted</h3>
            <p>Set your contact details here. You can also specify which you prefer - email or phone.</p>
   
            <p>
                <label for=\"email\">
                    <span>Email address *</span>
                    <input maxlength=\"200\" name=\"email\" id=\"email\" type=\"text\" value=\"{$member->getPrimaryPerson()->getEmail()}\">
                </label>
            </p>            
            <p>
                <label for=\"phone1_number\">
                    <span>Primary phone</span>
                    <input maxlength=\"200\" name=\"phone1_number\" id=\"phone1_number\" type=\"text\" value=\"{$member->getPrimaryPerson()->getPhone1Number}\">
                </label>
            </p>
            <p>
                <label for=\"phone2_number\">
                    <span>Secondary phone</span>
                    <input maxlength=\"200\" name=\"phone2_number\" id=\"phone2_number\" type=\"text\" value=\"{$member->getPrimaryPerson()->getPhone2Number}\">
                </label>
            </p>
            <h3>Where you live</h3>
            <p>Only you and the administrators of the site can see your full address. Everyone else will see just your neighbourhood and first part of the post code. We won't force you to set your full address here, it's up to you.</p>
            <p>
                <label for=\"address_street1\">
                    <span>Street address</span>
                    <input maxlength=\"200\" name=\"address_street1\" id=\"address_street1\" type=\"text\" value=\"{$member->getPrimaryPerson()->getAddressStreet1()}\">
                </label>
            </p>
            <p>
                <label for=\"address_street2\">
                    <span>" . ADDRESS_LINE_2 . " *</span>
                    <input maxlength=\"200\" name=\"address_street2\" id=\"address_street2\" type=\"text\" value=\"{$member->getPrimaryPerson()->getAddressStreet2()}\">
                </label>
            </p>
            <p>
                <label for=\"address_city\">
                    <span>" . ADDRESS_LINE_3 . "  *</span>
                    <input maxlength=\"200\" name=\"address_city\" id=\"address_city\" type=\"text\" value=\"{$member->getPrimaryPerson()->getAddressCity()}\">
                </label>
            </p>
            <p>
                <label for=\"address_state_code\">
                    <span>" . STATE_TEXT . "</span>
                    <input maxlength=\"200\" name=\"address_state_code\" id=\"address_state_code\" type=\"text\" value=\"{$member->getPrimaryPerson()->getAddressStateCode()}\">
                </label>
            </p>            
            <p>
                <label for=\"address_post_code\">
                    <span>" . ZIP_TEXT . " *</span>
                    <input maxlength=\"200\" name=\"address_post_code\" id=\"address_post_code\" type=\"text\" value=\"{$member->getPrimaryPerson()->getAddressPostCode()}\">
                </label>
            </p>
            <input type=\"hidden\" id=\"address_country\" name=\"address_country\" value=\"". DEFAULT_COUNTRY ."\" />
            <p class=\"summary\">
                <input name=\"submit\" id=\"submit\" value=\"Submit\" type=\"submit\" />
                * denotes a required field
            </p>
        </form>";
$p->page_title = $page_title;
$p->DisplayPage($output);


?>
