<?php
ini_set('memory_limit', '-1');
// include_once('inc/head.php');
include_once('ini.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);
$appsDependent = new dependentdata($db->con);
$mpmno = 100 +$accessoriesModel->lastRow('inv.MRD_PRECOSTING_MASTER', 'MPM_SL');
$userid = $auth->loggedUserId();
// print_r($_POST['mpm_cdate']);
// echo "<pre>";
// print_r($_POST);
// echo "<pre>";
// print_r($_POST);
// exit;
date_default_timezone_set('Asia/Dhaka');
if(isset($_GET['costsheet']) && $_GET['costsheet']=='create'){
if(isset($_POST['myButton'])){
// ==============Static==============
$masterStatic['MPM_SL']                     =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_MASTER', 'MPM_SL');
$masterStatic['MPM_EUSER']	                =	$userid;
$masterStatic['MPM_EDATE']	                =	date("d:M:Y");
$masterStatic['MPM_ETIME']	                =	date("h:i:sa");
$masterStatic['MPM_NO']	                    =	$mpmno;
// $masterStatic['MPM_CDATE']	                =	date("d-M-Y");
$masterStatic['MPM_CDATE']	                =	$_POST['mpm_cdate'];
$masterStatic['MPM_BUYER_ID']	            =	$_POST['buyer'];
$masterStatic['MRD_BUYER_STYLE_ID']	        =	$_POST['stylename'];
$masterStatic['MRD_BUYER_SEASON_ID']	    =	$_POST['seasson'];
$masterStatic['MRD_BUYER_DEPT_ID']	        =	$_POST['department'];
$masterStatic['MPM_PACK_TYPE']	            =	$_POST['pack_type'];
$masterStatic['MPM_PACK_NUMBER']	        =	$_POST['pack_number'];
$masterStatic['MPM_ORDER_QTY']	            =	$_POST['order_qty'];
$masterStatic['MPF_TOTAL_FAB_PRICE']	    =	$_POST['mpf_total_fab_price'];
$masterStatic['MPT_TOTAL_TRIM_PRICE']	    =	$_POST['mpt_total_trim_price'];
$masterStatic['MPO_TOTAL_PRICE']	        =	$_POST['mpo_total_price'];
$masterStatic['MPC_TOTAL_PROFIT']	        =	$_POST['mpc_total_profit'];
$masterStatic['MPC_TOTAL_EXCES']	        =	$_POST['mpc_total_exces'];
$masterStatic['MPC_TOTAL_CM']	            =	$_POST['mpc_total_cm'];
$masterStatic['MPM_TOTAL_FABRIC_PRICE']	    =	$_POST['mpm_total_fabric_price'];
$masterStatic['MPM_TOTAL_OTHER_PRICE']	    =	$_POST['mpm_total_other_price'];
$masterStatic['MPM_TOTAL_MATERIAL_PRICE']	=	$_POST['mpm_total_material_price'];
$masterStatic['MPM_TOTAL_CM_PRICE']	        =	$_POST['mpm_total_cm_price'];
$masterStatic['MPM_TOTAL_CB_PRICE']	        =	$_POST['mpm_total_cb_price'];
$masterStatic['MPM_PROFIT_PRICE']	        =	$_POST['mpm_profit_price'];
$masterStatic['MPM_FOB_PRICE']	            =	$_POST['mpm_fob_price'];
$masterStatic['MPM_UNIT_PRICE']	            =	$_POST['mpm_unit_price'];
$masterStatic['MPM_TOTAL_PRICE']	        =	$_POST['mpm_total_price'];
$masterStatic['MPM_REMARKS']	            =	$_POST['mpm_remarks'];
$masterStatic['MPM_TOTAL_TRIM_PRICE']	    =	$_POST['mpm_total_trim_price'];
$masterStatic['MPM_OFFER_PRICE']	        =	$_POST['mpm_offer_price'];
$masterStatic['MPM_PRICE_DEFF']	            =	$_POST['mpm_price_deff'];
$masterStatic['PUBLISHED_STATUS']	        =	$_POST['myButton'];

$accessoriesModel->insertData('INV.MRD_PRECOSTING_MASTER',$masterStatic);
// ==============for offer Price===========
$offer_price['MPOP_SL']	            =	$accessoriesModel->lastRow('inv.MRD_PRECOSTING_OFFER_PRICE', 'MPOP_SL');
$offer_price['MPM_NO']	            =	$mpmno;
$offer_price['OFFER_PRICE']	        =	$_POST['mpm_offer_price'];
$offer_price['CREATED_BY']	        =	$userid;
$offer_price['CREATED_AT']	        =	date('d-M-Y h:i:sa');
$accessoriesModel->insertData('INV.MRD_PRECOSTING_OFFER_PRICE',$offer_price);
// ============End Static============

// ============Fabric start============
foreach($_POST['mrd_item_name'] as $key=> $value){
    $fabTable['MPF_SL']              =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_FABRIC', 'MPF_SL');
    $fabTable['MPM_NO']              =   $mpmno;
    $fabTable['MRD_ITEM_ID']         =   $_POST['mrd_item_name'][$key];
    $fabTable['MRD_FAB_ID']          =   $_POST['mrd_fab_name'][$key];
    $fabTable['MRD_COLOR_ID']        =   $_POST['mrd_color_name'][$key];
    $fabTable['MRD_YARN_COUNT_ID']   =   $_POST['mrd_yarn_count_name'][$key];
    $fabTable['MPF_GSM']             =   $_POST['mpf_gsm_name'][$key];
    $fabTable['MPF_CADCON']          =   $_POST['mpf_cadcon'][$key];
    // $fabTable['MPF_RATIO']           =   $_POST['mpf_ratio'][$key];
    // $fabTable['MPF_GREIGE_FABRIC']   =   $_POST['mpf_greige_fabric'][$key];
    $fabTable['MPF_YARN_PRICE']      =   $_POST['mpf_yarn_price'][$key];
    $fabTable['MPF_KNIT_PRICE']      =   $_POST['mpf_knit_price'][$key];
    $fabTable['MPF_DYEING_PRICE']    =   $_POST['mpf_dyeing_price'][$key];
    $fabTable['MPF_FAB_COST']        =   $_POST['mpf_fab_cost'][$key];
    $fabTable['MPF_AOP_YD_PRICE']    =   $_POST['mpf_aop_yd_price'][$key];
    $fabTable['MPF_AOP_COST']        =   $_POST['mpf_aop_cost'][$key];
    $fabTable['MPF_FAB_PRICE']       =   $_POST['mpf_fab_price'][$key];
    
    // echo "<pre>";
    // print_r($fabTable);
    // exit;
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_FABRIC',$fabTable);
}
// ============Fabric end============

// ============Trim start============
foreach($_POST['mrd_trim_name'] as $key=> $value){
    $trimTable['MPT_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_TRIM', 'MPT_SL');
    $trimTable['MPM_NO']                    =   $mpmno;
    $trimTable['MRD_TRIM_ID']               =   $_POST['mrd_trim_name'][$key];
    $trimTable['MRD_TRIM_UNIT_ID']          =   $_POST['mrd_trim_unit_name'][$key];
    $trimTable['MPT_TRIM_PRICE']            =   $_POST['mpt_trim_price'][$key];
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_TRIM',$trimTable);
}
// ============Trim end============
// ============Oc start============
foreach($_POST['mrd_other_cost_name'] as $key=> $value){
    $ocTable['MPO_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_OTHER', 'MPO_SL');
    $ocTable['MPM_NO']                    =   $mpmno;
    $ocTable['MRD_OTHER_COST_ID']         =   $_POST['mrd_other_cost_name'][$key];
    $ocTable['MRD_TRIM_UNIT_ID']          =   $_POST['mrd_oc_unit_name'][$key];
    $ocTable['MPO_OTHER_PRICE']           =   $_POST['mpo_other_price'][$key];
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_OTHER',$ocTable);
}
// ============Oc end============

// ============cm start============
foreach($_POST['mrd_item_name_cm'] as $key=> $value){

    // echo "<pre>";
    // print_r($_POST['mrd_other_cost_name']);
    $cmTable['MPC_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_CM', 'MPC_SL');
    $cmTable['MPM_NO']                    =   $mpmno;
    $cmTable['MRD_ITEM_ID']               =   $_POST['mrd_item_name_cm'][$key];
    $cmTable['MPC_SMV']                   =   $_POST['mpc_smv'][$key];
    $cmTable['MPC_EFF']                   =   $_POST['mpc_eff'][$key];
    $cmTable['MPC_CPM']                   =   $_POST['mpc_cpm'][$key];
    $cmTable['MPC_PROFIT']                =   $_POST['mpc_profit'][$key];
    $cmTable['MPC_EXCESS_ACC']            =   $_POST['mpc_excess_acc'][$key];
    $cmTable['MPC_CM']                    =   $_POST['mpc_cm'][$key];
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_CM',$cmTable);
}
// ============cm end============
//=============================================

if(!empty($_FILES['mpp_pic']['tmp_name'])){
    foreach ($_FILES as $key => $value):
        $tempVar = explode('-', $key);
        $mpmno = $mpmno;
        $blobdata = file_get_contents($value['tmp_name']);
        $imageType = $value['type'];
        $itemId = $_POST['mrd_item_name_pic'];
        // print_r( $itemId);
        // exit;
        $id = $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_PIC', 'MPP_SL');
        $accessoriesModel->imageUploadCosting($id, $mpmno, $itemId,$blobdata,$imageType);
        // $accessoriesModel->imageUploadCosting($id, $blobdata, $imageType, $mpmno,$itemId);
    endforeach;
}
header('location:costsheetview-.php?success=true');
}
}
// ========================Start Edit==========================
elseif(isset($_GET['edit']) && $_GET['edit']=='update'){
if(isset($_POST['updateButton'])){
    // echo "<pre>";
    // print_r($_POST);
    // exit;
$masterId = $_POST['mpm_no'];
// ==============Static==============
// $masterStatic['MPM_SL']                     =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_MASTER', 'MPM_SL');
$masterStatic['UPDATED_BY']	                =	$userid;
$masterStatic['UPDATED_AT']	                =	date("d-M-Y h:i:s A");
// $masterStatic['MPM_ETIME']	                =	date("h:i:sa");
// $masterStatic['MPM_NO']	                    =	$mpmno;
// $masterStatic['MPM_CDATE']	                =	date("d-M-Y");
$masterStatic['MPM_CDATE']	                =	$_POST['mpm_cdate'];
$masterStatic['MPM_BUYER_ID']	            =	$_POST['buyer'];
$masterStatic['MRD_BUYER_STYLE_ID']	        =	htmlspecialchars($_POST['stylename']);
$masterStatic['MRD_BUYER_SEASON_ID']	    =	$_POST['seasson'];
$masterStatic['MRD_BUYER_DEPT_ID']	        =	$_POST['department'];
$masterStatic['MPM_PACK_TYPE']	            =	$_POST['pack_type'];
$masterStatic['MPM_PACK_NUMBER']	        =	$_POST['pack_number'];
$masterStatic['MPM_ORDER_QTY']	            =	$_POST['order_qty'];
$masterStatic['MPF_TOTAL_FAB_PRICE']	    =	$_POST['mpf_total_fab_price'];
$masterStatic['MPT_TOTAL_TRIM_PRICE']	    =	$_POST['mpt_total_trim_price'];
$masterStatic['MPO_TOTAL_PRICE']	        =	$_POST['mpo_total_price'];
$masterStatic['MPC_TOTAL_PROFIT']	        =	$_POST['mpc_total_profit'];
$masterStatic['MPC_TOTAL_EXCES']	        =	$_POST['mpc_total_exces'];
$masterStatic['MPC_TOTAL_CM']	            =	$_POST['mpc_total_cm'];
$masterStatic['MPM_TOTAL_FABRIC_PRICE']	    =	$_POST['mpm_total_fabric_price'];
$masterStatic['MPM_TOTAL_OTHER_PRICE']	    =	$_POST['mpm_total_other_price'];
$masterStatic['MPM_TOTAL_MATERIAL_PRICE']	=	$_POST['mpm_total_material_price'];
$masterStatic['MPM_TOTAL_CM_PRICE']	        =	$_POST['mpm_total_cm_price'];
$masterStatic['MPM_TOTAL_CB_PRICE']	        =	$_POST['mpm_total_cb_price'];
$masterStatic['MPM_PROFIT_PRICE']	        =	$_POST['mpm_profit_price'];
$masterStatic['MPM_FOB_PRICE']	            =	$_POST['mpm_fob_price'];
$masterStatic['MPM_UNIT_PRICE']	            =	$_POST['mpm_unit_price'];
$masterStatic['MPM_TOTAL_PRICE']	        =	$_POST['mpm_total_price'];
$masterStatic['MPM_REMARKS']	            =	htmlspecialchars($_POST['mpm_remarks']);
$masterStatic['MPM_TOTAL_TRIM_PRICE']	    =	$_POST['mpm_total_trim_price'];
$masterStatic['MPM_OFFER_PRICE']	        =	$_POST['mpm_offer_price'];
$masterStatic['MPM_PRICE_DEFF']	            =	$_POST['mpm_price_deff'];
$masterStatic['PUBLISHED_STATUS']	        =	$_POST['updateButton'];

// $accessoriesModel->insertData('INV.MRD_PRECOSTING_MASTER',$masterStatic);
$accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_MASTER',$masterStatic,'MPM_NO='.$masterId);
// ===========for offer price=============
$offer_price['MPOP_SL']	            =	$accessoriesModel->lastRow('inv.MRD_PRECOSTING_OFFER_PRICE', 'MPOP_SL');
$offer_price['MPM_NO']	            =	$masterId;
$offer_price['OFFER_PRICE']	        =	$_POST['mpm_offer_price'];
$offer_price['CREATED_BY']	        =	$userid;
$offer_price['CREATED_AT']	        =	date('d-M-Y h:i:sa');
$accessoriesModel->insertData('INV.MRD_PRECOSTING_OFFER_PRICE',$offer_price);
// exit;
// ============End Static============

// ============Fabric start============
$accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_FABRIC','MPM_NO='.$masterId);
foreach($_POST['mrd_item_name'] as $key=> $value){
    $fabTable['MPF_SL']              =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_FABRIC', 'MPF_SL');
    $fabTable['MPM_NO']              =   $masterId;
    $fabTable['MRD_ITEM_ID']         =   $_POST['mrd_item_name'][$key];
    $fabTable['MRD_FAB_ID']          =   $_POST['mrd_fab_name'][$key];
    $fabTable['MRD_COLOR_ID']        =   $_POST['mrd_color_name'][$key];
    $fabTable['MRD_YARN_COUNT_ID']   =   $_POST['mrd_yarn_count_name'][$key];
    $fabTable['MPF_GSM']             =   $_POST['mpf_gsm_name'][$key];
    $fabTable['MPF_CADCON']          =   $_POST['mpf_cadcon'][$key];
    // $fabTable['MPF_RATIO']           =   $_POST['mpf_ratio'][$key];
    // $fabTable['MPF_GREIGE_FABRIC']   =   $_POST['mpf_greige_fabric'][$key];
    $fabTable['MPF_YARN_PRICE']      =   $_POST['mpf_yarn_price'][$key];
    $fabTable['MPF_KNIT_PRICE']      =   $_POST['mpf_knit_price'][$key];
    $fabTable['MPF_DYEING_PRICE']    =   $_POST['mpf_dyeing_price'][$key];
    $fabTable['MPF_FAB_COST']        =   $_POST['mpf_fab_cost'][$key];
    $fabTable['MPF_AOP_YD_PRICE']    =   $_POST['mpf_aop_yd_price'][$key];
    $fabTable['MPF_AOP_COST']        =   $_POST['mpf_aop_cost'][$key];
    $fabTable['MPF_FAB_PRICE']       =   $_POST['mpf_fab_price'][$key];
    
    // echo "<pre>";
    // print_r($fabTable);
    // exit;
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_FABRIC',$fabTable);
}
// exit;
// ============Fabric end============

// ============Trim start============
$accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_TRIM','MPM_NO='.$masterId);
foreach($_POST['mrd_trim_name'] as $key=> $value){
    $trimTable['MPT_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_TRIM', 'MPT_SL');
    $trimTable['MPM_NO']                    =   $masterId;
    $trimTable['MRD_TRIM_ID']               =   $_POST['mrd_trim_name'][$key];
    $trimTable['MRD_TRIM_UNIT_ID']          =   $_POST['mrd_trim_unit_name'][$key];
    $trimTable['MPT_TRIM_PRICE']            =   $_POST['mpt_trim_price'][$key];
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_TRIM',$trimTable);
}
// exit;
// ============Trim end============
// ============Oc start============
$accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_OTHER','MPM_NO='.$masterId);
foreach($_POST['mrd_other_cost_name'] as $key=> $value){
    $ocTable['MPO_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_OTHER', 'MPO_SL');
    $ocTable['MPM_NO']                    =   $masterId;
    $ocTable['MRD_OTHER_COST_ID']         =   $_POST['mrd_other_cost_name'][$key];
    $ocTable['MRD_TRIM_UNIT_ID']          =   $_POST['mrd_oc_unit_name'][$key];
    $ocTable['MPO_OTHER_PRICE']           =   $_POST['mpo_other_price'][$key];
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_OTHER',$ocTable);
}
// ============Oc end============

// ============cm start============
$accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_CM','MPM_NO='.$masterId);
foreach($_POST['mrd_item_name_cm'] as $key=> $value){
    // echo "<pre>";
    // print_r($_POST['mrd_other_cost_name']);
    $cmTable['MPC_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_CM', 'MPC_SL');
    $cmTable['MPM_NO']                    =   $masterId;
    $cmTable['MRD_ITEM_ID']               =   $_POST['mrd_item_name_cm'][$key];
    $cmTable['MPC_SMV']                   =   $_POST['mpc_smv'][$key];
    $cmTable['MPC_EFF']                   =   $_POST['mpc_eff'][$key];
    $cmTable['MPC_CPM']                   =   $_POST['mpc_cpm'][$key];
    $cmTable['MPC_PROFIT']                =   $_POST['mpc_profit'][$key];
    $cmTable['MPC_EXCESS_ACC']            =   $_POST['mpc_excess_acc'][$key];
    $cmTable['MPC_CM']                    =   $_POST['mpc_cm'][$key];
    $accessoriesModel->insertData('INV.MRD_PRECOSTING_CM',$cmTable);
}
// ============cm end============
//=============================================

if(!empty($_FILES['mpp_pic']['tmp_name'])){
    foreach ($_FILES as $key => $value):
        $tempVar = explode('-', $key);
        $mpmno = $masterId;
        $blobdata = file_get_contents($value['tmp_name']);
        $imageType = $value['type'];
        $itemId = $_POST['mrd_item_name_pic'];
        // print_r( $itemId);
        // exit;
        // $id = $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_PIC', 'MPP_SL');
        $check = $accessoriesModel->checkDataExistence('select * from inv.mrd_precosting_pic where mpm_no='.$mpmno);
        // print_r($check);
        // exit;
        if($check == 'not exist'){
        $id = $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_PIC', 'MPP_SL');
        $accessoriesModel->imageUploadCosting($id, $mpmno, $itemId,$blobdata,$imageType);
        }
        else{
        $accessoriesModel->imageUpdateCosting($mpmno, $itemId,$blobdata,$imageType);
        }
    endforeach;
}
elseif(!empty($_POST['mrd_item_name_pic'])){
    $item['MRD_ITEM_ID'] = $_POST['mrd_item_name_pic'];
    // print_r($_POST['mrd_item_name_pic']);
    // print_r( $item);
    // exit;
    $da = $accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_PIC',$item,'MPM_NO='.$masterId);
    // print_r($da);
    // exit;
}
header('location:costsheetview-.php?update=true');
}
// ============================================End Edit================================================
}
elseif(isset($_GET['deleteData'])){
    $deleteId = $_GET['deleteData'];
    $delete = $accessoriesModel->dataUpdate("INV.MRD_PRECOSTING_MASTER",array('DELETE_STATUS' => 1),"MPM_NO=$deleteId");
    if($delete)
    header('location:costsheetview-.php?delete=true');
}

// elseif(isset($_GET['accessories_name']) && $_GET['accessories_name'] == 'insert'){
//     $accessories = $_POST['accessories_name'];
//     $exist = $accessoriesModel->checkDataExistence("select MRD_TRIM_SL from inv.MRD_TRIM where trim(upper(MRD_TRIM_NAME)) = trim(upper('$accessories'))");
//     if($exist == 'not exist'){
//         $accessories_name['MRD_TRIM_NAME'] = $_POST['accessories_name'];
//         $accessories_name['MRD_TRIM_SL'] = $accessoriesModel->lastRowId('inv.MRD_TRIM', 'MRD_TRIM_SL');
//         $accessories_name['MRD_TRIM_ID'] = $accessories_name['MRD_TRIM_SL'];
//         $accessoriesModel->insertData('INV.MRD_TRIM',$accessories_name);
//         $response = array('success'=>true);
 
//     }else{
//         $response = array('success'=>false);
//     }
// }
elseif(isset($_GET['unit_name']) && $_GET['unit_name'] == 'insert'){
    $unit = $_POST['unit_name'];
    $exist = $accessoriesModel->checkDataExistence("select MRD_TRIM_UNIT_SL from inv.MRD_TRIM_UNIT where trim(upper(MRD_TRIM_UNIT_NAME)) = trim(upper('$unit'))");
    if($exist == 'not exist'){
        $unit_name['MRD_TRIM_UNIT_NAME'] = $_POST['unit_name'];
        $unit_name['MRD_TRIM_UNIT_SL'] = $accessoriesModel->lastRowId('inv.MRD_TRIM_UNIT', 'MRD_TRIM_UNIT_SL');
        $unit_name['MRD_TRIM_UNIT_ID'] = $unit_name['MRD_TRIM_UNIT_SL'];
        $accessoriesModel->insertData('INV.MRD_TRIM_UNIT',$unit_name);
        $response = array('success'=>true);
    }else{
        $response = array('success'=>false);
    }
}

elseif(isset($_GET['dropdown']) && $_GET['dropdown'] == 'fetch'){   
    $table = $_GET['table'];
    $optionId = $_GET['optionId'];
    $optionValue = $_GET['optionValue'];
    // $drop =  $appsDependent->dropdownCommon('inv.mrd_buyer', 'MRD_BUYER_ID', 'MRD_BUYER_NAME');
    $drop =  $appsDependent->dropdownCommon($table, $optionId, $optionValue);
        echo $drop;
}

elseif(isset($_GET['getDepartment']) && $_GET['getDepartment'] == 'fetch'){   

    if(!empty($_POST["buyer_id"])) 
    {
        $id=intval($_POST['buyer_id']);
        $pageType = $_GET['pageType'];
        // ERP.MER_DEPARTMENT', 'NDEPTID', 'VDEPTNAME', "NBUYERID","2"
        $query=oci_parse($db->con,"SELECT * FROM ERP.MER_DEPARTMENT WHERE NBUYERID=$id");
        oci_execute($query);
        if(isset($pageType) && $pageType == 'create'){
        ?>
        <option value="">Select Department</option>
        <?php
        }
        while($row=oci_fetch_array($query))
        {
            ?>
            <option value="<?php echo htmlentities($row['NDEPTID']); ?>"><?php echo htmlentities($row['VDEPTNAME']); ?></option>
            <?php
        }
    }	
}
elseif(isset($_GET['getSeasson']) && $_GET['getSeasson'] == 'fetch'){   

    if(!empty($_POST["buyer_id"])) 
    {
        $id=intval($_POST['buyer_id']);
        $pageType = $_GET['pageType'];
        // ERP.MER_DEPARTMENT', 'NDEPTID', 'VDEPTNAME', "NBUYERID","2"
        // erp.mer_seassonname', 'NSEASSONCODE', 'VSEASSONNAME','NBUYERID
        $sql=oci_parse($db->con,"SELECT * FROM ERP.mer_seassonname WHERE NBUYERID=$id");
        oci_execute($sql);
        if(isset($pageType) && $pageType == 'create'){
        ?>
        <option value="">Select Seasson</option>
        <?php
        }
        while($row=oci_fetch_array($sql))
        {
            ?>
            <option value="<?php echo htmlentities($row['NSEASSONCODE']); ?>"><?php echo htmlentities($row['VSEASSONNAME']); ?></option>
            <?php
        }
    }	
}

elseif(isset($_GET['dependenddropdown']) && $_GET['dependenddropdown'] == 'fetch'){   
    $table = $_GET['table'];
    $optionId = $_GET['optionId'];
    $optionValue = $_GET['optionValue'];
    $value = $_GET['value'];
    $buyer_id = $_GET['buyer_id'];
    // $drop =  $appsDependent->dropdownCommon('inv.mrd_buyer', 'MRD_BUYER_ID', 'MRD_BUYER_NAME');
    // $drop =  $appsDependent->dropdownCommon($table, $optionId, $optionValue);
    $drop =  $appsDependent->dropdownInput($table, $optionId, $optionValue,$buyer_id,$value);
        // echo $drop;
        echo json_encode($drop);
}

elseif(isset($_GET['dropdown']) && $_GET['dropdown'] == 'insert'){   
    $table = $_POST['table'];
    $name= trim($_POST['name']);
    $itemName = $_POST['name_field'];
    $itemId = $_POST['id_field'];
    $itemSl = $_POST['sl_field'];

    $exist = $accessoriesModel->checkDataExistence("select $itemSl from $table where trim(upper($itemName)) = trim(upper('$name'))");
    if($exist == 'not exist'){
        $item[$itemName] = $name;
        $item[$itemSl] = $accessoriesModel->lastRow($table,  $itemSl);
        $item[$itemId] = $item[$itemSl];
        $accessoriesModel->insertData($table,$item);
        $response = array('success'=>true);
    }
    $response = array('success'=>false);
}
elseif(isset($_GET['pageType']) && $_GET['pageType'] == 'publish'){   
    $mpm_no = $_POST['mpm_no'];
    $publish['PUBLISHED_STATUS'] = 1;
    $publish['PUBLISHED_BY'] = $userid;
   $response = $accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_MASTER',$publish,'MPM_NO='.$mpm_no);
   if($response)
   echo json_encode(array('success'=>true));
    else
  echo json_encode(array('success'=>false));
}
elseif(isset($_GET['pageType']) && $_GET['pageType'] == 'approve'){   
    $mpm_no = $_POST['mpm_no'];
    $approval['APPROVAL_STATUS'] = 1;
    $approval['APPROVAL_BY'] = $userid;
   $response = $accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_MASTER',$approval,'MPM_NO='.$mpm_no);
//    $response = $accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_MASTER',array('APPROVAL_STATUS'=>1),'MPM_NO='.$mpm_no);
   if($response)
   echo json_encode(array('success'=>true));
    else
  echo json_encode(array('success'=>false));
}
elseif(isset($_GET['pageType']) && $_GET['pageType'] == 'approve_manager'){   
    $mpm_no = $_POST['mpm_no'];
    $approve['APPROVE_MANAGER_STATUS'] = 1;
    $approve['APPROVE_MANAGER_BY'] = $userid;
   $response = $accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_MASTER',$approve,'MPM_NO='.$mpm_no);
   if($response)
   echo json_encode(array('success'=>true));
    else
  echo json_encode(array('success'=>false));
}
elseif(isset($_GET['dropdown']) && $_GET['dropdown'] == 'update'){   
    $table = $_POST['table'];
    $column = $_POST['name_field'];
    $name= trim($_POST['name']);
    $id_field = $_POST['id_field'];
    $id = $_POST['update_id'];

        $accessoriesModel->dataUpdate($table,array($column=>$name),$id_field=$id);
        $response = array('success'=>true);

    // $response = array('success'=>false);
}
elseif(isset($_GET['term'])){
$searchTerm = $_GET['term']; // Get user input
$sql = "SELECT MRD_BUYER_STYLE_NAME FROM INV.MRD_BUYER_STYLE WHERE UPPER(MRD_BUYER_STYLE_NAME) LIKE UPPER('%$searchTerm%')";
$result = oci_parse($db->con, $sql);
oci_execute($result);

$suggestions = array();
while ($row = oci_fetch_assoc($result)) {
    $suggestions[] = $row['MRD_BUYER_STYLE_NAME'];
}

// echo json_encode($suggestions); // Return suggestions as JSON
echo $suggestions;
}
//=============================================

// =================Revise===================
elseif(isset($_GET['pageType']) && $_GET['pageType']=='revise'){
    if(isset($_POST['reviseButton'])){
        // echo "<pre>";
        // print_r($_POST);
        // exit;
    $masterId = $_POST['mpm_no'];

    // print_r($lastrow);exit;
    
    // =====================================
    $copy = oci_parse($db->con,'INSERT INTO INV.MRD_PRECOSTING_MASTER_REV (MPMR_SL,MPM_SL,MPM_EUSER,MPM_EDATE,MPM_ETIME,MPM_NO,MPM_CDATE,MPM_BUYER_ID,MRD_BUYER_STYLE_ID,
    MRD_BUYER_SEASON_ID,MRD_BUYER_DEPT_ID,MPM_PACK_TYPE,MPM_PACK_NUMBER,MPM_ORDER_QTY,MPF_TOTAL_FAB_PRICE,MPT_TOTAL_TRIM_PRICE,MPO_TOTAL_PRICE,
    MPC_TOTAL_PROFIT,MPC_TOTAL_EXCES,MPC_TOTAL_CM,MPM_TOTAL_FABRIC_PRICE,MPM_TOTAL_TRIM_PRICE,MPM_TOTAL_OTHER_PRICE,MPM_TOTAL_MATERIAL_PRICE,
    MPM_TOTAL_CM_PRICE,MPM_TOTAL_CB_PRICE,MPM_PROFIT_PRICE,MPM_FOB_PRICE,MPM_UNIT_PRICE,MPM_OFFER_PRICE,MPM_PRICE_DEFF,MPM_TOTAL_PRICE,MPM_REMARKS,
    DELETE_STATUS,UPDATED_AT,UPDATED_BY,PUBLISHED_STATUS,APPROVAL_STATUS,REVISE_STATUS) 
    SELECT (SELECT NVL(MAX(MPMR_SL), 0) + 1 FROM INV.MRD_PRECOSTING_MASTER_REV),MPM_SL,MPM_EUSER,MPM_EDATE,MPM_ETIME,MPM_NO,MPM_CDATE,MPM_BUYER_ID,MRD_BUYER_STYLE_ID,
    MRD_BUYER_SEASON_ID,MRD_BUYER_DEPT_ID,MPM_PACK_TYPE,MPM_PACK_NUMBER,MPM_ORDER_QTY,MPF_TOTAL_FAB_PRICE,MPT_TOTAL_TRIM_PRICE,MPO_TOTAL_PRICE,
    MPC_TOTAL_PROFIT,MPC_TOTAL_EXCES,MPC_TOTAL_CM,MPM_TOTAL_FABRIC_PRICE,MPM_TOTAL_TRIM_PRICE,MPM_TOTAL_OTHER_PRICE,MPM_TOTAL_MATERIAL_PRICE,
    MPM_TOTAL_CM_PRICE,MPM_TOTAL_CB_PRICE,MPM_PROFIT_PRICE,MPM_FOB_PRICE,MPM_UNIT_PRICE,MPM_OFFER_PRICE,MPM_PRICE_DEFF,MPM_TOTAL_PRICE,MPM_REMARKS,
    DELETE_STATUS,UPDATED_AT,UPDATED_BY,PUBLISHED_STATUS,APPROVAL_STATUS,REVISE_STATUS
    FROM INV.MRD_PRECOSTING_MASTER WHERE mpm_no ='.$masterId);
    // echo $copy;
    oci_execute($copy);
    // =====================================
    // =====================================fABRIC
    $copy_fab = oci_parse($db->con,'INSERT INTO INV.MRD_PRECOSTING_FABRIC_REV (MPMR_SL,MPF_SL,MPM_NO,MRD_ITEM_ID,MRD_FAB_ID,MRD_COLOR_ID,MRD_YARN_COUNT_ID,MPF_GSM,MPF_CADCON,MPF_RATIO,
    MPF_GREIGE_FABRIC,MPF_YARN_PRICE,MPF_KNIT_PRICE,
    MPF_DYEING_PRICE,MPF_FAB_COST,MPF_AOP_YD_PRICE,MPF_AOP_COST,MPF_FAB_PRICE) 
    SELECT (SELECT NVL(MAX(MPMR_SL),0) FROM INV.MRD_PRECOSTING_MASTER_REV),MPF_SL,MPM_NO,MRD_ITEM_ID,MRD_FAB_ID,MRD_COLOR_ID,MRD_YARN_COUNT_ID,MPF_GSM,
    MPF_CADCON,MPF_RATIO,MPF_GREIGE_FABRIC,MPF_YARN_PRICE,MPF_KNIT_PRICE,
    MPF_DYEING_PRICE,MPF_FAB_COST,MPF_AOP_YD_PRICE,MPF_AOP_COST,MPF_FAB_PRICE
    FROM INV.MRD_PRECOSTING_FABRIC WHERE mpm_no ='.$masterId);
    // echo $copy;
    oci_execute($copy_fab);
    // =====================================
    // =====================================TRIM
    $copy_trim = oci_parse($db->con,'INSERT INTO INV.MRD_PRECOSTING_TRIM_REV (MPMR_SL,MPT_SL,MPM_NO,MRD_TRIM_ID,MRD_TRIM_UNIT_ID,MPT_TRIM_PRICE) 
    SELECT (SELECT NVL(MAX(MPMR_SL),0) FROM INV.MRD_PRECOSTING_MASTER_REV),MPT_SL,MPM_NO,MRD_TRIM_ID,MRD_TRIM_UNIT_ID,MPT_TRIM_PRICE
    FROM INV.MRD_PRECOSTING_TRIM WHERE mpm_no ='.$masterId);
    // echo $copy;
    oci_execute($copy_trim);
    // =====================================
   // =====================================other
   $copy_other = oci_parse($db->con,'INSERT INTO INV.MRD_PRECOSTING_OTHER_REV (MPMR_SL,MPO_SL,MPM_NO,MRD_OTHER_COST_ID,MRD_TRIM_UNIT_ID,MPO_OTHER_PRICE) 
   SELECT (SELECT NVL(MAX(MPMR_SL),0) FROM INV.MRD_PRECOSTING_MASTER_REV),MPO_SL,MPM_NO,MRD_OTHER_COST_ID,MRD_TRIM_UNIT_ID,MPO_OTHER_PRICE
   FROM INV.MRD_PRECOSTING_OTHER WHERE mpm_no ='.$masterId);
   // echo $copy;
   oci_execute($copy_other);
   // =====================================

    // =====================================cm
    $copy_cm = oci_parse($db->con,'INSERT INTO INV.MRD_PRECOSTING_CM_REV (MPMR_SL,MPC_SL,MPM_NO,MRD_ITEM_ID,MPC_SMV,MPC_EFF,MPC_CPM,MPC_PROFIT,MPC_EXCESS_ACC,MPC_CM) 
    SELECT (SELECT NVL(MAX(MPMR_SL),0) FROM INV.MRD_PRECOSTING_MASTER_REV),MPC_SL,MPM_NO,MRD_ITEM_ID,MPC_SMV,MPC_EFF,MPC_CPM,MPC_PROFIT,MPC_EXCESS_ACC,MPC_CM
    FROM INV.MRD_PRECOSTING_CM WHERE mpm_no ='.$masterId);
    // echo $copy;
    oci_execute($copy_cm);
    // =====================================

    


    //=====================================pic
    // EMPTY_BLOB(), '$type') RETURNING MPP_PICTURE INTO :image
    // $copy_pic = oci_parse($db->con,'INSERT INTO INV.MRD_PRECOSTING_PIC_REV (MPMR_SL,MPP_SL,MPM_NO,MRD_ITEM_ID,EMPTY_BLOB(),MPP_PICTRUE_TYPE) RETURNING MPP_PICTURE INTO :image 
    // SELECT (SELECT NVL(MAX(MPMR_SL), 0) FROM INV.MRD_PRECOSTING_MASTER_REV),MPP_SL,MPM_NO,MRD_ITEM_ID,MPP_PICTURE,MPP_PICTRUE_TYPE
    // FROM INV.MRD_PRECOSTING_PIC WHERE mpm_no ='.$masterId);
    // 	$blob = oci_new_descriptor($db->con, OCI_D_LOB);
    //     oci_bind_by_name($copy_pic, ":image", $blob, -1, OCI_B_BLOB);
    //     oci_execute($copy_pic, OCI_DEFAULT);

    $copy_pic = oci_parse($db->con,'INSERT INTO INV.MRD_PRECOSTING_PIC_REV (MPMR_SL,MPP_SL,MPM_NO,MRD_ITEM_ID,MPP_PICTURE,MPP_PICTRUE_TYPE) 
    SELECT (SELECT NVL(MAX(MPMR_SL), 0) FROM INV.MRD_PRECOSTING_MASTER_REV),MPP_SL,MPM_NO,MRD_ITEM_ID,MPP_PICTURE,MPP_PICTRUE_TYPE
    FROM INV.MRD_PRECOSTING_PIC WHERE mpm_no ='.$masterId);
    // echo $copy;
    $res = oci_execute($copy_pic);
    // print_r($res);
    // exit;

    //=====================================
// =================================History Data insertion complete=======================
    


        // ==============Static==============
    // $masterStatic['MPM_SL']                     =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_MASTER', 'MPM_SL');
    $masterStatic['UPDATED_BY']	                =	$userid;
    $masterStatic['UPDATED_AT']	                =	date("d-M-Y h:i:s A");
    // $masterStatic['MPM_ETIME']	                =	date("h:i:sa");
    // $masterStatic['MPM_NO']	                    =	$mpmno;
    // $masterStatic['MPM_CDATE']	                =	date("d-M-Y");
    $masterStatic['MPM_CDATE']	                =	$_POST['mpm_cdate'];
    $masterStatic['MPM_BUYER_ID']	            =	$_POST['buyer'];
    $masterStatic['MRD_BUYER_STYLE_ID']	        =	htmlspecialchars($_POST['stylename']);
    $masterStatic['MRD_BUYER_SEASON_ID']	    =	$_POST['seasson'];
    $masterStatic['MRD_BUYER_DEPT_ID']	        =	$_POST['department'];
    $masterStatic['MPM_PACK_TYPE']	            =	$_POST['pack_type'];
    $masterStatic['MPM_PACK_NUMBER']	        =	$_POST['pack_number'];
    $masterStatic['MPM_ORDER_QTY']	            =	$_POST['order_qty'];
    $masterStatic['MPF_TOTAL_FAB_PRICE']	    =	$_POST['mpf_total_fab_price'];
    $masterStatic['MPT_TOTAL_TRIM_PRICE']	    =	$_POST['mpt_total_trim_price'];
    $masterStatic['MPO_TOTAL_PRICE']	        =	$_POST['mpo_total_price'];
    $masterStatic['MPC_TOTAL_PROFIT']	        =	$_POST['mpc_total_profit'];
    $masterStatic['MPC_TOTAL_EXCES']	        =	$_POST['mpc_total_exces'];
    $masterStatic['MPC_TOTAL_CM']	            =	$_POST['mpc_total_cm'];
    $masterStatic['MPM_TOTAL_FABRIC_PRICE']	    =	$_POST['mpm_total_fabric_price'];
    $masterStatic['MPM_TOTAL_OTHER_PRICE']	    =	$_POST['mpm_total_other_price'];
    $masterStatic['MPM_TOTAL_MATERIAL_PRICE']	=	$_POST['mpm_total_material_price'];
    $masterStatic['MPM_TOTAL_CM_PRICE']	        =	$_POST['mpm_total_cm_price'];
    $masterStatic['MPM_TOTAL_CB_PRICE']	        =	$_POST['mpm_total_cb_price'];
    $masterStatic['MPM_PROFIT_PRICE']	        =	$_POST['mpm_profit_price'];
    $masterStatic['MPM_FOB_PRICE']	            =	$_POST['mpm_fob_price'];
    $masterStatic['MPM_UNIT_PRICE']	            =	$_POST['mpm_unit_price'];
    $masterStatic['MPM_TOTAL_PRICE']	        =	$_POST['mpm_total_price'];
    $masterStatic['MPM_REMARKS']	            =	htmlspecialchars($_POST['mpm_remarks']);
    $masterStatic['MPM_TOTAL_TRIM_PRICE']	    =	$_POST['mpm_total_trim_price'];
    $masterStatic['MPM_OFFER_PRICE']	        =	$_POST['mpm_offer_price'];
    $masterStatic['MPM_PRICE_DEFF']	            =	$_POST['mpm_price_deff'];
    // $masterStatic['PUBLISHED_STATUS']	        =	$_POST['reviseButton'];
    $masterStatic['REVISE_STATUS']	            =	$_POST['reviseButton'];
    $masterStatic['APPROVAL_STATUS']	            =	0;
    // print_r($masterStatic);
    // exit;

    $accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_MASTER',$masterStatic,'MPM_NO='.$masterId);
    // ============Fabric start============
    $accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_FABRIC','MPM_NO='.$masterId);
    foreach($_POST['mrd_item_name'] as $key=> $value){
        $fabTable['MPF_SL']              =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_FABRIC', 'MPF_SL');
        $fabTable['MPM_NO']              =   $masterId;
        $fabTable['MRD_ITEM_ID']         =   $_POST['mrd_item_name'][$key];
        $fabTable['MRD_FAB_ID']          =   $_POST['mrd_fab_name'][$key];
        $fabTable['MRD_COLOR_ID']        =   $_POST['mrd_color_name'][$key];
        $fabTable['MRD_YARN_COUNT_ID']   =   $_POST['mrd_yarn_count_name'][$key];
        $fabTable['MPF_GSM']             =   $_POST['mpf_gsm_name'][$key];
        $fabTable['MPF_CADCON']          =   $_POST['mpf_cadcon'][$key];
        // $fabTable['MPF_RATIO']           =   $_POST['mpf_ratio'][$key];
        // $fabTable['MPF_GREIGE_FABRIC']   =   $_POST['mpf_greige_fabric'][$key];
        $fabTable['MPF_YARN_PRICE']      =   $_POST['mpf_yarn_price'][$key];
        $fabTable['MPF_KNIT_PRICE']      =   $_POST['mpf_knit_price'][$key];
        $fabTable['MPF_DYEING_PRICE']    =   $_POST['mpf_dyeing_price'][$key];
        $fabTable['MPF_FAB_COST']        =   $_POST['mpf_fab_cost'][$key];
        $fabTable['MPF_AOP_YD_PRICE']    =   $_POST['mpf_aop_yd_price'][$key];
        $fabTable['MPF_AOP_COST']        =   $_POST['mpf_aop_cost'][$key];
        $fabTable['MPF_FAB_PRICE']       =   $_POST['mpf_fab_price'][$key];

        // echo "<pre>";
        // print_r($fabTable);
        // exit;
        $accessoriesModel->insertData('INV.MRD_PRECOSTING_FABRIC',$fabTable);
    }
    // exit;
    // ============Fabric end============
    
    // ============Trim start============
    $accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_TRIM','MPM_NO='.$masterId);
    foreach($_POST['mrd_trim_name'] as $key=> $value){
        $trimTable['MPT_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_TRIM', 'MPT_SL');
        $trimTable['MPM_NO']                    =   $masterId;
        $trimTable['MRD_TRIM_ID']               =   $_POST['mrd_trim_name'][$key];
        $trimTable['MRD_TRIM_UNIT_ID']          =   $_POST['mrd_trim_unit_name'][$key];
        $trimTable['MPT_TRIM_PRICE']            =   $_POST['mpt_trim_price'][$key];
        $accessoriesModel->insertData('INV.MRD_PRECOSTING_TRIM',$trimTable);
    }
    // exit;
    // ============Trim end============
    // ============Oc start============
    $accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_OTHER','MPM_NO='.$masterId);
    foreach($_POST['mrd_other_cost_name'] as $key=> $value){
        $ocTable['MPO_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_OTHER', 'MPO_SL');
        $ocTable['MPM_NO']                    =   $masterId;
        $ocTable['MRD_OTHER_COST_ID']         =   $_POST['mrd_other_cost_name'][$key];
        $ocTable['MRD_TRIM_UNIT_ID']          =   $_POST['mrd_oc_unit_name'][$key];
        $ocTable['MPO_OTHER_PRICE']           =   $_POST['mpo_other_price'][$key];
        $accessoriesModel->insertData('INV.MRD_PRECOSTING_OTHER',$ocTable);
    }
    // ============Oc end============
    
    // ============cm start============
    $accessoriesModel->deleteSingleRow('INV.MRD_PRECOSTING_CM','MPM_NO='.$masterId);
    foreach($_POST['mrd_item_name_cm'] as $key=> $value){
        // echo "<pre>";
        // print_r($_POST['mrd_other_cost_name']);
        $cmTable['MPC_SL']                    =   $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_CM', 'MPC_SL');
        $cmTable['MPM_NO']                    =   $masterId;
        $cmTable['MRD_ITEM_ID']               =   $_POST['mrd_item_name_cm'][$key];
        $cmTable['MPC_SMV']                   =   $_POST['mpc_smv'][$key];
        $cmTable['MPC_EFF']                   =   $_POST['mpc_eff'][$key];
        $cmTable['MPC_CPM']                   =   $_POST['mpc_cpm'][$key];
        $cmTable['MPC_PROFIT']                =   $_POST['mpc_profit'][$key];
        $cmTable['MPC_EXCESS_ACC']            =   $_POST['mpc_excess_acc'][$key];
        $cmTable['MPC_CM']                    =   $_POST['mpc_cm'][$key];
        $accessoriesModel->insertData('INV.MRD_PRECOSTING_CM',$cmTable);
    }
    // ============cm end============
    //=============================================
    
    if(!empty($_FILES['mpp_pic']['tmp_name'])){
        foreach ($_FILES as $key => $value):
            $tempVar = explode('-', $key);
            $mpmno = $masterId;
            $blobdata = file_get_contents($value['tmp_name']);
            $imageType = $value['type'];
            $itemId = $_POST['mrd_item_name_pic'];
            $check = $accessoriesModel->checkDataExistence('select * from inv.mrd_precosting_pic where mpm_no='.$mpmno);

            if($check == 'not exist'){
            $id = $accessoriesModel->lastRowId('inv.MRD_PRECOSTING_PIC', 'MPP_SL');
            $accessoriesModel->imageUploadCosting($id, $mpmno, $itemId,$blobdata,$imageType);
            }
            else{
            $accessoriesModel->imageUpdateCosting($mpmno, $itemId,$blobdata,$imageType);
            }
        endforeach;
    }
    elseif(!empty($_POST['mrd_item_name_pic'])){
        $item['MRD_ITEM_ID'] = $_POST['mrd_item_name_pic'];

        $da = $accessoriesModel->dataUpdate('INV.MRD_PRECOSTING_PIC',$item,'MPM_NO='.$masterId);
    }
    header('location:costsheetview-.php?update=true');
    }
    }

// =================Revise===================

?>