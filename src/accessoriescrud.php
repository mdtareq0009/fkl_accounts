<?php 
/**
 * Crud class use for data update/insert/delete operations.
 * Author: Md Jakir Hosen. 
 **/

namespace accessories;

class accessoriescrud
{
	protected $con;
	public function __construct($db)
	{
		$this->con = $db;
	}

	public function checkDataExistence($query){
		$checkData = oci_parse($this->con, $query);
			oci_execute($checkData);
        $data = oci_fetch_assoc($checkData);
	    	oci_close($this->con);
        if(oci_num_rows($checkData) == 1){
        	return 'exist';
        }else{
        	return 'not exist';
        }
	}

	public function getNextSequenceValue($sequenceName) {
		$query = "SELECT $sequenceName.NEXTVAL FROM DUAL";
		$stmt = oci_parse($this->con, $query);
		oci_execute($stmt);
		$row = oci_fetch_assoc($stmt);
		return $row['NEXTVAL'];
	}

	public function lastRowId($table, $columnName){
		$getID = oci_parse($this->con, "SELECT MAX($columnName) AS id FROM $table");
		oci_execute($getID);
		$rowinfo = oci_fetch_array($getID);
		if(oci_num_rows($getID) > 0){
			$lastId = intval($rowinfo['ID']) + 1;
		}else{
			$lastId = 1;
		}
		//oci_free_statement($getID);
	    	//oci_close($this->con);
		return $lastId;
	}
	public function firstRowValue($query){
		$sql = oci_parse($this->con, $query);
//		print_r($sql);
		oci_execute($sql);
		$rowinfo = oci_fetch_assoc($sql);
		oci_free_statement($sql);
		oci_close($this->con);
		return (empty($rowinfo) ? 'empty' : $rowinfo);
	
	}

	public function countRowId($table, $columnName,$prefix){
		$productCode = $prefix . "00001";

		$getID = oci_parse($this->con, "SELECT COUNT($columnName) AS ID FROM $table");
			oci_execute($getID);
			$rowinfo = oci_fetch_array($getID);
			if(oci_num_rows($getID) > 0){
				$newProductId = intval($rowinfo['ID']) + 1;
				$zeros = array('0', '00', '000', '0000');
				$productCode = $prefix . (strlen($newProductId) > count($zeros) ? $newProductId : $zeros[count($zeros) - strlen($newProductId)] . $newProductId);
			}	
		return $productCode;
	}

	public function insertData($table, array $data){
		$getColumn = array_keys($data);
        $getValue = array_values($data);
        $getColumnConvert = implode(',', $getColumn);
        $tempValueArr = array();
        foreach ($getValue as $value) {
        	array_push($tempValueArr, htmlspecialchars(trim($value), ENT_QUOTES));
        }
        $getValueConvert = "'".rtrim(implode("','", $tempValueArr), ',')."'";
        $insertData = oci_parse($this->con, "INSERT INTO $table ($getColumnConvert) VALUES ($getValueConvert)");

        if(oci_execute($insertData)){
        	oci_free_statement($insertData);
        	oci_close($this->con);
        	return true;
        }else{
        	return false;
        }
	}


	public function insertDataManualCommit($table, array $data){
			$getColumn = array_keys($data);
			$getValue = array_values($data);
			$getColumnConvert = implode(',', $getColumn);
			$tempValueArr = array();
			foreach ($getValue as $value) {
				array_push($tempValueArr, htmlspecialchars(trim($value), ENT_QUOTES));
			}
			$getValueConvert = "'".rtrim(implode("','", $tempValueArr), ',')."'";
			$insertData = oci_parse($this->con, "INSERT INTO $table ($getColumnConvert) VALUES ($getValueConvert)");
			$checkExecution = oci_execute($insertData, OCI_NO_AUTO_COMMIT);
			if(!$checkExecution){
				oci_rollback($this->con);
				return 'not insert';
			}else{
				return 'inserted';
			}
	}

	public function imageUpload($id, $image, $type, $workorderItemId){
		$result = oci_parse($this->con, "INSERT INTO ACCESSORIES_IMAGES (NID, NWORKORDERITEMID, BIMAGE, VFILEFORMATE) VALUES($id, $workorderItemId, EMPTY_BLOB(), '$type') RETURNING BIMAGE INTO :image");
        $blob = oci_new_descriptor($this->con, OCI_D_LOB);
        oci_bind_by_name($result, ":image", $blob, -1, OCI_B_BLOB);
        oci_execute($result, OCI_DEFAULT);
        if(!$blob->save($image)){
        	oci_rollback($this->con);
        }else{
        	oci_commit($this->con);
        }
        oci_free_statement($result);
    	oci_close($this->con);
        $blob->free();
    }

