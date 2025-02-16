<!DOCTYPE html>
<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1'); 

ini_set('memory_limit', '-1');
include_once('inc/head.php');

use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if ($auth->verifyNavigationPermission('draft workorder') || $auth->verifyNavigationPermission('publish workorder') || $auth->verifyNavigationPermission('approved workorder') || $auth->verifyNavigationPermission('accepted workorder') || $auth->verifyNavigationPermission('all workorder') || $auth->verifyNavigationPermission('checked')):
        $workorderOpt = new workorderoperation($db->con);
        $appsDependent = new dependentdata($db->con);
        $accessoriesModel = new accessoriescrud($db->con);
        $userid = $auth->loggedUserId();
        $userInfo = $auth->loggedUser();
        $managerFeature = $auth->getManagerFeatureAll();
?>

        <body class="m4-cloak h-vh-100">
            <div class="preloader" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, 0.8); left: 0;">
                <div data-role="activity" data-type="square" data-style="color" style="position: relative;top: 50%; left: 50%;"></div>
            </div>
            <div class="success-notification" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, .93); left: 0;">
            </div>
            <div data-role="navview" data-toggle="#paneToggle" data-expand="xxl" data-compact="xl" data-active-state="true">
                <?php include_once('inc/navigation.php'); ?>
                <div class="navview-content h-100">
                    <?php include_once('inc/topbar.php'); ?>
                    <div class="content-inner h-100" style="overflow-y: auto">
                        <div class="row border-bottom bd-lightGray pl-1 mr-1 ribbed-lightGray" style="margin-left: 0px;">
                            <div class="cell-md-4">
                                <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="work-order" data-page="<?= $pageOpt->currentPageClass() ?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Peacocks Order Details</h4>
                            </div>
                            <div class="cell-md-8">
                                <?php
                                if (!empty($pageOpt->previousPageUrl())): ?>
                                    <a href="<?= $pageOpt->previousPageUrl() ?>" class="image-button success place-right-md place-right bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                        <span class='mif-arrow-left icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                        <span class="caption text-bold">Back</span>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php
                        if ($auth->verifyUserPermission('draft workorder', 1) || $auth->verifyUserPermission('publish workorder', 1) || $auth->verifyUserPermission('approved workorder', 1) || $auth->verifyUserPermission('accepted workorder', 1) || $auth->verifyUserPermission('all workorder', 1)):
                        ?>
                            <style type="text/css">
                                .input {
                                    height: 28px !important;
                                    line-height: 28px !important;
                                }

                                .input input {
                                    height: 26px !important;
                                }
                            </style>

                            <div class="d-flex flex-justify-center">
                                <div class="cell-lg-12">
                                    <div data-role="panel" data-title-caption="Order Details Upload | Peacocks" data-title-icon="<span class='mif-plus'></span>" class="workorder-form-panel" data-collapsible="false">
                                        <div class="errors custom-alert"></div>
                                        <div class="success custom-alert"></div>
                                        <div class="p-1">
                                            <form method="POST" action="action/peacocksData.php" enctype="multipart/form-data">
                                                <input type="hidden" name="csrf" class="csrf" value="<?= $db->csrfToken() ?>">
                                                <div class="form-group">
                                                    <input type="file" data-role="file" data-mode="drop" name="uploadFile">
                                                </div>
                                                <div class="text-center">
                                                    <?php
                                                    if (isset($_SESSION['message'])) {
                                                        echo "<h2 class='text-center success'>" . $_SESSION['message'] . "</h2>";
                                                        unset($_SESSION['message']);
                                                    }
                                                    ?>
                                                    <input type="submit" class="button success" name="submit" value="Upload">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        else:
                            $auth->redirect403();
                        endif; ?>
                    </div>
                </div>
            </div>
            </div>
            <?php include_once('inc/footer.php'); ?>
        </body>
<?php
    else:
        $auth->redirect403();
    endif;
endif;
?>

</html>