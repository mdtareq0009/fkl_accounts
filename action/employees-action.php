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
			
		if($_POST['formName'] == 'add-employees'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_MOBILE_NO']));
				$vemployeeid = trim(strtoupper($data['V_EMPLOYEE_ID']));
				$product_code = trim(strtoupper($data['V_EMPLOYEE_CODE']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_MOBILE_NO) FROM EMPLOYEE WHERE UPPER(V_MOBILE_NO) = '$unitfull'") == 'not exist'):

					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_EMPLOYEE_CODE) FROM EMPLOYEE WHERE UPPER(V_EMPLOYEE_CODE) = '$product_code'") == 'exist'):
						
						$prefix = 'EMP-';
						$data['V_EMPLOYEE_CODE'] = $accessoriesModel->countRowId('EMPLOYEE', 'N_ID',$prefix);

					endif;
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_EMPLOYEE_ID) FROM EMPLOYEE WHERE UPPER(V_EMPLOYEE_ID) = '$vemployeeid'") == 'exist'):
						
						$response = array(
							'status' => false,
							'successmsg' => 'Employee ID Already Here.',
						);
						echo json_encode($response);
						exit;

					endif;

					unset($_POST['formName']);
					unset($data['csrf']);
					unset($data['N_ID']);
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					// var_dump($data);
					// exit;
					$accessoriesModel->insertData("EMPLOYEE", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'Employee added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Employee Already Here.',
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

		elseif($_POST['formName'] == 'edit-employees'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_MOBILE_NO']));
					$vemployeeid = trim(strtoupper($data['V_EMPLOYEE_ID']));
					$unitID = $data['N_ID'];

					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_EMPLOYEE_ID) FROM EMPLOYEE WHERE UPPER(V_EMPLOYEE_ID) = '$vemployeeid' and N_ID != '$unitID'") == 'exist'):
						$response = array(
							'status' => false,
							'successmsg' => 'Employee ID Already Here.',
						);
						echo json_encode($response);
						exit;
					endif;

					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_MOBILE_NO) FROM EMPLOYEE WHERE UPPER(V_MOBILE_NO) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						
						
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("EMPLOYEE", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'Employee updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'Employee Already Here.',
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

			elseif($_POST['formName'] == 'delete-employees'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						$accessoriesModel->dataUpdate("EMPLOYEE", $data, "N_ID = $id");

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

