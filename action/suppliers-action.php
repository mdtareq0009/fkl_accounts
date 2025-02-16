<?php
require_once '../ini.php';
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $auth->loginPageRedirect();
else:
	$accessoriesModel = new accessoriescrud($db->con);

	if(isset($_POST['formName'])):

		if($_POST['formName'] == 'add-suppliers'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$suppliersname = strtoupper($_POST['VNAME']);

				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME) FROM ACCESSORIES_SUPPLIERS WHERE UPPER(VNAME) = '$suppliersname' ") == 'not exist'):
					unset($_POST['formName']);
					unset($_POST['csrf']);
					$lastId = $accessoriesModel->lastRowId('ACCESSORIES_SUPPLIERS', 'NID');
					$_POST['NID'] = $lastId;
					$_POST['ALTID'] = 'ACCESSORIES'.$lastId;
					$_POST['VCREATEDUSER'] = $auth->loggedUserId();
					$_POST['VCREATEDAT'] = date('d-m-Y');
					$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId(); 
					$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
					$accessoriesModel->insertData("ACCESSORIES_SUPPLIERS", $_POST);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Supplier added successfully.',
						'viewUrl' => 'suppliers.php?page=all-suppliers',
						'createUrl' => 'suppliers.php?page=create-new'
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
					'status' => 'errors',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		//next elseif
		elseif($_POST['formName'] == 'delete-supplier'):
			unset($_POST['formName']);
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT NSUPLLIERID FROM ACCESSORIES_WORKORDERMASTER WHERE NSUPLLIERID = $id") == 'not exist'):
				if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_SUPPLIERS WHERE nid = $id") == 'exist'):
					$where = 'NID = '.$id;
					$accessoriesModel->deleteSingleRow('ACCESSORIES_SUPPLIERS', $where);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Supplier information has been deleted successfully.'
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
					'status' => 'errors',
					'errormsg' => 'Opps ! Delete failed because supplier name is already used in a workorder.',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'edit-suppliers'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				unset($_POST['formName']);
				unset($_POST['csrf']);
				$id = $_POST['id'];
				unset($_POST['id']);
				$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
				$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId();
				$accessoriesModel->dataUpdate("ACCESSORIES_SUPPLIERS", $_POST, "NID = $id");
				$response = array(
					'status' => 'success',
					'successmsg' => 'Supllier information updated successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 1
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

