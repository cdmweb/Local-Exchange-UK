<?php
include_once("includes/inc.global.php");
if (!empty($_GET["action"]))
	$action = $_GET["action"];

if (!empty($_POST["action"]))
	$action = $_POST["action"];

if ($action=="logout")
{
	$cUser->Logout();
}

if ($action=="login")
{
	if (!empty($_POST["location"]))
		$redir_url = $_POST["location"];

	if (!empty($_POST["user"])) {
		$user = $_POST["user"];
	} else{
		$cErr->Error("Please enter a user name to log in.");
	}
	if (!empty($_POST["pass"]))
		$pass = $_POST["pass"];

	if ($user=="" || $pass=="")
	{
		if (empty($user))
		{
			$cErr->Error("Please enter a user name to log in.");
		} 
		if (empty($pass)) {
			$cErr->Error("Please enter a password to log on with this account.");
		}

	} else {
		//
		$cUser->Login($user,$pass);
	}


}

include("redirect.php");	// if nothing in particular is set, will redirect to home, but this allows the user login
				// process to potentially set an alternate location.

?>
