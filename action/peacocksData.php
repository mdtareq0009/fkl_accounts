<?php
require_once '../ini.php';

use accessories\accessoriescrud;

if (!$auth->authUser()):
    $auth->loginPageRedirect();
else:
    $accessoriesModel = new accessoriescrud($db->con);

    if (isset($_POST['submit'])) {
        if ($db->csrfVerify($_POST['csrf']) == 'success') {
            $fileName = $_FILES['uploadFile']['name'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

            $allowed_ext = ['xls', 'csv', 'xlsx'];
            $dataArr = [];
            if (in_array($file_ext, $allowed_ext)) {
                $inputFileNamePath = $_FILES['uploadFile']['tmp_name'];
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
                $data = $spreadsheet->getActiveSheet()->toArray();

                $count = "0";
                foreach ($data as $row) {
                    if ($count > 0) {
                        $orderInfo = $accessoriesModel->getData("select tmob.norderid, tmob.vordernumber as orderno, tmob.vlot as lot, tc.vcolcode as ccode, tc.vcolname as color, tc.ncolsl colsl, tm.nks_id as nksid from erp.mer_monthlyorderbooking tmob
                        left join erp.mer_ks_master tm on tmob.norderid=tm.nordercode
                        left join erp.mer_ks_color tc on tm.nks_id=tc.nks_id and instr(upper('".$row[6]."'),upper(tc.vcolname))  > 0  and instr(upper(tc.vcolcode),upper('".$row[8]."')) > 0
                        where tmob.vordernumber='".$row[0]."' and tmob.vlot like '%".$row[1]."%'");

                        $dataArr['NORDERID'] = $orderInfo[0]['NORDERID'];
                        $dataArr['VEAN'] = $row[3];
                        $dataArr['VIPLCODE'] = $row[4];
                        $dataArr['VBUYERUNITPRICE'] = $row[7];
                        $dataArr['VLOT'] = $row[1];
                        $dataArr['VUSERID'] = $auth->loggedUserId();
                        $dataArr['DENTRYDATE'] = date('d-M-Y');
                        $dataArr['VPACKSIZE'] = $row[5];
                        $dataArr['NKS_ID'] = $orderInfo[0]['NKSID'];
                        $dataArr['NCOLSL'] = $orderInfo[0]['COLSL'];
                        $dataArr['VSIZE'] = $row[2];

                        if ($accessoriesModel->insertData('erp.mer_ks_peacock_details',$dataArr) == true) {
                            $msg = true;
                        }
                    } else {
                        $count = "1";
                    }
                }

                if (isset($msg)) {
                    $_SESSION['message'] = "Successfully Imported!";
                    header('Location: ../peacocksOrderDetails.php');
                    exit(0);
                } else {
                    $_SESSION['message'] = "Not Imported";
                    header('Location: ../peacocksOrderDetails.php');
                    exit(0);
                }
            } else {
                $_SESSION['message'] = "Invalid File";
                header('Location: ../peacocksOrderDetails.php');
                exit(0);
            }
        }
    }
endif;
