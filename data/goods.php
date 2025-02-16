<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('goods', 4)):
	$accessoriesModel = new accessoriescrud($db->con);
	$masterData = $accessoriesModel->getData("SELECT goods.nid, goods.vname, subgroup.vname AS subgroupname, goods.vparameters, groups.vname AS groupname, goods.vuseunit, unit.vnameshort, goods.vcreatedat, goods.vcreateduser, createdemp.vempname AS createduser, goods.vlastupdatedat, goods.vlastupdateduser, updatedemp.vempname AS updateduser FROM ACCESSORIES_GOODS goods LEFT JOIN hrm_employee createdemp ON createdemp.vemployeeid = goods.vcreateduser LEFT JOIN accessories_subgroup subgroup ON subgroup.nid = goods.nsubgroupid LEFT JOIN ACCESSORIES_GROUP groups ON groups.nid = subgroup.ngroupid LEFT JOIN accessories_quantityunit unit ON unit.nid = goods.nqtyunitid LEFT JOIN hrm_employee updatedemp ON updatedemp.vemployeeid = goods.vlastupdateduser ORDER BY goods.NID DESC");
	$data = '{
	"header": [
	{
	"name": "goodsname",
	"title": "Goods Name",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "goodsOptions",
	"title": "Options",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "goodsunit",
	"title": "Goods MOU",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "workingunit",
	"title": "Working Unit",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "subgroup",
	"title": "Sub Group",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "group",
	"title": "Group",
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
	$parameters = '';
	$explodeParameters = explode(',', $datarow['VPARAMETERS']);
	foreach ($explodeParameters as $key => $value):
		$parameters .= '<span class=\"tally fg-white m-1\" style=\"line-height: 18px; background: #006d77;\">'.trim($value).'</span>';
	endforeach;

	$data .= '[';
	$data .= '"<strong>'.$datarow['VNAME'].'</strong>",';
	$data .= '"'.$parameters.'",';
	$data .= '"'.$datarow['VNAMESHORT'].'",';
	$data .= '"'.$datarow['VUSEUNIT'].'",';
	$data .= '"'.$datarow['SUBGROUPNAME'].'",';
	$data .= '"'.$datarow['GROUPNAME'].'",';
	$data .= '"'.$datarow['VCREATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
	$data .= '"'.$datarow['VLASTUPDATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VLASTUPDATEDUSER'].'\'>'.$datarow['UPDATEDUSER'].'</a>",';

	$data .= '"'.($auth->verifyUserPermission('goods', 2) == true ? '<a href=\'goods.php?page=edit&id='.$datarow['NID'].'\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to edit goods information.\' class=\'tool-button secondary fg-white\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('goods', 3) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' onclick=\'deleteRow('.$datarow['NID'].', \"goods\", \"goods\", $(this))\' data-hint-text=\'Click to delete goods.\' class=\'tool-button alert\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>' : '').'"';
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