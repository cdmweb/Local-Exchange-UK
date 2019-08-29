<?php

//CT just load what you need of current user
class cMemberSelf extends cMember {
    private $display_name;
    /**
     * @return mixed
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * @param mixed $display_name
     *
     * @return self
     */
    public function setDisplayName($display_name)
    {
        $this->display_name = $display_name;

        return $this;
    }



    // public function __construct($values=null) {
    //     if(!empty($values)){
    //         //refers to parent
    //         $this->Build($values);
    //         $this->setDisplayName($values['display_name']);
    //     }
    // }
    public function Load($member_id) {
        global $cDB, $cErr, $cQueries;
        //
        if(empty($member_id)) return false;
        //CT barebones
        $member_id = $cDB->EscTxt($member_id);
        $condition = "p1.primary_member = 'Y' and m.member_id='{$member_id}' and m.status = 'A'";
        $query = $cDB->Query("{$cQueries->getMySqlMemberSelf($condition)} LIMIT 1");

        $i=0;
        while($row = $cDB->FetchArray($query))
        {       
            $this->Build($row);
            $this->setDisplayName($row['display_name']);
            $i++;
        }
        if(empty($i)){
            $cErr->Error("We could not log you in. Your account may be restricted. (".$member_id.").");
            $login_history->RecordLoginFailure($member_id, $status);
            return false;
        }
        return true;              
    }


        // CT remove
        //public function SaveNewMember() {
        //     global $cDB, $cErr; 
            



        //     /* [chris] adjusted to store 'confirm_payments' preference */
        //     /* ct removed mothers maiden, fax, do this for legibility*/
                    

        //     $hash = password_hash($plainTextPassword, PASSWORD_DEFAULT);
        //     $insert = $cDB->Query("INSERT INTO ".DATABASE_MEMBERS." (member_id, password, member_role, security_q, security_a, status, member_note, admin_note, join_date, expire_date, away_date, account_type, email_updates, confirm_payments, balance) VALUES (
        //             {$this->getMemberId()},
        //             {$hash},
        //             {$this->getSecurityQ()},
        //             {$this->getSecurityA()},
        //             {$this->getStatus()},
        //             {$this->getMemberNote()},
        //             {$this->getAdminNote()},
        //             {$this->getJoinDate()},
        //             {$this->getExpireDate()},
        //             {$this->getAwayDate()}
        //             {$this->getAccountType()}
        //             {$this->getEmailUpdates()},
        //             {$this->getConfirmPayments()},
        //             {$this->getBalance()}
        //         ");

        //     return $insert;
        // }
    public function LoginFromCookie()
    {
/*
        if (isset($_COOKIE["login"]) && isset($_COOKIE["pass"]))
        {
            $this->Login($_COOKIE["login"], $_COOKIE["pass"], true);
        }
*/
        return false;
    }

    public function IsLoggedOn()
    {
//      if (isset($_SESSION["user_login"]) and $_SESSION["user_login"] != LOGGED_OUT)
        if (isset($_SESSION["user_login"]))
            return true;
        else
            return false;
    }
    public function Login($member_id, $pass, $from_cookie=false) {
        global $cDB,$cErr;
        $login_history = new cLoginHistory();
        /*        $query = $cDB->Query("SELECT member_id, member_role 
        FROM ".DATABASE_USERS." WHERE member_id = " . $cDB->EscTxt($member_id) . " 
        AND (password=sha('". $cDB->EscTxt($pass) ."') 
        OR password='". $cDB->EscTxt($pass) ."') 
        and status = 'A';");    */  
        $query = $cDB->Query("SELECT member_id, status
            FROM ".DATABASE_USERS." WHERE member_id = '{$cDB->EscTxt($member_id)}'
            AND (password=sha('{$cDB->EscTxt($pass)}') OR password='{$cDB->EscTxt($pass)}');");
        while($row = $cDB->FetchArray($query)) {
            if($row['status'] == "L"){
                $error = "Your account has been locked due to too many unsuccessful login attempts. Contact the administrator for help for help";
            } else{
                // successs!
                $this->Load($member_id);
                $_SESSION["user_login"] = $member_id;   
                return true;
            }
        }
        if(!isset($error)) $error = "Your details were incorrect or you don't have an account.";
        $cErr->Error($error);


        $login_history->RecordLoginFailure($member_id, $status);
        return false;    
    }
    
    public function ValidatePassword($pass) {
        global $cDB;
        $query = $cDB->Query("SELECT member_id, member_role 
            FROM ".DATABASE_USERS." WHERE member_id = ". $cDB->EscTxt($this->member_id) ." 
            AND (password=sha({$cDB->EscTxt($pass)}) OR password={$cDB->EscTxt($pass)});");  
        
        return (empty($cDB->FetchArray($query))) ? true : false;
    }


    /*
        public function DoLoginStuff($member_id)
        {
            global $cDB;
            //setcookie("login",$user,time()+60*60*24*1,"/");
            //setcookie("pass",$pass,time()+60*60*24*1,"/");

            $this->LoadMember($member_id);
            $_SESSION["user_login"] = $member_id;
        }
    */
    public function UserLoginPage() // A free-standing login page
    {
        global $p;
        $string = file_get_contents(TEMPLATES_PATH . '/form_login.php', TRUE);
        return $p->ReplacePlaceholders($string_query);
    }

    public function ChangePassword($pass) { // TODO: Should use SaveMember and should reset $this->password
        global $cDB, $cErr;
        
        $update = $cDB->Query("UPDATE ". DATABASE_MEMBERS ." SET password=sha(". $cDB->EscTxt($pass) .") WHERE member_id=". $cDB->EscTxt($this->member_id) .";");
        
        if($update) {
            return true;
        } else {
            $cErr->Error("There was an error updating the password.");
            //include("redirect.php");
        }
    }
   
    

    public function GeneratePassword() {  
        return Text_Password::create(8) . chr(rand(50,57));
    }

    public function MustBeLoggedOn()
    {
        global $p, $cErr;
        
        if ($this->IsLoggedOn())
            return true;
        
        // user isn't logged on, but is in a section of the site where they should be logged on.
        $_SESSION['REQUEST_URI'] = $_SERVER['REQUEST_URI'];
        $cErr->SaveErrors();
        header("location:" . HTTP_BASE . "/login_redirect.php");
                
        exit;
    }


    public function Logout() {
        setcookie(session_name(), session_id(), time() - 42000, '/');
        $_SESSION = array();
        session_destroy();
    }

    public function MustBeLevel($level) {
        global $p;
        $this->MustBeLoggedOn(); // seems prudent to check first.

        if ($this->getMemberRole()<$level)
        {
            $page = "<p class='AccessDenied'>You don't have permissions for this action.  <a href='mailto:".EMAIL_ADMIN."'>Contact the admin</a> to raise your permissions</p>";
            $p->DisplayPage($page);
            exit;

        }

    }

}
?>