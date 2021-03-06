
 

<!-- Modal -->
<div class="modal " tabindex="-1" role="dialog" id="packageProductModal"   data-backdrop="static">
   <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                 <h4 class="modal-title"><i class="fa fa-search"></i>  ค้นหาสินค้า</h4>
           
                 
			</div>
			
            <div class="modal-body">
				
			
				<div class="row">
					<div class="col-md-12" >
				
            
						<!-- <h4> Name :  <label id="label_empName"></label></h4> -->
    					

                          <div class="panel-body">

                              <!-- <form class="form-horizontal " method="post" action="edit_user_pro.php" > -->

                              	<!-- <input type="hidden" id="check_update" name="check_update" value="1"> -->
                                <!-- <input type="hidden" id="emp_id" name="emp_id" value="<?php echo $user['emp_id']?>"> -->
                                
									<!-- ID -->	
									<div class="input-group">
											<input type="text" class="form-control input-lg input-p"
											id="pack-search-product" placeholder="รหัสสินค้า, บาร์โค้ด ,ชื่อสินค้า " >
											<span class="input-group-btn">
												<button type="button" class="btn btn-info input-lg btn-lg" id="btn-pack-product-add">
													<i class="fa fa-search"></i> ค้นหาสินค้า [Enter]</button>
											</span>
										</div>
									

							  <!-- </form> -->
							  </div>    	
					</div>             	
				</div>   		  

			 <div class="row">
				<div class="col-md-12" >

				<table  id="pack-product-table" class="table table-striped table-bordered table-hover dataTable nowrap" style="width:100%" >
					<thead  >
						<tr>
							<th>รหัสสินค้า</th>
							<th>ชื่อสินค้า</th>
							<th>หน่วยนับ</th>
							<!-- <th>ราคาขาย (บาท)</th> -->
						</tr> 
						</thead>
	

				</table>


                       
                        
                        
            
			 </div>
			</div>
			
				<div class="modal-footer">
				
					<button type="button" onclick="clearPackageProduct()" class="btn btn-primary" data-dismiss="modal">
						<i class="fa fa-refresh"></i>&nbsp; ล้างข้อมูล</button>
					
					<button type="button" class="btn btn-warning" data-dismiss="modal">
						<i class="fa fa-remove"></i>&nbsp; ปิดหน้าต่าง [ESC]</button>
				</div>
				
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>


  <script>

	function openModalPackageProduct(iRow) {

		$("#packageProductModal").modal(); 

		$("#pack-search-product").focus().val('');


		packageProductTable = $('#pack-product-table').DataTable({
			scrollY:   '250px',
			//  scrollCollapse: true,
			lengthChange:false,
			searching:false,
			paging:   false,
			data:[],
		    columns: [
				// { 
				// 	 "data": "product_ID",
				// 	 "fnCreatedCell" : function(nTd, sData, oData, iRow, iCol) {
				// 		$(nTd).html(++iRow);
				// 	}
	            // },
		     	{ 
		     		"data": "product_ID"
					 , "className": "col-id", "width": "20%"
	            },
		     	{ 
		     		"data": "product_Name"
	            },
		     	// { 
				// 	 "data": "qty"
				// 	 , "className": "col-qty" 

	            // },
				{ 
					"data": "unit_name"
	            } 
		      
		    ],
		    "aoColumnDefs": [
		      { "sClass": "text-center", "aTargets": [0,2] }
		    ],
			ordering: false,
			destroy: true,
			rowCallback: function (row, data) {},
			// info:     false,
			language : DT_TH['language'] 
		 });  


	}


	function doSearchPackageProduct(){

		var name = $("#pack-search-product").val();
		var product_ID = $('#a-product_ID').val();

		if(name.trim() == ""){ return false};
		
		_showLoading(true);
		
		$.ajax({
			url: "service/product_service.php?method=search",
			data:{ 
				name : name , 
				product_ID : product_ID ,
				package : 'Y'
			}
		}).done(function (result) {
			packageProductTable.clear().draw();
			// _data = result;
			packageProductTable.rows.add(result).draw();
			    _showLoading();
		}).fail(function (jqXHR, textStatus, errorThrown) { 
			_showLoading();
		});
		
	};


	$('#btn-pack-product-add').click( function () {
		doSearchPackageProduct();
	} );
	
	
	$("#pack-search-product").on('keyup', function (e) {
		if (e.keyCode === 13) {
			doSearchPackageProduct();
		}
	});


	$('#pack-product-table').on( 'dblclick', 'tr', function () {
		
			var data = packageProductTable.row( this ).data();
			setPackageProduct(data['product_ID']);

			$("#packageProductModal").modal('hide'); 

	});
   
</script>



  
  