<?php
 //error_reporting(E_ALL);
 //ini_set('display_errors', '1'); 

ini_set('memory_limit', '-1');
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('all workorder', 2)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();

		$managerFeature = $auth->getManagerFeatureAll();
		$subordinateFeature = $auth->getSubordinateFeature();
		$subordinateFeature_2 = $auth->getSubordinateFeature_2();

		$subordinateFeature = "'" . str_replace(",", "','", addslashes($subordinateFeature)) . "'";
		$subordinateFeature_2 = "'" . str_replace(",", "','", addslashes($subordinateFeature_2)) . "'";

		// $managerFeature = $auth->allGetManagerFeature($managerFeature);
		// $managerFeature = "'" . str_replace(",", "','", addslashes($managerFeature)) . "'";
		
		$filterWhere = $pageOpt->filterWhere('workorder');
		$pageOpt->filterWhereClose();

		$masterData = $accessoriesModel->getData("SELECT 
			master.nid, 
			master.vponumber, 
			master.vblockorderinfo, 
			master.vstatus, 
			master.vissue, 
			s.vname as supplier, 
			REGEXP_REPLACE(
				LISTAGG(goods.vname, ', ') WITHIN GROUP (
				ORDER BY 
					items.nid ASC
				), 
				'([^,]+)(, \\1)+', 
				'\\1'
			) AS itemname, 
			listagg(items.ntotalqty, ', ') within group (
				order by 
				items.nid asc
			) as itemqty, 
			master.vordernumberorfklnumber, 
			master.vtype, 
			master.vcreateduser, 
			employee.VEMPNAME AS createduser, 
			master.vpublishedat, 
			master.ncheckedstatus, 
			master.vpublisheduser, 
			checkedby.VEMPNAME AS publisheduser, 
			master.napprovedstatus, 
			master.vapproveduser, 
			approvedemp.VEMPNAME AS approveduser, 
			master.nacceptencestatus, 
			master.vaccepteduser, 
			acceptedemp.VEMPNAME AS accepteduser, 
			REGEXP_REPLACE(
				LISTAGG(
				UPPER(orderinfo.vstylename), 
				', '
				) WITHIN GROUP (
				ORDER BY 
					orderinfo.vpart ASC
				), 
				'([^,]+)(, \\1)+', 
				'\\1'
			) AS orderinfostylename, 
			REGEXP_REPLACE(
				LISTAGG(orderinfoks.nks_id, ', ') WITHIN GROUP (
				ORDER BY 
					orderinfo.norderid ASC
				), 
				'([^,]+)(, \\1)+', 
				'\\1'
			) AS orderinfoksksid, 
			orderinfo.vname AS orderinfobuyername, 
			REGEXP_REPLACE(
				LISTAGG(
				UPPER(ksorderinfo.vstylename), 
				', '
				) WITHIN GROUP (
				ORDER BY 
					ksorderinfo.vpart ASC
				), 
				'([^,]+)(, \\1)+', 
				'\\1'
			) AS ksstylename, 
			REGEXP_REPLACE(
				LISTAGG(ksorderinfo.vordernumber, ', ') WITHIN GROUP (
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
				TO_CHAR(
				extract(
					year 
					from 
					TO_DATE(master.vcreatedat, 'dd-mm-yyyy')
				)
				) = TO_CHAR(
				extract(
					year 
					from 
					TO_DATE(orderinfo.dshipdate, 'dd-mm-yy')
				)
				) 
				OR TO_CHAR(
				extract(
					year 
					from 
					TO_DATE(master.vcreatedat, 'dd-mm-yyyy')
				)
				) = TO_CHAR(
				extract(
					year 
					from 
					TO_DATE(orderinfo.duserdate, 'dd-mm-yy')
				)
				)
			) 
			LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid 
			LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (
				TO_NUMBER(
				REGEXP_REPLACE (
					upper(vordernumberorfklnumber), 
					'[A-Z]', 
					0
				)
				)
			) 
			LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode 
			LEFT JOIN hrm_employee employee ON employee.VEMPLOYEEID = master.vcreateduser 
			LEFT JOIN hrm_employee checkedby ON checkedby.VEMPLOYEEID = master.vpublisheduser 
			LEFT JOIN hrm_employee approvedemp ON approvedemp.VEMPLOYEEID = master.vapproveduser 
			LEFT JOIN hrm_employee acceptedemp ON acceptedemp.VEMPLOYEEID = master.vaccepteduser 
			LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid 
			LEFT join accessories_suppliers s on master.nsupllierid = s.nid 
			WHERE 
			master.ndeletedstatus = 0 
			AND master.vstatus = 'publish' 
			AND (
				master.vcreateduser = '$userid' 
				OR master.vcreateduser IN ($managerFeature) 
				OR master.vcreateduser IN ($subordinateFeature) 
				OR master.vcreateduser IN ($subordinateFeature_2) 
				OR (
				SELECT 
					vrole 
				FROM 
					ACCESSORIES_USERSPERMISSION 
				WHERE 
					vfklid = '$userid'
				) = 'admin' 
				OR (
				SELECT 
					vrole 
				FROM 
					ACCESSORIES_USERSPERMISSION 
				WHERE 
					vfklid = '$userid'
				) = 'super admin'
			) $filterWhere 
			GROUP BY 
			master.nid, 
			master.vcreatedat, 
			master.vponumber, 
			master.vblockorderinfo, 
			master.vstatus, 
			master.vissue, 
			master.vordernumberorfklnumber, 
			master.vtype, 
			master.vcreateduser, 
			employee.empname, 
			master.vpublishedat, 
			master.ncheckedstatus, 
			master.vpublisheduser, 
			master.napprovedstatus, 
			master.vapproveduser, 
			approvedemp.empname, 
			master.nacceptencestatus, 
			master.vaccepteduser, 
			acceptedemp.empname, 
			checkedby.empname, 
			orderinfo.vname, 
			ksorderinfo.vname, 
			s.vname 
			ORDER BY 
			master.nid DESC
");
		// $masterData = $accessoriesModel->getData("SELECT master.nid, master.vponumber, master.vblockorderinfo, master.vstatus, master.vissue, s.vname as supplier, REGEXP_REPLACE(LISTAGG(goods.vname, ', ') WITHIN GROUP (ORDER BY items.nid ASC), '([^,]+)(, \\1)+', '\\1') AS itemname, listagg(items.ntotalqty,', ') within group (order by items.nid asc) as itemqty, master.vordernumberorfklnumber, master.vtype, master.vcreateduser, employee.empname AS createduser, master.vpublishedat, master.ncheckedstatus, master.vpublisheduser, checkedby.empname AS publisheduser, master.napprovedstatus, master.vapproveduser, approvedemp.empname AS approveduser, master.nacceptencestatus, master.vaccepteduser, acceptedemp.empname AS accepteduser,  REGEXP_REPLACE(LISTAGG(UPPER(orderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfostylename, REGEXP_REPLACE(LISTAGG(orderinfoks.nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfoksksid, orderinfo.vname AS orderinfobuyername, REGEXP_REPLACE(LISTAGG(UPPER(ksorderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  ksorderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS ksstylename, REGEXP_REPLACE(LISTAGG(ksorderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY ksorderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS ksordernumber, ksorderinfo.vname AS ksbuyername FROM accessories_workordermaster master LEFT JOIN accessories_workorderitems items ON items.nworkordermasterid = master.nid LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.vordernumber = master.vordernumberorfklnumber AND (TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.dshipdate, 'dd-mm-yy'))) OR TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.duserdate, 'dd-mm-yy')))) LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (TO_NUMBER(REGEXP_REPLACE (upper(vordernumberorfklnumber), '[A-Z]', 0))) LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode LEFT JOIN hr_employeeinfo@crypton employee ON employee.empid = master.vcreateduser LEFT JOIN hr_employeeinfo@crypton checkedby ON checkedby.empid = master.vpublisheduser LEFT JOIN hr_employeeinfo@crypton approvedemp ON approvedemp.empid = master.vapproveduser LEFT JOIN hr_employeeinfo@crypton acceptedemp ON acceptedemp.empid = master.vaccepteduser LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid left join accessories_suppliers s on master.nsupllierid=s.nid WHERE master.ndeletedstatus = 0 AND master.vstatus = 'publish' AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) OR master.vcreateduser IN ($subordinateFeature) OR master.vcreateduser IN ($subordinateFeature_2) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') $filterWhere GROUP BY master.nid, master.vcreatedat, master.vponumber, master.vblockorderinfo, master.vstatus, master.vissue, master.vordernumberorfklnumber, master.vtype, master.vcreateduser, employee.empname, master.vpublishedat, master.ncheckedstatus, master.vpublisheduser, master.napprovedstatus, master.vapproveduser, approvedemp.empname, master.nacceptencestatus, master.vaccepteduser, acceptedemp.empname, checkedby.empname, orderinfo.vname, ksorderinfo.vname, s.vname ORDER BY TO_DATE(regexp_substr(master.vponumber,'[^-]+',1,2),'MONYY'), regexp_substr(master.vponumber,'[^-]+',1,3) DESC");
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
		"name": "supplier",
		"title": "Supplier",
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
		"name": "publishedat",
		"title": "Workorder Date",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},{
		"name": "approvedby",
		"title": "Approved By",
		"sortable": true,
		"clsColumn": "text-center",
		"cls": "text-center"
		},
		{
		"name": "actions",
		"title": "Actions",
		"sortable": false,
		"size": 120,
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
			$supplierName = '';
			if($datarow['VTYPE'] == 'Order number'):
				if(strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'block' || strtolower($datarow['VORDERNUMBERORFKLNUMBER']) == 'store'):
					$explodeBlockData = explode("##", $datarow['VBLOCKORDERINFO']);
					$orderNumber = $datarow['VORDERNUMBERORFKLNUMBER'];
					$fklNo = isset($explodeBlockData[0]) ? $explodeBlockData[0] : '' ;
					$buyerName = isset($explodeBlockData[2]) ? $explodeBlockData[2] : '' ;
					$styleName = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
					// $supplierName = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
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
		// $data .= '"'.$datarow['ITEMQTY'].'",';
		$data .= '"'.$fklNo.'",';
		$data .= '"'.$orderNumber.'",';
		$data .= '"'.$buyerName.'",';
		$data .= '"'.$styleName.'",';
		$data .= '"'.$datarow['SUPPLIER'].'",';
		$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VCREATEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
		
		if(!empty($datarow['VPUBLISHEDUSER'])):
			$data .= '"'.$datarow['VPUBLISHEDAT'].'",';
		//   $data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VPUBLISHEDUSER'].'\'>'.$datarow['PUBLISHEDUSER'].'</a>",';
		else:
		  $data .= '"x",';
		endif;
		if($datarow['NAPPROVEDSTATUS'] == 1):
		  $data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VAPPROVEDUSER'].'\'>'.$datarow['APPROVEDUSER'].'</a>",';
		else:
		  $data .= '"x",';
		endif;
		// if($datarow['NACCEPTENCESTATUS'] == 1):
		//   $data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VACCEPTEDUSER'].'\'>'.$datarow['ACCEPTEDUSER'].'</a>",';
		// else:
		//   $data .= '"x",';
		// endif;
		$data .= '"'.($auth->verifyUserPermission('all workorder', 3) == true ? '<a class=\'tool-button outline dark\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to view details of work order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'workorder.php?page=details&id='.$datarow['NID'].'\'><span class=\'mif-eye\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('publish workorder', 6) == true ? '<a class=\'tool-button info\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Print Work Order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'reports/workorder/'.$datarow['NID'].'\' target=\'_blank\'><span class=\'mif-printer\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').' <a class=\'tool-button dark \' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Copy work order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' onclick=\'workorderCopy(\"'.$datarow['VTYPE'].'\", '.$datarow['NID'].', \"'.$db->csrfToken().'\")\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'javascript:void(0)\'><span class=\'mif-copy\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a>"';
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