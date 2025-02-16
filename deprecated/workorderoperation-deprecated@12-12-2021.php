<?php 
/**
 * dependent page class for all dropdown dependent data retrive from database and customization for multiple dropdown.
 * Author: Md Jakir Hosen. 
 **/

namespace accessories;

class workorderoperation
{
	private $con;
	private $accessoriesModel;
	public function __construct($db)
	{
        $this->con = $db;
        $this->accessoriesModel = new accessoriescrud($this->con);
        $this->filterToDate = '';
        $this->filterFormDate = '';
	}

	public function purchaseOrderNumber($userPrefix, $userId){
		$currentDate = date("m-Y");
		$month = date('M');
		$year = date('y');
		$sql = oci_parse($this->con,"SELECT lpad(substr(vponumber, instr(vponumber, '-', - 1) + 1, length(vponumber)), 6, 0) AS counter FROM accessories_workordermaster WHERE TO_CHAR(TO_DATE(vpublishedat, 'dd-mm-yyyy'), 'mm-yyyy') = '$currentDate' AND vponumber LIKE '$userPrefix-%'AND vstatus = 'publish' AND ndeletedstatus = 0 ORDER BY lpad(substr(vponumber, instr(vponumber, '-', - 1) + 1, length(vponumber)), 6, 0) DESC FETCH FIRST 1 ROWS ONLY");
		oci_execute($sql);
		$rowinfo = oci_fetch_array($sql);
		if(oci_num_rows($sql) > 0){
			$count = intval($rowinfo['COUNTER']) + 1;
		}else{
			$count = 1;
		}
		return $userPrefix.'-'.strtoupper($month).$year.'-'.$count;
	}

	public function checkColumnExist($tableId, $fieldName, $tableName){
		$retrun = 0;
		$sql = oci_parse($this->con, "SELECT $fieldName FROM $tableName WHERE nworkorderitemsid = $tableId");
		oci_execute($sql);
		$tempArr = array();
		while ($row = oci_fetch_assoc($sql)) {
			if(!empty($row[$fieldName])){
				array_push($tempArr, 'found');
			}
		}
		if(count($tempArr) > 0){
			$retrun = 1;
			return $retrun;
		}else{
			return $retrun;
		}
	}

	public function actualQty($value, $type, $addition){
		if($type == 'parcent'){
			$additional = 100+$addition;
			$getPreviousValue = round(($value / $additional) * 100);
		}else{
			$getPreviousValue = $value - $addition;
		}
		return $getPreviousValue;
	}

	public function additionQty($value, $type, $addition){
		if($type == 'parcent'){
			$additional = ($value / 100) * $addition;
			$additionalValue = round($value + $additional);
		}else{
			$additionalValue = $value + $addition;
		}
		return $additionalValue;
	}

	public function conversionCalculate($value, $type, $convertValue, $moreCon='', $unit){
		if($moreCon == ''){
			if($type == 'divided'){
				$convert = floatval($value) / floatval($convertValue);
			}else{
				$convert = floatval($value) * floatval($convertValue);
			}
		}else{
			if($type == 'divided'){
				$convert = floatval($value) / floatval($convertValue);
			}else{
				$convert = floatval($value) * floatval($convertValue);
			}

			$convert = $convert / floatval($moreCon);
		}

		if(strtolower($unit) == 'yrds' || strtolower($unit) ==  'gg'){
			return round($convert, 2);
		}else{
			return round($convert);
		}
	}

	public function colorWiseData($fklNo){
		$tempColorCode = array();
		$tempQty = array();
		$getData = $this->accessoriesModel->getData("SELECT ksdetails.ncolsl, TRIM(kscolor.vcolcode) AS colorcode, INITCAP(TRIM(kscolor.vcolname)) AS colorname, LISTAGG(ksdetails.vqtybreakdown, ',') WITHIN GROUP (ORDER BY ksdetails.nks_id ASC) AS QTY FROM ERP.mer_ks_details ksdetails LEFT JOIN ERP.mer_ks_color kscolor ON kscolor.ncolsl = ksdetails.ncolsl AND kscolor.nks_id = ksdetails.nks_id WHERE ksdetails.nks_id IN ($fklNo) GROUP BY ksdetails.ncolsl, TRIM(kscolor.vcolcode), INITCAP(TRIM(kscolor.vcolname)) ORDER BY ksdetails.ncolsl");
		if(is_array($getData)){
			foreach ($getData as $key => $value){
				if(empty($value['COLORCODE'])){
					array_push($tempColorCode, $value['COLORNAME']);
				}else{
					array_push($tempColorCode, $value['COLORCODE'].' ('.$value['COLORNAME'].')');
				}
				$qty = explode(',', $value['QTY']);
				array_push($tempQty, array_sum($qty));					
		    }
			$response = array(
				'status' => 'success',
				'color'  => $tempColorCode,
				'qty'    => $tempQty
			);
		}else{
			$response = array(
                'status' => 'tablempty'
			);
		}
		return $response;

	}

