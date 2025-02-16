<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('suppliers', 4)):
	$accessoriesModel = new accessoriescrud($db->con);
	$masterData = $accessoriesModel->getData("SELECT 
		supplier.nid, 
		supplier.vname, 
		nvl(supplier.vemail, '-') AS vemail, 
		nvl(supplier.vcontact, '-') AS vcontact, 
		nvl(supplier.vaddress, '-') AS vaddress, 
		supplier.vcountry, 
		supplier.vcreatedat, 
		supplier.vcreateduser, 
		'DEMO' AS createduser, 
		supplier.vlastupdatedat, 
		supplier.vlastupdateduser, 
		'DEMO' AS updateduser 
	FROM ACCESSORIES_SUPPLIERS supplier 
	ORDER BY NID DESC");
	$data = '{
	"header": [
	{
	"name": "suppliername",
	"title": "Supplier Name",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "email",
	"title": "Email",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "contact",
	"title": "Contact Number",
	"sortable": true,
	"clsColumn": "text-center",
	"cls": "text-center"
	},
	{
	"name": "address",
	"title": "Address",
	"sortable": true,
	"clsColumn": "text-left",
	"cls": "text-center"
	},
	{
	"name": "country",
	"title": "Country",
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
	$data .= '"'.$datarow['VEMAIL'].'",';
	$data .= '"'.$datarow['VCONTACT'].'",';
	$data .= '"'.$datarow['VADDRESS'].'",';
	$data .= '"'.$datarow['VCOUNTRY'].'",';
	$data .= '"'.$datarow['VCREATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
	$data .= '"'.$datarow['VLASTUPDATEDAT'].'",';
	$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VLASTUPDATEDUSER'].'\'>'.$datarow['UPDATEDUSER'].'</a>",';

	$data .= '"'.($auth->verifyUserPermission('suppliers', 2) == true ? '<a href=\'suppliers.php?page=edit&id='.$datarow['NID'].'\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to edit supplier information.\' class=\'tool-button secondary fg-white\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('suppliers', 3) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to delete supplier.\' class=\'tool-button alert\' onclick=\'deleteRow('.$datarow['NID'].', \"supplier\", \"suppliers\", $(this))\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>' : '').'"';
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