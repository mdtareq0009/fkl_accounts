<!DOCTYPE html>
<?php 
include_once('inc/head_login.php');

// $token= $_GET['t'];
// $ipaddress = $_GET['t2'];

// $conn = oci_connect('assets', 'assets', '192.168.100.157:1522/erp');
// $sql = "SELECT * FROM FGSP_TOKEN WHERE IP_ADDRESS = '$ipaddress' AND TOKEN='$token'";
// $qResult = oci_parse($conn, $sql);

// if (oci_execute($qResult)) {

// 	$updateSql = "UPDATE FGSP_TOKEN SET APPLICATION='Accessories' WHERE IP_ADDRESS='$ipaddress' AND TOKEN='$token'";
// 	$updateResult = oci_parse($conn, $updateSql);
// 	oci_execute($updateResult);

//     $user = oci_fetch_assoc($qResult);
//     if ($user === FALSE) {
//         header("Location: http://192.168.100.20/");
//     }
// } else {
//     die('Something Went Wrong!' . oci_error($conn));
// }


if($auth->authUser()):
    $url = $pageOpt->getRequestedPage();
    header("location: $url");
else:
?>
<body class="m4-cloak" style="background: linear-gradient(#3fa3ac,#fff) !important;">
    <div class="h-vh-100 d-flex flex-justify-center flex-align-center">
        <div class="login-box">
            <form class="bg-white p-4 login-form" method="POST" action="">
                <img src="images/logo110x51.png" class="place-right mt-5-minus mr-6-minus">
                <h1 class="mb-0">Login</h1>
                <div class="fg-teal mb-3 mt-2" style="font-size: 14px;">ACCESSORIES STORE AUTOMATION SYSTEM</div>
                <div class="faildlogin mb-3 mt-2" style="color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; font-size: 14px;">
                    
                </div>
                <div class="form-group">
                    <input type="text" data-role="input" placeholder="FKLID" name='fklid' data-append="<span class='mif-user'>" data-validate="required">
                    <span class="invalid_feedback">Enter your valid FKLID</span>
                </div>
                <div class="form-group">
                    <input type="password" data-role="input" name="password" placeholder="Password" data-append="<span class='mif-key'>" data-validate="required">
                    <span class="invalid_feedback">Enter your valid password</span>
                </div>
                <div class="form-group d-flex flex-align-center flex-justify-between">
                    <div></div>
                    <button class="image-button outline dark icon-right" type="submit">
                        <span class="mif-settings-power icon"></span>
                        <span class="caption">Login</span>
                    </button>
                    
                </div>
		<div class="form-group border-top bd-default pt-2">
               		<a href="http://192.168.100.20/" class="d-block">Back to the FKL Portal</a>
            	</div>

            </form>
        </div>
    </div>
    <div style="position: absolute;right: 25px;bottom: 20px;color: #868686;font-family: cursive;">Developed by <img src="images/FKLIT.png" style="width: 75px;background: #fff;border-radius: 13px 0px 13px 0px;margin-left: 5px;padding: 2px;border: 1px dashed #5e5e5e;"></div>
        <?php include_once('inc/footer_login.php'); ?>
    </body>
    <?php endif; ?>
</html>