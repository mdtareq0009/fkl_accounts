<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyNavigationPermission('subgroups')):
	$accessoriesModel = new accessoriescrud($db->con);
	$masterData = $accessoriesModel->getData(
		"SELECT 
		subgroups.nid, 
		subgroups.vname, 
		groups.vname AS groupname, 
		subgroups.vcreatedat, 
		subgroups.vcreateduser, 
		createdemp.vempname AS createduser, 
		subgroups.vlastupdatedat, 
		subgroups.vlastupdateduser, 
		updatedemp.vempname AS updateduser 
		FROM 
		ACCESSORIES_SUBGROUP subgroups 
		LEFT JOIN hrm_employee createdemp ON createdemp.vemployeeid = subgroups.vcreateduser 
		LEFT JOIN hrm_employee updatedemp ON updatedemp.vemployeeid = subgroups.vlastupdateduser 
		LEFT JOIN ACCESSORIES_GROUP groups ON groups.nid = subgroups.ngroupid 
		ORDER BY 
  		subgroups.NID DESC
	");
	$data = '{
	"header": [
	{
	"name": "subgroupsname",
	"title": "Sub Group Name",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "groupname",
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
	$data .= '"'.$datarow['GROUPNAME'].'",';
	$data .= '"'.$datarow['VCREATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
	$data .= '"'.$datarow['VLASTUPDATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VLASTUPDATEDUSER'].'\'>'.$datarow['UPDATEDUSER'].'</a>",';

	$data .= '"'.($auth->verifyUserPermission('subgroups', 2) == true ? '<a href=\'subgroups.php?page=edit&id='.$datarow['NID'].'\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to edit sub-group information.\' class=\'tool-button secondary fg-white\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('subgroups', 3) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to delete sub-group.\' class=\'tool-button alert\' onclick=\'deleteRow('.$datarow['NID'].', \"subgroup\", \"goods\", $(this))\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>' : '').'"';
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