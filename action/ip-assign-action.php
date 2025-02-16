<?php
require_once '../ini.php';
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $auth->loginPageRedirect();
else:
	header('Content-Type: application/json');
	$ipaddress = getenv("REMOTE_ADDR") ;
	$accessoriesModel = new accessoriescrud($db->con);
	if(isset($_POST['formName'])):
		
		

////// CATEGORY CRUD
			
		if($_POST['formName'] == 'add-ip-assign'):
			// $data = $_POST['type']->csrf;
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['N_EMPLOYEE_ID']));
				$assign_code = trim(strtoupper($data['V_IP_VLAN_ASSIGN_NO']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(N_EMPLOYEE_ID) FROM IT_ASSET_IP_VLAN_ASSIGN_MASTER WHERE UPPER(N_EMPLOYEE_ID) = '$unitfull'") == 'not exist'):

					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_IP_VLAN_ASSIGN_NO) FROM IT_ASSET_IP_VLAN_ASSIGN_MASTER WHERE UPPER(V_IP_VLAN_ASSIGN_NO) = '$assign_code'") == 'exist'):
						
						$prefix = 'IPA-';
						$data['V_IP_VLAN_ASSIGN_NO'] = $accessoriesModel->countRowId('IT_ASSET_IP_VLAN_ASSIGN_MASTER', 'N_ID',$prefix);

					endif;
					
					unset($_POST['formName']);
					unset($data['csrf']);
					unset($data['N_ID']);
					unset($data['old_N_IP_VLAN_ID']);
					$data["V_CURRENT_ASSIGN_TYPE"] = 'Assign';
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;

					
					$accessoriesModel->insertData("IT_ASSET_IP_VLAN_ASSIGN_MASTER", $data);
					
					$lastId = $accessoriesModel->lastRowId('IT_ASSET_IP_VLAN_ASSIGN_MASTER', 'N_ID');
					
					$masterdata = array();
					$masterdata['N_IP_VLAN_ASSIGN_ID'] = $lastId;
					$masterdata["V_CURRENT_ASSIGN_TYPE"] = 'Assign';
					$masterdata["V_NOTE"] = $data['V_NOTE'];
					$masterdata["D_ASSIGN_DATE"] = $data['D_ASSIGN_DATE'];
					$masterdata['N_CREATED_BY'] = $auth->loggedUserId();
					$masterdata['V_IP_ADDRESS'] = $ipaddress;
					
				 $accessoriesModel->insertData("IT_ASSET_IP_VLAN_ASSIGN_DETAILS", $masterdata);
					// print_r($ddda);
					// exit;
					
					$updateData = array();
					$updateData['C_IS_ACTIVE']= 'a';
					$id = $data['N_IP_VLAN_ID'];

					$accessoriesModel->dataUpdate("IP_VLAN", $updateData, "N_ID = $id");

					$response = array(
						'status' => true,
						'successmsg' => 'IP ASSIGNED successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Employess  Already IP ASSIGN Here.',
					);
					echo json_encode($response);
			    endif;
				
			else:
				$response = array(
					'status' => 'errors',
					'value' => 1
				);
				echo json_encode($response);
			endif;

		elseif($_POST['formName'] == 'edit-ip-assign'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['N_IP_VLAN_ID']));
					$vemployeeid = trim(strtoupper($data['N_EMPLOYEE_ID']));
					$unitID = $data['N_ID'];

					if($accessoriesModel->checkDataExistence("SELECT UPPER(N_EMPLOYEE_ID) FROM IT_ASSET_IP_VLAN_ASSIGN_MASTER WHERE UPPER(N_EMPLOYEE_ID) = '$vemployeeid' and N_ID != '$unitID'") == 'exist'):
						$response = array(
							'status' => false,
							'successmsg' => 'Employee ID Already Here.',
						);
						echo json_encode($response);
						exit;
					endif;

					// print_r($unitfull);
					// print_r($accessoriesModel->checkDataExistence("SELECT UPPER(N_IP_VLAN_ID) FROM IP_VLAN_ASSIGN_MASTER WHERE UPPER(N_IP_VLAN_ID) = '$unitfull' and N_ID != '$unitID' and V_CURRENT_ASSIGN_TYPE = 'Assign'"));
					// exit;
					

					if($accessoriesModel->checkDataExistence("SELECT UPPER(N_IP_VLAN_ID) FROM IT_ASSET_IP_VLAN_ASSIGN_MASTER WHERE UPPER(N_IP_VLAN_ID) = '$unitfull' and N_ID != '$unitID' and V_CURRENT_ASSIGN_TYPE = 'Assign'") == 'not exist'):

						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];

						$old_N_IP_id = $data['old_N_IP_VLAN_ID'];
						$vid = $data['N_IP_VLAN_ID'];

						unset($data['N_ID']);
						unset($data['old_N_IP_VLAN_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN_ASSIGN_MASTER", $data, "N_ID = $id");

						
					
						// print_r($old_N_IP_id);
						// // print_r($vid);
						// exit;

						$vdata = array();
						$vdata['C_IS_ACTIVE'] = 'n';
						
						$updateData = array();
						$updateData['C_IS_ACTIVE']= 'a';

						if($vid !== $old_N_IP_id ){
							$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN", $vdata, "N_ID = $old_N_IP_id");
							$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN", $updateData, "N_ID = $vid");
						}
						


						$response = array(
							'status' => true,
							'successmsg' => 'IP ASSIGNED updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'Employess  Already IP ASSIGN Here.',
						);
						echo json_encode($response);
					endif;
	
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;

			elseif($_POST['formName'] == 'delete-ip-assign'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						
						$accessoriesModel->dataUpdate("IP_VLAN_ASSIGN_MASTER", $data, "N_ID = $id");
						
						$selectdata = $accessoriesModel->firstRowValue("SELECT N_IP_VLAN_ID FROM IP_VLAN_ASSIGN_MASTER WHERE UPPER(N_ID) = '$id'");
						$vid = $selectdata['N_IP_VLAN_ID'];
						$vdata = array();
						$vdata['C_IS_ACTIVE'] = 'n';
						$accessoriesModel->dataUpdate("IP_VLAN", $vdata, "N_ID = $vid");
						
					
						$response = array(
							'status' => true,
							'successmsg' => 'Employee has been deleted successfully.'
						);
						echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;
	//// end category entry
	
		endif;

	else:
		$response = array(
			'status' => 'invalidRequest'
		);
		echo json_encode($response);
	endif;
endif;

