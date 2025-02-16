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
			
		if($_POST['formName'] == 'add-ip-lans'):
			
			
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_IP_VLAN WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):

					

					unset($_POST['formName']);
					unset($data['csrf']);
					unset($data['N_ID']);

					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					// var_dump($data);
					// exit;
					$accessoriesModel->insertData("IT_ASSET_IP_VLAN", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'IP Lan added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'IP Lan Already Here.',
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

		elseif($_POST['formName'] == 'edit-ip-lans'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_IP_VLAN WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'IP Lan updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'IP Lan Already Here.',
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

			elseif($_POST['formName'] == 'delete-ip-lans'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'IP Lan has been deleted successfully.'
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