	public function colorAndSizeWiseData($fklNo){
		$mainArr = array();
		$getData = $this->accessoriesModel->getData("SELECT ksdetails.ncolsl, TRIM(kscolor.vcolcode) AS colorcode, INITCAP(TRIM(kscolor.vcolname)) AS colorname, ksdetails.vqtybreakdown, (SELECT vsizebreakdown FROM ERP.mer_ks_master WHERE nks_id = ksdetails.nks_id) AS sizename FROM ERP.mer_ks_details ksdetails LEFT JOIN ERP.mer_ks_color kscolor ON kscolor.ncolsl = ksdetails.ncolsl AND kscolor.nks_id = ksdetails.nks_id WHERE ksdetails.nks_id IN ($fklNo) ORDER BY ksdetails.nks_id, ksdetails.ncolsl");
		if(is_array($getData)){
			$sizeArray = array();
			foreach ($getData as $key => $value){
				$explodeQty = explode(',', $value['VQTYBREAKDOWN']);
				$explodeSize = explode(',', $value['SIZENAME']);
				$colorname = $value['COLORCODE'].' ('.$value['COLORNAME'].')';
				foreach ($explodeSize as $sizeKey => $size){
					array_push($sizeArray, $size);
					$mainArr[trim($colorname)][$size] = isset($mainArr[trim($colorname)][$size]) ? $mainArr[trim($colorname)][$size] + intval($explodeQty[$sizeKey]) : intval($explodeQty[$sizeKey]);
				}		
		    }
			$response = array(
				'status' => 'success',
				'colorsizeQty'  => $mainArr,
				'sizename' => array_unique($sizeArray)
			);
	    }else{
	    	$response = array(
	    		'status' => 'tablempty'
	    	);
	    }
	    return $response;
	}

	public function kimballColorSizeWiseData($fklNo){
		$mainArr = array();
		$tempKimball = array();
		$tempLot = array();
		$getData = $this->accessoriesModel->getData("SELECT orderview.vlot, ksdetails.ncolsl, TRIM(kscolor.vcolcode) AS colorcode, INITCAP(TRIM(kscolor.vcolname)) AS colorname, ksdetails.vqtybreakdown AS QTY, kscolor.vkimballno, (SELECT vsizebreakdown FROM ERP.mer_ks_master WHERE nks_id = ksdetails.nks_id) AS sizename FROM ERP.mer_ks_details ksdetails LEFT JOIN ERP.mer_ks_master ksmaster ON ksmaster.nks_id = ksdetails.nks_id LEFT JOIN ERP.mer_ks_color kscolor ON kscolor.ncolsl = ksdetails.ncolsl AND kscolor.nks_id = ksdetails.nks_id LEFT JOIN erp.mer_vw_orderinfo orderview ON orderview.norderid = ksmaster.nordercode WHERE ksdetails.nks_id IN ($fklNo) ORDER BY ksdetails.nks_id, ksdetails.ncolsl, kscolor.vkimballno");
		if(is_array($getData)){
			$sizeArray = array();
			foreach ($getData as $key => $value){
				$explodeQty = explode(',', $value['QTY']);
				$explodeSize = explode(',', $value['SIZENAME']);
				//array_push($tempKimball, $value['VKIMBALLNO']);
				if(empty($value['COLORCODE'])):
					$colorname = $value['COLORNAME'];
				else:	
					$colorname = $value['COLORCODE'].' ('.str_replace(array('(', ')'), array('', ''), $value['COLORNAME']).')';
				endif;
				$lot = $value['VLOT'];
				foreach ($explodeSize as $sizeKey => $size){
					array_push($sizeArray, $size);
					$mainArr[trim($colorname).'*lot*'.$lot][$size] = isset($mainArr[trim($colorname).'*lot*'.$lot][$size]) ? $mainArr[trim($colorname).'*lot*'.$lot][$size] + intval($explodeQty[$sizeKey]) : intval($explodeQty[$sizeKey]);
				}
				if(isset($tempKimball[trim($colorname).'*lot*'.$lot])){
					if(strval($tempKimball[trim($colorname).'*lot*'.$lot]) != strval($value['VKIMBALLNO'])){
						$tempKimball[trim($colorname).'*lot*'.$lot] = strval($tempKimball[trim($colorname).'*lot*'.$lot].', '.$value['VKIMBALLNO']);
					}
				}else{
					$tempKimball[trim($colorname).'*lot*'.$lot] = strval($value['VKIMBALLNO']);
				}		
		    }
		    foreach ($mainArr as $key => $value){
		    	$explodeLot = explode('*lot*', $key);
		    	array_push($tempLot, end($explodeLot));
		    }
			$response = array(
				'status' => 'success',
				'colorsizeQty'  => $mainArr,
				'sizename' => array_unique($sizeArray),
				'kimball'  => array_values($tempKimball),
				'lot'  => $tempLot  
			);
	    }else{
	    	$response = array(
	    		'status' => 'tablempty'
	    	);
	    }
	    return $response;
	}

