<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);


	$clause = '';

	if(isset($_GET['department_id']) && $_GET['department_id'] !=''){
		$departmentid = $_GET['department_id'];
		$clause .= " and options.N_DEPARTMENT_ID = '$departmentid'";
	}
	
	$masterData = $accessoriesModel->getData("SELECT
				options.N_ID,
				options.V_EMPLOYEE_ID,
				options.V_EMPLOYEE_CODE,
				options.V_EMPLOYEE_NAME,
				options.V_MOBILE_NO,
				options.V_ADDRESS,
				options.V_PBIX_NO,
				options.DT_CREATED_AT,
				dep.N_ID AS DEPARTMENT_ID,
				dg.N_ID AS DESIGNATION_ID,
				dep.V_NAME AS DEPARTMENT_NAME,
				dg.V_NAME AS DESIGNATION_NAME,
				createdemp.VEMPNAME AS createduser,
				options.DT_UPDATED_AT,
				updatedemp.VEMPNAME AS updateduser
			FROM
				EMPLOYEE options
				LEFT JOIN DEPARTMENT  dep ON dep.N_ID = options.N_DEPARTMENT_ID
				LEFT JOIN DESIGNATION  dg ON dg.N_ID = options.N_DESIGNATION_ID
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