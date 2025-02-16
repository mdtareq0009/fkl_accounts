<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('user permission', 1)):
	$accessoriesModel = new accessoriescrud($db->con);
	// $masterData = $accessoriesModel->getData("SELECT 
	// 		permission.nid, 
	// 		permission.vfklid, 
	// 		permission.vrole, 
	// 		permission.nstatus, 
	// 		'TAREQ' AS manager, 
	// 		'DEMO' as vdepartmentname , 
	// 		'DEMO' as vdesignationname,
	// 		permission.vpurchasecodeprefix
	// 	FROM accessories_userspermission permission 
	// 	ORDER BY permission.nid DESC
	// ");
	$masterData = $accessoriesModel->getData("SELECT permission.nid, permission.vfklid, permission.vrole, 
	permission.nstatus, emp.vempname AS USERNAME, nvl(empm.vempname, '-') AS manager, 
	dept.vdepartmentname, desig.vdesignationname, permission.vpurchasecodeprefix 
	FROM accessories_userspermission permission 
	INNER JOIN ERP.hrm_employee emp ON emp.vemployeeid = permission.vfklid 
	LEFT JOIN ERP.hrm_employee empm ON empm.vemployeeid = permission.vfklid 
	INNER JOIN ERP.hrm_department dept ON dept.ndepartmentcode = emp.ndeptcode 
	INNER JOIN ERP.hrm_designation desig ON desig.ndesignationcode = emp.ndesignationcode 
	ORDER BY permission.nid 
	DESC");

	$data = '{
	"header": [
	{
	"name": "FKLID",
	"title": "FKLID",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "username",
	"title": "User Name",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "designation",
	"title": "Designation",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "department",
	"title": "Department",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "manager",
	"title": "Manager/Asst. Manager",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "prefix",
	"title": "P.O. Prefix",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "role",
	"title": "Role",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "status",
	"title": "Status",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "actions",
	"title": "Actions",
	"sortable": false,
	"size": 120,
	"clsColumn": "bg-grayWhite text-center",
	"cls": "text-center"
	}
	],

	"data":[';
	$first = true;
	if(is_array($masterData)):
	foreach ($masterData as $key => $datarow):
	if($first):
	$first = false;
	else:
	$data .=',';
	endif;
	if($datarow['NSTATUS'] == 1):
	$satatusbtn = '<button type=\'button\' class=\'tool-button  bg-darkRed bg-lightRed-hover bg-active-lightRed fg-white\' onclick=\'userActiveInactive('.$datarow['NID'].', \"user\", \"users\", \"inactive\")\' data-id=\''.$datarow['NID'].'\' style=\'width: 26px;height: 26px;line-height: 26px;\' data-value=\''.$datarow['NSTATUS'].'\'><span class=\'mif-not\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>';
	else:
	$satatusbtn = '<button type=\'button\' class=\'tool-button bg-darkGreen bg-lightGreen-hover bg-active-lightGreen fg-white\' onclick=\'userActiveInactive('.$datarow['NID'].', \"user\", \"users\", \"active\")\' data-id=\''.$datarow['NID'].'\' style=\'width: 26px;height: 26px;line-height: 26px;\' data-value=\''.$datarow['NSTATUS'].'\'><span class=\'mif-done\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>';
	endif;
	$data .= '[';
	$data .= '"'.$datarow['VFKLID'].'",';
	$data .= '"'.$datarow['USERNAME'].'",';
	$data .= '"'.$datarow['VDESIGNATIONNAME'].'",';
	$data .= '"'.$datarow['VDEPARTMENTNAME'].'",';
	$data .= '"'.$datarow['MANAGER'].'",';
	$data .= '"'.$datarow['VPURCHASECODEPREFIX'].'",';
	$data .= '"'.ucwords($datarow['VROLE']).'",';
	$data .= '"'.($datarow['NSTATUS'] == 1 ? '<span class=\'fg-darkGreen\'>Active</span>' : '<span class=\'fg-red\'>Inactive</span>' ).'",';
	$data .= '"'.$satatusbtn.'   <a href=\'user-permission.php?page=edit&id='.$datarow['NID'].'\'style=\'width: 26px;height: 26px;line-height: 26px;\' class=\'tool-button secondary fg-white\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a>  <button type=\'button\' onclick=\'deleteRow('.$datarow['NID'].', \"user\", \"users\", $(this))\' class=\'tool-button alert\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>"';

	$data .= ']';
	endforeach;
	endif;
	$data .= ']}';
	$data = json_decode($data, true);

	echo json_encode($data,  JSON_UNESCAPED_SLASHES);
	else:
        $auth->redirect403();
    endif;
else:
	$auth->loginPageRedirect(); 
endif;
?>