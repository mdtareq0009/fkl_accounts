<?php
ini_set('memory_limit', '-1');
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	 if($auth->verifyUserPermission('publish workorder', 2)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeatureAll();
		$subordinateFeature = $auth->getSubordinateFeature();
		$subordinateFeature_2 = $auth->getSubordinateFeature_2();
		
		$subordinateFeature = "'" . str_replace(",", "','", addslashes($subordinateFeature)) . "'";
		$subordinateFeature_2 = "'" . str_replace(",", "','", addslashes($subordinateFeature_2)) . "'";

		$masterData = $accessoriesModel->getData("SELECT 
	* 
	FROM 
	(
    SELECT 
      master.nid, 
      master.vponumber, 
      master.vblockorderinfo, 
      master.vissue, 
      REGEXP_REPLACE(
        LISTAGG(goods.vname, ', ') WITHIN GROUP (
          ORDER BY 
            items.nid ASC
        ), 
        '([^,]+)(, \\1)+', 
        '\\1'
      ) AS itemname, 
      master.vordernumberorfklnumber, 
      master.vtype, 
      master.vpublisheduser, 
      employee.empname AS createduser, 
      master.vpublishedat, 
      master.ncheckedstatus, 
      master.vcheckeduser, 
      checkedby.empname AS checkeduser, 
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
      LEFT JOIN hr_employeeinfo@crypton employee ON employee.empid = master.vpublisheduser 
      LEFT JOIN hr_employeeinfo@crypton checkedby ON checkedby.empid = master.vcheckeduser 
      LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid 
    WHERE 
      master.vstatus = 'publish' 
      AND master.nissuestatus = 1 
      AND master.ncheckedstatus = 0 
      AND master.napprovedstatus = 0 
      AND master.ndeletedstatus = 0 
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
      ) 
    GROUP BY 
      master.nid, 
      master.vponumber, 
      master.vblockorderinfo, 
      master.vissue, 
      master.vordernumberorfklnumber, 
      master.vtype, 
      master.vpublisheduser, 
      employee.empname, 
      master.vpublishedat, 
      master.ncheckedstatus, 
      master.vcheckeduser, 
      checkedby.empname, 
      orderinfo.vname, 
      ksorderinfo.vname 
    ORDER BY 
      master.nid DESC
  ) 
where 
  rownum <= 10000");
		


/*AND (TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(ksorderinfo.dshipdate, 'dd-mm-yy'))) OR TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(ksorderinfo.duserdate, 'dd-mm-yy'))))*/
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
		},';
		if($auth->verifyUserPermission('checked', 1)):
		$data .= '{
		"name": "checkedby",
		"title": "<input type=\'checkbox\' data-role=\'checkbox\' data-style=\'2\' onchange=\'allCheck(\"published-workorder-table\", $(this))\' data-cls-check=\'bd-white\' data-cls-checkbox=\'all-checked\' data-caption=\'\'>",
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
		$data .= '"<strong>W.O-'.$datarow['NID'].'</strong><br><small class=\'tally '.($datarow['VISSUE'] > 1 ? 'warning' : 'info').'\' style=\'position: absolute;left: -10px;bottom: 10px;transform: rotate(-38deg);font-size: 11px;font-weight: bold; padding: 0 4px;\'>ISSUE-'.$datarow['VISSUE'].'</small>",';
		$data .= '"<strong>'.$datarow['VPONUMBER'].'</strong>",';
		$data .= '"'.$datarow['ITEMNAME'].'",';
		$data .= '"'.$fklNo.'",';
		$data .= '"'.$orderNumber.'",';
		$data .= '"'.$buyerName.'",';
		$data .= '"'.$styleName.'",';
		$data .= '"<a href=\'users.php?page=usersdetails&fklid='.$datarow['VPUBLISHEDUSER'].'\'>'.$datarow['CREATEDUSER'].'</a>",';
		$data .= '"'.$datarow['VPUBLISHEDAT'].'",';
		
		// if($auth->verifyUserPermission('publish workorder', 2)):
		if($auth->verifyUserPermission('checked', 1)):
			$data .= '"<input type=\'checkbox\' data-role=\'checkbox\' value=\''.$datarow['NID'].'\' onchange=\'singleCheck(\"published-workorder-table\", $(this), event)\' data-style=\'2\' data-cls-check=\'bd-dark\' data-cls-checkbox=\'single-check\' data-caption=\'\'>",';
		endif;
		$data .= '"'.($auth->verifyUserPermission('publish workorder', 3) == true ? '<a class=\'tool-button outline dark\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to view details of work order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'workorder.php?page=details&id='.$datarow['NID'].'\'><span class=\'mif-eye\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('publish workorder', 6) == true ? '<a class=\'tool-button info\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Print Work Order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' style=\'width: 26px;height: 26px;line-height: 26px;\' href=\'reports/workorder/'.$datarow['NID'].'\' target=\'_blank\'><span class=\'mif-printer\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').'<button class=\'tool-button\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Expand more options\' style=\'width: 26px;height: 26px;line-height: 26px;\' id=\'dropdown_toggle_'.$key.'\'><span class=\'mif-arrow-drop-down\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button><div class=\'mt-1 fg-white\' data-role=\'collapse\' data-toggle-element=\'#dropdown_toggle_'.$key.'\' data-collapsed=\'true\'>'.($auth->verifyUserPermission('publish workorder', 4) == true ? '<a href=\'workorder.php?page=edit&id='.$datarow['NID'].'\' data-role=\'hint\' data-hint-position=\'top\' data-hint-text=\'Click to edit work order <br>(<strong>'.$datarow['VPONUMBER'].'</strong>)\' class=\'tool-button secondary fg-white\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-pencil\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></a> ' : '').''.($auth->verifyUserPermission('publish workorder', 10) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Move to drafts work order page\' '.($datarow['VISSUE'] > 1 ? 'disabled' : '').' onclick=\'rowRollBack('.$datarow['NID'].', \"published\", \"workorder\", $(this))\' class=\'tool-button yellow\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-refresh\' style=\'height: 22px; line-height: 22px;\'></span></button> ' : '').''.($auth->verifyUserPermission('publish workorder', 5) == true ? '<button type=\'button\' data-role=\'hint\' data-hint-position=\'left\' data-hint-text=\'Click to delete work order (<strong>'.$datarow['VPONUMBER'].'</strong>)\' onclick=\'deleteRow('.$datarow['NID'].', \"workorder\", \"workorder\", $(this))\' class=\'tool-button alert\' style=\'width: 26px;height: 26px;line-height: 26px;\'><span class=\'mif-bin\' style=\'height: 22px; line-height: 22px; font-size: .9rem;\'></span></button>' : '').'</div>"';
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