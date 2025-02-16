<!DOCTYPE html>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);
$appsDependent = new dependentdata($db->con);

if($auth->authUser()):
	if($auth->verifyUserPermission('publish workorder', 1)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeature();

        
        $gp_master_id = isset($_GET['gp_master_id']) ? $_GET['gp_master_id'] : "";

        $gatepassMasterData = $accessoriesModel->getData("select m.*,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS USER_NAME,(SELECT designation FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS Designation from gatepass_masterdata m where m.ID='$gp_master_id'");
        $gp_no = $gatepassMasterData[0]['GP_NO'];
        $gatepassItemData = $accessoriesModel->getData("select * from gatepass_itemdata where gp_no='$gp_no'");
        // echo "<pre>";
        // print_r($gp_master_id);
        // exit;
?>

<style>
    input{
        padding: 5px !important;
        margin: 0px !important;
    }
    .drop-width{
        min-width: 450px !important;
        font-size: 12px;
        min-height: 200px;
        overflow-y: scroll;
        background-color: #fff;
        text-align: left;
    }
    .drop-width-item{
        min-width: 450px !important;
        font-size: 12px;
        min-height: 200px;
        overflow-y: scroll;
        background-color: #fff;
        text-align: left;
    }
    .select .drop-container .input {
  margin: 4px 2px 6px;
  /* width: calc(100% - 4px); */
  min-width: 450px;
}
</style>
<body class="input-small m4-cloak h-vh-100">
    <div class="input-small preloader" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, 0.8); left: 0;">
        <div data-role="activity" data-type="square" data-style="color" style="position: relative;top: 50%; left: 50%;"></div>
    </div>
    <div class="input-small success-notification" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, .93); left: 0;">
        
    </div>
    <div data-role="navview" data-toggle="#paneToggle" data-expand="xxl" data-compact="xl" data-active-state="true">
        <?php include_once('inc/navigation.php'); ?>
        <div class="navview-content h-100">
            <?php include_once('inc/topbar.php');?>
            <div class="content-inner h-100" style="overflow-y: auto">
                <div class="card pl-2 pr-2">
                   
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="setting" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-pencil"></span></span> Gate Pass Edit</h4>
              
