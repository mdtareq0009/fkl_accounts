<!DOCTYPE html>
<?php
include_once('inc/head.php');
use accessories\accessoriescrud;
use accessories\dependentdata;

if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyNavigationPermission('goods')):
    $accessoriesModel = new accessoriescrud($db->con);
    $appsDependent = new dependentdata($db->con);
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
                        if($_GET['page'] == 'edit' && $auth->verifyUserPermission('goods', 1)):
                        ?>
                            <a href="goods.php?page=create-new" class="image-button success place-right-md border place-right mr-2 bd-dark-hover" style="height: 22px;">
                                <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                <span class="caption text-bold">Add New</span>
                            </a>
                        <?php
                        endif;
                        else:
                            if($auth->verifyUserPermission('goods', 1)):
                        ?>
                        <a href="goods.php?page=create-new" class="image-button success place-right-md border place-right bd-dark-hover" style="height: 22px;">
                            <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                            <span class="caption text-bold">Add New</span>
                        </a>
                       
                        <?php endif;
                        endif; ?>
                    </div>
                </div>
                <?php
                /*================================================
               
                ================================================*/
                if($_GET['page'] == 'create-new'):
                    if($auth->verifyUserPermission('goods', 1)):
                ?>
                <style type="text/css">
                .input{
                height: 28px !important;
                line-height: 28px !important;
                }
                .input input {
                height: 26px !important;
                }
                </style>
                <div class="d-flex flex-justify-center">
                    <div class="cell-lg-12">
                        <div data-role="panel" data-title-caption="Add Goods" data-title-icon="<span class='mif-plus'></span>" class="goods-form-panel" data-collapsible="false">
                        <div class="p-1">
                            <form method="POST" class="goods-form d-flex flex-justify-center" action="" onsubmit="postData('goods-form', 'action/goods-action.php');">
                                <input type="hidden" name="csrf"  value="<?=$db->csrfToken()?>">
                                <input type="hidden" name="formName" value="add-goods">
                                <div class="cell-sm-7 p-4 bg-white ">
                                    <div class="row">
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Goods Name<span class="fg-red">*</span></label>
                                                <input type="text" data-role="input" class="input-small required-field" name="VNAME" placeholder="Enter Goods Name" value="">
                                                <span class="invalid_feedback">Goods name is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Select Sub-group<span class="fg-red">*</span></label>
                                                <select data-role="select" name="NSUBGROUPID" class="input-small required-field-select" data-filter-placeholder="Search Sub-group...">
                                                    <option value="0">Select One</option>
                                                    <?= $appsDependent->dropdownCommon('ACCESSORIES_SUBGROUP', 'NID', 'VNAME') ?>
                                                </select>
                                                <span class="invalid_feedback">Sub-group is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Select MOU<span class="fg-red">*</span></label>
                                                <select data-role="select" name="NQTYUNITID" class="input-small required-field-select" data-filter-placeholder="Search MOU...">
                                                    <option value="0">Select One</option>
                                                    <?= $appsDependent->dropdownCommon('ACCESSORIES_QUANTITYUNIT', 'NID', 'VNAMESHORT') ?>
                                                </select>
                                                <span class="invalid_feedback">MOU is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Select Working Unit<span class="fg-red">*</span></label>
                                                <select data-role="select" name="VUSEUNIT" class="input-small required-field-select" data-filter-placeholder="Search Working Unit...">
                                                    <option value="0">Select One</option>
                                                    <option value="Sewing">Sewing</option>
                                                    <option value="Finishing">Finishing</option>
                                                    <option value="Cutting">Cutting</option>
                                                    <option value="Printing">Printing</option>
                                                    <option value="Knitting">Knitting</option>
                                                    <option value="Dyeing">Dyeing</option>                                    
                                                </select>
                                                <span class="invalid_feedback">Sub-group is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-12">
                                            <div class="form-group">
                                                <label>Goods Options<span class="fg-red">*</span></label>
                                                <div class="row no-gap">
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Color Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Color Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Size Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Size Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Color & Size Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Color & Size Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Kimball & Color Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Kimball & Color Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Kimball/Color/Size Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Kimball/Color/Size Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Country' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Country' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Grmnts Color' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Grmnts Color' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Size Name' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Size Name' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Grmnts Color/Kimball/Lot' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Grmnts Color/Kimball/Lot' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <?php $options = $accessoriesModel->getData("SELECT vname FROM ACCESSORIES_GOODSOPTIONS ORDER BY NID ASC");
                                                    if(is_array($options)):
                                                        foreach ($options as $key => $value): ?>
                                                        <div class="cell-3">
                                                            <input type='checkbox' data-role='checkbox' value='<?=$value['VNAME']?>' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='<?=$value['VNAME']?>' data-cls-check='bd-dark myCheck'>
                                                        </div>
                                                    <?php
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </div>                                                   
                                        
                                            </div>
                                        </div>                                       
                                        <div class="cell-12 d-flex flex-justify-center mt-2">
                                            <button type="submit" name="goods-submit" data-type="publish" class="image-button border bd-dark-hover success mr-2">
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
                    if($auth->verifyUserPermission('goods', 2)):
                    $id = isset($_GET['id']) ? $_GET['id'] : 0;
                    if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_GOODS WHERE nid = $id") == 'exist'):
                    $goodsData = $accessoriesModel->getData("SELECT nid, vname, nsubgroupid, vuseunit, nqtyunitid, vparameters FROM ACCESSORIES_GOODS WHERE nid = $id");
                    $subGroupId = $goodsData[0]['NSUBGROUPID'];
                    $mouId =  $goodsData[0]['NQTYUNITID'];
                    $parameters = explode(", ",$goodsData[0]['VPARAMETERS']);
                ?>
                <style type="text/css">
                .input{
                height: 28px !important;
                line-height: 28px !important;
                }
                .input input {
                height: 26px !important;
                }
                </style>
                <div class="d-flex flex-justify-center">
                    <div class="cell-lg-12">
                        <div data-role="panel" data-title-caption="Edit Goods" data-title-icon="<span class='mif-pencil'></span>" class="goods-form-panel" data-collapsible="false">
                        <div class="p-1">
                            <form method="POST" class="d-flex flex-justify-center" action="" onsubmit="updateData($(this), 'goods', 'goods.php?page=edit&id=<?=$id?>');">
                                <input type="hidden" name="csrf"  value="<?=$db->csrfToken()?>">
                                <input type="hidden" name="formName" value="edit-goods">
                                <input type="hidden" name="id" value="<?=$id?>">
                                <div class="cell-sm-7 p-4 bg-white ">
                                    <div class="row">
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Goods Name<span class="fg-red">*</span></label>
                                                <input type="text" data-role="input" class="input-small required-field" name="VNAME" placeholder="Enter Goods Name" value="<?=$goodsData[0]['VNAME']?>">
                                                <span class="invalid_feedback">Goods name is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Select Sub-group<span class="fg-red">*</span></label>
                                                <select data-role="select" name="NSUBGROUPID" class="input-small required-field-select" data-filter-placeholder="Search Sub-group...">
                                                    <option value="0">Select One</option>
                                                    <?= $appsDependent->dropdownCommon('ACCESSORIES_SUBGROUP', 'NID', 'VNAME', "$subGroupId") ?>
                                                </select>
                                                <span class="invalid_feedback">Sub-group is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Select MOU<span class="fg-red">*</span></label>
                                                <select data-role="select" name="NQTYUNITID" class="input-small required-field-select" data-filter-placeholder="Search MOU...">
                                                    <option value="0">Select One</option>
                                                    <?= $appsDependent->dropdownCommon('ACCESSORIES_QUANTITYUNIT', 'NID', 'VNAMESHORT', "$mouId") ?>
                                                </select>
                                                <span class="invalid_feedback">MOU is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6">
                                            <div class="form-group">
                                                <label>Select Working Unit<span class="fg-red">*</span></label>
                                                <select data-role="select" name="VUSEUNIT" class="input-small required-field-select" data-filter-placeholder="Search Working Unit...">
                                                    <option value="0">Select One</option>
                                                    <option value="Sewing" <?=($goodsData[0]['VUSEUNIT'] == 'Sewing' ? 'selected' : '')?>>Sewing</option>
                                                    <option value="Finishing" <?=($goodsData[0]['VUSEUNIT'] == 'Finishing' ? 'selected' : '')?>>Finishing</option>
                                                    <option value="Cutting" <?=($goodsData[0]['VUSEUNIT'] == 'Cutting' ? 'selected' : '')?>>Cutting</option>
                                                    <option value="Printing" <?=($goodsData[0]['VUSEUNIT'] == 'Printing' ? 'selected' : '')?>>Printing</option>
                                                    <option value="Knitting" <?=($goodsData[0]['VUSEUNIT'] == 'Knitting' ? 'selected' : '')?>>Knitting</option>
                                                    <option value="Dyeing" <?=($goodsData[0]['VUSEUNIT'] == 'Dyeing' ? 'selected' : '')?>>Dyeing</option>                                    
                                                </select>
                                                <span class="invalid_feedback">Sub-group is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-12">
                                            <div class="form-group">
                                                <label>Goods Options<span class="fg-red">*</span></label>
                                                <div class="row no-gap">
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Color Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Color Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Size Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Size Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Color & Size Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Color & Size Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Kimball & Color Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Kimball & Color Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Kimball/Color/Size Wise Qty' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Kimball/Color/Size Wise Qty' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Country' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Country' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Grmnts Color' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Grmnts Color' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Size Name' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Size Name' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    
                                                    <div class="cell-3">
                                                        <input type='checkbox' checked readonly data-role='checkbox' value='Grmnts Color/Kimball/Lot' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='Grmnts Color/Kimball/Lot' data-cls-check='bd-dark myCheck'>
                                                    </div>
                                                    <?php $options = $accessoriesModel->getData("SELECT vname FROM ACCESSORIES_GOODSOPTIONS ORDER BY NID ASC");
                                                    if(is_array($options)):
                                                        foreach ($options as $key => $value):
                                                            if(in_array($value['VNAME'], $parameters)):
                                                        ?>
                                                                <div class="cell-3">
                                                                    <input type='checkbox' data-role='checkbox' checked value='<?=$value['VNAME']?>' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='<?=$value['VNAME']?>' data-cls-check='bd-dark myCheck'>
                                                                </div>
                                                        <?php
                                                            else:
                                                        ?>
                                                                <div class="cell-3">
                                                                    <input type='checkbox' data-role='checkbox' value='<?=$value['VNAME']?>' name='VPARAMETERS[]' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-light rounded ' data-caption='<?=$value['VNAME']?>' data-cls-check='bd-dark myCheck'>
                                                                </div>
                                                        <?php
                                                           endif;
                                                        endforeach;
                                                    endif;
                                                    ?>
                                                </div>                                                   
                                        
                                            </div>
                                        </div>                                       
                                        <div class="cell-12 d-flex flex-justify-center mt-2">
                                            <button type="submit"  class="image-button border bd-dark-hover secondary mr-2">
                                                <span class='mif-done icon'></span>
                                                <span class="caption text-bold">Update</span>
                                            </button>
                                            <a href="goods.php?page=edit&id=<?=$id?>" class="image-button border bd-dark-hover warning mr-2">
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
                    $pageOpt->redirectWithscript($pageOpt->previousPageUrl(), 'Invalid goods id!');
                endif;
            else:
                $auth->redirect403();
            endif;
            elseif($_GET['page'] == 'all-goods'):
                if($auth->verifyUserPermission('goods', 4)):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="All Goods" data-collapsible="false" data-title-icon="<span class='mif-stack'></span>">
                        <div class="ml-1 mr-1">
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
                                data-cls-table-container="goods-table"
                                data-source="data/goods.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from $1 to $2 of $3 goods"
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