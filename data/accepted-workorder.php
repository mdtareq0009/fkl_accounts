<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('accepted workorder', 2)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeatureAll();
		$subordinateFeature = $auth->getSubordinateFeature();
		$subordinateFeature_2 = $auth->getSubordinateFeature_2();
		
		// $managerFeature = $auth->allGetManagerFeature($managerFeature);

		$subordinateFeature = "'" . str_replace(",", "','", addslashes($subordinateFeature)) . "'";
		$subordinateFeature_2 = "'" . str_replace(",", "','", addslashes($subordinateFeature_2)) . "'";

		$filterWhere = $pageOpt->filterWhere('workorder');
		$pageOpt->filterWhereClose();
		$masterData = $accessoriesModel->getData("SELECT
			master.nid,
			master.vponumber,
			master.vblockorderinfo,
			master.vissue,
			REGEXP_REPLACE (
				LISTAGG (goods.vname, ', ') WITHIN GROUP (
					ORDER BY
						items.nid ASC
				),
				'([^,]+)(, \\1)+',
				'\\1'
			) AS itemname,
			master.vordernumberorfklnumber,
			master.vtype,
			master.vcreateduser,
			employee.vempname AS createduser,
			approvedemployee.vempname AS approveduser,
			master.vapproveduser,
			master.vaccepteduser,
			acceptedemployee.vempname AS accepteduser,
			master.vcreatedat,
			master.ncheckedstatus,
			master.vcheckeduser,
			checkedby.vempname AS checkeduser,
			REGEXP_REPLACE (
				LISTAGG (UPPER(orderinfo.vstylename), ', ') WITHIN GROUP (
					ORDER BY
						orderinfo.vpart ASC
				),
				'([^,]+)(, \\1)+',
				'\\1'
			) AS orderinfostylename,
			REGEXP_REPLACE (
				LISTAGG (orderinfoks.nks_id, ', ') WITHIN GROUP (
					ORDER BY
						orderinfo.norderid ASC
				),
				'([^,]+)(, \\1)+',
				'\\1'
			) AS orderinfoksksid,
			orderinfo.vname AS orderinfobuyername,
			REGEXP_REPLACE (
				LISTAGG (UPPER(ksorderinfo.vstylename), ', ') WITHIN GROUP (
					ORDER BY
						ksorderinfo.vpart ASC
				),
				'([^,]+)(, \\1)+',
				'\\1'
			) AS ksstylename,
			REGEXP_REPLACE (
				LISTAGG (ksorderinfo.vordernumber, ', ') WITHIN GROUP (
					ORDER BY
						ksorderinfo.norderid ASC
				),
				'([^,]+)(, \\1)+',
				'\\1'
			) AS ksordernumber,
			ksorderinfo.vname AS ksbuyername
		FROM
			accessories_workordermaster master
			LEFT JOIN accessories_workorderitems items ON items.nworkordermasterid = master.nid
			LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.vordernumber = master.vordernumberorfklnumber
			AND (
				TO_CHAR (
					extract(
						year
						from
							TO_DATE (master.vcreatedat, 'dd-mm-yyyy')
					)
				) = TO_CHAR (
					extract(
						year
						from
							TO_DATE (orderinfo.dshipdate, 'dd-mm-yy')
					)
				)
				OR TO_CHAR (
					extract(
						year
						from
							TO_DATE (master.vcreatedat, 'dd-mm-yyyy')
					)
				) = TO_CHAR (
					extract(
						year
						from
							TO_DATE (orderinfo.duserdate, 'dd-mm-yy')
					)
				)
			)
			LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid
			LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (
				TO_NUMBER (
					REGEXP_REPLACE (upper(vordernumberorfklnumber), '[A-Z]', 0)
				)
			)
			LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode
			LEFT JOIN hrm_employee employee ON employee.vemployeeid = master.vcreateduser
			LEFT JOIN hrm_employee approvedemployee ON approvedemployee.vemployeeid = master.vapproveduser
			LEFT JOIN hrm_employee acceptedemployee ON acceptedemployee.vemployeeid = master.vaccepteduser
			LEFT JOIN hrm_employee checkedby ON checkedby.vemployeeid = master.vcheckeduser
			LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid
		WHERE
			master.vstatus = 'publish'
			AND master.nissuestatus = 1
			AND master.ncheckedstatus = 1
			AND master.nmerchandiserstatus = 1
			AND master.nmerchandisermanagerstatus = 1
			AND master.nmerchandisergmstatus = 1
			AND master.npurchasegmstatus = 1
			AND master.nauditstatus = 1
			AND master.napprovedstatus = 1
			AND master.nacceptencestatus = 1
			AND master.ndeletedstatus = 0
		AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) OR master.vcreateduser IN ($subordinateFeature) OR master.vcreateduser IN ($subordinateFeature_2) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') 
		$filterWhere 
		GROUP BY master.nid, master.vponumber, master.vblockorderinfo, master.vissue, master.vordernumberorfklnumber, master.vtype, master.vcreateduser, employee.vempname, approvedemployee.vempname, acceptedemployee.vempname, master.vcreatedat, master.vapproveduser, master.vaccepteduser, master.ncheckedstatus, master.vcheckeduser, checkedby.vempname, orderinfo.vname, ksorderinfo.vname 
		ORDER BY master.nid DESC");
		$data = '{
		"header": [
		{
		"name": "wonumber",
		"title": "Workorder No.",
		"sortable": true,
		"clsColumn": "text-center pos-relative",
		"cls": "text-center"
		},
		{
		"name": "purchasenumber",
		"title": "Purchase No.",
		"sortable": true,
		"clsColumn": "text-center",
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
		"name": "approvedby",
		"title": "Approved By",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "acceptedby",
		"title": "Accepted By",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},';
		if($auth->verifyUserPermission('accepted workorder', 9)):
		$data .= '{
		"name": "reissue",
		"title": "Re-issue",
		"size": 18,
		"clsColumn": "text-center",
		"cls": "text-center"
		},';
		endif;
		$data .= '{
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
		$data .= '"<strong>W.O-'.$datarow['NID'].'</strong><br><small class=\'tally '.($datarow['VISSUE'] > 1 ? 'warning' : 'info').'\' style=\'position: absolute;left: -10px;bottom: 10px;transform: rotate(-38deg);font-size: 11px;font-weight: bold; padding: 0 4px;\'>ISSUE-'.$datarow['VISSUE'].'</small>",';
		$data .= '"<strong>'.$datarow['VPONUMBER'].'</strong>",';
		$data .= '"'.$datarow['ITEMNAME'].'",';
		$data .= '"'.$fklNo.'",';
		$data .= '"'.$orderNumber.'",';
		$data .= '"'.$buyerName.'",';
		$data .= '"'.$styleName.'",';
		$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
		$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VAPPROVEDUSER'].'\'>'.$datarow['APPROVEDUSER'].'</a>",';
		$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VACCEPTEDUSER'].'\'>'.$datarow['ACCEPTEDUSER'].'</a>",';
        if($auth->verifyUserPermission('accepted workorder', 9)):
		$data .= '"<a href=\'workorder.php?page=newissue&id='.$datarow['NID'].'\' class=\'image-button warning\' style=\'height: 22px; padding: 0 2px 0 0;\'><span class=\'mif-plus icon\' style=\'height: 22px; line-height: 22px; font-size: 11px; width: 20px;\'></span><span class=\'caption\' style=\'margin-left: 2px;\'>Issue-'.($datarow['VISSUE']+1).'</span></a>",';
		endif;
		$data .= '"'.($auth->verifyUserPermission('accepted workorder', 3) == true ? '<a class=\'tool-button outline dark\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to view details of work order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'workorder.php?page=details&id='.$datarow['NID'].'\'><span class=\'mif-eye\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '' ).''.($auth->verifyUserPermission('accepted workorder', 6) == true ? '<a class=\'tool-button info\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Print Work Order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'reports/workorder/'.$datarow['NID'].'\' target=\'_blank\'><span class=\'mif-printer\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a>' : '').'"';
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