<!-- ================================Content======================================================== -->
                <!-- <form action="javascript:void()" method="post" id="form" onsubmit="return checkForm(this);"> -->
                <form action="gatepassController.php?pageType=gp_update" method="post" id="form" name="form"  enctype="multipart/form-data" onsubmit="return checkForm(this);">
                    <table class="input-small table">
                        <tr>
                            <td width="10%">Emp Id.<b style="color: red">*</b></td>
                            <td width="1%">:</td>
                            <td>
                            <input type="text" name="empid" id="empid" value="<?php echo $gatepassMasterData[0]['USERID']; ?>" placeholder="Employee Id" data-role="input" class="input-small">
                            <input type="text" hidden name="gp_no" id="gp_no" value="<?php echo $gatepassMasterData[0]['GP_NO']; ?>">
                            <input type="text" hidden name="gp_id" id="gp_id" value="<?php echo $gatepassMasterData[0]['ID']; ?>">
                            </td>			
                            <td width="3%"></td>
                            <td width="10%">To<b style="color: red">*</b></td>
                            <td width="1%">:</td>
                            <td width="11%"> 
                            <input type="text" required name="attention_to" value="<?php echo $gatepassMasterData[0]['TO_RECIPIENT']; ?>" placeholder="Attention" id="attention_to" data-role="input" class="input-small">
                            </td>
                            <td width="3%"></td>
                            <td width="10%">Address<b style="color:red">*</b></td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                                <textarea name="address" id="address" placeholder="Address" class="input-small"><?php echo $gatepassMasterData[0]['ADDRESS']; ?></textarea>
                            </td>
                            <td width="3%"></td>
                            <td width="10%">From<b style="color: red">*</b> </td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                   
                            <input type="text" required name="from" value="<?php echo $gatepassMasterData[0]['FROM_SOURCE']; ?>" id="from" data-role="input" class="input-small">
                            </td>				
                        </tr>
                        <tr>
                            <td width="10%">Name<b style="color: red">*</b> </td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                            <input type="text" name="empname" required value="<?php echo $gatepassMasterData[0]['USER_NAME']; ?>" placeholder="Employee Name" id="empname" data-role="input" class="input-small">
                            </td>				
                            <td width="3%"></td>
                            <td width="10%">Designation<b style="color: red">*</b> </td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                           <input type="text" name="designation" required value="<?php echo $gatepassMasterData[0]['DESIGNATION']; ?>" placeholder="Designation" id="designation" data-role="input" class="input-small">
                            </td>
                            <td>
                                <?php
                                 if($gatepassMasterData[0]['RETURNABLE'] == 'on')
                                    $isChecked = 'checked';
                                else
                                    $isChecked = '';
                                ?>
                                <input type="checkbox" name="returnable" <?php echo $isChecked ?> id="returnable" data-role="checkbox" data-caption="Returnable" data-caption-position="left">
                            </td>
                            <td width="3%"></td>
                       
                            <td width="1%">:</td>
                            <td width="10%"> 
                
                            <div id="datepicker_return" class="input-small input-group date"  data-date-format="dd-mm-yyyy" >
                                    <input type="text" name="return_date" class="input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" value="<?=$gatepassMasterData[0]['RETURN_DATE']?>" data-format="%d-%m-%Y" data-input-format="%d-%m-%Y" data-clear-button="true" placeholder="Return Date">
                                    <span class="input-small invalid_feedback">Date is required.</span>
                                </div>
                     
                            </td>
                            <td width="3%"></td>

                            <td width="10%">Date</td>
                            <td width="1%">:</td>
                            <td width="10%">						  
                                <div id="datepicker" class="input-small input-group date"  data-date-format="dd-mm-yyyy" >
                                    <input type="text" name="gp_date" class="input-small deliverydate accessories-disable input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" value="<?=$gatepassMasterData[0]['GP_DATE']?>" data-input-format="%d-%m-%Y" data-clear-button="true">
                                    <span class="input-small invalid_feedback">Date is required.</span>
                                </div>
                            </td>
                
                        </tr>
                    </table> 
                    <br>	

<!--================3 table=========================== -->	
	<hr>
