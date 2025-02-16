<?php
require_once '../ini.php';
use accessories\accessoriescrud;

use accessories\workorderoperation;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

if(!$auth->authUser()):
    $auth->loginPageRedirect();
else:
	$workorderOpt = new workorderoperation($db->con);
	$accessoriesModel = new accessoriescrud($db->con);

	if(isset($_POST['formName'])):

		if($_POST['formName'] == 'ordersearch'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$ordernumber = $_POST['ordernumber'];
				$fklnumber = $_POST['ordernumber'];
				if($_POST['ordertype'] == 'Order number'):
					if($accessoriesModel->checkDataExistence("SELECT vordernumber FROM erp.mer_vw_orderinfo WHERE vordernumber = '$ordernumber' AND TO_DATE(dshipdate, 'dd/mm/yyyy') >= TRUNC(SYSDATE)") == 'exist'):
						//AND TO_DATE(dshipdate, 'dd/mm/yyyy') >= TRUNC(SYSDATE)
						
						$orderData = $accessoriesModel->getData("SELECT vordernumber, vname, kimballno, LISTAGG(nks_id, ', ') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC) AS ksid, REGEXP_REPLACE(LISTAGG(vseassonname, '##') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^##]+)(##\\1)+', '\\1') AS season, vdeptname, REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(vcountry, ',') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^,]+)(,\\1)+', '\\1') AS country, REGEXP_REPLACE(LISTAGG(vsizebreakdown, '##') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^##]+)(##\\1)+', '\\1') AS sizebreakdown FROM erp.mer_vw_orderinfo LEFT JOIN erp.mer_ks_master ON nordercode = norderid WHERE vordernumber = '$ordernumber' AND (SELECT max(TO_DATE(dshipdate, 'dd/mm/yyyy')) FROM erp.mer_vw_orderinfo WHERE vordernumber = '$ordernumber') >= TRUNC(SYSDATE) GROUP BY vordernumber, vname, kimballno, vdeptname");
						//AND (SELECT max(TO_DATE(dshipdate, 'dd/mm/yyyy')) FROM erp.mer_vw_orderinfo WHERE vordernumber = '$ordernumber') >= TRUNC(SYSDATE)
						$response = array(
							'status' => 'success',
							'data'   => $orderData
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => 'notfound'
						);
						echo json_encode($response);
				    endif;
				elseif($_POST['ordertype'] == 'FKL number'):
					//echo "SELECT nks_id FROM erp.mer_vw_ks_information WHERE nks_id IN ($fklnumber)";
					if($accessoriesModel->checkDataExistence("SELECT nks_id FROM erp.mer_ks_master WHERE nks_id IN ($fklnumber)") == 'exist'):
						$fklData = $accessoriesModel->getData("SELECT vordernumber, vname, REGEXP_REPLACE(LISTAGG(kimballno, ',') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(nks_id, ', ') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC) AS ksid, REGEXP_REPLACE(LISTAGG(vseassonname, '##') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^##]+)(##\\1)+', '\\1') AS season, vdeptname, REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(vcountry, ',') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^,]+)(,\\1)+', '\\1') AS country, REGEXP_REPLACE(LISTAGG(vsizebreakdown, '##') WITHIN GROUP (ORDER BY lpad(vlot, 10), vpart ASC), '([^##]+)(##\\1)+', '\\1') AS sizebreakdown FROM erp.mer_ks_master LEFT JOIN erp.mer_vw_orderinfo ON norderid = nordercode WHERE nks_id IN ($fklnumber) GROUP BY vordernumber, vname, vdeptname");
						$response = array(
							'status' => 'success',
							'data'   => $fklData
						);
						echo json_encode($response);	
					else:
						$response = array(
							'status' => 'notfound'
						);
						echo json_encode($response);
				    endif;
				else:
					$response = array(
							'status' => 'notfound'
					);
					echo json_encode($response);
				endif;
			else:
				$response = array(
					'status' => 'csrfmissing'
				);
				echo json_encode($response);
			endif;

		//goodsfinder//
		elseif($_POST['formName'] == 'goodsfinder'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$subgroupId = $_POST['subgroupid'];
				$goods = $accessoriesModel->getData("SELECT  goods.nid, goods.vname, goods.vparameters, unit.vnameshort FROM ACCESSORIES_GOODS goods LEFT JOIN ACCESSORIES_QUANTITYUNIT unit ON unit.nid = goods.nqtyunitid WHERE nsubgroupid IN ($subgroupId) ORDER BY nsubgroupid ASC");
				if(is_array($goods)):
					$response = array(
						'status' => 'success',
						'data'   => $goods
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => 'tablempty'
					);
				    echo json_encode($response);
				endif;
			else:
				$response = array(
					'status' => 'csrfmissing'
				);
				echo json_encode($response);
			endif;

		//Preset Option
		elseif($_POST['formName'] == 'presetdata'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$fklNo = $_POST['fklNumber'];
				if($_POST['preSetOpton'] == 'colorwise'):
					$response = $workorderOpt->colorWiseData($fklNo);
					echo json_encode($response);
				elseif($_POST['preSetOpton'] == 'sizewise'):
					
					$response = $workorderOpt->sizeWiseQty($fklNo);
					echo json_encode($response);
					
				elseif($_POST['preSetOpton'] == 'color&sizewise'):
					
					$response = $workorderOpt->colorAndSizeWiseData($fklNo);
					echo json_encode($response);
					
				elseif($_POST['preSetOpton'] == 'kimball&sizewise'):
					
					$response = $workorderOpt->kimballColorSizeWiseData($fklNo);
					echo json_encode($response);

				elseif($_POST['preSetOpton'] == 'kimballsizewise'):
					
					$response = $workorderOpt->kimballColorWiseData($fklNo);
					echo json_encode($response);

				endif;
			else:
				$response = array(
					'status' => 'csrfmissing'
				);
				echo json_encode($response);
			endif;

		//Workorder insert//
		elseif($_POST['formName'] == 'workorderpublish'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$masterId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERMASTER', 'NID');
				$loggedId = $auth->loggedUserId();
				if($_POST['masterdata'][0]['VSTATUS'] == 'publish'):
					$_POST['masterdata'][0]['VPONUMBER'] = $workorderOpt->purchaseOrderNumber($auth->loggedUser()['prefix'], $loggedId);
					$_POST['masterdata'][0]['VISSUE'] = '1';
				endif;				
				$_POST['masterdata'][0]['NID'] = $masterId;
				$_POST['masterdata'][0]['VCREATEDUSER'] = $loggedId;
				$_POST['masterdata'][0]['VCREATEDAT'] = date('d-m-Y');
				$_POST['masterdata'][0]['VTODATE'] = date('d-m-Y');
				$_POST['masterdata'][0]['VLASTUPDATEDUSER'] = $loggedId;
				$_POST['masterdata'][0]['VLASTUPDATEDAT'] = date('d-m-Y');
				if($_POST['masterdata'][0]['VSTATUS'] == 'publish'):
					$_POST['masterdata'][0]['VPUBLISHEDUSER'] = $loggedId;
					$_POST['masterdata'][0]['VPUBLISHEDAT'] = date('d-m-Y');
				endif;
				$accessoriesModel->insertData('ACCESSORIES_WORKORDERMASTER', $_POST['masterdata'][0]);

				$tempItemId = array();
				foreach ($_POST['itemsdata'] as $key => $value):
					$itemData = $value['itemData'];
					$customData = $value['customData'];
					$images = isset($value['images']) ? $value['images'] : '';
					$sizeData = isset($value['sizeWiseQty']) ? $value['sizeWiseQty'] : '';
					$settingData = $value['settingData'];
					unset($value['itemData']);
					unset($value['customData']);
					unset($value['sizeWiseQty']);
					unset($value['settingData']);
					$itemTableId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERITEMS', 'NID');
					array_push($tempItemId, $itemTableId);
					$value['NID'] = $itemTableId;
			        $value['NWORKORDERMASTERID'] = $masterId;
			        // echo "<pre>";
			        // print_r($value);
			        // echo "</pre>";
					$accessoriesModel->insertData('ACCESSORIES_WORKORDERITEMS', $value);
					foreach ($itemData as $itemDatakey => $item):
						$item['NWORKORDERITEMSID'] = $itemTableId;
						$itemDataId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERITEMDATA', 'NID');
						$customColumnId = $accessoriesModel->lastRowId('ACCESSORIES_CUSTOMCOLUMNVALUE', 'NID');
						$item['NID'] = $itemDataId;
						$accessoriesModel->insertData('ACCESSORIES_WORKORDERITEMDATA', $item);
						$customColumns = array();
						$customColumns['NID'] = $customColumnId;
						$customColumns['NGRIDITEMDATAID'] = $itemDataId;
						foreach ($customData[$itemDatakey] as $customKey => $customcolumn):
							$customColumns['VCOLUMN'.($customKey+1)] = $customcolumn;
						endforeach;
						$accessoriesModel->insertData('ACCESSORIES_CUSTOMCOLUMNVALUE', $customColumns);
						if(is_array($sizeData)):
							$size = array();
					    	foreach ($sizeData[$itemDatakey] as $sizeName => $sizeQty):
					    		$size['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERSIZEQTY', 'NID');
					    		$size['VSIZENAME'] = str_replace('size-', '', $sizeName);
					    		$size['NREQUIREDQTY'] = $sizeQty;
					    		$size['NWORKORDERITEMSDATAID'] = $itemDataId;
					    		$accessoriesModel->insertData('ACCESSORIES_WORKORDERSIZEQTY', $size);
					        endforeach;
				    	endif;
					endforeach;
					$settingData['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERFORMSETTING', 'NID');
					$settingData['NWORKORDERITEMSID'] = $itemTableId;
					$accessoriesModel->insertData('ACCESSORIES_WORKORDERFORMSETTING', $settingData);
					// echo "<pre>";
					// print_r($settingData);
					// echo "</pre>";
				endforeach;
				if($_POST['masterdata'][0]['VSTATUS'] == 'publish'):
					$accessoriesModel->accessoriesHistory("Work order created.", "$loggedId", $masterId);
					$accessoriesModel->accessoriesHistory("Work order published.", "$loggedId", $masterId);
				else:
					$accessoriesModel->accessoriesHistory("Work order created.", "$loggedId", $masterId);
				endif;
				$response = array(
					'status' => 'success',
					'masterid' => $masterId,
					'itemid' => implode(',', $tempItemId)
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'csrfmissing'
				);
				echo json_encode($response);
			endif;

		//Workorder update//
		elseif($_POST['formName'] == 'workorderupdate'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				
				$loggedId = $auth->loggedUserId();
				$masterId = $_POST['masterdata'][0]['NID'];
				$_POST['masterdata'][0]['VLASTUPDATEDUSER'] = $loggedId;
				$_POST['masterdata'][0]['VLASTUPDATEDAT'] = date('d-m-Y');
				if($_POST['masterdata'][0]['VSTATUS'] == 'publish'):
					$_POST['masterdata'][0]['VPUBLISHEDUSER'] = $loggedId;
					$_POST['masterdata'][0]['VPUBLISHEDAT'] = date('d-m-Y');
				endif;
				//if($accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERMASTER", "nid = $masterId")):
				    $accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERMASTER", "nid = $masterId");
					$accessoriesModel->insertData('ACCESSORIES_WORKORDERMASTER', $_POST['masterdata'][0]);
				// else:
				// 	$response = array(
			 //            'status' => 'invalidRequest'
			 //        );
			 //        echo json_encode($response);
			 //        die();
				// endif;
				$tempItemId = array();
				$tempItemIdForDelete = array();
				$tempItemDataId = array();
				$getItemIdForDelete = $accessoriesModel->getData("SELECT NID FROM ACCESSORIES_WORKORDERITEMS WHERE NWORKORDERMASTERID = $masterId ORDER BY NID ASC");
				foreach ($getItemIdForDelete as $itemD):
					$itemIdDelete = $itemD['NID'];
					array_push($tempItemIdForDelete, $itemIdDelete);
					$getItemDataIdForDelete = $accessoriesModel->getData("SELECT NID FROM ACCESSORIES_WORKORDERITEMDATA WHERE NWORKORDERITEMSID = $itemIdDelete");
					foreach ($getItemDataIdForDelete as $gridid):
						array_push($tempItemDataId, $gridid['NID']);
					endforeach;
				endforeach;
				$exItemId = implode(',', $tempItemIdForDelete);
				$exGridId = implode(',', $tempItemDataId);

				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERITEMS", "NWORKORDERMASTERID = $masterId");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERITEMDATA", "NWORKORDERITEMSID IN ($exItemId)");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_CUSTOMCOLUMNVALUE", "NGRIDITEMDATAID IN ($exGridId)");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERSIZEQTY", "NWORKORDERITEMSDATAID IN ($exGridId)");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERFORMSETTING", "NWORKORDERITEMSID IN ($exItemId)");
				// $accessoriesModel->deleteSingleRow("ACCESSORIES_IMAGES", "NWORKORDERITEMID IN ($exItemId)");

				foreach ($_POST['itemsdata'] as $key => $value):
					$itemData = $value['itemData'];
					$customData = $value['customData'];
					$images = isset($value['images']) ? $value['images'] : '';
					$sizeData = isset($value['sizeWiseQty']) ? $value['sizeWiseQty'] : '';
					$settingData = $value['settingData'];
					unset($value['itemData']);
					unset($value['customData']);
					unset($value['sizeWiseQty']);
					unset($value['settingData']);
					$itemTableId = $value['NID'];
					array_push($tempItemId, $itemTableId);
					//$value['NID'] = $itemTableId;
			        $value['NWORKORDERMASTERID'] = $masterId;
					$accessoriesModel->insertData('ACCESSORIES_WORKORDERITEMS', $value);
					foreach ($itemData as $itemDatakey => $item):
						$item['NWORKORDERITEMSID'] = $itemTableId;
						$itemDataId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERITEMDATA', 'NID');
						$customColumnId = $accessoriesModel->lastRowId('ACCESSORIES_CUSTOMCOLUMNVALUE', 'NID');
						$item['NID'] = $itemDataId;
						$accessoriesModel->insertData('ACCESSORIES_WORKORDERITEMDATA', $item);
						$customColumns = array();
						$customColumns['NID'] = $customColumnId;
						$customColumns['NGRIDITEMDATAID'] = $itemDataId;
						foreach ($customData[$itemDatakey] as $customKey => $customcolumn):
							$customColumns['VCOLUMN'.($customKey+1)] = $customcolumn;
						endforeach;
						$accessoriesModel->insertData('ACCESSORIES_CUSTOMCOLUMNVALUE', $customColumns);
						if(is_array($sizeData)):
							$size = array();
					    	foreach ($sizeData[$itemDatakey] as $sizeName => $sizeQty):
					    		$size['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERSIZEQTY', 'NID');
					    		$size['VSIZENAME'] = str_replace('size-', '', $sizeName);
					    		$size['NREQUIREDQTY'] = $sizeQty;
					    		$size['NWORKORDERITEMSDATAID'] = $itemDataId;
					    		$accessoriesModel->insertData('ACCESSORIES_WORKORDERSIZEQTY', $size);
					        endforeach;
				    	endif;
					endforeach;
					$settingData['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERFORMSETTING', 'NID');
					$settingData['NWORKORDERITEMSID'] = $itemTableId;
					$accessoriesModel->insertData('ACCESSORIES_WORKORDERFORMSETTING', $settingData);
				endforeach;
				
				$accessoriesModel->accessoriesHistory("Work order updated.", "$loggedId", $masterId);
				$response = array(
					'status' => 'success',
					'masterid' => $masterId,
					'itemid' => implode(',', $tempItemId)
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'csrfmissing'
				);
				echo json_encode($response);
			endif;

		//Workorder new issue//
		elseif($_POST['formName'] == 'workordernewissue'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$masterId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERMASTER', 'NID');
				$loggedId = $auth->loggedUserId();
				$prevMasterId = $_POST['masterdata'][0]['masterid'];
				unset($_POST['masterdata'][0]['masterid']);
				$_POST['masterdata'][0]['NID'] = $masterId;
				$_POST['masterdata'][0]['VCREATEDUSER'] = $loggedId;
				$_POST['masterdata'][0]['VCREATEDAT'] = date('d-m-Y');
				$_POST['masterdata'][0]['VLASTUPDATEDUSER'] = $loggedId;
				$_POST['masterdata'][0]['VLASTUPDATEDAT'] = date('d-m-Y');				
				$_POST['masterdata'][0]['VPUBLISHEDUSER'] = $loggedId;
				$_POST['masterdata'][0]['VPUBLISHEDAT'] = date('d-m-Y');
				$accessoriesModel->dataUpdate('ACCESSORIES_WORKORDERMASTER', array('NISSUESTATUS' => 0), "NID = $prevMasterId");
				$accessoriesModel->insertData('ACCESSORIES_WORKORDERMASTER', $_POST['masterdata'][0]);

				$tempItemId = array();
				foreach ($_POST['itemsdata'] as $key => $value):
					$itemData = $value['itemData'];
					$customData = $value['customData'];
					$images = isset($value['images']) ? $value['images'] : '';
					$sizeData = isset($value['sizeWiseQty']) ? $value['sizeWiseQty'] : '';
					$settingData = $value['settingData'];
					unset($value['itemData']);
					unset($value['customData']);
					unset($value['sizeWiseQty']);
					unset($value['settingData']);
					$itemTableId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERITEMS', 'NID');
					array_push($tempItemId, $itemTableId);
					$value['NID'] = $itemTableId;
			        $value['NWORKORDERMASTERID'] = $masterId;
	
					$accessoriesModel->insertData('ACCESSORIES_WORKORDERITEMS', $value);
					foreach ($itemData as $itemDatakey => $item):
						$item['NWORKORDERITEMSID'] = $itemTableId;
						$itemDataId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERITEMDATA', 'NID');
						$customColumnId = $accessoriesModel->lastRowId('ACCESSORIES_CUSTOMCOLUMNVALUE', 'NID');
						$item['NID'] = $itemDataId;
						$accessoriesModel->insertData('ACCESSORIES_WORKORDERITEMDATA', $item);
						$customColumns = array();
						$customColumns['NID'] = $customColumnId;
						$customColumns['NGRIDITEMDATAID'] = $itemDataId;
						foreach ($customData[$itemDatakey] as $customKey => $customcolumn):
							$customColumns['VCOLUMN'.($customKey+1)] = $customcolumn;
						endforeach;
						$accessoriesModel->insertData('ACCESSORIES_CUSTOMCOLUMNVALUE', $customColumns);
						if(is_array($sizeData)):
							$size = array();
					    	foreach ($sizeData[$itemDatakey] as $sizeName => $sizeQty):
					    		$size['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERSIZEQTY', 'NID');
					    		$size['VSIZENAME'] = str_replace('size-', '', $sizeName);
					    		$size['NREQUIREDQTY'] = $sizeQty;
					    		$size['NWORKORDERITEMSDATAID'] = $itemDataId;
					    		$accessoriesModel->insertData('ACCESSORIES_WORKORDERSIZEQTY', $size);
					        endforeach;
				    	endif;
					endforeach;
					$settingData['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERFORMSETTING', 'NID');
					$settingData['NWORKORDERITEMSID'] = $itemTableId;
					$accessoriesModel->insertData('ACCESSORIES_WORKORDERFORMSETTING', $settingData);
			
				endforeach;
				$accessoriesModel->accessoriesHistory("Work order inactive because it has been re-issued.", "$loggedId", $prevMasterId);
				$accessoriesModel->accessoriesHistory("Work order re-issued.", "$loggedId", $masterId);
				$accessoriesModel->accessoriesHistory("Work order published.", "$loggedId", $masterId);
				
				$response = array(
					'status' => 'success',
					'masterid' => $masterId,
					'itemid' => implode(',', $tempItemId)
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'csrfmissing'
				);
				echo json_encode($response);
			endif;


		elseif($_POST['formName'] == 'imagesupload'):
			$getItemID = explode(',', $_POST['itemid']);
			foreach ($_FILES as $key => $value):
				$tempVar = explode('-', $key);
				$workorderItemId = $getItemID[end($tempVar)];
				$blobdata = file_get_contents($value['tmp_name']);
				$imageType = $value['type'];
				$id = $accessoriesModel->lastRowId('ACCESSORIES_IMAGES', 'NID');
				$accessoriesModel->imageUpload($id, $blobdata, $imageType, $workorderItemId);
			endforeach;

		elseif($_POST['formName'] == 'excell-export'):
			$tableClass = $_POST['tableclass'];
			$itemId = $_POST['itemid'];
			$itemName = $_POST['itemname'];
			$unit = $_POST['tableunit'];
			$arrFile = explode('.', $_FILES['excelfile']['name']);
	        $extension = end($arrFile);
	        if('csv' == $extension):
	            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
	        elseif('xlsx' == $extension):
	            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
	        else:
	        	echo 'errors';
	        	die();
	        endif;
			//$reader->setLoadSheetsOnly(["ITCOMMON"]);
			$reader->setReadDataOnly(true);
			$spreadsheet = $reader->load($_FILES['excelfile']['tmp_name']);
	        $sheetData = $spreadsheet->getActiveSheet()->toArray();
	        $dataTableCreate = "";
	        $sizeName = array();
	        $totalQty = array();
	        if(in_array('destination', array_map('strtolower', $sheetData[0]))):
	        	foreach ($sheetData as $rowsKey => $rows):
		        	$dataTableCreatePart1 = "";
		        	$dataTableCreatePart2 = "";
		        	$dataTableCreatePart3 = "";
		        	if($rowsKey == 0):
		        		//print_r($rows);
		        		$dataTableCreate .= "<tr>";
		        		$dataTableCreate .= "<th class='items-header'>Name of Item</th>";
		        		foreach ($rows as $cellsKey => $cells):
		        			if($cellsKey == 0):
		        				$dataTableCreatePart1 .= '<th class="kimball-color-size-wise-qty'.$itemId.'-header" data-columnname="Color Name">Color Name</th>';
		        			elseif($cellsKey == 1):
		        				$dataTableCreatePart1 .= '<th class="destination'.$itemId.'-header" data-columnname="Destination">Destination</th>';
		        			elseif($cellsKey == 2):
		        				$dataTableCreatePart1 .= '<th class="country'.$itemId.'-header" data-columnname="Country">Country</th>';
		        			elseif($cellsKey == 3):
		        				$dataTableCreatePart1 .= '<th class="kimball-color-size-wise-qty'.$itemId.'-header" data-columnname="Kimball No.">Kimball No.</th>';
		        			elseif($cellsKey == 4):
		        				$dataTableCreatePart1 .= '<th class="kimball-color-size-wise-qty'.$itemId.'-header" data-columnname="Lot No.">Lot No.</th>';
		        			elseif($cellsKey == count($rows) - 2):
		        				$dataTableCreatePart3 .= '<th class="totalqty-header">W.O. Required Qty.</th>';
		        			elseif($cellsKey == count($rows) - 1):
		        				$dataTableCreatePart3 .= '<th class="remarks-header">Remarks</th>';
		        			else:
		        				array_push($sizeName, $cells);
		        				$dataTableCreatePart2 .= '<div class="cell size-'.$cells.'-header bg-gray border bd-light csize-header" style="min-width: 60px;">'.$cells.'</div>';
		        			endif;
		        		endforeach;
		        		$dataTableCreate .= $dataTableCreatePart1.'<th class="kimball-color-size-wise-qty'.$itemId.'-header colorsizeqty-header"><div>Size Wise Qty</div><div class="row no-gap">'.$dataTableCreatePart2.'</div></th>'.$dataTableCreatePart3;
		        		$dataTableCreate .= "</tr>";
		        	elseif ($rowsKey == 1):
		        		$dataTableCreate .= "<tr class='data-row'>";
		        		$dataTableCreate .= '<td class="text-center text-bold maingrid-rowspan items-name items-'.$itemId.'" data-itemid="'.$itemId.'" rowspan="'.(count($sheetData)-1).'">'.$itemName.'</td>';
		        	    $count = 0;
		        		foreach ($rows as $cellsKey => $cells):
		        			if($cellsKey == 0):
		        				$dataTableCreatePart1 .= '<td class="kimball-color-size-wise-qty'.$itemId.' color-celll-fixed">'.$cells.'</td>';
		        			elseif($cellsKey == 1):
		        				$dataTableCreatePart1 .= '<td class="destination'.$itemId.'"><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="'.$cells.'" readonly></td>';
		        			elseif($cellsKey == 2):
		        				$dataTableCreatePart1 .= '<td class="country'.$itemId.'"><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="'.$cells.'" readonly></td>';
		        			elseif($cellsKey == 3):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' kimball-cell">'.$cells.'</td>';
		        			elseif($cellsKey == 4):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' lot-cell">'.$cells.'</td>';
		        			elseif($cellsKey == count($rows) - 2):
		        				$dataTableCreatePart3 .= '<td class="totalqty"><div class="row no-gap"><div class="cell"><input type="text" readonly="" class="input-small text-center row-totalqty-input" name="rowtotalqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="'.round($cells).'"></div><div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;"><span class="text-bold">'.$unit.'</span></div></div></td>';
		        				array_push($totalQty, $cells);
		        			elseif($cellsKey == count($rows) - 1):
		        				$dataTableCreatePart3 .= '<td class="remarks" style="position:relative;"><textarea style="max-width: 100%; height:40px; margin: 0 auto;" class="remarks-input data-copier" name="vremarks">'.$cells.'</textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), \'remarks\', \''.$tableClass.'\')"><span class="mif-copy"></span></a></td>';
		        			else:
		        				$dataTableCreatePart2 .= '<div class="cell size-'.$sizeName[$count].' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center size-'.$sizeName[$count].'-input csize-input" data-sizename="'.$sizeName[$count].'" readonly="" value="'.round($cells).'" style="width: 100%;"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeName[$count].'-input" value="'.$cells.'"></div>';
		        				$count++;
		        			endif;
		        		endforeach;
		        		$dataTableCreate .= $dataTableCreatePart1.'<td class="kimball-color-size-wise-qty'.$itemId.' colorsizeqty"><div class="row no-gap">'.$dataTableCreatePart2.'</div></td>'.$dataTableCreatePart3;
		        		$dataTableCreate .= "</tr>";
		        	else:
		        		$dataTableCreate .= "<tr class='data-row appended-row'>";
		        	    $count = 0;
		        		foreach ($rows as $cellsKey => $cells):
		        			if($cellsKey == 0):
		        				$dataTableCreatePart1 .= '<td class="kimball-color-size-wise-qty'.$itemId.' color-celll-fixed">'.$cells.'</td>';
		        			elseif($cellsKey == 1):
		        				$dataTableCreatePart1 .= '<td class="destination'.$itemId.'"><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="'.$cells.'" readonly></td>';
		        			elseif($cellsKey == 2):
		        				$dataTableCreatePart1 .= '<td class="country'.$itemId.'"><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="'.$cells.'" readonly></td>';
		        			elseif($cellsKey == 3):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' kimball-cell">'.$cells.'</td>';
		        			elseif($cellsKey == 4):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' lot-cell">'.$cells.'</td>';
		        			elseif($cellsKey == count($rows) - 2):
		        				$dataTableCreatePart3 .= '<td class="totalqty"><div class="row no-gap"><div class="cell"><input type="text" readonly="" class="input-small text-center row-totalqty-input" name="rowtotalqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="'.round($cells).'"></div><div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;"><span class="text-bold">'.$unit.'</span></div></div></td>';
		        				array_push($totalQty, $cells);
		        			elseif($cellsKey == count($rows) - 1):
		        				$dataTableCreatePart3 .= '<td class="remarks" style="position:relative; overflow:hidden;"><textarea style="max-width: 100%; height:40px; margin: 0 auto;" class="remarks-input data-copier" name="vremarks">'.$cells.'</textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), \'remarks\', \''.$tableClass.'\')"><span class="mif-copy"></span></a><div class="row-removeBtn ribbed-darkRed" onclick="rowRemover($(this), \''.$tableClass.'\');"><span class="mif-cross"></span></div></td>';
		        			else:
		        				$dataTableCreatePart2 .= '<div class="cell size-'.$sizeName[$count].' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center size-'.$sizeName[$count].'-input csize-input" data-sizename="'.$sizeName[$count].'" readonly="" value="'.round($cells).'" style="width: 100%;"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeName[$count].'-input" value="'.$cells.'"></div>';
		        				$count++;
		        			endif;
		        		endforeach;
		        		$dataTableCreate .= $dataTableCreatePart1.'<td class="kimball-color-size-wise-qty'.$itemId.' colorsizeqty"><div class="row no-gap">'.$dataTableCreatePart2.'</div></td>'.$dataTableCreatePart3;
		        		$dataTableCreate .= "</tr>";
		        	endif;
		        endforeach;
		        $dataTableCreate .= '<tr style="background: #e0f0f1;"><td style="font-weight:bold; position:relative;" class="text-right grandQtyCell"><p style="width: 155px;margin: 0px;">Quantity Grand Total</p></td><td class="kimball-color-size-wise-qty'.$itemId.'"></td><td class="destination'.$itemId.'"></td><td class="country'.$itemId.'"></td><td class="kimball-color-size-wise-qty'.$itemId.'"></td><td class="kimball-color-size-wise-qty'.$itemId.'"></td><td class="kimball-color-size-wise-qty'.$itemId.'" style="position:relative;"><input type="hidden" class="input-small text-center garmentsgrandtotal" name="garmentsgrandtotal" style="min-width: 80px; max-width:120px; margin: 0 auto;" value="'.round(array_sum($totalQty)).'"></td><td class="grandtotalqty"><div class="row no-gap"><div class="cell"><input type="text" readonly="" class="input-small text-center grand-totalqty-input" name="grandqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="'.round(array_sum($totalQty)).'"></div><div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;"><span class="text-bold grandunit">'.$unit.'</span></div></div></td><td class="itemqty-errors text-left"><span class="invalid_feedback">W.O. required quantity grand total must be greater than zero(0).</span></td></tr>';            

	        else:
		        foreach ($sheetData as $rowsKey => $rows):
		        	$dataTableCreatePart1 = "";
		        	$dataTableCreatePart2 = "";
		        	$dataTableCreatePart3 = "";
		        	if($rowsKey == 0):
		        		//print_r($rows);
		        		$dataTableCreate .= "<tr>";
		        		$dataTableCreate .= "<th class='items-header'>Name of Item</th>";
		        		foreach ($rows as $cellsKey => $cells):
		        			if($cellsKey == 0):
		        				$dataTableCreatePart1 .= '<th class="kimball-color-size-wise-qty'.$itemId.'-header" data-columnname="Color Name">Color Name</th>';
		        			elseif($cellsKey == 1):
		        				$dataTableCreatePart1 .= '<th class="country'.$itemId.'-header" data-columnname="Country">Country</th>';
		        			elseif($cellsKey == 2):
		        				$dataTableCreatePart1 .= '<th class="kimball-color-size-wise-qty'.$itemId.'-header" data-columnname="Kimball No.">Kimball No.</th>';
		        			elseif($cellsKey == 3):
		        				$dataTableCreatePart1 .= '<th class="kimball-color-size-wise-qty'.$itemId.'-header" data-columnname="Lot No.">Lot No.</th>';
		        			elseif($cellsKey == count($rows) - 2):
		        				$dataTableCreatePart3 .= '<th class="totalqty-header">W.O. Required Qty.</th>';
		        			elseif($cellsKey == count($rows) - 1):
		        				$dataTableCreatePart3 .= '<th class="remarks-header">Remarks</th>';
		        			else:
		        				array_push($sizeName, $cells);
		        				$dataTableCreatePart2 .= '<div class="cell size-'.$cells.'-header bg-gray border bd-light csize-header" style="min-width: 60px;">'.$cells.'</div>';
		        			endif;
		        		endforeach;
		        		$dataTableCreate .= $dataTableCreatePart1.'<th class="kimball-color-size-wise-qty'.$itemId.'-header colorsizeqty-header"><div>Size Wise Qty</div><div class="row no-gap">'.$dataTableCreatePart2.'</div></th>'.$dataTableCreatePart3;
		        		$dataTableCreate .= "</tr>";
		        	elseif ($rowsKey == 1):
		        		$dataTableCreate .= "<tr class='data-row'>";
		        		$dataTableCreate .= '<td class="text-center text-bold maingrid-rowspan items-name items-'.$itemId.'" data-itemid="'.$itemId.'" rowspan="'.(count($sheetData)-1).'">'.$itemName.'</td>';
		        	    $count = 0;
		        		foreach ($rows as $cellsKey => $cells):
		        			if($cellsKey == 0):
		        				$dataTableCreatePart1 .= '<td class="kimball-color-size-wise-qty'.$itemId.' color-celll-fixed">'.$cells.'</td>';
		        			elseif($cellsKey == 1):
		        				$dataTableCreatePart1 .= '<td class="country'.$itemId.'"><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="'.$cells.'" readonly></td>';
		        			elseif($cellsKey == 2):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' kimball-cell">'.$cells.'</td>';
		        			elseif($cellsKey == 3):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' lot-cell">'.$cells.'</td>';
		        			elseif($cellsKey == count($rows) - 2):
		        				$dataTableCreatePart3 .= '<td class="totalqty"><div class="row no-gap"><div class="cell"><input type="text" readonly="" class="input-small text-center row-totalqty-input" name="rowtotalqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="'.round($cells).'"></div><div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;"><span class="text-bold">'.$unit.'</span></div></div></td>';
		        				array_push($totalQty, $cells);
		        			elseif($cellsKey == count($rows) - 1):
		        				$dataTableCreatePart3 .= '<td class="remarks" style="position:relative;"><textarea style="max-width: 100%; height:40px; margin: 0 auto;" class="remarks-input data-copier" name="vremarks">'.$cells.'</textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), \'remarks\', \''.$tableClass.'\')"><span class="mif-copy"></span></a></td>';
		        			else:
		        				$dataTableCreatePart2 .= '<div class="cell size-'.$sizeName[$count].' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center size-'.$sizeName[$count].'-input csize-input" data-sizename="'.$sizeName[$count].'" readonly="" value="'.round($cells).'" style="width: 100%;"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeName[$count].'-input" value="'.$cells.'"></div>';
		        				$count++;
		        			endif;
		        		endforeach;
		        		$dataTableCreate .= $dataTableCreatePart1.'<td class="kimball-color-size-wise-qty'.$itemId.' colorsizeqty"><div class="row no-gap">'.$dataTableCreatePart2.'</div></td>'.$dataTableCreatePart3;
		        		$dataTableCreate .= "</tr>";
		        	else:
		        		$dataTableCreate .= "<tr class='data-row appended-row'>";
		        	    $count = 0;
		        		foreach ($rows as $cellsKey => $cells):
		        			if($cellsKey == 0):
		        				$dataTableCreatePart1 .= '<td class="kimball-color-size-wise-qty'.$itemId.' color-celll-fixed">'.$cells.'</td>';
		        			elseif($cellsKey == 1):
		        				$dataTableCreatePart1 .= '<td class="country'.$itemId.'"><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="'.$cells.'" readonly></td>';
		        			elseif($cellsKey == 2):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' kimball-cell">'.$cells.'</td>';
		        			elseif($cellsKey == 3):
		        				$dataTableCreatePart1 .= '<td class="text-center kimball-color-size-wise-qty'.$itemId.' lot-cell">'.$cells.'</td>';
		        			elseif($cellsKey == count($rows) - 2):
		        				$dataTableCreatePart3 .= '<td class="totalqty"><div class="row no-gap"><div class="cell"><input type="text" readonly="" class="input-small text-center row-totalqty-input" name="rowtotalqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="'.round($cells).'"></div><div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;"><span class="text-bold">'.$unit.'</span></div></div></td>';
		        				array_push($totalQty, $cells);
		        			elseif($cellsKey == count($rows) - 1):
		        				$dataTableCreatePart3 .= '<td class="remarks" style="position:relative; overflow:hidden;"><textarea style="max-width: 100%; height:40px; margin: 0 auto;" class="remarks-input data-copier" name="vremarks">'.$cells.'</textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), \'remarks\', \''.$tableClass.'\')"><span class="mif-copy"></span></a><div class="row-removeBtn ribbed-darkRed" onclick="rowRemover($(this), \''.$tableClass.'\');"><span class="mif-cross"></span></div></td>';
		        			else:
		        				$dataTableCreatePart2 .= '<div class="cell size-'.$sizeName[$count].' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="input-small text-center size-'.$sizeName[$count].'-input csize-input" data-sizename="'.$sizeName[$count].'" readonly="" value="'.round($cells).'" style="width: 100%;"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeName[$count].'-input" value="'.$cells.'"></div>';
		        				$count++;
		        			endif;
		        		endforeach;
		        		$dataTableCreate .= $dataTableCreatePart1.'<td class="kimball-color-size-wise-qty'.$itemId.' colorsizeqty"><div class="row no-gap">'.$dataTableCreatePart2.'</div></td>'.$dataTableCreatePart3;
		        		$dataTableCreate .= "</tr>";
		        	endif;
		        endforeach;
		        $dataTableCreate .= '<tr style="background: #e0f0f1;"><td style="font-weight:bold; position:relative;" class="text-right grandQtyCell"><p style="width: 155px;margin: 0px;">Quantity Grand Total</p></td><td class="kimball-color-size-wise-qty'.$itemId.'"></td><td class="country'.$itemId.'"></td><td class="kimball-color-size-wise-qty'.$itemId.'"></td><td class="kimball-color-size-wise-qty'.$itemId.'"></td><td class="kimball-color-size-wise-qty'.$itemId.'" style="position:relative;"><input type="hidden" class="input-small text-center garmentsgrandtotal" name="garmentsgrandtotal" style="min-width: 80px; max-width:120px; margin: 0 auto;" value="'.round(array_sum($totalQty)).'"></td><td class="grandtotalqty"><div class="row no-gap"><div class="cell"><input type="text" readonly="" class="input-small text-center grand-totalqty-input" name="grandqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="'.round(array_sum($totalQty)).'"></div><div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;"><span class="text-bold grandunit">'.$unit.'</span></div></div></td><td class="itemqty-errors text-left"><span class="invalid_feedback">W.O. required quantity grand total must be greater than zero(0).</span></td></tr>';
		    endif;
	        echo $dataTableCreate;

		//next else if//
	    elseif($_POST['formName'] == 'published'):
	    	$idExplode = explode(',', $_POST['workorderid']);
	    	foreach ($idExplode as $key => $value):
	    		$userId = $auth->loggedUserId();
	    		$dataArr = array();
	    		$dataArr['VLASTUPDATEDUSER'] = $userId;
	    		$dataArr['VLASTUPDATEDAT'] = date('d-m-Y');
	    		$dataArr['VSTATUS'] = 'publish';
	    		$dataArr['VTODATE'] = date('d-m-Y');
	    		$dataArr['VISSUE'] = 1;
	    		$dataArr['VPUBLISHEDUSER'] = $userId;
 	    		$dataArr['VPUBLISHEDAT'] = date('d-m-Y');
 	    		$getCreatedUserId = $accessoriesModel->getData("SELECT VCREATEDUSER FROM ACCESSORIES_WORKORDERMASTER WHERE NID = $value");
 	    		$getCreatedUserPrefix = $accessoriesModel->getUser($getCreatedUserId[0]['VCREATEDUSER']);
 	    		$dataArr['VPONUMBER'] = $workorderOpt->purchaseOrderNumber($getCreatedUserPrefix['prefix'], $getCreatedUserId[0]['VCREATEDUSER']);	    		// $masterId = $accessoriesModel->lastRowId('ACCESSORIES_WORKORDERMASTER', 'NID');
	    		// $dataArr['NID'] = $masterId;
	    		$accessoriesModel->dataUpdate("ACCESSORIES_WORKORDERMASTER", $dataArr, "NID = $value");
	    		// $item = array();
	    		// $item['NWORKORDERMASTERID'] = $masterId; 
	    		// $accessoriesModel->dataUpdate("ACCESSORIES_WORKORDERITEMS", $item, "NWORKORDERMASTERID = $value");
	    		// $history = array();
	    		// $history['NDEPENDENTID'] = $masterId;
	    		// $accessoriesModel->dataUpdate("ACCESSORIES_HISTORYLOG", $history, "NDEPENDENTID = $value");
	    		$accessoriesModel->accessoriesHistory("Work order published.", "$userId", $value);
	    	endforeach;
	    	if($_POST['type'] == 'single'):
	    		$response = array(
					'status' => 'success',
					'id'     => $_POST['workorderid']
				);
	    	else:
		    	$response = array(
					'status' => 'success'
				);
	        endif;
			echo json_encode($response);
		elseif($_POST['formName'] == 'approved'):
	    	$idExplode = explode(',', $_POST['workorderid']);
	    	foreach ($idExplode as $key => $value):
	    		$userId = $auth->loggedUserId();
	    		$dataArr = array();
	    		$dataArr['VAPPROVEDUSER'] = $userId;
	    		$dataArr['NAPPROVEDSTATUS'] = 1;
	    		$dataArr['VLASTUPDATEDUSER'] = $userId;
		    	$dataArr['VLASTUPDATEDAT'] = date('d-m-Y');
		    	$accessoriesModel->dataUpdate("ACCESSORIES_WORKORDERMASTER", $dataArr, "NID = $value");
		    	$accessoriesModel->accessoriesHistory("Work order approved.", "$userId", $value);
	        endforeach;
	    	if($_POST['type'] == 'single'):
	    		$response = array(
					'status' => 'success',
					'id'     => end($idExplode)
				);
	    	else:
		    	$response = array(
					'status' => 'success'
				);
	        endif;
			echo json_encode($response);
		elseif($_POST['formName'] == 'restore'):
	    	$idExplode = explode(',', $_POST['workorderid']);
	    	foreach ($idExplode as $key => $value):
	    		$userId = $auth->loggedUserId();
	    		$dataArr = array();
	    		$dataArr['NDELETEDSTATUS'] = 0;
	    		$dataArr['VSTATUS'] = 'draft';
	    		$dataArr['VPUBLISHEDUSER'] = '';
	    		$dataArr['VPONUMBER'] = '';
	    		$dataArr['VPUBLISHEDAT'] = '';
	    		$dataArr['VLASTUPDATEDUSER'] = $userId;
		    	$dataArr['VLASTUPDATEDAT'] = date('d-m-Y');
		    	$accessoriesModel->dataUpdate("ACCESSORIES_WORKORDERMASTER", $dataArr, "NID = $value");
		    	$accessoriesModel->accessoriesHistory("Work order restored.", "$userId", $value);
	        endforeach;
		    $response = array(
			    'status' => 'success'
			);
			echo json_encode($response);
		elseif($_POST['formName'] == 'remove'):
	    	$idExplode = explode(',', $_POST['workorderid']);
	    	foreach ($idExplode as $key => $value):
	    		$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERMASTER", "nid = $value");
	    		$accessoriesModel->deleteSingleRow("ACCESSORIES_HISTORYLOG", "ndependentid = $value");
				$tempItemId = array();
				$tempItemIdForDelete = array();
				$tempItemDataId = array();
				$getItemIdForDelete = $accessoriesModel->getData("SELECT NID FROM ACCESSORIES_WORKORDERITEMS WHERE NWORKORDERMASTERID = $value ORDER BY NID ASC");
				foreach ($getItemIdForDelete as $itemD):
					$itemIdDelete = $itemD['NID'];
					array_push($tempItemIdForDelete, $itemIdDelete);
					$getItemDataIdForDelete = $accessoriesModel->getData("SELECT NID FROM ACCESSORIES_WORKORDERITEMDATA WHERE NWORKORDERITEMSID = $itemIdDelete");
					foreach ($getItemDataIdForDelete as $gridid):
						array_push($tempItemDataId, $gridid['NID']);
					endforeach;
				endforeach;
				$exItemId = implode(',', $tempItemIdForDelete);
				$exGridId = implode(',', $tempItemDataId);

				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERITEMS", "NWORKORDERMASTERID = $value");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERITEMDATA", "NWORKORDERITEMSID IN ($exItemId)");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_CUSTOMCOLUMNVALUE", "NGRIDITEMDATAID IN ($exGridId)");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERSIZEQTY", "NWORKORDERITEMSDATAID IN ($exGridId)");
				$accessoriesModel->deleteSingleRow("ACCESSORIES_WORKORDERFORMSETTING", "NWORKORDERITEMSID IN ($exItemId)");
	        endforeach;
		    $response = array(
			    'status' => 'success'
			);
			echo json_encode($response);
		elseif($_POST['formName'] == 'accepted'):
	    	$idExplode = explode(',', $_POST['workorderid']);
	    	foreach ($idExplode as $key => $value):
	    		$userId = $auth->loggedUserId();
	    		$dataArr = array();
	    		$dataArr['VACCEPTEDUSER'] = $userId;
	    		$dataArr['NACCEPTENCESTATUS'] = 1;
	    		$dataArr['VLASTUPDATEDUSER'] = $userId;
		    	$dataArr['VLASTUPDATEDAT'] = date('d-m-Y');
		    	$accessoriesModel->dataUpdate("ACCESSORIES_WORKORDERMASTER", $dataArr, "NID = $value");
		    	$accessoriesModel->accessoriesHistory("Work order accepted by store.", "$userId", $value);
	        endforeach;
	    	if($_POST['type'] == 'single'):
	    		$response = array(
					'status' => 'success',
					'id'     => end($idExplode)
				);
	    	else:
		    	$response = array(
					'status' => 'success'
				);
	        endif;
			echo json_encode($response);
		elseif($_POST['formName'] == 'delete-attachment'):
			unset($_POST['formName']);
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_IMAGES WHERE nid = $id") == 'exist'):
				$where = 'NID = '.$id;
				$accessoriesModel->deleteSingleRow('ACCESSORIES_IMAGES', $where);
				$response = array(
					'status' => 'success'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors'
				);
				echo json_encode($response);
		    endif;
		elseif($_POST['formName'] == 'delete-workorder'):
			$id = $_POST['id'];
			$userId = $auth->loggedUserId();
			if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_WORKORDERMASTER WHERE nid = $id AND napprovedstatus = 0 AND (vcreateduser = '$userId' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')") == 'exist'):
				$where = 'NID = '.$id;
				$dataTemp = array();
				$dataTemp['NDELETEDSTATUS'] = 1;
				$dataTemp['NLASTDELETEDUSER'] = $userId;
				$accessoriesModel->dataUpdate('ACCESSORIES_WORKORDERMASTER', $dataTemp, $where);
				$accessoriesModel->accessoriesHistory("Work order move to trash.", "$userId", $id);
				$response = array(
					'status' => 'success',
					'successmsg' => 'Workorder has been deleted successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 2
				);
				echo json_encode($response);
		    endif;
		elseif($_POST['formName'] == 'rollback-published'):
			$id = $_POST['id'];
			$userId = $auth->loggedUserId();
			if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_WORKORDERMASTER WHERE nid = $id AND vstatus = 'publish' AND napprovedstatus = 0 AND ((SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')") == 'exist'):
				$where = 'NID = '.$id;
				$dataTemp = array();
				$dataTemp['VSTATUS'] = 'draft';
				$dataTemp['VPONUMBER'] = '';
				$dataTemp['VISSUE'] = '';
				$dataTemp['VLASTUPDATEDUSER'] = $userId;
				$dataTemp['VLASTUPDATEDAT'] = date('d-m-Y');
				$dataTemp['NAPPROVEDSTATUS'] = 0;
				$dataTemp['VAPPROVEDUSER'] = '';
				$dataTemp['NACCEPTENCESTATUS'] = 0;
				$dataTemp['VACCEPTEDUSER'] = '';
				$dataTemp['VPUBLISHEDUSER'] = '';
				$dataTemp['VPUBLISHEDAT'] = '';
				$accessoriesModel->dataUpdate('ACCESSORIES_WORKORDERMASTER', $dataTemp, $where);
				$accessoriesModel->accessoriesHistory("Work order moved to drafts page.", "$userId", $id);
				$response = array(
					'status' => 'success',
					'successmsg' => 'Workorder moved into drafts page successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 2
				);
				echo json_encode($response);
		    endif;
		elseif($_POST['formName'] == 'rollback-approved'):
			$id = $_POST['id'];
			$userId = $auth->loggedUserId();
			if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_WORKORDERMASTER WHERE nid = $id AND vstatus = 'publish' AND napprovedstatus = 1 AND nacceptencestatus = 0 AND ((SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')") == 'exist'):
				$where = 'NID = '.$id;
				$dataTemp = array();
				$dataTemp['VSTATUS'] = 'publish';
				$dataTemp['VLASTUPDATEDUSER'] = $userId;
				$dataTemp['VLASTUPDATEDAT'] = date('d-m-Y');
				$dataTemp['NAPPROVEDSTATUS'] = 0;
				$dataTemp['VAPPROVEDUSER'] = '';
				$dataTemp['NACCEPTENCESTATUS'] = 0;
				$dataTemp['VACCEPTEDUSER'] = '';
				$accessoriesModel->dataUpdate('ACCESSORIES_WORKORDERMASTER', $dataTemp, $where);
				$accessoriesModel->accessoriesHistory("Work order moved to published page.", "$userId", $id);
				$response = array(
					'status' => 'success',
					'successmsg' => 'Workorder moved into publish page successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 2
				);
				echo json_encode($response);
		    endif;
		else:
			$response = array(
				'status' => 'invalidFormSubmission'
			);
			echo json_encode($response);
		endif;

	else:
		$response = array(
			'status' => 'invalidRequest'
		);
		echo json_encode($response);
	endif;
endif;

