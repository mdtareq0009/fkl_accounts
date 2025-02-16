<!DOCTYPE html>
<?php
include_once('inc/head.php');

if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
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
            <?php include_once('inc/topbar.php');?>
            <div class="content-inner h-100" style="overflow-y: auto">
                <div class="row border-bottom bd-lightGray pl-1 mr-1 ribbed-lightGray" style="margin-left: 0px;">
                    <div class="cell-md-4">
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="help" data-page="need-help" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-help"></span></span> Help</h4>
                    </div>
                    <div class="cell-md-8">
                       
                    </div>
                </div>
            <?php
            if($_GET['page'] == 'need-help'): 
            ?>
            <div class="d-flex flex-justify-center">
                <div class="cell-lg-12 text-center">
                    <div class="display3 fg-orange mt-0">Need Help?</div>
                    <div class="h1 text-center m-8">Contact<br>Software Development Team</div>
                    <div class="text-leader2 text-center page-error-box mx-auto">
                        <div class="h3 text-center m-8">Manager: Ext. 1401</div>
                        <div class="h4 text-center m-8">Developer: Ext. 1409 & 1410</div>
                    </div>                       
                </div>
            </div>           
            <?php
            else:
                $pageOpt->redirectWithscript($pageOpt->pageFirst(), 'Requested page is invalid!');
            endif;
            ?>
        </div>
    </div>
</div>
</div>
<?php include_once('inc/footer.php'); ?>
</body>
<?php
endif;
?>
</html>