<?php
include_once("includes/inc.global.php");
$p->site_section = ADMINISTRATION;
$p->page_title = "MySQL Backup";
$cUser->MustBeLevel(2);
global $cDB;
if ($_REQUEST["backup"]==true) {
	
	global $dbhost, $dbuname, $dbpass, $dbname;
	$dbhost =  DATABASE_SERVER;
	$dbuname = DATABASE_USERNAME;
	$dbpass = DATABASE_PASSWORD;
	$dbname = DATABASE_NAME;
	
	@set_time_limit(600);
	
	// English Text	
	$strNoTablesFound = "No tables found in database.";
	$strHost = "Host";
	$strDatabase = "Database ";
	$strTableStructure = "Table structure for table";
	$strDumpingData = "Dumping data for table";
	$strError = "Error";
	$strSQLQuery = "SQL-query";
	$strMySQLSaid = "MySQL said: ";
	$strBack = "Back";
	$strFileName = "dbbackup";
	$strName = "Database saved";
	$strDone = "the";
	$strat = "at";
	$strby = "by";
	$date_jour = date ("m-d-Y");
	
	$client = getenv("HTTP_USER_AGENT");
	
	if(ereg('[^(]*\((.*)\)[^)]*',$client,$regs)) {
		
		$os = $regs[1];
		//CT  linebreaks for linux too - most common server
		// this looks better under WinX
		//if (eregi("Win",$os)) 
		$crlf="\r\n";
	}
	
	function my_handler($sql_insert) {
	
		    global $crlf;
		    echo "$sql_insert;$crlf";
	}


	
		// Get the content of $table as a series of INSERT statements.
		// After every row, a custom callback function $handler gets called.
		// $handler must accept one parameter ($sql_insert);
		function get_table_content($db, $table, $handler)
		{
		    $result = mysqli_db_query($db, "SELECT * FROM $table") or mysqli_die();
		    $i = 0;
		    while($row = mysqli_fetch_row($result))
		    {
		//        set_time_limit(60); // HaRa
		        $table_list = "(";
		
		        for($j=0; $j<mysqli_num_fields($result);$j++)
		            $table_list .= mysqli_field_name($result,$j).", ";
		
		        $table_list = substr($table_list,0,-2);
		        $table_list .= ")";
		
		        if(isset($GLOBALS["showcolumns"]))
		            $schema_insert = "INSERT INTO $table $table_list VALUES (";
		        else
		            $schema_insert = "INSERT INTO $table VALUES (";
		
		        for($j=0; $j<mysqli_num_fields($result);$j++)
		        {
		            if(!isset($row[$j]))
		                $schema_insert .= " NULL,";
		            elseif($row[$j] != "")
		                $schema_insert .= " '".addslashes($row[$j])."',";
		            else
		                $schema_insert .= " '',";
		        }
		        $schema_insert = ereg_replace(",$", "", $schema_insert);
		        $schema_insert .= ")";
		        $handler(trim($schema_insert));
		        $i++;
		    }
		    return (true);
		}
		
		// Return $table's CREATE definition
		// Returns a string containing the CREATE statement on success
		function get_table_def($db, $table, $crlf)
		{
		    $schema_create = "";
		    $schema_create .= "DROP TABLE IF EXISTS $table;$crlf ";
		    $schema_create .= "CREATE TABLE $table ($crlf ";
		
		    $result = mysqli_db_query($db, "SHOW FIELDS FROM $table") or mysqli_die();
		    while($row = mysqli_fetch_array($result))
		    {

		        $schema_create .= "   $row[Field] $row[Type]";
		
		        if(isset($row["Default"]) && (!empty($row["Default"]) || $row["Default"] == "0"))
		            //CT exclude current timestamp from quotes as it broke dump
		            if ($row["Default"] != "CURRENT_TIMESTAMP"){
						$schema_create .= " DEFAULT '$row[Default]'";
		            } else{
		            	$schema_create .= " DEFAULT $row[Default]";
		        	}
		        	//print($row[Default]);
		        if($row["Null"] != "YES")
		            $schema_create .= " NOT NULL";
		        if($row["Extra"] != "")
		            $schema_create .= " $row[Extra]";
		        $schema_create .= ",$crlf";
		    }
		    $schema_create = ereg_replace(",".$crlf."$", "", $schema_create);
		    $result = mysqli_db_query($db, "SHOW KEYS FROM $table") or mysqli_die();
		    while($row = mysqli_fetch_array($result))
		    {
		        $kname=$row['Key_name'];
		        if(($kname != "PRIMARY") && ($row['Non_unique'] == 0))
		            $kname="UNIQUE|$kname";
		         if(!isset($index[$kname]))
		             $index[$kname] = array();
		         $index[$kname][] = $row['Column_name'];
		    }
		
		    while(list($x, $columns) = @each($index))
		    {
		         $schema_create .= ",$crlf";
		         if($x == "PRIMARY")
		             $schema_create .= "   PRIMARY KEY (" . implode($columns, ", ") . ")";
		         elseif (substr($x,0,6) == "UNIQUE")
		            $schema_create .= "   UNIQUE ".substr($x,7)." (" . implode($columns, ", ") . ")";
		         else
		            $schema_create .= "   KEY $x (" . implode($columns, ", ") . ")";
		    }
		
		    $schema_create .= "$crlf)";
		    return (stripslashes($schema_create));
		}
		
		function mysqli_die($error = "")
		{
		    echo "<b> $strError </b><p>";
		    if(isset($sql_query) && !empty($sql_query))
		    {
		        echo "$strSQLQuery: <pre>$sql_query</pre><p>";
		    }
		    if(empty($error))
		        echo $strMySQLSaid.mysqli_error();
		    else
		        echo $strMySQLSaid.$error;
		    echo "<br><a href=\"javascript:history.go(-1)\">$strBack</a>";
		    exit;
		}
		
		
		@mysqli_select_db("$dbname") or die ("Unable to select database");
		// CT exclude views from the dump
		$tables = mysqli_db_query($dbname, "SHOW FULL TABLES where Table_type='BASE TABLE'") or mysqli_die();
		//$tables = mysqli_list_tables($dbname);
		
		$num_tables = @mysqli_numrows($tables);
		if($num_tables == 0)
		{
		    echo "No tables found in database.";
		}
		else
		{
$i = 0;
$stunden = date ("H:i");
header("Content-disposition: filename=$dbname-".date('y-m-d',time()).".sql");
header("Content-type: application/octetstream");
header("Pragma: no-cache");
header("Expires: 0");
		
print "# ========================================================$crlf";
print "# This Backup was made with MySql-Tool Version 2.0$crlf";
print "# http://www.nukeland.de (michaelius@nukeland.de)$crlf";
print "# $crlf";
print "# Modified for use with Local Exchange UK software$crlf";
print "# chris@cdmweb.co.uk and me@claratodd.com$crlf";
print "# $crlf";
print "# $strName : $dbname$crlf";
print "# $strDone $datum $strat $stunden !$crlf";
print "# $crlf";
print "# ========================================================$crlf";
print "  $crlf";		    
       while($i < $num_tables)
	{ 
	$table = mysqli_tablename($tables, $i);
print  $crlf;
print "# --------------------------------------------------------$crlf";
print "#$crlf";
print "# $strTableStructure '$table'$crlf";
print "#$crlf";
print $crlf;
	echo get_table_def($dbname, $table, $crlf).";$crlf$crlf";
print "#$crlf";
print "# $strDumpingData '$table'$crlf";
print "#$crlf";
print $crlf;
			
get_table_content($dbname, $table, "my_handler");		
$i++;
}
}				
	
	//$p->DisplayPage($output);
	
	exit;
}
$output = "Use this tool to backup your MySQL Database.<p>";
$output .= "<a href='mysqli_backup.php?backup=true' class='large'>Backup Now</a>";
		
$p->DisplayPage($output);
?>