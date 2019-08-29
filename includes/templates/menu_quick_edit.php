<?php
if($cUser->getMemberRole() > 0 || $cUser->getMemberId() == $cMember->getMemberId()){
$output .="
<p>
	<div>
		<a href=\"member_edit.php?member_id={$member_id}\" class=\"button edit\"><i class=\"fas fa-pencil-alt\"></i> edit</a>

		<a href=\"listing_manage.php?type=O&member_id={$member_id}\" class=\"button\"><i class=\"fas fa-hand-holding-heart\"></i> Manage offers</a>

		<a href=\"listing_manage.php?type=W&member_id={$member_id}\" class=\"button\"><i class=\"fas fa-hand-holding\"></i> Manage wants</a>

	</div>
</p>";
}
?>