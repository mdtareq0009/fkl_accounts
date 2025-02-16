<?php
require_once '../ini.php';
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $auth->loginPageRedirect();
else:
	header('Content-Type: application/json');
	$ipaddress = getenv("REMOTE_ADDR") ;
	$accessoriesModel = new accessoriescrud($db->con);


	if(isset($_POST['actionType'])):

        //// PRODUCT STORE
		if($_POST['actionType'] == 'add-products'):
			// $data = $_POST['productForm']->csrf;
			// $data = (array)$_POST['productForm'];
			$data = json_decode($_POST['productForm'], true);
			
			if($db->csrfVerify($data['csrf']) == 'success'):
				$unitfull = trim(strtoupper($data['V_PRODUCT_NAME']));
				$product_code = trim(strtoupper($data['V_PRODUCT_CODE']));
			
				if($accessoriesModel->checkDataExistence("SELECT UPPER(V_PRODUCT_NAME) FROM IT_ASSET_PRODUCT WHERE UPPER(V_PRODUCT_NAME) = '$unitfull'") == 'not exist'):
					if($accessoriesModel->checkDataExistence("SELECT UPPER(V_PRODUCT_CODE) FROM IT_ASSET_PRODUCT WHERE UPPER(V_PRODUCT_CODE) = '$product_code'") == 'exist'):
						$prefix = 'P-';
						$data['V_PRODUCT_CODE'] = $accessoriesModel->countRowId('IT_ASSET_PRODUCT', 'N_ID',$prefix);

					endif;

					unset($_POST['actionType']);
					unset($data['csrf']);
					unset($data['N_ID']);
					// $lastId = $accessoriesModel->lastRowId('CATEGORY', 'N_ID');
					// $data['NID'] = $lastId;
					$data['N_CREATED_BY'] = $auth->loggedUserId();
					$data['V_IP_ADDRESS'] = $ipaddress;
					// var_dump($data);
					// exit;
					$accessoriesModel->insertData("IT_ASSET_PRODUCT", $data);
					$response = array(
						'status' => true,
						'successmsg' => 'Product added successfully.',
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => false,
						'successmsg' => 'Product Already Here.',
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

        //// PRODUCT UPDATE
		elseif($_POST['actionType'] == 'edit-products'):
			
            $data = json_decode($_POST['productForm'], true);

            if($db->csrfVerify($data['csrf']) == 'success'):
                $unitfull = trim(strtoupper($data['V_PRODUCT_NAME']));
                $unitID = $data['N_ID'];
                if($accessoriesModel->checkDataExistence("SELECT UPPER(V_PRODUCT_NAME) FROM IT_ASSET_PRODUCT WHERE UPPER(V_PRODUCT_NAME) = '$unitfull' and N_ID != '$unitID'") == 'not exist'):
                    unset($data['actionType']);
                    unset($data['csrf']);
                    $id = $data['N_ID'];
                    unset($data['N_ID']);

                    $data['N_UPDATED_BY'] = $auth->loggedUserId();
                    $data['V_IP_ADDRESS'] = $ipaddress;
                    $accessoriesModel->dataUpdate("IT_ASSET_PRODUCT", $data, "N_ID = $id");

                    $response = array(
                        'status' => true,
                        'successmsg' => 'Product updated successfully.'
                    );
                    echo json_encode($response);
                else:
                    $response = array(
                        'status' => false,
                        'successmsg' => 'Product Already Here.',
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


        //// PRODUCT DELETE
		elseif($_POST['actionType'] == 'delete-products'):
			
            $data = json_decode($_POST['productForm'], true);

            // print_r($data);
            // exit;

            if($db->csrfVerify($data['csrf']) == 'success'):

                $unitID = $data['N_ID'];

                    unset($data['actionType']);
                    unset($data['csrf']);
                    $id = $data['N_ID'];
                    unset($data['N_ID']);
                    $accessoriesModel->dataUpdate("IT_ASSET_PRODUCT", $data, "N_ID = $id");

                    $response = array(
                        'status' => true,
                        'successmsg' => 'Product has been deleted successfully.'
                    );
                    echo json_encode($response);
            else:
                $response = array(
                    'status' => 'errors',
                    'value' => 1
                );
                echo json_encode($response);
            endif;

	
		endif;

	else:
		$response = array(
			'status' => 'invalidRequest'
		);
		echo json_encode($response);
	endif;
endif;

