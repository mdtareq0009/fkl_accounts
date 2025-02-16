<?php
require_once '../ini.php';
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $auth->loginPageRedirect();
else:
	$accessoriesModel = new accessoriescrud($db->con);

	if(isset($_POST['formName'])):
		if($_POST['formName'] == 'change-password'):
			if($db->csrfVerify($_POST['csrf']) == 'success'):
				$loggedId = $auth->loggedUserId();
				$currentPass = $_POST['cpass'];
				if($accessoriesModel->checkDataExistence("SELECT vuserid FROM ADN_USERINFORMATION WHERE vuserid = '$loggedId' AND vpassword = '$currentPass'") == 'exist'):
					if($_POST['npass'] == $_POST['conpass']):
						$dataArr = array();
						$dataArr['vpassword'] = $_POST['npass'];
						$accessoriesModel->dataUpdate("ADN_USERINFORMATION", $dataArr, "vuserid = '$loggedId'");
						$response = array(
							'status' => 'success'
						);
						echo json_encode($response);
					else:
						$response = array(
							'status' => 'errors',
							'value' => 3
						);
						echo json_encode($response);
					endif;
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

		elseif($_POST['formName'] == 'userfinder'):
			$fklid = $_POST['fklid'];
			if($accessoriesModel->checkDataExistence("SELECT VUSERID FROM ERP.ADN_USERINFORMATION WHERE vempid = '$fklid'") == 'exist'):
				// $userInfo = $accessoriesModel->getData("SELECT vempname FROM hrm_employee WHERE vemployeeid = '$fklid'");
				$userInfo = $accessoriesModel->getData("SELECT VEMPNAME as VEMPNAME FROM ERP.hrm_employee WHERE VEMPLOYEEID = '$fklid'");
				$response = array(
					'status' => 'success',
					'name' => $userInfo[0]['VEMPNAME']
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors'
				);
				echo json_encode($response);
			endif;

		elseif($_POST['formName'] == 'managerfinder'):
			$fklid = $_POST['fklid'];
			if($accessoriesModel->checkDataExistence("SELECT vemployeeid FROM ERP.hrm_employee WHERE vemployeeid = '$fklid'") == 'exist'):
				$userInfo = $accessoriesModel->getData("SELECT VEMPNAME FROM ERP.hrm_employee WHERE vemployeeid = '$fklid'");
				// if($accessoriesModel->checkDataExistence("SELECT empname as vempname FROM hr_employeeinfo@crypton WHERE empid = '$fklid'") == 'exist'):
				// $userInfo = $accessoriesModel->getData("SELECT empname as vempname FROM hr_employeeinfo@crypton WHERE empid = '$fklid'");
				$response = array(
					'status' => 'success',
					'name' => $userInfo[0]['VEMPNAME']
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors'
				);
				echo json_encode($response);
			endif;
			elseif($_POST['formName'] == 'subordinatefinder'):
				$fklid = $_POST['fklid'];
				if($accessoriesModel->checkDataExistence("SELECT vemployeeid FROM ERP.hrm_employee WHERE vemployeeid = '$fklid'") == 'exist'):
					$userInfo = $accessoriesModel->getData("SELECT vempname FROM ERP.hrm_employee WHERE vemployeeid = '$fklid'");
					// if($accessoriesModel->checkDataExistence("SELECT empname as vempname FROM hr_employeeinfo@crypton WHERE empid = '$fklid'") == 'exist'):
					// 	$userInfo = $accessoriesModel->getData("SELECT empname as vempname FROM hr_employeeinfo@crypton WHERE empid = '$fklid'");
					$response = array(
						'status' => 'success',
						'name' => $userInfo[0]['VEMPNAME']
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors'
					);
					echo json_encode($response);
				endif;
			elseif($_POST['formName'] == 'subordinatefinder-2'):
				$fklid = $_POST['fklid'];
				if($accessoriesModel->checkDataExistence("SELECT vemployeeid FROM ERP.hrm_employee WHERE vemployeeid = '$fklid'") == 'exist'):
					$userInfo = $accessoriesModel->getData("SELECT vempname FROM ERP.hrm_employee WHERE vemployeeid = '$fklid'");
					// if($accessoriesModel->checkDataExistence("SELECT empname as vempname FROM hr_employeeinfo@crypton WHERE empid = '$fklid'") == 'exist'):
					// $userInfo = $accessoriesModel->getData("SELECT empname as vempname FROM hr_employeeinfo@crypton WHERE empid = '$fklid'");
					$response = array(
						'status' => 'success',
						'name' => $userInfo[0]['VEMPNAME']
					);
					echo json_encode($response);
				else:
					$response = array(
						'status' => 'errors'
					);
					echo json_encode($response);
				endif;
		elseif($_POST['formName'] == 'user-insert'):
			// print_r($_POST['formName']);
			// exit();
			unset($_POST['formName']);
			$fklid = $_POST['VFKLID'];
			if($accessoriesModel->checkDataExistence("SELECT vfklid FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$fklid'") == 'exist'):
				$response = array(
					'status' => 'errors'
				);
				echo json_encode($response);
			else:
				$data = array();
				foreach ($_POST as $key => $value):
					
					$data[$key] = is_array($value) ? implode(',', $value) : $value;
				endforeach;
				$data['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_USERSPERMISSION', 'NID');
				// echo "<pre>";
				// print_r($data);
				// exit();

				$result = $accessoriesModel->insertData('ACCESSORIES_USERSPERMISSION', $data);
				$response = array(
					'status' => 'success',
					'result' => $result
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'user-update'):
			unset($_POST['formName']);
			$fklid = $_POST['VFKLID'];
			if($accessoriesModel->checkDataExistence("SELECT vfklid FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$fklid'") == 'exist'):
				$data = array();
				foreach ($_POST as $key => $value):
					$data[$key] = is_array($value) ? implode(',', $value) : $value;
				endforeach;
				$accessoriesModel->deleteSingleRow("ACCESSORIES_USERSPERMISSION", "vfklid = $fklid");
				$data['NID'] = $accessoriesModel->lastRowId('ACCESSORIES_USERSPERMISSION', 'NID');
				$accessoriesModel->insertData('ACCESSORIES_USERSPERMISSION', $data);
				$response = array(
					'status' => 'success',
					'id' => $data['NID']
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors'
				);
				echo json_encode($response);
			endif;
		elseif($_POST['formName'] == 'delete-user'):
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_USERSPERMISSION WHERE nid = $id") == 'exist'):
				$where = 'NID = '.$id;
				$accessoriesModel->deleteSingleRow('ACCESSORIES_USERSPERMISSION', $where);
				$response = array(
					'status' => 'success',
					'successmsg' => 'User has been deleted successfully.'
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
					'value' => 2
				);
				echo json_encode($response);
		    endif;
		elseif($_POST['formName'] == 'activeinactive-user'):
			$id = $_POST['id'];
			if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_USERSPERMISSION WHERE nid = $id") == 'exist'):
				$where = 'NID = '.$id;
				if($_POST['operate'] == 'active'){
					$accessoriesModel->dataUpdate('ACCESSORIES_USERSPERMISSION', array('NSTATUS' => 1), $where);
				}
				if($_POST['operate'] == 'inactive'){
					$accessoriesModel->dataUpdate('ACCESSORIES_USERSPERMISSION', array('NSTATUS' => 0), $where);
				}
				$response = array(
					'status' => 'success',
				);
				echo json_encode($response);
			else:
				$response = array(
					'status' => 'errors',
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

