<?php
//$SETTINGS["hostname"] = 'localhost';
//$SETTINGS["mysql_user"] = 'root';
//$SETTINGS["mysql_pass"] = 'root';
//$SETTINGS["mysql_database"] = 'pos_system';

//// $SETTINGS["hostname"] = 'localhost';
//// $SETTINGS["mysql_user"] = 'khotdeec_root';
//// $SETTINGS["mysql_pass"] = 'khotdee@1234';
//// $SETTINGS["mysql_database"] = 'khotdeec_ceramic';

//$SETTINGS["USERS"] = 'emp_tb'; // this is the default table name that we used

//$connection = mysqli_connect($SETTINGS["hostname"], $SETTINGS["mysql_user"], $SETTINGS["mysql_pass"]) or die ('Unable to connect to MySQL server.<br ><br >Please make sure your MySQL login details are correct.');
//$db = mysqli_select_db($SETTINGS["mysql_database"], $connection) or die ('request "Unable to select database."');
//mysqli_query($connection,"SET NAMES UTF8");

?>



<?php

	$hostname = "localhost";
	$mysql_user = "root";
	$mysql_pass = "P@ssw0rd";
	$mysql_database = "pos_system";
    
    $connection = mysqli_connect($hostname,$mysql_user,$mysql_pass,$mysql_database);
    
    mysqli_query($connection,"SET NAMES UTF8");
	
	$USERS = "emp_tb";

?>


 