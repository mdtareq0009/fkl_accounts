<?php
require_once '../ini.php';
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $auth->loginPageRedirect();
else:
	$accessoriesModel = new accessoriescrud($db->con);

	if(isset($_POST['formName'])):

		if($_POST['formName'] == 'add-goods-options'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$optionName = strtoupper($_POST['VNAME']);

				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME) FROM ACCESSORIES_GOODSOPTIONS WHERE UPPER(VNAME) = '$optionName' ") == 'not exist'):
					unset($_POST['formName']);
					unset($_POST['csrf']);
					$lastId = $accessoriesModel->lastRowId('ACCESSORIES_GOODSOPTIONS', 'NID');
					$_POST['NID'] = $lastId;
					$_POST['VCREATEDUSER'] = $auth->loggedUserId();
					$_POST['VCREATEDAT'] = date('d-m-Y');
					$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId(); 
					$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
					$accessoriesModel->insertData("ACCESSORIES_GOODSOPTIONS", $_POST);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Goods option added successfully.',
						'viewUrl' => 'goods-options.php?page=all-goods-options',
						'createUrl' => 'goods-options.php?page=create-new'
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
		elseif($_POST['formName'] == 'add-groups'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$group = strtoupper($_POST['VNAME']);

				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME) FROM ACCESSORIES_GROUP WHERE UPPER(VNAME) = '$group' ") == 'not exist'):
					unset($_POST['formName']);
					unset($_POST['csrf']);
					$lastId = $accessoriesModel->lastRowId('ACCESSORIES_GROUP', 'NID');
					$_POST['NID'] = $lastId;
					$_POST['VCREATEDUSER'] = $auth->loggedUserId();
					$_POST['VCREATEDAT'] = date('d-m-Y');
					$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId(); 
					$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
					$accessoriesModel->insertData("ACCESSORIES_GROUP", $_POST);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Group added successfully.',
						'viewUrl' => 'groups.php?page=all-groups',
						'createUrl' => 'groups.php?page=create-new'
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
		elseif($_POST['formName'] == 'edit-groups'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				unset($_POST['formName']);
				unset($_POST['csrf']);
				$id = $_POST['id'];
				unset($_POST['id']);
				$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
				$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId();
				$accessoriesModel->dataUpdate("ACCESSORIES_GROUP", $_POST, "NID = $id");
				$response = array(
					'status' => 'success',
					'successmsg' => 'Group name updated successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 1
				);
				echo json_encode($response);
			endif;

		elseif($_POST['formName'] == 'add-subgroups'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$subgroup = strtoupper($_POST['VNAME']);

				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME) FROM ACCESSORIES_SUBGROUP WHERE UPPER(VNAME) = '$subgroup' ") == 'not exist'):
					unset($_POST['formName']);
					unset($_POST['csrf']);
					$lastId = $accessoriesModel->lastRowId('ACCESSORIES_SUBGROUP', 'NID');
					$_POST['NID'] = $lastId;
					$_POST['VCREATEDUSER'] = $auth->loggedUserId();
					$_POST['VCREATEDAT'] = date('d-m-Y');
					$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId(); 
					$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
					$accessoriesModel->insertData("ACCESSORIES_SUBGROUP", $_POST);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Sub-group added successfully.',
						'viewUrl' => 'subgroups.php?page=all-subgroups',
						'createUrl' => 'subgroups.php?page=create-new'
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
		elseif($_POST['formName'] == 'edit-subgroups'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				unset($_POST['formName']);
				unset($_POST['csrf']);
				$id = $_POST['id'];
				unset($_POST['id']);
				$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
				$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId();
				$accessoriesModel->dataUpdate("ACCESSORIES_SUBGROUP", $_POST, "NID = $id");
				$response = array(
					'status' => 'success',
					'successmsg' => 'Sub group name updated successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'add-materials-unit'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$unitfull = strtoupper($_POST['VNAME']);
				$unitshort = strtoupper($_POST['VNAMESHORT']);

				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME), UPPER(VNAMESHORT) FROM ACCESSORIES_QUANTITYUNIT WHERE UPPER(VNAME) = '$unitfull' OR UPPER(VNAMESHORT) = '$unitshort' ") == 'not exist'):
					unset($_POST['formName']);
					unset($_POST['csrf']);
					$lastId = $accessoriesModel->lastRowId('ACCESSORIES_QUANTITYUNIT', 'NID');
					$_POST['NID'] = $lastId;
					$_POST['VCREATEDUSER'] = $auth->loggedUserId();
					$_POST['VCREATEDAT'] = date('d-m-Y');
					$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId(); 
					$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
					$accessoriesModel->insertData("ACCESSORIES_QUANTITYUNIT", $_POST);
					$response = array(
						'status' => 'success',
						'successmsg' => 'MOU added successfully.',
						'viewUrl' => 'materials-unit.php?page=all-units',
						'createUrl' => 'materials-unit.php?page=create-new'
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
		elseif($_POST['formName'] == 'edit-materials-unit'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				unset($_POST['formName']);
				unset($_POST['csrf']);
				$id = $_POST['id'];
				unset($_POST['id']);
				$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
				$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId();
				$accessoriesModel->dataUpdate("ACCESSORIES_QUANTITYUNIT", $_POST, "NID = $id");
				$response = array(
					'status' => 'success',
					'successmsg' => 'MOU updated successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'add-goods'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$unitfull = strtoupper($_POST['VNAME']);

				if($accessoriesModel->checkDataExistence("SELECT UPPER(VNAME) FROM ACCESSORIES_GOODS WHERE UPPER(VNAME) = '$unitfull'") == 'not exist'):
					unset($_POST['formName']);
					unset($_POST['csrf']);
					$lastId = $accessoriesModel->lastRowId('ACCESSORIES_GOODS', 'NID');
					$_POST['NID'] = $lastId;
					$_POST['VPARAMETERS'] = implode(', ', $_POST['VPARAMETERS']);
					$_POST['VCREATEDUSER'] = $auth->loggedUserId();
					$_POST['VCREATEDAT'] = date('d-m-Y');
					$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId(); 
					$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
					$accessoriesModel->insertData("ACCESSORIES_GOODS", $_POST);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Goods added successfully.',
						'viewUrl' => 'goods.php?page=all-goods',
						'createUrl' => 'goods.php?page=create-new'
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
		elseif($_POST['formName'] == 'edit-goods'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				unset($_POST['formName']);
				unset($_POST['csrf']);
				$id = $_POST['id'];
				unset($_POST['id']);
				$_POST['VPARAMETERS'] = implode(', ', $_POST['VPARAMETERS']);
				$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
				$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId();
				$accessoriesModel->dataUpdate("ACCESSORIES_GOODS", $_POST, "NID = $id");
				$response = array(
					'status' => 'success',
					'successmsg' => 'Goods updated successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'edit-goods-options'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				unset($_POST['formName']);
				unset($_POST['csrf']);
				$id = $_POST['id'];
				unset($_POST['id']);
				$_POST['VLASTUPDATEDAT'] = date('d-m-Y');
				$_POST['VLASTUPDATEDUSER'] = $auth->loggedUserId();
				$accessoriesModel->dataUpdate("ACCESSORIES_GOODSOPTIONS", $_POST, "NID = $id");
				$response = array(
					'status' => 'success',
					'successmsg' => 'Goods option updated successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'delete-goods-options'):
			unset($_POST['formName']);
			$where = 'NID = '.$_POST['id'];
			$accessoriesModel->deleteSingleRow('ACCESSORIES_GOODSOPTIONS', $where);
			$response = array(
				'status' => 'success',
				'successmsg' => 'Goods option has been deleted successfully.'
			);
			echo json_encode($response);
			//next elseif
		elseif($_POST['formName'] == 'delete-goods'):
			unset($_POST['formName']);
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT NGOODSID FROM ACCESSORIES_WORKORDERITEMS WHERE NGOODSID = $id") == 'not exist'):
				if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_GOODS WHERE nid = $id") == 'exist'):
					$where = 'NID = '.$id;
					$accessoriesModel->deleteSingleRow('ACCESSORIES_GOODS', $where);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Goods information has been deleted successfully.'
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
					'errormsg' => 'Opps ! Delete failed because goods name is already used in a workorder.',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'delete-group'):
			unset($_POST['formName']);
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT NGROUPID FROM ACCESSORIES_SUBGROUP WHERE NGROUPID = $id") == 'not exist'):
				if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_GROUP WHERE nid = $id") == 'exist'):
					$where = 'NID = '.$id;
					$accessoriesModel->deleteSingleRow('ACCESSORIES_GROUP', $where);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Group has been deleted successfully.'
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
					'errormsg' => 'Opps ! Delete failed because group name is already used in a sub-group table.',
					'value' => 1
				);
				echo json_encode($response);
			endif;

		elseif($_POST['formName'] == 'delete-subgroup'):
			unset($_POST['formName']);
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT NSUBGROUPID FROM ACCESSORIES_GOODS WHERE NSUBGROUPID = $id") == 'not exist'):
				if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_SUBGROUP WHERE nid = $id") == 'exist'):
					$where = 'NID = '.$id;
					$accessoriesModel->deleteSingleRow('ACCESSORIES_SUBGROUP', $where);
					$response = array(
						'status' => 'success',
						'successmsg' => 'Sub-group has been deleted successfully.'
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
					'errormsg' => 'Opps ! Delete failed because sub-group name is already used in a goods table.',
					'value' => 1
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'delete-unit'):
			unset($_POST['formName']);
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT NQTYUNITID FROM ACCESSORIES_GOODS WHERE NQTYUNITID = $id") == 'not exist'):
				if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_QUANTITYUNIT WHERE nid = $id") == 'exist'):
					$where = 'NID = '.$id;
					$accessoriesModel->deleteSingleRow('ACCESSORIES_QUANTITYUNIT', $where);
					$response = array(
						'status' => 'success',
						'successmsg' => 'MOU has been deleted successfully.'
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
					'errormsg' => 'Opps ! Delete failed because MOU name is already used in a goods table.',
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

