<?php
ini_set('memory_limit', '-1');
// include_once('inc/head.php');
include_once('ini.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);
$appsDependent = new dependentdata($db->con);
// $mpmno = 100 +$accessoriesModel->lastRow('inv.MRD_PRECOSTING_MASTER', 'MPM_SL');
date_default_timezone_set('Asia/Dhaka');
$userid = $auth->loggedUserId();

function test($data){
      echo "<pre>";
    print_r($data);
    exit;
}

if($_GET['pageType'] && $_GET['pageType']=='fetch_empinformation'){
    $empid = $_POST['empid'];
    $gpno = 1000 + $accessoriesModel->lastRow('gatepass_masterdata', 'ID');
    $response = array();
    $empinfo = $accessoriesModel->getData("select empname,deptname,unit,designation from vw_all_employeeinformation@crypton where empid = '$empid' and status=1");
    $response = array('success'=>true,'empinfo'=>$empinfo,'gpno'=>$gpno);
    echo json_encode($response);
}
elseif($_GET['pageType'] && $_GET['pageType']=='gp_create'){
// test($_POST);

    if(isset($_POST['submit'])){
        // ==============Static==============
        $masterData['ID']               =   $accessoriesModel->lastRow('gatepass_masterdata', 'ID');
        $masterData['GP_NO']	        =	1000 + $masterData['ID'];
        // $masterData['GP_NO']	        =	$_POST['gpno'];
        $masterData['TO_RECIPIENT']	    =	$_POST['attention_to'];
        $masterData['FROM_SOURCE']	    =	$_POST['from'];
        $masterData['ADDRESS']	        =	$_POST['address'];
        $masterData['GP_DATE']	        =	$_POST['gp_date'];
        $masterData['CREATED_AT']	    =	date("d-M-Y h:i:sa");
        $masterData['CREATED_BY']	    =	$auth->loggedUserId();
        $masterData['USERID']	        =	$_POST['empid'];
        $masterData['PREPARED_BY']	    =	$userid;
        $masterData['DELETE_STATUS']	=	0;
        $masterData['TOTAL_QTY']	    =	$_POST['total_qty'];
        if(isset($_POST['returnable']) && $_POST['returnable']=='on')
        $masterData['RETURNABLE']	    =	$_POST['returnable'];
        $masterData['RETURN_DATE']	    =	$_POST['return_date'];
        $resultMaster = $accessoriesModel->insertData('gatepass_masterdata',$masterData);


        foreach($_POST['itemname'] as $key=> $value){
            // test($_POST);
            $itemData['ID']                    =   $accessoriesModel->lastRow('gatepass_itemdata', 'ID');
            $itemData['GP_MASTER_ID']          =   $masterData['ID'];
            $itemData['GP_NO']                 =   $masterData['GP_NO'];
            // $itemData['GP_NO']                 =   $_POST['gpno'];
            $itemData['ORDERINFORMATION']      =   $_POST['itemname'][$key];
            $itemData['DESCRIPTION']           =   $_POST['description'][$key];
            $itemData['QTY']                  =   $_POST['qty'][$key];
            $itemData['UNIT']                  =   $_POST['unit_name'][$key];
            $itemData['REMARKS']               =   $_POST['remarks'][$key];
           $resultItem = $accessoriesModel->insertData('gatepass_itemdata',$itemData);
        }
    }
    if($resultMaster){
        if($resultItem){
            header('location: gatepassView.php');
        }else{
            echo "<script>alert('Something Error! Item Not inserted!!')</script>";
        }
    }else{
        echo "<script>alert('Something Error! Master Data is problem!!')</script>";
    }
}
elseif($_GET['pageType'] && $_GET['pageType']=='gp_update'){
// test($_POST);

    if(isset($_POST['submit'])){
        $gp_no = $_POST['gp_no'];
        $gp_id = $_POST['gp_id'];
        // ==============Static==============
        // $masterData['ID']               =   $accessoriesModel->lastRow('gatepass_masterdata', 'ID');
        // $masterData['GP_NO']	        =	1000 + $masterData['ID'];
        // $masterData['GP_NO']	        =	$_POST['gpno'];
        $masterData['TO_RECIPIENT']	    =	$_POST['attention_to'];
        $masterData['FROM_SOURCE']	    =	$_POST['from'];
        $masterData['ADDRESS']	        =	$_POST['address'];
        $masterData['GP_DATE']	        =	$_POST['gp_date'];
        $masterData['UPDATED_AT']	    =	date("d-M-Y h:i:sa");
        // $masterData['CREATED_BY']	=	$auth->loggedUserId();
        $masterData['USERID']	        =	$_POST['empid'];
        $masterData['UPDATED_BY']	    =	$userid;
        $masterData['DELETE_STATUS']	=	0;
        $masterData['TOTAL_QTY']	    =	$_POST['total_qty'];
        if(isset($_POST['returnable']) && $_POST['returnable']=='on')
        $masterData['RETURNABLE']	    =	$_POST['returnable'];
        $masterData['RETURN_DATE']	    =	$_POST['return_date'];
        $resultMaster = $accessoriesModel->dataUpdate('gatepass_masterdata',$masterData,"ID=".$gp_id);

        $accessoriesModel->deleteSingleRow('gatepass_itemdata','GP_NO='.$gp_no);
        // $accessoriesModel->dataUpdate('gatepass_itemdata',array('DELETE_STATUS'=>'1'),'GP_NO='.$gp_no);
        foreach($_POST['itemname'] as $key=> $value){
            // test($_POST);
            $itemData['ID']                    =   $accessoriesModel->lastRow('gatepass_itemdata', 'ID');
            $itemData['GP_MASTER_ID']          =    $gp_id;
            $itemData['GP_NO']                 =   $gp_no;
            // $itemData['GP_NO']                 =   $_POST['gpno'];
            $itemData['ORDERINFORMATION']      =   $_POST['itemname'][$key];
            $itemData['DESCRIPTION']           =   $_POST['description'][$key];
            $itemData['QTY']                  =   $_POST['qty'][$key];
            $itemData['UNIT']                  =   $_POST['unit_name'][$key];
            $itemData['REMARKS']               =   $_POST['remarks'][$key];
           $resultItem = $accessoriesModel->insertData('gatepass_itemdata',$itemData);
        }
    }
    if($resultMaster){
        if($resultItem){
            header('location: gatepassView.php');
        }else{
            echo "<script>alert('Something Error! Item Not inserted!!')</script>";
        }
    }else{
        echo "<script>alert('Something Error! Master Data is problem!!')</script>";
    }
}

elseif($_GET['pageType'] && $_GET['pageType']=='gp_received'){
    // test($_POST);
    $gp_master_id = $_POST['gp_master_id'];
    $receivedData['RECEIVED_STATUS'] = 1;
    $receivedData['RECEIVED_BY'] = $userid;
   $response =  $accessoriesModel->dataUpdate('GATEPASS_MASTERDATA',$receivedData,'ID='.$gp_master_id);
    if($response)
        echo json_encode(array('success'=>true));
    else
        echo json_encode(array('success'=>false));
}
elseif($_GET['pageType'] && $_GET['pageType']=='gp_inventory'){
    // test($_POST);
    $gp_master_id = $_POST['gp_master_id'];
    $inventoryData['INVENTORY_APPROVED_STATUS'] = 1;
    $inventoryData['INVENTORY_APPROVED_BY'] = $userid;
   $response =  $accessoriesModel->dataUpdate('GATEPASS_MASTERDATA',$inventoryData,'ID='.$gp_master_id);
    if($response)
        echo json_encode(array('success'=>true));
    else
        echo json_encode(array('success'=>false));
}
elseif($_GET['pageType'] && $_GET['pageType']=='gp_headofdepartment'){
    // test($_POST);
    $gp_master_id = $_POST['gp_master_id'];
    $headOfDepartmentData['HEAD_OF_DEPARTMENT_STATUS'] = 1;
    $headOfDepartmentData['HEAD_OF_DEPARTMENT_BY'] = $userid;
   $response =  $accessoriesModel->dataUpdate('GATEPASS_MASTERDATA',$headOfDepartmentData,'ID='.$gp_master_id);
    if($response)
        echo json_encode(array('success'=>true));
    else
        echo json_encode(array('success'=>false));
}
elseif($_GET['pageType'] && $_GET['pageType']=='gp_approved'){
    // test($_POST);
    $gp_master_id = $_POST['gp_master_id'];
    $approvedData['APPROVED_STATUS'] = 1;
    $approvedData['APPROVED_BY'] = $userid;
   $response =  $accessoriesModel->dataUpdate('GATEPASS_MASTERDATA',$approvedData,'ID='.$gp_master_id);
    if($response)
        echo json_encode(array('success'=>true));
    else
        echo json_encode(array('success'=>false));
}
elseif($_GET['pageType'] && $_GET['pageType']=='gp_security_pass'){
    // test($_POST);
    $gp_master_id = $_POST['gp_master_id'];
    $securitypassData['SECURITY_PASS_STATUS'] = 1;
    $securitypassData['SECURITY_PASS_BY'] = $userid;
   $response =  $accessoriesModel->dataUpdate('GATEPASS_MASTERDATA',$securitypassData,'ID='.$gp_master_id);
    if($response)
        echo json_encode(array('success'=>true));
    else
        echo json_encode(array('success'=>false));
}


?>