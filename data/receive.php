<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);
	 $clause = '';

    // // For fetching category related product
	// if(isset($_GET['category_id']) && $_GET['category_id'] !=''){
	// 	$categoryid = $_GET['category_id'];
	// 	$clause .= " and options.N_CATEGORY_ID = '$categoryid'";
	// }

    $masterData = $accessoriesModel->getData("
        SELECT
            options.N_ID,
            options.V_REF_NO, 
            TO_CHAR(options.DT_CREATED_AT, 'DD-MON-YY, HH12:MI AM') AS DT_CREATED_AT,
            createdemp.VEMPNAME AS createduser,
            TO_CHAR(options.DT_UPDATED_AT, 'DD-MON-YY, HH12:MI AM') AS DT_UPDATED_AT,
            updatedemp.VEMPNAME AS updateduser
        FROM
            IT_ASSET_RECEIVE_MASTER options
            --LEFT JOIN CATEGORY c ON c.N_ID = options.N_CATEGORY_ID
            --LEFT JOIN BRAND b ON b.N_ID = options.N_BRAND_ID
            --LEFT JOIN UNIT u ON u.N_ID = options.N_UNIT_ID
            LEFT JOIN hrm_employee createdemp ON createdemp.VEMPLOYEEID = options.N_CREATED_BY
            LEFT JOIN hrm_employee updatedemp ON updatedemp.VEMPLOYEEID = options.N_UPDATED_BY
        WHERE
            options.C_STATUS = 'a'
            $clause
        ORDER BY
            options.N_ID DESC
    ");


        echo json_encode($masterData,  JSON_UNESCAPED_SLASHES);
	else:
		$auth->redirect403();
	endif;
else:
	$auth->loginPageRedirect();
endif;
?>