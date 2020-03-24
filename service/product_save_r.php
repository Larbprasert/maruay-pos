<?php 
	session_start();
	include("config_service.php");
	
	function resizeImage($resourceType,$image_width,$image_height) {
		$resizeWidth = 100;
		$resizeHeight = 100;
		$imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
		imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
		return $imageLayer;
	}

	$query_last_insert = " INSERT INTO tb_product (name,createdate,createby) VALUES('".$_POST["txtName"]."',now(),99)";
	$last_insert = mysqli_query($conn,$query_last_insert) or die("Could not query");
	
	if($last_insert){
		$last_id = mysqli_insert_id($conn);
 
		for($i=1;$i<=(int)($_POST["hdnLine"]);$i++)
		{
			if($_FILES["fileUpload".$i]["name"] != "")
			{
				$filename = $_FILES["fileUpload".$i]["name"];
				$sourceProperties = getimagesize($fileName);
				$resizeFileName = time();
				$uploadPath = "./uploads/";
				$fileExt = pathinfo($_FILES['upload_image']['name'], PATHINFO_EXTENSION);

				// $filepath = uniqid()."_".$_FILES["fileUpload".$i]["name"];
				$filepath = uniqid()."_".$resizeFileName;
				$uploadImageType = $sourceProperties[2];
				$sourceImageWidth = $sourceProperties[0];
				$sourceImageHeight = $sourceProperties[1];

				switch ($uploadImageType) {
						case IMAGETYPE_JPEG:
						$resourceType = imagecreatefromjpeg($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagejpeg($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						break;
		
					case IMAGETYPE_GIF:
						$resourceType = imagecreatefromgif($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagegif($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						break;
		
					case IMAGETYPE_PNG:
						$resourceType = imagecreatefrompng($fileName); 
						$imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
						imagepng($imageLayer,$uploadPath."thump_".$resizeFileName.'.'. $fileExt);
						break;
		
					default:
						$imageProcess = 0;
						break;
				}


				
				if(move_uploaded_file($_FILES["fileUpload".$i]["tmp_name"], $uploadPath.$filepath))
				{
					$strSQL = "INSERT INTO tb_image ";
					$strSQL .="(product_id,img_name,img_path, createdate) VALUES ('".$last_id."','".$filename."','".$filepath."',now() )";
					mysqli_query($conn,$strSQL);
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

  
    