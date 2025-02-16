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
		
		if($_POST['formName'] == 'add-types'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['VNAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME) FROM ACCESSORIES_TYPES WHERE UPPER(VNAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
					$lastId = $accessoriesModel->lastRowId('ACCESSORIES_TYPES', 'NID');
					$data['NID'] = $lastId;
					$data['VCREATEDUSER'] = $auth->loggedUserId();
					$data['VCREATEDAT'] = date('d-m-Y');
					$data['VLASTUPDATEDUSER'] = $auth->loggedUserId(); 
					$data['VLASTUPDATEDAT'] = date('d-m-Y');
					// var_dump($data);
					// exit;
					$accessoriesModel->insertData("ACCESSORIES_TYPES", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'Types added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Types Already Here.',
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
		elseif($_POST['formName'] == 'edit-types'):
			
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['VNAME']));
				$unitID = $data['NID'];

				
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME) FROM ACCESSORIES_TYPES WHERE UPPER(VNAME) = '$unitfull' and NID != '$unitID'") == 'not exist'):
					unset($data['formName']);
					unset($data['csrf']);
					$id = $data['NID'];
					unset($data['NID']);
					// var_dump($_POST['formName']);
					// exit;
					$data['VLASTUPDATEDAT'] = date('d-m-Y');
					$data['VLASTUPDATEDUSER'] = $auth->loggedUserId();
					$accessoriesModel->dataUpdate("ACCESSORIES_TYPES", $data, "NID = $id");
					$response = array(
						'status' => true,
						'successmsg' => 'Type updated successfully.'
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Types Already Here.',
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
		
		elseif($_POST['formName'] == 'delete-types'):
			// var_dump($_POST['data']);
			// exit;
			unset($_POST['formName']);
			$where = 'NID = '.$_POST['data'];
			$accessoriesModel->deleteSingleRow('ACCESSORIES_TYPES', $where);
			$response = array(
				'status' => 'success',
				'successmsg' => 'Type has been deleted successfully.'
			);
			echo json_encode($response);
			//next elseif




////// CATEGORY CRUD
			
		elseif($_POST['formName'] == 'add-categories'):

			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_CATEGORY WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
//					unset($data['N_ID']);
                    $lastId = $accessoriesModel->lastRowId('IT_ASSET_CATEGORY', 'N_ID');
                    $data['N_ID'] = $lastId;
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					$accessoriesModel->insertData("IT_ASSET_CATEGORY", $data);
//					 print_r($accessoriesModel->insertData("CATEGORY", $data););
//					 exit;
					$response = array(
						'status' => true,
						'successmsg' => 'Category added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Category Already Here.',
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

		elseif($_POST['formName'] == 'edit-categories'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
	
					
				
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_CATEGORY WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_CATEGORY", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'Category updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'Category Already Here.',
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

			elseif($_POST['formName'] == 'delete-categories'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						unset($data['V_NAME']);
						$accessoriesModel->dataUpdate("IT_ASSET_CATEGORY", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'Category has been deleted successfully.'
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
	
////// BRAND CRUD
			
		elseif($_POST['formName'] == 'add-brands'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_BRAND WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
//					unset($data['N_ID']);
                    $lastId = $accessoriesModel->lastRowId('IT_ASSET_BRAND', 'N_ID');
                    $data['N_ID'] = $lastId;
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;

					$accessoriesModel->insertData("IT_ASSET_BRAND", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'Brand added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Brand Already Here.',
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

		elseif($_POST['formName'] == 'edit-brands'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
	
					
				
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_BRAND WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_BRAND", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'Brand updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'Brand Already Here.',
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

			elseif($_POST['formName'] == 'delete-brands'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						unset($data['V_NAME']);
						$accessoriesModel->dataUpdate("IT_ASSET_BRAND", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'Brand has been deleted successfully.'
						);
						echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;
	//// end BRAND entry
	
////// COLOR CRUD
			
		elseif($_POST['formName'] == 'add-colors'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_COLOR WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
					unset($data['N_ID']);
					// $lastId = $accessoriesModel->lastRowId('CATEGORY', 'N_ID');
					// $data['NID'] = $lastId;
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					// var_dump($data);
					// exit;
					$accessoriesModel->insertData("IT_ASSET_COLOR", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'Color added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Color Already Here.',
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

		elseif($_POST['formName'] == 'edit-colors'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
	
					
				
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_COLOR WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_COLOR", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'Color updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'Color Already Here.',
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

			elseif($_POST['formName'] == 'delete-colors'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						unset($data['V_NAME']);
						$accessoriesModel->dataUpdate("IT_ASSET_COLOR", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'Color has been deleted successfully.'
						);
						echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;
	//// end COLOR entry
////// SIZE CRUD
			
		elseif($_POST['formName'] == 'add-sizes'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_PSIZE WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
					unset($data['N_ID']);
					// $lastId = $accessoriesModel->lastRowId('CATEGORY', 'N_ID');
					// $data['NID'] = $lastId;
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					// var_dump($data);
					// exit;
					$accessoriesModel->insertData("IT_ASSET_PSIZE", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'Size added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Size Already Here.',
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

		elseif($_POST['formName'] == 'edit-sizes'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
	
					
				
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_PSIZE WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_PSIZE", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'Size updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'Size Already Here.',
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

			elseif($_POST['formName'] == 'delete-sizes'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						unset($data['V_NAME']);
						$accessoriesModel->dataUpdate("IT_ASSET_PSIZE", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'Size has been deleted successfully.'
						);
						echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;
	//// end SIZE entry
////// unit CRUD
			
		elseif($_POST['formName'] == 'add-units'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_UNIT WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
//					unset($data['N_ID']);
                    $lastId = $accessoriesModel->lastRowId('IT_ASSET_UNIT', 'N_ID');
                    $data['N_ID'] = $lastId;
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					// var_dump($data);
					// exit;
					$accessoriesModel->insertData("IT_ASSET_UNIT", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'Unit added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Unit Already Here.',
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

		elseif($_POST['formName'] == 'edit-units'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
	
					
				
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_UNIT WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_UNIT", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'Unit updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'Unit Already Here.',
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

			elseif($_POST['formName'] == 'delete-units'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						unset($data['V_NAME']);
						$accessoriesModel->dataUpdate("IT_ASSET_UNIT", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'Unit has been deleted successfully.'
						);
						echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;
	//// end SIZE entry


////// ip lan CRUD
			
		elseif($_POST['formName'] == 'add-ip-categories'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_IP_VLAN_CATEGORY WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
					unset($data['N_ID']);
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					$accessoriesModel->insertData("IT_ASSET_IP_VLAN_CATEGORY", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'IP category added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'IP category Already Here.',
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

		elseif($_POST['formName'] == 'edit-ip-categories'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
	
					
				
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_IP_VLAN_CATEGORY WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN_CATEGORY", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'IP category updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'IP category Already Here.',
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

			elseif($_POST['formName'] == 'delete-ip-categories'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						unset($data['V_NAME']);
						$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN_CATEGORY", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'IP category has been deleted successfully.'
						);
						echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;
	//// end IP CATEGORY entry
////// ip lan Type CRUD
			
		elseif($_POST['formName'] == 'add-ip-types'):
			
			// $data = $_POST['type']->csrf;
			// $data = (array)$_POST['type'];
			$data = json_decode($_POST['type'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_NAME']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_IP_VLAN_TYPE WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($data['csrf']);
					unset($data['N_ID']);
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					$accessoriesModel->insertData("IT_ASSET_IP_VLAN_TYPE", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'IP type added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'IP type Already Here.',
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

		elseif($_POST['formName'] == 'edit-ip-types'):
			
				$data = json_decode($_POST['type'], true);
				
				if($db->csrfVerify($data['csrf']) == 'success'):
					$unitfull = trim(strtoupper($data['V_NAME']));
					$unitID = $data['N_ID'];
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM IT_ASSET_IP_VLAN_TYPE WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);

						$data['N_UPDATED_BY'] = $auth->loggedUserId();
						$data['V_IP_ADDRESS'] = $ipaddress;
						$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN_TYPE", $data, "N_ID = $id");
						
						$response = array(
							'status' => true,
							'successmsg' => 'IP type updated successfully.'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => false,
							'successmsg' => 'IP type Already Here.',
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

			elseif($_POST['formName'] == 'delete-ip-types'):
			
				$data = json_decode($_POST['type'], true);

				// print_r($data);
				// exit;
				
				if($db->csrfVerify($data['csrf']) == 'success'):
				
					$unitID = $data['N_ID'];
	
						unset($data['formName']);
						unset($data['csrf']);
						$id = $data['N_ID'];
						unset($data['N_ID']);
						unset($data['V_NAME']);
						$accessoriesModel->dataUpdate("IT_ASSET_IP_VLAN_TYPE", $data, "N_ID = $id");

						$response = array(
							'status' => true,
							'successmsg' => 'IP type has been deleted successfully.'
						);
						echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors',
						'value' => 1
					);
					echo json_encode($response);
				endif;
	// end IP Type entry

    // Start Department entry
        elseif($_POST['formName'] == 'add-departments'):

            // $data = $_POST['type']->csrf;
            // $data = (array)$_POST['type'];
            $data = json_decode($_POST['type'], true);

            if($db->csrfVerify($data['csrf']) == 'success'):
                $unitfull = trim(strtoupper($data['V_NAME']));

                if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM DEPARTMENT WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
                    unset($_POST['formName']);
                    unset($data['csrf']);
                    unset($data['N_ID']);
                    $data['N_CREATED_BY'] = $auth->loggedUserId();
                    $data['V_IP_ADDRESS'] = $ipaddress;
                    $accessoriesModel->insertData("DEPARTMENT", $data);
                    $response = array(
                        'status' => true,
                        'successmsg' => 'Department added successfully.',
                    );
                    echo json_encode($response);
                else:
                    $response = array(
                        'status' => false,
                        'successmsg' => 'Department Already Here.',
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

        elseif($_POST['formName'] == 'edit-departments'):

            $data = json_decode($_POST['type'], true);

            if($db->csrfVerify($data['csrf']) == 'success'):
                $unitfull = trim(strtoupper($data['V_NAME']));
                $unitID = $data['N_ID'];
                if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM DEPARTMENT WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
                    unset($data['formName']);
                    unset($data['csrf']);
                    $id = $data['N_ID'];
                    unset($data['N_ID']);

                    $data['N_UPDATED_BY'] = $auth->loggedUserId();
                    $data['V_IP_ADDRESS'] = $ipaddress;
                    $accessoriesModel->dataUpdate("DEPARTMENT", $data, "N_ID = $id");

                    $response = array(
                        'status' => true,
                        'successmsg' => 'Department updated successfully.'
                    );
                    echo json_encode($response);
                else:
                    $response = array(
                        'status' => false,
                        'successmsg' => 'Department Already Here.',
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

        elseif($_POST['formName'] == 'delete-departments'):

            $data = json_decode($_POST['type'], true);

            // print_r($data);
            // exit;

            if($db->csrfVerify($data['csrf']) == 'success'):

                $unitID = $data['N_ID'];

                unset($data['formName']);
                unset($data['csrf']);
                $id = $data['N_ID'];
                unset($data['N_ID']);
                unset($data['V_NAME']);
                $accessoriesModel->dataUpdate("DEPARTMENT", $data, "N_ID = $id");

                $response = array(
                    'status' => true,
                    'successmsg' => 'Department has been deleted successfully.'
                );
                echo json_encode($response);
            else:
                $response = array(
                    'status' => 'errors',
                    'value' => 1
                );
                echo json_encode($response);
            endif;
    //End Department entry

    // Start Department entry
        elseif($_POST['formName'] == 'add-designations'):

        // $data = $_POST['type']->csrf;
        // $data = (array)$_POST['type'];
        $data = json_decode($_POST['type'], true);

        if($db->csrfVerify($data['csrf']) == 'success'):
            $unitfull = trim(strtoupper($data['V_NAME']));

            if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM DESIGNATION WHERE UPPER(V_NAME) = '$unitfull'") == 'not exist'):
                unset($_POST['formName']);
                unset($data['csrf']);
                unset($data['N_ID']);
                $data['N_CREATED_BY'] = $auth->loggedUserId();
                $data['V_IP_ADDRESS'] = $ipaddress;
                $accessoriesModel->insertData("DESIGNATION", $data);
                $response = array(
                    'status' => true,
                    'successmsg' => 'Designation added successfully.',
                );
                echo json_encode($response);
            else:
                $response = array(
                    'status' => false,
                    'successmsg' => 'Designation Already Here.',
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

        elseif($_POST['formName'] == 'edit-designations'):

        $data = json_decode($_POST['type'], true);

        if($db->csrfVerify($data['csrf']) == 'success'):
            $unitfull = trim(strtoupper($data['V_NAME']));
            $unitID = $data['N_ID'];
            if($accessoriesModel->checkDataExistence("SELECT UPPER(V_NAME) FROM DESIGNATION WHERE UPPER(V_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
                unset($data['formName']);
                unset($data['csrf']);
                $id = $data['N_ID'];
                unset($data['N_ID']);

                $data['N_UPDATED_BY'] = $auth->loggedUserId();
                $data['V_IP_ADDRESS'] = $ipaddress;
                $accessoriesModel->dataUpdate("DESIGNATION", $data, "N_ID = $id");

                $response = array(
                    'status' => true,
                    'successmsg' => 'Designation updated successfully.'
                );
                echo json_encode($response);
            else:
                $response = array(
                    'status' => false,
                    'successmsg' => 'Designation Already Here.',
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

        elseif($_POST['formName'] == 'delete-designations'):

        $data = json_decode($_POST['type'], true);

        // print_r($data);
        // exit;

        if($db->csrfVerify($data['csrf']) == 'success'):

            $unitID = $data['N_ID'];

            unset($data['formName']);
            unset($data['csrf']);
            $id = $data['N_ID'];
            unset($data['N_ID']);
            unset($data['V_NAME']);
            $accessoriesModel->dataUpdate("DESIGNATION", $data, "N_ID = $id");

            $response = array(
                'status' => true,
                'successmsg' => 'Designation has been deleted successfully.'
            );
            echo json_encode($response);
        else:
            $response = array(
                'status' => 'errors',
                'value' => 1
            );
            echo json_encode($response);
        endif;
        //End Department entry
	    endif;

	else:
		$response = array(
			'status' => 'invalidRequest'
		);
		echo json_encode($response);
	endif;
endif;

