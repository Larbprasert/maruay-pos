<?php 
	session_start();
	include("service/config_service.php");

	header('Content-type: image/jpeg');

	$strSQL = " SELECT * FROM tb_files WHERE file_id = ".$_GET["file_id"];
	$objQuery = mysqli_query($connection,$strSQL) or die ("Error Query [".$strSQL."]");
	$objResult = mysqli_fetch_array($objQuery);

	echo $objResult["name"];

?>

  
    