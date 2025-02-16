<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('mou', 4)):
	$accessoriesModel = new accessoriescrud($db->con);
	$masterData = $accessoriesModel->getData("SELECT 
	unit.nid, 
	unit.vname, 
	unit.vnameshort, 
	unit.vcreatedat, 
	unit.vcreateduser, 
	createdemp.vempname AS createduser, 
	unit.vlastupdatedat, unit.vlastupdateduser, 
	updatedemp.vempname AS updateduser 
	FROM ACCESSORIES_QUANTITYUNIT unit 
	LEFT JOIN hrm_employee createdemp ON createdemp.vemployeeid = unit.vcreateduser 
	LEFT JOIN hrm_employee updatedemp ON updatedemp.vemployeeid = unit.vlastupdateduser 
	ORDER BY unit.NID DESC");
	$data = '{
	"header": [
	{
	"name": "ufname",
	"title": "Unit Full Name",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "usname",
	"title": "Unit Short Name",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "createdat",
	"title": "Created At",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "createduser",
	"title": "Created By",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "updatedat",
	"title": "Updated At",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "updateduser",
	"title": "Updated By",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "actions",
	"title": "Actions",
	"sortable": false,
	"size": 85,
	"clsColumn": "bg-white text-center custom-action",
	"cls": "text-center"
	}';
	$data .='], "data":[';
	$first = true;

	if(is_array($masterData)):
	foreach ($masterData as $key => $datarow):
		if($first):
			$first = false;
		else:
			$data .=',';
		endif;

	$data .= '[';
	$data .= '"<strong>'.$datarow['VNAME'].'</strong>",';
	$data .= '"'.$datarow['VNAMESHORT'].'",';
	$data .= '"'.$datarow['VCREATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
	$data .= '"'.$datarow['VLASTUPDATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VLASTUPDATEDUSER'].'\'>'.$datarow['UPDATEDUSER'].'</a>",';

	$data .= '"'.($auth->verifyUserPermission('mou', 2) == true ? '<a href=\'materials-unit.php?page=edit&id='.$datarow['NID'].'\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to edit MOU.\' class=\'tool-button secondary fg-white\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('mou', 3) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to delete MOU.\' onclick=\'deleteRow('.$datarow['NID'].', \"unit\", \"goods\", $(this))\' class=\'tool-button alert\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>' : '').'"';
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