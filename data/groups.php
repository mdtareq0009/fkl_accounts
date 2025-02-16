<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('groups', 4)): 
	$accessoriesModel = new accessoriescrud($db->con);
	$masterData = $accessoriesModel->getData("SELECT groups.nid, groups.vname, groups.vcreatedat, groups.vcreateduser, createdemp.vempname AS createduser, groups.vlastupdatedat, groups.vlastupdateduser, updatedemp.vempname AS updateduser FROM ACCESSORIES_GROUP groups LEFT JOIN hrm_employee createdemp ON createdemp.vemployeeid = groups.vcreateduser LEFT JOIN hrm_employee updatedemp ON updatedemp.vemployeeid = groups.vlastupdateduser ORDER BY NID DESC");
	$data = '{
	"header": [
	{
	"name": "optionsname",
	"title": "Group Name",
	"sortable": true,
	"clsColumn": "text-left",
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
	$data .= '"'.$datarow['VCREATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
	$data .= '"'.$datarow['VLASTUPDATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VLASTUPDATEDUSER'].'\'>'.$datarow['UPDATEDUSER'].'</a>",';

	$data .= '"'.($auth->verifyUserPermission('groups', 2) == true ? '<a href=\'groups.php?page=edit&id='.$datarow['NID'].'\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to edit group.\' class=\'tool-button secondary fg-white\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('groups', 3) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' onclick=\'deleteRow('.$datarow['NID'].', \"group\", \"goods\", $(this))\' data-hint-text=\'Click to delete group.\' class=\'tool-button alert\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>' : '').'"';
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