<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);


	$clause = '';

	if(  (isset($_GET['fromdate']) && $_GET['fromdate'] !='' ) && (isset($_GET['todate']) && $_GET['todate'] !='' )  ){
		$fromdate = $_GET['fromdate'];
		$todate = $_GET['todate'];
		$clause .= " and options.D_ASSIGN_DATE between '$fromdate' and '$todate'";
	}
	if(isset($_GET['id']) && $_GET['id'] !=''){
		$id = $_GET['id'];
		$clause .= " and options.N_ID = '$id'";
	}
	if(isset($_GET['department_id']) && $_GET['department_id'] !=''){
		$departmentid = $_GET['department_id'];
		$clause .= " and options.N_DEPARTMENT_ID = '$departmentid'";
	}
	if(isset($_GET['category_id']) && $_GET['category_id'] !=''){
		$categoryid = $_GET['category_id'];
		$clause .= " and ipl.N_IP_CATEGORY_ID = '$categoryid'";
	}
	if(isset($_GET['employee_id']) && $_GET['employee_id'] !=''){
		$employeeid = $_GET['employee_id'];
		$clause .= " and options.N_EMPLOYEE_ID = '$employeeid'";
	}
	if(isset($_GET['iplan_id']) && $_GET['iplan_id'] !=''){
		$iplanid = $_GET['iplan_id'];
		$clause .= " and options.N_IP_VLAN_ID = '$iplanid'";
	}
	if(isset($_GET['iplan_type_id']) && $_GET['iplan_type_id'] !=''){
		$iplantypeid = $_GET['iplan_type_id'];
		$clause .= " and options.N_IP_VLAN_TYPE_ID = '$iplantypeid'";
	}


	
	$masterData = $accessoriesModel->getData("SELECT
				options.N_ID,
				options.D_ASSIGN_DATE,
				options.V_IP_VLAN_ASSIGN_NO,
				options.V_CURRENT_ASSIGN_TYPE,
				options.V_NOTE,
				emp.N_ID AS EMPLOYEE_ID,
				emp.V_EMPLOYEE_NAME AS EMPLOYEE_NAME,
				dep.N_ID AS DEPARTMENT_ID,
				dep.V_NAME AS DEPARTMENT_NAME,
				ipl.N_ID AS IP_LAN_ID,
				ipl.V_NAME AS IP_LAN_NAME,
				iplt.N_ID AS IP_LAN_TYPE_ID,
				iplt.V_NAME AS IP_LAN_TYPE_NAME,
				iplc.N_ID AS IP_CATEGORY_ID,
				iplc.V_NAME AS IP_CATEGORY_NAME,
				options.DT_CREATED_AT,
				createdemp.VEMPNAME AS createduser,
				options.DT_UPDATED_AT,
				updatedemp.VEMPNAME AS updateduser
			FROM
				IT_ASSET_IP_VLAN_ASSIGN_MASTER options
				LEFT JOIN EMPLOYEE  emp ON emp.N_ID = options.N_EMPLOYEE_ID
				LEFT JOIN DEPARTMENT  dep ON dep.N_ID = options.N_DEPARTMENT_ID
				LEFT JOIN IT_ASSET_IP_VLAN_TYPE  iplt ON iplt.N_ID = options.N_IP_VLAN_TYPE_ID
				LEFT JOIN IT_ASSET_IP_VLAN  ipl ON ipl.N_ID = options.N_IP_VLAN_ID
				LEFT JOIN IT_ASSET_IP_VLAN_CATEGORY iplc ON iplc.N_ID = ipl.N_IP_CATEGORY_ID
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