<!-- 3 table in td -->	
<table width="100%">
	<tr>	
		<td>
			<div  style="width: 100%;">
			<input type="hidden" id="rowCount_trim" value="1" >		
				<table id="tblSample_trim" style="width:100%;text-align:center ;border-color: white" border="1" class="input-small table-border table row-border cell-border subcompact" >
                        <thead>
                            <th class="input-small text-center">SL</th>
                            <th class="input-small text-center">Order Information<b style="color: red">*</b></th>
                            <th class="input-small text-center">Description<b style="color: red">*</b></th>
                            <th class="input-small text-center">Qty<b style="color: red">*</b></th>
                            <th class="input-small text-center">Unit<b style="color: red">*</b></th>
                            <th  class="input-small text-center"> Rmark's </th>
                            <th></th>
                        </thead>
                        <?php foreach($gatepassItemData as $key=>$item){ ?>
					<tr>
							<td><?php echo $key+1 ?></td>		 
						<td>
							<input type="text" name="itemname[]" value="<?php echo $item['ORDERINFORMATION'] ?>" placeholder="Order Information" id="itemname"  class="input-small" data-role="input" />	
						</td>
						<td>
							<textarea name="description[]" id="description" placeholder="Goods Description"  class="input-small"><?php echo $item['DESCRIPTION'] ?> </textarea>
						</td>
                        <td><input type="text" class="input-small trimPriceCls input-small"  value="<?php echo $item['QTY'] ?>" autocomplete="off" placeholder='Item Quantity' name="qty[]" id="mpt_trim_price0" value="0" onKeyUp="rowCal_trim($(this));"/></td>
						<td>
                        <select data-cls-drop-list="drop-width"  data-role="select"  name="unit_name[]" class="input-small input-small suppliername" data-filter-placeholder="Search Unit Name">

                            <?php echo $appsDependent->dropdownCommon('inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME', $item['UNIT']) ?>
                            </select>
						</td>
						<td><textarea class='input-small' name='remarks[]' id="remarks" placeholder="Remarks" > <?php echo $item['REMARKS'] ?></textarea></td>
						<td><label class="button small alert removeRow_trim" id="remove_trim0"  style="float: right; padding: 1px 6px 2px 6px" ><b style="color: white" title="Remove Row!" onClick="rowCal_trim($(this));" >&#10006;</b></label></td>
					</tr>
                    <?php } ?>
				</table>
				<table width="100%" border="0" class="input-small table row-border">
					<tr>
						<td colspan="5">
						<button type="button" class="button small success" onclick="addRowToTable_trim();" style="float: left; padding: 1px 6px 2px 6px" data-toggle="tooltip" data-placement="top" title="Add Row!" >&#10010;</button>
						</td>
						<td width="" align="right">Total Qty<b style="color: red">*</b>=</td>
						<td width="14.80%"><label> <input  class="input-small totaltrimCls"  style="background-color: #A8DDD9" id="mpt_total_trim_price" name="total_qty" type="text" size="100%" value="<?php echo $gatepassMasterData[0]['TOTAL_QTY'] ?>" readonly placeholder=" Type !"/> </label></td>
					</tr>
				</table>
			</div>
		</td>
		&nbsp;
	</tr>
</table>	
    <br>		
<br><hr>
        <div class="form-group text-center ">
            <button type="submit" name="submit" id="myButton" class="button success rounded mb-4 mt-4 mr-5" value="1">Submit</button>
        </div>
</form>
        </div>
<!-- ================================Content======================================================== -->
<?php
    else:
    ?>
    <div class="input-small row mt-3">
        <div class="input-small cell-md-12 d-flex flex-justify-center flex-align-center">
            <div class="input-small display1 m-2 text-center text-bold" style="color: #d4d4d4;">FKL Gate Pass System</div>
        </div>
    </div>
    <?php
    endif;
else:
    $auth->redirect403();
