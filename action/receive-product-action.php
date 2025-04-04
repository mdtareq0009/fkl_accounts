<?php
require_once '../ini.php';
use accessories\accessoriescrud;

if (!$auth->authUser()) {
    $auth->loginPageRedirect();
    exit;
}

header('Content-Type: application/json');
$ipaddress = getenv("REMOTE_ADDR");
$accessoriesModel = new accessoriescrud($db->con);

// Check if 'actionType' exists in POST
if (isset($_POST['actionType'])) {

    // Action Type: Add Receive Product
    if ($_POST['actionType'] == 'add-receive-product') {

        // Decode JSON data from POST
        $receiveInfoData = json_decode($_POST['receiveInfoForm'], true); // Receive entry form data
        $productDetailsData = json_decode($_POST['productDetailsForm'], true); // Product Details Form Data
//        print_r($receiveInfoData);
//        print_r($productDetailsData);
//        exit;

        // CSRF Verification
        if ($db->csrfVerify($receiveInfoData['csrf']) == 'success') {

            // Step 1: Insert into RECEIVE_MASTER table
            unset($receiveInfoData['csrf']); // Remove CSRF key
            unset($_POST['actionType']);
            unset($receiveInfoData['N_ID']);

            $receiveInfoData['N_CREATED_BY'] = $auth->loggedUserId();
            $receiveInfoData['V_IP_ADDRESS'] = $ipaddress;

            // Get the last ID for RECEIVE_MASTER
            $lastId = $accessoriesModel->lastRowId('RECEIVE_MASTER', 'N_ID');
            $receiveInfoData['N_ID'] = $lastId;
//            print_r($receiveInfoData);

            $accessoriesModel->insertData("RECEIVE_MASTER", $receiveInfoData);

            // Step 2: Insert into RECEIVE_DETAILS table
            if (!empty($productDetailsData)) {
                foreach ($productDetailsData as $product) {
                    $detailsData                        = array();
                    $detailsData['N_DEMAND_MASTER_ID']  = $receiveInfoData['N_ID'];
                    $detailsData['N_PRODUCT_ID']        = $product['product_id'];
                    $detailsData['V_MODEL_NO']          = $product['model'];
                    $detailsData['V_PARTS_NO']          = '';                        // Add if available
                    $detailsData['N_QUANTITY']          = $product['qty'];
                    $detailsData['N_DELIVERY_QUANTITY'] = 0;
                    $detailsData['N_CATEGORY_ID']       = $product['category_id'];
                    $detailsData['V_WARRENTY']          = $product['warranty'];
                    $detailsData['V_LIFETIME']          = $product['life_time'];
                    $detailsData['IS_SERIAL_ITEM']      = $product['is_serial'];
                    $detailsData['IS_MACHINE']          = $product['is_machine'];
                    $detailsData['V_NOTE']              = $product['note'];
                    $detailsData['N_CREATED_BY']        = $auth->loggedUserId();
                    $detailsData['V_IP_ADDRESS']        = $ipaddress;

                    // Get the last ID for RECEIVE_DETAILS
                    $lastId = $accessoriesModel->lastRowId('RECEIVE_DETAILS', 'N_ID');
                    $detailsData['N_ID'] = $lastId;

                    $accessoriesModel->insertData("RECEIVE_DETAILS", $detailsData);

                    // Step 3: Insert into PRODUCT_SERIAL_DETAILS table (if serial items exist)
                    if ($product['is_serial'] == 'Yes' && !empty($product['serialItems'])) {
                        foreach ($product['serialItems'] as $serial) {
                            $serialData                         = array();
                            $serialData['N_RECEIVE_MASTER_ID']  = $receiveInfoData['N_ID'];
                            $serialData['N_RECEIVE_DETAILS_ID'] = $detailsData['N_ID'];
                            $serialData['N_PRODUCT_ID']         = $product['product_id'];
                            $serialData['V_SERIAL_NO']          = $serial['serial_no'];
                            $serialData['V_RECEIVE_DATE']       = date('Y-m-d');
                            $serialData['V_RECEIVE_STATUS']     = 'Received';
                            $serialData['V_RECEIVE_QUANTITY']   = $serial['qty'];
                            $serialData['N_CREATED_BY']         = $auth->loggedUserId();
                            $serialData['V_IP_ADDRESS']         = $ipaddress;

                            // Get the last ID for PRODUCT_SERIAL_DETAILS
                            $lastId = $accessoriesModel->lastRowId('PRODUCT_SERIAL_DETAILS', 'N_ID');
                            $serialData['N_ID'] = $lastId;

                            $accessoriesModel->insertData("PRODUCT_SERIAL_DETAILS", $serialData);
                        }
                    }
                }
            }

            // Send success response
            $response = array(
                'status' => true,
                'successmsg' => 'Receive entry and product details added successfully.'
            );
            echo json_encode($response);

        } else {
            // CSRF verification failed
            $response = array(
                'status' => 'errors',
                'value' => 1
            );
            echo json_encode($response);
        }
    }




    else {
        // Invalid actionType
        $response = array(
            'status' => 'invalidAction',
            'message' => 'Invalid action type provided.'
        );
        echo json_encode($response);
    }

} else {
    // No actionType in POST request
    $response = array(
        'status' => 'invalidRequest',
        'message' => 'No actionType specified in the request.'
    );
    echo json_encode($response);
}
