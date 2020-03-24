<?php 
	session_start();
	include("config_service.php");
	


	$query_last_insert = " INSERT INTO tb_product (name,createdate,createby) VALUES('".$_POST["txtName"]."',now(),99)";
	$last_insert = mysqli_query($conn,$query_last_insert) or die(mysqli_error($conn));
	
	if($last_insert){
		$last_id = mysqli_insert_id($conn);

		// echo "last_id ".$last_id ." completed.<br>";

		/* for($i=1;$i<=(int)($_POST["hdnLine"]);$i++)
		{
			// echo "Copy/Upload ".$_FILES["fileUpload".$i]["name"]." completed.<br>";

			if($_FILES["fileUpload".$i]["name"] != "")
			{
				
				//*** Read file BINARY ***'
				$fp = fopen($_FILES["fileUpload".$i]["tmp_name"],"r");
				$ReadBinary = fread($fp,filesize($_FILES["fileUpload".$i]["tmp_name"]));
				fclose($fp);
				$FileData = addslashes($ReadBinary);

				$strSQL = "INSERT INTO tb_files ";
				$strSQL .="(product_id,name) VALUES ('".$last_id."','".$FileData."')";
				$objQuery = mysqli_query($conn,($strSQL);		

				
			}
		} */
		for($i=1;$i<=(int)($_POST["hdnLine"]);$i++)
		{
			if($_FILES["fileUpload".$i]["name"] != "")
			{
				$filename = $_FILES["fileUpload".$i]["name"];
				$filepath = uniqid()."_".$_FILES["fileUpload".$i]["name"];
				if(move_uploaded_file($_FILES["fileUpload".$i]["tmp_name"],"../upload/".$filepath))
				{
					$strSQL = "INSERT INTO tb_image ";
					$strSQL .="(product_id,img_name,img_path, createdate) VALUES ('".$last_id."','".$filename."','".$filepath."',now() )";
					mysqli_query($connection,$strSQL);
					// echo "Copy/Upload ".$_FILES["fileUpload".$i]["name"]." completed.<br>";
				}
			}
		}



		echo "<script>alert(' บันทึกข้อมูลสำเร็จ ! '); </script>";

		print "<meta http-equiv='refresh' content='0;URL=../new_product.php'>";

		exit();


	}else{  
		echo "<script>alert('บันทึกข้อมูลไม่สำเร็จ !'); </script>";
	}



?>

  
    