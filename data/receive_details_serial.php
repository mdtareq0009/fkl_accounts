<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);
	 $clause = '';

    // For fetching category related product
	if(isset($_GET['receive_id']) && $_GET['receive_id'] !=''){
		$receiveid = $_GET['receive_id'];
		$clause .= " and options.N_RECEIVE_MASTER_ID = '$receiveid'";
	}
	if(isset($_GET['receive_details_id']) && $_GET['receive_details_id'] !=''){
		$receivedetailsid = $_GET['receive_details_id'];
		$clause .= " and options.N_RECEIVE_DETAILS_ID = '$receiveid'";
	}
	if(isset($_GET['product_id']) && $_GET['product_id'] !=''){
		$productid = $_GET['product_id'];
		$clause .= " and options.N_PRODUCT_ID = '$productid'";
	}

    $masterData = $accessoriesModel->getData("
        SELECT
            options.N_ID AS SR_DETAILS_ID,
            options.N_RECEIVE_MASTER_ID, 
            options.N_RECEIVE_DETAILS_ID, 
            options.V_SERIAL_NO, 
            options.N_PRODUCT_ID AS PRODUCT_ID, 
            options.V_RECEIVE_QUANTITY AS V_RECEIVE_QUANTITY,
            TO_CHAR(options.DT_CREATED_AT, 'DD-MON-YY, HH12:MI AM') AS DT_CREATED_AT,
            createdemp.VEMPNAME AS createduser,
            TO_CHAR(options.DT_UPDATED_AT, 'DD-MON-YY, HH12:MI AM') AS DT_UPDATED_AT,
            updatedemp.VEMPNAME AS updateduser
        FROM
            PRODUCT_SERIAL_DETAILS options
            LEFT JOIN RECEIVE_MASTER rm ON rm.N_ID = options.N_RECEIVE_MASTER_ID
            LEFT JOIN RECEIVE_DETAILS rd ON rd.N_ID = options.N_RECEIVE_DETAILS_ID
            LEFT JOIN PRODUCT p ON p.N_ID = options.N_PRODUCT_ID
            LEFT JOIN hrm_employee createdemp ON createdemp.VEMPLOYEEID = options.N_CREATED_BY
            LEFT JOIN hrm_employee updatedemp ON updatedemp.VEMPLOYEEID = options.N_UPDATED_BY
        WHERE
            options.C_STATUS = 'a'
            $clause
        ORDER BY
            options.N_ID DESC
    ");


    // if($masterData){
    //     foreach($masterData as $masterData){

    //     }
    // }


        echo json_encode($masterData,  JSON_UNESCAPED_SLASHES);
	else:
		$auth->redirect403();
	endif;
else:
	$auth->loginPageRedirect();
endif;
?>