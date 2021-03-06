<?php 
	session_start();
	include("config_service.php");
	
	if(isset($_REQUEST['method'])){

		header('Content-type: application/json');
			
		if("save"==$_REQUEST['method']){


			$saleType=$_REQUEST['saleType'];
			$priceType=$_REQUEST['priceType'];
			$total_discount=$_REQUEST['total_discount'];
			$total_amount=$_REQUEST['total_amount'];
			$sale_status=$_REQUEST['sale_status'];
			$pay=$_REQUEST['pay'];
			$change=$_REQUEST['change'];
			$user_id=$_REQUEST['user_id'];

			$total_amount = str_replace(",","",$total_amount);
			$total_discount = str_replace(",","",$total_discount);

			$sale_id = "" ;


			$query_insert = "  insert into  `tb_SaleHeader`( saleHeader_ID ,`saleType_ID`,`total_discount`,`total_amount`,
			`customer_ID`,`employee_ID`,`order_date`,`sale_status`,`create_date`,
			`create_by` ,`pay`,`change` ) values ( fn_getNumber('".$saleType."')
			,'".$priceType."' ,'".$total_discount."','".$total_amount."' ,NULL,'".$user_id."' 
			,NOW(),'".$sale_status."',NOW(), '".$user_id."' ,'".$pay."', '".$change."' ) ";

			// echo $query_insert;

			// $objQuery = mysqli_query($connection,$query_insert) or die(mysqli_connect_error());
			$objQuery = mysqli_query($connection,$query_insert) or die ('request "Could not execute SQL query" '.$query_insert);		
			// $objQuery = mysqli_query($connection,$query_insert);			

			if($objQuery){

				$items = json_decode($_REQUEST['items'], true);
				foreach ($items as $item) {
					
/* Issue get price between wholesale_price and sell_price*/
					if($item['wholesale_flg'] == "1" &&  $item['qty'] >=  $item['qty_condition']){
							$query_detail = "  insert into  `tb_SaleDetail` ( `saleHeader_ID` , `product_ID`
							, `product_Name` , unit_name , cost, old_qty, `qty`, `price`,`discount`,`amount` ,`create_date`, `create_by` ) 
							values (  
							( SELECT `VALUE` FROM tb_PosNum WHERE `TYPE` = '".$saleType."' ) ,  
							'".$item['product_ID']."' 
							,'".$item['product_Name']."'
							,'".$item['unit_name']."' ,'".$item['cost']."'  ,'".$item['old_qty']."' 
							,'".$item['qty']."' ,'".$item['wholesale_price']."' ,'".$item['discount']."' ,'".$item['total_price']."'
							, NOW(), '".$user_id."' ) ";
					}else{
							$query_detail = "  insert into  `tb_SaleDetail` ( `saleHeader_ID` , `product_ID`
							, `product_Name` , unit_name , cost, old_qty, `qty`, `price`,`discount`,`amount` ,`create_date`, `create_by` ) 
							values (  
							( SELECT `VALUE` FROM tb_PosNum WHERE `TYPE` = '".$saleType."' ) ,  
							'".$item['product_ID']."' 
							,'".$item['product_Name']."'
							,'".$item['unit_name']."' ,'".$item['cost']."'  ,'".$item['old_qty']."' 
							,'".$item['qty']."' ,'".$item['sell_price']."' ,'".$item['discount']."' ,'".$item['total_price']."'
							, NOW(), '".$user_id."' ) ";
					}
 
					// $query_detail = "  insert into  `tb_SaleDetail` ( `saleHeader_ID` , `product_ID`
					// , `product_Name` , unit_name , cost, old_qty, `qty`, `price`,`discount`,`amount` ,`create_date`, `create_by` ) 
					// values (  
					// 	( SELECT `VALUE` FROM tb_PosNum WHERE `TYPE` = '".$saleType."' ) ,  
					// 	 '".$item['product_ID']."' 
					// 	,'".$item['product_Name']."'
					// 	,'".$item['unit_name']."' ,'".$item['cost']."'  ,'".$item['old_qty']."' 
					// ,'".$item['qty']."' ,'".$item['sell_price']."' ,'".$item['discount']."' ,'".$item['total_price']."'
					// , NOW(), '".$user_id."' ) ";
/* Issue get price between wholesale_price and sell_price*/

					// $objQuery_detail = mysqli_query($connection,$query_detail) or die(mysqli_connect_error());
					$objQuery_detail = mysqli_query($connection,$query_detail) or die ('request "Could not execute SQL query" '.$query_detail);					
					//$objQuery_detail = mysqli_query($connection,$query_detail);

					if( "INV" == $saleType){
						$query_stk = " call sp_cutStock('".$item['product_ID']."' ,".$item['qty']." )";
						// $objQuery_stk = mysqli_query($connection,$query_stk) or die(mysqli_connect_error());
						$objQuery_stk = mysqli_query($connection,$query_stk) or die ('request "Could not execute SQL query" '.$query_stk);						
						// $objQuery_stk = mysqli_query($connection,$query_stk);	

						$new_qty =  (int) $item['old_qty'] - (int) $item['qty'] ;

						$query_trans = "  insert into  `tb_TransactionLog` ( `saleHeader_ID` , `product_ID`
						, `product_Name` , old_qty ,`qty`,new_qty , `create_date`, `create_by`, transType ) 
						values (  
							( SELECT `VALUE` FROM tb_PosNum WHERE `TYPE` = '".$saleType."' ) ,  
							 '".$item['product_ID']."' ,
							fn_getProductName('".$item['product_ID']."') ,
						'".$item['old_qty']."' ,'-".$item['qty']."' ,'".$new_qty."' ,  NOW(), '".$user_id."' , 'S' ) ";
	
						// $objQuery_trans = mysqli_query($connection,$query_trans) or die(mysqli_connect_error());
						$objQuery_trans = mysqli_query($connection,$query_trans) or die ('request "Could not execute SQL query" '.$query_trans);						
						// $objQuery_trans = mysqli_query($connection,$query_trans);
						
					}

				}

			}

			if($objQuery && $objQuery_detail){

				$sql = "select `VALUE` as INV_NO FROM tb_PosNum WHERE `TYPE` = '".$saleType."' ";
			
				$sql_result = mysqli_query($connection,$sql) or die ('request "Could not execute SQL query" '.$sql);
				$obj = mysqli_fetch_assoc($sql_result);
				if(!empty($obj)){	
					$inv_no = $obj['INV_NO'];
				}

				$arr = array('status' => 'success' , 'inv_no' => $inv_no );  
				echo json_encode($arr);
			} else{			
				$arr = array('status' => 'error');  
				echo json_encode($arr);
			}


		}else if("invoice_return"==$_REQUEST['method']){

			$saleHeader_ID=$_REQUEST['saleHeader_ID'];
			$user_id=$_REQUEST['user_id'];

			$query_stk = " call sp_returnStock('".$saleHeader_ID."' , '".$user_id."' )";
			$objQuery = mysqli_query($connection,$query_stk) or die(mysqli_connect_error());	

			$items = json_decode($_REQUEST['items'], true);
			foreach ($items as $item) {

				$query_detail = "  insert into  `tb_TransactionLog` ( `saleHeader_ID` , `product_ID`
				, `product_Name` , old_qty ,`qty`,new_qty , `create_date`, `create_by`, transType ) 
				values (  
					 '".$item['saleHeader_ID']."' ,
					 '".$item['product_ID']."' ,
					 '".$item['product_Name']."' ,
					'".$item['old_qty_update']."' ,'".$item['qty']."' ,'".$item['new_qty_update']."' , 
					 NOW(), '".$user_id."' , 'R' ) ";

				$objQuery_detail = mysqli_query($connection,$query_detail) or die(mysqli_connect_error());

			}

			
			if($objQuery){
				$arr = array('status' => 'success');  
				echo json_encode($arr);
			} else{			
				$arr = array('status' => 'error');  
				echo json_encode($arr);
			}
			
		}


	}else{
		echo "Required parameter!";
	}



?>

  
    