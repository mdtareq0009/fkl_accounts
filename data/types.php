<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);

	$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
	$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Number of records to fetch each time



	// $masterData = $accessoriesModel->getData("SELECT * FROM (SELECT
	// 			options.NID,
	// 			options.VNAME,
	// 			options.VCREATEDAT,
	// 			options.VCREATEDUSER,
	// 			createdemp.VEMPNAME AS createduser,
	// 			options.VLASTUPDATEDAT,
	// 			options.VLASTUPDATEDUSER,
	// 			updatedemp.VEMPNAME AS updateduser,
	// 			ROW_NUMBER() OVER (ORDER BY options.NID DESC) AS rnum
	// 		FROM
	// 			ACCESSORIES_TYPES options
	// 			LEFT JOIN hrm_employee createdemp ON createdemp.VEMPLOYEEID = options.VCREATEDUSER
	// 			LEFT JOIN hrm_employee updatedemp ON updatedemp.VEMPLOYEEID = options.VLASTUPDATEDUSER
	// 		ORDER BY
	// 			options.NID DESC
	// )
    //      WHERE rnum > $offset AND rnum <= ($offset + $limit)
	// ");
	$masterData = $accessoriesModel->getData("SELECT
				options.NID,
				options.VNAME,
				options.VCREATEDAT,
				options.VCREATEDUSER,
				createdemp.VEMPNAME AS createduser,
				options.VLASTUPDATEDAT,
				options.VLASTUPDATEDUSER,
				updatedemp.VEMPNAME AS updateduser
			FROM
				ACCESSORIES_TYPES options
				LEFT JOIN hrm_employee createdemp ON createdemp.VEMPLOYEEID = options.VCREATEDUSER
				LEFT JOIN hrm_employee updatedemp ON updatedemp.VEMPLOYEEID = options.VLASTUPDATEDUSER
			ORDER BY
				options.NID DESC
	
	");
	
	echo json_encode($masterData,  JSON_UNESCAPED_SLASHES);
	else:
		$auth->redirect403();
	endif;
else:
	$auth->loginPageRedirect();
endif;
?>