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

	public function lastRowId($table, $columnName){
		$getID = oci_parse($this->con, "SELECT MAX($columnName) AS id FROM $table");
		oci_execute($getID);
		$rowinfo = oci_fetch_array($getID);
		if(oci_num_rows($getID) > 0){
			$lastId = intval($rowinfo['ID']) + 1;
		}else{
			$lastId = 1;
		}
		oci_free_statement($getID);
	    oci_close($this->con);
		return $lastId;
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
			
	    	oci_execute($sql);
	    	while($row = oci_fetch_assoc($sql)){
	    		$data[] = $row;
	    	}
	    	oci_free_statement($sql);
	    	oci_close($this->con);
	    	return (empty($data) ? 'Table is empty...' : $data);
	    }else{
	    	return "Parameter $query is empty!";
	    }
	}

	public function dataUpdate($table, array $data, $where){
		$first = true;
		$appender = '';
		foreach ($data as $columName => $value):
		    if($first):
		        $first = false;
		    else:
		        $appender .=', ';
		    endif;
		    $appender .= $columName." = '".$value."'"; 
		endforeach;
        $updateData = oci_parse($this->con, "UPDATE $table SET $appender WHERE $where");
        if(oci_execute($updateData)){
        	oci_free_statement($updateData);
        	oci_close($this->con);
        	return true;
        }else{
        	return false;
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

	public function getUser($id=''){
		if($id != ''){
			$fklid = $id;
			$getUsers = $this->getData("SELECT emp.vempname, desig.vdesignationname, dept.vdepartmentname, pic.bpicture, permission.vrole, permission.vpurchasecodeprefix FROM hrm_employee emp INNER JOIN erp.hrm_designation desig ON desig.ndesignationcode = emp.ndesignationcode INNER JOIN erp.hrm_department dept ON dept.ndepartmentcode = emp.ndeptcode INNER JOIN erppic.hrm_employeepicture pic ON pic.vemployeeid = emp.vemployeeid INNER JOIN ACCESSORIES_USERSPERMISSION permission ON permission.vfklid = emp.vemployeeid WHERE emp.vemployeeid = '$id'");
			if(is_array($getUsers)){
				$userTemp = array();
				$userTemp['prefix'] = $getUsers[0]['VPURCHASECODEPREFIX'];				
				$userTemp['name'] = $getUsers[0]['VEMPNAME'];
				$userTemp['designation'] = $getUsers[0]['VDESIGNATIONNAME'];
				$userTemp['department'] = $getUsers[0]['VDEPARTMENTNAME'];
				$picture = $getUsers[0]['BPICTURE']->load();
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

    public function __destruct(){
      return oci_close($this->con);
    }
}