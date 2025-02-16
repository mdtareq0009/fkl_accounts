<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);


	if(isset($_GET['formName'])):

		if($_GET['formName'] == 'product-code'):
			$prefix = 'P-';
			$productcode = $accessoriesModel->countRowId('IT_ASSET_PRODUCT', 'N_ID',$prefix);
			echo $productcode;
		endif;
		if($_GET['formName'] == 'employee-code'):
			$prefix = 'EMP-';
			$productcode = $accessoriesModel->countRowId('EMPLOYEE', 'N_ID',$prefix);
			echo $productcode;
		endif;
		if($_GET['formName'] == 'ip-assign-code'):
			$prefix = 'IPA-';
			$productcode = $accessoriesModel->countRowId('IT_ASSET_IP_VLAN_ASSIGN_MASTER', 'N_ID',$prefix);
			echo $productcode;
		endif;
		if($_GET['formName'] == 'combian-code'):
			$year = date('y');
			$prefix = 'FKL'.'.'. $year . '.';
			$productcode = $accessoriesModel->countRowId('IT_ASSET_COMBIAN_MASTER', 'N_ID',$prefix);
			echo $productcode;
		endif;
		if($_GET['formName'] == 'receive-code'):
			$prefix = 'DRM-';
			$productcode = $accessoriesModel->countRowId('IT_ASSET_RECEIVE_MASTER', 'N_ID',$prefix);
			echo $productcode;
		endif;
		if($_GET['formName'] == 'machine-code'):
			$prefix = 'FKL';
			$year = date('y');
			$prefix_year = $prefix .'.'. $year .'.';
			$productcode = $accessoriesModel->countRowId('IT_ASSET_COMBIAN_MASTER', 'N_ID', $prefix_year);
			echo $productcode;
		endif;

	endif;
	else:
		$auth->redirect403();
	endif;
else:
	$auth->loginPageRedirect();
endif;
?>