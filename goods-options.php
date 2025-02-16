<!DOCTYPE html>
<?php
include_once('inc/head.php');
use accessories\accessoriescrud;

if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyNavigationPermission('goods options')):
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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="setting" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-cogs"></span></span> Settings</h4>
                    </div>
                    <div class="cell-md-8">
                        <?php if(!empty($pageOpt->previousPageUrl())): ?>
                        <a href="<?=$pageOpt->previousPageUrl()?>" class="image-button success place-right-md place-right bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                            <span class='mif-arrow-left icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                            <span class="caption text-bold">Back</span>
                        </a>
                            <?php
                            if($_GET['page'] == 'edit' && $auth->verifyUserPermission('goods options', 1)):
                            ?>
                                <a href="goods-options.php?page=create-new" class="image-button success place-right-md border place-right mr-2  bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Add New</span>
                                </a>
                            <?php
                            endif;
                            ?>
                        <?php else: 
                            if($auth->verifyUserPermission('goods options', 1)): ?>
                                <a href="goods-options.php?page=create-new" class="image-button success place-right-md border place-right bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Add New</span>
                                </a>
                        <?php endif;
                        endif;
                         ?>
                    </div>
                </div>
                <?php
                /*================================================
               
                ================================================*/
                if($_GET['page'] == 'create-new'):
                    if($auth->verifyUserPermission('goods options', 1)):
                ?>
                <div class="d-flex flex-justify-center">
                    <div class="cell-lg-12">
                        <div data-role="panel" data-title-caption="Add Goods Option" data-title-icon="<span class='mif-plus'></span>" class="goods-options-form-panel" data-collapsible="false">
                        <div class="p-1">
                           <form method="POST" class="goods-options-form d-flex flex-justify-center" action="" onsubmit="postData('goods-options-form', 'action/goods-action.php');">
                                <input type="hidden" name="csrf"  value="<?=$db->csrfToken()?>">
                                <input type="hidden" name="formName" value="add-goods-options">
                                <div class="cell-sm-5 p-4 bg-white ">
                                    <div class="row">
                                        <div class="cell-sm-12">
                                            <div class="form-group">
                                                <label>Option Name<span class="fg-red">*</span></label>
                                                <input type="text" data-role="input" class="input-small required-field" name="VNAME" placeholder="Enter Options Name" value="">
                                                <span class="invalid_feedback">Option name is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-12 d-flex flex-justify-center mt-2">
                                            <button type="submit" name="goodsoptions-submit" class="image-button border bd-dark-hover success mr-2">
                                                <span class='mif-done icon'></span>
                                                <span class="caption text-bold">Save</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>                            
                        </div>
                        </div>
                    </div>
                </div>
                <?php
                    else:
                        $auth->redirect403();
                    endif;                
                elseif($_GET['page'] == 'edit'):
                    if($auth->verifyUserPermission('goods options', 2)):
                    $id = isset($_GET['id']) ? $_GET['id'] : 0;
                    if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_GOODSOPTIONS WHERE nid = $id") == 'exist'):
                    $optionData = $accessoriesModel->getData("SELECT nid, vname FROM ACCESSORIES_GOODSOPTIONS WHERE nid = $id");
                ?>
                <div class="d-flex flex-justify-center">
                    <div class="cell-lg-12">
                        <div data-role="panel" data-title-caption="Edit Goods Option" data-title-icon="<span class='mif-pencil'></span>" class="goods-options-form-panel" data-collapsible="false">
                        <div class="p-1">
                           <form method="POST" class="d-flex flex-justify-center" action="" onsubmit="updateData($(this), 'goods', 'goods-options.php?page=edit&id=<?=$id?>')">
                                <input type="hidden" name="csrf"  value="<?=$db->csrfToken()?>">
                                <input type="hidden" name="formName" value="edit-goods-options">
                                <input type="hidden" name="id" value="<?=$id?>">
                                <div class="cell-sm-5 p-4 bg-white ">
                                    <div class="row">
                                        <div class="cell-sm-12">
                                            <div class="form-group">
                                                <label>Option Name<span class="fg-red">*</span></label>
                                                <input type="text" data-role="input" class="input-small required-field" name="VNAME" placeholder="Enter Options Name" value="<?=$optionData[0]['VNAME']?>">
                                                <span class="invalid_feedback">Option name is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-12 d-flex flex-justify-center mt-2">
                                            <button type="submit"  class="image-button border bd-dark-hover secondary">
                                                <span class='mif-done icon'></span>
                                                <span class="caption text-bold">Update</span>
                                            </button>
                                            <a href="goods-options.php?page=edit&id=<?=$id?>" class="image-button border bd-dark-hover warning ml-2">
                                                <span class='mif-refresh icon'></span>
                                                <span class="caption text-bold">Refresh</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                            </form>                            
                        </div>
                        </div>
                    </div>
                </div>
                <?php
                    else:
                        $pageOpt->redirectWithscript($pageOpt->previousPageUrl(), 'Invalid goods option id!');
                    endif;
                else:
                    $auth->redirect403();
                endif;  
                /*================================================
                
                ================================================*/
                elseif($_GET['page'] == 'all-goods-options'):
                    if($auth->verifyUserPermission('goods options', 4)):
                ?>
                <div class="d-flex flex-justify-center">
                    <div class="cell-lg-12">
                        <div data-role="panel" data-title-caption="All Goods Options" data-title-icon="<span class='mif-layers'></span>" class="goods-options-form-panel" data-collapsible="false">
                        <div class="p-1">
                            <table 
                                class="table striped table-border cell-border subcompact mt-1 accessories-table-common"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7"
                                data-cls-rows-count="cell-md-5"
                                data-rows="18"
                                data-rows-steps="-1, 18, 30, 50, 100, 150"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="options-table"
                                data-source="data/goods-options.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from $1 to $2 of $3 Option(s)"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                            >
                            </table>        
                        </div>
                    </div>
                </div>
            </div>           
            <?php
                else:
                    $auth->redirect403();
                endif;
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
    else:
        $auth->redirect403();
    endif;
endif;
?>
</html>