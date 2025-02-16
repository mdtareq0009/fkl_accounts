<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('dashboard', 1)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$data = '{
		"header": [
		{
		"name": "purchasenumber",
		"title": "Purchase No.",
		"sortable": true,
		"clsColumn": "text-center pos-relative",
		"cls": "text-center"
		},
		{
		"name": "purchasedate",
		"title": "Purchase Order Date",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "ordermonth",
		"title": "Order Month",
		"sortable": true,
		"clsColumn": "text-left",
		"cls": "text-center"
		},
		{
		"name": "marchendisername",
		"title": "Merchandiser Name",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "buyername",
		"title": "Buyer",
		"sortable": true,
		"clsColumn": "text-left",
		"cls": "text-center"
		},
		{
		"name": "orderno",
		"title": "Order No.",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "stylename",
		"title": "Style / Ref.",
		"sortable": true,
		"clsColumn": "text-left",
		"cls": "text-center"
		},
		{
		"name": "orderqty",
		"title": "Order Qty",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "unit1",
		"title": "Unit",
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "departmentname",
		"title": "Department Name",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "nameofgoods",
		"title": "Name of Goods",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "totalrecieveqty",
		"title": "Total RCV Qty",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "unit2",
		"title": "Unit",
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "recievedue",
		"title": "RCV Due",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "DeliveryQty",
		"title": "Delivery Qty",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "closingbalance",
		"title": "Closing Balance",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "unit3",
		"title": "Unit",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "Remarks",
		"title": "Remarks",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		}';
		$data .='], "data":[';

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