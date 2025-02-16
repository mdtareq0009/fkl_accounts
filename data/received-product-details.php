<?php
require_once('../ini.php');
use accessories\accessoriescrud;
if($auth->authUser()):
	if($auth->verifyUserPermission('types', 4)):
	$accessoriesModel = new accessoriescrud($db->con);
	$clause = '';

        if (isset($_GET['product_id']) && !empty($_GET['product_id'])) {
            $product_id = $_GET['product_id'];

            $productDetails = $accessoriesModel->getData("
                SELECT 
                    N_PRODUCT_ID,
                    V_MODEL_NO,
                    V_WARRENTY,
                    V_LIFETIME,
                    N_QUANTITY,
                    V_NOTE
                FROM 
                    RECEIVE_DETAILS 
                WHERE 
                    N_PRODUCT_ID = '$product_id' 
                    AND C_STATUS = 'a'
            ");

            echo json_encode($productDetails, JSON_UNESCAPED_SLASHES);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Product ID is missing.']);
        }
	else:
		$auth->redirect403();
	endif;
else:
	$auth->loginPageRedirect();
endif;
?>