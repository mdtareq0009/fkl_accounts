<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);


	$clause = '';

	if(isset($_GET['assign_status']) && $_GET['assign_status'] == true){
		
		$clause .= " and (options.C_IS_ACTIVE = 'n' or options.C_IS_ACTIVE = 'r')";
	}

	if(isset($_GET['category_id']) && $_GET['category_id'] !=''){
		$categoryid = $_GET['category_id'];
		$clause .= " and options.N_IP_CATEGORY_ID = '$categoryid'";
	}


	$masterData = $accessoriesModel->getData("SELECT
				options.N_ID,
				options.V_NAME,
				options.C_IS_ACTIVE,
				options.DT_CREATED_AT,
				c.N_ID AS CAT_ID,
				c.V_NAME AS CAT_NAME,
				createdemp.VEMPNAME AS createduser,
				options.DT_UPDATED_AT,
				updatedemp.VEMPNAME AS updateduser
			FROM
				IT_ASSET_IP_VLAN options
				LEFT JOIN IT_ASSET_IP_VLAN_CATEGORY  c ON c.N_ID = options.N_IP_CATEGORY_ID
				LEFT JOIN hrm_employee createdemp ON createdemp.VEMPLOYEEID = options.N_CREATED_BY
				LEFT JOIN hrm_employee updatedemp ON updatedemp.VEMPLOYEEID = options.N_UPDATED_BY
				where options.C_STATUS = 'a'
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