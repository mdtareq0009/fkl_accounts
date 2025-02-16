<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('trash', 1)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();

		$masterData = $accessoriesModel->getData("SELECT 
		master.nid, master.vponumber, master.vblockorderinfo, master.nlastdeleteduser, master.vissue, 
		REGEXP_REPLACE(LISTAGG(goods.vname, ', ') WITHIN GROUP (ORDER BY items.nid ASC), '([^,]+)(, \\1)+', '\\1') AS itemname, 
		master.vordernumberorfklnumber, master.vtype, master.vcreateduser, 
		employee.vempname AS createduser, 
		master.vcreatedat, master.ncheckedstatus, 
		deletedby.vempname AS deleteduser, 
		REGEXP_REPLACE(LISTAGG(UPPER(orderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfostylename, 
		REGEXP_REPLACE(LISTAGG(orderinfoks.nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfoksksid, 
		orderinfo.vname AS orderinfobuyername, 
		REGEXP_REPLACE(LISTAGG(UPPER(ksorderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  ksorderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS ksstylename, 
		REGEXP_REPLACE(LISTAGG(ksorderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY ksorderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS ksordernumber, 
		ksorderinfo.vname AS ksbuyername FROM accessories_workordermaster master 
		LEFT JOIN accessories_workorderitems items ON items.nworkordermasterid = master.nid 
		LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.vordernumber = master.vordernumberorfklnumber 
		AND (TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.dshipdate, 'dd-mm-yy'))) 
		OR TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.duserdate, 'dd-mm-yy')))) 
		LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid 
		LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (TO_NUMBER(REGEXP_REPLACE (upper(vordernumberorfklnumber), 'BLOCK', 0))) 
		LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode 
		LEFT JOIN hrm_employee employee ON employee.vemployeeid = master.vcreateduser 
		LEFT JOIN hrm_employee deletedby ON deletedby.vemployeeid = master.nlastdeleteduser 
		LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid 
		WHERE master.ndeletedstatus = 1 
		AND (master.vcreateduser = '$userid' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') 
		GROUP BY master.nid, master.vponumber, master.vblockorderinfo, master.vissue, master.vordernumberorfklnumber, master.vtype, master.vcreateduser, employee.vempname, master.vcreatedat, master.ncheckedstatus, master.nlastdeleteduser, deletedby.vempname, orderinfo.vname, ksorderinfo.vname ORDER BY master.nid DESC");
		$data = '{
			"header": [
			{
			"name": "worderno",
			"title": "Workorder No.",
			"sortable": true,
			"clsColumn": "text-center pos-relative",
			"cls": "text-center"
			},
			{
			"name": "itemname",
			"title": "Item Name",
			"sortable": true,
			"clsColumn": "text-left",
			"cls": "text-center"
			},
			{
			"name": "fklno",
			"title": "FKL No.",
			"sortable": true,
			"clsColumn": "text-center",
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
			"name": "buyername",
			"title": "Buyer",
			"sortable": true,
			"clsColumn": "text-left",
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
			"name": "createduser",
			"title": "Created By",
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
			"name": "deleteduser",
			"title": "Deleted User",
			"sortable": true,
			"clsColumn": "text-center",
			"cls": "text-center"
			},
			{
			"name": "Restore/Delete",
			"title": "<input type=\'checkbox\' data-role=\'checkbox\' data-style=\'2\' onchange=\'allCheck(\"trash-workorder-table\", $(this))\' data-cls-check=\'bd-white\' data-cls-checkbox=\'all-checked\' data-caption=\'\'>",
			"clsColumn": "text-center",
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
				$orderNumber = '';
				$fklNo = '';
				$buyerName = '';
				$styleName = '';
				if($datarow['VTYPE'] == 'Order number'):
					if(strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'block'):
						$explodeBlockData = explode("##", $datarow['VBLOCKORDERINFO']);
						$orderNumber = $datarow['VORDERNUMBERORFKLNUMBER'];
						$fklNo = isset($explodeBlockData[0]) ? $explodeBlockData[0] : '' ;
						$buyerName = isset($explodeBlockData[2]) ? $explodeBlockData[2] : '' ;
						$styleName = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
					else:
						$orderNumber = $datarow['VORDERNUMBERORFKLNUMBER'];
						$fklNo = $datarow['ORDERINFOKSKSID'];
						$buyerName = $datarow['ORDERINFOBUYERNAME'];
						$styleName = $datarow['ORDERINFOSTYLENAME'];
					endif;
				elseif($datarow['VTYPE'] == 'FKL number'):
					if(strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'block'):
						$explodeBlockData = explode("##", $datarow['VBLOCKORDERINFO']);
						$fklNo = $datarow['VORDERNUMBERORFKLNUMBER'];
						$orderNumber = isset($explodeBlockData[0]) ? $explodeBlockData[0] : '' ;
						$buyerName = isset($explodeBlockData[2]) ? $explodeBlockData[2] : '' ;
						$styleName = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
					else:
						$fklNo = $datarow['VORDERNUMBERORFKLNUMBER'];
						$orderNumber = $datarow['KSORDERNUMBER'];
						$buyerName = $datarow['KSBUYERNAME'];
						$styleName = $datarow['KSSTYLENAME'];
					endif;
				endif;
				$data .= '[';
				$data .= '"<strong>W.O-'.$datarow['NID'].'</strong>",';
				$data .= '"'.$datarow['ITEMNAME'].'",';
				$data .= '"'.$fklNo.'",';
				$data .= '"'.$orderNumber.'",';
				$data .= '"'.$buyerName.'",';
				$data .= '"'.$styleName.'",';
				$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
				$data .= '"'.$datarow['VCREATEDAT'].'",';
				$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['NLASTDELETEDUSER'].'\'>'.$datarow['DELETEDUSER'].'</a>",';
				$data .= '"<input type=\'checkbox\' data-role=\'checkbox\' value=\''.$datarow['NID'].'\' onchange=\'singleCheck(\"trash-workorder-table\", $(this), event)\' data-style=\'2\' data-cls-check=\'bd-dark\' data-cls-checkbox=\'single-check\' data-caption=\'\'>"';
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