	public function getData($query){
	    if(!empty($query)){
	    	$sql = oci_parse($this->con, $query);

			// Execute the query
			if (!oci_execute($sql)) {
				return ['error' => "Error executing SQL query: " . oci_error($sql)['message']];
			}

	    	while($row = oci_fetch_assoc($sql)){
	    		$data[] = $row;
	    	}
	    	oci_free_statement($sql);
	    	oci_close($this->con);
	    	return (empty($data) ? 'empty' : $data);
	    }else{
	    	return "Parameter $query is empty!";
	    }
	}

	public function dataUpdate($table, array $data, $where){
		$first = true;
		$appender = '';
		foreach ($data as $columName => $value){
		    if($first){
		      $first = false;
		    }else{
		      $appender .=', ';
		    }
		    $appender .= $columName." = '".$value."'"; 
		}
	
        $updateData = oci_parse($this->con, "UPDATE $table SET $appender WHERE $where");
        if(oci_execute($updateData)){
        	oci_free_statement($updateData);
        	oci_close($this->con);
        	return true;
        }else{
        	return false;
        }
    }

    public function dataUpdateManualCommit($table, array $data, $where){
		$first = true;
			$appender = '';
			foreach ($data as $columName => $value){
		    if($first){
		      	$first = false;
		    }else{
		      	$appender .=', ';
		    }
		    	$appender .= $columName." = '".$value."'";
			}
		$updateData = oci_parse($this->con, "UPDATE $table SET $appender WHERE $where");
		if(oci_execute($updateData, OCI_NO_AUTO_COMMIT)){	
			return 'updated';
		}else{
			oci_rollback($this->con);
			return 'failed';
		}
    }

    public function deleteSingleRow($table, $where){
    	$deleteData = oci_parse($this->con, "DELETE FROM $table WHERE $where");
        if(oci_execute($deleteData)){
        	oci_free_statement($deleteData);
        	oci_close($this->con);
        	return true;
        }else{
        	return false;
        }
    }

    public function deleteSingleRowManualCommit($table, $where){
    	$deleteData = oci_parse($this->con, "DELETE FROM $table WHERE $where");
        if(oci_execute($deleteData, OCI_NO_AUTO_COMMIT)){
        	return 'deleted';
        }else{
        	oci_rollback($this->con);
        	return 'failed';
        }
    }

// 	public function getUser($id=''){
// 		if($id != ''){
// 			$fklid = $id;
// 			// $getUsers = $this->getData("SELECT 
// 			// emp.empname as vempname, 
// 			// desig.name as vdesignationname, 
// 			// dept.name as vdepartmentname, 
// 			// pic.bpicture, 
// 			// permission.vrole, 
// 			// permission.vpurchasecodeprefix 
// 			// FROM hr_employeeinfo@crypton emp LEFT JOIN hr_designation@crypton desig 
// 			// ON desig.id = emp.designationid 
// 			// LEFT JOIN hr_department@crypton dept ON dept.id = emp.departmentid 
// 			// LEFT JOIN erppic.hrm_employeepicture pic ON pic.vemployeeid = emp.empid 
// 			// LEFT JOIN ACCESSORIES_USERSPERMISSION permission 
// 			// ON permission.vfklid = emp.empid WHERE emp.empid = '$id'");
// 			// // $getUsers = $this->getData("SELECT emp.vempname, desig.vdesignationname, dept.vdepartmentname, pic.bpicture, permission.vrole, permission.vpurchasecodeprefix FROM hrm_employee emp INNER JOIN erp.hrm_designation desig ON desig.ndesignationcode = emp.ndesignationcode INNER JOIN erp.hrm_department dept ON dept.ndepartmentcode = emp.ndeptcode INNER JOIN erppic.hrm_employeepicture pic ON pic.vemployeeid = emp.vemployeeid INNER JOIN ACCESSORIES_USERSPERMISSION permission ON permission.vfklid = emp.vemployeeid WHERE emp.vemployeeid = '$id'");
// 			// if(is_array($getUsers)){
// 				$userTemp = array();
// 				$userTemp['prefix'] ='Demo';				
// 				$userTemp['name'] = 'Demo';
// 				$userTemp['designation'] = 'Demo';
// 				$userTemp['department'] = 'Demo';
// 				$picture = '';
// 			    $picture = "";
// 				$userTemp['picture'] = $picture;
// 				$userTemp['role'] = 'Demo';
// 				return $userTemp;
// 		// 	}else{
// 		// 		echo "<script type='text/javascript'>alert('getUser($id): User not found!');</script>";
// 		// 	}
// 		// }else{
// 		// 	echo "<script type='text/javascript'>alert('getUser($id): Invalid fklid!');</script>";
// 		// }
// 	}
// }

