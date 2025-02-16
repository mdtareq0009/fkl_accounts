<?php
require_once('../ini.php');
use accessories\accessoriescrud;
use accessories\workorderoperation;
if($auth->authUser()):
	if($auth->verifyUserPermission('publish workorder', 6) || $auth->verifyUserPermission('approved workorder', 6) || $auth->verifyUserPermission('accepted workorder', 6) || $auth->verifyUserPermission('all workorder', 6) || $auth->verifyUserPermission('checked', 6) || $auth->verifyUserPermission('checked', 1) || $auth->verifyUserPermission('checked', 2) || $auth->verifyUserPermission('checked', 3) || $auth->verifyUserPermission('checked', 4) || $auth->verifyUserPermission('checked', 5) || $auth->verifyUserPermission('checked', 7) || $auth->verifyUserPermission('checked', 8) || $auth->verifyUserPermission('checked', 9) || $auth->verifyUserPermission('checked', 10) || $auth->verifyUserPermission('checked', 12)):
      		$workorderOpt = new workorderoperation($db->con);
      		$accessoriesModel = new accessoriescrud($db->con);
      		$id = isset($_GET['id']) ? $_GET['id'] : 0;
      		if($accessoriesModel->checkDataExistence("SELECT nid FROM accessories_workordermaster WHERE nid = $id AND ndeletedstatus = 0 AND vstatus = 'publish' AND nissuestatus = 1") == 'exist'):
        
        		$masterData = $accessoriesModel->getData("SELECT master.nid, master.vponumber, master.vblockorderinfo, master.vordernumberorfklnumber, master.vtype, supplier.vname AS supplier,
				master.vtodate, master.vattn, master.vform, master.vgarmentsqty, master.vdeliverydate, master.vcreatedat, master.vcreateduser,
				master.vorderdetails, master.vextranotes, master.vissue
				,signature.bempsign as createdsign
				,vpublisheduser
				,published.bempsign as publishedsign
				,vcheckeduser,checked.bempsign as checkedsign
				,vmerchandiseruser,merchandiser.bempsign as merchandisersign
				,vmerchandisermanageruser,merchandisermanager.bempsign as merchandisermanager
				,vmerchandisergmuser,merchandisergm.bempsign as merchandisergm
				,vpurchasegmuser,purchasegm.bempsign as purchasegm
				,VAUDITUSER as vaudituser,NAUDITSTATUS,audit_user.bempsign as audituser
				,vapproveduser,approved.bempsign as approved
				,vaccepteduser,accepted.bempsign as accepted
				FROM accessories_workordermaster master 
				LEFT JOIN accessories_suppliers supplier ON supplier.nid = master.nsupllierid
				LEFT JOIN erppic.hrm_employeepicture signature ON master.vcreateduser = signature.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture published ON master.vpublisheduser = published.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture checked ON master.vcheckeduser = checked.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture merchandiser ON master.vmerchandiseruser = merchandiser.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture merchandisermanager ON master.vmerchandisermanageruser = merchandisermanager.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture merchandisergm ON master.vmerchandisergmuser = merchandisergm.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture purchasegm ON master.vpurchasegmuser = purchasegm.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture approved ON master.vapproveduser = approved.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture accepted ON master.vaccepteduser = accepted.vemployeeid
				LEFT JOIN erppic.hrm_employeepicture audit_user ON master.VAUDITUSER = audit_user.vemployeeid
				WHERE master.nid = $id
				AND master.vstatus = 'publish' AND master.ndeletedstatus = 0 AND master.nissuestatus = 1");
        		// $masterData = $accessoriesModel->getData("SELECT master.nid, master.vponumber, master.vblockorderinfo, master.vordernumberorfklnumber, master.vtype, supplier.vname AS supplier, master.vtodate, master.vattn, master.vform, master.vgarmentsqty, master.vdeliverydate, master.vcreatedat, master.vcreateduser, master.vorderdetails, master.vextranotes, master.vissue FROM accessories_workordermaster master LEFT JOIN accessories_suppliers supplier ON supplier.nid = master.nsupllierid WHERE master.nid = $id AND master.vstatus = 'publish' AND master.ndeletedstatus = 0 AND master.nissuestatus = 1");
        		$AOP = $accessoriesModel->getData("SELECT (SELECT vname FROM accessories_goods WHERE nid = itemtable.ngoodsid) AS itemname FROM accessories_workorderitems itemtable WHERE itemtable.nworkordermasterid = $id ORDER BY itemtable.nid ASC");
        		//print_r($AOP);
				$createduser = $masterData[0]['VCREATEDUSER'];
				$companyShortName = $workorderOpt->companyShortName($masterData[0]['VCREATEDUSER']);
				// echo $companyShortName;

				$merchandiserUser = $masterData[0]['VMERCHANDISERUSER'];
				$merchandiserManagerUser = $masterData[0]['VMERCHANDISERMANAGERUSER'];
				// $checkedSign = $masterData[0]['CHECKEDSIGN'];
				// $checkedUserSign = "<img style='display:block; width:117px;height:75px;' id='base64image' src='data:image/png;base64, ".base64_encode($masterData[0]['CHECKEDSIGN']->load())."' />";
				// $checkedUserSign = 'data:image/png;base64,'.base64_encode($masterData[0]['CHECKEDSIGN']->load());
			
				// print_r($masterData[0]['CHECKEDSIGN']->load());
				// exit;
				$checkedSign = isset($masterData[0]['CHECKEDSIGN'])? $masterData[0]['CHECKEDSIGN']->load() : '';
				$merchandiserSign = isset($masterData[0]['MERCHANDISERSIGN']) ? $masterData[0]['MERCHANDISERSIGN']->load() : '';
				$merchandiserManagerSign = isset($masterData[0]['MERCHANDISERMANAGER']) ? $masterData[0]['MERCHANDISERMANAGER']->load() : '';
				$merchandiserGmSign = isset($masterData[0]['MERCHANDISERGM']) ? $masterData[0]['MERCHANDISERGM']->load() : '';
				$purchaseGmSign = isset($masterData[0]['PURCHASEGM']) ? $masterData[0]['PURCHASEGM']->load() : '';
				$approvedSign = isset($masterData[0]['APPROVED']) ? $masterData[0]['APPROVED']->load() : '';
				$acceptedSign = isset($masterData[0]['ACCEPTED']) ? $masterData[0]['ACCEPTED']->load() : '';
				// print_r($sign);
				// exit;
				
				// $checkedUserSign = "<img style='display:block; width:117px;height:75px;' id='base64image' src='data:image/png;base64, ".$masterData[0]['CHECKEDSIGN']->load()."' />";
				$createdUserName = $accessoriesModel->getUser($createduser)['name'];
				// $checkedUserName = $accessoriesModel->getUser($checkedUser)['name'];
        		if(strtolower($masterData[0]['VTYPE']) == 'order number'):
          			$orderNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
          			$createdat = $masterData[0]['VCREATEDAT'];
          			if(strtolower($masterData[0]['VORDERNUMBERORFKLNUMBER']) == 'block' || strtolower($masterData[0]['VORDERNUMBERORFKLNUMBER']) == 'store'):
          				$explodeBlockData = explode('##', $masterData[0]['VBLOCKORDERINFO']);
          				$orderInfo[0]['STYLENAME'] = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
          				$orderInfo[0]['VSEASSONNAME'] = isset($explodeBlockData[3]) ? $explodeBlockData[3] : '' ;
          				$orderInfo[0]['BUYERNAME'] = isset($explodeBlockData[2]) ? $explodeBlockData[2] : '' ;
          				$orderInfo[0]['VDEPTNAME'] = isset($explodeBlockData[4]) ? $explodeBlockData[4] : '' ;
          				$orderInfo[0]['KIMBALLNO'] = isset($explodeBlockData[5]) ? $explodeBlockData[5] : '' ;
          				$fklNumber = isset($explodeBlockData[0]) ? $explodeBlockData[0] : '' ;
          			else:
          				$orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ksid, orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_vw_orderinfo orderinfo LEFT JOIN erp.mer_ks_master kmaster ON kmaster.nordercode = orderinfo.norderid WHERE orderinfo.vordernumber = '$orderNumber' AND to_date(orderinfo.dshipdate,'DD/MM/YYYY') - to_date('$createdat','DD/MM/YYYY') <= 365 AND to_date(orderinfo.dshipdate,'DD/MM/YYYY') - to_date('$createdat','DD/MM/YYYY') >= 0 GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname ORDER BY orderinfo.vseassonname DESC");
          				$fklNumber = $orderInfo[0]['KSID'];
          			endif;
        		elseif(strtolower($masterData[0]['VTYPE']) == 'fkl number'):
         			$fklNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
         			if(strtolower($masterData[0]['VORDERNUMBERORFKLNUMBER']) == 'block' || strtolower($masterData[0]['VORDERNUMBERORFKLNUMBER']) == 'store'):
          				$explodeBlockData = explode('##', $masterData[0]['VBLOCKORDERINFO']);
          				$orderInfo[0]['STYLENAME'] = isset($explodeBlockData[1]) ? $explodeBlockData[1] : '' ;
          				$orderInfo[0]['VSEASSONNAME'] = isset($explodeBlockData[3]) ? $explodeBlockData[3] : '' ;
          				$orderInfo[0]['BUYERNAME'] = isset($explodeBlockData[2]) ? $explodeBlockData[2] : '' ;
          				$orderInfo[0]['VDEPTNAME'] = isset($explodeBlockData[4]) ? $explodeBlockData[4] : '' ;
          				$orderInfo[0]['KIMBALLNO'] = isset($explodeBlockData[5]) ? $explodeBlockData[5] : '' ;
          				$orderNumber = isset($explodeBlockData[0]) ? $explodeBlockData[0] : '' ;
          			else:
          				$fklCon = intval($fklNumber);
	          			$orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(orderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ordernumber,  orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_ks_master kmaster LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.norderid = kmaster.nordercode WHERE kmaster.nks_id IN ($fklCon) GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname ORDER BY orderinfo.vseassonname DESC");
	          			$orderNumber = $orderInfo[0]['ORDERNUMBER'];
	          		endif;
        		else:
          			$orderInfo =  array();
        		endif;

        		require_once('fpdf.php');

        		class PDF extends FPDF{
          			public function Header(){
          				global $masterData;
          				global $orderInfo;
          				global $orderNumber;
          				global $fklNumber;
          				global $accessoriesModel;
          				global $id;
						global $companyShortName;

          				$this->SetFont('Arial','b',5.5);

						// ===============================
						$itemName = $accessoriesModel->getData("SELECT itemtable.nid, (SELECT vname FROM accessories_goods WHERE nid = itemtable.ngoodsid) AS itemname FROM accessories_workorderitems itemtable WHERE itemtable.nworkordermasterid = $id ORDER BY itemtable.nid DESC");
						// ===============================
         				//$this->Cell(0, 20, $this->Image('report-watermark.jpg',  $this->GetPageWidth()/2-50, 85, 110, 110), 0, 0, 'L', false );
          				//$this->Ln(0);
						  if(trim($companyShortName)==trim('ECO')){
							$this->Cell( 10, 15, $this->Image('ECO.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
						}else{
							$this->Cell( 10, 15, $this->Image('fakir-knit.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
						}
          				// $this->Cell( 10, 15, $this->Image('fakir-knit.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
          				// $this->Cell( 10, 15, $this->Image('fakir-logo-design-web-design.png', $this->GetPageWidth()/2-16.15, 3, 32.5, 12.5), 0, 0, 'L', false );
          				$this->Ln(5);
          				$this->MultiCell(0,4,"(AN ISO 9001 : 2008, SCR, BSCI, SEDEX, ORGANIC & OEKO TEX CERTIFIED COMPANY)", '', 'C');
          				$this->SetDrawColor(245,131,34);
          				$this->Line(0, $this->GetY(),  $this->GetPageWidth(), $this->GetY());
          				$this->Ln(1);
          				if($this->PageNo() == 1){
            					$this->SetFont('Times','b',10);
            					$this->SetDrawColor(1,1,1);
            					$this->SetFillColor(54, 52, 52);
            					$this->SetTextColor(255, 255, 255);
            					$this->SetX($this->GetPageWidth()/2 - 15);
            					$this->Cell(30, 5, 'Work Order', 1, 1, 'C', true );
            					$this->Ln(2);
           						$this->SetFont('Times','',9);
            					$this->SetTextColor(0, 0, 0);
            					if(strtolower($fklNumber) == 'store' || strtolower($orderNumber) == 'store'):
            						$tableHeader = array(
              						'columnName'          => array('Date', $masterData[0]['VTODATE'], '', 'Name Of Store', 'Accessories Store'),
              						'columnWidth'         => array(15, 47, 2, 22, 58),
              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
              						'columnFontSize'      => 8.5,
              						'columnFontWeight'    => array('b', '', '','b', ''),
              						'columnAlign'         => 'L', //options center, left, right
              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader, true);
	            					// $this->Ln();
	            					$tableHeader = array(
	              						'columnName'          => array('To', strlen($masterData[0]['SUPPLIER']) > 30 ? substr(htmlspecialchars_decode($masterData[0]['SUPPLIER'], ENT_QUOTES),0,29)."..." : htmlspecialchars_decode($masterData[0]['SUPPLIER'], ENT_QUOTES), ''),
	              						'columnWidth'         => array(15, 48, 1, 22, 52),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	             						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '','b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader);
	            					$this->Ln();
	           						$tableHeader = array(
	              						'columnName'          => array('Attention', strlen($masterData[0]['VATTN']) > 24 ? substr($masterData[0]['VATTN'],0,23)."..." : $masterData[0]['VATTN'], '', 'Workorder No.', 'W.O-'.$masterData[0]['NID']),
	              						'columnWidth'         => array(15, 48, 1, 22, 52),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	              						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '','b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader);
	            					$this->Ln();
	            					$tableHeader = array(
	              						'columnName'          => array('From', strlen($masterData[0]['VFORM']) > 30 ? substr($masterData[0]['VFORM'],0,29)."..." : $masterData[0]['VFORM'], ''),
	              						'columnWidth'         => array(15, 48, 1, 22, 52),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	              						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '','b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader);
	            					
            					else:
	            					$tableHeader = array(
	              						'columnName'          => array('Date', $masterData[0]['VTODATE'], '', 'Style / Ref.', $orderInfo[0]['STYLENAME']),
	              						'columnWidth'         => array(15, 47, 2, 22, 58),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	              						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '','b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader, true);
	            					// $this->Ln();
	            					$tableHeader = array(
	              						'columnName'          => array('To', htmlspecialchars_decode($masterData[0]['SUPPLIER'], ENT_QUOTES), '', 'Season', $orderInfo[0]['VSEASSONNAME']),
	              						'columnWidth'         => array(15, 48, 1, 22, 52),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	             						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '','b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader, true);
	            					//$this->Ln();
	           						$tableHeader = array(
	              						'columnName'          => array('Attention', strlen($masterData[0]['VATTN']) > 24 ? substr($masterData[0]['VATTN'],0,23)."..." : $masterData[0]['VATTN'], '', 'Order Number', $orderNumber),
	              						'columnWidth'         => array(15, 48, 1, 22, 52),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	              						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '','b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader);
	            					$this->Ln();
	            					$tableHeader = array(
	              						'columnName'          => array('From', strlen($masterData[0]['VFORM']) > 30 ? substr($masterData[0]['VFORM'],0,29)."..." : $masterData[0]['VFORM'], '', ($itemName[0]['ITEMNAME']=='Yarn' || $itemName[0]['ITEMNAME']=='Fabric') || $itemName[0]['ITEMNAME']=='Foreign Fabric'?'Quantity':'Garments Qty.', ($itemName[0]['ITEMNAME']=='Yarn' || $itemName[0]['ITEMNAME']=='Fabric' || $itemName[0]['ITEMNAME']=='Foreign Fabric')?$masterData[0]['VGARMENTSQTY'].' ':$masterData[0]['VGARMENTSQTY'].' Pcs'),
	              						'columnWidth'         => array(15, 48, 1, 22, 52),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	              						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '','b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader);
	            					$this->Ln();
	            					$tableHeader = array(
	              						'columnName'          => array('FKL No.', $fklNumber, '', 'Workorder No.', 'W.O-'.$masterData[0]['NID']),
	              						'columnWidth'         => array(15, 48, 1, 22, 52),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=>255, 'g'=>255, 'b'=>255), array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 255, 'g' => 255, 'b' => 255),
	              						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', '', '', 'b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	             					);
	            					$this->smartRow($tableHeader);
            					endif;
            					if(strtolower($fklNumber) == 'store' || strtolower($orderNumber) == 'store'):
            						$this->Ln(-35);
            					else:
            						$this->Ln(-39);
            					endif;
            					$this->SetX($this->GetPageWidth() - 50.5);
								
            					$this->SetFont('Times','b',8);
								if($companyShortName=='ECO')
									$this->Cell(20, 6.5, '', 0, 1, 'L', false );
								else
									$this->Cell(20, 6.5, 'CC : ', 0, 1, 'L', false );
								$this->Ln(-4.9);
								$this->SetX($this->GetPageWidth() - 43.5);
								$this->SetFont('Times','',8);
								if($companyShortName=='ECO')
									$this->Cell(34, 3.5, '', 0, 1, 'L', false );
								else
									$this->Cell(34, 3.5, 'Deputy Managing Director', 0, 1, 'L', false );
								$this->SetX($this->GetPageWidth() - 43.5);
								if($companyShortName=='ECO')
									$this->Cell(34, 3.5, '', 0, 1, 'L', false );
								else
									$this->Cell(34, 3.5, 'General Manager (Purchase)', 0, 1, 'L', false );
								$this->SetX($this->GetPageWidth() - 43.5);
								if($companyShortName=='ECO')
									$this->Cell(34, 3.5, '', 0, 1, 'L', false );
								else
            					$this->Cell(34, 3.5, 'Store & Accounts', 0, 1, 'L', false );
            					$this->Ln(3.6);
            					$this->SetTextColor(0, 0, 0);
            					$this->SetX($this->GetPageWidth() -59);
            					$this->SetDrawColor(54, 52, 52);
            					$this->SetFont('Times','',8.5);

            					if(strtolower($fklNumber) == 'store' || strtolower($orderNumber) == 'store'):
            						$this->Cell(49, 5, 'INV/C/3/004', 1, 1, 'C', false );
            					else:
            						$this->Cell(49, 5, 'PUR/3/001', 1, 1, 'C', false );
            					endif;
            					$this->SetX($this->GetPageWidth() - 59);
            					$tableHeader = array(
             						'columnName'          => array('P.O. Number',$masterData[0]['VPONUMBER']),
              						'columnWidth'         => array(20, 29),
              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222)),
              						'columnBorder'        => array('r' => 54, 'g' => 52, 'b' => 52),
              						'columnFontSize'      => 8.5,
              						'columnFontWeight'    => array('b', ''),
              						'columnAlign'         => 'L', //options center, left, right
              						'columnVerticalAlign' => 'middle' // middle, top, bottom
            					);
            					$this->smartRow($tableHeader);
            					$this->Ln();
            					$this->SetX($this->GetPageWidth() - 59);
            					$tableHeader = array(
              						'columnName'          => array('Buyer Name', htmlspecialchars_decode($orderInfo[0]['BUYERNAME'], ENT_QUOTES)),
              						'columnWidth'         => array(20, 29),
              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222)),
              						'columnBorder'        => array('r' => 0, 'g' => 0, 'b' => 0),
              						'columnFontSize'      => 8.5,
              						'columnFontWeight'    => array('b', ''),
              						'columnAlign'         => 'L', //options center, left, right
              						'columnVerticalAlign' => 'middle' // middle, top, bottom
            					);
            					$this->smartRow($tableHeader, true);
            					//$this->Ln();
            					//$this->SetXY($this->GetY());
            					$this->SetX($this->GetPageWidth() - 59);
            					$tableHeader = array(
              						'columnName'          => array('Department', htmlspecialchars_decode($orderInfo[0]['VDEPTNAME'], ENT_QUOTES)),
              						'columnWidth'         => array(20, 29),
              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222)),
              						'columnBorder'        => array('r' => 0, 'g' => 0, 'b' => 0),
              						'columnFontSize'      => 8.5,
              						'columnFontWeight'    => array('b', ''),
              						'columnAlign'         => 'L', //options center, left, right
              						'columnVerticalAlign' => 'middle' // middle, top, bottom
            					);
            					$this->smartRow($tableHeader);
            					
            					if(strtolower($fklNumber) == 'store' || strtolower($orderNumber) == 'store'):
            						$this->Ln();
            					else:
            						$this->Ln();
	            					$this->SetX($this->GetPageWidth() - 59);
	            					$tableHeader = array(
	              						'columnName'          => array('Kimball', $orderInfo[0]['KIMBALLNO']),
	              						'columnWidth'         => array(20, 29),
	              						'columnBackground'    => array(array('r'=> 222, 'g'=>222, 'b' => 222)),
	              						'columnBorder'        => array('r' => 0, 'g' => 0, 'b' => 0),
	              						'columnFontSize'      => 8.5,
	              						'columnFontWeight'    => array('b', ''),
	              						'columnAlign'         => 'L', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableHeader, true);
	            				endif;
            					
            					$this->SetX($this->GetPageWidth() -59);
            					$this->SetFont('Times','',8.5);
            					$this->Cell(49, 5, 'ISSUE-'.$masterData[0]['VISSUE'], 1, 1, 'C', false );
              
          				}
              				//print_r(count($this->page));
        			}

        			public function Footer() {
          				global $createdUserName;
          				global $merchandiserUser;
          				global $checkedUserName;
          				global $orderInfo;
          				global $orderNumber;
          				global $fklNumber;
          				global $AOP;
						
          				global $checkedSign;
						global $merchandiserSign;
						global $merchandiserManagerSign;
						global $merchandiserGmSign;
						global $purchaseGmSign;
						global $auditSign;
						global $approvedSign;
						global $acceptedSign;
						global $accessoriesModel;
						global $masterData;

						// For Checked=========
						$checkedType = array();
						$checkedImg = array();
						$finfo = new finfo(FILEINFO_MIME_TYPE);
						$checkedMimeType = $finfo->buffer($checkedSign);
						$checkedExtension = str_replace('image/','',$checkedMimeType);
						// $checkedExplode = explode(' ',$checkedSign);
						array_push($checkedType,$checkedExtension);
						array_push($checkedImg,"data:".$checkedMimeType.";base64,".base64_encode($checkedSign));
						
						// For merchandiserImg=========
						$merchandiserType = array();
						$merchandiserImg = array();
						$merchandiserMimeType = $finfo->buffer($merchandiserSign);
						$merchandiserExtension = str_replace('image/','',$merchandiserMimeType);
						// $merchandiserExplode = explode(';',$merchandiserSign);
						array_push($merchandiserType,$merchandiserExtension);
						array_push($merchandiserImg,"data:".$merchandiserMimeType.";base64,".base64_encode($merchandiserSign));
						
						// For merchandiserManagerImg=========
						$merchandiserManagerType = array();
						$merchandiserManagerImg = array();
						$merchandiserManagerMimeType = $finfo->buffer($merchandiserManagerSign);
						$merchandiserManagerExtension = str_replace('image/','',$merchandiserManagerMimeType);
						array_push($merchandiserManagerType,$merchandiserManagerExtension);
						array_push($merchandiserManagerImg,"data:".$merchandiserManagerMimeType.";base64,".base64_encode($merchandiserManagerSign));
						
						// For MerchandiserGMImg=========
						$merchandiserGmType = array();
						$merchandiserGmImg = array();
						$merchandiserGmMimeType = $finfo->buffer($merchandiserGmSign);
						$merchandiserGmExtension = str_replace('image/','',$merchandiserGmMimeType);
						array_push($merchandiserGmType,$merchandiserGmExtension);
						array_push($merchandiserGmImg,"data:".$merchandiserGmMimeType.";base64,".base64_encode($merchandiserGmSign));
						
						// For purchaseGMImg=========
						$purchaseGmType = array();
						$purchaseGmImg = array();
						$purchaseGmMimeType = $finfo->buffer($purchaseGmSign);
						$purchaseGmExtension = str_replace('image/','',$purchaseGmMimeType);
						array_push($purchaseGmType,$purchaseGmExtension);
						array_push($purchaseGmImg,"data:".$purchaseGmMimeType.";base64,".base64_encode($purchaseGmSign));
						// For audit=========
						$auditType = array();
						$auditImg = array();
						$auditMimeType = $finfo->buffer($auditSign);
						$auditExtension = str_replace('image/','',$auditMimeType);
						array_push($auditType,$auditExtension);
						array_push($auditImg,"data:".$auditMimeType.";base64,".base64_encode($auditSign));
						
						// For approvedImg=========
						$approvedType = array();
						$approvedImg = array();
						$approvedMimeType = $finfo->buffer($approvedSign);
						$approvedExtension = str_replace('image/','',$approvedMimeType);
						// $this->SetFont('Times','b',10);
						// For Accept=========
						// $approvedType = array();
						// $approvedImg = array();
						// $approvedMimeType = $finfo->buffer($approvedSign);
						// $approvedExtension = str_replace('image/','',$approvedMimeType);
			
						array_push($approvedType,$approvedExtension);
						array_push($approvedImg,"data:".$approvedMimeType.";base64,".base64_encode($approvedSign));
						
						//   $this->imageRow($checkedImg,$checkedType)
          				$cellWidth = ($this->GetPageWidth() - 20 ) / 7;
          				// $arrayMrdSignature1 = array('dashed' => array('Prepared By', ($checkedMimeType != 'application/x-empty') ? $this->imageSign($checkedImg, $checkedType, 30) : '', ($merchandiserMimeType != 'application/x-empty') ?$this->imageSign($merchandiserImg,$merchandiserType,55) : '', ($merchandiserManagerMimeType != 'application/x-empty')?$this->imageSign($merchandiserManagerImg,$merchandiserManagerType,85) : '', ($merchandiserGmMimeType != 'application/x-empty') ? $this->imageSign($merchandiserGmImg,$merchandiserGmType,120) : '', ($purchaseGmMimeType != 'application/x-empty') ? $this->imageSign($purchaseGmImg,$purchaseGmType,157) :'', ($approvedMimeType != 'application/x-empty') ? $this->imageSign($approvedImg,$approvedType,182) :''), 'cellwidth' => array($cellWidth-8, $cellWidth-8, $cellWidth+6, $cellWidth-1, $cellWidth+19, $cellWidth, $cellWidth-5), 'signDesignation' => array($createdUserName, 'Checked By', !empty($accessoriesModel->getUserData($merchandiserUser)['designation'])?$accessoriesModel->getUserData($merchandiserUser)['designation']:'Asst. Manager',' Manager/ Sr. Manager ('.$accessoriesModel->getUserData($merchandiserUser)['name'].' )', 'G.M.', 'D.G.M. (Purchase)', 'Approved By'));
						
          				$arrayMrdSignature1 = array('dashed' => array('Prepared By', ($checkedMimeType != 'application/x-empty') ? $this->imageSign($checkedImg, $checkedType, 30) : '', ($merchandiserMimeType != 'application/x-empty') ?$this->imageSign($merchandiserImg,$merchandiserType,55) : '', ($merchandiserManagerMimeType != 'application/x-empty')?$this->imageSign($merchandiserManagerImg,$merchandiserManagerType,85) : '', ($merchandiserGmMimeType != 'application/x-empty') ? $this->imageSign($merchandiserGmImg,$merchandiserGmType,120) : '', ($purchaseGmMimeType != 'application/x-empty') ? $this->imageSign($purchaseGmImg,$purchaseGmType,157) :'', ($approvedMimeType != 'application/x-empty') ? $this->imageSign($approvedImg,$approvedType,182) :''), 'cellwidth' => array($cellWidth-8, $cellWidth-8, $cellWidth+6, $cellWidth-1, $cellWidth+19, $cellWidth, $cellWidth-5), 'signDesignation' => array($createdUserName, !empty($accessoriesModel->getUserData($masterData[0]['VCHECKEDUSER'])['name'])? $accessoriesModel->getUserData($masterData[0]['VCHECKEDUSER'])['name'].' ( '.$accessoriesModel->getUserData($masterData[0]['VCHECKEDUSER'])['designation'].' )':'Checked By', !empty($accessoriesModel->getUserData($masterData[0]['VMERCHANDISERUSER'])['name'])?$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERUSER'])['name'].' ( '.$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERUSER'])['designation'].' )':'Senior Merchandiser / Asst. Manager', !empty($accessoriesModel->getUserData($masterData[0]['VMERCHANDISERMANAGERUSER'])['name'])?$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERMANAGERUSER'])['name'].' ( '.$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERMANAGERUSER'])['designation'].' )':' Manager / Senior Manager (Merchandising)', !empty($accessoriesModel->getUserData($masterData[0]['VMERCHANDISERGMUSER'])['name'])? $accessoriesModel->getUserData($masterData[0]['VMERCHANDISERGMUSER'])['name'].' ( '.$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERGMUSER'])['designation'] .' )':'G.M. (Merchandising & Marketing)', !empty($accessoriesModel->getUserData($masterData[0]['VPURCHASEGMUSER'])['name'])?$accessoriesModel->getUserData($masterData[0]['VPURCHASEGMUSER'])['name'].' ( '.$accessoriesModel->getUserData($masterData[0]['VPURCHASEGMUSER'])['designation'].' )':'D.G.M. (Purchase)', !empty($accessoriesModel->getUserData($masterData[0]['VAPPROVEDUSER'])['name'])?$accessoriesModel->getUserData($masterData[0]['VAPPROVEDUSER'])['name'].' ( '.$accessoriesModel->getUserData($masterData[0]['VAPPROVEDUSER'])['designation'].' )':'Approved By'));

          				// $arrayMrdSignature1 = array('dashed' => array('Prepared By', ($checkedMimeType != 'application/x-empty') ? $this->imageSign($checkedImg, $checkedType, 30) : '', ($merchandiserMimeType != 'application/x-empty') ?$this->imageSign($merchandiserImg,$merchandiserType,55) : '', ($merchandiserManagerMimeType != 'application/x-empty')?$this->imageSign($merchandiserManagerImg,$merchandiserManagerType,85) : '', ($merchandiserGmMimeType != 'application/x-empty') ? $this->imageSign($merchandiserGmImg,$merchandiserGmType,110) : '', ($purchaseGmMimeType != 'application/x-empty') ? $this->imageSign($purchaseGmImg,$purchaseGmType,142) :'', ($auditMimeType != 'application/x-empty') ? $this->imageSign($auditImg,$auditType,157) : '',($approvedMimeType != 'application/x-empty') ? $this->imageSign($approvedImg,$approvedType,182) :''), 'cellwidth' => array($cellWidth-8, $cellWidth-8, $cellWidth+6, $cellWidth-1, $cellWidth+19, $cellWidth, $cellWidth-5), 'signDesignation' => array($createdUserName, !empty($accessoriesModel->getUserData($masterData[0]['VCHECKEDUSER'])['name'])?'('.$accessoriesModel->getUserData($masterData[0]['VCHECKEDUSER'])['name'].') Checked By ':'Checked By', !empty($accessoriesModel->getUserData($masterData[0]['VMERCHANDISERUSER'])['name'])?'('.$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERUSER'])['name'].') Senior Merchandiser / Asst. Manager ':'Senior Merchandiser / Asst. Manager', !empty($accessoriesModel->getUserData($masterData[0]['VMERCHANDISERMANAGERUSER'])['name'])?'('.$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERMANAGERUSER'])['name'].') Manager / Senior Manager (Merchandising) ':' Manager / Senior Manager (Merchandising)', !empty($accessoriesModel->getUserData($masterData[0]['VMERCHANDISERGMUSER'])['name'])?'('.$accessoriesModel->getUserData($masterData[0]['VMERCHANDISERGMUSER'])['name'].') G.M. (Merchandising & Marketing) ':'G.M. (Merchandising & Marketing)', !empty($accessoriesModel->getUserData($masterData[0]['VPURCHASEGMUSER'])['name'])?'('.$accessoriesModel->getUserData($masterData[0]['VPURCHASEGMUSER'])['name'].') D.G.M. (Purchase) ':'D.G.M. (Purchase)', !empty($accessoriesModel->getUserData($masterData[0]['VAUDITUSER'])['name'])?'('.$accessoriesModel->getUserData($masterData[0]['VAUDITUSER'])['name'].') Audit ':'Audit', !empty($accessoriesModel->getUserData($masterData[0]['VAPPROVEDUSER'])['name'])?'('.$accessoriesModel->getUserData($masterData[0]['VAPPROVEDUSER'])['name'].') Approved By ':'Approved By'));

          				// $arrayMrdSignature1 = array('dashed' => array('Prepared By', $this->imageSign($checkedImg,$checkedType,30), $this->imageSign($merchandiserImg,$merchandiserImg,55), $this->imageSign($merchandiserManagerImg,$merchandiserManagerType,85), $this->imageSign($merchandiserGmImg,$merchandiserGmImg,120), $this->imageSign($purchaseGmImg,$purchaseGmType,157), ''), 'cellwidth' => array($cellWidth-8, $cellWidth-8, $cellWidth+6, $cellWidth-1, $cellWidth+19, $cellWidth, $cellWidth-5), 'signDesignation' => array($createdUserName, 'Checked By', 'Senior Merchandiser / Asst. Manager', ' Manager / Senior Manager (Merchandising)', 'G.M. (Merchandising & Marketing)', 'G.M. (Purchase)', 'Approved By'));

          				$arrayMrdSignature2 = array('dashed' => array('Prepared By', 'Checked By', '-----------------------------', '-------------------', '-------------------', '-------------------------------------------', '----------------'), 'cellwidth' => array($cellWidth-8, $cellWidth-8, $cellWidth+6, $cellWidth-1, $cellWidth, $cellWidth+19, $cellWidth-5), 'signDesignation' => array($createdUserName, '', 'Senior Merchandiser / Asst. Manager', ' Manager / Senior Manager (Merchandising)', 'A.G.M. (Knitting)', 'G.M. (Merchandising & Marketing)', 'Approved By'));
          				$purchase = $AOP[0]['ITEMNAME'] == 'AOP' ? $arrayMrdSignature2 : $arrayMrdSignature1; 
          				if(!isset($this->footerset[$this->page])) {
            					$this->SetY(-20);       
            					$this->Ln(1);
            					
            					// if($orderInfo[0]['BUYERNAME'] == 'Primark' || $orderInfo[0]['BUYERNAME'] == 'Penneys' || $orderInfo[0]['BUYERNAME'] == 'Primark & Penneys'){
            					if(strtolower($fklNumber) == 'store' || strtolower($orderNumber) == 'store'):
            						$cellWidth = ($this->GetPageWidth() - 20 ) / 5;
            						$arrayAccStoreSignature = array('dashed' => array('Prepared By', 'Checked By', '--------------------', '----------------------', '----------------'), 'cellwidth' => array($cellWidth, $cellWidth, $cellWidth, $cellWidth, $cellWidth), 'signDesignation' => array($createdUserName, '', 'Manager (Store)', 'A.G.M. (Inventory)', 'Approved By'));
	            					$tableFooter = array(
	              						'columnName'          => $arrayAccStoreSignature['dashed'],
	              						'columnWidth'         => $arrayAccStoreSignature['cellwidth'],
	              						'columnFontSize'      => 8,
	              						'columnFontWeight'    => '',
	              						'columnHeight'        => 3, //set column padding
	              						'columnAlign'         => 'C', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableFooter, true);
	            					//$this->Ln(2);
	            					
	            					$tableFooter = array(
	              						'columnName'          => $arrayAccStoreSignature['signDesignation'],
	              						'columnWidth'         => $arrayAccStoreSignature['cellwidth'],
	              						'columnFontSize'      => 8,
	              						'columnFontWeight'    => '',
	              						'columnHeight'        => 3, //set column padding
	              						'columnAlign'         => 'C', //options center, left, right
	              						'columnVerticalAlign' => 'top' // middle, top, bottom
	            					);
	            					$this->smartRow($tableFooter);
            					else:
            						$tableFooter = array(
	              						'columnName'          => $purchase['dashed'],
	              						'columnWidth'         => $purchase['cellwidth'],
	              						'columnFontSize'      => 6.5,
	              						'columnFontWeight'    => '',
	              						'columnHeight'        => 2.5, //set column padding
	              						'columnAlign'         => 'C', //options center, left, right
	              						'columnVerticalAlign' => 'middle' // middle, top, bottom
	            					);
	            					$this->smartRow($tableFooter, true);
	            					//$this->Ln(2);
	            					
	            					$tableFooter = array(
	              						'columnName'          => $purchase['signDesignation'],
	              						'columnWidth'         => $purchase['cellwidth'],
	              						'columnFontSize'      => 6.5,
	              						'columnFontWeight'    => '',
	              						'columnHeight'        => 2.5, //set column padding
	              						'columnAlign'         => 'C', //options center, left, right
	              						'columnVerticalAlign' => 'top' // middle, top, bottom
	            					);
	            					$this->smartRow($tableFooter);
	            				endif;
            					// }else{
            					// 	$cellWidth = ($this->GetPageWidth() - 20 ) / 6;
            					// 	$tableFooter = array(
              			// 			'columnName'          => array('Prepared By', '---------------', '------------------------------', '-------------------------------------------', '-------------------', '----------------'),
              			// 			'columnWidth'         => array($cellWidth-10, $cellWidth-6, $cellWidth+7, $cellWidth+15, $cellWidth, $cellWidth-4),
              			// 			'columnFontSize'      => 8,
              			// 			'columnFontWeight'    => '',
              			// 			'columnHeight'        => 3, //set column padding
              			// 			'columnAlign'         => 'C', //options center, left, right
              			// 			'columnVerticalAlign' => 'middle' // middle, top, bottom
	            				// 	);
	            				// 	$this->smartRow($tableFooter, true);
	            				// 	//$this->Ln(2);
	            					
	            				// 	$tableFooter = array(
	              		// 				'columnName'          => array($createdUserName, 'Checked By', 'Asst. Manager / Manager (Merchandising)', 'G.M. (Merchandising & Marketing)', $purchase, 'Approved By'),
	              		// 				'columnWidth'         => array($cellWidth-10, $cellWidth-6, $cellWidth+7, $cellWidth+15, $cellWidth, $cellWidth-4),
	              		// 				'columnFontSize'      => 8,
	              		// 				'columnFontWeight'    => '',
	              		// 				'columnHeight'        => 3, //set column padding
	              		// 				'columnAlign'         => 'C', //options center, left, right
	              		// 				'columnVerticalAlign' => 'top' // middle, top, bottom
	            				// 	);
	            				// 	$this->smartRow($tableFooter);
            					// }
            					$this->Ln(4.4);
            					$this->SetFont('Times','',7);
            					$this->Cell($this->GetPageWidth() - 15,9,'Page '.$this->PageNo().'/{nb}',0,0,'R');
            					$this->Ln(1);
            					$this->SetTextColor(226, 226, 226);
            					$this->SetFont('Arial','',18);
            					$this->TextWithDirection(6, $this->GetPageHeight() - 10, 'www.fakirknit.com', 'U');
            					$this->SetTextColor(0, 0, 0);
            					$this->SetFont('Times','BU',9);
            					$this->Write(7, 'Registered Office');
            					$this->Ln(5);
            					$this->SetX(0);
            					$this->SetFont('Times','',7);
            					$tableFooter = array(
              						'columnName'          => array('90/1, Motijheel C/A, City Center, Level-28 B, Fl-29, Dhaka-1000, Bangladesh. Tel: +88-02-55110921-4, 09678005006-7. Fax: +88-02-9569852', 'E-mail : fklinfo@fakirgroup.com'),
              						'columnWidth'         => array(169, 41),
              						'columnBackground'    => array(array('r'=> 245, 'g'=>131, 'b' => 34), array('r'=> 43, 'g'=>43, 'b' => 43)),
              						'textColour'          => array(array('r' => 255, 'g' => 255, 'b' => 255), array('r' => 255, 'g' => 255, 'b' => 255)),
              						'columnFontSize'      => 8.1,
              						'columnFontWeight'    => '',
              						'columnHeight'        => 5.5, //set column padding
              						'columnAlign'         => 'L', //options center, left, right
              						'columnVerticalAlign' => 'middle' // middle, top, bottom
            					);
            					$this->smartRow($tableFooter);
            					$this->Ln(0);        
            					$this->footerset[$this->page] = true;
          				}
        			}
      			}
				//end of class

      			$pdf = new PDF();
      			$pdf->SetTitle('('.$masterData[0]['VPONUMBER'].') Work Order Details');
      			$pdf->AliasNbPages();
      			$pdf->AddPage('P', 'A4');
      			$pdf->Ln(2);
      			if(!empty($masterData[0]['VORDERDETAILS'])):
      				$pdf->SetFont('Times','b',8.5);
      				$pdf->SetDrawColor(100,100,100);
      				$pdf->SetFillColor(222, 222, 222);
      				$pdf->SetTextColor(0, 0, 0);
      				$pdf->Cell($pdf->GetPageWidth() - 20, 4.5, 'Order Details', 0, 1, 'L', true );
      				$pdf->Ln(.5);
      				$pdf->SetFont('Times','',8.5);
      				$pdf->MultiCell($pdf->GetPageWidth() - 20, 4.3, htmlspecialchars_decode($masterData[0]['VORDERDETAILS'],ENT_QUOTES), 0);
      				$pdf->Ln(2);
      			endif;
      			$numberofTable = $accessoriesModel->getData("SELECT itemtable.nid, itemtable.nworkordermasterid, itemtable.ntotalqty, itemtable.ntotalgarmentsqty, itemtable.ntotalgarmentsqtywithextra, itemtable.vaddition, itemtable.vconvertion, (SELECT vname FROM accessories_goods WHERE nid = itemtable.ngoodsid) AS itemname, itemtable.vordernumber, itemtable.vpnnumber, itemtable.vcolumnname, itemtable.vqtyunit, itemtable.vgridtype, itemtable.vsizename FROM accessories_workorderitems itemtable WHERE itemtable.nworkordermasterid = $id ORDER BY itemtable.nid ASC");
                $itemNameMultiple = array();
      			if(is_array($numberofTable)):
        			foreach ($numberofTable as $key => $value):
        				array_push($itemNameMultiple, $value['ITEMNAME']);
          				$tableId = $value['NID'];
          				$tableHeader = array(
                       				'columnName'          => array('Name of Item', $value['ITEMNAME']),
                       				'columnWidth'         => array(21, 169),
                       				'columnBackground'    => array(array('r'=> 54, 'g'=>52, 'b' => 52), array('r'=> 222, 'g'=>222, 'b' => 222)),
                       				// 'columnBorder'        => array('r' => 0, 'g' => 0, 'b' => 0),
                       				'textColour'          => array(array('r' => 255, 'g' => 255, 'b' => 255), array('r' => 0, 'g' => 0, 'b' => 0)),
                       				'columnFontSize'      => 8.5,
                       				'columnFontWeight'    => 'b',
                       				'columnHeight'        => 4.5, //set column padding
                       				'columnAlign'         => 'L', //options center, left, right
                       				'columnVerticalAlign' => 'middle' // middle, top, bottom
                     			);
          				$pdf->smartRow($tableHeader);
          				$pdf->Ln(5.5);
          				$customColumnSelector = array();
          				$tableColumn = array();
          				$tableColumnWidth = array();
          				$columnBackground = array();
          				$textColour = array();
          				if(!empty($value['VORDERNUMBER'])):
            					array_push($tableColumn, 'Order No.');
          				endif;
          				if(!empty($value['VPNNUMBER'])):
            					array_push($tableColumn, 'PN No.');
  				        endif;
          				$customColumnWidth = array();
          				$sizeExplode = array();
          				$customColumn = explode(',', $value['VCOLUMNNAME']);
          				$holdColumn = array();
          				foreach ($customColumn as $columnKey => $column):
            					if($column == 'Size Name'):
            					array_push($customColumnSelector, 'customcolumn.vcolumn'.($columnKey+1));
            					array_push($holdColumn, trim($column));
              
            					elseif($column == 'Lot No.'):
              						array_push($customColumnWidth, 8);
              						array_push($customColumnSelector, 'customcolumn.vcolumn'.($columnKey+1));
              						array_push($tableColumn, trim($column));
            					elseif($column == 'Kimball No.'):
              						array_push($tableColumn, trim($column));
              						array_push($customColumnSelector, 'customcolumn.vcolumn'.($columnKey+1));
              						array_push($customColumnWidth, 13);
              						// array_push($customColumnWidth, 12);
            					else:
              						array_push($customColumnSelector, 'customcolumn.vcolumn'.($columnKey+1));
             						array_push($tableColumn, trim($column));
            					endif;
          				endforeach;
          				// print_r($tableColumn);
          				if(count($holdColumn) > 0 ):
          					array_push($tableColumn, trim($holdColumn[0]));
          				endif;
          				$minusColum = 0;
          				$minusWidth = 0;
          				if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
							if($AOP[0]['ITEMNAME']=='Yarn'){
								array_push($tableColumn, 'Yarn Qty.');
								$minusColum += 1;
								$minusWidth += 15;
							}
							elseif($AOP[0]['ITEMNAME']=='Fabric'){
								array_push($tableColumn, 'Fabric Qty.');
								$minusColum += 1;
								$minusWidth += 15;
							}
							elseif($AOP[0]['ITEMNAME']=='Foreign Fabric'){
								array_push($tableColumn, 'Fabric Qty.');
								$minusColum += 1;
								$minusWidth += 15;
							}
							else{
								array_push($tableColumn, 'Garments Qty.');
								$minusColum += 1;
								$minusWidth += 15;
							}
          				endif;
          				if($value['VGRIDTYPE'] == 'colornsize'):
        					$sizeExplode = explode(',', $value['VSIZENAME']);
        					foreach ($sizeExplode as $sizekey => $size):
          						array_push($customColumnWidth, 9.5);
          						array_push($tableColumn, trim($size));
        					endforeach;
          				endif;
          				if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTYWITHEXTRA', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
            					array_push($tableColumn, trim($value['VADDITION']));
            					$minusColum += 1;
            					$minusWidth += 15;
          				endif;
          				if($workorderOpt->checkColumnExist($tableId, 'NCONVERTERQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
            					array_push($tableColumn, trim($value['VCONVERTION']));
            					$minusColum += 1;
            					$minusWidth += 15;
         				 endif;
          				if($workorderOpt->checkColumnExist($tableId, 'NROWTOTALQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
            					array_push($tableColumn, 'W.O. Total Req. Qty.');
            					$minusColum += 1;
            					$minusWidth += 18.5;
            					array_push($tableColumn, 'Unit');
            					$minusColum += 1;
           					$minusWidth += 8;
					endif;
          				if($workorderOpt->checkColumnExist($tableId, 'VREMARKS', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
            					array_push($tableColumn, 'Remarks');
            					// $minusColum += 1;
            					// $minusWidth += 45;
          				endif;
          				// print_r($tableColumn);
          				// print_r( $customColumnWidth);
          				$currentWidth =  (190-$minusWidth - array_sum($customColumnWidth)) / (count($tableColumn) - $minusColum - count($customColumnWidth));
          				//echo $currentWidth;


          				foreach ($tableColumn as $columWidth):
            					array_push($columnBackground, array('r'=> 222, 'g'=>222, 'b' => 222));
            					array_push($textColour, array('r'=> 0, 'g'=>0, 'b' => 0));
            					if($columWidth == 'Unit'):
              						array_push($tableColumnWidth, 8);
            					elseif($columWidth == 'Garments Qty.'):
             						array_push($tableColumnWidth, 15);
								elseif($columWidth == 'Yarn Qty.'):
									array_push($tableColumnWidth, 15);
								elseif($columWidth == 'Fabric Qty.'):
									array_push($tableColumnWidth, 15);
            					elseif($columWidth == trim($value['VADDITION'])):
              						array_push($tableColumnWidth, 15);
            					elseif($columWidth == trim($value['VCONVERTION'])):
              						array_push($tableColumnWidth, 15);
            					elseif($columWidth == 'W.O. Total Req. Qty.'):
              						array_push($tableColumnWidth, 18.5);
            					elseif($columWidth == 'Lot No.'):
              						array_push($tableColumnWidth, 8);
            					elseif($columWidth == 'Kimball No.'):
              						array_push($tableColumnWidth, 13);
              						// array_push($tableColumnWidth, 12);
            					elseif(in_array($columWidth, $sizeExplode)):
              						array_push($tableColumnWidth, 9.5);
           					// elseif($columWidth == 'Composition'):
            						// array_push($tableColumnWidth, 35);
            					else:
              						array_push($tableColumnWidth, $currentWidth);
            					endif;
          				endforeach;
          				$tableHeader = array(
                				'columnName'          => $tableColumn,
                				'columnWidth'         => $tableColumnWidth,
                				'columnBackground'    => $columnBackground,
                				'columnBorder'        => array('r'=> 0, 'g'=>0, 'b' => 0),
                				'textColour'          => $textColour,
                				'columnFontSize'      => 8,
                				'columnFontWeight'    => 'b',
                				'columnHeight'        => 3.9, //set column padding
                				// 'columnHeight'        => 3.9, //set column padding
                				'columnAlign'         => 'C', //options center, left, right
                				'columnVerticalAlign' => 'middle' // middle, top, bottom
                 			);
          				$pdf->smartRow($tableHeader, true);
          				// $pdf->Ln(6);
          				$customColumnName = count($customColumnSelector) > 0 ? ','.implode(',', $customColumnSelector) : '';
          				$gridSql = "SELECT rowdata.nid, rowdata.nrowtotalqty, rowdata.nrowgarmentsqty, rowdata.nrowgarmentsqtywithextra, rowdata.nconverterqty, rowdata.vremarks $customColumnName ";
          				if($value['VGRIDTYPE'] == 'colornsize'):
            					$gridSql .= ",LISTAGG(sizewiseqty.nrequiredqty, ',') WITHIN GROUP (ORDER BY sizewiseqty.nid ASC) AS QTY";
          				endif;
            				$gridSql .= " FROM accessories_workorderitemdata rowdata LEFT JOIN accessories_customcolumnvalue customcolumn ON customcolumn.ngriditemdataid = rowdata.nid";
          				if($value['VGRIDTYPE'] == 'colornsize'):
            					$gridSql .= " LEFT JOIN accessories_workordersizeqty sizewiseqty ON sizewiseqty.nworkorderitemsdataid = rowdata.nid";
          				endif;
          				$gridSql .= " WHERE rowdata.nworkorderitemsid = $tableId GROUP BY rowdata.nid, rowdata.nrowtotalqty, rowdata.nrowgarmentsqty, rowdata.nrowgarmentsqtywithextra, rowdata.nconverterqty, rowdata.vremarks $customColumnName ORDER BY rowdata.nid ASC";
          				// echo $gridSql;
          				$gridData = $accessoriesModel->getData($gridSql);
          				// $gridData = $accessoriesModel->getData("SELECT rowdata.nid, rowdata.nrowtotalqty, rowdata.nrowgarmentsqty, rowdata.nrowgarmentsqtywithextra, rowdata.nconverterqty, rowdata.vremarks $customColumnName FROM accessories_workorderitemdata rowdata LEFT JOIN accessories_customcolumnvalue customcolumn ON customcolumn.ngriditemdataid = rowdata.nid WHERE rowdata.nworkorderitemsid = $tableId ORDER BY rowdata.nid ASC");
          				$sizeDataTemp = array();
          				foreach ($gridData as $keyParent => $gridValue):
            					$tempArr = array();
            					$tempAllign = array();
            					$tempFontSize = array();
            					if(!empty($value['VORDERNUMBER'])):
             						array_push($tempArr, htmlspecialchars_decode($value['VORDERNUMBER'], ENT_QUOTES));
              						array_push($tempAllign, 'C');
              						array_push($tempFontSize, 7.5);
            					endif;
            					if(!empty($value['VPNNUMBER'])):
              						array_push($tempArr, htmlspecialchars_decode($value['VPNNUMBER'], ENT_QUOTES));
              						array_push($tempAllign, 'C');
              						array_push($tempFontSize, 7.5);
            					endif;
            					$arrayCellSpan = array();
            					if(count($customColumnSelector) > 0):
            						$holdValue = array();
              						for($i = 1; $i <= count($customColumnSelector); $i++):
                						if($customColumn[$i-1] == 'Color Name'):
                  							array_push($tempArr, htmlspecialchars_decode(trim($gridValue['VCOLUMN'.$i]), ENT_QUOTES));
                  							array_push($tempAllign, 'L');
                  							array_push($tempFontSize, 7.5);
                						elseif($customColumn[$i-1] == 'Color'):
                  							array_push($tempArr, htmlspecialchars_decode(trim($gridValue['VCOLUMN'.$i]), ENT_QUOTES));
                  							array_push($tempAllign, 'L');
                  							array_push($tempFontSize, 7);
                						elseif($customColumn[$i-1] == 'Size Name'):
                							array_push($holdValue, htmlspecialchars_decode(trim($gridValue['VCOLUMN'.$i]), ENT_QUOTES));
                  							
                  							// 
                  							// 
                						else:
                  							array_push($tempArr, htmlspecialchars_decode(trim($gridValue['VCOLUMN'.$i]), ENT_QUOTES));
                  							array_push($tempAllign, 'C');
                  							array_push($tempFontSize, 6.5);
                						endif;
              						endfor;
            					endif;
            					$footerCol1 = array();
            					$footerBg = array();
            					$footerAlign = array();
            					$footerFont = array();
            					if(count($holdValue) > 0):
            						array_push($tempArr, $holdValue[0]);
            						array_push($tempAllign, 'C');
									array_push($tempFontSize, 7.5);
            					endif;
            					if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
              						array_push($tempArr,  number_format($gridValue['NROWGARMENTSQTY']));
              						array_push($tempAllign, 'R');
              						array_push($tempFontSize, 7.5);
              						array_push($arrayCellSpan, 15);
              						array_push($footerCol1, number_format($value['NTOTALGARMENTSQTY']));
              						array_push($footerBg, array('r'=> 222, 'g'=>222, 'b' => 222));
              						array_push($footerAlign, 'R');
              						array_push($footerFont, 7.4);
            					endif;
            					if(!empty($gridValue['QTY'])):
              						$explodeQty = explode(',', $gridValue['QTY']);
              						foreach ($explodeQty as $key => $sizeQty):
               							array_push($tempArr,  number_format($sizeQty));
                						array_push($tempAllign, 'R');
                						array_push($tempFontSize, 6.5);
                						$sizeDataTemp[$key] = isset($sizeDataTemp[$key]) ? $sizeDataTemp[$key] + $sizeQty : $sizeQty;
              						endforeach;
            					endif;
            					if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTYWITHEXTRA', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
              						array_push($tempArr, number_format($gridValue['NROWGARMENTSQTYWITHEXTRA']));
              						array_push($tempAllign, 'R');
              						array_push($tempFontSize, 7.5);
              						array_push($arrayCellSpan, 15);
              						array_push($footerCol1, number_format($value['NTOTALGARMENTSQTYWITHEXTRA']));
              						array_push($footerBg, array('r'=> 222, 'g'=>222, 'b' => 222));
              						array_push($footerAlign, 'R');
              						array_push($footerFont, 7.4);
            					endif;
            					if($workorderOpt->checkColumnExist($tableId, 'NCONVERTERQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
              						array_push($tempArr, number_format($gridValue['NCONVERTERQTY'], 3));
              						array_push($tempAllign, 'C');
              						array_push($tempFontSize, 7.5);
              						array_push($arrayCellSpan, 15);
              						array_push($footerCol1, '');
              						array_push($footerBg, array('r'=> 222, 'g'=>222, 'b' => 222));
              						array_push($footerAlign, 'R');
              						array_push($footerFont, 7.4);
            					endif;
            					if($workorderOpt->checkColumnExist($tableId, 'NROWTOTALQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
              						array_push($tempArr, (strtolower(htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)) == "yrds" || strtolower(htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)) == "gg" ? number_format($gridValue['NROWTOTALQTY'], 2) : number_format($gridValue['NROWTOTALQTY'])));
              						array_push($tempAllign, 'R');
              						array_push($tempFontSize, 7.5);
              						array_push($tempArr, htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES));
              						array_push($tempAllign, 'L');
             						array_push($tempFontSize, 7);
              						array_push($arrayCellSpan, 18.5);
              						array_push($footerCol1, (strtolower(htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)) == "yrds" || strtolower(htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)) == "gg"  ? number_format($value['NTOTALQTY'], 2) : number_format($value['NTOTALQTY'])));
              						array_push($footerCol1, str_replace("'", "", htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)));
              						array_push($arrayCellSpan, 8);
              						array_push($footerBg, array('r'=> 222, 'g'=>222, 'b' => 222));
              						array_push($footerAlign, 'R');
              						array_push($footerFont, 7.4);
              						array_push($footerBg, array('r'=> 222, 'g'=>222, 'b' => 222));
              						array_push($footerAlign, 'L');
              						array_push($footerFont, 7.2);
            					endif;
            					if($workorderOpt->checkColumnExist($tableId, 'VREMARKS', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
              						array_push($tempArr, htmlspecialchars_decode(trim($gridValue['VREMARKS']), ENT_QUOTES));
              						array_push($tempAllign, 'L');
              						array_push($tempFontSize, 7);
              						array_push($arrayCellSpan, $currentWidth);
              						array_push($footerCol1, '');
              						array_push($footerBg, array('r'=> 222, 'g'=>222, 'b' => 222));
              						array_push($footerAlign, 'L');
              						array_push($footerFont, 7.2);
            					endif; 
            					$pdf->SetAutoPageBreak(false);
            					if($pdf->GetY() > $pdf->GetPageHeight() - 35):
              						$pdf->AddPage();
              						$pdf->Ln(2);
              						$tableHeader = array(
                						'columnName'          => $tableColumn,
                						'columnWidth'         => $tableColumnWidth,
                						'columnBackground'    => $columnBackground,
                						'columnBorder'        => array('r'=> 0, 'g'=>0, 'b' => 0),
                						'textColour'          => $textColour,
                						'columnFontSize'      => 8,
                						'columnFontWeight'    => 'b',
                						'columnHeight'        => 3.9, //set column padding
                						'columnAlign'         => 'C', //options center, left, right
                						'columnVerticalAlign' => 'middle' // middle, top, bottom
                 					);
              						$pdf->smartRow($tableHeader, true);
            					endif;
            					$tableRow = array(
              						'columnName'          => $tempArr,
              						'columnWidth'         => $tableColumnWidth,
              						'columnBorder'        => array('r'=> 0, 'g'=>0, 'b' => 0),
              						'columnFontSize'      => $tempFontSize,
              						'columnFontWeight'    => '',
              						'columnHeight'        => 4.1, //set column padding
              						'columnAlign'         => $tempAllign, //options center, left, right
              						'columnVerticalAlign' => 'middle' // middle, top, bottom
               					);
            					$pdf->smartRow($tableRow, true);
            					//$pdf->Ln(4.5);
          				endforeach;
          				$mergeFooterCol = array();
          				$mergeCellSpan = array();
          				$mergeFooterBg = array();
          				$mergeFooterAlign = array();
          				$mergeFooterFont = array();
          				foreach ($sizeDataTemp as $key => $value):
            					array_push($mergeCellSpan, 9.5);
            					array_push($mergeFooterCol, number_format($value));
            					array_push($mergeFooterBg, array('r'=> 222, 'g'=>222, 'b' => 222));
            					array_push($mergeFooterAlign, 'R');
            					array_push($mergeFooterFont, 6.5);
          				endforeach;
         				//echo 190 - $minusWidth   - ($currentWidth * (count($arrayCellSpan) - $minusColum ));
          				$arrayCellSpan = array_merge($mergeCellSpan, $arrayCellSpan);
          				array_unshift($arrayCellSpan, 190 - ($minusWidth + (count($sizeDataTemp) * 9.5))  - ($currentWidth * (count($arrayCellSpan) - ($minusColum + count($sizeDataTemp)))));
          				$footerCol1 = array_merge($mergeFooterCol, $footerCol1); 
          				array_unshift($footerCol1, 'Quantity Grand Total');
          				$footerBg = array_merge($mergeFooterBg, $footerBg);
          				array_unshift($footerBg, array('r'=> 222, 'g'=>222, 'b' => 222));
          				$footerAlign = array_merge($mergeFooterAlign, $footerAlign);
          				array_unshift($footerAlign, 'R');
          				$footerFont = array_merge($mergeFooterFont, $footerFont);
          				array_unshift($footerFont, 7.4);
          				$tableFooter = array(
            					'columnName'          => $footerCol1,
            					'columnWidth'         => $arrayCellSpan,
            					'columnBackground'    => $footerBg,
            					'columnBorder'        => array('r'=> 0, 'g'=>0, 'b' => 0),
            					'columnFontSize'      => $footerFont,
            					'columnFontWeight'    => 'b',
            					'columnHeight'        => 4.5, //set column padding
            					'columnAlign'         => $footerAlign, //options center, left, right
            					'columnVerticalAlign' => 'middle' // middle, top, bottom
            				);
          				$pdf->smartRow($tableFooter, true);
          				$attachment = $accessoriesModel->getData("SELECT bimage, vfileformate FROM accessories_images WHERE nworkorderitemid = $tableId");
          				if(is_array($attachment)):
            					if($pdf->GetY() > $pdf->GetPageHeight() - 100):
              						$pdf->AddPage();
              						$pdf->Ln(1);
            					endif;
            					$pdf->Ln(1);
            					$tableHeader = array(
              						'columnName'          => array('Attachment(s):'),
              						'columnWidth'         => array(23),
              						'columnFontSize'      => 8.5,
              						'columnFontWeight'    => 'bu',
              						'columnHeight'        => 4.5, //set column padding
              						'columnAlign'         => 'L', //options center, left, right
              						'columnVerticalAlign' => 'middle' // middle, top, bottom
            					);
          					$pdf->smartRow($tableHeader);
          					$pdf->Ln(6);
            					$tempType = array();
            					$tempImage = array();
            					foreach ($attachment as $key => $images):
              						$img = $images['BIMAGE']->load();
              						array_push($tempType, str_replace('image/', '', $images['VFILEFORMATE']));
              						array_push($tempImage, "data:".$images['VFILEFORMATE'].";base64,".base64_encode($img));       
            					endforeach;
            					$pdf->imageRow($tempImage, $tempType);
								
          				endif;
          				$pdf->Ln(3);
        			endforeach;
      			endif;
      			$pdf->SetFont('Times','b',8.5);
      			$pdf->SetDrawColor(100,100,100);
      			$pdf->SetFillColor(222, 222, 222);
      			$pdf->SetTextColor(0, 0, 0);
      			$pdf->Ln(1);
      			if(!empty($masterData[0]['VEXTRANOTES'])):
        			$pdf->SetAutoPageBreak(false);
        			if($pdf->GetY() > $pdf->GetPageHeight() - 40):
          				$pdf->AddPage();
        			endif;
        			$pdf->Cell($pdf->GetPageWidth() - 20, 4.5, 'Terms & Condition', 0, 1, 'L', true );
        			$pdf->Ln(.5);
        			$pdf->SetFont('Times','',8.5);
        			$pdf->MultiCell($pdf->GetPageWidth() - 20, 4.3, htmlspecialchars_decode($masterData[0]['VEXTRANOTES'], ENT_QUOTES), 0);
        			$pdf->Ln(3.5);
      			endif;
      			$tableFooter = array(
        			'columnName'          => array('Delivery Date', $masterData[0]['VDELIVERYDATE']),
        			'columnWidth'         => array(21, 18),
        			'columnBackground'    => array(array('r'=> 54, 'g'=>52, 'b' => 52), array('r'=> 222, 'g'=>222, 'b' => 222)),
        			'textColour'          => array(array('r' => 255, 'g' => 255, 'b' => 255), array('r' => 0, 'g' => 0, 'b' => 0)),
        			//'columnBorder'        => array('r'=> 0, 'g'=>0, 'b' => 0),
        			'columnFontSize'      => 8.5,
        			'columnFontWeight'    => 'b',
        			'columnHeight'        => 4.5, //set column padding
        			'columnAlign'         => 'C', //options center, left, right
        			'columnVerticalAlign' => 'middle' // middle, top, bottom
        		);

      			$pdf->smartRow($tableFooter);
      			$pdf->Ln(4);
      			$pdf->SetCreator("Md. Jakir Hosen");
      			$pdf->SetAuthor($createdUserName);
      			$pdf->SetKeywords("Accessories workorder");
      			$pdf->SetSubject("Workorder / W.O-".$masterData[0]['NID']);
      			$pdf->output('', strtolower(str_replace(' ', '-', implode('-',$itemNameMultiple)).'-'.$masterData[0]['VPONUMBER']).'-'.strtotime(date("Y-m-d H:i:s")).'.pdf');
    		else:
      			$auth->redirect404();
    		endif;
  	else:
    		$auth->redirect403();
  	endif;
else:
  	$auth->loginPageRedirect();
endif;
?>