endif;
?>
            </div>
        </div>
        <script>

        // DYNAMIC TABLE item  -------------------------------------------------------------------------------
        function addRowToTable_trim()
        {
            var tbl = document.getElementById('tblSample_trim');
            var lastRow1 = tbl.rows.length;
            
            var lastRow =  parseInt(document.getElementById("rowCount_trim").value)+1; 				
            document.getElementById("rowCount_trim").value=lastRow;
            
            var iteration =lastRow;
            var row = tbl.insertRow(lastRow1);
    
            var cellLeft = row.insertCell(0);
            var textNode = document.createTextNode(iteration);
            cellLeft.appendChild(textNode);
            var cellRight = row.insertCell(1);
            cellRight.innerHTML = makeElement_trim("itemname",(lastRow-1),"itemname");
            var cellRight = row.insertCell(2);
            cellRight.innerHTML = makeElement_trim("description",(lastRow-1),"description");
            cellRight = row.insertCell(3);
            cellRight.innerHTML = makeElement_trim("mpt_trim_price",(lastRow-1),"qty");
            cellRight = row.insertCell(4);
            cellRight.innerHTML = makeElement_trim("mrd_trim_unit_name",(lastRow-1),"unit_name");
            cellRight = row.insertCell(5);
            cellRight.innerHTML = makeElement_trim("remarks",(lastRow-1),"remarks");
            cellRight = row.insertCell(6);
            cellRight.innerHTML=makeElement_trim("removeRow_trim",(lastRow-1),"removeRow_trim");
            rowCount_fab=rowCount_fab+1; 
        }
    
        var dynamicId =0,dynamicName=0;
        var fadeOutDiv = "fkl";
        var rowCount_fab = "";
        function makeElement_trim(inputType,dynamicId,dynamicName){
                var InputType = inputType; 
                var myHTML_trim    = "";
                switch(InputType) {
                    case 'itemname':
                        myHTML_trim= "<input type='text' placeholder='Order Information'  name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"'  class='input-small' data-role='input' />";  	
                    break;	
                    case 'description':
                        myHTML_trim= "<textarea  name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' placeholder='Goods Description' class='input-small'> </textarea>";  	
                    break;	
                    case 'mpt_trim_price': 			  
                        myHTML_trim= "<input type='text' placeholder='Item Quantity' autocomplete='off' class='input-small trimPriceCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_trim($(this));' >";                  
                    break;
                    case 'mrd_trim_unit_name':
                        myHTML_trim= "<select data-cls-drop-list='drop-width'  data-role='select'  name='unit_name[]' class='input-small input' data-filter-placeholder='Search Item...'>"+"<?= $appsDependent->dropdownCommon('inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME', '0') ?>"+"</select>";	
          	
                    break;	
                    case 'remarks': 			  
                        myHTML_trim= "<textarea class='input-small' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' placeholder='Remark\'s' ></textarea>";                  
                    break;
                    case 'removeRow_trim':
                        myHTML_trim="<label class='button small alert removeRow_trim' id='remove"+dynamicId+"' onClick='rowCal_trim($(this));' style='float: right; padding: 1px 6px 2px 6px'><b style='color: white' title='Remove!'>&#10006;</b></label>";
                    break;
                    }
            return myHTML_trim;
        }
    
    // LAST ROW REMOVE ANY ROW + VALIDATION (CTRL+A) FOR ADD ROW + (CTRL+Z) FOR REMOVE LAST ROW	
        $(document).ready(function(e){
            $('#tblSample_trim').on('click', '.removeRow_trim', function(){ 
            //alert($("#tblSample_fab tr").length);
                if($("#tblSample_trim tr").length <= 2){
                    alert("Can't Remove Last Row !");
                }else{
                    $(this).closest('tr').remove();
                    totalTrimPrice();
                }
            });
        });	
        
            function rowCal_trim(ownClass){
                if(isNaN(ownClass.closest('tr').find('.trimPriceCls').val())){
                alert("Invalid input!");
                console.log('Invalid');
                ownClass.closest('tr').find('.trimPriceCls').val("");}
                var total_trim = isNaN(ownClass.closest('tr').find('.trimPriceCls').val()) || $.trim(ownClass.closest('tr').find('.trimPriceCls').val()) == '' ? 0 : ownClass.closest('tr').find('.trimPriceCls').val() ;
                ownClass.closest('tr').find('.totalTrimCls').val(parseFloat(((parseFloat(total_trim)))));
                totalTrimPrice();
        }
        
        function totalTrimPrice(){
            // alert('No work');
            var value = 0.00;
            $('.trimPriceCls').each(function(index){
                var val = parseFloat($(this).val());
                // alert(val);
                value += val;
            });
            console.log(value);
            document.getElementById('mpt_total_trim_price').value=value;
            // $('.totalTrimCls').val(value); 
            // $('.totalTrimCls').val(parseFloat(value));
            $('.gtTrimCls').val(parseFloat(value));
            getGrandTotal();
        }
     
        function checkForm(form)  {
         //alert("inside check");
        var buyer				= document.getElementById("buyer").value; 
        var stylename				= document.getElementById("stylename").value;
		   
		var seasson				= document.getElementById("seasson").value;  
		   
		var department				= document.getElementById("department").value; 
		var pack_type				= document.getElementById("pack_type").value; 
		   
		   
        var pack_number				= document.getElementById("pack_number").value; 
        var order_qty				= document.getElementById("order_qty").value;
        var mpf_total_fab_price		= document.getElementById("mpf_total_fab_price").value;
        var mpt_total_trim_price	= document.getElementById("mpt_total_trim_price").value;
        var mpo_total_price			= document.getElementById("mpo_total_price").value;
        var mpc_total_cm			= document.getElementById("mpc_total_cm").value;
        var mpm_total_price			= document.getElementById("mpm_total_price").value;
        var mpm_unit_price			= document.getElementById("mpm_unit_price").value;
        var mpm_offer_price			= document.getElementById("mpm_offer_price").value;
    
		if (buyer == null ||buyer == "" || buyer === 0){
            alert("Please Check Buyer!");
            return false;
        }else if (stylename == null ||stylename == "" || stylename === 0){
            alert("Please Check Style Name!");
            return false;
        }
		 else if (seasson == null ||seasson == "" || seasson === 0){
            alert("Please Check Season Name!");
            return false;
        }    
		   
		  else if (department == null ||department == "" || department === 0){
            alert("Please Check Department Name!");
            return false;
        } 
		   
		  else if (pack_type == null ||pack_type == "" || pack_type === 0){
            alert("Please Check Pack Type Name!");
            return false;
        } 

		else if (pack_number == null ||pack_number == "" || pack_number === 0){
            alert("Please Check Pack No No!");
            return false;
        } else if (order_qty == null ||order_qty == "" || order_qty == 0){
            alert("Please Check Order qty No!");
            return false;
        } else if (mpf_total_fab_price == null || mpf_total_fab_price == "" || mpf_total_fab_price == 0){
            alert("Please Check Fabric Cost!");
            return false;
        } else if (mpt_total_trim_price == null ||mpt_total_trim_price == "" || mpt_total_trim_price == 0){
            alert("Please Check Trim Cost!");
            return false;
        } else if (mpo_total_price == null ||mpo_total_price == "" || mpo_total_price == 0){
            alert("Please Check Other Cost!");
            return false;
        } else if (mpc_total_cm == null ||mpc_total_cm == "" || mpc_total_cm == 0){
            alert("Please Check CM!");
            return false;
        } else if (mpm_total_price == null ||mpm_total_price == "" || mpm_total_price == 0){
            alert("Please Check Total Price!");
            return false;
        } else if (mpm_unit_price == null ||mpm_unit_price == "" || mpm_unit_price == 0){
            alert("Please Check Unit Price!");
            return false;
        }else if (mpm_offer_price == null ||mpm_offer_price == "" || mpm_offer_price == 0){
            alert("Please Check Offer Price!");
            return false;
        }
        
        else{
            // form.myButton.disabled = true;
            form.myButton.value = "Please wait...";	    
            return true;
        }
      
      }	 	
 
    // TO REMOVE A ROW FROM A TABLE-----------------------------------------------------------------------
    
        function removeRowFromTable(){
    
            var tbl = document.getElementById('tblSample_fab');
            var lastRow = tbl.rows.length;
            if (lastRow > 2) tbl.deleteRow(lastRow - 1);
            
            rowCount_fab = rowCount_fab-1;
            TotalQty2();
            
            price_counter  = price_counter-1;
            amount_counter = amount_counter-1;
            
            TotalAmount2();
        }
    
    // NUMERIC NUMBER CHECK  -------------------------------------------------------------------------------
              
        function qtyCheck(val,ide){ 
            //alert("qty check");
            var input  = val;
            var status = !isNaN(input);
            if(status == false){
                alert("Invalid input!");
                document.getElementById(ide).value="";
            }else{
                TotalQty(val,ide);
            }
        }	
    // PRICE / RATE / AMOUNT CALCULATION  -------------------------------------------------------------------------------
        function TotalQty(val1,val2){ 
            //alert("total qty");
            var total1 = 0;
            var qty    = 0;
            for(var tq=0;tq<=rowCount_fab;tq++){
                qty=parseFloat(document.getElementById('qtyyyy'+tq).value);
                if(isNaN(qty) || qty == ''){
                    qty=0;
                }
                total1=total1+qty;
                document.getElementById('total_qty').value=total1.toFixed(2);;
            }
    
            Amount(val1,val2);
        }					
         
        //qty*price=amount-------
    
    
        function Amount(val1,val2) { //alert("amount");
            
            var Pos = val2.substring(6);
              var y   = document.getElementById('qtyyyy'+Pos).value;
              var z   = document.getElementById('pricee'+Pos).value;
              var x   = +y * +z;
              document.getElementById('amount'+Pos).value = x.toFixed(2);
            TotalAmount(x,val2);
        }
                        
            function TotalAmount(val1,val2){ //alert("total amount");
            
            var total  = 0;
            var amount = 0;
            for(var ta=0;ta<=rowCount_fab;ta++){
                amount=parseFloat(document.getElementById('amount'+ta).value);
                if(isNaN(amount) || amount == ''){
                    amount=0;
                }
                total=total+amount;
                document.getElementById('total_amount').value=total.toFixed(2);;
            }
        }
    
    // USE IN REMOVE ROW BUTTON FUNC() ---------------------------------------------------------------
                            
        function TotalAmount2(val1,val2){
            var total  = 0;
            var amount = 0;
            for(var ta2=0;ta2<=rowCount_fab;ta2++){
                amount=parseFloat(document.getElementById('amount'+ta2).value);
                if(isNaN(amount) || amount == ''){
                    amount=0;
                }
                total=total+amount;
                document.getElementById('total_amount').value=total.toFixed(2);;
            }
        }
    
        function TotalQty2(val1,val2){
            var total1=0;
            var qty=0;
            for(var tq2=0;tq2<=rowCount_fab;tq2++){
                qty=parseFloat(document.getElementById('qtyyyy'+tq2).value);
                if(isNaN(qty) || qty == ''){
                    qty=0;
                }
                total1=total1+qty;
                document.getElementById('total_qty').value=total1.toFixed(2);;
            }
        }					
                 
        
       function checkForm_backup(form)  {
        // alert("inside check");
        var pi_no				= document.getElementById("pi_no").value;
        var pi_date				= document.getElementById("pi_date").value;	  
        var supplier_id0		= document.getElementById("supplier_id0").value;	
        var product_brand0		= document.getElementById("product_brand0").value;	
        var supplierID0			= document.getElementById("supplierID0").value;	
          
            var total_qty			= document.getElementById("total_qty").value;	
        var total_amount			= document.getElementById("total_amount").value;	
       
       var proType    = [];
       var proDetails = [];
       var proUnit    = [];
       var proQty     = [];
       var proPrice   = [];
       var proAmount  = [];
       var countCls   = [];
           
         $('.proCls').each(function(){
            if($(this).val() == ''){
                //alert('pr');
                proType.push('err');
            } 
         });
         $('.proDetailsCls').each(function(){
            if($(this).val() == ''){
                //alert('pd');
                proDetails.push('err');
            } 
         });
           $('.countCls').each(function(){
            if($(this).val() == ''){
                //alert('pd');
                countCls.push('err');
            } 
         }); 
         $('.unitCls').each(function(){
            if($(this).val() == ''){
                //alert('pd');
                proUnit.push('err');
            } 
         });  
         $('.qtyCls').each(function(){
            if($(this).val() == '' || $(this).val() == 0){
                //alert('pd');
                proQty.push('err');
            } 
         });  
         $('.priceCls').each(function(){
            if($(this).val() == '' || $(this).val() == 0 ){
                //alert('pd');
                proPrice.push('err');
            } 
         });  
         $('.amountCls').each(function(){
            if($(this).val() == '' || $(this).val() == 0){
                //alert('pd');
                proAmount.push('err');
            } 
         });  	   
    
        if (pi_no == ""){
            alert("Please Check PI No!");
            return false;
        }else if (pi_date == ""){
            alert("Please Check PI Date!");
            return false;
        }else if (supplier_id0 == "" || supplierID0 == ""){
            alert("Please Check Supplier!");
            return false;
        }else if (total_qty == "" || total_qty == ""){
            alert("Please Check Total Qty!");
            return false;
        }else if (total_amount == "" || total_amount == ""){
            alert("Please Check Total Amount!");
            return false;
        }
    // dynamic field check ------------------------------------	   
        else if(proType.length > 0){
            alert("Please Check Product Type!");
            return false;
        }else if(proDetails.length > 0){
            alert("Please Check Product Details!");
            return false;
        }else if(countCls.length > 0){
            alert("Please Check Yarn Count!");
            return false;
        }else if(proUnit.length > 0){
            alert("Please Check Unit!");
            return false;
        }else if(proQty.length > 0){
            alert("Please Check Qty!");
            return false;
        }else if(proPrice.length > 0){
            alert("Please Check Price!");
            return false;
        }else if(proAmount.length > 0){
            alert("Please Check Amount!");
            return false;
        }	   	   
        else{
            form.myButton.disabled = true;
            form.myButton.value = "Please wait...";	    
            return true;
        }
      
      }	 	
        
        // REMOVE LAST ROW --------------------------------	
        
        function removeRowFromTable(){
            var tbl = document.getElementById('tblSample_fab');
            var lastRow = tbl.rows.length;
            if (lastRow > 2) tbl.deleteRow(lastRow - 1);
                rowCount_fab=rowCount_fab-1;
        }
    
    </script>		
    
    <script>
        //use for cursor move by arrow key
        $(document).ready(function(e){
            $('body').on('keyup', 'input', function(e) {
                $('input').keyup(function(e){
                    if(e.which==39)
                    $(this).closest('td').next().find('input').focus();
                    else if(e.which==37)
                    $(this).closest('td').prev().find('input').focus();
                    else if(e.which==40)
                    $(this).closest('tr').next().find('td:eq('+$(this).closest('td').index()+')').find('input').focus();
                    else if(e.which==38)
                    $(this).closest('tr').prev().find('td:eq('+$(this).closest('td').index()+')').find('input').focus();
                });
            });
        });
    </script>

