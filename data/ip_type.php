<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);



	$masterData = $accessoriesModel->getData("SELECT
				options.N_ID,
				options.V_NAME,
				options.DT_CREATED_AT,
				createdemp.VEMPNAME AS createduser,
				options.DT_UPDATED_AT,
				updatedemp.VEMPNAME AS updateduser
			FROM
				IT_ASSET_IP_VLAN_TYPE options
				LEFT JOIN hrm_employee createdemp ON createdemp.VEMPLOYEEID = options.N_CREATED_BY
				LEFT JOIN hrm_employee updatedemp ON updatedemp.VEMPLOYEEID = options.N_UPDATED_BY
				where options.C_STATUS = 'a'
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