	public function getUser($id=''){
		if($id != ''){
			$fklid = $id;
			// $getUsers = $this->getData("SELECT 
			// emp.empname as vempname, 
			// desig.name as vdesignationname, 
			// dept.name as vdepartmentname, 
			// pic.bpicture, 
			// permission.vrole, 
			// permission.vpurchasecodeprefix 
			// FROM hr_employeeinfo@crypton emp LEFT JOIN hr_designation@crypton desig 
			// ON desig.id = emp.designationid 
			// LEFT JOIN hr_department@crypton dept ON dept.id = emp.departmentid 
			// LEFT JOIN erppic.hrm_employeepicture pic ON pic.vemployeeid = emp.empid 
			// LEFT JOIN ACCESSORIES_USERSPERMISSION permission 
			// ON permission.vfklid = emp.empid WHERE emp.empid = '$id'");
			 $getUsers = $this->getData("SELECT emp.vempname, desig.vdesignationname, dept.vdepartmentname, pic.bpicture, permission.vrole, permission.vpurchasecodeprefix
			  FROM hrm_employee emp 
			  INNER JOIN erp.hrm_designation desig ON desig.ndesignationcode = emp.ndesignationcode 
			  INNER JOIN erp.hrm_department dept ON dept.ndepartmentcode = emp.ndeptcode 
			  INNER JOIN erppic.hrm_employeepicture pic ON pic.vemployeeid = emp.vemployeeid 
			  INNER JOIN ACCESSORIES_USERSPERMISSION permission ON permission.vfklid = emp.vemployeeid 
			  WHERE emp.vemployeeid = '$id'");
			if(is_array($getUsers)){
				$userTemp = array();
				$userTemp['prefix'] = $getUsers[0]['VPURCHASECODEPREFIX'];				
				$userTemp['name'] = $getUsers[0]['VEMPNAME'];
				$userTemp['designation'] = $getUsers[0]['VDESIGNATIONNAME'];
				$userTemp['department'] = $getUsers[0]['VDEPARTMENTNAME'];
				$picture = !empty($getUsers[0]['BPICTURE'])?$getUsers[0]['BPICTURE']->load():'';
			    $picture = "<img src='data:image/png;base64,".base64_encode($picture)."' class='avatar' style='width:100%;'>";
				$userTemp['picture'] = $picture;
				$userTemp['role'] = $getUsers[0]['VROLE'];
				return $userTemp;
			}else{
				echo "<script type='text/javascript'>alert('getUser($id): User not found!');</script>";
			}
		}else{
			echo "<script type='text/javascript'>alert('getUser($id): Invalid fklid!');</script>";
		}
	}

	public function getUserData($id=''){
		if($id != ''){
			$fklid = $id;
			$getUsers = $this->getData("SELECT emp.empname as vempname, desig.name as vdesignationname, dept.name as vdepartmentname, pic.bpicture, permission.vrole
, permission.vpurchasecodeprefix FROM hr_employeeinfo@crypton emp LEFT JOIN hr_designation@crypton desig 
ON desig.id = emp.designationid 
LEFT JOIN hr_department@crypton dept ON dept.id = emp.departmentid 
LEFT JOIN erppic.hrm_employeepicture pic ON pic.vemployeeid = emp.empid 
LEFT JOIN GATEPASS_USERSPERMISSION permission 
ON permission.vfklid = emp.empid WHERE emp.empid = '$id'");
			if(is_array($getUsers)){
				$userTemp = array();
				$userTemp['prefix'] = $getUsers[0]['VPURCHASECODEPREFIX'];				
				$userTemp['name'] = $getUsers[0]['VEMPNAME'];
				$userTemp['designation'] = $getUsers[0]['VDESIGNATIONNAME'];
				$userTemp['department'] = $getUsers[0]['VDEPARTMENTNAME'];
				$picture = !empty($getUsers[0]['BPICTURE'])?$getUsers[0]['BPICTURE']->load():'';
			    $picture = "<img src='data:image/png;base64,".base64_encode($picture)."' class='avatar' style='width:100%;'>";
				$userTemp['picture'] = $picture;
				$userTemp['role'] = $getUsers[0]['VROLE'];
				return $userTemp;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}

	public function accessoriesHistory($msg, $user, $dependentId){
		$historyData = array();
		$historyData['NID'] = $this->lastRowId('ACCESSORIES_HISTORYLOG', 'NID');
		$historyData['VHISTORYTEXT'] = $msg;
		$historyData['VFKLID'] = $user;
		$historyData['VDATETIME'] = date("d-m-Y, h:i:s a");
		$historyData['NDEPENDENTID'] = $dependentId;
		$this->insertData('ACCESSORIES_HISTORYLOG', $historyData);
	}

	public function rowCount($table, $where){
		$tableCount = $this->getData("SELECT COUNT(*) AS COUNTER FROM $table WHERE $where");
		if(is_array($tableCount)){
			return $tableCount[0]['COUNTER'];
		}else{
			return 0;
		}
	}

	public function imageUpdateCosting($mpmno,$itemId, $image, $type ){
		// public function imageUploadCosting($id, $image, $type, $mpmno,$itemId){
			$result = oci_parse($this->con, "UPDATE inv.mrd_precosting_pic SET MRD_ITEM_ID='$itemId', MPP_PICTURE=EMPTY_BLOB(), MPP_PICTRUE_TYPE='$type' WHERE MPM_NO='$mpmno' RETURNING MPP_PICTURE INTO :image");
			$blob = oci_new_descriptor($this->con, OCI_D_LOB);
			oci_bind_by_name($result, ":image", $blob, -1, OCI_B_BLOB);
			oci_execute($result, OCI_DEFAULT);
			if(!$blob->save($image)){
				oci_rollback($this->con);
			}else{
				oci_commit($this->con);
			}
			oci_free_statement($result);
			oci_close($this->con);
			$blob->free();
		}
		public function imageUploadCosting($id,$mpmno,$itemId, $image, $type ){
		// public function imageUploadCosting($id, $image, $type, $mpmno,$itemId){
			$result = oci_parse($this->con, "INSERT INTO inv.mrd_precosting_pic (MPP_SL, MPM_NO, MRD_ITEM_ID, MPP_PICTURE, MPP_PICTRUE_TYPE) VALUES($id, '$mpmno','$itemId', EMPTY_BLOB(), '$type') RETURNING MPP_PICTURE INTO :image");
			$blob = oci_new_descriptor($this->con, OCI_D_LOB);
			oci_bind_by_name($result, ":image", $blob, -1, OCI_B_BLOB);
			oci_execute($result, OCI_DEFAULT);
			if(!$blob->save($image)){
				oci_rollback($this->con);
			}else{
				oci_commit($this->con);
			}
			oci_free_statement($result);
			oci_close($this->con);
			$blob->free();
		}

		public function lastRow($table, $columnName){
			$getID = oci_parse($this->con, "SELECT NVL(MAX($columnName),0) AS id FROM $table");
			oci_execute($getID);
			$rowinfo = oci_fetch_array($getID);
			if(oci_num_rows($getID) > 0){
				$lastId = intval($rowinfo['ID']) + 1;
			}else{
				$lastId = 1;
			}
			//oci_free_statement($getID);
				//oci_close($this->con);
			return $lastId;
		}
		public function reviseLastRow($table, $columnName,$where){
			$getID = oci_parse($this->con, "SELECT NVL(MAX($columnName),0) AS id FROM $table $where");
			oci_execute($getID);
			$rowinfo = oci_fetch_array($getID);
			if(oci_num_rows($getID) > 0){
				$lastId = intval($rowinfo['ID']) + 1;
			}else{
				$lastId = 1;
			}
			//oci_free_statement($getID);
				//oci_close($this->con);
			return $lastId;
		}
	
    public function __destruct(){
      return oci_close($this->con);
    }
}