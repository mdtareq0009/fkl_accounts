<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('checked', 6)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeatureAll();
		$masterData = $accessoriesModel->getData("SELECT * from (SELECT master.nid, master.vponumber, master.vblockorderinfo, master.vissue, 
        REGEXP_REPLACE(LISTAGG(distinct goods.vname, ', ') WITHIN GROUP (ORDER BY items.nid ASC), '([^,]+)(, \\1)+', '\\1') AS itemname,
        master.vordernumberorfklnumber, master.vtype, master.vpublisheduser, employee.empname AS createduser, master.vapproveduser,
        approvedemployee.empname AS approveduser, master.vpublishedat, master.ncheckedstatus, master.vcheckeduser, checkedby.empname AS checkeduser,
        master.vmerchandiseruser,merchandiser.empname as merchandiseruser,master.vmerchandisermanageruser,merchandisermanager.empname as manageruser,
        master.vmerchandisergmuser,merchandisergm.empname as merchandisergmruser,master.vpurchasegmuser,purchasegm.empname as purchasegmuser,
        REGEXP_REPLACE(LISTAGG(UPPER(orderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') 
        AS orderinfostylename, 
        REGEXP_REPLACE(LISTAGG(orderinfoks.nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfoksksid,
        orderinfo.vname AS orderinfobuyername, 
        REGEXP_REPLACE(LISTAGG(UPPER(ksorderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  ksorderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS ksstylename,
        REGEXP_REPLACE(LISTAGG(ksorderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY ksorderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS ksordernumber,
        ksorderinfo.vname AS ksbuyername FROM accessories_workordermaster master 
        LEFT JOIN accessories_workorderitems items ON items.nworkordermasterid = master.nid
        LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.vordernumber = master.vordernumberorfklnumber 
        LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid
        AND (TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.dshipdate, 'dd-mm-yy'))) 
        OR TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.duserdate, 'dd-mm-yy')))) 
        LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (TO_NUMBER(REGEXP_REPLACE (upper(vordernumberorfklnumber), '[A-Z]', 0)))
        LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode 
        LEFT JOIN hr_employeeinfo@crypton employee ON employee.empid = master.vpublisheduser 
        LEFT JOIN hr_employeeinfo@crypton approvedemployee ON approvedemployee.empid = master.vapproveduser 
        LEFT JOIN hr_employeeinfo@crypton checkedby ON checkedby.empid = master.vcheckeduser 
        LEFT JOIN hr_employeeinfo@crypton merchandiser ON merchandiser.empid = master.vmerchandiseruser 
        LEFT JOIN hr_employeeinfo@crypton merchandisermanager ON merchandisermanager.empid = master.vmerchandisermanageruser 
        LEFT JOIN hr_employeeinfo@crypton merchandisergm ON merchandisergm.empid = master.vmerchandisergmuser 
        LEFT JOIN hr_employeeinfo@crypton purchasegm ON purchasegm.empid = master.vpurchasegmuser 
        LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid 
        WHERE master.vstatus = 'publish' AND master.ncheckedstatus = 1
        AND master.nmerchandiserstatus=1
        AND master.nmerchandisermanagerstatus=1
        AND master.nmerchandisergmstatus=1
        AND master.npurchasegmstatus=1
        and (master.nauditstatus=0 or master.nauditstatus is null)
        AND master.napprovedstatus = 0
        AND master.nacceptencestatus = 0
        AND master.ndeletedstatus = 0 AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) 
        OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin'
        OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin')
        GROUP BY master.nid, master.vponumber, master.vblockorderinfo, master.vissue, master.vordernumberorfklnumber, master.vtype, master.vpublisheduser, 
        employee.empname, master.vpublishedat, master.ncheckedstatus, master.vcheckeduser, checkedby.empname, master.vapproveduser,
        approvedemployee.empname, orderinfo.vname, ksorderinfo.vname,master.vmerchandiseruser,merchandiser.empname,
        master.vmerchandisermanageruser,merchandisermanager.empname,master.vmerchandisergmuser,merchandisergm.empname,
        master.vpurchasegmuser,purchasegm.empname
        ORDER BY master.nid DESC) where rownum <= 10000");
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
		"title": "Published By",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "createdat",
		"title": "Published At",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "checkedby",
		"title": "Checked By Purchase",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},';
		if($auth->verifyUserPermission('checked', 6)):
		$data .= '{
		"name": "merchandiser",
		"title": "<input type=\'checkbox\' data-role=\'checkbox\' data-style=\'2\' onchange=\'allCheck(\"audit-table\", $(this))\' data-cls-check=\'bd-white\' data-cls-checkbox=\'all-checked\' data-caption=\'\'>",
		"size": 18,
		"clsColumn": "text-center",
		"cls": "text-center"
		},';
		endif;
		$data .= '{
		"name": "actions",
		"title": "Actions",
		"sortable": false,
		"size": 115,
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
				if(strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'block' || strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'store'):
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
				if(strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'block' || strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'store'):
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
		$data .= '"<strong>'.$datarow['VPONUMBER'].'</strong><br><small class=\'tally '.($datarow['VISSUE'] > 1 ? 'warning' : 'info').'\' style=\'position: absolute;left: -10px;bottom: 10px;transform: rotate(-38deg);font-size: 11px;font-weight: bold; padding: 0 4px;\'>ISSUE-'.$datarow['VISSUE'].'</small>",';
		$data .= '"'.$datarow['ITEMNAME'].'",';
		$data .= '"'.$fklNo.'",';
		$data .= '"'.$orderNumber.'",';
		$data .= '"'.$buyerName.'",';
		$data .= '"'.$styleName.'",';
		$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VPUBLISHEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
		$data .= '"'.$datarow['VPUBLISHEDAT'].'",';
		$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VPURCHASEGMUSER'].'\'>'.$datarow['PURCHASEGMUSER'].'</a>",';
        if($auth->verifyUserPermission('checked', 6)):
		$data .= '"<input type=\'checkbox\' data-role=\'checkbox\' value=\''.$datarow['NID'].'\' onchange=\'singleCheck(\"audit-table\", $(this), event)\' data-style=\'2\' data-cls-check=\'bd-dark\' data-cls-checkbox=\'single-check\' data-caption=\'\'>",';
        endif;
	
		// $data .= '"'.($auth->verifyUserPermission('checked', 4) == true ? '<a class=\'tool-button outline dark\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to view details of work order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'workorder.php?page=details&id='.$datarow['NID'].'\'><span class=\'mif-eye\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('checked', 4) == true ? '<a class=\'tool-button info\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Print Work Order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'reports/workorder/'.$datarow['NID'].'\' target=\'_blank\'><span class=\'mif-printer\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').'"';

		$data .= '"'.($auth->verifyUserPermission('checked', 6) == true ? '<a class=\'tool-button outline dark\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to view details of work order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'workorder.php?page=details&id='.$datarow['NID'].'\'><span class=\'mif-eye\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('checked', 4) == true ? '<a class=\'tool-button info\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Print Work Order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'reports/workorder/'.$datarow['NID'].'\' target=\'_blank\'><span class=\'mif-printer\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('approved workorder', 6) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Move to published work order page\' class=\'tool-button yellow\' onclick=\'rowRollBack('.$datarow['NID'].', \"approved\", \"workorder\", $(this))\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-refresh\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button></div>' : '').'"';
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