<script>
$(document).ready(function(e){
    $('#empid').change(function() {
        var empid = $(this).val();
        $.ajax({
            url: 'gatepassController.php?pageType=fetch_empinformation',
            method: 'POST',
            data:{empid:empid},
            success:function(response){
                var response = JSON.parse(response);

                if(response.success== true){
                    $('#empname').val(response.empinfo[0]['EMPNAME']);
                    $('#gpno').val(response.gpno);
                    $('#designation').val(response.empinfo[0]['DESIGNATION']);
                    $('#from').val( 'FKL ' + response.empinfo[0]['DEPTNAME'] + ' ( ' + response.empinfo[0]['UNIT'] +' )');
                }else if(response.exist == 'Not exist'){
                    $.notify("Invalid Data", "error");
                }
            }
        })
    });
});

$(document).ready(function() {
    // Cache the checkbox and input elements
    var returnable = $('#returnable');
    var from = $('#from');
    var datepicker_returnable = $('#datepicker_return');
    datepicker_returnable.hide();
    // from.on('click', function() {
        returnable.on('change', function() {
        // Toggle the readonly attribute based on checkbox status
        if ($(this).is(':checked')) {
            datepicker_returnable.show();
            // datepicker_returnable.prop('readonly', false);
        } else {
            datepicker_returnable.hide();
        }
    });
});

</script>

<?php include_once('inc/footer.php'); ?>