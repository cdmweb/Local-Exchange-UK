<?php

include_once("includes/inc.global.php");
$p->site_section = ADMINISTRATION;
$p->page_title = "Refund Service Charge";


// *** Starts main() ***

$cUser->MustBeLevel(2);

if (isset($_GET["TID"]) && is_numeric($_GET["TID"]))
{
    $page = transfer_fee($_GET["TID"], $_GET["trade_time"]);
}
else if (isset($_GET["CID"]) && is_numeric($_GET["CID"]))
{
    $page = confirmation($_GET["CID"], $_GET["selected_time"]);
}
else
{
    $page = select_time();
}

$p->DisplayPage($page);

// *** Ends main() ***



function select_time()
{
    global $cDB;
    $system_account_id = SYSTEM_ACCOUNT_ID;
    $ts = time();
    $year = strftime("%Y", $ts);
    $month = strftime("%m", $ts);
    $trade_type = "S";
    $refunded = TRADE_MONTHLY_FEE_REVERSAL;

    // We'll show only the monthly fees taken during the last 12 months.
    $date_year_ago = ($year - 1) . "-$month-01";
    $sql = "select distinct(trade_date) from trades where
                 trade_date > '$date_year_ago' and type='$trade_type' and
                 member_id_to = '$system_account_id' and status != '$refunded'";
   //echo $sql;
    $result = $cDB->Query($sql);

    $selection_list = "";
    while ($row = mysqli_fetch_object($result))
    {
        $selection_list .=
            "<option value=\"$row->trade_date\">$row->trade_date</option>";
        

    }

    if (empty($selection_list))
    {
        return "No service charges found.";
    }

    $html = <<<ENDHTML
        <form method="GET" action="">
          <input type="hidden" name="CID" value="$ts" />
          <select name="selected_time">
            $selection_list
          </select>

          <input type="submit" value="Refund" />
        </form>
ENDHTML;

    return $html;
}


/*
 * Displays a confirmation dialogue.  Also shows a warning msg if monthly
 * fee has been already taken this month.
 */
function confirmation($cid, $selected_time)
{
    if( !defined("TAKE_SERVICE_FEE"))
    {
        return "This system isn't setup to process service fees.";
    }

    if($ignore==true && isset($_SESSION["LAST_CID"]) && $cid <= $_SESSION["LAST_CID"])
    {
        return "Action already executed.  Start from the administration
                    page for a new refund.";
    }
    else
    {
        $_SESSION["LAST_CID"] = $cid;
    }

    $ts = time();
    $html = <<<ENDHTML
        You are about to refund the service charge taken on
        <em>$selected_time</em>.

        <form method="GET" action="">
          <input type="hidden" name="TID" value="$ts" />
          <input type="hidden" name="trade_time" value="$selected_time">
          <input type="submit" value="Refund now" />
        </form>

        <p><strong>Or</strong></p>

        <form method="GET" action="admin_menu.php">
          <input type="submit" value="Cancel" />
        </form>
ENDHTML;

    return $html;
}



/*
 * Does the actual fee transfer from member accounts to the system account.
 */
function transfer_fee($tid, $trade_time) {
	
    // Make sure this transaction has not been done before.
    if(isset($_SESSION["LAST_TID"]) && $tid <= $_SESSION["LAST_TID"])
    {
        return "Already transfered.  Start from the administration
                      page for a new transfer.";
    }
    else
    {
        // Store the current transaction id for later checks.
        $_SESSION["LAST_TID"] = $tid;
    }

    global $cDB, $monthly_fee_exempt_list;
   
	   $sql = "select * from trades where trade_date='$trade_time'";
	   
   //echo $sql;
   
    $result = $cDB->Query($sql);
		
		$row = mysqli_fetch_array($result);

    $monthly_fee = $row["amount"];
    $system_account_id = SYSTEM_ACCOUNT_ID;
    $member_table = DATABASE_MEMBERS;
    $trade_table = DATABASE_TRADES;
    $trade_type_monthly = TRADE_MONTHLY_FEE;
    $trade_type = TRADE_MONTHLY_FEE_REVERSAL;
    $desc = "Refund for ".$row["description"]." taken on $trade_time";
    
    // Transaction starts.
    $cDB->Query("BEGIN");

    $trade_time = $cDB->EscTxt($trade_time);

    // We don't want to charge inactive accounts.
    $query0 = "select trade_id, member_id_from from " . DATABASE_TRADES .
                  " where trade_date = $trade_time 
                      and type='S'
                      and member_id_to = '$system_account_id'";
    $result0 = $cDB->Query($query0);

    // This single timestamp will be applied to every transfer done in
    // this transaction.  This is for the ease of identification of this
    // batch of transfer later.
 
    while ($row = mysqli_fetch_object($result0))
    {
    		
    		if ( !in_array($row->member_id, $monthly_fee_exempt_list)) {
    	
	        // Category 12 is "Miscellaneous".
	        $query1 = "insert into $trade_table set member_id_to='".$row->member_id_from."',
                              member_id_from='$system_account_id', amount=$monthly_fee, category=12,
                                  description='$desc', type='$trade_type', trade_date=$trade_time";
                                  
	        $result1 = $cDB->Query($query1);
	
	        $query2 = "update $member_table set balance = balance + $monthly_fee
	                             where member_id = '".$row->member_id_from."'";
	        $result2 = $cDB->Query($query2);
	// echo $query2."<br>";
	        $query3 = "update $member_table set balance = balance - $monthly_fee
	                             where member_id = '$system_account_id'";
	        $result3 = $cDB->Query($query3);
	
	        $query4 = "update $trade_table set status = '$trade_type' where
	                           trade_id = ".$row->trade_id."";
	        $result4 = $cDB->Query($query4);
	
	        if ( !( $result2 && $result3 && $result4))
	        {
	            $cDB->Query("rollback");
	
	            return "Couldn't transfer.";
	        }
      }
    }

    $cDB->Query("COMMIT");

    return "Done";
}



