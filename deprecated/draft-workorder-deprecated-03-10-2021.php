<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('draft workorder', 2)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeature();
		$filterWhere = $pageOpt->filterWhere();
		$pageOpt->filterWhereClose();
		$masterData = $accessoriesModel->getData("SELECT master.nid, master.vblockorderinfo, master.vponumber, master.vissue, REGEXP_REPLACE(LISTAGG(goods.vname, ', ') WITHIN GROUP (ORDER BY items.nid ASC), '([^,]+)(, \\1)+', '\\1') AS itemname, master.vordernumberorfklnumber, master.vtype, master.vcreateduser, employee.vempname AS createduser, master.vcreatedat, master.ncheckedstatus, master.vcheckeduser, checkedby.vempname AS checkeduser, REGEXP_REPLACE(LISTAGG(UPPER(orderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfostylename, REGEXP_REPLACE(LISTAGG(orderinfoks.nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfoksksid, orderinfo.vname AS orderinfobuyername, REGEXP_REPLACE(LISTAGG(UPPER(ksorderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  ksorderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS ksstylename, REGEXP_REPLACE(LISTAGG(ksorderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY ksorderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS ksordernumber, ksorderinfo.vname AS ksbuyername FROM accessories_workordermaster master LEFT JOIN accessories_workorderitems items ON items.nworkordermasterid = master.nid LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.vordernumber = master.vordernumberorfklnumber AND (TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.dshipdate, 'dd-mm-yy'))) OR TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.duserdate, 'dd-mm-yy')))) LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (TO_NUMBER(REGEXP_REPLACE (upper(vordernumberorfklnumber), 'BLOCK', 0))) LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode LEFT JOIN hrm_employee employee ON employee.vemployeeid = master.vcreateduser LEFT JOIN hrm_employee checkedby ON checkedby.vemployeeid = master.vcheckeduser LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid WHERE master.vstatus = 'draft' AND master.ndeletedstatus = 0 AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') $filterWhere GROUP BY master.nid, master.vblockorderinfo, master.vponumber, master.vissue, master.vordernumberorfklnumber, master.vtype, master.vcreateduser, employee.vempname, master.vcreatedat, master.ncheckedstatus, master.vcheckeduser, checkedby.vempname, orderinfo.vname, ksorderinfo.vname ORDER BY master.nid DESC");

		//to_date(master.vcreatedat, 'dd-mm-yy') >= trunc(SYSDATE, 'yyyy')  - interval '1' year AND to_date(master.vcreatedat, 'dd-mm-yy') < trunc(SYSDATE+30)
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
		"name": "publish",
		"title": "<input type=\'checkbox\' data-role=\'checkbox\' data-style=\'2\' onchange=\'allCheck(\"drafts-workorder-table\", $(this))\' data-cls-check=\'bd-white\' data-cls-checkbox=\'all-checked\' data-caption=\'\'>",
		"size": 18,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "actions",
		"title": "Actions",
		"sortable": false,
		"size": 190,
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

		$data .= '"<input type=\'checkbox\' data-role=\'checkbox\' value=\''.$datarow['NID'].'\' onchange=\'singleCheck(\"drafts-workorder-table\", $(this), event)\' data-style=\'2\' data-cls-check=\'bd-dark\' data-cls-checkbox=\'single-check\' data-caption=\'\'>",';

		$data .= '"'.($auth->verifyUserPermission('draft workorder', 3) == true ? '<a class=\'tool-button outline dark\' data-role=\'hint\' data-hint-position=\'top\' data-hint-text=\'Click to view details of work order <br>(<strong>W.O-'.$datarow['NID'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'workorder.php?page=details&id='.$datarow['NID'].'\'><span class=\'mif-eye\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> <a class=\'tool-button info\' data-role=\'hint\' data-hint-position=\'top\' data-hint-text=\'Print Draft Work Order <br>(<strong>W.0-'.$datarow['NID'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'reports/draft/workorder/'.$datarow['NID'].'\' target=\'_blank\'><span class=\'mif-printer\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').' <a class=\'tool-button dark \' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Copy work order <br>(<strong>W.O.-'.$datarow['NID'].'</strong>)\' onclick=\'workorderCopy(\"'.$datarow['VTYPE'].'\", '.$datarow['NID'].', \"'.$db->csrfToken().'\")\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'javascript:void(0)\'><span class=\'mif-copy\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> '.($auth->verifyUserPermission('draft workorder', 4) == true ? '<a href=\'workorder.php?page=edit&id='.$datarow['NID'].'\' data-role=\'hint\' data-hint-position=\'top\' data-hint-text=\'Click to edit work order <br>(<strong>W.O-'.$datarow['NID'].'</strong>)\' class=\'tool-button secondary fg-white\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('draft workorder', 5) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' onclick=\'deleteRow('.$datarow['NID'].', \"workorder\", \"workorder\", $(this))\' data-hint-text=\'Click to delete work order (<strong>W.O-'.$datarow['NID'].'</strong>)\' class=\'tool-button alert\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button></div>' : '').'"';
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