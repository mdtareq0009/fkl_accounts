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
	if($auth->verifyUserPermission('costsheet', 1)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeature();
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
    /* .drop-width:hover{
        background-color: #5ebdec;
        color: white;
    } */
    /* a:not([href]):not([tabindex]):hover {
    color: inherit;
    text-decoration: none;
} */
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
    /* .select .drop-container {
        position: absolute;
    top: 100%;
    left: 0;
    width: calc(100% + 2px);
    height: auto;
    border: 1px solid #dfdfdf;
    padding: 2px;
    margin-left: -1px;
    z-index: 1000;
    background-color: #fff;
    } */
    /* body{
        padding-left: 15px;
        padding-right: 15px;
    } */
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
                    <!-- <div class="cell-md-4"> -->
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="setting" data-page="<?php //echo $pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-cogs"></span></span> Cost Sheet</h4>
                    <!-- </div>
                    <div class="cell-md-8">
                    </div> -->
<!-- ================================Content======================================================== -->
                <!-- <form action="javascript:void()" method="post" id="form" onsubmit="return checkForm(this);"> -->
                <form action="costsheetSubmit.php?costsheet=create" onkeydown="return disableEnterSubmit(event)" method="post" id="form" name="form"  enctype="multipart/form-data" onsubmit="return checkForm(this);">
                    <table class="input-small table">
                        <tr>
                            <td width="10%">Create Date<b style="color: red">*</b></td>
                            <td width="1%">:</td>
                            <td>
                                <div id="datepicker" class="input-small input-group date"  data-date-format="dd-mm-yyyy" >
                                    <!-- <input type="text" class="input-small reqDateCls" name="mpm_cdate" id="mpm_cdate" value="<?php //echo date('d-M-y') ?>"  style="width: 100px; background-color:palegreen;color: red" placeholder="dd-MM-yyyy" readonly >
                                    <span class="input-small input-group-addon"><i class="input-small far fa-calendar-alt" style="font-size:10px;color:deeppink"></i></span> -->
                                    <input type="text" name="mpm_cdate" class="input-small deliverydate accessories-disable input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" value="<?=date('d-m-Y')?>" data-input-format="%d-%m-%Y" data-clear-button="true">
                                    <span class="input-small invalid_feedback">Delivery date is required.</span>
                                </div>
                            </td>			
                            <td width="3%"></td>
                            <td width="10%">Buyer Name<b style="color: red">*</b></td>
                            <td width="1%">:</td>
                            <td width="11%"> 
                            <!-- <select name="buyer" data-cls-drop-list="drop-width"  data-role="select" id="buyer"	onclick="fetchData('buyer','inv.mrd_buyer', 'MRD_BUYER_ID', 'MRD_BUYER_NAME')" class="input-small suppliername"> -->
                            <select data-cls-drop-list="drop-width"  data-role="select"  name="buyer" id="buyer" class="input-small " data-filter-placeholder="Search Buyer..." onChange="getDepartment(this.value,'create'),getSeasson(this.value,'create');"> <?php echo $appsDependent->dropdownCommon('erp.mer_buyername', 'NBUYERID', 'VNAME', "0") ?>
                            </select>
                            <!-- <select data-cls-drop-list="drop-width"  data-role="select"  name="buyer" id="buyer" class="input-small " data-filter-placeholder="Search Buyer..."> <?php //echo $appsDependent->dropdownCommon('inv.mrd_buyer', 'MRD_BUYER_ID', 'MRD_BUYER_NAME', "0") ?>
                            </select> -->
                            </td>
                            <td width="3%"></td>
                            <td width="10%">Style Name<b style="color:red">*</b></td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                            <!-- <select name="stylename" onclick="fetchData(this.id,'inv.mrd_buyer_style', 'MRD_BUYER_STYLE_ID', 'MRD_BUYER_STYLE_NAME')" id="stylename" class="input-small  suppliername" >
                            </select> -->
                            <!-- <input name="stylename" id="stylename" class="input-small" onkeyup="getSuggestions(this.value)"> -->
                            <!-- <input type="text" id="search" onkeyup="getSuggestions(this.value)"> -->
                            <!-- <div> -->
                                <!-- <textarea name="stylename" id="stylename" class="input-small" onkeyup="getSuggestions(this.value)"></textarea> -->
                                <textarea name="stylename" id="stylename" class="input-small"></textarea>
                                <!-- <div id="suggestions"></div> -->
                            <!-- </div> -->
                            <!-- <select data-cls-drop-list="drop-width"  data-role="select"  name="stylename" id="stylename" class="input-small " data-filter-placeholder="Search Style...">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_buyer_style', 'MRD_BUYER_STYLE_ID', 'MRD_BUYER_STYLE_NAME', "0") ?>
                            </select> -->
                            </td>
                            <td width="3%"></td>
                            <td width="10%">Seasson<b style="color: red">*</b> </td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                            <!-- <select name="seasson" id="seasson" class="input-small  suppliername" onclick="fetchData(this.id,'inv.mrd_buyer_season', 'MRD_BUYER_SEASON_ID', 'MRD_BUYER_SEASON_NAME')"> -->
                            <select name="seasson" id="seasson">
                            <!-- <select name="seasson" id="seasson" class="input-small  suppliername" onclick="dependendFetchData(this.id,document.getElementById('buyer').value,'erp.mer_seassonname', 'NSEASSONCODE', 'VSEASSONNAME','NBUYERID')"> -->
                            </select>
                            <!-- <select data-cls-drop-list="drop-width"  data-role="select"  name="seasson" id="seasson" class="input-small " data-filter-placeholder="Search Seasson...">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_buyer_season', 'MRD_BUYER_SEASON_ID', 'MRD_BUYER_SEASON_NAME', "0") ?>
                            </select> -->
                            </td>				
                        </tr>
                        <!-- <div id="suggestions"></div> -->
                        <tr>
                            <td width="10%">Department<b style="color: red">*</b> </td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                            <!-- <select name="department" id="department" class="input-small suppliername" onclick="fetchData(this.id,'inv.mrd_buyer_dept', 'MRD_BUYER_DEPT_ID', 'MRD_BUYER_DEPT_NAME')"> -->
                            <select name="department" id="department">
                            <!-- <select name="department" id="department" class="input-small suppliername" onclick="dependendFetchData(this.id,document.getElementById('buyer').value,'ERP.MER_DEPARTMENT', 'NDEPTID', 'VDEPTNAME','NBUYERID')"> -->
                            </select>
                            <!-- <select data-cls-drop-list="drop-width"  data-role="select"  name="department" id="department" class="input-small " data-filter-placeholder="Search Department...">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_buyer_dept', 'MRD_BUYER_DEPT_ID', 'MRD_BUYER_DEPT_NAME', "0") ?>
                            </select> -->
                            </td>				
                            <td width="3%"></td>
                            <td width="10%">Pack/Piecs<b style="color: red">*</b> </td>
                            <td width="1%">:</td>
                            <td width="10%"> 
                                <!-- <select data-cls-drop-list="drop-width"  data-role="select"  name="pack_type" id="pack_type" class="input-small " data-filter-placeholder="Search Buyer...">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_ITEM_PACK', 'MRD_ITEM_PACK_ID', 'MRD_ITEM_PACK_NAME', "0") ?>
                            </select> -->
                            <input type="text" hidden name="pack_type" value="1" id="pack_type" >
                            <input type="text" value="Pack" class="input-small" readonly>
                            </td>

                            <td width="3%"></td>
                            <td width="10%">Pieces Per Pack<b style="color: red">*</b> </td>
                            <!-- <td width="10%">Pack Number<b style="color: red">*</b> </td> -->
                            <td width="1%">:</td>
                            <td width="10%"> 
                                    <input type="text" name="pack_number" id="pack_number" value="1" class="input-small" onKeyUp="getGrandTotal();">
                            </td>
                            <td width="3%"></td>

                            <td width="10%">Order Qty</td>
                            <td width="1%">:</td>
                            <td width="10%">						  
                                <input type="text" name="order_qty" id="order_qty" class="input-small" onKeyUp="getGrandTotal();">
                            </td>
                
                        </tr>
                    </table> 
                    <br>	
<!-- =================fab table======================== -->	
                    <br><input type="hidden" id="rowCount_fab" value="1" >	
                    <table  id="tblSample_fab" style="text-align: center;border-color: white" class="input-small table-border table cell-border row-border subcompact" >
                        <thead>
                        <th class="input-small text-center" style="min-width: 10px;">SL</th>
                        <th class="input-small text-center" style="min-width: 150px;max-width:150px;">Item<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 250px;max-width:250px;" >Fabrication<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 150px;max-width:150px;">Color<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 70px;max-width:70px;">Yarn Count<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;max-width:50px;">GSM</th>
                        <th class="input-small text-center" style="min-width: 25px;">CAD Consumption (Kg)</th>
                        <!-- <th class="input-small text-center" style="min-width: 25px;">Ratio<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;">Greige Fabric<br>(Kg)<b style="color: red">*</b></th> -->
                        <th class="input-small text-center" style="min-width: 25px;">Yarn Price<br>(Kg)<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;">Knit Price<br>(Kg)<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;">Dying Price<br>(Kg)<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;">Fabric Cost<br>(Kg)<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;">AOP/YD Price<br>(Kg)<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;">AOP/YD Cost<br>(Kg)<b style="color: red">*</b></th>
                        <th class="input-small text-center" style="min-width: 25px;">Total Cost<b style="color: red">*</b></th>
                        <th></th>
                    </thead>
                    <tr>
                        <td>1</td>			 
                        <td>
                        <!-- <select id="mrd_item_name" onclick="fetchData(this.id,'inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME')"  id="mrd_item_name" name="mrd_item_name[]" class="input-small input-small suppliername text-left" style="text-align:left;min-width:150px;">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME', "0") ?>
                        </select> -->
                        <select data-cls-drop-list="drop-width"  data-role="select"  id="mrd_item_name" data-cls-drop-list="drop-width-item" style="max-width: 150px;" name="mrd_item_name[]" class="input-small input-small suppliername text-left" data-filter-placeholder="Search Fabrication...">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME', "0") ?>
                            </select>
                            <input type="hidden" name="mrd_item_id" id="mrd_item_id0"  value="0" />	
                        </td>
                        <td>
                            <div class="input-small form-group">
                                <!-- <select id="mrd_fab_name0" name="mrd_fab_name[]" style="max-width: 250px;text-align:left;min-width:250px;" class="input-small input-small text-left" onclick="fetchData(this.id,'inv.mrd_fab', 'MRD_FAB_ID', 'MRD_FAB_NAME')">
                                    <?php  //echo $appsDependent->dropdownCommon('inv.mrd_fab', 'MRD_FAB_ID', 'MRD_FAB_NAME', "0") ?>
                                </select> -->
                                <textarea  style="max-width: 250px;text-align:left;min-width:250px;" id="mrd_fab_name0" name="mrd_fab_name[]" class="input-small text-left"></textarea>
                                <!-- <select data-cls-drop-list="drop-width"  data-role="select"  style="max-width: 250px;text-align:left;min-width:250px;" id="mrd_fab_name0" name="mrd_fab_name[]" class="input-small input-small text-left" data-cls-drop-list="drop-width" data-filter-placeholder="Search Fabrication...">
                                    <?php //echo $appsDependent->dropdownCommon('inv.mrd_fab', 'MRD_FAB_ID', 'MRD_FAB_NAME', "0") ?>
                                </select> -->
                                <input type="hidden" name="mrd_fab_id" id="mrd_fab_id0"  value="0" />	
                            </div>
                        </td>
                        <td>
                        <!-- <select name="mrd_color_name[]" style="max-width: 150px;" onclick="fetchData(this.id,'inv.mrd_color1', 'MRD_COLOR_ID', 'MRD_COLOR_NAME')" class="input-small input-small suppliername" id="mrd_color_name0" data-filter-placeholder="Search Color...">
                            </select> -->
                        <textarea  style="max-width: 150px;" name="mrd_color_name[]" class="input-small" id="mrd_color_name0" ></textarea>
                            
                        <!-- <select data-cls-drop-list="drop-width"  data-role="select"  style="max-width: 150px;" data-cls-drop-list="drop-width-item" name="mrd_color_name[]" class="input-small input-small suppliername" id="mrd_color_name0" data-filter-placeholder="Search Color...">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_color1', 'MRD_COLOR_ID', 'MRD_COLOR_NAME', "0") ?>
                            </select> -->
                            <input type="hidden" name="mrd_color_id" id="mrd_color_id0"  value="0" />	
                        </td>
                        <td>
                        <!-- <select onclick="fetchData(this.id,'inv.mrd_yarn_count', 'MRD_YARN_COUNT_ID', 'MRD_YARN_COUNT_NAME')" style="text-align:left;min-width:80px;" name="mrd_yarn_count_name[]" class="input-small input-small suppliername" id="mrd_yarn_count_name">
                            </select> -->
                        <select data-cls-drop-list="drop-width"  data-role="select"  name="mrd_yarn_count_name[]" data-cls-drop-list="drop-width" class="input-small input-small suppliername" id="mrd_yarn_count_name" data-filter-placeholder="Search Color...">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_yarn_count', 'MRD_YARN_COUNT_ID', 'MRD_YARN_COUNT_NAME', "0") ?>
                            </select>
                            <input type="hidden" name="mrd_yarn_count_id" id="mrd_yarn_count_id0"  value="0" />	
                        </td>
                        <td>
                        <!-- <select name="mpf_gsm_name[]" style="text-align:left;min-width:80px;" class="input-small input-small suppliername" id="mpf_gsm_name" onclick="fetchData(this.id,'inv.mrd_fab_gsm', 'MRD_FAB_GSM_SL', 'MRD_FAB_GSM')">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_fab_gsm', 'MRD_FAB_GSM_SL', 'MRD_FAB_GSM', "0") ?>
                            </select> -->
                        <select data-cls-drop-list="drop-width"  data-role="select"  name="mpf_gsm_name[]" data-cls-drop-list="drop-width" class="input-small input-small suppliername" id="mpf_gsm_name" data-filter-placeholder="Search Color...">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_fab_gsm', 'MRD_FAB_GSM_SL', 'MRD_FAB_GSM', "0") ?>
                            </select>
                            <input type="hidden" name="mpf_gsm" id="mpf_gsm0"  value="0" />	
                        </td>
                        <td><input type="text" class="input-small cadConCls input-small" name="mpf_cadcon[]" id="mpf_cadcon0" value="0"  onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off'></td>
                        <!-- <td>
                            <input type="text" readonly  class="input-small ratioCls input-small" name="mpf_ratio[]" id="mpf_ratio"  value="100" onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off'>
                        </td> -->
                        <!-- <td><input type="text" class="input-small fabCls input-small"  readonly name="mpf_greige_fabric[]"  value="0" id="mpf_greige_fabric_id0" onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off' style="background-color: #A8DDD9"/></td> -->
                        <td><input type="text" class="input-small yarnPriceCls input-small"  name="mpf_yarn_price[]" id="mpf_yarn_price0" value="0" onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off'/></td>
                        <td><input type="text" class="input-small knitPriceCls input-small"  name="mpf_knit_price[]" id="mpf_knit_price0" value="0" onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off'/></td>
                        <td><input type="text" class="input-small dyeingPriceCls input-small" name="mpf_dyeing_price[]" id="mpf_dyeing_price0" value="0" onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off'></td>
                        <td><input type="text" class="input-small fabCostCls input-small" 	name="mpf_fab_cost[]" id="mpf_fab_cost0" readonly value="0" onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off' style="background-color:#A8DDD9"></td>
                        <td><input type="text" class="input-small aopPriceCls input-small" 	name="mpf_aop_yd_price[]" id="mpf_aop_yd_price0" value="0"	onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off'/></td>
                        <td><input type="text" class="input-small aopCostCls input-small" readonly 	name="mpf_aop_cost[]" id="mpf_aop_cost0" value="0"	onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off' style="background-color:#A8DDD9"/></td>
                        <td><input type="text" class="input-small fabPriceCls input-small" 	readonly	name="mpf_fab_price[]"    id="mpf_fab_price0"	value="0" onKeyUp="rowCal_fab($(this));" onBlur="rowCal_fab($(this));"  autocomplete='off' style="background-color:#A8DDD9"/></td>
                        <td>
                            <label class="input-small button alert removeRow small" id="remove0"  style="float: right; padding: 1px 6px 2px 6px" ><b style="color: white" title="Remove Row!" onClick="rowCal_fab($(this));" >&#10006;</b></label>
                        </td>
                    </tr>
                </table>
                <table width="100%" >
                    <tr>
                        <td colspan="5">
                        <button type="button" class="button success small" onclick="addRowToTable_Fab();" style="float: left; padding: 1px 6px 2px 6px" 
                                    data-toggle="tooltip" data-placement="top" title="Add Row!" >&#10010;</button>
                        </td>
                        <td width="" align="right">Total Fabric Price<b style="color: red">*</b>=</td>
                        <td width="14.80%"><label> <input  class="input-small totalFabCls"  style="background-color: #A8DDD9" id="mpf_total_fab_price" name="mpf_total_fab_price" type="text" size="100%" value="0" readonly placeholder=" Type !"/> </label></td>
                    </tr>
                </table>
<!--================fab total table=========================== -->	
<!--================3 table=========================== -->	
	<hr>
<!-- 3 table in td -->	
<table width="100%">
	<tr>	
		<td>
			<div  style="width: 90%;">
			<input type="hidden" id="rowCount_trim" value="1" >		
				<table id="tblSample_trim" style="width:100%;text-align:center ;border-color: white" border="1" class="input-small table-border table row-border cell-border subcompact" >
                        <thead>
                            <th class="input-small text-center">SL</th>
                            <th class="input-small text-center">Accesories Name<b style="color: red">*</b>
                            <!-- <button class="button small warning small" title="Create a Accessories" onclick="Metro.dialog.open('#accessories')"><span class="mif-plus"></span></button> -->
                        </th>
                            <th class="input-small text-center">Unit<b style="color: red">*</b>
                                <!-- <button class="button warning small" title="Create a Accessories" onclick="Metro.dialog.open('#unit')"><span class="mif-plus"></span></button> -->
                            </th>
                            <th class="input-small text-center">Price/Dzn<b style="color: red">*</b></th>
                            <th> 
                                <div class="input-small dialog" data-role="dialog" data-close-button="true" id="accessories">
                                    <div class="input-small dialog-title"><span class="input-small mif-plus small"></span> Add Accessories
                                    </div>
                                    <div class="input-small dialog-content">
                                        <!-- <form action="javascript:void(0)" method="post"> -->
                                            <div class="input-small form-group">
                                                <input required type="text" name="accessories_name" id="accessories_name" placeholder="Accessories Name" class="input-small metro-input input-small" data-role="input" required>
                                                <span class="input-small invalid_feedback">Enter a required value</span>
                                                <div class="input-small dialog-actions float-right">
                                                    <button type="submit" onclick="insertDropdown('accessories_name','INV.MRD_TRIM','MRD_TRIM_ID','MRD_TRIM_NAME','MRD_TRIM_SL')" name="add_accessories" id="add_accessories" class="input-small button small success  js-dialog-close">Submit</button>
                                                    <button class="input-small button small js-dialog-close ">Close</button>
                                                </div>
                                            </div>
                                        <!-- </form> -->
                                    </div>
                                </div>
                                <div class="input-small dialog" data-role="dialog" data-close-button="true" id="unit">
                                    <div class="input-small dialog-title"><span class="input-small mif-plus small"></span> Add Unit
                                    </div>
                                    <div class="input-small dialog-content">
                                        <!-- <form action="javascript:void(0)" method="post"> -->
                                            <div class="input-small form-group">
                                                <input required type="text" name="unit_name" id="unit_name" placeholder="Unit Name" class="input-small metro-input input-small" data-role="input" required>
                                                <span class="input-small invalid_feedback">Enter a required value</span>
                                                <div class="input-small dialog-actions float-right">
                                                    <button type="submit" name="add_unit" id="add_unit" class="input-small button small success  js-dialog-close">Submit</button>
                                                    <button class="input-small button small js-dialog-close ">Close</button>
                                                </div>
                                            </div>
                                        <!-- </form> -->
                                    </div>
                                </div>
                            </th>
                        </thead>
					<!-- </tr> -->
					<tr>
						<td>1</td>			 
						<td>
                        <!-- <select name="mrd_trim_name[]" id="mrd_trim_name" class="input-small input-small suppliername" onclick="fetchData(this.id,'inv.mrd_trim', 'MRD_TRIM_ID', 'MRD_TRIM_NAME')">
                            </select> -->
                        <select data-cls-drop-list="drop-width"  data-role="select"  name="mrd_trim_name[]" class="input-small input-small suppliername" data-filter-placeholder="Search Accessories...">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_trim', 'MRD_TRIM_ID', 'MRD_TRIM_NAME', "0") ?>
                            </select>
							<input type="hidden" name="mrd_trim_id" id="mrd_trim_id0"  value="0" />	
						</td>
						<td>
                        <!-- <select name="mrd_trim_unit_name[]" class="input-small" id="mrd_trim_unit_name" style="min-width: 80px;"onclick="fetchData(this.id,'inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME')">
                            <?php //echo $appsDependent->dropdownCommon('inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME', "0") ?>
                            </select> -->
                        <select data-cls-drop-list="drop-width"  data-role="select"  name="mrd_trim_unit_name[]" class="input-small input-small suppliername" data-filter-placeholder="Search Accessories Unit...">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME', "0") ?>
                            </select>
							<input type="hidden" name="mrd_trim_unit_id" id="mrd_trim_unit_id0"  value="0" />	
						</td>
						<td><input type="text" class="input-small trimPriceCls input-small"  name="mpt_trim_price[]" id="mpt_trim_price0" value="0" onKeyUp="rowCal_trim($(this));"/></td>
						<td><label class="button small alert removeRow_trim" id="remove_trim0"  style="float: right; padding: 1px 6px 2px 6px" ><b style="color: white" title="Remove Row!" onClick="rowCal_trim($(this));" >&#10006;</b></label></td>
					</tr>
				</table>

				<table width="100%" border="0" class="input-small table row-border">
					<tr>
						<td colspan="5">
						<button type="button" class="button small success" onclick="addRowToTable_trim();" style="float: left; padding: 1px 6px 2px 6px" data-toggle="tooltip" data-placement="top" title="Add Row!" >&#10010;</button>
						</td>
						<td width="" align="right">Total Trim Price<b style="color: red">*</b>=</td>
						<td width="14.80%"><label> <input  class="input-small totaltrimCls"  style="background-color: #A8DDD9" id="mpt_total_trim_price" name="mpt_total_trim_price" type="text" size="100%" value="0" readonly placeholder=" Type !"/> </label></td>
					</tr>
				</table>
			</div>
		</td>
		&nbsp;
		<td>
			<div  style="width: 90%;">
			<!-- for dynamic table Add / Remove -->		
			<input type="hidden" id="rowCount_oc" value="1" >		
			<!-- performance table -->		
			<table id="tblSample_oc" style="width:100%;text-align: center;border-color: white" border="1" class="input-small table-border table row-border cell-border subcompact" >
                            <thead>
                                <th class="input-small text-center">SL</th>
                                <th class="input-small text-center">Other Cost<b style="color: red">*</b></th>
                                <th class="input-small text-center">Unit<b style="color: red">*</b></th>
                                <th class="input-small text-center">Price/Dzn<b style="color: red">*</b></th>
                                <th></th>
                            </thead>
						<tr>
							<td>1</td>			 
							<td>
                            <!-- <select name="mrd_other_cost_name[]" class="input-small " id="mrd_other_cost_name" onclick="fetchData(this.id,'inv.mrd_other_cost', 'MRD_OTHER_COST_ID', 'MRD_OTHER_COST_NAME')">
                            </select> -->
                            <select data-cls-drop-list="drop-width"  data-role="select"  name="mrd_other_cost_name[]" class="input-small " data-filter-placeholder="Search Other Cost..">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_other_cost', 'MRD_OTHER_COST_ID', 'MRD_OTHER_COST_NAME', "0") ?>
                            </select>
								<input type="hidden" name="mrd_other_cost_id[]" id="mrd_other_cost_id0"  value="0" />	
							</td>
							<td>
                            <!-- <select name="mrd_oc_unit_name[]" class="input-small suppliername" style="min-width: 80px;" id="mrd_oc_unit_name" onclick="fetchData(this.id,'inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME')">
                            </select> -->
                            <select data-cls-drop-list="drop-width"  data-role="select"  name="mrd_oc_unit_name[]" class="input-small " data-filter-placeholder="Search Other's Unit..">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME', "0") ?>
                            </select>
								<input type="hidden" name="mrd_oc_unit_id" id="mrd_oc_unit_id0"  value="0" />	
							</td>
							<td><input type="text" class="input-small ocPriceCls"  name="mpo_other_price[]" id="mpo_other_price0" value="0" onKeyUp="rowCal_oc($(this));"/></td>
							<td><label class="button small alert removeRow_oc" id="remove_oc0"  style="float: right; padding: 1px 6px 2px 6px" ><b style="color: white" title="Remove Row!" onClick="rowCal_oc($(this));" >X</b></label></td>
						</tr>
					</table>

			<!-- total -->		
			<table width="100%" border="0" >
				<tr>
					<td colspan="5">
					<button type="button" class="button small success" onclick="addRowToTable_oc();" data-toggle="tooltip" data-placement="top" title="Add Row!" > &#10010; </button>
					</td>
					<td width="" align="right">Total Other Cost<b style="color: red">*</b>=</td>
					<td width="14.80%"><label> <input  class="input-small totalOcCls"  style="background-color: #A8DDD9" id="mpo_total_price" name="mpo_total_price" type="text" size="100%" value="0" readonly placeholder=" Type !"/> </label></td>
				</tr>
			</table>
			</div>	
		</td>
		&nbsp;
		<td>
			<div  style="width: 90%; background-color:cornsilk">
			<!-- for dynamic table Add / Remove -->		
			<input type="hidden" id="rowCount_pic" value="1" >		
			<!-- performance table -->		
			<table id="tblSample_pic" style="width:98%;border-color: white" border="1" class="input-small subcompact table-border table table-reiv" >
                            <thead>
                                <th class="input-small text-center">SL</th>
                                <th class="input-small text-center">Item<b style="color: red">*</b></th>
                                <th class="input-small text-center">Picture<b style="color: red">*</b></th>
                                <th></th>
                            </thead>
						</tr>
						<tr>
							<td>1</td>			 
							<td>
                            <!-- <select name="mrd_item_name_pic" id="mrd_item_name_pic" style="min-width: 150px;" class="input-small suppliername" onclick="fetchData(this.id,'inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME')">
                            </select> -->
                            <select data-cls-drop-list="drop-width"  data-role="select" style="min-width: 150px;max-width: 150px;" name="mrd_item_name_pic" class="input-small suppliername" data-filter-placeholder="Search Other's Unit..">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME', "0") ?>
                            </select>
								<input type="hidden" name="mrd_item_id_pic" id="mrd_item_id_pic0"  value="0" />	
							</td>
							<td><input type="file" class="picCls"  name="mpp_pic" id="mpp_pic0" accept="image/*"  onKeyUp="rowCal_pic($(this));" onchange="document.getElementById('blah0').src = window.URL.createObjectURL(this.files[0])"><img id="blah0" alt="your image" width="180" height="100" /></td>
							

							
							<!-- <td><label class="input-small button alert removeRow_pic" id="remove0"  style="float: right; padding: 1px 6px 2px 6px" ><b style="color: white" title="Remove Row!"  >&#10006;</b></label></td> -->
						</tr>
					</table>

			<!-- total -->		
			<!-- <table width="100%" border="0" >
				<tr>
					<td colspan="5">
					<button type="button" class="input-small button success" onclick="addRowToTable_pic();" style="float: left; padding: 1px 6px 2px 6px" data-toggle="tooltip" data-placement="top" title="Add Row!" >&#10010;</button>
					</td>
				</tr>
			</table> -->
			</div>	
		</td>	
	</tr>
</table>	
<!--================3 table=========================== -->	
<!--================Cm table=========================== -->	
                <input type="hidden" id="rowCount_cm" value="1" >
                <table id="tblSample_cm" style="width:100%;border-color:black" border="1" class="input-small table-border table cell-border row-border subcompact" >
					<thead>
						<th class="input-small text-center">SL</th>
						<th class="input-small text-center">Item<b style="color: red">*</b></th>
						<th class="input-small text-center">SMV<b style="color: red">*</b></th>
						<th class="input-small text-center">EFF (%)</th>
						<th class="input-small text-center">CPM</th>
						<th class="input-small text-center">PROFIT (%)<b style="color: red">*</b></th>
						<th class="input-small text-center">Excess Accesories<b style="color: red">*</b></th>
						<th class="input-small text-center">CM<b style="color: red">*</b></th>
						<th></th>
					</thead>
					<tr>
						<td>1</td>			 
						<td>
                        <!-- <select name="mrd_item_name_cm[]" style="min-width: 150px;" class="input-small" id="mrd_item_name_cm" onclick="fetchData(this.id,'inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME')">
                            </select> -->
                        <select data-cls-drop-list="drop-width"  data-role="select"  name="mrd_item_name_cm[]" class="input-small input" data-filter-placeholder="Search Other's Unit..">
                            <?php echo $appsDependent->dropdownCommon('inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME', "0") ?>
                            </select>
							<input type="hidden" name="mrd_item_id_cm" id="mrd_item_id_cm0"  value="0" />	
						</td>

						<!------------------------------PRODUCT  DETAILS--------------------------------------->

						<td><input type="text" class="input-small smvCls"  		name="mpc_smv[]" id="mpc_smv0" value="0" onKeyUp="rowCal_cm($(this));"/></td>
						<td><input type="text" class="input-small effCls" 		name="mpc_eff[]" id="mpc_eff0" value="0" onKeyUp="rowCal_cm($(this));"/></td>
						<td><input type="text" class="input-small cpmCls"  		name="mpc_cpm[]" id="mpc_cpm0" value="0" onKeyUp="rowCal_cm($(this));"/></td>
						<!-- <td><input type="text" class="input-small cpmCls"  		name="mpc_cpm[]" id="mpc_cpm0" value="0.042" onKeyUp="rowCal_cm($(this));"/></td> -->
						<td><input type="text" class="input-small profitCls" 	name="mpc_profit[]" id="mpc_profit0" value="0" onKeyUp="rowCal_cm($(this));"></td>
						<td><input type="text" class="input-small excessAccCls" name="mpc_excess_acc[]" id="mpc_excess_acc0" value="0"	onKeyUp="rowCal_cm($(this));"/></td>	
						<td><input type="text" class="input-small cmCls" 		name="mpc_cm[]"    id="mpc_cm0"	value="0" onKeyUp="cmToTotalCm()"/></td>
						<!-- <td><input type="text" class="input-small cmCls" 		name="mpc_cm[]"    id="mpc_cm0"	value="0"	onKeyUp="rowCal_cm($(this));"/></td> -->
						<td>
							<label class="button small alert removeRow_cm" id="removeCm0"  style="float: right; padding: 1px 6px 2px 6px" ><b style="color: white" title="Remove Row!" onClick="rowCal_cm($(this));" >&#10006;</b></label>
						</td>
					</tr>
			</table>
            <table width="100%" border="0" >
                <tr>
                    <td colspan="5">
                    <button type="button" class="button small success" onclick="addRowToTable_cm();" style="float: left; padding: 1px 6px 2px 6px" data-toggle="tooltip" data-placement="top" title="Add Row!" >&#10010;</button>
                    </td>
                    <td width="" align="right">Total Profit<b style="color: red">*</b>=</td>
                    <td width="14.80%"><label> <input  class="input-small totalProfitCls"  style="background-color: #A8DDD9" id="mpc_total_profit" name="mpc_total_profit" type="text" size="100%" value="0" readonly placeholder=" Type !"/> </label></td>
                    <td width="" align="right">Total Excess Acc<b style="color: red">*</b>=</td>
                    <td width="14.80%"><label> <input  class="input-small totalExcessCls"  style="background-color: #A8DDD9" id="mpc_total_exces" name="mpc_total_exces" type="text" size="100%" value="0" readonly placeholder=" Type !"/> </label></td>
                    <td width="" align="right">Total CM Price<b style="color: red">*</b>=</td>
                    <td width="14.80%"><label> <input  class="input-small totalCmCls"  style="background-color: #A8DDD9" id="mpc_total_cm" name="mpc_total_cm" type="text" size="100%" value="0" readonly placeholder=" Type !"/> </label></td>
                </tr>
            </table>
<!--================Cm table=========================== -->	
<!--================Grand Total table=========================== -->	
<hr>
<table width="100%" style="text-align: center" border="0">
		<tr>
			<td>Fabric Price/Dzn<b style="color:red">*</b></td>
			<td>:</td>
			<td> 
				  <input type="number" class="input-small gtFabCls" readonly name="mpm_total_fabric_price" id="mpm_total_fabric_price" value="0" style="background-color: #A8DDD9">				
			</td>
			<td></td>
			<td>Trim Price/Dzn<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtTrimCls" readonly name="mpm_total_trim_price" id="mpm_total_trim_price" value="0" style="background-color: #A8DDD9">
			</td>				
				<td></td>
			<td>Other Price/Dzn<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtOcCls" readonly name="mpm_total_other_price" id="mpm_total_other_price" value="0" style="background-color: #A8DDD9">
			</td>			
				<td></td>
			<td>Material Price/Dzn<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtMateCls" readonly name="mpm_total_material_price" id="mpm_total_material_price" value="0" style="background-color: #A8DDD9">
			</td>			
				<td></td>
			<td>CM/Dzn<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtCmCls" readonly name="mpm_total_cm_price" id="mpm_total_cm_price" value="0" style="background-color: #A8DDD9">
			</td>	
			<td></td>
			<td>Confirm Price/Pcs<b style="color: red">*</b> </td>
			<!-- <td>Offer Price<b style="color: red">*</b> </td> -->
			<td>:</td>
			<td> 
				<input type="text" name="mpm_offer_price" class="input-small" id="mpm_offer_price" placeholder="Price !" onKeyUp="getComPrice()">
			</td>	
		</tr>
		<tr>
			<td>Can Be/Dzn<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtCbCls" readonly name="mpm_total_cb_price" id="mpm_total_cb_price" value="0" style="background-color: #A8DDD9">
			</td>		
			<td></td>
			<td>Profit/Dzn<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtProCls" readonly name="mpm_profit_price" id="mpm_profit_price" value="0" style="background-color: #A8DDD9">
			</td>	
			<td></td>
			<td>Fob/Dzn<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtFobCls" readonly name="mpm_fob_price" id="mpm_fob_price" value="0" style="background-color: #A8DDD9">
			</td>	
			<td></td>
			<td>Unit Price<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtUnitCls" readonly name="mpm_unit_price" id="mpm_unit_price" value="0" style="background-color: #A8DDD9">
			</td>
			<td></td>
			<td>Total Price<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				 <input type="number" class="input-small gtTotalCls" readonly name="mpm_total_price" id="mpm_total_price" value="0" style="background-color: #A8DDD9">
			</td>
			<td></td>
			<td>Price Comparision<b style="color: red">*</b> </td>
			<td>:</td>
			<td> 
				<input type="number" name="mpm_price_deff" id="mpm_price_deff" class="input-small" readonly style="background-color: #A8DDD9" placeholder="Price Comparision !" >
			</td>
		</tr>
	</table>

<!--================Grand Total table=========================== -->	
	<!-- =======Remarks========= -->
    <br>		
	<table>
<!--    <tr>
				<td width="3%"></td>
				<td width="1%"></td>
				<td width="1%">:</td>
				<td width="15%">
					<input type="text" name="mpm_offer_price" id="mpm_offer_price" size="30" placeholder="Price !" onKeyUp="getComPrice()">
				</td>		
				<td width="3%"></td>
		</tr>	-->
		<script>
		function getComPrice(){
			var totalPrice=document.getElementById("mpm_unit_price").value;
			var offerPrice=document.getElementById("mpm_offer_price").value;
			
			document.getElementById("mpm_price_deff").value=(+offerPrice - +totalPrice);
			//document.getElementById("mpm_price_deff_view").value=(+totalPrice - +offerPrice);
		}
		
		</script>	
	<!--	<tr>
				<td width="3%"></td>
				<td width="1%">Price Comparision</td>
				<td width="1%">:</td>
				<td width="15%">
					<input type="text" name="mpm_price_deff" id="mpm_price_deff" size="30" placeholder="Price Comparision !" >
				</td>		
				<td width="3%"></td>
		</tr>-->
		<tr>
				<td></td>
				<td>Remarks</td>
				<td>:</td>
				<td>
					<textarea type="text" name="mpm_remarks" id="mpm_remarks" rows="5" cols="50" placeholder="Remarks !" ></textarea>
				</td>		
				<td width="3%"></td>
		</tr>	
        
	</table>
    <!-- ===========Remarks============== -->
<br><hr>
        <div class="form-group text-center ">
            <!-- <button type="submit" name="myButton" id="myButton" class="input-small button success mb-4 mt-4">Submit</button> -->
            <!-- <button type="submit" name="btn" id="btn" class="input-small button success mb-4 mt-4">Submit</button> -->
            <button type="submit" name="myButton" id="myButton" onkeydown="disableEnterSubmit(event)" class="button  warning rounded mb-4 mt-4 ml-5" value="0">Save</button>
            <button type="submit" name="myButton" id="myButton" onkeydown="disableEnterSubmit(event)" class="button success rounded mb-4 mt-4 mr-5" value="1">Publish</button>

        </div>
</form>
        </div>
<!-- ================================Content======================================================== -->
<?php
    else:
    ?>
    <div class="input-small row mt-3">
        <div class="input-small cell-md-12 d-flex flex-justify-center flex-align-center">
            <div class="input-small display1 m-2 text-center text-bold" style="color: #d4d4d4;">Cost Sheet</div>
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

    // DYNAMIC TABLE fab  -------------------------------------------------------------------------------
        function addRowToTable_Fab()
        {
            var tbl = document.getElementById('tblSample_fab');
            var lastRow1 = tbl.rows.length;
            
            var lastRow =  parseInt(document.getElementById("rowCount_fab").value)+1; 				
            document.getElementById("rowCount_fab").value=lastRow;
            
            var iteration =lastRow;
            var row = tbl.insertRow(lastRow1);
    
            var cellLeft = row.insertCell(0);
            var textNode = document.createTextNode(iteration);
            cellLeft.appendChild(textNode);
            var cellRight = row.insertCell(1);
            cellRight.innerHTML = makeElement_fab("mrd_item_name",(lastRow-1),"mrd_item_name");
            cellRight = row.insertCell(2);
            cellRight.innerHTML = makeElement_fab("mrd_fab_id",(lastRow-1),"mrd_fab_id");
            cellRight = row.insertCell(3);
            cellRight.innerHTML = makeElement_fab("mrd_color_id",(lastRow-1),"mrd_color_id");
            cellRight = row.insertCell(4);
            cellRight.innerHTML = makeElement_fab("mrd_yarn_count_id",(lastRow-1),"mrd_yarn_count_id");
            cellRight = row.insertCell(5);
            cellRight.innerHTML = makeElement_fab("mpf_gsm",(lastRow-1),"mpf_gsm");
            cellRight = row.insertCell(6);
            cellRight.innerHTML = makeElement_fab("mpf_cadcon",(lastRow-1),"mpf_cadcon");
            // cellRight = row.insertCell(7);
            // cellRight.innerHTML = makeElement_fab("mpf_ratio",(lastRow-1),"mpf_ratio");
            // cellRight = row.insertCell(8);
            // cellRight.innerHTML = makeElement_fab("mpf_greige_fabric",(lastRow-1),"mpf_greige_fabric");
            cellRight = row.insertCell(7);
            cellRight.innerHTML = makeElement_fab("mpf_yarn_price",(lastRow-1),"mpf_yarn_price");
            cellRight = row.insertCell(8);
            cellRight.innerHTML = makeElement_fab("mpf_knit_price",(lastRow-1),"mpf_knit_price");
            cellRight = row.insertCell(9);
            cellRight.innerHTML = makeElement_fab("mpf_dyeing_price",(lastRow-1),"mpf_dyeing_price");
            cellRight = row.insertCell(10);
            cellRight.innerHTML = makeElement_fab("mpf_fab_cost",(lastRow-1),"mpf_fab_cost");
            cellRight = row.insertCell(11);
            cellRight.innerHTML = makeElement_fab("mpf_aop_yd_price",(lastRow-1),"mpf_aop_yd_price");
            cellRight = row.insertCell(12);
            cellRight.innerHTML = makeElement_fab("mpf_aop_cost",(lastRow-1),"mpf_aop_cost");
            cellRight = row.insertCell(13);
            cellRight.innerHTML = makeElement_fab("mpf_fab_price",(lastRow-1),"mpf_fab_price");
            cellRight = row.insertCell(14);
            cellRight.innerHTML=makeElement_fab("removeRow",(lastRow-1),"removeRow");
            rowCount_fab=rowCount_fab+1; 
        }
    
        var dynamicId =0,dynamicName=0;
        var fadeOutDiv = "fkl";
        var rowCount_fab = "";
        function makeElement_fab(inputType,dynamicId,dynamicName){
                var InputType = inputType; 
                var myHTML_Fab    = "";
                switch(InputType) {
                    case 'mrd_item_name':
                        myHTML_Fab= "<select data-cls-drop-list='drop-width'  data-role='select'  style='max-width:150px;' name='mrd_item_name[]' class='input-small input-small suppliername' data-filter-placeholder='Search Fabrications...'>"+"<?= $appsDependent->dropdownCommon('inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME', '0') ?>"+"</select>"; 	 	
                    break;	
                    case 'mrd_fab_id':
                        myHTML_Fab= "<textarea style='max-width:250px;' name='mrd_fab_name[]' class='input-small' ></textarea>"; 	
                        // myHTML_Fab= "<select data-cls-drop-list='drop-width'  data-role='select'  style='max-width:250px;' name='mrd_fab_name[]' class='input-small input-small suppliername' data-filter-placeholder='Search Fabrications...'>"+"<?php //echo $appsDependent->dropdownCommon('inv.mrd_fab', 'MRD_FAB_ID', 'MRD_FAB_NAME', '0') ?>"+"</select>"; 	
                    break;	
                    case 'mrd_color_id':
                        myHTML_Fab= "<textarea style='max-width:250px;' name='mrd_color_name[]' class='input-small' ></textarea>";
                        // myHTML_Fab="<select data-cls-drop-list='drop-width'  data-role='select'  style='max-width:150px;' name='mrd_color_name[]' class='input-small input-small suppliername' data-filter-placeholder='Search Fabrications...'>"+"<?php //echo $appsDependent->dropdownCommon('inv.MRD_COLOR1', 'MRD_COLOR_ID', 'MRD_COLOR_NAME', '0') ?>"+"</select>"; 	
                    break;	
                    case 'mrd_yarn_count_id':
                        myHTML_Fab="<select data-cls-drop-list='drop-width'  data-role='select'  name='mrd_yarn_count_name[]' class='input-small input-small suppliername' data-filter-placeholder='Search Fabrications...'>"+"<?= $appsDependent->dropdownCommon('inv.mrd_yarn_count', 'MRD_YARN_COUNT_ID', 'MRD_YARN_COUNT_NAME', '0') ?>"+"</select>"; 		
                    break;			
                    case 'mpf_gsm': 			  
                        myHTML_Fab= "<select data-cls-drop-list='drop-width'  data-role='select'  name='mpf_gsm_name[]' class='input-small input-small suppliername' data-filter-placeholder='Search Fabrications...'>"+"<?= $appsDependent->dropdownCommon('inv.mrd_FAB_GSM', 'MRD_FAB_GSM_SL', 'MRD_FAB_GSM', '0') ?>"+"</select>";                              
                    break;	
                    case 'mpf_cadcon': 			  
                        myHTML_Fab= "<input type='text' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' class='input-small cadConCls' value='0' onKeyUp='rowCal_fab($(this));' onBlur='rowCal_fab($(this));' autocomplete='off'>";                  
                    break;	
                    
                    // case 'mpf_ratio': 			  
                    //     myHTML_Fab= "<input type='text' readonly class='input-small ratioCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='100' onKeyUp='rowCal_fab($(this));' onBlur='rowCal_fab($(this));' autocomplete='off'>";                  
                    // break;	
                    // case 'mpf_greige_fabric': 			  
                    //     myHTML_Fab= "<input type='text' class='input-small fabCls' readonly name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' style='background-color: #A8DDD9'>";  
                    // break;	
                     case 'mpf_yarn_price':
                       myHTML_Fab= "<input type='text' name='"+dynamicName+"[]'  id='"+dynamicName+dynamicId+"' onKeyUp='rowCal_fab($(this));' onBlur='rowCal_fab($(this));' autocomplete='off' value='0' class='input-small yarnPriceCls' ;\">";				 
                       break;
                       case 'mpf_knit_price':
                       myHTML_Fab= "<input type='text' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' onKeyUp='rowCal_fab($(this));' onBlur='rowCal_fab($(this));' autocomplete='off' value='0' class='input-small knitPriceCls';\">";				 
                       break;				   
                      case 'mpf_dyeing_price':
                       myHTML_Fab= "<input type='text' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' onKeyUp='rowCal_fab($(this));' onBlur='rowCal_fab($(this));' autocomplete='off' value='0' class='input-small dyeingPriceCls';\">";
                       break;	
                    case 'mpf_fab_cost': 			  
                        myHTML_Fab= "<input type='text' class='input-small fabCostCls' readonly name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' style='background-color: #A8DDD9'>";  
                    break;		
                    case 'mpf_aop_yd_price':
                       myHTML_Fab= "<input type='text' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' onKeyUp='rowCal_fab($(this));' onBlur='rowCal_fab($(this));' autocomplete='off' value='0' class='input-small aopPriceCls';\">";
                    break;
                    case 'mpf_aop_cost': 			  
                        myHTML_Fab= "<input type='text' class='input-small aopCostCls' readonly name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' style='background-color: #A8DDD9'>";  
                    break;		
                    case 'mpf_fab_price':
                       myHTML_Fab= "<input type='text' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' class='input-small fabPriceCls' value='0' style='background-color:#A8DDD9' readonly \">";
                       break;	 
                    case 'removeRow':
                        myHTML_Fab="<label class='button small alert removeRow' id='remove"+dynamicId+"' onClick='rowCal_fab($(this));' style='float: right; padding: 1px 6px 2px 6px'><b style='color: white' title='Remove!'>&#10006;</b></label>";
                    break;
                    }
            return myHTML_Fab;
        }
        
    // LAST ROW REMOVE ANY ROW + VALIDATION (CTRL+A) FOR ADD ROW + (CTRL+Z) FOR REMOVE LAST ROW	
        $(document).ready(function(e){
            $('#tblSample_fab').on('click', '.removeRow', function(){ 
            //alert($("#tblSample_fab tr").length);
                if($("#tblSample_fab tr").length <= 2){
                    alert("Can't Remove Last Row !");
                }else{
                    $(this).closest('tr').remove();
                    totalFabPrice();
                }
            });
    
        });	
             
        function rowCal_fab(ownClass){
                if(isNaN(ownClass.closest('tr').find('.cadConCls').val())){
                //alert("Invalid input!");
                ownClass.closest('tr').find('.cadConCls').val("");}
                var cadCon   = isNaN(ownClass.closest('tr').find('.cadConCls').val()) || $.trim(ownClass.closest('tr').find('.cadConCls').val()) == '' ? 0 : ownClass.closest('tr').find('.cadConCls').val() ; 
            
                // if(isNaN(ownClass.closest('tr').find('.ratioCls').val())){
                // alert("Invalid input!");
                // ownClass.closest('tr').find('.ratioCls').val("");}
                // var ratio   = isNaN(ownClass.closest('tr').find('.ratioCls').val()) || $.trim(ownClass.closest('tr').find('.ratioCls').val()) == '' ? 0 : ownClass.closest('tr').find('.ratioCls').val() ; 
            
                // if(isNaN(ownClass.closest('tr').find('.fabCls').val())){
                // alert("Invalid input!");
                // ownClass.closest('tr').find('.fabCls').val("");}
                // var fab   = isNaN(ownClass.closest('tr').find('.fabCls').val()) || $.trim(ownClass.closest('tr').find('.fabCls').val()) == '' ? 0 : ownClass.closest('tr').find('.fabCls').val() ; 
            
                if(isNaN(ownClass.closest('tr').find('.yarnPriceCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.yarnPriceCls').val("");}
                var yarn = isNaN(ownClass.closest('tr').find('.yarnPriceCls').val()) || $.trim(ownClass.closest('tr').find('.yarnPriceCls').val()) == '' ? 0 : ownClass.closest('tr').find('.yarnPriceCls').val() ;
            
                if(isNaN(ownClass.closest('tr').find('.knitPriceCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.knitPriceCls').val("");}
                var knit   = isNaN(ownClass.closest('tr').find('.knitPriceCls').val()) || $.trim(ownClass.closest('tr').find('.knitPriceCls').val()) == '' ? 0 : ownClass.closest('tr').find('.knitPriceCls').val() ; 
            
                if(isNaN(ownClass.closest('tr').find('.dyeingPriceCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.dyeingPriceCls').val("");}
                var dyeing = isNaN(ownClass.closest('tr').find('.dyeingPriceCls').val()) || $.trim(ownClass.closest('tr').find('.dyeingPriceCls').val()) == '' ? 0 : ownClass.closest('tr').find('.dyeingPriceCls').val() ;
                
                if(isNaN(ownClass.closest('tr').find('.aopPriceCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.aopPriceCls').val("");}
                var aop = isNaN(ownClass.closest('tr').find('.aopPriceCls').val()) || $.trim(ownClass.closest('tr').find('.aopPriceCls').val()) == '' ? 0 : ownClass.closest('tr').find('.aopPriceCls').val() ;
            
                if(isNaN(ownClass.closest('tr').find('.aopCostCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.aopCostCls').val("");}
                var aop_cost = isNaN(ownClass.closest('tr').find('.aopCostCls').val()) || $.trim(ownClass.closest('tr').find('.aopCostCls').val()) == '' ? 0 : ownClass.closest('tr').find('.aopCostCls').val() ;
                
                if(isNaN(ownClass.closest('tr').find('.fabCostCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.fabCostCls').val("");}
                var fab_cost = isNaN(ownClass.closest('tr').find('.fabCostCls').val()) || $.trim(ownClass.closest('tr').find('.fabCostCls').val()) == '' ? 0 : ownClass.closest('tr').find('.fabCostCls').val() ;
            
           // ratio=ratio/100;
            //used fabric	
            //ownClass.closest('tr').find('.fabCls').val(parseFloat(((parseFloat(ratio) * parseFloat(cadCon)))));
            //fab cost
            ownClass.closest('tr').find('.fabCostCls').val(parseFloat((((parseFloat(yarn) + parseFloat(knit) + parseFloat(dyeing)) * (cadCon)))));
            //aop cost	
            ownClass.closest('tr').find('.aopCostCls').val(parseFloat(((parseFloat(cadCon) * parseFloat(aop) ))));
            //total cost
            ownClass.closest('tr').find('.fabPriceCls').val(parseFloat((parseFloat(fab_cost) + parseFloat(aop_cost))));
                totalFabPrice();
        }
        
        function totalFabPrice(){
            var value = 0.00;
            $('.fabPriceCls').each(function(index){
                var val = parseFloat($(this).val());
                value += val;
            });
            //$('.totalAmountCls').val(value); 
            $('.totalFabCls').val(parseFloat(value));
            //grand total
            $('.gtFabCls').val(parseFloat(value));
            getGrandTotal();
        }
        
        // DYNAMIC TABLE trim  -------------------------------------------------------------------------------
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
            cellRight.innerHTML = makeElement_trim("mrd_trim_name",(lastRow-1),"mrd_trim_name");
            cellRight = row.insertCell(2);
            cellRight.innerHTML = makeElement_trim("mrd_trim_unit_name",(lastRow-1),"mrd_trim_unit_name");
            cellRight = row.insertCell(3);
            cellRight.innerHTML = makeElement_trim("mpt_trim_price",(lastRow-1),"mpt_trim_price");
            cellRight = row.insertCell(4);
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
                    case 'mrd_trim_name':
                        myHTML_trim= "<select data-cls-drop-list='drop-width'  data-role='select'  name='mrd_trim_name[]' class='input-small input' data-filter-placeholder='Search Item...'>"+"<?= $appsDependent->dropdownCommon('inv.mrd_trim', 'MRD_TRIM_ID', 'MRD_TRIM_NAME', '0') ?>"+"</select>";
                   	
                    break;	
                    case 'mrd_trim_unit_name':
                        myHTML_trim= "<select data-cls-drop-list='drop-width'  data-role='select'  name='mrd_trim_unit_name[]' class='input-small input' data-filter-placeholder='Search Item...'>"+"<?= $appsDependent->dropdownCommon('inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME', '0') ?>"+"</select>";	
          	
                    break;	
                    case 'mpt_trim_price': 			  
                        myHTML_trim= "<input type='text' class='input-small trimPriceCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_trim($(this));' >";                  
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
        
        
        // DYNAMIC TABLE Other cost  -------------------------------------------------------------------------------
        function addRowToTable_oc()
        {
            var tbl = document.getElementById('tblSample_oc');
            var lastRow1 = tbl.rows.length;
            
            var lastRow =  parseInt(document.getElementById("rowCount_oc").value)+1; 				
            document.getElementById("rowCount_oc").value=lastRow;
            
            var iteration =lastRow;
            var row = tbl.insertRow(lastRow1);
    
            var cellLeft = row.insertCell(0);
            var textNode = document.createTextNode(iteration);
            cellLeft.appendChild(textNode);
            var cellRight = row.insertCell(1);
            cellRight.innerHTML = makeElement_oc("mrd_other_cost_name",(lastRow-1),"mrd_other_cost_name");
            cellRight = row.insertCell(2);
            cellRight.innerHTML = makeElement_oc("mrd_oc_unit_name",(lastRow-1),"mrd_oc_unit_name");
            cellRight = row.insertCell(3);
            cellRight.innerHTML = makeElement_oc("mpo_other_price",(lastRow-1),"mpo_other_price");
            cellRight = row.insertCell(4);
            cellRight.innerHTML=makeElement_oc("remove_oc",(lastRow-1),"remove_oc");
            rowCount_fab=rowCount_fab+1; 
        }
    
        var dynamicId =0,dynamicName=0;
        var fadeOutDiv = "fkl";
        var rowCount_oc = "";
        function makeElement_oc(inputType,dynamicId,dynamicName){
                var InputType = inputType; 
                var myHTML_oc    = "";
                switch(InputType) {
                    case 'mrd_other_cost_name':
                     	
                        myHTML_oc= "<select data-cls-drop-list='drop-width'  data-role='select'  name='mrd_other_cost_name[]' class='input-small input' data-filter-placeholder='Search Item...'>"+"<?= urldecode($appsDependent->dropdown('inv.mrd_other_cost', 'MRD_OTHER_COST_ID', 'MRD_OTHER_COST_NAME', '0')) ?>"+"</select>";
                    break;	
                    case 'mrd_oc_unit_name':
                        myHTML_oc= "<select data-cls-drop-list='drop-width'  data-role='select'  name='mrd_oc_unit_name[]' class='input-small input-small suppliername' data-filter-placeholder='Search Item...'>"+"<?= urldecode($appsDependent->dropdownCommon('inv.mrd_trim_unit', 'MRD_TRIM_UNIT_ID', 'MRD_TRIM_UNIT_NAME', '0')) ?>"+"</select>"; 	
                    break;	
                    case 'mpo_other_price': 			  
                        myHTML_oc= "<input type='text' class='input-small ocPriceCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_oc($(this));'>";                  
                    break;
                    case 'remove_oc':
                        myHTML_oc="<label class='button small alert removeRow_oc' id='remove_oc"+dynamicId+"' onClick='rowCal_oc($(this));' style='float: right; padding: 1px 6px 2px 6px'><b style='color: white' title='Remove!'>&#10006;</b></label>";
                    break;
                    }
            return myHTML_oc;
        }
    
    // LAST ROW REMOVE ANY ROW + VALIDATION (CTRL+A) FOR ADD ROW + (CTRL+Z) FOR REMOVE LAST ROW	
        $(document).ready(function(e){
            $('#tblSample_oc').on('click', '.removeRow_oc', function(){ 
            //alert($("#tblSample_fab tr").length);
                if($("#tblSample_oc tr").length <= 2){
                    alert("Can't Remove Last Row !");
                }else{
                    $(this).closest('tr').remove();
                    totalOcPrice();
                }
            });
        });	
        
            function rowCal_oc(ownClass){
                if(isNaN(ownClass.closest('tr').find('.ocPriceCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.ocPriceCls').val("");}
                var total_oc = isNaN(ownClass.closest('tr').find('.ocPriceCls').val()) || $.trim(ownClass.closest('tr').find('.ocPriceCls').val()) == '' ? 0 : ownClass.closest('tr').find('.ocPriceCls').val() ;
                ownClass.closest('tr').find('.totalOcCls').val(parseFloat(((parseFloat(total_oc)))));
                totalOcPrice();
        }
        
        function totalOcPrice(){
            var value = 0.00;
            $('.ocPriceCls').each(function(index){
                var val = parseFloat($(this).val());
                value += val;
            });
            //$('.totalAmountCls').val(value); 
            $('.totalOcCls').val(parseFloat(value));
            $('.gtOcCls').val(parseFloat(value));
            getGrandTotal();
        }
    
        // DYNAMIC TABLE Pic  -------------------------------------------------------------------------------
        function addRowToTable_pic()
        {
            var tbl = document.getElementById('tblSample_pic');
            var lastRow1 = tbl.rows.length;
            
            var lastRow =  parseInt(document.getElementById("rowCount_pic").value)+1; 				
            document.getElementById("rowCount_pic").value=lastRow;
            
            var iteration =lastRow;
            var row = tbl.insertRow(lastRow1);
    
            var cellLeft = row.insertCell(0);
            var textNode = document.createTextNode(iteration);
            cellLeft.appendChild(textNode);
            var cellRight = row.insertCell(1);
            cellRight.innerHTML = makeElement_pic("mrd_item_name_pic",(lastRow-1),"mrd_item_name_pic");
            cellRight = row.insertCell(2);
            cellRight.innerHTML = makeElement_pic("mpp_pic",(lastRow-1),"mpp_pic");
            cellRight = row.insertCell(3);
            cellRight.innerHTML=makeElement_pic("removeRow_pic",(lastRow-1),"removeRow_pic");
            rowCount_fab=rowCount_fab+1; 
        }
    
        var dynamicId =0,dynamicName=0;
        var fadeOutDiv = "fkl";
        var rowCount_oc = "";
        function makeElement_pic(inputType,dynamicId,dynamicName){
                var InputType = inputType; 
                var myHTML_pic    = "";
                switch(InputType) {
                    case 'mrd_item_name_pic':
                        myHTML_pic= "<select data-cls-drop-list='drop-width'  data-role='select'  name='mrd_item_name_pic' class='input-small input' data-filter-placeholder='Search Item...'>"+"<?= urldecode($appsDependent->dropdown('inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME', '0')) ?>"+"</select>"; 		
                    break;	
                    case 'mpp_pic': 			  
                        myHTML_pic= "<input type='file' class='input-small picCls'  name='mpp_pic' id='mpp_pic"+dynamicId+"' accept='image/*' onKeyUp='rowCal_pic($(this));' onchange=\"document.getElementById('blah"+dynamicId+"').src = window.URL.createObjectURL(this.files[0])\"><img id='blah"+dynamicId+"' alt='your image' width='400' height='100' />";
                    break;
                    case 'removeRow_pic':
                        myHTML_pic="<label class='button small alert removeRow_pic' id='remove"+dynamicId+"'  style='float: right; padding: 1px 6px 2px 6px'><b style='color: white' title='Remove!'>&#10006;</b></label>";
                    break;
                    }
            return myHTML_pic;
        }
    
    // LAST ROW REMOVE ANY ROW + VALIDATION (CTRL+A) FOR ADD ROW + (CTRL+Z) FOR REMOVE LAST ROW	
        $(document).ready(function(e){
            $('#tblSample_pic').on('click', '.removeRow_pic', function(){ 
            //alert($("#tblSample_fab tr").length);
                if($("#tblSample_pic tr").length <= 2){
                    alert("Can't Remove Last Row !");
                }else{
                    $(this).closest('tr').remove();
                    totalPic();
                }
            });
        });	
        
    function rowCal_pic(ownClass){
                if(isNaN(ownClass.closest('tr').find('.picCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.picCls').val("");}
                var total_trim = isNaN(ownClass.closest('tr').find('.picCls').val()) || $.trim(ownClass.closest('tr').find('.picCls').val()) == '' ? 0 : ownClass.closest('tr').find('.picCls').val() ;
                ownClass.closest('tr').find('.picCls').val(parseFloat(((parseFloat(total_trim)).toFixed(2))));
                totalPic();
        }
        
        function totalPic(){
            var value = 0.00;
            $('.picCls').each(function(index){
                var val = parseFloat($(this).val());
                value += val;
            });
            //$('.totalAmountCls').val(value); 
            $('.totalPic').val(parseFloat(value).toFixed(2));
        }
        
            // DYNAMIC TABLE CM -------------------------------------------------------------------------------
        function addRowToTable_cm()
        {
            var tbl = document.getElementById('tblSample_cm');
            var lastRow1 = tbl.rows.length;
            
            var lastRow =  parseInt(document.getElementById("rowCount_cm").value)+1; 				
            document.getElementById("rowCount_cm").value=lastRow;
            
            var iteration =lastRow;
            var row = tbl.insertRow(lastRow1);
    
            var cellLeft = row.insertCell(0);
            var textNode = document.createTextNode(iteration);
            cellLeft.appendChild(textNode);
            var cellRight = row.insertCell(1);
            cellRight.innerHTML = makeElement_cm("mrd_item_name_cm",(lastRow-1),"mrd_item_name_cm");
            cellRight = row.insertCell(2);
            cellRight.innerHTML = makeElement_cm("mpc_smv",(lastRow-1),"mpc_smv");
            cellRight = row.insertCell(3);
            cellRight.innerHTML = makeElement_cm("mpc_eff",(lastRow-1),"mpc_eff");	
            cellRight = row.insertCell(4);
            cellRight.innerHTML = makeElement_cm("mpc_cpm",(lastRow-1),"mpc_cpm");
            cellRight = row.insertCell(5);
            cellRight.innerHTML = makeElement_cm("mpc_profit",(lastRow-1),"mpc_profit");
            cellRight = row.insertCell(6);
            cellRight.innerHTML = makeElement_cm("mpc_excess_acc",(lastRow-1),"mpc_excess_acc");
            cellRight = row.insertCell(7);
            cellRight.innerHTML = makeElement_cm("mpc_cm",(lastRow-1),"mpc_cm");
            cellRight = row.insertCell(8);
            cellRight.innerHTML=makeElement_cm("removeRow_cm",(lastRow-1),"removeRow_cm");
            rowCount_fab=rowCount_fab+1; 
        }
    
        var dynamicId =0,dynamicName=0;
        var fadeOutDiv = "fkl";
        var rowCount_cm = "";
        function makeElement_cm(inputType,dynamicId,dynamicName){
                var InputType = inputType; 
                var myHTML_cm    = "";
                switch(InputType) {
                    case 'mrd_item_name_cm':
                        myHTML_cm= "<select data-cls-drop-list='drop-width'  data-role='select'  name='mrd_item_name_cm[]' class='input-small input-small suppliername' data-filter-placeholder='Search Item...'>"+"<?= urldecode($appsDependent->dropdown('inv.mrd_item', 'MRD_ITEM_ID', 'MRD_ITEM_NAME', '0')) ?>"+"</select>"; 
                    break;	
                    case 'mpc_smv': 			  
                        myHTML_cm= "<input type='text' class='input-small smvCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_cm($(this));' onBlur='rowCal_cm($(this));'  autocomplete='off'>";                  
                    break;
                    case 'mpc_eff': 			  
                        myHTML_cm= "<input type='text' class='input-small effCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_cm($(this));' onBlur='rowCal_cm($(this));'  autocomplete='off'>";                  
                    break;
                    case 'mpc_cpm': 			  
                        myHTML_cm= "<input type='text' class='input-small cpmCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0.042' onKeyUp='rowCal_cm($(this));' onBlur='rowCal_cm($(this));'  autocomplete='off'>";                  
                    break;
                    case 'mpc_profit': 			  
                        myHTML_cm= "<input type='text' class='input-small profitCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_cm($(this));' onBlur='rowCal_cm($(this));'  autocomplete='off'>";                  
                    break;
                    case 'mpc_excess_acc': 			  
                        myHTML_cm= "<input type='text' class='input-small excessAccCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_cm($(this));' onBlur='rowCal_cm($(this));'  autocomplete='off'>";                  
                    break;
                    case 'mpc_cm': 			  
                        myHTML_cm= "<input type='text' class='input-small cmCls' name='"+dynamicName+"[]' id='"+dynamicName+dynamicId+"' value='0' onKeyUp='rowCal_cm($(this));' onBlur='rowCal_cm($(this));'  autocomplete='off'>";                  
                    break;
                    case 'removeRow_cm':
                        myHTML_cm="<label class='button small alert removeRow_cm' id='remove"+dynamicId+"' onClick='rowCal_cm($(this));' style='float: right; padding: 1px 6px 2px 6px'><b style='color: white' title='Remove!'>&#10006;</b></label>";
                    break;
                    }
            return myHTML_cm;
        }
    
    // LAST ROW REMOVE ANY ROW + VALIDATION (CTRL+A) FOR ADD ROW + (CTRL+Z) FOR REMOVE LAST ROW	
        $(document).ready(function(e){
            $('#tblSample_cm').on('click', '.removeRow_cm', function(){ 
            //alert($("#tblSample_fab tr").length);
                if($("#tblSample_cm tr").length <= 2){
                    alert("Can't Remove Last Row !");
                }else{
                    $(this).closest('tr').remove();
                    totalCmPrice();
                }
            });
        });	
           
    function rowCal_cm(ownClass){
                if(isNaN(ownClass.closest('tr').find('.smvCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.smvCls').val("");}
                var smv = isNaN(ownClass.closest('tr').find('.smvCls').val()) || $.trim(ownClass.closest('tr').find('.smvCls').val()) == '' ? 0 : ownClass.closest('tr').find('.smvCls').val() ;
                
                if(isNaN(ownClass.closest('tr').find('.effCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.effCls').val("");}
                var eff = isNaN(ownClass.closest('tr').find('.effCls').val()) || $.trim(ownClass.closest('tr').find('.effCls').val()) == '' ? 0 : ownClass.closest('tr').find('.effCls').val() ;
    
                if(isNaN(ownClass.closest('tr').find('.cpmCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.cpmCls').val("");}
                var cpm = isNaN(ownClass.closest('tr').find('.cpmCls').val()) || $.trim(ownClass.closest('tr').find('.cpmCls').val()) == '' ? 0 : ownClass.closest('tr').find('.cpmCls').val() ;
                
                if(isNaN(ownClass.closest('tr').find('.excessAccCls').val())){
                alert("Invalid input!");
                ownClass.closest('tr').find('.excessAccCls').val("");}
                var excess = isNaN(ownClass.closest('tr').find('.excessAccCls').val()) || $.trim(ownClass.closest('tr').find('.excessAccCls').val()) == '' ? 0 : ownClass.closest('tr').find('.excessAccCls').val() ;
    
                if(eff>0){
                    eff = parseFloat(eff)/100;
                }else{
                    eff=0;
                }
                
                //var sc1=parseFloat(parseFloat(smv)*12*parseFloat(cpm));
                //alert("smv * cpm * 12"+sc1/eff);
    
                ownClass.closest('tr').find('.cmCls').val(parseFloat(((parseFloat(smv)*12*parseFloat(cpm))/parseFloat(eff))));
        
        
            var cm = isNaN(ownClass.closest('tr').find('.cmCls').val()) || $.trim(ownClass.closest('tr').find('.cmCls').val()) == '' ? 0 : ownClass.closest('tr').find('.cmCls').val() ;
            var excessAccCls = isNaN(ownClass.closest('tr').find('.excessAccCls').val()) || $.trim(ownClass.closest('tr').find('.excessAccCls').val()) == '' ? 0 : ownClass.closest('tr').find('.excessAccCls').val() ;
            var profitCls = isNaN(ownClass.closest('tr').find('.profitCls').val()) || $.trim(ownClass.closest('tr').find('.profitCls').val()) == '' ? 0 : ownClass.closest('tr').find('.profitCls').val() ;
            var totalExcessCls = isNaN(ownClass.closest('tr').find('.totalExcessCls').val()) || $.trim(ownClass.closest('tr').find('.totalExcessCls').val()) == '' ? 0 : ownClass.closest('tr').find('.totalExcessCls').val() ;
        
                totalProfitPrice();
                totalExcessPrice();
                totalCmPrice();
        }
        
        function totalProfitPrice(){
            var value = 0.00;
            $('.profitCls').each(function(index){
                var val = parseFloat($(this).val());
                value += val;
            });
            var rowCount = $("#tblSample_cm tr").length-1; 
            value=value/rowCount;
            //$('.totalAmountCls').val(value); 
            $('.totalProfitCls').val(parseFloat(value));
        }
            function totalExcessPrice(){
            var value = 0.00;
            $('.excessAccCls').each(function(index){
                var val = parseFloat($(this).val());
                value += val;
            });
            var rowCount = $("#tblSample_cm tr").length-1; 
            value=value/rowCount;
            //$('.totalAmountCls').val(value); 
            $('.totalExcessCls').val(parseFloat(value));
        }
        
        function totalCmPrice(){
            var value = 0.00;
            $('.cmCls').each(function(index){
                var val = parseFloat($(this).val());
                value += val;
            });
            var rowCount = $("#tblSample_cm tr").length-1; 
            value=value;
            //$('.totalAmountCls').val(value); 
            $('.totalCmCls').val(parseFloat(value));
            $('.gtCmCls').val(parseFloat(value));
            getGrandTotal();
        }
        
        function getGrandTotal(){
            //alert("??");
            var gtFab=parseFloat(document.getElementById("mpm_total_fabric_price").value);
            var trimTotal=parseFloat(document.getElementById("mpt_total_trim_price").value);
            var gtTrimExcess=parseFloat(document.getElementById("mpc_total_exces").value);
            var gtTrim=parseFloat(+trimTotal + +gtTrimExcess);//alert(gtTrim);
            document.getElementById("mpm_total_trim_price").value=parseFloat(gtTrim);
            var gtOc=parseFloat(document.getElementById("mpm_total_other_price").value);
            document.getElementById("mpm_total_material_price").value=parseFloat(+gtFab + +gtTrim + +gtOc);
            var gtMate = parseFloat(document.getElementById("mpm_total_material_price").value);
            var gtCm=parseFloat(document.getElementById("mpm_total_cm_price").value);
            document.getElementById("mpm_total_cb_price").value=parseFloat(+gtCm + +gtMate);
            var gtCb=parseFloat(document.getElementById("mpm_total_cb_price").value);
            var profitSum=parseFloat((document.getElementById("mpc_total_profit").value)/100);
            document.getElementById("mpm_profit_price").value=parseFloat((+gtCb + +profitSum)*profitSum);
            //var gtPro=parseFloat(document.getElementById("mpc_total_profit").value).toFixed(2);
            var gtPro=document.getElementById("mpm_profit_price").value;
            document.getElementById("mpm_fob_price").value=parseFloat(+gtCb + +gtPro);
            var gtFob = parseFloat(document.getElementById("mpm_fob_price").value);
            var pkNo=parseFloat(document.getElementById("pack_number").value);
            document.getElementById("mpm_unit_price").value=parseFloat((gtFob/12)/pkNo);
            var gtUnit=parseFloat(document.getElementById("mpm_unit_price").value);
            var ordQty=parseFloat(document.getElementById("order_qty").value);
            document.getElementById("mpm_total_price").value=parseFloat(+gtUnit * +ordQty);
           // rowCal_fab();
    
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
                 
    // SHIPMENT TYPE WISE DESTINATION  -------------------------------------------------------------------------------
        
            function setDestination(myID,myVal){
                if(myVal === "BY AIR"){
                     document.getElementById("destination").value="DHAKA AIRPORT";
                }
                if(myVal === "BY SEA"){
                     document.getElementById("destination").value="CHITTAGONG PORT";
                }
                if(myVal === "BY ROAD"){
                     document.getElementById("destination").value="BENAPOLE PORT";
                }
            }
    
    // FORM VALIDATION  -------------------------------------------------------------------------------
        
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
        function qtySum(){
            var value = 0.00;
            $('.qtyCls').each(function(index){
                if(isNaN($(this).val())){
                    alert("Invalid input!");
                    value = 0 ;			
                }else{
                    var val = parseFloat($(this).val());
                    value += val;
                }									
            });
            $('.totalQtyCls').val(value);
        }
        
        function totalAmount(){
            
            var value = 0.00;
            $('.amountCls').each(function(index){
                var val = parseFloat($(this).val());
                value += val;
            });
            //$('.totalAmountCls').val(value); 
            $('.totalAmountCls').val(parseFloat(value).toFixed(2));
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
        /*var rowCount_fab=0, price_counter=0, amount_counter=0, */
        var x=0; 
        function postRequest(strURL,myID) {
            var xmlHttp;
            if (window.XMLHttpRequest) { // Mozilla, Safari, ...
                 var xmlHttp = new XMLHttpRequest();
            }else if (window.ActiveXObject) { // IE
                var xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlHttp.open('GET', strURL, true);
            xmlHttp.setRequestHeader('Content-Type', 'text/html');
            xmlHttp.onreadystatechange = function() {
                if (xmlHttp.readyState == 4) {
                   updatepage(xmlHttp.responseText,myID);
                }
            }
            xmlHttp.send(strURL);
        }
    
        function updatepage(str,myID){
            document.getElementById("suggestions").innerHTML =str;
            var bodyRect = document.body.getBoundingClientRect();
            var element  = document.getElementById(myID);
            var elemRect = element.getBoundingClientRect();
            offsettop    = Math.round(elemRect.top - bodyRect.top);
            offsetleft   = Math.round(elemRect.left - bodyRect.left);
            placeDiv(offsetleft,offsettop);
        }
        
       function getDO(myDO,myID){
           var url = "";
            if(myID.charAt(0) === "d"){
              url="getAjaxData.jsp?queryString="+myDO+"&myid="+myID; 
            }else{   
              var pos= myID.substring(4); 
              var DONO=document.getElementById('dono'+pos).value;
              url="getAjaxData2.jsp?queryString="+myDO+"&queryString2="+DONO+"&myid="+myID; 
            }
            postRequest(url,myID);
        }
    
        function getGP(myDO,myID){
            var url = "getAjaxData2.jsp?queryString="+myDO+"&myid="+myID;
            postRequest(url);
        }
    
        function fill(val1,val2) {
            document.getElementById(val2).value=val1;
            var d = document.getElementById('suggestions');
            d.style.display = "none";
        }
    
        function placeDiv(x_pos, y_pos) {
              var d = document.getElementById('suggestions');
              d.style.position = "absolute";
              d.style.left = x_pos+'px';
              d.style.top = y_pos+'px';
              d.style.display = "block";
        }
    
        function TTQty(val,ide) {
          
            var Pos = ide.substring(6);
            var y   = document.getElementById('amount'+Pos).value;	
            var y1  = document.getElementById('total_amount').value;
            var yy  =	(+y1 - +y);
            document.getElementById('total_amount').value = yy;
        }					
     
        //------- validation -------------------------
       
        function nounit(val1,val2){
            
            var pos = "";
            var x   = "";
            pos     = val2.substring(8);
            if(val2.charAt(7)==='1'){
                x="qty1";
            }else{
                x="qty2";
            }
            if(val1==="NONE"){
                document.getElementById(x+pos).value    = "0.0";
                document.getElementById(x+pos).readOnly = true;
            }else{
                document.getElementById(x+pos).value    = "";
                document.getElementById(x+pos).readOnly = false;
            }
        }
    </script> 
<script>
  

    function insertDropdown(id,table,id_field,name_field,sl_field){
        var name = $('#'+id).val();
        var table = table;
        var id_field = id_field;
        var name_field = name_field;
        var sl_field = sl_field;
            if(name == null || name==''){
                alert('Data Can\'t Submit Empty!');
            }else{
                $.ajax({
                        type: 'POST',
                        url: "costsheetSubmit.php?dropdown=insert",
                        data:{'name':name,'table':table,'id_field':id_field,'name_field':name_field,'sl_field':sl_field},
                        // data: {'name':name},
                        success: function(response){
                            alert ("Successfully Inserted");
                        }
                    });
                }
            }
</script>
<script>
    function fetchData(id,table,optionId,optionValue){
        // $(document).ready(function() {
            // Metro.init();
        $.ajax({
                // url: "costsheetSubmit.php?"+id+"=fetch",
                url: "costsheetSubmit.php?dropdown=fetch&table="+table+"&optionId="+optionId+"&optionValue="+optionValue,
                success: function(response){
                    $('#'+id).append(response);
                    // Metro.dialog.open('#accessories');
                }
            });
        // });
    }
    function getSuggestions(str) {
    if (str.length == 0) {
        document.getElementById("suggestions").innerHTML = "";
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var suggestions = JSON.parse(this.responseText);
                // var suggestions = this.responseText;
                var html = "";
                suggestions.forEach(function(suggestion) {
                    // html += "<ul><li>" + suggestion + "</ul></li>";
                    html += "<div>" + suggestion + "</div>";
                    // html += suggestion ;
                });
                document.getElementById("suggestions").innerHTML = html;
                // $('#suggestions').text=html;
            }
        };
        xmlhttp.open("GET", "costsheetSubmit.php?term=" + str, true);
        xmlhttp.send();
    }
}

function cmToTotalCm(){
        var cm = document.getElementsByClassName('cmCls')[0].value;
        var totalCm = document.getElementsByClassName('totalCmCls')[0].value=cm;
        document.getElementById('mpm_total_cm_price').value=totalCm;
        getGrandTotal();
        // alert(cm);
    }
</script>

<?php include_once('inc/footer.php'); ?>