    public function kimballColorWiseData($fklNo){
    	$tempColorCode = array();
		$tempQty = array();
		$tempKimball = array();
		$tempLot = array();
		// $getData = $accessoriesModel->getData("SELECT orderview.vlot, ksdetails.ncolsl, INITCAP(TRIM(kscolor.vcolname)) AS colorname, ksdetails.vqtybreakdown AS QTY, kscolor.vkimballno FROM ERP.mer_ks_details ksdetails LEFT JOIN ERP.mer_ks_color kscolor ON kscolor.ncolsl = ksdetails.ncolsl AND kscolor.nks_id = ksdetails.nks_id LEFT JOIN erp.mer_vw_orderinfo orderview ON orderview.norderid = (SELECT ksmaster.nordercode FROM ERP.mer_ks_master ksmaster WHERE ksmaster.nks_id = ksdetails.nks_id) WHERE ksdetails.nks_id IN ($fklNo) ORDER BY ksdetails.nks_id, ksdetails.ncolsl");
		$getData = $this->accessoriesModel->getData("SELECT orderview.vlot, ksdetails.ncolsl, TRIM(kscolor.vcolcode) AS colorcode, INITCAP(TRIM(kscolor.vcolname)) AS colorname, ksdetails.vqtybreakdown AS QTY, kscolor.vkimballno FROM ERP.mer_ks_details ksdetails LEFT JOIN ERP.mer_ks_master ksmaster ON ksmaster.nks_id = ksdetails.nks_id LEFT JOIN ERP.mer_ks_color kscolor ON kscolor.ncolsl = ksdetails.ncolsl AND kscolor.nks_id = ksdetails.nks_id LEFT JOIN erp.mer_vw_orderinfo orderview ON orderview.norderid = ksmaster.nordercode WHERE ksdetails.nks_id IN ($fklNo) ORDER BY ksdetails.nks_id, ksdetails.ncolsl, kscolor.vkimballno");
		if(is_array($getData)){
			foreach ($getData as $key => $value){
				if(empty($value['COLORCODE'])):
					array_push($tempColorCode, $value['COLORNAME']);
				else:
					array_push($tempColorCode, $value['COLORCODE'].' ('.str_replace(array('(', ')'), array('', ''), $value['COLORNAME']).')');
				endif;
				$qty = explode(',', $value['QTY']);
				array_push($tempQty, array_sum($qty));
				array_push($tempKimball, $value['VKIMBALLNO']);
				array_push($tempLot, $value['VLOT']);
			}
			$response = array(
				'status'  => 'success',
				'color'   => $tempColorCode,
				'qty'     => $tempQty,
				'kimball' => $tempKimball,
				'lot'     => $tempLot
			);
	    }else{
	    	$response = array(
	    		'status' => 'tablempty'
	    	);
	    }
	    return $response;
    }


    public function sizeWiseQty($fklNo){
    	$sizeData = array();
		$getData = $this->accessoriesModel->getData("SELECT ksdetails.ncolsl, ksdetails.vqtybreakdown, (SELECT vsizebreakdown FROM ERP.mer_ks_master WHERE nks_id = ksdetails.nks_id) AS sizename FROM ERP.mer_ks_details ksdetails LEFT JOIN ERP.mer_ks_color kscolor ON kscolor.ncolsl = ksdetails.ncolsl AND kscolor.nks_id = ksdetails.nks_id WHERE ksdetails.nks_id IN ($fklNo) ORDER BY ksdetails.nks_id,ksdetails.ncolsl");
		if(is_array($getData)){
			foreach ($getData as $key => $value){
				$explodeQty = explode(',', $value['VQTYBREAKDOWN']);
				$explodeSize = explode(',', $value['SIZENAME']);
				foreach ($explodeSize as $sizeKey => $size){
					$sizeData[$size] = isset($sizeData[$size]) ? $sizeData[$size] + $explodeQty[$sizeKey] : $explodeQty[$sizeKey];
				}
		    }
			$response = array(
				'status' => 'success',
				'sizeQty'  => $sizeData
			);
	    }else{
	    	$response = array(
	    		'status' => 'tablempty'
	    	);
	    }
	    return $response;
    }

	public function __destruct(){
        return oci_close($this->con);
    }
    
}