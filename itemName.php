<!DOCTYPE html>
<?php
include_once('inc/head.php');
use accessories\accessoriescrud;

if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyUserPermission('checked', 9)):
        $accessoriesModel = new accessoriescrud($db->con);
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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="setting" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-cogs"></span></span> Item Name</h4>
                    </div>
                    <div class="cell-md-8">
                        <?php if(!empty($pageOpt->previousPageUrl())): ?>
                        <a href="<?=$pageOpt->previousPageUrl()?>" class="image-button success place-right-md place-right bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                            <span class='mif-arrow-left icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                            <span class="caption text-bold">Back</span>
                        </a>
                            <?php
                            if($_GET['page'] == 'edit' && $auth->verifyUserPermission('groups', 1)):
                            ?>
                            <a href="stylename.php?page=create-new" class="image-button success place-right-md border mr-2 place-right bd-dark-hover" style="height: 22px;">
                                <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                <span class="caption text-bold">Add New</span>
                            </a>
                            <?php
                            endif;
                            ?>
                        <?php else:
                            if($auth->verifyUserPermission('groups', 1)): 
                        ?>
                                <a href="stylename.php?page=create-new" class="image-button success place-right-md border place-right bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Add New</span>
                                </a>
                        <?php
                           endif;
                        endif  
                        ?>
                    </div>
                </div>
                <div class="card">
                    <div class="row">
                        <div class="cell-md-4"></div>
                        <div class="cell-md-4">
                            <div class="form-group">
                                <label>Item Name<span class="fg-red">*</span></label>
                                <input type="text" data-role="input" class="input-small required-field" name="item" id="item" placeholder="Enter Item Name" value="">
                                <span class="invalid_feedback">Item name is required.</span>
                            </div>
                        </div>
                        <div class="cell-md-4"></div>
                        <div class="cell-md-4"></div>
                        <div class="cell-md-4">
                            <div class="cell-12 d-flex flex-justify-center mt-2">
                                <button type="text" name="style-submit" class="image-button rounded border bd-dark-hover success mr-2" onclick="insertDropdown('item','INV.MRD_ITEM','MRD_ITEM_ID','MRD_ITEM_NAME','MRD_ITEM_SL')">
                                    <span class='mif-done icon'></span>
                                    <span class="caption text-bold">Save</span>
                                </button>
                            </div>
                        </div>
                        <div class="cell-md-4"></div>
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