<!DOCTYPE html>
<?php
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;

if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyNavigationPermission('draft workorder') || $auth->verifyNavigationPermission('publish workorder') || $auth->verifyNavigationPermission('approved workorder') || $auth->verifyNavigationPermission('accepted workorder') || $auth->verifyNavigationPermission('all workorder')):
        $workorderOpt = new workorderoperation($db->con);
        $appsDependent = new dependentdata($db->con);
        $accessoriesModel = new accessoriescrud($db->con);
        $userid = $auth->loggedUserId();
        $managerFeature = $auth->getManagerFeature();
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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="work-order" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Work Order</h4>
                    </div>
                    <div class="cell-md-8">
                        <?php
                        if(!empty($pageOpt->previousPageUrl())): ?>
                            <a href="<?=$pageOpt->previousPageUrl()?>" class="image-button success place-right-md place-right bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                <span class='mif-arrow-left icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                <span class="caption text-bold">Back</span>
                            </a>
                        <?php
                            if($_GET['page'] == 'details'):
                            $id = $_GET['id'];
                            $idData = $accessoriesModel->getData("SELECT next_id, vponumbernext, prev_id, vponumberprev, vcreatedusernext, vcreateduserprev FROM (SELECT ndeletedstatus, nissuestatus, nid, vcreateduser, lag(nid) OVER (ORDER BY nid DESC) as prev_id, lag(vponumber) OVER (ORDER BY nid DESC) as vponumberprev, lead(nid) OVER (ORDER BY nid DESC) as next_id, lead(vponumber) OVER (ORDER BY nid DESC) as vponumbernext,  lag(vcreateduser) OVER (ORDER BY nid DESC) as vcreateduserprev, lead(vcreateduser) OVER (ORDER BY nid DESC) as vcreatedusernext FROM ACCESSORIES_WORKORDERMASTER WHERE nissuestatus = 1 AND ndeletedstatus = 0 AND (vcreateduser = '$userid' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin')) master WHERE nid = $id AND nissuestatus = 1 AND ndeletedstatus = 0 AND (vcreateduser = '$userid' OR vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin')");
                            
                            if($auth->verifyUserPermission('draft workorder', 1) && $pageOpt->currentPageClass() == 'drafts'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('publish workorder', 1) && $pageOpt->currentPageClass() == 'published'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('approved workorder', 1) && $pageOpt->currentPageClass() == 'approved'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('accepted workorder', 1) && $pageOpt->currentPageClass() == 'accepted'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('all workorder', 1) && $pageOpt->currentPageClass() == 'all-work-order'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php    
                            endif;
                                if(isset($idData[0]['NEXT_ID'])):?>
                                    <a href="workorder.php?page=details&id=<?=$idData[0]['NEXT_ID'];?>" data-role="hint" data-hint-position="top" data-hint-text="Next Work Order: <strong><?=empty($idData[0]['VPONUMBERNEXT']) ? 'W.O-'.$idData[0]['NEXT_ID'] : $idData[0]['VPONUMBERNEXT'] ?></strong>" class="image-button mr-2 place-right dark outline icon-right" style="height: 22px;">
                                        <span class='mif-chevron-right mif-3x icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;">
                                        </span>
                                        <span class="caption text-bold">Next</span>
                                    </a>
                                <?php
                                endif;
                                if(isset($idData[0]['PREV_ID'])):?>
                                    <a href="workorder.php?page=details&id=<?=$idData[0]['PREV_ID'];?>" data-role="hint" data-hint-position="top" data-hint-text="Previous Work Order: <strong><?=empty($idData[0]['VPONUMBERPREV']) ? 'W.0-'.$idData[0]['PREV_ID'] : $idData[0]['VPONUMBERPREV'] ?></strong>" class="image-button mr-2 place-right dark outline" style="height: 22px;">
                                        <span class='mif-chevron-left icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                        <span class="caption text-bold">Prev</span>
                                    </a>
                                <?php 
                                endif;
                            elseif($_GET['page'] == 'create-new'): ?>
                                <a href="javscript:void(0)" class="image-button workorder-submit success bg-orange bg-darkOrange-hover place-right-md mr-2-md border bd-dark-hover" data-type="draft" style="height: 22px; display: none;">
                                    <span class='mif-spinner icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Publish Later (Draft)</span>
                                </a>
                                <a href="javscript:void(0)" data-type="publish" class="image-button workorder-submit success place-right-md mr-2-md border bd-dark-hover" style="height: 22px; display: none;">
                                    <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Publish Work Order</span>
                                </a>
                            <?php
                            elseif($_GET['page'] == 'edit'):
                                $id = $_GET['id'];
                                $idData = $accessoriesModel->getData("SELECT vstatus FROM accessories_workordermaster WHERE nid = $id");
                                $statusheader = is_array($idData) ? $idData[0]['VSTATUS'] : '';
                               
                                if($auth->verifyUserPermission('draft workorder', 1) && $pageOpt->currentPageClass() == 'drafts'):
                                ?>
                                    <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                        <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                        <span class="caption text-bold">Create</span>
                                    </a>
                                <?php
                                elseif ($auth->verifyUserPermission('publish workorder', 1) && $pageOpt->currentPageClass() == 'published'):
                                ?>
                                    <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                        <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                        <span class="caption text-bold">Create</span>
                                    </a>
                                <?php
                                elseif ($auth->verifyUserPermission('approved workorder', 1) && $pageOpt->currentPageClass() == 'approved'):
                                ?>
                                    <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                        <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                        <span class="caption text-bold">Create</span>
                                    </a>
                                <?php
                                elseif ($auth->verifyUserPermission('accepted workorder', 1) && $pageOpt->currentPageClass() == 'accepted'):
                                ?>
                                    <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                        <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                        <span class="caption text-bold">Create</span>
                                    </a>
                                <?php
                                elseif ($auth->verifyUserPermission('all workorder', 1) && $pageOpt->currentPageClass() == 'all-work-order'):
                                ?>
                                    <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                        <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                        <span class="caption text-bold">Create</span>
                                    </a>
                                <?php    
                                endif;
                                ?>
                                <a href="workorder.php?page=edit&id=<?=isset($_GET['id']) ? $_GET['id'] : 0; ?>" class="image-button place-right warning mr-2 border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-refresh icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Refresh</span>
                                </a>
                                <button type="button" class="image-button workorder-update place-right secondary mr-2 border bd-dark-hover" style="height: 22px;" name="workorder-update" data-type="<?=$statusheader?>">
                                    <span class='mif-checkmark icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Update Work Order</span>
                                </button>
                            <?php
                            elseif($_GET['page'] == 'newissue'): ?>
                                <a href="javscript:void(0)" data-type="publish" class="image-button workorder-newissue success place-right-md mr-2-md border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Publish Work Order</span>
                                </a>
                            <?php 
                            endif; ?>
                        <?php
                        else:
                            if($auth->verifyUserPermission('draft workorder', 1) && $pageOpt->currentPageClass() == 'drafts'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('publish workorder', 1) && $pageOpt->currentPageClass() == 'published'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('approved workorder', 1) && $pageOpt->currentPageClass() == 'approved'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('accepted workorder', 1) && $pageOpt->currentPageClass() == 'accepted'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php
                            elseif ($auth->verifyUserPermission('all workorder', 1) && $pageOpt->currentPageClass() == 'all-work-order'):
                            ?>
                                <a href="workorder.php?page=create-new" class="image-button success mr-2 place-right border bd-dark-hover" style="height: 22px;">
                                    <span class='mif-plus icon mif-3x' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                    <span class="caption text-bold">Create</span>
                                </a>
                            <?php    
                            endif;
                   
                        endif; ?>
                    </div>
                </div>
                <?php
                /*================================================
                Work order create new
                ================================================*/
                if($_GET['page'] == 'create-new'):
                    if($auth->verifyUserPermission('draft workorder', 1) || $auth->verifyUserPermission('publish workorder', 1) || $auth->verifyUserPermission('approved workorder', 1) || $auth->verifyUserPermission('accepted workorder', 1) || $auth->verifyUserPermission('all workorder', 1)):
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
                        <div data-role="panel" data-title-caption="Create Work Order" data-title-icon="<span class='mif-plus'></span>" class="workorder-form-panel" data-collapsible="false">
                        <div class="errors custom-alert">
                        </div>
                        <div class="success custom-alert">
                        </div>
                        <div class="p-1">
                            <form method="POST" action="" class="order-search">
                                <input type="hidden" name="csrf" class="csrf" value="<?=$db->csrfToken()?>">
                                <div class="row">
                                    <div class="cell-xl-6 cell-lg-12">
                                        <div class="row">
                                            <div class="cell-sm-6">
                                                <div class="form-group">
                                                    <label>Enter Order / FKL No.<span class="fg-red">*</span></label>
                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                        <div class="cell-5" style="padding: 0px;">
                                                            <select data-role="select" name="input-ordernumber-type" class="input-small input-ordernumber-type" data-filter="false">
                                                                <option value="Order number">Order Number</option>
                                                                <option value="FKL number">FKL Number</option>
                                                            </select>
                                                        </div>
                                                        <div class="cell-7" style="padding: 0px;">
                                                            <input type="text" data-role="input" class="input-small search-ordernumber" data-cls-input="place-right">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="cell-sm-6 cell-md-4 accessories-content-hidden content-visible lotappend">
                                                
                                            </div> -->
                                            <div class="cell-sm-6 accessories-content-hidden content-visible">
                                                <div class="form-group">
                                                    <label>Select Supplier<span class="fg-red">*</span></label>
                                                    <!-- <label style="position: absolute; right: 0; top: -2px;">
                                                        <a href="javscript:void(0)" class="image-button secondary secondary ribbed-teal bg-darkTeal-hover" style="height: 20px;">
                                                            <span class='mif-plus icon' style="height: 20px; line-height: 20px; font-size: .7rem; width: 20px;"></span>
                                                            <span class="caption">New</span>
                                                        </a>
                                                    </label> -->
                                                    <select data-role="select" name="suppliers" class="input-small accessories-disable suppliername" disabled data-filter-placeholder="Search Suplliers...">
                                                        <?= $appsDependent->dropdownCommon('ACCESSORIES_SUPPLIERS', 'NID', 'VNAME') ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-4 accessories-content-hidden content-visible">
                                                <div class="form-group">
                                                    <label>Attn.<span class="fg-red">*</span></label>
                                                    <input type="text" data-role="input" disabled class="input-small accessories-disable required-field attnetion-name" name="attn" placeholder="Enter Name">
                                                    <span class="invalid_feedback">Attn. name is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-4 accessories-content-hidden content-visible">
                                                <div class="form-group">
                                                    <label>From<span class="fg-red">*</span></label>
                                                    <input type="text" data-role="input" disabled class="input-small accessories-disable required-field form-name" name="form" placeholder="Enter Name">
                                                    <span class="invalid_feedback">Form name is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-4 accessories-content-hidden content-visible">
                                                <div class="form-group">
                                                    <label>Select Delivery Date<span class="fg-red">*</span></label>
                                                    <input type="text" name="deliverydate" disabled class="deliverydate accessories-disable input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" data-min-date="<?=date('d-m-Y')?>" data-input-format="%d-%m-%Y" data-clear-button="true">
                                                    <span class="invalid_feedback">Delivery date is required.</span>
                                                </div>
                                            </div>
                                            <?php
                                            $groupData = $accessoriesModel->getData("SELECT nid, vname FROM ACCESSORIES_GROUP ORDER BY nid ASC");
                                            if(is_array($groupData)):
                                            foreach ($groupData as $groupName):
                                            ?>
                                            <div class="cell-sm-6 cell-md-4 accessories-content-hidden content-visible">
                                                <div class="form-group">
                                                    <label>Select <?=$groupName['VNAME']?></label>
                                                    <select data-role="select" name="selecteditem" multiple class="input-small accessories-disable itemsevent" disabled data-filter-placeholder="Search <?=$groupName['VNAME']?>">
                                                        <?php
                                                        $groupId = $groupName['NID'];
                                                        $subGroupData = $accessoriesModel->getData("SELECT nid, vname FROM ACCESSORIES_SUBGROUP WHERE ngroupid = $groupId ORDER BY nid ASC");
                                                        if(is_array($subGroupData)):
                                                        foreach ($subGroupData as $subGroupName):
                                                        ?>
                                                        <optgroup label="<?=$subGroupName['VNAME']?>" style="background-color: #e0f0f1;">
                                                            <?php
                                                            $subGroupData = $subGroupName['NID'];
                                                            $goods = $accessoriesModel->getData("SELECT goods.nid, goods.vname, goods.vparameters, unit.vnameshort FROM ACCESSORIES_GOODS goods LEFT JOIN ACCESSORIES_QUANTITYUNIT unit ON unit.nid = goods.nqtyunitid WHERE nsubgroupid = $subGroupData ORDER BY nsubgroupid ASC");
                                                            if(is_array($goods)):
                                                            foreach ($goods as $good):
                                                            ?>
                                                            <option value="<?=$good['NID']?>" data-qtyunit="<?=$good['VNAMESHORT']?>" data-perameters="<?=$good['VPARAMETERS']?>"><?=$good['VNAME']?></option>
                                                            <?php
                                                            endforeach;
                                                            endif;
                                                            ?>
                                                        </optgroup>
                                                        <?php
                                                        endforeach;
                                                        endif;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            endforeach;
                                            else:
                                            echo $groupData;
                                            endif;
                                            ?>
                                            
                                        </div>
                                    </div>
                                    <div class="cell-xl-6 cell-lg-12">
                                        <input type="hidden" class="allcountry" value="">
                                        <input type="hidden" class="allsize" value="">
                                        <input type="hidden" class="addrowdisabler" value="0">
                                        <div class="cell-12" style="margin-top: 15px;">
                                            <table class="subcompact cell-border bg-white table searchordertable accessories-content-hidden content-visible" style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                                                <tr>
                                                    <td style="padding: 0px;"><span class="fklnumbersection  orderfklcommon" style="display: none;"><span class="text-bold searchLabel">FKL NO.</span> <span id="fklno"></span></span><span class="ordernumbersection  orderfklcommon" style="display: none;"><span class="text-bold searchLabel">ORDER NO.</span> <span id="ordernumber"></span></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Style Name</span> <span id="stylename"></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Buyer Name</span> <span id="buyername"></span></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Season</span> <span id="season"></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Dept.</span> <span id="dept"></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Kimball</span> <span id="kimble"></span></td>
                                                    <!-- <td style="padding-left: 0px;"><span class="text-bold searchLabel">Gmts Qty</span> <span id="qty"></span><input type="hidden" name="gmtqty" class="gmtqty required-field" value=''><span class="invalid_feedback">Garments qty is required.</span></td> -->
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="cell-12 accessories-content-hidden content-visible" style="margin-top: -10px;">
                                            <div class="form-group">
                                                <label>Order Details</label>
                                                <textarea data-role="textarea" name="order-details" class="disabled accessories-disable order-detils"></textarea>
                                                <!-- <span class="invalid_feedback">Order details is required.</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell-sm-12">
                                        <div class="ribbed-lightGray maingridheader" style="box-shadow: 0px 0px 2px #fff; margin-top: -10px; position: relative; display: none;">
                                            <h5 class="p-1" style="font-size: 1.1rem;">Details Entry Section</h5>
                                        </div>
                                    </div>
                                    <div class="gridappender cell-sm-12" style="margin-top: -15px;">
                                    </div>
                                </div>
                                <div class='addition-popup' style="display: none;">
                                    <div class='addittion-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="tableclass">
                                        <input type="hidden" name="columnClass" value="" class="columnclass">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Add Extra Garments Quantity</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <input type='text' class='input-small text-center addition-qty' name='addition' oninput="numberValidate($(this), $(this).val());" style='margin: 0 auto;' value='0'>
                                            </div>
                                            <div class='cell'>
                                                <select name='additiontype' class='addition-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='parcent'>(%) of total garments</option>
                                                    <option value='qty'>Pcs. Added</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success addition-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success addition-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='converter-popup' style="display: none;">
                                    <div class='converter-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="contableclass">
                                        <input type="hidden" name="columnClass" value="" class="concolumnclass">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Apply Quantity Conversion Rules</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 0px;padding: 6px;">
                                            <div class='cell-12'>
                                                <select name='convertiontype' class='convertion-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='' id='convertiontype1' data-caltype='multiply'></option>
                                                    <option value='' id='convertiontype2' data-caltype='divided'></option>
                                                </select>
                                            </div>
                                            <div class="cell-12 extraCalAdded mt-1">

                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center; margin-top: -4px;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success convertion-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success converter-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='datafill-popup' style="display: none;">
                                    <div class='datafill-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="datafilltableclass">
                                        <input type="hidden" name="columnClass" value="" class="datafillcolumnclass">
                                        <input type="hidden" name="dataname" value="" class="datafilldataname">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Data Fill Type</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <select name='datafilltype' class='datafill-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='Fixed'>Fixed</option>
                                                    <option value='Manuall'>Manually Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='datafill-popup1' style="display: none;">
                                    <div class='datafill-popup1-container'>
                                        <input type="hidden" name="tableClass" value="" class="datafilltableclass1">
                                        <input type="hidden" name="columnClass" value="" class="datafillcolumnclass1">
                                        <input type="hidden" name="dataname" value="" class="datafilldataname1">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Data Fill Type</p>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px;">
                                            <div class='cell-12'>
                                                <select name='datafilltype' class='datafill1-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='Fixed'>Fixed</option>
                                                    <option value='Manuall'>Manually Select</option>
                                                </select>
                                            </div>
                                            <div class='cell-12 repeater-content'>
                                                <label>Data Repeat</label>
                                                 <select name='datarepeat' class='datarepeat-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='1'>1 time</option>
                                                    <option value='2'>2 times</option>
                                                    <option value='3'>3 times</option>
                                                    <option value='4'>4 times</option>
                                                    <option value='5'>5 times</option>
                                                    <option value='6'>6 times</option>
                                                    <option value='7'>7 times</option>
                                                    <option value='8'>8 times</option>
                                                    <option value='9'>9 times</option>
                                                    <option value='10'>10 times</option>
                                                    <option value='11'>11 times</option>
                                                    <option value='12'>12 times</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-customize-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-customize-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='excel-popup' style="display: none;">
                                    <div class='excel-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="exceltableclass">
                                        <input type="hidden" name="dataid" value="" class="exceldataid">
                                        <input type="hidden" name="dataname" value="" class="excelitemname">
                                         <input type="hidden" name="dataunit" value="" class="excelitemunit">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Import File (.xlsx or .csv)</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                               <input type="file" data-role="file" data-mode="drop" class="excel-file" onchange="checkValidFile($(this));">
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success excel-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success excel-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell-sm-12 extra-notes" style="margin-top: -10px;">
                                    </div>
                                </div>
                                <div class="row submit-section text-center d-flex flex-justify-center">
                                    <button type="button" name="workorder-submit" data-type="publish" class="workorder-submit image-button border bd-dark-hover success mr-2" style="display: none;">
                                    <span class='mif-plus icon'></span>
                                    <span class="caption text-bold">Publish Work Order</span>
                                    </button>
                                    <button type="button" data-type="draft" class="image-button workorder-submit success bg-orange bg-darkOrange-hover ml-2-md border bd-dark-hover" style="display: none;">
                                    <span class='mif-spinner icon'></span>
                                    <span class="caption text-bold" >Publish Later (Draft)</span>
                                    </button>
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
            elseif($_GET['page'] == 'published'):
                if($auth->verifyNavigationPermission('publish workorder')):
                    if($auth->verifyUserPermission('publish workorder', 2)):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="Published Work Order" data-collapsible="false" data-title-icon="<span class='mif-spinner'></span>">
                        
                        <div class="ml-1 mr-1">
                            <table 
                                class="table striped table-border cell-border subcompact mt-1 accessories-table-common published-workorder-table"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7 cell-sm-6"
                                data-cls-rows-count="cell-md-5 cell-sm-6"
                                data-rows="20"
                                data-rows-steps="-1, 18, 30, 50, 100, 150"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="published-workorder"
                                data-source="data/published-workorder.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from $1 to $2 of $3 Published Work Order(s)"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                            >
                            <?php
                            if($auth->verifyUserPermission('publish workorder', 7)):
                            ?>
                                <tfoot>
                                <tr>
                                    <td colspan="10" style="padding: 1px;"></td>
                                    <td style="padding: 1px;"><button class="image-button success" type="button" onclick="workorderViewOperation('published-workorder-table', 'approved', 'workorder.php?page=approved');" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Approve</span></button></td>
                                    <td style="padding: 1px;"></td>
                                </tr>
                                </tfoot>
                            <?php
                            endif;
                            ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    else:
                    ?>
                    <div class="row mt-3">
                        <div class="cell-md-12 d-flex flex-justify-center flex-align-center">
                            <div class="display1 m-2 text-center text-bold" style="color: #d4d4d4;">Accessories Store Automation System</div>
                        </div>
                    </div>
                    <?php
                    endif;
                else:
                    $auth->redirect403();
                endif;
            elseif($_GET['page'] == 'drafts'):
                if($auth->verifyNavigationPermission('draft workorder')):
                    if($auth->verifyUserPermission('draft workorder', 2)):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="Drafts Work Order" data-collapsible="false" data-title-icon="<span class='mif-drafts'></span>">

                        <div class="ml-1 mr-1">
                            <div class="row">
                                 <div class="cell-sm-12 cell-md-7">
                                    <form action="" method="GET">
                                        <div class="row no-gap">
                                            <div style="width: 100px;background: #1a404d;color: #fff;padding-left: 5px;font-size: 14px;line-height: 26px;height: 27px;font-weight: bold;">
                                                <span class="mif-filter icon"></span>  Date Filter</span>
                                            </div>
                                            <input type="hidden" name="page" value="drafts">
                                            <div style="width: 30%; margin-left: 5px">
                                                <span style="width: 100%;display: block;float: right;"><input  class="input-small" type="text" required data-role="calendarpicker"  data-cls-calendar="compact" placeholder="Select From Date" data-format="%d-%m-%Y" name="formdate" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?=date('d-m-Y')?>" value="<?=$pageOpt->filterFormDate;?>"></span>
                                            </div>
                                            <span style="margin-left: 5px; font-weight: bold;background: #d7d7d7;padding: 1px 5px;color: #1d1d1d;">To</span>
                                            <div style="width: 30%; margin-left: 5px;">
                                                <span style="width: 100%;display: block;float: right;"><input  class="input-small" type="text" required data-role="calendarpicker" name="todate" data-cls-calendar="compact" placeholder="Select To Date" data-format="%d-%m-%Y" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?=date('d-m-Y')?>" value="<?=$pageOpt->filterToDate;?>"></span>
                                            </div>
                                            
                                            <button type="submit" class="image-button warning ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                <span class='mif-spinner2 icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                <span class="caption">Load Data</span>
                                            </button>
                                            <?php if(isset($_SESSION['filterWhere'])): ?>
                                            <a href="workorder.php?page=drafts" class="image-button alert ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                <span class='mif-cross icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                <span class="caption">Clear Filter</span>
                                            </a>
                                        <?php endif; ?>
                                        </div>
                                    </form>
                                </div>                             
                            </div>
                            <table 
                                class="table striped table-border cell-border subcompact mt-1 accessories-table-common drafts-workorder-table"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7 cell-sm-8"
                                data-cls-rows-count="cell-md-5 cell-sm-4"
                                data-rows="20"
                                data-rows-steps="-1, 20, 30, 50, 100, 150"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="drafts-workorder"
                                data-source="data/draft-workorder.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from <strong>$1</strong> to <strong>$2</strong> of <strong>$3</strong> Draft Work Order(s) <small style='color: #ff9447;'><em>[N:B: By default, only showing last 60 days data. For more than 60 days data you can use the date filter option.]</em></small>"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                            >
                                <tfoot>
                                <tr>
                                    <td colspan="9" style="padding: 1px;"></td>
                                    <td style="padding: 1px;"><button class="image-button success" type="button" onclick="workorderViewOperation('drafts-workorder-table', 'published', 'workorder.php?page=published');" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-spinner icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Publish</span></button></td>
                                    <td style="padding: 1px;"></td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    else:
                    ?>
                    <div class="row mt-3">
                        <div class="cell-md-12 d-flex flex-justify-center flex-align-center">
                            <div class="display1 m-2 text-center text-bold" style="color: #d4d4d4;">Accessories Store Automation System</div>
                        </div>
                    </div>
                    <?php
                    endif;
                else:
                    $auth->redirect403();
                endif;
            elseif($_GET['page'] == 'approved'):
                if($auth->verifyNavigationPermission('approved workorder')):
                    if($auth->verifyUserPermission('approved workorder', 2)):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="Approved Work Order" data-collapsible="false" data-title-icon="<span class='mif-done_all'></span>">
                        <div class="ml-1 mr-1">
                            <table 
                                class="table striped table-border cell-border subcompact mt-1 accessories-table-common approved-workorder-table"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7 cell-sm-6"
                                data-cls-rows-count="cell-md-5 cell-sm-6"
                                data-rows="20"
                                data-rows-steps="-1, 20, 30, 50, 100, 150"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="approved-workorder"
                                data-source="data/approved-workorder.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from $1 to $2 of $3 Approved Work Order(s)"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                                >
                                <?php
                                if($auth->verifyUserPermission('approved workorder', 8)):
                                ?>
                                <tfoot>
                                <tr>
                                    <td colspan="10" style="padding: 1px;"></td>
                                    <td style="padding: 1px;"><button class="image-button success" type="button" onclick="workorderViewOperation('approved-workorder-table', 'accepted', 'workorder.php?page=accepted');" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-done_all icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Accept</span></button></td>
                                    <td style="padding: 1px;"></td>
                                </tr>
                                </tfoot>
                                <?php
                                endif;
                                ?>
                            </table>
                        
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    else:
                    ?>
                    <div class="row mt-3">
                        <div class="cell-md-12 d-flex flex-justify-center flex-align-center">
                            <div class="display1 m-2 text-center text-bold" style="color: #d4d4d4;">Accessories Store Automation System</div>
                        </div>
                    </div>
                    <?php
                    endif;
                else:
                    $auth->redirect403();
                endif;
            elseif($_GET['page'] == 'accepted'):
                if($auth->verifyNavigationPermission('accepted workorder')):
                    if($auth->verifyUserPermission('accepted workorder', 2)):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="Pending Work Order" data-collapsible="false" data-title-icon="<span class='mif-spinner'></span>">
                        <div class="ml-1 mr-1">
                             <div class="row">
                                 <div class="cell-sm-12 cell-md-7">
                                    <form action="" method="GET">
                                        <div class="row no-gap">
                                            <div style="width: 100px;background: #1a404d;color: #fff;padding-left: 5px;font-size: 14px;line-height: 26px;height: 27px;font-weight: bold;">
                                                <span class="mif-filter icon"></span>  Date Filter</span>
                                            </div>
                                            <input type="hidden" name="page" value="accepted">
                                            <div style="width: 30%; margin-left: 5px">
                                                <span style="width: 100%;display: block;float: right;"><input  class="input-small" type="text" required data-role="calendarpicker"  data-cls-calendar="compact" placeholder="Select From Date" data-format="%d-%m-%Y" name="formdate" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?=date('d-m-Y')?>" value="<?=$pageOpt->filterFormDate;?>"></span>
                                            </div>
                                            <span style="margin-left: 5px; font-weight: bold;background: #d7d7d7;padding: 1px 5px;color: #1d1d1d;">To</span>
                                            <div style="width: 30%; margin-left: 5px;">
                                                <span style="width: 100%;display: block;float: right;"><input  class="input-small" type="text" required data-role="calendarpicker" name="todate" data-cls-calendar="compact" placeholder="Select To Date" data-format="%d-%m-%Y" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?=date('d-m-Y')?>" value="<?=$pageOpt->filterToDate;?>"></span>
                                            </div>
                                            
                                            <button type="submit" class="image-button warning ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                <span class='mif-spinner2 icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                <span class="caption">Load Data</span>
                                            </button>
                                            <?php if(isset($_SESSION['filterWhere'])): ?>
                                            <a href="workorder.php?page=accepted" class="image-button alert ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                <span class='mif-cross icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                <span class="caption">Clear Filter</span>
                                            </a>
                                        <?php endif; ?>
                                        </div>
                                    </form>
                                </div>                             
                            </div>
                            <table 
                                class="table striped table-border cell-border subcompact mt-1 accessories-table-common accepted-workorder-table"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7 cell-sm-6"
                                data-cls-rows-count="cell-md-5 cell-sm-6"
                                data-rows="20"
                                data-rows-steps="-1, 20, 30, 50, 100, 150"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="accepted-workorder"
                                data-source="data/accepted-workorder.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from <strong>$1</strong> to <strong>$2</strong> of <strong>$3</strong> Accepted Work Order(s) <small style='color: #ff9447;'><em>[N:B: By default, only showing last 60 days data. For more than 60 days data you can use the date filter option.]</em></small>"
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
                    ?>
                    <div class="row mt-3">
                        <div class="cell-md-12 d-flex flex-justify-center flex-align-center">
                            <div class="display1 m-2 text-center text-bold" style="color: #d4d4d4;">Accessories Store Automation System</div>
                        </div>
                    </div>
                    <?php
                    endif;
                else:
                    $auth->redirect403();
                endif;
            elseif($_GET['page'] == 'all-work-order'):
                if($auth->verifyNavigationPermission('all workorder')):
                    if($auth->verifyUserPermission('all workorder', 2)):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="All Work Order" data-collapsible="false" data-title-icon="<span class='mif-list'></span>">
                        <div class="ml-1 mr-1">
                            <div class="row">
                                 <div class="cell-sm-12 cell-md-7">
                                    <form action="" method="GET">
                                        <div class="row no-gap">
                                            <div style="width: 100px;background: #1a404d;color: #fff;padding-left: 5px;font-size: 14px;line-height: 26px;height: 27px;font-weight: bold;">
                                                <span class="mif-filter icon"></span>  Date Filter</span>
                                            </div>
                                            <input type="hidden" name="page" value="all-work-order">
                                            <div style="width: 30%; margin-left: 5px">
                                                <span style="width: 100%;display: block;float: right;"><input  class="input-small" type="text" required data-role="calendarpicker"  data-cls-calendar="compact" placeholder="Select From Date" data-format="%d-%m-%Y" name="formdate" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?=date('d-m-Y')?>" value="<?=$pageOpt->filterFormDate;?>"></span>
                                            </div>
                                            <span style="margin-left: 5px; font-weight: bold;background: #d7d7d7;padding: 1px 5px;color: #1d1d1d;">To</span>
                                            <div style="width: 30%; margin-left: 5px;">
                                                <span style="width: 100%;display: block;float: right;"><input  class="input-small" type="text" required data-role="calendarpicker" name="todate" data-cls-calendar="compact" placeholder="Select To Date" data-format="%d-%m-%Y" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?=date('d-m-Y')?>" value="<?=$pageOpt->filterToDate;?>"></span>
                                            </div>
                                            
                                            <button type="submit" class="image-button warning ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                <span class='mif-spinner2 icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                <span class="caption">Load Data</span>
                                            </button>
                                            <?php if(isset($_SESSION['filterWhere'])): ?>
                                            <a href="workorder.php?page=all-work-order" class="image-button alert ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                <span class='mif-cross icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                <span class="caption">Clear Filter</span>
                                            </a>
                                        <?php endif; ?>
                                        </div>
                                    </form>
                                </div>                             
                            </div>
                            <table 
                                class="table striped table-border cell-border subcompact mt-1 accessories-table-common all-workorder-table"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7"
                                data-cls-rows-count="cell-md-5"
                                data-rows="20"
                                data-rows-steps="-1, 20, 50, 100, 150, 200, 300, 500, 800, 1200"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="all-workorder"
                                data-source="data/all-workorder.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from <strong>$1</strong> to <strong>$2</strong> of <strong>$3</strong> Work Order(s) <small style='color: #ff9447;'><em>[N:B: By default, only showing last 60 days data. For more than 60 days data you can use the date filter option.]</em></small>"
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
                    ?>
                    <div class="row mt-3">
                        <div class="cell-md-12 d-flex flex-justify-center flex-align-center">
                            <div class="display1 m-2 text-center text-bold" style="color: #d4d4d4;">Accessories Store Automation System</div>
                        </div>
                    </div>
                    <?php
                    endif;
                else:
                    $auth->redirect403();
                endif;
            elseif($_GET['page'] == 'details'):
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="Details of Work Order <strong>(W.O-<?=$id?>)</strong>" data-collapsible="false" data-title-icon="<span class='mif-description'></span>">
                        <div class="ml-1 mr-1">
                            <?php
                            if($accessoriesModel->checkDataExistence("SELECT nid FROM accessories_workordermaster WHERE nid = $id AND ndeletedstatus = 0 AND (vcreateduser = '$userid' OR vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin')") == 'exist'):
                                $masterData = $accessoriesModel->getData("SELECT master.nid, master.vtodate, master.vponumber, master.vissue, master.vstatus, master.vattn, master.vform, master.vordernumberorfklnumber, master.vtype, master.vorderdetails, master.vextranotes, master.vdeliverydate, master.vcreateduser, master.vcreatedat, master.vgarmentsqty, master.vissue, master.ncheckedstatus, master.vcheckeduser, master.vlastupdateduser, master.vlastupdatedat, master.vpublisheduser, master.vpublishedat, master.napprovedstatus, master.vapproveduser, master.nacceptencestatus, master.nissuestatus, master.vaccepteduser, master.nmrstatus, master.vmruser, supplier.nid AS supplierid, supplier.vname AS suppliername FROM accessories_workordermaster master LEFT JOIN accessories_suppliers supplier ON supplier.nid = master.nsupllierid WHERE master.nid = $id AND master.ndeletedstatus = 0 AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') ORDER BY master.nid ASC");
                                if(strtolower($masterData[0]['VTYPE']) == 'order number'):
                                $orderNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
                                $orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ksid, orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_vw_orderinfo orderinfo LEFT JOIN erp.mer_ks_master kmaster ON kmaster.nordercode = orderinfo.norderid WHERE orderinfo.vordernumber = '$orderNumber' GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname");
                                $fklNumber = $orderInfo[0]['KSID'];
                                elseif(strtolower($masterData[0]['VTYPE']) == 'fkl number'):
                                $fklNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
                                $orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(orderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ordernumber,  orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_ks_master kmaster LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.norderid = kmaster.nordercode WHERE kmaster.nks_id IN ($fklNumber) GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname");
                                $orderNumber = $orderInfo[0]['ORDERNUMBER'];
                                else:
                                $orderInfo =  array();
                                endif;
                            ?>
                            <div class="row">
                                <div class="cell-md-10">
                                    <div class="d-flex flex-justify-end" style="margin-top: -9px;">

                                        <?php if($masterData[0]['VSTATUS'] == 'publish' && $masterData[0]['NISSUESTATUS'] == 1): ?>
                                        <div class="text-left pl-1 pr-1">
                                            <a class="image-button info" href="reports/workorder/<?=$masterData[0]['NID']?>" target="_blank" style="height: 22px; padding: 1px 35px 1px 0px;"><span class="mif-printer icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Print</span></a>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($masterData[0]['NAPPROVEDSTATUS'] == 0 && $masterData[0]['NISSUESTATUS'] == 1): ?>
                                        <div class="text-center pl-1 pr-1">
                                            <a class="image-button secondary" href="workorder.php?page=edit&id=<?=$masterData[0]['NID']?>" style="height: 22px; padding: 1px 41px 1px 0px;"><span class="mif-pencil icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Edit</span></a>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($masterData[0]['NAPPROVEDSTATUS'] == 1 && $masterData[0]['NACCEPTENCESTATUS'] == 1 && $masterData[0]['NISSUESTATUS'] == 1): ?>
                                        <div class="text-center pl-1 pr-1">
                                            <a class="image-button warning" href="workorder.php?page=newissue&id=<?=$id?>" style="height: 22px; padding: 1px 6px 1px 0px;"><span class="mif-plus icon" style="height: 22px; line-height: 22px; font-size: 11px; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Issue - <?=($masterData[0]['VISSUE']+1)?></span></a>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($masterData[0]['VSTATUS'] == 'publish' && $masterData[0]['NACCEPTENCESTATUS'] == 0 && $masterData[0]['NAPPROVEDSTATUS'] == 1 && $masterData[0]['NISSUESTATUS'] == 1): ?>
                                        <div class="text-left pl-1 pr-1">
                                            <button type="button" class="image-button yellow" onclick="rowRollBack(<?=$masterData[0]['NID']?>, 'approved', 'workorder', $(this), 'workorder.php?page=details&id=<?=$masterData[0]['NID']?>')" style="height: 22px; padding: 1px 10px 1px 0px;"><span class="mif-refresh icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Roll Back</span></button>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($masterData[0]['VSTATUS'] == 'publish' && $masterData[0]['NACCEPTENCESTATUS'] == 0 && $masterData[0]['NAPPROVEDSTATUS'] == 0 && $masterData[0]['VISSUE'] == 1): ?>
                                        <div class="text-left pl-1 pr-1">
                                            <button type="button" class="image-button yellow" onclick="rowRollBack(<?=$masterData[0]['NID']?>, 'published', 'workorder', $(this), 'workorder.php?page=details&id=<?=$masterData[0]['NID']?>')" style="height: 22px; padding: 1px 10px 1px 0px;"><span class="mif-refresh icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Roll Back</span></button>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($masterData[0]['NAPPROVEDSTATUS'] == 0 && $masterData[0]['NISSUESTATUS'] == 1): ?>
                                        <div class="text-center pl-1 pr-1">
                                            <button type="button" class="image-button alert" onclick="deleteRow(<?=$masterData[0]['NID']?>, 'workorder', 'workorder', $(this), '<?=$pageOpt->previousPageUrl()?>')" style="height: 22px; padding: 1px 26px 1px 0px;"><span class="mif-bin icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Delete</span></button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="row" style="margin-top: -10px;" id="printJS-form" ondblclick="printJS({ printable: 'printJS-form', type: 'html', maxWidth : 1920, css: ['vendor/metro4/css/metro-all.min.css', 'css/index.css', 'css/custom-style.css'], targetStyles: ['*'], scanStyles : false,  documentTitle :'Work Order'})">
                                        <div class="cell-12">
                                            <table class='subcompact table cell-border workorder-detailstable bg-white' style="width:100%;">
                                                <tr>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Date</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$masterData[0]['VTODATE']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Style / Ref.</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$orderInfo[0]['STYLENAME']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">P.O. Number</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=empty($masterData[0]['VPONUMBER']) ? '-' : $masterData[0]['VPONUMBER'] ?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">To</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$masterData[0]['SUPPLIERNAME']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Season</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$orderInfo[0]['VSEASSONNAME']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Buyer Name</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$orderInfo[0]['BUYERNAME']?></span>
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Attention</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$masterData[0]['VATTN']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Order Number</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$orderNumber?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Department</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$orderInfo[0]['VDEPTNAME']?></span>
                                                    </td> 
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">From</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$masterData[0]['VFORM']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">FKL Number</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$fklNumber?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Kimball</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$orderInfo[0]['KIMBALLNO']?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Garments Qty</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$masterData[0]['VGARMENTSQTY']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;">
                                                        <?php $createdUser = $accessoriesModel->getUser($masterData[0]['VCREATEDUSER']); ?>
                                                        <span class="text-bold searchLabel" style="background: #dedede;">Created By</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><a href="javascript:void(0)" data-role="popover" data-popover-text="<div class='row no-gap' style='width:310px;'><div class='cell' style='max-width: 105px;'><div style='width: 100px;'><?=$createdUser['picture']?></div></div><div class='cell'><table class='subcompact table workorder-detailstable bg-white cell-border'><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$createdUser['name']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$createdUser['designation']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$createdUser['department']?></span></td></tr></table></div></div>" data-popover-position="bottom"  data-cls-popover="border bd-teal"><?=$createdUser['name']?></a>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Issue</span>
                                                    <span style="font-size: 14px; padding: 0px 4px 0px 3px;">
                                                    <?php
                                                    $ponumbertemp = $masterData[0]['VPONUMBER'];
                                                    $getAllIssued = $accessoriesModel->getData("SELECT nid, vissue, nissuestatus FROM accessories_workordermaster WHERE vponumber = '$ponumbertemp'");
                                                    if(is_array($getAllIssued)):
                                                        echo 'ISSUE-'.$masterData[0]['VISSUE'];
                                                        foreach ($getAllIssued as $issuekey => $issuevalue):
                                                            if($issuevalue['VISSUE'] == $masterData[0]['VISSUE']):
                                                                
                                                            else:
                                                                echo ', <a href="workorder.php?page=details&id='.$issuevalue['NID'].'">ISSUE-'.$issuevalue['VISSUE'].'</a>';
                                                            endif;
                                                        endforeach;
                                                    else:
                                                        echo '-';
                                                    endif;
                                                    ?>   
                                                    </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Approved By</span>
                                                        <?php
                                                        if($masterData[0]['NAPPROVEDSTATUS'] == 1):
                                                            $approvedUser = $accessoriesModel->getUser($masterData[0]['VAPPROVEDUSER']);
                                                        ?>
                                                        <span style="font-size: 14px; padding: 0px 4px 0px 3px;"><a href="javascript:void(0)" data-role="popover" data-popover-text="<div class='row no-gap' style='width:310px;'><div class='cell' style='max-width: 105px;'><div style='width: 100px;'><?=$approvedUser['picture']?></div></div><div class='cell'><table class='subcompact table workorder-detailstable bg-white cell-border'><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$approvedUser['name']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$approvedUser['designation']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$approvedUser['department']?></span></td></tr></table></div></div>" data-popover-position="bottom"  data-cls-popover="border bd-teal"><?=$approvedUser['name']?></a></span>
                                                        <?php
                                                        else:
                                                            echo '-';
                                                        endif;
                                                        ?>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Created At</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=$masterData[0]['VCREATEDAT']?></span>
                                                    </td>
                                                    <td style="padding-left: 0px;">
                                                        <?php $updatedUser = $accessoriesModel->getUser($masterData[0]['VLASTUPDATEDUSER']); ?>
                                                        <span class="text-bold searchLabel" style="background: #dedede;">Updated By</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><a href="javascript:void(0)" data-role="popover" data-popover-text="<div class='row no-gap' style='width:310px;'><div class='cell' style='max-width: 105px;'><div style='width: 100px;'><?=$updatedUser['picture']?></div></div><div class='cell'><table class='subcompact table workorder-detailstable bg-white cell-border'><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$updatedUser['name']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$updatedUser['designation']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$updatedUser['department']?></span></td></tr></table></div></div>" data-popover-position="bottom"  data-cls-popover="border bd-teal"><?=$updatedUser['name']?></a>    <strong>@</strong> <?=$masterData[0]['VLASTUPDATEDAT']?></span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 0px;">
                                                        <span class="text-bold searchLabel" style="background: #dedede;">Accepted By</span><?php
                                                        if($masterData[0]['NACCEPTENCESTATUS'] == 1):
                                                            $acceptedUser = $accessoriesModel->getUser($masterData[0]['VACCEPTEDUSER']);
                                                        ?>
                                                        <span style="font-size: 14px; padding: 0px 4px 0px 3px;"><a href="javascript:void(0)" data-role="popover" data-popover-text="<div class='row no-gap' style='width:310px;'><div class='cell' style='max-width: 105px;'><div style='width: 100px;'><?=$acceptedUser['picture']?></div></div><div class='cell'><table class='subcompact table workorder-detailstable bg-white cell-border'><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$acceptedUser['name']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$acceptedUser['designation']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$acceptedUser['department']?></span></td></tr></table></div></div>" data-popover-position="bottom"  data-cls-popover="border bd-teal"><?=$acceptedUser['name']?></a></span>
                                                        <?php
                                                        else:
                                                            echo '-';
                                                        endif;
                                                        ?>
                                                      
                                                    </td>
                                                    <td style="padding-left: 0px;">
                                                        <span class="text-bold searchLabel" style="background: #dedede;">Published By</span><?php
                                                        if(!empty($masterData[0]['VPUBLISHEDUSER'])):
                                                        $publishedUser = $accessoriesModel->getUser($masterData[0]['VPUBLISHEDUSER']);
                                                        ?><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><a href="javascript:void(0)" data-role="popover" data-popover-text="<div class='row no-gap' style='width:310px;'><div class='cell' style='max-width: 105px;'><div style='width: 100px;'><?=$publishedUser['picture']?></div></div><div class='cell'><table class='subcompact table workorder-detailstable bg-white cell-border'><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$publishedUser['name']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$publishedUser['designation']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$publishedUser['department']?></span></td></tr></table></div></div>" data-popover-position="bottom"  data-cls-popover="border bd-teal"><?=$publishedUser['name']?></a>
                                                    <?php
                                                    else:
                                                        echo '-';
                                                    endif;
                                                    ?>
                                                    </td>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #dedede;">Published At</span><span style="font-size: 14px; padding: 0px 4px 0px 3px;"><?=(!empty($masterData[0]['VPUBLISHEDUSER']) ? $masterData[0]['VPUBLISHEDAT'] : '-')?></span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="cell-12" style="margin-top: -10px;">
                                            <?php
                                            if(!empty($masterData[0]['VORDERDETAILS'])): 
                                            ?>
                                            <table class='subcompact table cell-border workorder-detailstable bg-white' style="width:100%;">
                                                <tr>
                                                    <td style="padding-left: 0px; background: #dedede;"><span style="font-size: 14px; padding: 0px 4px 0px 3px; font-weight: bold;">Order Details</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 4px;"><span style="font-size: 14px;"><?=nl2br(trim(htmlspecialchars_decode($masterData[0]['VORDERDETAILS'],ENT_QUOTES))) ?></span>
                                                    </td>
                                                </tr>

                                            </table>
                                            <?php
                                            endif;
                                            $numberofTable = $accessoriesModel->getData("SELECT itemtable.nid, itemtable.nworkordermasterid, itemtable.ntotalqty, itemtable.ntotalgarmentsqty, itemtable.ntotalgarmentsqtywithextra, itemtable.vaddition, itemtable.vconvertion, (SELECT vname FROM accessories_goods WHERE nid = itemtable.ngoodsid) AS itemname, itemtable.vordernumber, itemtable.vpnnumber, itemtable.vcolumnname, itemtable.vqtyunit, itemtable.vgridtype, itemtable.vsizename FROM accessories_workorderitems itemtable WHERE itemtable.nworkordermasterid = $id ORDER BY itemtable.nid ASC");
                                            foreach ($numberofTable as $key => $value):
                                                $tableId = $value['NID'];
                                            ?>
                                                <table class='subcompact table cell-border workorder-detailstable bg-white' style="width:100%;">
                                                    <tr>
                                                        <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #363434;color: #ffff;">Name of Item</span><span style="font-size: 14px; padding: 0px 4px 0px 3px; font-weight: bold;"><?=$value['ITEMNAME']?></span>
                                                        </td>
                                                    </tr>
                                                </table>                                                
                                            <?php
                                                $tableColumn = array();
                                                $customColumnSelector = array();
                                                if(!empty($value['VORDERNUMBER'])):
                                                    array_push($tableColumn, 'Order No.');
                                                endif;
                                                if(!empty($value['VPNNUMBER'])):
                                                    array_push($tableColumn, 'PN No.');
                                                endif;
                                                $customColumnWidth = array();
                                                $sizeExplode = array();
                                                $customColumn = explode(',', $value['VCOLUMNNAME']);
                                                foreach ($customColumn as $columnKey => $column):
                                                    array_push($customColumnSelector, 'customcolumn.vcolumn'.($columnKey+1));
                                                    array_push($tableColumn, trim($column));
                                                endforeach;
                                                if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                  array_push($tableColumn, 'Garments Qty.');
                                                endif;
                                                if($value['VGRIDTYPE'] == 'colornsize'):
                                                    $sizeExplode = explode(',', $value['VSIZENAME']);
                                                    foreach ($sizeExplode as $sizekey => $size):
                                                        array_push($tableColumn, trim($size));
                                                    endforeach;
                                                endif;
                                                if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTYWITHEXTRA', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                    array_push($tableColumn, trim($value['VADDITION']));
                                                endif;
                                                if($workorderOpt->checkColumnExist($tableId, 'NCONVERTERQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                    array_push($tableColumn, trim($value['VCONVERTION']));
                                                endif;
                                                if($workorderOpt->checkColumnExist($tableId, 'NROWTOTALQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                    array_push($tableColumn, 'W.O. Total Req. Qty.');
                                                    array_push($tableColumn, 'Unit');
                                                endif;
                                                if($workorderOpt->checkColumnExist($tableId, 'VREMARKS', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                   array_push($tableColumn, 'Remarks');
                                                endif;
                                            ?>
                                                <table class='subcompact table cell-border striped workorder-detailstable bg-white' style='margin-top: 0px; width:100%;'>
                                                    <tr>
                                            <?php          
                                                foreach ($tableColumn as $columns):
                                            ?>
                                                        <th class="text-center"><?=$columns?></th>
                                            <?php                                                    
                                                endforeach;
                                            ?>      </tr>
                                            <?php
                                                $customColumnName = count($customColumnSelector) > 0 ? ','.implode(',', $customColumnSelector) : '';
                                                $gridSql = "SELECT rowdata.nid, rowdata.nrowtotalqty, rowdata.nrowgarmentsqty, rowdata.nrowgarmentsqtywithextra, rowdata.nconverterqty, rowdata.vremarks $customColumnName ";
                                                if($value['VGRIDTYPE'] == 'colornsize'):
                                                  $gridSql .= ",LISTAGG(sizewiseqty.nrequiredqty, ',') WITHIN GROUP (ORDER BY sizewiseqty.nid ASC) AS QTY";
                                                endif;
                                                  $gridSql .= " FROM accessories_workorderitemdata rowdata LEFT JOIN accessories_customcolumnvalue customcolumn ON customcolumn.ngriditemdataid = rowdata.nid";
                                                if($value['VGRIDTYPE'] == 'colornsize'):
                                                  $gridSql .= " LEFT JOIN accessories_workordersizeqty sizewiseqty ON sizewiseqty.nworkorderitemsdataid = rowdata.nid";
                                                endif;
                                                $gridSql .= " WHERE rowdata.nworkorderitemsid = $tableId GROUP BY rowdata.nid, rowdata.nrowtotalqty, rowdata.nrowgarmentsqty, rowdata.nrowgarmentsqtywithextra, rowdata.nconverterqty, rowdata.vremarks $customColumnName ORDER BY rowdata.nid ASC";
                                                // echo $gridSql;
                                                $gridData = $accessoriesModel->getData($gridSql);
                                                $sizeDataTemp = array();
                                                
                                                foreach ($gridData as $keyParent => $gridValue):
                                                    $footerCol1 = array();
                                                    $footerColSpan = array();
                                                    $tempArr = array();
                                                    if(!empty($value['VORDERNUMBER'])):
                                                        array_push($tempArr, htmlspecialchars_decode($value['VORDERNUMBER'], ENT_QUOTES));
                                                        array_push($footerColSpan, 1);
                                                    endif;
                                                    if(!empty($value['VPNNUMBER'])):
                                                        array_push($tempArr, htmlspecialchars_decode($value['VPNNUMBER'], ENT_QUOTES));
                                                        array_push($footerColSpan, 1);
                                                    endif;
                                                    if(count($customColumnSelector) > 0):
                                                        for($i = 1; $i <= count($customColumnSelector); $i++):
                                                            array_push($tempArr, htmlspecialchars_decode(trim($gridValue['VCOLUMN'.$i]), ENT_QUOTES));
                                                            array_push($footerColSpan, 1);
                                                        endfor;
                                                    endif;
                                                    if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                        array_push($tempArr,  number_format($gridValue['NROWGARMENTSQTY']));
                                                        array_push($footerCol1, number_format($value['NTOTALGARMENTSQTY']));
                                                    endif;
                                                    if(!empty($gridValue['QTY'])):
                                                        $explodeQty = explode(',', $gridValue['QTY']);
                                                        foreach ($explodeQty as $key => $sizeQty):
                                                          array_push($tempArr,  number_format($sizeQty));
                                                          $sizeDataTemp[$key] = isset($sizeDataTemp[$key]) ? $sizeDataTemp[$key] + $sizeQty : $sizeQty;
                                                        endforeach;
                                                    endif;
                                                    if($workorderOpt->checkColumnExist($tableId, 'NROWGARMENTSQTYWITHEXTRA', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                        array_push($tempArr, number_format($gridValue['NROWGARMENTSQTYWITHEXTRA']));                                      
                                                        array_push($footerCol1, number_format($value['NTOTALGARMENTSQTYWITHEXTRA']));
                                                    endif;
                                                    if($workorderOpt->checkColumnExist($tableId, 'NCONVERTERQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                        array_push($tempArr, number_format($gridValue['NCONVERTERQTY'], 3));
                                                        array_push($footerCol1, '');
                                                    endif;
                                                    if($workorderOpt->checkColumnExist($tableId, 'NROWTOTALQTY', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                        array_push($tempArr, strtolower(htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)) == "con's" ? number_format($gridValue['NROWTOTALQTY']) : number_format($gridValue['NROWTOTALQTY'], 2));
                                                        
                                                        array_push($tempArr, htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES));
                                                       
                                                        array_push($footerCol1, strtolower(htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)) == "con's" ? number_format($value['NTOTALQTY']) : number_format($value['NTOTALQTY'], 2));
                                                        array_push($footerCol1, str_replace("'", "", htmlspecialchars_decode(trim($value['VQTYUNIT']), ENT_QUOTES)));
                                                    endif;
                                                    if($workorderOpt->checkColumnExist($tableId, 'VREMARKS', 'ACCESSORIES_WORKORDERITEMDATA') == 1):
                                                        array_push($tempArr, htmlspecialchars_decode(trim($gridValue['VREMARKS']), ENT_QUOTES));
                                                        array_push($footerCol1, '');
                                                    endif;
                                            ?>
                                               
                                                    <tr>
                                            <?php
                                                    foreach ($tempArr as $key => $tableData):
                                            ?>
                                                        <td class="text-center" style="font-size: 82%;"><?=$tableData?></td>
                                            <?php  
                                                    endforeach;
                                            ?>
                                                    </tr>
                                            <?php
                                                endforeach;
                                                $mergeFooterCol = array();
                                                foreach ($sizeDataTemp as $key => $sizevalue):
                                                    array_push($mergeFooterCol, number_format($sizevalue));
                                                endforeach;
                                                $footerCol1 = array_merge($mergeFooterCol, $footerCol1); 
                                                array_unshift($footerCol1, 'Quantity Grand Total');
                                            ?>
                                            
                                                <tr>
                                            <?php
                                                foreach ($footerCol1 as $footerKey => $footervalue):
                                                    if($footerKey == 0):
                                            ?>
                                                       <td colspan="<?=count($footerColSpan)?>" class="text-right text-bold" style="background: #dedede; font-size: 81%;"><?=$footervalue?></td>
                                            <?php
                                                    else:
                                            ?>
                                                       <td class="text-center text-bold" style="background: #dedede; font-size: 81%;"><?=$footervalue?></td>
                                            <?php
                                                    endif;
                                                endforeach;
                                            ?>
                                                  </tr>
                                              
                                                </table>

                                            <?php
                                                $attachment = $accessoriesModel->getData("SELECT bimage, vfileformate FROM accessories_images WHERE nworkorderitemid = $tableId");
                                                if(is_array($attachment)):
                                                ?>
                                                    <table class='subcompact table cell-border workorder-detailstable bg-white' style="width:100%;">
                                                        <tr>
                                                            <td style="padding-left: 0px; background: #dedede;" colspan="<?=count($attachment);?>"><span style="font-size: 14px; padding: 0px 4px 0px 3px; font-weight: bold;">Attachment(s)</span>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                <?php
                                                        foreach ($attachment as $key => $images):
                                                            $img = $images['BIMAGE']->load(); ?>
                                                            <td style="padding-left: 4px; text-align: center;"><img src="data:<?=$images['VFILEFORMATE']?>;base64,<?=base64_encode($img)?>" style="max-width: 215px;margin: 0 auto; min-width: 90px;">
                                                            </td>
                                                <?php     
                                                        endforeach;
                                                ?>
                                                        </tr>
                                                    </table>
                                                <?php
                                                endif;
                                            endforeach;
                                            ?>
                                            <?php
                                            if(!empty($masterData[0]['VEXTRANOTES'])): 
                                            ?>
                                            <table class='subcompact table cell-border workorder-detailstable bg-white' style="width:100%;">
                                                <tr>
                                                    <td style="padding-left: 0px; background: #dedede;"><span style="font-size: 14px; padding: 0px 4px 0px 3px; font-weight: bold;">Terms & Condition</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 4px;"><span style="font-size: 14px;"><?=nl2br(trim(htmlspecialchars_decode($masterData[0]['VEXTRANOTES'],ENT_QUOTES))) ?></span>
                                                    </td>
                                                </tr>

                                            </table>
                                            <?php
                                            endif;
                                            ?>
                                            <table class='subcompact table cell-border workorder-detailstable bg-white' style="width:100%;">
                                                <tr>
                                                    <td style="padding-left: 0px;"><span class="text-bold searchLabel" style="background: #363434;color: #ffff;">Delivery Date</span><span style="font-size: 14px; padding: 0px 4px 0px 3px; font-weight: bold;"><?=$masterData[0]['VDELIVERYDATE']?></span>
                                                    </td>
                                                </tr>
                                            </table>            
                                        </div>
                                    </div>
                                </div>

                                <div class="cell-md-2 bd-light border bg-white">
                                    <div class="progress-bar-heading">
                                        <p><span class="mif-safari icon"></span> Current Status</p>
                                    </div>
                                    <div class="workorder-progress-bar">
                                        <ol class="steps">
                                            <?php
                                            if($masterData[0]['VSTATUS'] == 'publish'):
                                            ?>
                                            <li class="step is-complete" data-step="1">Published   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Published By <strong><?=$accessoriesModel->getUser($masterData[0]['VCREATEDUSER'])['name'];?></strong>" style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span></li>
                                            <?php
                                            else:
                                            ?>
                                            <li class="step is-active" data-step="1">Published   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Awaiting to publish" style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span>  <button class="image-button success" type="button" onclick="workorderManualUpdate('published', <?=$id?>);" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-spinner icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Publish</span></button></li>
                                            <?php
                                            endif;
                                            ?>
                                            <?php
                                            /*
                                            if($masterData[0]['NCHECKEDSTATUS'] == 1 && $masterData[0]['VSTATUS'] == 'publish'):
                                            ?>
                                            <li class="step is-complete" data-step="2">Checked   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Checked By <strong><?=$accessoriesModel->getUser($masterData[0]['VCHECKEDUSER'])['name'];?></strong>" style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span></li>
                                            <?php
                                            elseif($masterData[0]['NCHECKEDSTATUS'] == 0 && $masterData[0]['VSTATUS'] == 'publish'):
                                            ?>
                                            <li class="step is-active" data-step="2">Checked  <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Waiting for the responsible person to check." style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span></li>
                                            <?php
                                            else:
                                            ?>
                                            <li class="step" data-step="2">Checked</li>
                                            <?php
                                            endif;*/
                                            ?>
                                            <?php
                                            if($masterData[0]['NAPPROVEDSTATUS'] == 1 && $masterData[0]['VSTATUS'] == 'publish'):
                                            ?>
                                            <li class="step is-complete" data-step="2">Approved   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Approved By <strong><?=$accessoriesModel->getUser($masterData[0]['VAPPROVEDUSER'])['name'];?></strong>" style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span></li>
                                            <?php
                                            elseif($masterData[0]['NAPPROVEDSTATUS'] == 0 && $masterData[0]['VSTATUS'] == 'publish'):
                                            ?>
                                            <li class="step is-active" data-step="2">Approved   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Waiting for the approval of responsible person." style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span>  <button class="image-button success" type="button" onclick="workorderManualUpdate('approved', <?=$id?>);" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Approve</span></button></li>
                                            <?php
                                            else:
                                            ?>
                                            <li class="step" data-step="2">Approved</li>
                                            <?php
                                            endif;
                                            ?>
                                            <?php
                                            if($masterData[0]['NACCEPTENCESTATUS'] == 1 && $masterData[0]['NAPPROVEDSTATUS'] == 1):
                                            ?>
                                            <li class="step is-complete" data-step="3">Accepted   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Accepted By <strong><?=$accessoriesModel->getUser($masterData[0]['VACCEPTEDUSER'])['name'];?></strong>" style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span></li>
                                            <?php
                                            elseif($masterData[0]['NACCEPTENCESTATUS'] == 0 && $masterData[0]['NAPPROVEDSTATUS'] == 1):
                                            ?>
                                            <li class="step is-active" data-step="3">Accepted   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Waiting for store respons." style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span>  <button class="image-button success" type="button" onclick="workorderManualUpdate('accepted', <?=$id?>);" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-done_all icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Accept</span></button></li>
                                            <?php
                                            else:
                                            ?>
                                            <li class="step" data-step="3">Accepted By Store</li>
                                            <?php
                                            endif;
                                            ?>
                                            <?php
                                            if($masterData[0]['NMRSTATUS'] == 1 && $masterData[0]['NACCEPTENCESTATUS'] == 1):
                                            ?>
                                            <li class="step is-complete" data-step="4">Materials Received   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Materials Received By <strong><?=$accessoriesModel->getUser($masterData[0]['VMRUSER'])['name'];?></strong>" style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span></li>
                                            <?php
                                            elseif($masterData[0]['NMRSTATUS'] == 0 && $masterData[0]['NACCEPTENCESTATUS'] == 1):
                                            ?>
                                            <li class="step is-active" data-step="4">Materials Received   <span class="mif-info bg-darkGrayBlue fg-gray" data-role="hint" data-hint-hide="10000" data-hint-position="left" data-hint-text="Waiting to receive work order materials." style="border-radius: 50%;width: 16px;height: 16px;text-align: center;line-height: 16px;cursor: pointer;font-size: 12px;"></span></li>
                                            <?php
                                            else:
                                            ?>
                                            <li class="step" data-step="4">Materials Received</li>
                                            <?php
                                            endif;
                                            ?>
                                        </ol>
                                    </div>
                                    <div class="progress-bar-heading mt-1">
                                        <p><span class="mif-history icon"></span> History</p>
                                    </div>
                                    <div class="history-content">
                                    <?php
                                    $historyData = $accessoriesModel->getData("SELECT * FROM ACCESSORIES_HISTORYLOG WHERE NDEPENDENTID = $id ORDER BY NID DESC");
                                    if(is_array($historyData)):
                                        foreach ($historyData as $key => $history):
                                    ?>

                                    <table class='subcompact table cell-border workorder-detailstable bg-white' style="box-shadow: 2px 3px 4px #103642;">
                                        <tr>
                                            <td style="padding-left: 4px; position: relative;"><span style="font-size: 14px; font-weight: bold;border-bottom: 1px dashed #489fb5; display: block;"><?=$history['VHISTORYTEXT']?></span>
                                                <span style="font-size: 11.5px;">By, <?php
                                                $historyUser = $accessoriesModel->getUser($history['VFKLID']); ?>
                                                    <a href="javascript:void(0)" data-role="popover" data-popover-text="<div class='row no-gap' style='width:310px;'><div class='cell' style='max-width: 105px;'><div style='width: 100px;'><?=$historyUser['picture']?></div></div><div class='cell'><table class='subcompact table workorder-detailstable bg-white cell-border'><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$historyUser['name']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$historyUser['designation']?></span></td></tr><tr><td style='padding-left: 0px;'><span style='font-size: 14px; padding: 0px 4px 0px 3px;'><?=$historyUser['department']?></span></td></tr></table></div></div>" data-popover-position="left" data-cls-popover="border bd-teal"><?=$historyUser['name']?></a> At, <?=$history['VDATETIME']?></span>
                                                    <div style="position: absolute;top: -12px;right: -1px;font-weight: bold;padding: 0px 5px;background: #006d77;border-radius: 4px;color: #fff;"><?=($key+1)?></div>
                                            </td>
                                        </tr>
                                        
                                    </table>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                    </div>
                                </div>
                            </div>
                        <?php
                            else:
                                $pageOpt->redirectWithscript($pageOpt->previousPageUrl(), 'Invalid work order id!');
                            endif;
                        ?>                  
                        </div>
                    </div>
                </div>
            </div>
            <?php
            elseif($_GET['page'] == 'newissue'):
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                if($accessoriesModel->checkDataExistence("SELECT nid FROM accessories_workordermaster WHERE nid = $id AND nissuestatus = 1 AND ndeletedstatus = 0 AND napprovedstatus = 1 AND nacceptencestatus = 1 AND (vcreateduser = '$userid' OR vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin')") == 'exist'):
                    $masterData = $accessoriesModel->getData("SELECT master.nid, master.vtodate, master.vponumber, master.vissue, master.vstatus, master.vattn, master.vform, master.vordernumberorfklnumber, master.vtype, master.vorderdetails, master.vextranotes, master.vdeliverydate, master.vcreateduser, master.vcreatedat, master.vgarmentsqty, master.vissue, master.ncheckedstatus, master.vcheckeduser, master.vlastupdateduser, master.vlastupdatedat, master.vpublisheduser, master.vpublishedat, master.napprovedstatus, master.vapproveduser, master.nacceptencestatus, master.vaccepteduser, master.nmrstatus, master.vmruser, master.nsupllierid FROM accessories_workordermaster master WHERE master.nid = $id AND master.nissuestatus = 1 AND master.ndeletedstatus = 0 AND master.napprovedstatus = 1 AND master.nacceptencestatus = 1 AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') ORDER BY master.nid ASC");
                    if(strtolower($masterData[0]['VTYPE']) == 'order number'):
                    $orderNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
                    $orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ksid, regexp_replace(        LISTAGG(orderinfo.vcountry, ',') WITHIN GROUP(ORDER BY orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS country, regexp_replace(LISTAGG(kmaster.vsizebreakdown, '##') WITHIN GROUP(ORDER BY orderinfo.norderid ASC), '([^##]+)(##\\1)+', '\\1') AS sizebreakdown, orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_vw_orderinfo orderinfo LEFT JOIN erp.mer_ks_master kmaster ON kmaster.nordercode = orderinfo.norderid WHERE orderinfo.vordernumber = '$orderNumber' GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname");
                    $fklNumber = $orderInfo[0]['KSID'];
                    elseif(strtolower($masterData[0]['VTYPE']) == 'fkl number'):
                    $fklNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
                    $orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(orderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ordernumber, regexp_replace(LISTAGG(orderinfo.vcountry, ',') WITHIN GROUP(ORDER BY orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS country, regexp_replace(LISTAGG(kmaster.vsizebreakdown, '##') WITHIN GROUP(ORDER BY orderinfo.norderid ASC), '([^##]+)(##\\1)+', '\\1') AS sizebreakdown, orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_ks_master kmaster LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.norderid = kmaster.nordercode WHERE kmaster.nks_id IN ($fklNumber) GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname");
                    $orderNumber = $orderInfo[0]['ORDERNUMBER'];
                    else:
                        $orderInfo =  array();
                    endif;
                    $supplierid = $masterData[0]['NSUPLLIERID'];
                    $materialCheckData =  $accessoriesModel->getData("SELECT LISTAGG(ngoodsid, ',') WITHIN GROUP(ORDER BY nid ASC)AS goodsid FROM accessories_workorderitems WHERE nworkordermasterid = $id GROUP BY nworkordermasterid");
                    $goodsArr = explode(',', $materialCheckData[0]['GOODSID']);
                    // print_r($goodsArr);
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
                    <div data-role="panel" data-title-caption="Issue-<?=$masterData[0]['VISSUE']+1?> Work Order <strong>(<?=$masterData[0]['VPONUMBER']?>)</strong>" data-title-icon="<span class='mif-plus'></span>" class="workorder-form-panel" data-collapsible="false">
                        <div class="errors custom-alert">
                        </div>
                        <div class="success custom-alert">
                        </div>
                        <div class="p-1">
                            <form method="POST" action="" class="order-search">
                                <input type="hidden" name="csrf" class="csrf" value="<?=$db->csrfToken()?>">
                                <div class="row">
                                    <div class="cell-xl-6 cell-lg-12">
                                        <div class="row">
                                            <div class="cell-sm-6">
                                                <div class="form-group">
                                                    <label>Enter Order / FKL No.<span class="fg-red">*</span></label>
                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                        <div class="cell-5" style="padding: 0px;">
                                                            <select data-role="select" name="input-ordernumber-type" class="input-small input-ordernumber-type" data-filter="false">
                                                                <option value="<?=$masterData[0]["VTYPE"]?>"><?=$masterData[0]["VTYPE"]?></option>
                                                            </select>
                                                        </div>
                                                        <div class="cell-7" style="padding: 0px;">
                                                            <input type="text" data-role="input" class="input-small vordernumberorfklnumber" readonly data-cls-input="place-right" value="<?=$masterData[0]['VORDERNUMBERORFKLNUMBER']?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="cell-sm-6 cell-md-4 accessories-content-hidden content-visible lotappend">
                                                
                                            </div> -->
                                            <div class="cell-sm-6 ">
                                                <div class="form-group">
                                                    <label>Select Supplier<span class="fg-red">*</span></label>
                                                    <label style="position: absolute; right: 0; top: -2px;">
                                                        <a href="javscript:void(0)" class="image-button secondary secondary ribbed-teal bg-darkTeal-hover" style="height: 20px;">
                                                            <span class='mif-plus icon' style="height: 20px; line-height: 20px; font-size: .7rem; width: 20px;"></span>
                                                            <span class="caption">New</span>
                                                        </a>
                                                    </label>
                                                    <select data-role="select" name="suppliers" class="input-small suppliername" data-filter-placeholder="Search Suplliers...">
                                                        <?= $appsDependent->dropdownCommon('ACCESSORIES_SUPPLIERS', 'NID', 'VNAME', "$supplierid") ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>To Date<span class="fg-red">*</span></label>
                                                    <input type="text" name="todate"  class="todate input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" data-clear-button="true" data-input-format="%d-%m-%y" data-min-date="<?=date('d-m-Y');?>" value="<?=date('d-m-Y');?>">
                                                    <span class="invalid_feedback">To date is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>Attn.<span class="fg-red">*</span></label>
                                                    <input type="text" data-role="input"  class="input-small required-field attnetion-name" name="attn" placeholder="Enter Name" value="<?=$masterData[0]["VATTN"]?>">
                                                    <span class="invalid_feedback">Attn. name is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>From<span class="fg-red">*</span></label>
                                                    <input type="text" data-role="input"  class="input-small required-field form-name" name="form" placeholder="Enter Name" value="<?=$masterData[0]["VFORM"]?>">
                                                    <span class="invalid_feedback">From name is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>Select Delivery Date<span class="fg-red">*</span></label>
                                                    <input type="text" name="deliverydate"  class="deliverydate accessories-disable input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" data-clear-button="true" data-input-format="%d-%m-%y" data-min-date="<?=date('d-m-Y');?>" value="">
                                                    <span class="invalid_feedback">Delivery date is required.</span>
                                                </div>
                                            </div>
                                            <?php
                                            $groupData = $accessoriesModel->getData("SELECT nid, vname FROM ACCESSORIES_GROUP ORDER BY nid ASC");
                                            if(is_array($groupData)):
                                            foreach ($groupData as $groupName):
                                            ?>
                                            <div class="cell-sm-6 cell-md-4 ">
                                                <div class="form-group">
                                                    <label>Select <?=$groupName['VNAME']?></label>
                                                    <select data-role="select" name="selecteditem" multiple class="input-small accessories-disable itemsevent"  data-filter-placeholder="Search <?=$groupName['VNAME']?>">
                                                        <?php
                                                        $groupId = $groupName['NID'];
                                                        $subGroupData = $accessoriesModel->getData("SELECT nid, vname FROM ACCESSORIES_SUBGROUP WHERE ngroupid = $groupId ORDER BY nid ASC");
                                                        if(is_array($subGroupData)):
                                                        foreach ($subGroupData as $subGroupName):
                                                        ?>
                                                        <optgroup label="<?=$subGroupName['VNAME']?>" style="background-color: #e0f0f1;">
                                                            <?php
                                                            $subGroupData = $subGroupName['NID'];
                                                            $goods = $accessoriesModel->getData("SELECT goods.nid, goods.vname, goods.vparameters, unit.vnameshort FROM ACCESSORIES_GOODS goods LEFT JOIN ACCESSORIES_QUANTITYUNIT unit ON unit.nid = goods.nqtyunitid WHERE nsubgroupid = $subGroupData ORDER BY nsubgroupid ASC");
                                                            if(is_array($goods)):
                                                            foreach ($goods as $good):
                                                            ?>
                                                            <option value="<?=$good['NID']?>" <?=(in_array($good['NID'], $goodsArr) ? 'selected' : '')?> data-qtyunit="<?=$good['VNAMESHORT']?>" data-perameters="<?=$good['VPARAMETERS']?>"><?=$good['VNAME']?></option>
                                                            <?php
                                                            endforeach;
                                                            endif;
                                                            ?>
                                                        </optgroup>
                                                        <?php
                                                        endforeach;
                                                        endif;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            endforeach;
                                            else:
                                            echo $groupData;
                                            endif;
                                            ?>
                                            
                                        </div>
                                    </div>
                                    <div class="cell-xl-6 cell-lg-12">
                                        <?php
                                        $allCountry = array_unique(explode(',', str_replace(array("##", "+"),array(",", ","),$orderInfo[0]['COUNTRY'])));
                                        $allSize = implode(',', array_unique(explode(',', str_replace("##",",",$orderInfo[0]['SIZEBREAKDOWN']))));
                                        ?>
                                        <input type="hidden" class="allcountry" value="<?=implode(',', $allCountry);?>">
                                        <input type="hidden" class="allsize" value="<?=$allSize;?>">
                                        <input type="hidden" class="masterid" value="<?=$masterData[0]['NID']?>">
                                        <input type="hidden" class="ponumber" value="<?=$masterData[0]['VPONUMBER']?>">
                                        <input type="hidden" class="issuenumber" value="<?=($masterData[0]['VISSUE']+1)?>">
                                        <input type="hidden" class="addrowdisabler" value="0">
                                        <div class="cell-12" style="margin-top: 15px;">
                                            <table class="subcompact cell-border bg-white table searchordertable " style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                                                <tr>
                                                    <td style="padding: 0px;">
                                        
                                                        <span class="" style="<?=(strtolower($masterData[0]['VTYPE']) == 'fkl number' ? 'display: none;' : 'display: block;')?>"><span class="text-bold searchLabel">FKL NO.</span> <span id="fklno"><?=$fklNumber?></span></span><span class="" style="<?=(strtolower($masterData[0]['VTYPE']) == 'order number' ? 'display: none;' : 'display: block;')?>"><span class="text-bold searchLabel">ORDER NO.</span> <span id="ordernumber"><?=$orderNumber?></span></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Style Name</span> <span id="stylename"><?=$orderInfo[0]['STYLENAME']?></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Buyer Name</span> <span id="buyername"><?=$orderInfo[0]['BUYERNAME']?></span></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Season</span> <span id="season"><?=$orderInfo[0]['VSEASSONNAME']?></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Dept.</span> <span id="dept"><?=$orderInfo[0]['VDEPTNAME']?></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Kimball</span> <span id="kimble"><?=$orderInfo[0]['KIMBALLNO']?></span></td>
                                                    <!-- <td style="padding-left: 0px;"><span class="text-bold searchLabel">Gmts Qty</span> <span id="qty"></span><input type="hidden" name="gmtqty" class="gmtqty required-field" value=''><span class="invalid_feedback">Garments qty is required.</span></td> -->
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="cell-12" style="margin-top: -10px;">
                                            <div class="form-group">
                                                <label>Order Details</label>
                                                <textarea data-role="textarea" name="order-details" class="order-detils"><?=$masterData[0]['VORDERDETAILS']?></textarea>
                                                <!-- <span class="invalid_feedback">Order details is required.</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell-sm-12">
                                        <div class="ribbed-lightGray maingridheader" style="box-shadow: 0px 0px 2px #fff; margin-top: -10px; position: relative;">
                                            <h5 class="p-1" style="font-size: 1.1rem;">Details Entry Section</h5>
                                        </div>
                                    </div>
                                    <div class="gridappender cell-sm-12" style="margin-top: -15px;">
                                        <?php
                                        $itemdata = $accessoriesModel->getData("SELECT item.nid, goods.nid AS goodsid, goods.vname AS goodsname, goods.vparameters, item.vqtyunit FROM accessories_workorderitems item INNER JOIN accessories_goods goods ON goods.nid = item.ngoodsid WHERE item.nworkordermasterid = $id ORDER BY item.nid ASC");
                                        foreach ($itemdata as $key => $item):
                                            $getItemName = htmlspecialchars_decode($item['GOODSNAME']);
                                            $getItemId = $item['GOODSID'];
                                            $itemId = $item['NID'];
                                            $parentId = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($getItemName)).$getItemId;
                                            if($key % 2 == 0):
                                                $background = "custom-table-bg";
                                                $backgroundCell = "bg-darkCyan fg-white";
                                            else:
                                                $background = "custom-table-bg1";
                                                $backgroundCell = "bg-darkGrayBlue fg-white";
                                            endif;
                                        ?>
                                        <div class="workorder-table-main pt-1 mb-2 <?=$background;?>" id="<?=$parentId?>" style="padding-bottom:1px;">
                                            
                                            <?php 
                                            /*
                                            ** Option Table Start.
                                            */
                                            ?>

                                            <table class="subcompact cell-border table searchordertable" style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                                                <tr>
                                                    <td style="background: #e0f0f1; width:132px; font-weight:bold;" class="text-left">Available Columns</td>
                                                    <?php
                                                    $parameters = explode(',', htmlspecialchars_decode($item['VPARAMETERS']));
                                                    array_push($parameters, 'Code No.');
                                                    array_push($parameters, 'Garments Qty');
                                                    array_push($parameters, 'Addition');
                                                    array_push($parameters, 'Converter');
                                                    array_push($parameters, 'Upload');
                                                    $cellData1 = "";
                                                    $cellData2 = "";
                                                    $cellData3 = "";
                                                    foreach ($parameters as $key => $options):
                                                        if(trim(strtolower($options)) == 'color wise qty' || trim(strtolower($options)) == 'color & size wise qty' || trim(strtolower($options)) == 'kimball/color/size wise qty' || trim(strtolower($options)) == 'size wise qty' || trim(strtolower($options)) == 'kimball & color wise qty'):
                                                            $cellData1 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border  bd-white rounded fg-white parameters-disabled parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                        elseif(trim(strtolower($options)) == 'addition'):
                                                        
                                                            $cellData3 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-crimson rounded bd-white addition-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";

                                                        elseif(trim(strtolower($options)) == 'converter'):
                                                            $cellData3 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-taupe rounded bd-white converter-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";

                                                        elseif(trim(strtolower($options)) == 'upload'):
                                                            if($getItemName == 'Hang Tag' && (trim($orderInfo[0]['BUYERNAME']) == 'Primark' || trim($orderInfo[0]['BUYERNAME']) == 'Penneys' || trim($orderInfo[0]['BUYERNAME']) =='Primark & Penneys')):
                                                                    $cellData3 .= "<div class='row d-flex flex-justify-center'><button type='button' class='mt-1 image-button secondary excel-upload' style='height: 28px;' data-parentid='".$getItemId."' data-name='".$getItemName."' data-tableclass='".$parentId."'><span class='mif-upload icon' style='height: 28px; line-height: 24px; font-size: .9rem; width: 23px;'></span><span class='caption'>Upload (.xlsx or .csv)</span></button></div>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'grmnts color'):
                                                            $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-darkCyan bd-white rounded fg-white parameters-disabled parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                        elseif(trim(strtolower($options)) == 'grmnts color/kimball/lot'):
                                                           
                                                            $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white parameters-disabled bg-darkCyan parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                            
                                                        elseif(trim(strtolower($options)) == 'size name'):
                                                            $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 bg-darkCyan p-1 border  bd-white rounded fg-white parameters-disabled parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                        elseif(trim(strtolower($options)) == 'garments qty'):
                                                            
                                                            $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 bg-darkCyan mb-1 p-1 border  bd-white rounded fg-white parameters-disabled parameters parameters-groupcommon ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                        else:
                                                            $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bd-white rounded bg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                        endif;
                                                    endforeach;

                                                    ?>
                                                    <td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Knitting Shit Data</div><div><?=$cellData1?></div>
                                                    </td>
                                                    <td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Customize Options</div><div><?=$cellData2?></div>
                                                    </td>
                                                    <td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Extra Operation</div><div style='width: 221px; margin: 0 auto;'><?=$cellData3?></div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table class='table subcompact cell-border table maingrid bg-white itemtable <?=$parentId?>' data-uniqueid='<?=$getItemId?>' data-itemid='<?=$item["NID"]?>' onkeydown='enableCellNavigation($(this))'>
                                                <tr>
                                                    <th class='items-header'>Name of Item</th>
                                                    <th class='totalqty-header'>W.O. Required Qty.</th>
                                                    <th class='remarks-header'>Remarks</th>
                                                </tr>
                                                <tr class="data-row-hidden appended-row" style="display: none;">
                                                    <td class='totalqty'>
                                                        <div class='row no-gap'>
                                                            <div class='cell'>
                                                                <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'>
                                                            </div>
                                                            <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='remarks' style='position:relative;'>
                                                        <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea>
                                                        <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                        </a>
                                                    </td>                                                 
                                                </tr>
                                                <tr class="data-row">
                                                    <td class='text-center text-bold maingrid-rowspan items-name items-<?=$getItemId?>' data-itemid='<?=$getItemId?>' rowspan='1'>
                                                        <?=$getItemName?>
                                                    </td>
                                                    <td class='totalqty'>
                                                        <div class='row no-gap'>
                                                            <div class='cell'>
                                                                <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'>
                                                            </div>
                                                            <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='remarks' style='position:relative;'>
                                                        <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea>
                                                        <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                        </a>
                                                    </td>   
                                                </tr>
                                                <tr style="background: #e0f0f1;">
                                                    <td style="font-weight:bold;" class="text-right grandQtyCell"><button onclick='rowAdder($(this), "<?=$parentId?>")' type='button' class='tool-button ribbed-teal success' style='position: absolute;width: 20px;height: 20px;line-height: 18px;top: 6px;z-index: 1083;right: 3px;'><span class='mif-plus' style='font-size: 13px;'></span></button><p style="width: 155px;margin: 0px;">Quantity Grand Total</p></td>
                                                    <td class="grandtotalqty">
                                                        <div class="row no-gap">
                                                            <div class="cell">
                                                                <input type="text" readonly="" class="input-small text-center grand-totalqty-input" name="grandqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="0">
                                                            </div>
                                                            <div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;">
                                                                <span class="text-bold grandunit"><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='itemqty-errors text-left'><span class='invalid_feedback'>W.O. required quantity grand total must be greater than zero(0).</span></td>     
                                                </tr>
                                            </table>
                                            
                                        </div>
                                        <?php
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                                <div class='addition-popup' style="display: none;">
                                    <div class='addittion-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="tableclass">
                                        <input type="hidden" name="columnClass" value="" class="columnclass">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Add Extra Garments Quantity</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <input type='text' class='input-small text-center addition-qty' name='addition' oninput="numberValidate($(this), $(this).val());" style='margin: 0 auto;' value='0'>
                                            </div>
                                            <div class='cell'>
                                                <select name='additiontype' class='addition-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='parcent'>(%) of total garments</option>
                                                    <option value='qty'>Pcs. Added</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success addition-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success addition-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='converter-popup' style="display: none;">
                                    <div class='converter-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="contableclass">
                                        <input type="hidden" name="columnClass" value="" class="concolumnclass">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Apply Quantity Conversion Rules</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 0px;padding: 6px;">
                                            <div class='cell-12'>
                                                <select name='convertiontype' class='convertion-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='' id='convertiontype1' data-caltype='multiply'></option>
                                                     <option value='' id='convertiontype2' data-caltype='divided'></option>
                                                </select>
                                            </div>
                                            <div class="cell-12 extraCalAdded mt-1">
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center; margin-top: -4px;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success convertion-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success converter-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='datafill-popup' style="display: none;">
                                    <div class='datafill-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="datafilltableclass">
                                        <input type="hidden" name="columnClass" value="" class="datafillcolumnclass">
                                        <input type="hidden" name="dataname" value="" class="datafilldataname">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Data Fill Type</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <select name='datafilltype' class='datafill-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='Fixed'>Fixed</option>
                                                    <option value='Manuall'>Manually Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='datafill-popup1' style="display: none;">
                                    <div class='datafill-popup1-container'>
                                        <input type="hidden" name="tableClass" value="" class="datafilltableclass1">
                                        <input type="hidden" name="columnClass" value="" class="datafillcolumnclass1">
                                        <input type="hidden" name="dataname" value="" class="datafilldataname1">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Data Fill Type</p>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px;">
                                            <div class='cell-12'>
                                                <select name='datafilltype' class='datafill1-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='Fixed'>Fixed</option>
                                                    <option value='Manuall'>Manually Select</option>
                                                </select>
                                            </div>
                                            <div class='cell-12 repeater-content'>
                                                <label>Data Repeat</label>
                                                 <select name='datarepeat' class='datarepeat-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='1'>1 time</option>
                                                    <option value='2'>2 times</option>
                                                    <option value='3'>3 times</option>
                                                    <option value='4'>4 times</option>
                                                    <option value='5'>5 times</option>
                                                    <option value='6'>6 times</option>
                                                    <option value='7'>7 times</option>
                                                    <option value='8'>8 times</option>
                                                    <option value='9'>9 times</option>
                                                    <option value='10'>10 times</option>
                                                    <option value='11'>11 times</option>
                                                    <option value='12'>12 times</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-customize-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-customize-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class='excel-popup' style="display: none;">
                                    <div class='excel-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="exceltableclass">
                                        <input type="hidden" name="dataid" value="" class="exceldataid">
                                        <input type="hidden" name="dataname" value="" class="excelitemname">
                                        <input type="hidden" name="dataunit" value="" class="excelitemunit">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Import File (.xlsx or .csv)</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <input type="file" data-role="file" data-mode="drop" class="excel-file" onchange="checkValidFile($(this));">
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success excel-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success excel-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell-sm-12 extra-notes" style="margin-top: -10px;">
                                        <table class='subcompact cell-border table searchordertable extraNotes-table' style='margin-top: 0.5rem; margin-bottom: 0.5rem;'>
                                            <tr>
                                                <td style='background: #e0f0f1; width:130px; font-weight:bold; text-align:center;'>Note</td>
                                                <td class='text-center bg-white'>
                                                    <textarea data-role='textarea' width='100%' name='extranotes' class='extranotes'></textarea>
                                                </td>                                       
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row submit-section text-center d-flex flex-justify-center">
                                    <button type="button" name="workorder-newissue" data-type="publish" class="workorder-newissue image-button border bd-dark-hover success mr-2">
                                    <span class='mif-plus icon'></span>
                                    <span class="caption text-bold">Publish Work Order</span>
                                    </button>
                                </div>
                            </form>
                        </div>              
                    </div>
                </div>
            </div>
            <?php
            else:
                $pageOpt->redirectWithscript($pageOpt->previousPageUrl(), 'Invalid work order id!');
            endif;

            elseif($_GET['page'] == 'edit'):
                $id = isset($_GET['id']) ? $_GET['id'] : 0;
                if($accessoriesModel->checkDataExistence("SELECT nid FROM accessories_workordermaster WHERE nid = $id AND nissuestatus = 1 AND ndeletedstatus = 0 AND napprovedstatus = 0 AND (vcreateduser = '$userid' OR vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin')") == 'exist'):
                    $masterData = $accessoriesModel->getData("SELECT master.nid, master.vtodate, master.vponumber, master.vissue, master.vstatus, master.vattn, master.vform, master.vordernumberorfklnumber, master.vtype, master.vorderdetails, master.vextranotes, master.vdeliverydate, master.vcreateduser, master.vcreatedat, master.vgarmentsqty, master.vissue, master.ncheckedstatus, master.vcheckeduser, master.vlastupdateduser, master.vlastupdatedat, master.vpublisheduser, master.vpublishedat, master.napprovedstatus, master.vapproveduser, master.nacceptencestatus, master.vaccepteduser, master.nmrstatus, master.vmruser, master.nsupllierid FROM accessories_workordermaster master WHERE master.nid = $id AND master.nissuestatus = 1 AND master.ndeletedstatus = 0 AND master.napprovedstatus = 0 AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') ORDER BY master.nid ASC");
                    if(strtolower($masterData[0]['VTYPE']) == 'order number'):
                    $orderNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
                    $orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ksid, regexp_replace(        LISTAGG(orderinfo.vcountry, ',') WITHIN GROUP(ORDER BY orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS country, regexp_replace(LISTAGG(kmaster.vsizebreakdown, '##') WITHIN GROUP(ORDER BY orderinfo.norderid ASC), '([^##]+)(##\\1)+', '\\1') AS sizebreakdown, orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_vw_orderinfo orderinfo LEFT JOIN erp.mer_ks_master kmaster ON kmaster.nordercode = orderinfo.norderid WHERE orderinfo.vordernumber = '$orderNumber' GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname");
                    $fklNumber = $orderInfo[0]['KSID'];
                    elseif(strtolower($masterData[0]['VTYPE']) == 'fkl number'):
                    $fklNumber = $masterData[0]['VORDERNUMBERORFKLNUMBER'];
                    $orderInfo = $accessoriesModel->getData("SELECT REGEXP_REPLACE(LISTAGG(UPPER(vstylename), ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS stylename, REGEXP_REPLACE(LISTAGG(orderinfo.kimballno, ',') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS kimballno, LISTAGG(orderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC) AS ordernumber, regexp_replace(LISTAGG(orderinfo.vcountry, ',') WITHIN GROUP(ORDER BY orderinfo.vpart ASC), '([^,]+)(,\\1)+', '\\1') AS country, regexp_replace(LISTAGG(kmaster.vsizebreakdown, '##') WITHIN GROUP(ORDER BY orderinfo.norderid ASC), '([^##]+)(##\\1)+', '\\1') AS sizebreakdown, orderinfo.vname AS buyername, orderinfo.vseassonname, orderinfo.vdeptname FROM ERP.mer_ks_master kmaster LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.norderid = kmaster.nordercode WHERE kmaster.nks_id IN ($fklNumber) GROUP BY orderinfo.vname, orderinfo.vseassonname, orderinfo.vdeptname");
                    $orderNumber = $orderInfo[0]['ORDERNUMBER'];
                    else:
                        $orderInfo =  array();
                    endif;
                    $supplierid = $masterData[0]['NSUPLLIERID'];
                    $materialCheckData =  $accessoriesModel->getData("SELECT LISTAGG(ngoodsid, ',') WITHIN GROUP(ORDER BY nid ASC)AS goodsid FROM accessories_workorderitems WHERE nworkordermasterid = $id GROUP BY nworkordermasterid");
                    $goodsArr = explode(',', $materialCheckData[0]['GOODSID']);
                    // print_r($goodsArr);
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
                    <div data-role="panel" data-title-caption="Edit Work Order <strong>(W.O-<?=$masterData[0]['NID']?>)</strong>" data-title-icon="<span class='mif-pencil'></span>" class="workorder-form-panel" data-collapsible="false">
                        <div class="errors custom-alert">
                        </div>
                        <div class="success custom-alert">
                        </div>
                        <div class="p-1">
                            <form method="POST" action="" class="order-search">
                                <input type="hidden" name="csrf" class="csrf" value="<?=$db->csrfToken()?>">
                                <div class="row">
                                    <div class="cell-xl-6 cell-lg-12">
                                        <div class="row">
                                            <div class="cell-sm-6">
                                                <div class="form-group">
                                                    <label>Enter Order / FKL No.<span class="fg-red">*</span></label>
                                                    <div class="row" style="margin-left: 0px;margin-right: 0px;">
                                                        <div class="cell-5" style="padding: 0px;">
                                                            <select data-role="select" name="input-ordernumber-type" class="input-small input-ordernumber-type" data-filter="false">
                                                                <option value="<?=$masterData[0]["VTYPE"]?>"><?=$masterData[0]["VTYPE"]?></option>
                                                            </select>
                                                        </div>
                                                        <div class="cell-7" style="padding: 0px;">
                                                            <input type="text" data-role="input" class="input-small vordernumberorfklnumber" readonly data-cls-input="place-right" value="<?=$masterData[0]['VORDERNUMBERORFKLNUMBER']?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="cell-sm-6 cell-md-4 accessories-content-hidden content-visible lotappend">
                                                
                                            </div> -->
                                            <div class="cell-sm-6 ">
                                                <div class="form-group">
                                                    <label>Select Supplier<span class="fg-red">*</span></label>
                                                    <label style="position: absolute; right: 0; top: -2px;">
                                                        <a href="javscript:void(0)" class="image-button secondary secondary ribbed-teal bg-darkTeal-hover" style="height: 20px;">
                                                            <span class='mif-plus icon' style="height: 20px; line-height: 20px; font-size: .7rem; width: 20px;"></span>
                                                            <span class="caption">New</span>
                                                        </a>
                                                    </label>
                                                    <select data-role="select" name="suppliers" class="input-small suppliername" data-filter-placeholder="Search Suplliers...">
                                                        <?= $appsDependent->dropdownCommon('ACCESSORIES_SUPPLIERS', 'NID', 'VNAME', "$supplierid") ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>To Date<span class="fg-red">*</span></label>
                                                    <input type="text" name="todate"  class="todate input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" data-clear-button="true" data-input-format="%d-%m-%y" data-min-date="<?=$masterData[0]["VTODATE"]?>" value="<?=$masterData[0]["VTODATE"]?>">
                                                    <span class="invalid_feedback">To date is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>Attn.<span class="fg-red">*</span></label>
                                                    <input type="text" data-role="input"  class="input-small required-field attnetion-name" name="attn" placeholder="Enter Name" value="<?=$masterData[0]["VATTN"]?>">
                                                    <span class="invalid_feedback">Attn. name is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>From<span class="fg-red">*</span></label>
                                                    <input type="text" data-role="input"  class="input-small required-field form-name" name="form" placeholder="Enter Name" value="<?=$masterData[0]["VFORM"]?>">
                                                    <span class="invalid_feedback">From name is required.</span>
                                                </div>
                                            </div>
                                            <div class="cell-sm-6 cell-md-3">
                                                <div class="form-group">
                                                    <label>Select Delivery Date<span class="fg-red">*</span></label>
                                                    <input type="text" name="deliverydate"  class="deliverydate accessories-disable input-small required-field" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" data-clear-button="true" data-input-format="%d-%m-%y" data-min-date="<?=$masterData[0]["VDELIVERYDATE"]?>" value="<?=$masterData[0]["VDELIVERYDATE"]?>">
                                                    <span class="invalid_feedback">Delivery date is required.</span>
                                                </div>
                                            </div>
                                            <?php
                                            $groupData = $accessoriesModel->getData("SELECT nid, vname FROM ACCESSORIES_GROUP ORDER BY nid ASC");
                                            if(is_array($groupData)):
                                            foreach ($groupData as $groupName):
                                            ?>
                                            <div class="cell-sm-6 cell-md-4 ">
                                                <div class="form-group">
                                                    <label>Select <?=$groupName['VNAME']?></label>
                                                    <select data-role="select" name="selecteditem" multiple class="input-small accessories-disable itemsevent"  data-filter-placeholder="Search <?=$groupName['VNAME']?>">
                                                        <?php
                                                        $groupId = $groupName['NID'];
                                                        $subGroupData = $accessoriesModel->getData("SELECT nid, vname FROM ACCESSORIES_SUBGROUP WHERE ngroupid = $groupId ORDER BY nid ASC");
                                                        if(is_array($subGroupData)):
                                                        foreach ($subGroupData as $subGroupName):
                                                        ?>
                                                        <optgroup label="<?=$subGroupName['VNAME']?>" style="background-color: #e0f0f1;">
                                                            <?php
                                                            $subGroupData = $subGroupName['NID'];
                                                            $goods = $accessoriesModel->getData("SELECT goods.nid, goods.vname, goods.vparameters, unit.vnameshort FROM ACCESSORIES_GOODS goods LEFT JOIN ACCESSORIES_QUANTITYUNIT unit ON unit.nid = goods.nqtyunitid WHERE nsubgroupid = $subGroupData ORDER BY nsubgroupid ASC");
                                                            if(is_array($goods)):
                                                            foreach ($goods as $good):
                                                            ?>
                                                            <option value="<?=$good['NID']?>" <?=(in_array($good['NID'], $goodsArr) ? 'selected' : '')?> data-qtyunit="<?=$good['VNAMESHORT']?>" data-perameters="<?=$good['VPARAMETERS']?>"><?=$good['VNAME']?></option>
                                                            <?php
                                                            endforeach;
                                                            endif;
                                                            ?>
                                                        </optgroup>
                                                        <?php
                                                        endforeach;
                                                        endif;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php
                                            endforeach;
                                            else:
                                            echo $groupData;
                                            endif;
                                            ?>
                                            
                                        </div>
                                    </div>
                                    <div class="cell-xl-6 cell-lg-12">
                                        <?php
                                        $allCountry = array_unique(explode(',', str_replace(array("##", "+"),array(",", ","),$orderInfo[0]['COUNTRY'])));
                                        $allSize = implode(',', array_unique(explode(',', str_replace("##",",",$orderInfo[0]['SIZEBREAKDOWN']))));
                                        ?>
                                        <input type="hidden" class="allcountry" value="<?=implode(',', $allCountry);?>">
                                        <input type="hidden" class="allsize" value="<?=$allSize;?>">
                                        <input type="hidden" class="masterid" value="<?=$masterData[0]['NID']?>">
                                        <input type="hidden" class="createdat" value="<?=$masterData[0]['VCREATEDAT']?>">
                                        <input type="hidden" class="createduser" value="<?=$masterData[0]['VCREATEDUSER']?>">
                                        <input type="hidden" class="ponumber" value="<?=$masterData[0]['VPONUMBER']?>">
                                        <input type="hidden" class="issuenumber" value="<?=$masterData[0]['VISSUE']?>">
                                        <div class="cell-12" style="margin-top: 15px;">
                                            <table class="subcompact cell-border bg-white table searchordertable " style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                                                <tr>
                                                    <td style="padding: 0px;">
                                        
                                                        <span class="" style="<?=(strtolower($masterData[0]['VTYPE']) == 'fkl number' ? 'display: none;' : 'display: block;')?>"><span class="text-bold searchLabel">FKL NO.</span> <span id="fklno"><?=$fklNumber?></span></span><span class="" style="<?=(strtolower($masterData[0]['VTYPE']) == 'order number' ? 'display: none;' : 'display: block;')?>"><span class="text-bold searchLabel">ORDER NO.</span> <span id="ordernumber"><?=$orderNumber?></span></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Style Name</span> <span id="stylename"><?=$orderInfo[0]['STYLENAME']?></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Buyer Name</span> <span id="buyername"><?=$orderInfo[0]['BUYERNAME']?></span></td>
                                                    
                                                </tr>
                                                <tr>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Season</span> <span id="season"><?=$orderInfo[0]['VSEASSONNAME']?></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Dept.</span> <span id="dept"><?=$orderInfo[0]['VDEPTNAME']?></span></td>
                                                    <td style="padding: 0px;"><span class="text-bold searchLabel">Kimball</span> <span id="kimble"><?=$orderInfo[0]['KIMBALLNO']?></span></td>
                                                    <!-- <td style="padding-left: 0px;"><span class="text-bold searchLabel">Gmts Qty</span> <span id="qty"></span><input type="hidden" name="gmtqty" class="gmtqty required-field" value=''><span class="invalid_feedback">Garments qty is required.</span></td> -->
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="cell-12" style="margin-top: -10px;">
                                            <div class="form-group">
                                                <label>Order Details</label>
                                                <textarea data-role="textarea" name="order-details" class="order-detils"><?=$masterData[0]['VORDERDETAILS']?></textarea>
                                                <!-- <span class="invalid_feedback">Order details is required.</span> -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell-sm-12">
                                        <div class="ribbed-lightGray maingridheader" style="box-shadow: 0px 0px 2px #fff; margin-top: -10px; position: relative;">
                                            <h5 class="p-1" style="font-size: 1.1rem;">Details Entry Section</h5>
                                        </div>
                                    </div>
                                    <div class="gridappender cell-sm-12" style="margin-top: -15px;">
                                        <?php
                                        $itemdata = $accessoriesModel->getData("SELECT item.nid, goods.nid AS goodsid, goods.vname AS goodsname, goods.vparameters, item.ntotalgarmentsqty , item.ntotalgarmentsqtywithextra , item.ntotalqty , item.vaddition, item.vcolumnname , item.vconvertion , item.vgridtype , item.vordernumber , item.vpnnumber , item.vqtyunit , item.vsizename, setting.nexcelupload , setting.nconversionvalue , setting.vconvertiomntype , setting.nadditionvalue , setting.vadditiontype , setting.vcheckedoptions, setting.vdatafilltype FROM accessories_workorderitems item INNER JOIN accessories_goods goods ON goods.nid = item.ngoodsid LEFT JOIN accessories_workorderformsetting setting ON setting.nworkorderitemsid = item.nid WHERE item.nworkordermasterid = $id ORDER BY item.nid ASC");
                                        foreach ($itemdata as $key => $item):
                                            $getItemName = htmlspecialchars_decode($item['GOODSNAME']);
                                            $checkedOption = explode(',', htmlspecialchars_decode($item['VCHECKEDOPTIONS']));
                                            $getItemId = $item['GOODSID'];
                                            $itemId = $item['NID'];
                                            $parentId = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($getItemName)).$getItemId;
                                            if($key % 2 == 0):
                                                $background = "custom-table-bg";
                                                $backgroundCell = "bg-darkCyan fg-white";
                                            else:
                                                $background = "custom-table-bg1";
                                                $backgroundCell = "bg-darkGrayBlue fg-white";
                                            endif;
                                        ?>


                                        <div class="workorder-table-main pt-1 mb-2 <?=$background;?>" id="<?=$parentId?>" style="padding-bottom:1px;">
                                            
                                            <?php 
                                            /*
                                            ** Option Table Start.
                                            */
                                            ?>

                                            <table class="subcompact cell-border table searchordertable" style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                                                <tr>
                                                    <td style="background: #e0f0f1; width:132px; font-weight:bold;" class="text-left">Available Columns</td>
                                                    <?php
                                                    $parameters = explode(',', htmlspecialchars_decode($item['VPARAMETERS']));
                                                    array_push($parameters, 'Code No.');
                                                    array_push($parameters, 'Garments Qty');
                                                    array_push($parameters, 'Addition');
                                                    array_push($parameters, 'Converter');
                                                    array_push($parameters, 'Upload');
                                                    $cellData1 = "";
                                                    $cellData2 = "";
                                                    $cellData3 = "";
                                                    foreach ($parameters as $key => $options):
                                                        if(trim(strtolower($options)) == 'color wise qty' || trim(strtolower($options)) == 'color & size wise qty' || trim(strtolower($options)) == 'kimball/color/size wise qty' || trim(strtolower($options)) == 'size wise qty' || trim(strtolower($options)) == 'kimball & color wise qty'):
                                                            if(in_array(trim($options), $checkedOption)):
                                                                $cellData1 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border bd-white rounded fg-white parameters-disabled parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' checked data-cls-check='bd-white myCheck'>";
                                                            else:
                                                                $cellData1 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-2 mt-1 mb-1 p-1 border  bd-white rounded fg-white parameters-disabled parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' disabled data-cls-check='bd-white myCheck'>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'addition'):
                                                            if(in_array(trim($options), $checkedOption)):
                                                                $cellData3 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-crimson rounded bd-white addition-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."'b checked data-caption='".trim($options)."'>";
                                                            elseif((in_array('Color Wise Qty', $checkedOption) == true || in_array('Color & Size Wise Qty', $checkedOption) == true || in_array('Kimball/Color/Size Wise Qty', $checkedOption) == true || in_array('Size Wise Qty', $checkedOption) == true || in_array('Kimball & Color Wise Qty', $checkedOption) == true || in_array('Garments Qty', $checkedOption) == true) && $item['NEXCELUPLOAD'] != 1):
                                                                $cellData3 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-crimson rounded bd-white addition-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                            else:
                                                                $cellData3 .= "<input type='checkbox' data-role='checkbox' disabled value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-crimson rounded bd-white addition-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'converter'):
                                                            if(in_array(trim($options), $checkedOption)):
                                                                $cellData3 .= "<input type='checkbox' data-role='checkbox' checked value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-taupe rounded bd-white converter-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                            elseif((in_array('Color Wise Qty', $checkedOption) == true || in_array('Color & Size Wise Qty', $checkedOption) == true || in_array('Kimball/Color/Size Wise Qty', $checkedOption) == true || in_array('Size Wise Qty', $checkedOption) == true || in_array('Kimball & Color Wise Qty', $checkedOption) == true || in_array('Garments Qty', $checkedOption) == true) && $item['NEXCELUPLOAD'] != 1):
                                                                $cellData3 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-taupe rounded bd-white converter-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                            else:
                                                                $cellData3 .= "<input type='checkbox' data-role='checkbox' disabled value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-taupe rounded bd-white converter-enable fg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'upload'):
                                                            if($getItemName == 'Hang Tag' && (trim($orderInfo[0]['BUYERNAME']) == 'Primark' || trim($orderInfo[0]['BUYERNAME']) == 'Penneys' || trim($orderInfo[0]['BUYERNAME']) =='Primark & Penneys')):
                                                                    $cellData3 .= "<div class='row d-flex flex-justify-center'><button type='button' class='mt-1 image-button secondary excel-upload' style='height: 28px;' data-parentid='".$getItemId."' data-name='".$getItemName."' data-tableclass='".$parentId."'><span class='mif-upload icon' style='height: 28px; line-height: 24px; font-size: .9rem; width: 23px;'></span><span class='caption'>Upload (.xlsx or .csv)</span></button></div>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'grmnts color'):
                                                            if(in_array(trim($options), $checkedOption)):
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-darkCyan bd-white rounded fg-white parameters-disabled parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' checked data-cls-check='bd-white myCheck'>";
                                                            else:
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bg-darkCyan bd-white rounded fg-white parameters-disabled parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' disabled data-cls-check='bd-white myCheck'>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'grmnts color/kimball/lot'):
                                                            if(in_array(trim($options), $checkedOption)):
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white parameters-disabled bg-darkCyan parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' checked data-cls-check='bd-white myCheck'>";
                                                            else:
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border  bd-white rounded fg-white parameters-disabled bg-darkCyan parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' disabled data-cls-check='bd-white myCheck'>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'size name'):
                                                            if(in_array(trim($options), $checkedOption)):
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 bg-darkCyan p-1 border  bd-white rounded fg-white parameters-disabled parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' checked data-cls-check='bd-white myCheck'>";
                                                            else:
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 bg-darkCyan p-1 border  bd-white rounded fg-white parameters-disabled parameters-groupcommon parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' disabled data-cls-check='bd-white myCheck'>";
                                                            endif;
                                                        elseif(trim(strtolower($options)) == 'garments qty'):
                                                            if(in_array(trim($options), $checkedOption)):
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 bg-darkCyan mb-1 p-1 border  bd-white rounded fg-white parameters-disabled parameters parameters-groupcommon".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' checked data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                            elseif(in_array('Color Wise Qty', $checkedOption) == false && in_array('Color & Size Wise Qty', $checkedOption) == false && in_array('Kimball/Color/Size Wise Qty', $checkedOption) == false && in_array('Size Wise Qty', $checkedOption) == false && in_array('Kimball & Color Wise Qty', $checkedOption) == false):
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 bg-darkCyan mb-1 p-1 border  bd-white rounded fg-white parameters-disabled parameters parameters-groupcommon".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                            else:
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 bg-darkCyan mb-1 p-1 border  bd-white rounded fg-white parameters-disabled parameters parameters-groupcommon ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' disabled data-caption='".trim($options)."' data-cls-check='bd-white myCheck'>";
                                                            endif;
                                                        else:
                                                            if($item['NEXCELUPLOAD'] == 1):
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' disabled value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bd-white rounded bg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                            elseif(in_array(trim($options), $checkedOption)):
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bd-white rounded bg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' checked data-caption='".trim($options)."'>";
                                                            else:
                                                                $cellData2 .= "<input type='checkbox' data-role='checkbox' value='".trim($options)."' data-parentid='".$getItemId."' data-cls-checkbox='mr-1 ml-1 mt-1 mb-1 p-1 border bd-white rounded bg-white parameters ".preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower(trim($options)))."' data-caption='".trim($options)."'>";
                                                            endif;
                                                        endif;
                                                    endforeach;

                                                    ?>
                                                    <td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Knitting Shit Data</div><div><?=$cellData1?></div>
                                                    </td>
                                                    <td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Customize Options</div><div><?=$cellData2?></div>
                                                    </td>
                                                    <td class='text-left' style='background: #e0f0f1; vertical-align: top !important;'><div class='columheader text-center'>Extra Operation</div><div style='width: 221px; margin: 0 auto;'><?=$cellData3?></div>
                                                    </td>
                                                </tr>
                                            </table>


                                            <?php 
                                            /*
                                            ** Option Table End.
                                            */
                                            ?>


                                            <?php
                                            $customColumnSelector = array();
                                            $groupcustomColumnSelector = array();
                                            $customColumn = explode(',', $item['VCOLUMNNAME']);
                                            foreach ($customColumn as $columnKey => $column):
                                                array_push($customColumnSelector, 'customcolumn.vcolumn'.($columnKey+1).' AS '.rtrim(preg_replace('/[^A-Za-z0-9]/','', strtolower($column)), '.'));
                                                array_push($groupcustomColumnSelector, 'customcolumn.vcolumn'.($columnKey+1));
                                            endforeach;
                                            //print_r($customColumnSelector);
                                            $customColumnName = count($customColumnSelector) > 0 ? ','.implode(',', $customColumnSelector) : '';
                                            $groupcustomColumnName = count($groupcustomColumnSelector) > 0 ? ','.implode(',', $groupcustomColumnSelector) : '';
                                            $gridSql = "SELECT rowdata.nid, rowdata.nrowtotalqty, rowdata.nrowgarmentsqty, rowdata.nrowgarmentsqtywithextra, rowdata.nconverterqty, rowdata.vremarks $customColumnName ";
                                            if($item['VGRIDTYPE'] == 'colornsize'):
                                                $gridSql .= ",LISTAGG(sizewiseqty.nrequiredqty || '##' || sizewiseqty.vsizename, ',') WITHIN GROUP (ORDER BY sizewiseqty.nid ASC) AS QTY";
                                            endif;
                                                $gridSql .= " FROM accessories_workorderitemdata rowdata LEFT JOIN accessories_customcolumnvalue customcolumn ON customcolumn.ngriditemdataid = rowdata.nid";
                                            if($item['VGRIDTYPE'] == 'colornsize'):
                                                $gridSql .= " LEFT JOIN accessories_workordersizeqty sizewiseqty ON sizewiseqty.nworkorderitemsdataid = rowdata.nid";
                                            endif;
                                            $gridSql .= " WHERE rowdata.nworkorderitemsid = $itemId GROUP BY rowdata.nid, rowdata.nrowtotalqty, rowdata.nrowgarmentsqty, rowdata.nrowgarmentsqtywithextra, rowdata.nconverterqty, rowdata.vremarks $groupcustomColumnName ORDER BY rowdata.nid ASC";
                                            //echo $gridSql;
                                            $gridData = $accessoriesModel->getData($gridSql);
                                            $tempColorArray = array();
                                            $tempSizeArray = array();
                                            foreach ($gridData as $tkey => $tvalue):
                                                if(isset($tvalue['COLORNAME'])):
                                                    $colorEx = explode(',', $tvalue['COLORNAME']);
                                                    foreach ($colorEx as $cvalue):
                                                        array_push($tempColorArray, trim($cvalue));
                                                    endforeach;
                                                elseif(isset($tvalue['SIZENAME'])):
                                                    $sizeEx = explode(',', $tvalue['SIZENAME']);
                                                    foreach ($sizeEx as $svalue):
                                                        array_push($tempSizeArray, trim($svalue));
                                                    endforeach;
                                                endif;
                                            endforeach;
                                            $rowDisable = 0;

                                            /*
                                            ** Miangrid Table Start.
                                            */
                                            
                                            ?>
                                            <table class='table subcompact cell-border table maingrid bg-white <?=$item["NEXCELUPLOAD"] == 0 ? $item["VDATAFILLTYPE"] : "excel-grid";?> itemtable <?=$parentId?>' data-uniqueid='<?=$getItemId?>' data-itemid='<?=$item["NID"]?>' onkeydown='enableCellNavigation($(this))'>

                                                <?php 
                                                /*
                                                ** Main Grid Column Header Start.
                                                */
                                                ?>
                                                <tr>
                                                    <th class='items-header'>Name of Item</th>
                                                    <?php
                                                    foreach ($checkedOption as $ckey => $columnCombine):
                                                        $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                        $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                        if($columnCombine == 'Color Wise Qty'):
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Color Name'>Color Name</th>
                                                            <th class='<?=$columntrid?>-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>
                                                        <?php
                                                        elseif($columnCombine == 'Size Wise Qty'):
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Size Name'>Size Name</th>
                                                            <th class='<?=$columntrid?>-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>
                                                           
                                                        <?php
                                                        elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            $sizeName =  explode(',', $item['VSIZENAME']);
                                                            $csize = '<div class="row no-gap">';
                                                            foreach ($sizeName as $skey => $val):
                                                                $csize .= '<div class="cell size-'.$val.'-header bg-gray border bd-light csize-header" style="min-width: 60px;">'.$val.'</div>';
                                                            endforeach;
                                                            $csize .= '</div>';
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Color Name'>Color Name</th>
                                                            <th class='<?=$columntrid?>-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div><?=$csize;?></th>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            $sizeName =  explode(',', $item['VSIZENAME']);
                                                            $csize = '<div class="row no-gap">';
                                                            foreach ($sizeName as $skey => $val):
                                                                $csize .= '<div class="cell size-'.$val.'-header bg-gray border bd-light csize-header" style="min-width: 60px;">'.$val.'</div>';
                                                            endforeach;
                                                            $csize .= '</div>';
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Color Name'>Color Name</th>
                                                            <?php
                                                            if($item["NEXCELUPLOAD"] == 1 && in_array('Country', $customColumn)):
                                                            ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Country'>Country</th>
                                                            <?php
                                                            endif;
                                                            ?>
                                                            <th class='<?=$columntrid?>-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove("<?=$parentId?>", "kimballcelll")'>Kimball No.</th>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Lot No.'>Lot No.</th>
                                                            <th class='<?=$columntrid?>-header colorsizeqty-header' data-columnname=''><div>Size Wise Qty</div><?=$csize;?></th>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Color Name'>Color Name</th>
                                                            <th class='<?=$columntrid?>-header kimballcelll-header' data-columnname='Kimball No.' ondblclick='columnRemove("<?=$parentId?>", "kimballcelll")'>Kimball No.</th>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Lot No.'>Lot No.</th>
                                                            <th class='<?=$columntrid?>-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color'):
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Color Name'>Color Name</th>
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Color Name'>Color Name</th>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Kimball No.'>Kimball No.</th>
                                                            <th class='<?=$columntrid?>-header' data-columnname='Lot No.'>Lot No.</th>");
                                                        <?php
                                                        elseif($columnCombine == 'Garments Qty'):
                                                        ?>
                                                        <th class='<?=$columntrid?>-qty-header' data-columnname='Garments Qty.'>Garments Qty.</th>
                                                        <?php
                                                        elseif($columnCombine == 'Addition'):
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='<?=$item["VADDITION"]?>'><?=$item["VADDITION"]?></th>
                                                        <?php
                                                        elseif($columnCombine == 'Converter'):
                                                        ?>
                                                            <th class='<?=$columntrid?>-header' data-columnname='<?=$item["VCONVERTION"]?>'><?=$item["VCONVERTION"]?></th>
                                                        <?php
                                                        else:
                                                            if($columnCombine != 'Symbol'):
                                                        ?>
                                                                <th class='<?=$columntrid?>-header' data-columnname='<?=$columnCombine;?>'><?=$columnCombine;?></th>
                                                    <?php
                                                            endif;
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                    <th class='totalqty-header'>W.O. Required Qty.</th>
                                                    <th class='remarks-header'>Remarks</th>
                                                    <?php
                                                    if(in_array('Symbol', $checkedOption)): 
                                                        $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower('Symbol')).$getItemId;
                                                    ?>
                                                        <th class='<?=$columntrid?>-header' data-columnname='Symbol'>Symbol</th>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </tr>


                                                <?php 
                                                /*
                                                ** Main Grid Column Header End.





                                                ** Fixed Data Row Start. 
                                                */
                                                $index = mt_rand();
                                                if($item['VDATAFILLTYPE'] == 'fixed-data'):
                                                ?>
                                                <tr class="data-row-hidden appended-row" style="display: none;">
                                                    <?php
                                                    foreach ($checkedOption as $ckey => $columnCombine):
                                                        $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                        $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                        if($columnCombine == 'Color Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Size Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                           
                                                        <?php
                                                        elseif($columnCombine == 'Color & Size Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td><td class='<?=$columntrid?>'></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?> kimballcelll'></td>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?>'></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?> kimballcelll'></td>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                        <!-- <?php
                                                        //elseif($columnCombine == 'Grmnts Color'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                        <?php
                                                       // elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                            <td class='<?=$columntrid?> text-center'></td> -->
                                                        <?php
                                                        elseif($columnCombine == 'Addition'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='text' readonly class='input-small text-center row-garmentsqtyextra-input' name='garmentsextraqty' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualExtraGarmentsQty("<?=$parentId;?>", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'>
                                                        <?php
                                                        elseif($columnCombine == 'Converter'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='text'  class='input-small text-center row-convertion-input' name='convertioninput' oninput='numberValidate($(this), $(this).val()); convertionCalculate("<?=$parentId;?>", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='1'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'PN No.'):
                                                         //Silence is better than being right. Dont remove this condition.
                                                        elseif($columnCombine == 'Order No.'):
                                                          //Silence is better than being right. Dont remove this condition.         
                                                        elseif($columnCombine == 'Symbol'):
                                                          //Silence is better than being right. Dont remove this condition.         
                                                        elseif($columnCombine == 'Code No.'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'>
                                                                <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>
                                                            </td>
                                                        <?php
                                                        else:
                                                        ?>
                                                            <td class='<?=$columntrid?>'>
                                                                <textarea class='custom-column-value'  style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>
                                                            </td>
                                                    <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                    <td class='totalqty'>
                                                        <div class='row no-gap'>
                                                            <div class='cell'>
                                                                <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'>
                                                            </div>
                                                            <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='remarks' style='position:relative;'>

                                                        <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea>
                                                        <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                        </a>
                                                    </td>                                                 
                                                </tr>
                                                <?php
                                                
                                                foreach ($gridData as $dkey => $data):
                                                    $indexCount = $index++;
                                                    $sizeData = array();
                                                    if($item['VGRIDTYPE'] == 'colornsize'):
                                                        $sizeNameQty = explode(',', $data['QTY']);
                                                        if(count($sizeNameQty) > 0):
                                                            foreach ($sizeNameQty as $skey => $sdata):
                                                                $sizeArr = explode('##', $sdata);
                                                                $sizeData[$sizeArr[1]] = $sizeArr[0];
                                                            endforeach;
                                                        endif;
                                                    endif;
                                                    //print_r($sizeData);
                                                    if($dkey == 0):
                                                ?>
                                                    <tr class="data-row">
                                                        <td class='text-center text-bold maingrid-rowspan items-name items-<?=$getItemId?>' data-itemid='<?=$getItemId?>' rowspan='<?=count($gridData)?>'>
                                                            <?=$getItemName?>
                                                        </td>
                                                    <?php
                                                        foreach ($checkedOption as $ckey => $columnCombine):
                                                            $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                            $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                            if($columnCombine == 'Country'):
                                                                $explodeCountry = explode(',', $data['COUNTRY']);
                                                                $dataForCountry = '';
                                                                $dataForCountry .= "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
                                                                
                                                                foreach ($allCountry as $ckey => $country):
                                                                    if(in_array(trim($country), $explodeCountry)):
                                                                        $dataForCountry .= "<option value='".$country."' selected>".$country."</option>";
                                                                    else:
                                                                        $dataForCountry .= "<option value='".$country."'>".$country."</option>";
                                                                    endif;
                                                                endforeach;
                                                                $dataForCountry .= "</select>"; ?>
                                                            <td class='<?=$columntrid?>'><?=$dataForCountry?></td>
                                                            <?php
                                                            elseif($columnCombine == 'Color Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                    <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> size-celll-fixed'><?=$data['SIZENAME']?></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                    <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                </td>
                                                               
                                                            <?php
                                                            elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <?php
                                                                $csize = '<div class="row no-gap">';
                                                                $sizeName =  explode(',', $item['VSIZENAME']);
                                                                foreach ($sizeName as $skey => $sizeval):
                                                                    $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" readonly value="'.$sizeData[$sizeval].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                endforeach;
                                                                $csize .= '</div>';
                                                                ?>
                                                                <td class='<?=$columntrid?> colorsizeqty'><?=$csize?></td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <td class='<?=$columntrid?> kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                                <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                <?php
                                                                $csize = '<div class="row no-gap">';
                                                                $sizeName =  explode(',', $item['VSIZENAME']);
                                                                foreach ($sizeName as $skey => $sizeval):
                                                                    $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));" readonly value="'.$sizeData[$sizeval].'" style="width: 100%;"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                endforeach;
                                                                $csize .= '</div>';
                                                                ?>

                                                                <td class='<?=$columntrid?> colorsizeqty'>
                                                                    <?=$csize?>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <td class='<?=$columntrid?> kimballcelll kimball-cell'><?=$data['KIMBALLNO']?></td>
                                                                <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                    <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                </td>
                                                            <!-- <?php
                                                            //elseif($columnCombine == 'Grmnts Color'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed' style='position:relative;'></td>
                                                            <?php
                                                            //elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                                <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                                <td class='<?=$columntrid?> text-center'></td> -->
                                                            <?php
                                                            elseif($columnCombine == 'Addition'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type="text" readonly="" class="input-small text-center row-garmentsqtyextra-input" name="garmentsextraqty" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualExtraGarmentsQty('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NROWGARMENTSQTYWITHEXTRA']?>" data-additionval="<?=$item['NADDITIONVALUE']?>" data-additiontype="<?=$item['VADDITIONTYPE']?>">
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Converter'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type="text" class="data-copier input-small text-center row-convertion-input" name="convertioninput" oninput="numberValidate($(this), $(this).val()); convertionCalculate('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NCONVERTERQTY']?>" <?=!empty($item['NCONVERSIONVALUE']) ? 'data-convertionval="'.$item['NCONVERSIONVALUE'].'"' : '';?>  <?=!empty($item['VCONVERTIOMNTYPE']) ? 'data-calinputtype="'.$item['VCONVERTIOMNTYPE'].'"' : '';?>>
                                                                    <a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopyWithCalculation($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'PN No.'): ?>
                                                                <td class="pn-no-cell <?=$columntrid?> text-center" rowspan="<?=count($gridData);?>"><input type="text" class="input-small pn-no-input text-center" name="pnnumber" style="max-width:120px; margin: 0 auto;" value="<?=$item['VPNNUMBER']?>"></td>
                                                            <?php
                                                            elseif($columnCombine == 'Order No.'):
                                                            ?>
                                                            <td class="order-no-cell <?=$columntrid?>" rowspan="<?=count($gridData);?>"><input type="text" class="input-small order-no-input" name="ordernumber" style="max-width:120px; margin: 0 auto;" value="<?=$item['VORDERNUMBER']?>"></td>                                                                    
                                                            <?php       
                                                            elseif($columnCombine == 'Code No.'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'>
                                                                    <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; margin: 0 auto; height: 40px;'><?=$data["CODENO"];?></textarea>
                                                                </td>
                                                            <?php
                                                            else:
                                                                if($columnCombine != 'Symbol'):
                                                                    $columnname = preg_replace('/[^A-Za-z0-9]/','', strtoupper($columnCombine));

                                                            ?>
                                                                    <td class='<?=$columntrid?>' style="position: relative;">
                                                                        <textarea class="custom-column-value data-copier" style="max-width:100%; margin: 0 auto; height: 40px;"><?=$data["$columnname"];?></textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                    </td>
                                                        <?php
                                                                endif;
                                                            endif;

                                                        endforeach;
                                                    ?>
                                                         <td class='totalqty'>
                                                                <div class='row no-gap'>
                                                                    <div class='cell'>
                                                                        <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$data['NROWTOTALQTY']?>'>
                                                                    </div>
                                                                    <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class='remarks' style='position:relative;'>
                                                                <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'><?=$data['VREMARKS']?></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                                </a>
                                                            </td>
                                                            <?php
                                                            if(in_array('Symbol', $checkedOption)):
                                                                $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower('Symbol')).$getItemId;
                                                                $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                                $count = $accessoriesModel->getData("SELECT nid FROM accessories_images WHERE nworkorderitemid = $itemId");
                                                                $counter = is_array($count) ? 4 - count($count) : 4;
                                                            ?>
                                                                <td class="symbol-cell <?=$columntrid?> text-center" rowspan="<?=count($gridData);?>">
                                                                    <input type="hidden" class="symbol-count" value="<?=$counter?>">
                                                                    <div class="<?=$parentId?>-symbol">
                                                                        <input type='file' name='attachment[]' multiple data-role='file' data-mode='drop' onchange='checkimage($(this), <?=$counter?>); checkvalidformat($(this));' class='symbol-input'><small>Allowed file format: jpg/png/jpeg/gif, Max allowed : 4 images.</small>
                                                                        <br>
                                                                        <small class="fg-red"><?=is_array($count) ? count($count) : 0; ?> symbol(s) added.</small>
                                                                    </div>
                                                                
                                                            </td>
                                                            <?php
                                                            endif;
                                                            ?>
                                                    </tr>
                                                    <?php
                                                    else:
                                                    ?>
                                                        <tr class="data-row appended-row">
                                                        <?php
                                                            foreach ($checkedOption as $ckey => $columnCombine):
                                                                $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                                $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                                if($columnCombine == 'Country'):
                                                                    $explodeCountry = explode(',', $data['COUNTRY']);
                                                                    $dataForCountry = '';
                                                                    $dataForCountry .= "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
                                                                    
                                                                    foreach ($allCountry as $ckey => $country):
                                                                        if(in_array(trim($country), $explodeCountry)):
                                                                            $dataForCountry .= "<option value='".$country."' selected>".$country."</option>";
                                                                        else:
                                                                            $dataForCountry .= "<option value='".$country."'>".$country."</option>";
                                                                        endif;
                                                                    endforeach;
                                                                    $dataForCountry .= "</select>"; ?>
                                                                    <td class='<?=$columntrid?>'><?=$dataForCountry?></td>
                                                            <?php
                                                                elseif($columnCombine == 'Color Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <td class='<?=$columntrid?>-qty'>
                                                                        <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                        <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'Size Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> size-celll-fixed'><?=$data['SIZENAME']?></td>
                                                                    <td class='<?=$columntrid?>-qty'>
                                                                        <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                        <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                    </td>
                                                                   
                                                                <?php
                                                                elseif($columnCombine == 'Color & Size Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <?php
                                                                    $csize = '<div class="row no-gap">';
                                                                    $sizeName =  explode(',', $item['VSIZENAME']);
                                                                    foreach ($sizeName as $skey => $sizeval):
                                                                        $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" readonly value="'.$sizeData[$sizeval].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                    endforeach;
                                                                    $csize .= '</div>';
                                                                    ?>
                                                                    <td class='<?=$columntrid?> colorsizeqty'><?=$csize?></td>
                                                                <?php
                                                                elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <td class='<?=$columntrid?> kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                                    <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                    <?php
                                                                    $csize = '<div class="row no-gap">';
                                                                    $sizeName =  explode(',', $item['VSIZENAME']);
                                                                    foreach ($sizeName as $skey => $sizeval):
                                                                        $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" readonly value="'.$sizeData[$sizeval].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                    endforeach;
                                                                    $csize .= '</div>';
                                                                    ?>

                                                                    <td class='<?=$columntrid?> colorsizeqty'>
                                                                        <?=$csize?>
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <td class='<?=$columntrid?> kimballcelll kimball-cell'><?=$data['KIMBALLNO']?></td>
                                                                    <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                    <td class='<?=$columntrid?>-qty'>
                                                                        <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                        <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                    </td>
                                                               
                                                                <?php
                                                                elseif($columnCombine == 'Addition'):
                                                                ?>
                                                                    <td class='<?=$columntrid?>' style='position:relative;'>
                                                                        <input type="text" readonly="" class="input-small text-center row-garmentsqtyextra-input" name="garmentsextraqty" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualExtraGarmentsQty('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NROWGARMENTSQTYWITHEXTRA']?>" data-additionval="<?=$item['NADDITIONVALUE']?>" data-additiontype="<?=$item['VADDITIONTYPE']?>">
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'Converter'):
                                                                ?>
                                                                    <td class='<?=$columntrid?>' style='position:relative;'>
                                                                        <input type="text" class="data-copier input-small text-center row-convertion-input" name="convertioninput" oninput="numberValidate($(this), $(this).val()); convertionCalculate('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NCONVERTERQTY']?>" <?=(!empty($item['NCONVERSIONVALUE']) ? 'data-convertionval="'.$item['NCONVERSIONVALUE'].'"' : '');?>  <?=!empty($item['VCONVERTIOMNTYPE']) ? 'data-calinputtype="'.$item['VCONVERTIOMNTYPE'].'"' : '';?>>
                                                                        <a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopyWithCalculation($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'PN No.'):
                                                                    
                                                                elseif($columnCombine == 'Order No.'):

                                                                elseif($columnCombine == 'Symbol'):
                                                                     
                                                                elseif($columnCombine == 'Code No.'):
                                                                ?>
                                                                    <td class='<?=$columntrid?>'>
                                                                        <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; margin: 0 auto; height: 40px;'><?=$data["CODENO"];?></textarea>
                                                                    </td>
                                                                <?php
                                                                else:
                                                                    $columnname = preg_replace('/[^A-Za-z0-9]/','', strtoupper($columnCombine));
                                                                ?>
                                                                    <td class='<?=$columntrid?>' style="position: relative;">
                                                                        <textarea class="custom-column-value data-copier" style="max-width:100%; margin: 0 auto; height: 40px;"><?=$data["$columnname"];?></textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                    </td>
                                                            <?php
                                                                endif;
                                                            ?>
                                                               
                                                            <?php
                                                            endforeach;
                                                        ?>
                                                            <td class='totalqty'>
                                                                <div class='row no-gap'>
                                                                    <div class='cell'>
                                                                        <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$data['NROWTOTALQTY']?>'>
                                                                    </div>
                                                                    <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class='remarks' style='position:relative; overflow: hidden;'>
                                                                <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'><?=$data['VREMARKS']?></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                                </a>
                                                                <div class="row-removeBtn ribbed-darkRed" onclick="rowRemover($(this), '<?=$parentId?>');"><span class="mif-cross"></span></div>
                                                            </td>                                                           
                                                        </tr>
                                                    <?php
                                                    endif;
                                                    ?>

                                            <?php
                                                endforeach;
                                            ?>
                                                <tr style="background: #e0f0f1;">
                                                        <td style="font-weight:bold; position:relative;" class="text-right grandQtyCell"><p style="width: 155px;margin: 0px;">Quantity Grand Total</p></td>
                                                    <?php
                                                        foreach ($checkedOption as $ckey => $columnCombine):
                                                            $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                            $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                            if($columnCombine == 'Color Wise Qty' || $columnCombine == 'Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type="text" class="input-small text-center garmentsgrandtotal" name="garmentsgrandtotal" style="min-width: 80px; max-width:120px; margin: 0 auto;" value="<?=$item["NTOTALGARMENTSQTY"]?>" readonly="">
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>'>
                                                            </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?> kimballcelll'></td>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>'>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?> kimballcelll'></td>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                   <input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>' readonly>
                                                                </td>
                                                            <!-- <?php
                                                            //elseif($columnCombine == 'Grmnts Color'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed' style='position:relative;'></td>
                                                            <?php
                                                            //elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                                <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                                <td class='<?=$columntrid?> text-center'></td> -->
                                                            <?php
                                                            elseif($columnCombine == 'Addition'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type='text' class='input-small text-center garmentsextragrandtotal' name='garmentsextragrandtotal' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTYWITHEXTRA"]?>' readonly>
                                                                </td>
                                        
                                                            <?php
                                                            else:
                                                                if($columnCombine != 'Symbol'):
                                                            ?> 
                                                                    <td class='<?=$columntrid?>' style="position: relative;"></td>
                                                        <?php   endif;
                                                            endif;

                                                        endforeach;
                                                    ?>
                                                        <td class="grandtotalqty">
                                                            <div class="row no-gap">
                                                                <div class="cell">
                                                                    <input type="text" readonly="" class="input-small text-center grand-totalqty-input" name="grandqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$item["NTOTALQTY"]?>">
                                                                </div>
                                                                <div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;">
                                                                    <span class="text-bold grandunit"><?=$item['VQTYUNIT']?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class='remarks' style='position:relative;'></td>
                                                        <?php
                                                            if(in_array('Symbol', $checkedOption)):
                                                                $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower('Symbol')).$getItemId;
                                                                $count = $accessoriesModel->getData("SELECT nid FROM accessories_images WHERE nworkorderitemid = $itemId");
                                                                $counter = is_array($count) ? 4 - count($count) : 4;
                                                            ?>
                                                                <td class="<?=$columntrid?> text-center"></td>
                                                            <?php
                                                            endif;
                                                            ?>
                                                    </tr>
                                                <?php
                                                elseif($item['VDATAFILLTYPE'] == 'manual-data'):
                                                $indexCount = $index++;
                                                ?>
                                                    <tr class="data-row-hidden appended-row" style="display: none;">
                                                    <?php
                                                    foreach ($checkedOption as $ckey => $columnCombine):
                                                        $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                        $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                        if($columnCombine == 'Country'):
                                                            $dataForCountry = '';
                                                            $dataForCountry .= "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
                                                            foreach ($allCountry as $ckey => $country):
                                                                $dataForCountry .= "<option value='".$country."'>".$country."</option>";
                                                            endforeach;
                                                            $dataForCountry .= "</select>"; ?>
                                                            <td class='<?=$columntrid?>'><?=$dataForCountry?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Color Wise Qty'):
                                                            $response = $workorderOpt->colorWiseData($fklNumber);
                                                            $rowDisable = count($response['color']);
                                                            
                                                            $colorQty = '';
                                                            $colorQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorQty .= '<div class="row no-gap">';
                                                            // $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            foreach ($response['color'] as $colorkey => $colorVal):
                                                                if(in_array(trim($colorVal), $tempColorArray)):
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                else:
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'"><input type="checkbox"  id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $colorQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorQty .= ' <div class="cell">';
                                                            $colorQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorQty .= '<span class="caption">Continue</span>';
                                                            $colorQty .= '</a></div></div></div></div>';
                                                            ?>
          
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorQty;?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Size Wise Qty'):
                                                            $response = $workorderOpt->sizeWiseQty($fklNumber);
                                                            $rowDisable = count($response['sizeQty']);
                                                           
                                                            $sizeQty = '';
                                                            $sizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $sizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $sizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            foreach ($response['sizeQty'] as $sizekey => $sizevalue):
                                                                $count = $counter++; 
                                                                if(in_array(trim($sizekey), $tempSizeArray)):
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                else:
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'"><input type="checkbox" id="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $sizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $sizeQty .= ' <div class="cell">';
                                                            $sizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $sizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $sizeQty .= '<span class="caption">Continue</span>';
                                                            $sizeQty .= '</a></div></div></div></div>';          
                                                        ?>
                                                            <td class='<?=$columntrid?> size-celll-manual' style='position:relative;'><?=$sizeQty?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?>-qty'><input type='hidden' class='addition-qty-hidden' value='0'><input type='text' class='input-small text-center garments-qty-input' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'></td>
                                                           
                                                        <?php
                                                        elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            $response = $workorderOpt->colorAndSizeWiseData($fklNumber);

                                                            $rowDisable = count($response['colorsizeQty']);

                                                            
                                                            $colorSizeQty = '';
                                                            $colorSizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorSizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorSizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            foreach ($response['colorsizeQty'] as $cskey => $csvalue):
                                                                $count = $counter++;
                                                                if(in_array(trim($cskey), $tempColorArray)):
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="colorsname'.$count.'" disabled class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                else:
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="colorsname'.$count.'" class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $colorSizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorSizeQty .= ' <div class="cell">';
                                                            $colorSizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorSizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorSizeQty .= '<span class="caption">Continue</span>';
                                                            $colorSizeQty .= '</a></div></div></div></div>';
                                                            $csize = '<div class="row no-gap">';
                                                            foreach ($response['sizename'] as $sizeValue):
                                                                $csize .= '<div class="cell size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input colorsizewiseqtyinput" data-sizename="'.$sizeValue.'" readonly value="0" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input" value="0"></div>';
                                                            endforeach;
                                                            $csize .= '</div>';
                                                            ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorSizeQty?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> colorsizeqty'><?=$csize?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            $response = $workorderOpt->kimballColorSizeWiseData($fklNumber);
                                                          
                                                            $rowDisable = count($response['colorsizeQty']);
                                                           
                                                            $kColorSizeQty = '';
                                                            $kColorSizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kColorSizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
                                                            $kColorSizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            foreach ($response['colorsizeQty'] as $cskey => $csvalue):
                                                                $count = $counter++;
                                                                if(in_array(trim(str_replace('*lot*'.$response['lot'][$count], '', $cskey)), $tempColorArray)):
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '', trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                else:
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'"><input type="checkbox" id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '', trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $kColorSizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kColorSizeQty .= ' <div class="cell">';
                                                            $kColorSizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kColorSizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kColorSizeQty .= '<span class="caption">Continue</span>';
                                                            $kColorSizeQty .= '</a></div></div></div></div>';
                                                            $ksize = '<div class="row no-gap">';
                                                            foreach ($response['sizename'] as $sizeValue):
                                                                $ksize .= '<div class="cell size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input colorsizewiseqtyinput" data-sizename="'.$sizeValue.'" readonly value="0" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input" value="0"></div>';
                                                            endforeach;
                                                            $ksize .= '</div>';
                                                            ?>
                                                             
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kColorSizeQty?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'></td>
                                                            <td class='<?=$columntrid?> colorsizeqty'><?=$ksize?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            $response = $workorderOpt->kimballColorWiseData($fklNumber);
                                                            
                                                            $rowDisable = count($response['color']);
                                                            $kColorQty = '';
                                                            $kColorQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kColorQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
                                                            $kColorQty .= '<div class="row no-gap">';
                                                            foreach ($response['color'] as $kckey => $kcvalue):
                                                                
                                                                if(in_array(trim($kcvalue), $tempColorArray)):
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'" style="background:#aeaeae; color: #808080;"><input type="checkbox"  disabled id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                else:
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'"><input type="checkbox" id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                endif;
                                                            
                                                            endforeach;
                                                            $kColorQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kColorQty .= ' <div class="cell">';
                                                            $kColorQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kColorQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kColorQty .= '<span class="caption">Continue</span>';
                                                            $kColorQty .= '</a></div></div></div></div>';
                                                            ?>
      
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kColorQty;?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color'):
                                                            $response = $workorderOpt->colorWiseData($fklNumber);
                                                            $rowDisable = count($response['color']);

                                                            $colorG = '';
                                                            $colorG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorG .= '<div class="row no-gap">';
                                                            foreach ($response['color'] as $cgkey => $cgvalue):
                                                                $colorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$getItemId.'" name="colornameg'.$cgkey.'" class="colornameg" value="'.$cgvalue.'" onchange="colorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$cgvalue.'</div>';             
                                                            endforeach;
                                                          
                                                            $colorG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorG .= ' <div class="cell">';
                                                            $colorG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorG .= '<span class="caption">Continue</span>';
                                                            $colorG .= '</a></div></div></div></div>';
                                                        ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorG?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                        
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                            $response = $workorderOpt->kimballColorWiseData($fklNumber);
                                                            $kcolorG = '';
                                                            $kcolorG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kcolorG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $kcolorG .= '<div class="row no-gap">';
                                                                foreach ($response['color'] as $kckey => $kcvalue):
                                                                    $kcolorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolornameg'.$kckey.'" class="kcolornameg" value="'.$kcvalue.'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                endforeach;
                                                            $kcolorG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kcolorG .= ' <div class="cell">';
                                                            $kcolorG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kcolorG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kcolorG .= '<span class="caption">Continue</span>';
                                                            $kcolorG .= '</a></div></div></div></div>';
                                                        ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kcolorG?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'></td>");
                                                        <?php
                                                        elseif($columnCombine == 'Size Name'):
                                                        
                                                            $sizeG = '';
                                                            $sizeG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $sizeG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Size Name</p>';
                                                            $sizeG .= '<div class="row no-gap">';
                                                            $sizeName = array_unique(explode(',', str_replace("##",",",$orderInfo[0]['SIZEBREAKDOWN'])));
                                                            foreach ($sizeName as $sizekey => $sizevalue):
                                                                $sizeG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$itemId.'" name="sizenameg'.$sizekey.'" class="sizenameg" value="'.$sizevalue.'" onchange="sizeWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$sizevalue.'</div>';
                                                            endforeach;
                                                                   
                                                            $sizeG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $sizeG .= ' <div class="cell">';
                                                            $sizeG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $sizeG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $sizeG .= '<span class="caption">Continue</span>';
                                                            $sizeG .= '</a></div></div></div></div>';
                                                            ?>
                                                            <td class='<?=$columntrid?> size-celll-manual' style='position:relative;'><?=$sizeG?><div class='data-content'></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                        <?php
                                                        elseif($columnCombine == 'Addition'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='text' readonly class='input-small text-center row-garmentsqtyextra-input' name='garmentsextraqty' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualExtraGarmentsQty("<?=$parentId;?>", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0' data-additionval="<?=$item['NADDITIONVALUE']?>" data-additiontype="<?=$item['VADDITIONTYPE']?>">
                                                        <?php
                                                        elseif($columnCombine == 'Converter'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='text'  class='data-copier input-small text-center row-convertion-input' name='convertioninput' oninput='numberValidate($(this), $(this).val()); convertionCalculate("<?=$parentId?>", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='1'><a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopyWithCalculation($(this), "<?=$columntrid?>", "<?=$parentId?>")'><span class='mif-copy'></span></a></td>
                                                        <?php
                                                        elseif($columnCombine == 'Garments Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId?>", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'PN No.'):
                                                         //Silence is better than being right. Dont remove this condition.
                                                        elseif($columnCombine == 'Order No.'):
                                                          //Silence is better than being right. Dont remove this condition.         
                                                        elseif($columnCombine == 'Symbol'):
                                                          //Silence is better than being right. Dont remove this condition.         
                                                        elseif($columnCombine == 'Code No.'):
                                                        ?>
                                                       
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; height: 40px; margin: 0 auto;'></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "<?=$columntrid?>", "<?=$parentId?>")'><span class='mif-copy'></span></a>
                                                            </td>
                                                        <?php
                                                        else:
                                                        ?>
                                                           
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <textarea class='custom-column-value data-copier'  style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "<?=$columntrid?>", "<?=$parentId?>")'><span class='mif-copy'></span></a>
                                                           </td>
                                                    <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                    <td class='totalqty'>
                                                        <div class='row no-gap'>
                                                            <div class='cell'>
                                                                <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'>
                                                            </div>
                                                            <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='remarks' style='position:relative; overflow: hidden;'>

                                                        <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea>
                                                        <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                        </a>
                                                        <div class="row-removeBtn ribbed-darkRed" onclick="rowRemover($(this), '<?=$parentId?>');"><span class="mif-cross"></span></div>
                                                    </td>                                                 
                                                </tr>
                                                <?php
                                                foreach ($gridData as $dkey => $data):
                                                    $indexCount = $index++;
                                                    $sizeData = array();
                                                    if($item['VGRIDTYPE'] == 'colornsize'):
                                                        $sizeNameQty = explode(',', $data['QTY']);
                                                        if(count($sizeNameQty) > 0):
                                                            foreach ($sizeNameQty as $skey => $sdata):
                                                                $sizeArr = explode('##', $sdata);
                                                                $sizeData[$sizeArr[1]] = $sizeArr[0];
                                                            endforeach;
                                                        endif;
                                                    endif;
                                                    //print_r($sizeData);
                                                    if($dkey == 0):
                                                ?>
                                                    <tr class="data-row">
                                                        <td class='text-center text-bold maingrid-rowspan items-name items-<?=$getItemId?>' data-itemid='<?=$getItemId?>' rowspan='<?=count($gridData)?>'>
                                                            <?=$getItemName?>
                                                        </td>
                                                     <?php
                                                    foreach ($checkedOption as $ckey => $columnCombine):
                                                        $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                        $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                        if($columnCombine == 'Country'):
                                                                $explodeCountry = explode(',', $data['COUNTRY']);
                                                                $dataForCountry = '';
                                                                $dataForCountry .= "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
                                                                    
                                                                    foreach ($allCountry as $ckey => $country):
                                                                    if(in_array(trim($country), $explodeCountry)):
                                                                    $dataForCountry .= "<option value='".$country."' selected>".$country."</option>";
                                                                    else:
                                                                    $dataForCountry .= "<option value='".$country."'>".$country."</option>";
                                                                    endif;
                                                                    endforeach;
                                                                $dataForCountry .= "</select>"; ?>
                                                                <td class='<?=$columntrid?>'><?=$dataForCountry?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Color Wise Qty'):
                                                            $colorQty = '';
                                                            $colorQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorQty .= '<div class="row no-gap">';
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));
                                                            foreach ($response['color'] as $colorkey => $colorVal):
                                                                if(in_array(trim($colorVal), $colorDisabled)):
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                elseif(in_array(trim($colorVal), array_map('trim', $colorInsertedValue))):
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'" style="background:#006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                else:
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'"><input type="checkbox"  id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $colorQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorQty .= ' <div class="cell">';
                                                            $colorQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorQty .= '<span class="caption">Continue</span>';
                                                            $colorQty .= '</a></div></div></div></div>';
                                                            ?>
          
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorQty;?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Size Wise Qty'):
                                                            $sizeQty = '';
                                                            $sizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $sizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $sizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            $sizeInsertedValue = explode(',', $data['SIZENAME']);
                                                            // print_r($sizeInsertedValue); echo "<br>";
                                                            // print_r(array_map('trim', $sizeInsertedValue)); echo "<br>";
                                                            $sizeDisabled = array_diff($tempSizeArray, array_map('trim', $sizeInsertedValue));
                                                            // print_r($sizeDisabled);
                                                            foreach ($response['sizeQty'] as $sizekey => $sizevalue):
                                                                $count = $counter++; 
                                                                if(in_array(trim($sizekey), $sizeDisabled)):
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                elseif(in_array(trim($sizekey), array_map('trim', $sizeInsertedValue))):
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'" style="background:#006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                else:
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'"><input type="checkbox" id="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $sizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $sizeQty .= ' <div class="cell">';
                                                            $sizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $sizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $sizeQty .= '<span class="caption">Continue</span>';
                                                            $sizeQty .= '</a></div></div></div></div>';          
                                                        ?>
                                                            <td class='<?=$columntrid?> size-celll-manual' style='position:relative;'><?=$sizeQty?><div class='data-content'><?=$data['SIZENAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?>-qty'><input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'><input type='text' class='input-small text-center garments-qty-input' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'></td>
                                                           
                                                        <?php
                                                        elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            ?>
                                                            <script src="vendor/jquery/jquery-3.4.1.min.js"></script>
                                                            <script type="text/javascript">jQuery.noConflict();jQuery(document).ready(function($){
                                                                if(localStorage.getItem('colorsizewisedata') === null){
                                                                    localStorage.setItem("colorsizewisedata", JSON.stringify(<?=json_encode($response)?>));
                                                                }
                                                                });</script>
                                                            <?php
                                                            $colorSizeQty = '';
                                                            $colorSizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorSizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorSizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));
                                                            
                                                            foreach ($response['colorsizeQty'] as $cskey => $csvalue):
                                                                $count = $counter++;
                                                                if(in_array(trim($cskey), $colorDisabled)):
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="colorsname'.$count.'" disabled class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                elseif(in_array(trim($cskey), array_map('trim', $colorInsertedValue))):
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'" style="background:#006d77; color: #fff; font-weight: bold;"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="colorsname'.$count.'" checked class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                else:
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="colorsname'.$count.'" class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $colorSizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorSizeQty .= ' <div class="cell">';
                                                            $colorSizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorSizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorSizeQty .= '<span class="caption">Continue</span>';
                                                            $colorSizeQty .= '</a></div></div></div></div>';
                                                            $csize = '<div class="row no-gap">';
                                                            foreach ($response['sizename'] as $sizeValue):
                                                                $csize .= '<div class="cell size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input colorsizewiseqtyinput" data-sizename="'.$sizeValue.'" readonly value="'.$sizeData[$sizeValue].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeValue], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                            endforeach;
                                                            $csize .= '</div>';
                                                            ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorSizeQty?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> colorsizeqty'><?=$csize?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            ?>
                                                            <script src="vendor/jquery/jquery-3.4.1.min.js"></script>
                                                            <script type="text/javascript">jQuery.noConflict();jQuery(document).ready(function($){
                                                                if(localStorage.getItem('kimballcolorsizewisedata') === null){
                                                                    localStorage.setItem('kimballcolorsizewisedata', JSON.stringify(<?=json_encode($response)?>));
                                                                }
                                                            });
                                                            </script>
                                                            
                                                            <?php
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));                               
                                                            $kColorSizeQty = '';
                                                            $kColorSizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kColorSizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
                                                            $kColorSizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            //print_r($response['colorsizeQty']);
                                                            foreach ($response['colorsizeQty'] as $cskey => $csvalue):
                                                                $count = $counter++;
                                                                if(in_array(trim(str_replace('*lot*'.$response['lot'][$count], '', $cskey)), $colorDisabled)):
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '', trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                elseif(in_array(trim(str_replace('*lot*'.$response['lot'][$count], '', $cskey)),  array_map('trim', $colorInsertedValue))):
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'" style="background:#006d77; color: #fff; font-weight:bold;"><input type="checkbox" checked id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '', trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                else:
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'"><input type="checkbox" id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '', trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $kColorSizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kColorSizeQty .= ' <div class="cell">';
                                                            $kColorSizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kColorSizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kColorSizeQty .= '<span class="caption">Continue</span>';
                                                            $kColorSizeQty .= '</a></div></div></div></div>';
                                                            $ksize = '<div class="row no-gap">';
                                                            foreach ($response['sizename'] as $sizeValue):
                                                                $ksize .= '<div class="cell size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input colorsizewiseqtyinput" data-sizename="'.$sizeValue.'" readonly value="'.$sizeData[$sizeValue].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", $sizeValue).'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeValue], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                            endforeach;
                                                            $ksize .= '</div>';
                                                            ?>
                                                             
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kColorSizeQty?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'><?=$data['LOTNO']?></td>
                                                            <td class='<?=$columntrid?> colorsizeqty'><?=$ksize?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            $kColorQty = '';
                                                            $kColorQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kColorQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
                                                            $kColorQty .= '<div class="row no-gap">';
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));
                                                            foreach ($response['color'] as $kckey => $kcvalue):
                                                                
                                                                if(in_array(trim($kcvalue), $colorDisabled)):
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'" style="background:#aeaeae; color: #808080;"><input type="checkbox"  disabled id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                elseif(in_array(trim($kcvalue), array_map('trim', $colorInsertedValue))):
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'" style="background:#006d77; color: #fff; font-weight:bold;"><input type="checkbox"  checked id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                else:
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'"><input type="checkbox" id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                endif;
                                                            
                                                            endforeach;
                                                            $kColorQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kColorQty .= ' <div class="cell">';
                                                            $kColorQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kColorQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kColorQty .= '<span class="caption">Continue</span>';
                                                            $kColorQty .= '</a></div></div></div></div>';
                                                            ?>
      
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kColorQty;?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'><?=$data['LOTNO']?></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color'):
                                                            
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorG = '';
                                                            $colorG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorG .= '<div class="row no-gap">';
                                                            foreach ($response['color'] as $cgkey => $cgvalue):
                                                                if(in_array(trim($cgvalue), array_map('trim', $colorInsertedValue))):
                                                                    $colorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6" style="background: #006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$getItemId.'" name="colornameg'.$cgkey.'" class="colornameg" value="'.$cgvalue.'" onchange="colorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$cgvalue.'</div>';
                                                                else:
                                                                    $colorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$getItemId.'" name="colornameg'.$cgkey.'" class="colornameg" value="'.$cgvalue.'" onchange="colorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$cgvalue.'</div>';
                                                                endif;
                                                                    
                                                            endforeach;
                                                          
                                                            $colorG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorG .= ' <div class="cell">';
                                                            $colorG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorG .= '<span class="caption">Continue</span>';
                                                            $colorG .= '</a></div></div></div></div>';
                                                        ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorG?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                        
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):

                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $kcolorG = '';
                                                            $kcolorG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kcolorG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $kcolorG .= '<div class="row no-gap">';
                                                            foreach ($response['color'] as $kckey => $kcvalue):
                                                                if(in_array(trim($kcvalue), array_map('trim', $colorInsertedValue))):
                                                                    $kcolorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6" style="background: #006d77; color: #fff; font-weight: bold;"
                                                                ><input type="checkbox" checked id="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolornameg'.$kckey.'" class="kcolornameg" value="'.$kcvalue.'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                else:
                                                                    $kcolorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" name="kcolornameg'.$kckey.'" class="kcolornameg" value="'.$kcvalue.'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                endif;

                                                            endforeach;
                                                            $kcolorG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kcolorG .= ' <div class="cell">';
                                                            $kcolorG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kcolorG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kcolorG .= '<span class="caption">Continue</span>';
                                                            $kcolorG .= '</a></div></div></div></div>';
                                                        ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kcolorG?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'><?=$data['LOTNO']?></td>");
                                                        <?php
                                                        elseif($columnCombine == 'Size Name'):

                                                            $sizeInsertedValue = explode(',', $data['SIZENAME']);                                                        
                                                            $sizeG = '';
                                                            $sizeG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $sizeG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Size Name</p>';
                                                            $sizeG .= '<div class="row no-gap">';
                                                            $sizeName = array_unique(explode(',', str_replace("##",",",$orderInfo[0]['SIZEBREAKDOWN'])));
                                                            foreach ($sizeName as $sizekey => $sizevalue):
                                                                if(in_array(trim($sizevalue), array_map('trim', $sizeInsertedValue))):
                                                                    $sizeG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6" style="background: #006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$getItemId.'" name="sizenameg'.$sizekey.'" class="sizenameg" value="'.$sizevalue.'" onchange="sizeWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$sizevalue.'</div>';
                                                                else:
                                                                    $sizeG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$getItemId.'" name="sizenameg'.$sizekey.'" class="sizenameg" value="'.$sizevalue.'" onchange="sizeWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$getItemId.'" style="cursor:pointer;">'.$sizevalue.'</div>';
                                                                endif;
                                                            endforeach;
                                                                   
                                                            $sizeG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $sizeG .= ' <div class="cell">';
                                                            $sizeG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $sizeG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $sizeG .= '<span class="caption">Continue</span>';
                                                            $sizeG .= '</a></div></div></div></div>';
                                                            ?>
                                                            <td class='<?=$columntrid?> size-celll-manual' style='position:relative;'><?=$sizeG?><div class='data-content'><?=$data['SIZENAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                        <?php
                                                        elseif($columnCombine == 'Addition'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type="text" readonly="" class="input-small text-center row-garmentsqtyextra-input" name="garmentsextraqty" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualExtraGarmentsQty('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NROWGARMENTSQTYWITHEXTRA']?>" data-additionval="<?=$item['NADDITIONVALUE']?>" data-additiontype="<?=$item['VADDITIONTYPE']?>">
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Converter'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type="text" class="data-copier input-small text-center row-convertion-input" name="convertioninput" oninput="numberValidate($(this), $(this).val()); convertionCalculate('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NCONVERTERQTY']?>" <?=!empty($item['NCONVERSIONVALUE']) ? 'data-convertionval="'.$item['NCONVERSIONVALUE'].'"' : '';?>    <?=!empty($item['VCONVERTIOMNTYPE']) ? 'data-calinputtype="'.$item['VCONVERTIOMNTYPE'].'"' : '';?> >
                                                                <a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopyWithCalculation($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Garments Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId?>", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'PN No.'): ?>
                                                            <td class="pn-no-cell <?=$columntrid?> text-center" rowspan="<?=count($gridData);?>"><input type="text" class="input-small pn-no-input text-center" name="pnnumber" style="max-width:120px; margin: 0 auto;" value="<?=$item['VPNNUMBER']?>"></td>
                                                        <?php
                                                        elseif($columnCombine == 'Order No.'):
                                                        ?>
                                                            <td class="order-no-cell <?=$columntrid?>" rowspan="<?=count($gridData);?>"><input type="text" class="input-small order-no-input" name="ordernumber" style="max-width:120px; margin: 0 auto;" value="<?=$item['VORDERNUMBER']?>"></td>
                                                        <?php
                                                          //Silence is better than being right. Dont remove this condition.         
                                                        elseif($columnCombine == 'Code No.'):
                                                        ?>
                                                       
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; height: 40px; margin: 0 auto;'><?=$data['CODENO']?></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "<?=$columntrid?>", "<?=$parentId?>")'><span class='mif-copy'></span></a>
                                                            </td>
                                                        <?php
                                                        else:
                                                            if($columnCombine != 'Symbol'):
                                                                $columnname = preg_replace('/[^A-Za-z0-9]/','', strtoupper($columnCombine));
                                                        ?>
                                                                <td class='<?=$columntrid?>' style="position: relative;">
                                                                    <textarea class="custom-column-value data-copier" style="max-width:100%; margin: 0 auto; height: 40px;"><?=$data["$columnname"];?></textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                </td>
                                                        <?php
                                                            endif;
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                   
                                                    <td class='totalqty'>
                                                        <div class='row no-gap'>
                                                            <div class='cell'>
                                                                <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$data['NROWTOTALQTY']?>'>
                                                            </div>
                                                            <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='remarks' style='position:relative;'>
                                                        <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'><?=$data['VREMARKS']?></textarea>
                                                        <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                        </a>
                                                    </td>
                                                    <?php
                                                    if(in_array('Symbol', $checkedOption)):
                                                        $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower('Symbol')).$getItemId;
                                                        $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                        $count = $accessoriesModel->getData("SELECT nid FROM accessories_images WHERE nworkorderitemid = $itemId");
                                                        $counter = is_array($count) ? 4 - count($count) : 4;
                                                    ?>
                                                        <td class="symbol-cell <?=$columntrid?> text-center" rowspan="<?=count($gridData);?>">
                                                            <input type="hidden" class="symbol-count" value="<?=$counter?>">
                                                            <div class="<?=$parentId?>-symbol">
                                                                <input type='file' name='attachment[]' multiple data-role='file' data-mode='drop' onchange='checkimage($(this), <?=$counter?>); checkvalidformat($(this));' class='symbol-input'><small>Allowed file format: jpg/png/jpeg/gif, Max allowed : 4 images.</small>
                                                                <br>
                                                                <small class="fg-red"><?=is_array($count) ? count($count) : 0; ?> symbol(s) added.</small>
                                                            </div>
                                                        
                                                    </td>
                                                    <?php
                                                    endif;
                                                    ?>
                                                </tr>
                                                <?php
                                                else:
                                                ?>
                                                    <tr class="data-row appended-row">
                                                    <?php
                                                        foreach ($checkedOption as $ckey => $columnCombine):
                                                            $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                            $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                            
                                                            if($columnCombine == 'Country'):
                                                                $explodeCountry = explode(',', $data['COUNTRY']);
                                                                $dataForCountry = '';
                                                                $dataForCountry .= "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
                                                                    
                                                                    foreach ($allCountry as $ckey => $country):
                                                                    if(in_array(trim($country), $explodeCountry)):
                                                                    $dataForCountry .= "<option value='".$country."' selected>".$country."</option>";
                                                                    else:
                                                                    $dataForCountry .= "<option value='".$country."'>".$country."</option>";
                                                                    endif;
                                                                    endforeach;
                                                                $dataForCountry .= "</select>"; ?>
                                                                <td class='<?=$columntrid?>'><?=$dataForCountry?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Color Wise Qty'):
                                                            $colorQty = '';
                                                            $colorQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorQty .= '<div class="row no-gap">';
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));
                                                            foreach ($response['color'] as $colorkey => $colorVal):
                                                                if(in_array(trim($colorVal), $colorDisabled)):
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$itemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                elseif(in_array(trim($colorVal), array_map('trim', $colorInsertedValue))):
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'" style="background:#006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$itemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                else:
                                                                    $colorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colornameDiv'.$colorkey.'"><input type="checkbox"  id="colorselect-'.$colorkey.'-'.$indexCount.'-'.$itemId.'" name="colorname'.$colorkey.'" class="colorwiseqty colorname'.$colorkey.'" value="'.$colorVal.'" data-qty="'.$response['qty'][$colorkey].'" onchange="colorWiseQty($(this), \''.$colorkey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselect-'.$colorkey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$colorVal.'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $colorQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorQty .= ' <div class="cell">';
                                                            $colorQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorQty .= '<span class="caption">Continue</span>';
                                                            $colorQty .= '</a></div></div></div></div>';
                                                            ?>
          
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorQty;?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Size Wise Qty'):
                                                            $sizeQty = '';
                                                            $sizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $sizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $sizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            $sizeInsertedValue = explode(',', $data['SIZENAME']);
                                                            // print_r($sizeInsertedValue); echo "<br>";
                                                            // print_r(array_map('trim', $sizeInsertedValue)); echo "<br>";
                                                            $sizeDisabled = array_diff($tempSizeArray, array_map('trim', $sizeInsertedValue));
                                                            // print_r($sizeDisabled);
                                                            foreach ($response['sizeQty'] as $sizekey => $sizevalue):
                                                                $count = $counter++; 
                                                                if(in_array(trim($sizekey), $sizeDisabled)):
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="sizeselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                elseif(in_array(trim($sizekey), array_map('trim', $sizeInsertedValue))):
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'" style="background:#006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="sizeselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                else:
                                                                    $sizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 sizenameDiv'.$count.'"><input type="checkbox" id="sizeselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="sizename'.$count.'" class="sizewiseqty sizename'.$count.'" value="'.$sizekey.'" data-qty="'.$sizevalue.'" onchange="sizeWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$sizekey.'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $sizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $sizeQty .= ' <div class="cell">';
                                                            $sizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $sizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $sizeQty .= '<span class="caption">Continue</span>';
                                                            $sizeQty .= '</a></div></div></div></div>';          
                                                        ?>
                                                            <td class='<?=$columntrid?> size-celll-manual' style='position:relative;'><?=$sizeQty?><div class='data-content'><?=$data['SIZENAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?>-qty'><input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'><input type='text' class='input-small text-center garments-qty-input' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'></td>
                                                           
                                                        <?php
                                                        elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            $colorSizeQty = '';
                                                            $colorSizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorSizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorSizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));
                                                            
                                                            foreach ($response['colorsizeQty'] as $cskey => $csvalue):
                                                                $count = $counter++;
                                                                if(in_array(trim($cskey), $colorDisabled)):
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="colorsname'.$count.'" disabled class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                elseif(in_array(trim($cskey), array_map('trim', $colorInsertedValue))):
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'" style="background:#006d77; color: #fff; font-weight: bold;"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="colorsname'.$count.'" checked class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                else:
                                                                    $colorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 colorsnameDiv'.$count.'"><input type="checkbox" id="colorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="colorsname'.$count.'" class="colorswiseqty colorsname'.$count.'" value="'.trim($cskey).'" onchange="colorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="colorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.trim($cskey).'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $colorSizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorSizeQty .= ' <div class="cell">';
                                                            $colorSizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorSizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorSizeQty .= '<span class="caption">Continue</span>';
                                                            $colorSizeQty .= '</a></div></div></div></div>';
                                                            $csize = '<div class="row no-gap">';
                                                            foreach ($response['sizename'] as $sizeValue):
                                                                $csize .= '<div class="cell size-'.$sizeValue.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeValue.'-input colorsizewiseqtyinput" data-sizename="'.$sizeValue.'" readonly value="'.$sizeData[$sizeValue].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeValue.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeValue], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                            endforeach;
                                                            $csize .= '</div>';
                                                            ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorSizeQty?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> colorsizeqty'><?=$csize?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));                               
                                                            $kColorSizeQty = '';
                                                            $kColorSizeQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kColorSizeQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
                                                            $kColorSizeQty .= '<div class="row no-gap">';
                                                            $counter = 0;
                                                            //print_r($response['colorsizeQty']);
                                                            foreach ($response['colorsizeQty'] as $cskey => $csvalue):
                                                                $count = $counter++;
                                                                if(in_array(trim(str_replace('*lot*'.$response['lot'][$count], '', $cskey)), $colorDisabled)):
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'" style="background:#aeaeae; color: #808080;"><input type="checkbox" disabled id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '', trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                elseif(in_array(trim(str_replace('*lot*'.$response['lot'][$count], '', $cskey)),  array_map('trim', $colorInsertedValue))):
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'" style="background:#006d77; color: #fff; font-weight:bold;"><input type="checkbox" checked id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '', trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                else:
                                                                    $kColorSizeQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6 kcolorsnameDiv'.$count.'"><input type="checkbox" id="kcolorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" name="kcolorsname'.$count.'" class="kcolorswiseqty kcolorsname'.$count.'" data-kimball="'.$response['kimball'][$count].'" data-lot="'.$response['lot'][$count].'" value="'.trim($cskey).'" onchange="kcolorsWiseQty($(this), \''.$count.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorsselect-'.$count.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.str_replace('*lot*'.$response['lot'][$count], '',trim($cskey)).' / '.$response['kimball'][$count].' / '.$response['lot'][$count].'</label></div>';
                                                                endif;
                                                            endforeach;
                                                            $kColorSizeQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kColorSizeQty .= ' <div class="cell">';
                                                            $kColorSizeQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kColorSizeQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kColorSizeQty .= '<span class="caption">Continue</span>';
                                                            $kColorSizeQty .= '</a></div></div></div></div>';
                                                            $ksize = '<div class="row no-gap">';
                                                            foreach ($response['sizename'] as $sizeValue):
                                                                $ksize .= '<div class="cell size-'.$sizeValue.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeValue.'-input colorsizewiseqtyinput" data-sizename="'.$sizeValue.'" readonly value="'.$sizeData[$sizeValue].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeValue.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeValue], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                            endforeach;
                                                            $ksize .= '</div>';
                                                            ?>
                                                             
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kColorSizeQty?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'><?=$data['LOTNO']?></td>
                                                            <td class='<?=$columntrid?> colorsizeqty'><?=$ksize?></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            $kColorQty = '';
                                                            $kColorQty .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kColorQty .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Select Data</p>';
                                                            $kColorQty .= '<div class="row no-gap">';
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorDisabled = array_diff($tempColorArray, array_map('trim', $colorInsertedValue));
                                                            foreach ($response['color'] as $kckey => $kcvalue):
                                                                
                                                                if(in_array(trim($kcvalue), $colorDisabled)):
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'" style="background:#aeaeae; color: #808080;"><input type="checkbox"  disabled id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$itemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                elseif(in_array(trim($kcvalue), array_map('trim', $colorInsertedValue))):
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'" style="background:#006d77; color: #fff; font-weight:bold;"><input type="checkbox"  checked id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$itemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                else:
                                                                    $kColorQty .= '<div class="mt-1 mb-1 p-1 border bd-light cell-3 kcolornameDiv'.$kckey.'"><input type="checkbox" id="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$itemId.'" name="kcolorname'.$kckey.'" class="kcolorwiseqty kcolorname'.$kckey.'" value="'.$kcvalue.'" data-qty="'.$response['qty'][$kckey].'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQty($(this), \''.$kckey.'\', \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselect-'.$kckey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                endif;
                                                            
                                                            endforeach;
                                                            $kColorQty .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kColorQty .= ' <div class="cell">';
                                                            $kColorQty .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kColorQty .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kColorQty .= '<span class="caption">Continue</span>';
                                                            $kColorQty .= '</a></div></div></div></div>';
                                                            ?>
      
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kColorQty;?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'><?=$data['LOTNO']?></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color'):
                                                            
                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $colorG = '';
                                                            $colorG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $colorG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $colorG .= '<div class="row no-gap">';
                                                            foreach ($response['color'] as $cgkey => $cgvalue):
                                                                if(in_array(trim($cgvalue), array_map('trim', $colorInsertedValue))):
                                                                    $colorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6" style="background: #006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$itemId.'" name="colornameg'.$cgkey.'" class="colornameg" value="'.$cgvalue.'" onchange="colorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$cgvalue.'</div>';
                                                                else:
                                                                    $colorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$itemId.'" name="colornameg'.$cgkey.'" class="colornameg" value="'.$cgvalue.'" onchange="colorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="colorselectg-'.$cgkey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$cgvalue.'</div>';
                                                                endif;
                                                                    
                                                            endforeach;
                                                          
                                                            $colorG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $colorG .= ' <div class="cell">';
                                                            $colorG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $colorG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $colorG .= '<span class="caption">Continue</span>';
                                                            $colorG .= '</a></div></div></div></div>';
                                                        ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$colorG?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                        
                                                        <?php
                                                        elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):

                                                            $colorInsertedValue = explode(',', $data['COLORNAME']);
                                                            $kcolorG = '';
                                                            $kcolorG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $kcolorG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Color Name</p>';
                                                            $kcolorG .= '<div class="row no-gap">';
                                                            foreach ($response['color'] as $kckey => $kcvalue):
                                                                if(in_array(trim($kcvalue), array_map('trim', $colorInsertedValue))):
                                                                    $kcolorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6" style="background: #006d77; color: #fff; font-weight: bold;"
                                                                ><input type="checkbox" checked id="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$itemId.'" name="kcolornameg'.$kckey.'" class="kcolornameg" value="'.$kcvalue.'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                else:
                                                                    $kcolorG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$itemId.'" name="kcolornameg'.$kckey.'" class="kcolornameg" value="'.$kcvalue.'" data-kimball="'.$response['kimball'][$kckey].'" data-lot="'.$response['lot'][$kckey].'" onchange="kimballColorWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="kcolorselectg-'.$kckey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$kcvalue.' / '.$response['kimball'][$kckey].' / '.$response['lot'][$kckey].'</label></div>';
                                                                endif;

                                                            endforeach;
                                                            $kcolorG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $kcolorG .= ' <div class="cell">';
                                                            $kcolorG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $kcolorG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $kcolorG .= '<span class="caption">Continue</span>';
                                                            $kcolorG .= '</a></div></div></div></div>';
                                                        ?>
                                                            <td class='<?=$columntrid?> color-celll-manual' style='position:relative;'><?=$kcolorG?><div class='data-content'><?=$data['COLORNAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                            <td class='<?=$columntrid?> text-center lot-cell'><?=$data['LOTNO']?></td>");
                                                        <?php
                                                        elseif($columnCombine == 'Size Name'):

                                                            $sizeInsertedValue = explode(',', $data['SIZENAME']);                                                        
                                                            $sizeG = '';
                                                            $sizeG .= '<div class="right-popup" style="display: none;"><div class="right-popup-container">';
                                                            $sizeG .= '<p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Pick Size Name</p>';
                                                            $sizeG .= '<div class="row no-gap">';
                                                            $sizeName = array_unique(explode(',', str_replace("##",",",$orderInfo[0]['SIZEBREAKDOWN'])));
                                                            foreach ($sizeName as $sizekey => $sizevalue):
                                                                if(in_array(trim($sizevalue), array_map('trim', $sizeInsertedValue))):
                                                                    $sizeG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6" style="background: #006d77; color: #fff; font-weight: bold;"><input type="checkbox" checked id="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$itemId.'" name="sizenameg'.$sizekey.'" class="sizenameg" value="'.$sizevalue.'" onchange="sizeWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$sizevalue.'</div>';
                                                                else:
                                                                    $sizeG .= '<div class="mt-1 mb-1 p-1 border bd-light cell-xl-2 cell-lg-3 cell-sm-4 cell-6"><input type="checkbox" id="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$itemId.'" name="sizenameg'.$sizekey.'" class="sizenameg" value="'.$sizevalue.'" onchange="sizeWiseQtyGeneral($(this), \''.$parentId.'\')" style="cursor:pointer;"><label for="sizeselectg-'.$sizekey.'-'.$indexCount.'-'.$itemId.'" style="cursor:pointer;">'.$sizevalue.'</div>';
                                                                endif;
                                                            endforeach;
                                                                   
                                                            $sizeG .= '</div><div class="row no-gap" style="margin: 0 auto;padding: 6px; text-align: center;">';
                                                            $sizeG .= ' <div class="cell">';
                                                            $sizeG .= '<a href="javscript:void(0)" class="image-button success border bd-dark-hover" style="height: 22px;" onclick="closePicker();">';
                                                            $sizeG .= '<span class="mif-done icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>';
                                                            $sizeG .= '<span class="caption">Continue</span>';
                                                            $sizeG .= '</a></div></div></div></div>';
                                                            ?>
                                                            <td class='<?=$columntrid?> size-celll-manual' style='position:relative;'><?=$sizeG?><div class='data-content'><?=$data['SIZENAME']?></div><span class='tally picker-popup' onclick='colorPicker($(this), "<?=$parentId?>");'><span class='icon mif-yelp'></span></span></td>
                                                        <?php
                                                        elseif($columnCombine == 'Addition'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type="text" readonly="" class="input-small text-center row-garmentsqtyextra-input" name="garmentsextraqty" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualExtraGarmentsQty('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NROWGARMENTSQTYWITHEXTRA']?>" data-additionval="<?=$item['NADDITIONVALUE']?>" data-additiontype="<?=$item['VADDITIONTYPE']?>">
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Converter'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type="text" class="data-copier input-small text-center row-convertion-input" name="convertioninput" oninput="numberValidate($(this), $(this).val()); convertionCalculate('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NCONVERTERQTY']?>" <?=!empty($item['NCONVERSIONVALUE']) ? 'data-convertionval="'.$item['NCONVERSIONVALUE'].'"' : '';?>    <?=!empty($item['VCONVERTIOMNTYPE']) ? 'data-calinputtype="'.$item['VCONVERTIOMNTYPE'].'"' : '';?>>
                                                                <a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopyWithCalculation($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Garments Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId?>", $(this));' name='garmentsqty' style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'PN No.'):
                                                            
                                                        elseif($columnCombine == 'Order No.'):

                                                        elseif($columnCombine == 'Symbol'):
                                                             
                                                        elseif($columnCombine == 'Code No.'):
                                                        ?>
                                                       
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; height: 40px; margin: 0 auto;'><?=$data['CODENO']?></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "<?=$columntrid?>", "<?=$parentId?>")'><span class='mif-copy'></span></a>
                                                            </td>
                                                        <?php
                                                        else:
                                                            
                                                                $columnname = preg_replace('/[^A-Za-z0-9]/','', strtoupper($columnCombine));
                                                        ?>
                                                                <td class='<?=$columntrid?>' style="position: relative;">
                                                                    <textarea class="custom-column-value data-copier" style="max-width:100%; margin: 0 auto; height: 40px;"><?=$data["$columnname"];?></textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                </td>
                                                        <?php
                                                            
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                   
                                                    <td class='totalqty'>
                                                        <div class='row no-gap'>
                                                            <div class='cell'>
                                                                <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$data['NROWTOTALQTY']?>'>
                                                            </div>
                                                            <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='remarks' style='position:relative; overflow: hidden;'>
                                                        <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'><?=$data['VREMARKS']?></textarea>
                                                        <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                        </a>
                                                        <div class="row-removeBtn ribbed-darkRed" onclick="rowRemover($(this), '<?=$parentId?>');"><span class="mif-cross"></span></div>
                                                    </td>
                                                   
                                                </tr>
                                                            
                                                <?php
                                                endif;
                                                ?>

                                            <?php
                                                endforeach;
                                            ?>
                                                <tr style="background: #e0f0f1;">
                                                        <td style="font-weight:bold;" class="text-right grandQtyCell">
                                                            <?php
                                                            if($item['VDATAFILLTYPE'] == 'manual-data'):
                                                            ?>
                                                                <button onclick="rowAdder($(this), '<?=$parentId?>')" type="button" class="tool-button ribbed-teal success" style="position: absolute;width: 20px;height: 20px;line-height: 18px;top: 6px;z-index: 1083;right: 3px;"><span class="mif-plus" style="font-size: 13px;"></span></button>
                                                            <?php
                                                            endif;
                                                            ?>
                                                            <p style="width: 155px;margin: 0px;">Quantity Grand Total</p></td>
                                                    <?php
                                                        foreach ($checkedOption as $ckey => $columnCombine):
                                                            $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                            $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                            if($columnCombine == 'Color Wise Qty' || $columnCombine == 'Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type="text" class="input-small text-center garmentsgrandtotal" name="garmentsgrandtotal" style="min-width: 80px; max-width:120px; margin: 0 auto;" value="<?=$item["NTOTALGARMENTSQTY"]?>" readonly="">
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>'>
                                                            </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?> kimballcelll'></td>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>'>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?> kimballcelll'></td>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                   <input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>' readonly>
                                                                </td>
                                                           <?php
                                                            elseif($columnCombine == 'Grmnts Color'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                            <?php
                                                            elseif($columnCombine == 'Size Name'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                            <?php
                                                            elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                                <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                                <td class='<?=$columntrid?> text-center'></td>
                                                            <?php
                                                            elseif($columnCombine == 'Addition'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type='text' class='input-small text-center garmentsextragrandtotal' name='garmentsextragrandtotal' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTYWITHEXTRA"]?>' readonly>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Garments Qty'):
                                                            ?>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type="text" class="input-small text-center garmentsgrandtotal" name="garmentsgrandtotal" style="min-width: 80px; max-width:120px; margin: 0 auto;" value="<?=$item["NTOTALGARMENTSQTY"]?>" readonly="">
                                                            </td>
                                        
                                                            <?php
                                                            else:
                                                                if($columnCombine != 'Symbol'):
                                                            ?> 
                                                                    <td class='<?=$columntrid?>' style="position: relative;"></td>
                                                        <?php   endif;
                                                            endif;

                                                        endforeach;
                                                    ?>
                                                        <td class="grandtotalqty">
                                                            <div class="row no-gap">
                                                                <div class="cell">
                                                                    <input type="text" readonly="" class="input-small text-center grand-totalqty-input" name="grandqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$item["NTOTALQTY"]?>">
                                                                </div>
                                                                <div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;">
                                                                    <span class="text-bold grandunit"><?=$item['VQTYUNIT']?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class='remarks' style='position:relative;'></td>
                                                        <?php
                                                            if(in_array('Symbol', $checkedOption)):
                                                                $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower('Symbol')).$getItemId;
                                                                $count = $accessoriesModel->getData("SELECT nid FROM accessories_images WHERE nworkorderitemid = $itemId");
                                                                $counter = is_array($count) ? 4 - count($count) : 4;
                                                            ?>
                                                                <td class="<?=$columntrid?> text-center"></td>
                                                            <?php
                                                            endif;
                                                            ?>
                                                    </tr>
                                                <?php
                                                //Excell upload
                                                elseif($item['NEXCELUPLOAD'] == 1):
                                                ?>
                                                <tr class="data-row-hidden appended-row" style="display: none;">
                                                    <?php
                                                    foreach ($checkedOption as $ckey => $columnCombine):
                                                        $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                        $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                        if($columnCombine == 'Color Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'Size Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                           
                                                        <?php
                                                        elseif($columnCombine == 'Color & Size Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td><td class='<?=$columntrid?>'></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <?php
                                                            if($item["NEXCELUPLOAD"] == 1 && in_array('Country', $customColumn)):
                                                            ?>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <?php
                                                            endif;
                                                            ?>
                                                            <td class='<?=$columntrid?> kimballcelll'></td>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?>'></td>
                                                        <?php
                                                        elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?> kimballcelll'></td>
                                                            <td class='<?=$columntrid?>'></td>
                                                            <td class='<?=$columntrid?>-qty'>
                                                                <input type='hidden' class='addition-qty-hidden' value='0'>
                                                                <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='0'>
                                                            </td>
                                                        <!-- <?php
                                                        //elseif($columnCombine == 'Grmnts Color'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                        <?php
                                                       // elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                            <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                            <td class='<?=$columntrid?> text-center'></td> -->
                                                        <?php
                                                        elseif($columnCombine == 'Addition'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='text' readonly class='input-small text-center row-garmentsqtyextra-input' name='garmentsextraqty' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualExtraGarmentsQty("<?=$parentId;?>", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'>
                                                        <?php
                                                        elseif($columnCombine == 'Converter'):
                                                        ?>
                                                            <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='text'  class='input-small text-center row-convertion-input' name='convertioninput' oninput='numberValidate($(this), $(this).val()); convertionCalculate("<?=$parentId;?>", $(this));' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='1'>
                                                            </td>
                                                        <?php
                                                        elseif($columnCombine == 'PN No.'):
                                                         //Silence is better than being right. Dont remove this condition.
                                                        elseif($columnCombine == 'Order No.'):
                                                          //Silence is better than being right. Dont remove this condition.         
                                                        elseif($columnCombine == 'Symbol'):
                                                          //Silence is better than being right. Dont remove this condition.         
                                                        elseif($columnCombine == 'Code No.'):
                                                        ?>
                                                            <td class='<?=$columntrid?>'>
                                                                <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>
                                                            </td>
                                                        <?php
                                                        else:
                                                        ?>
                                                            <td class='<?=$columntrid?>'>
                                                                <textarea class='custom-column-value'  style='max-width:100%; margin: 0 auto; height: 40px;'></textarea>
                                                            </td>
                                                    <?php
                                                        endif;
                                                    endforeach;
                                                    ?>
                                                    <td class='totalqty'>
                                                        <div class='row no-gap'>
                                                            <div class='cell'>
                                                                <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='0'>
                                                            </div>
                                                            <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class='remarks' style='position:relative;'>

                                                        <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'></textarea>
                                                        <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                        </a>
                                                    </td>                                                 
                                                </tr>
                                                <?php
                                                
                                                foreach ($gridData as $dkey => $data):
                                                    $indexCount = $index++;
                                                    $sizeData = array();
                                                    if($item['VGRIDTYPE'] == 'colornsize'):
                                                        $sizeNameQty = explode(',', $data['QTY']);
                                                        if(count($sizeNameQty) > 0):
                                                            foreach ($sizeNameQty as $skey => $sdata):
                                                                $sizeArr = explode('##', $sdata);
                                                                $sizeData[$sizeArr[1]] = $sizeArr[0];
                                                            endforeach;
                                                        endif;
                                                    endif;
                                                    //print_r($sizeData);
                                                    if($dkey == 0):
                                                ?>
                                                    <tr class="data-row">
                                                        <td class='text-center text-bold maingrid-rowspan items-name items-<?=$getItemId?>' data-itemid='<?=$getItemId?>' rowspan='<?=count($gridData)?>'>
                                                            <?=$getItemName?>
                                                        </td>
                                                    <?php
                                                        foreach ($checkedOption as $ckey => $columnCombine):
                                                            $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                            $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                            if($columnCombine == 'Country'):
                                                                $explodeCountry = explode(',', $data['COUNTRY']);
                                                                $dataForCountry = '';
                                                                $dataForCountry .= "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
                                                                
                                                                foreach ($allCountry as $ckey => $country):
                                                                    if(in_array(trim($country), $explodeCountry)):
                                                                        $dataForCountry .= "<option value='".$country."' selected>".$country."</option>";
                                                                    else:
                                                                        $dataForCountry .= "<option value='".$country."'>".$country."</option>";
                                                                    endif;
                                                                endforeach;
                                                                $dataForCountry .= "</select>"; ?>
                                                            <td class='<?=$columntrid?>'><?=$dataForCountry?></td>
                                                            <?php
                                                            elseif($columnCombine == 'Color Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                    <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> size-celll-fixed'><?=$data['SIZENAME']?></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                    <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                </td>
                                                               
                                                            <?php
                                                            elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <?php
                                                                $csize = '<div class="row no-gap">';
                                                                $sizeName =  explode(',', $item['VSIZENAME']);
                                                                foreach ($sizeName as $skey => $sizeval):
                                                                    $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" readonly ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));" value="'.$sizeData[$sizeval].'" style="width: 100%;"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                endforeach;
                                                                $csize .= '</div>';
                                                                ?>
                                                                <td class='<?=$columntrid?> colorsizeqty'><?=$csize?></td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <?php
                                                                if($item["NEXCELUPLOAD"] == 1 && in_array('Country', $customColumn)):
                                                                ?>
                                                                <td class='<?=$columntrid?>'><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="<?=$data['COUNTRY']?>" readonly></td>
                                                                <?php
                                                                endif;
                                                                ?>
                                                                <td class='<?=$columntrid?> kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                                <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                <?php
                                                                $csize = '<div class="row no-gap">';
                                                                $sizeName =  explode(',', $item['VSIZENAME']);
                                                                foreach ($sizeName as $skey => $sizeval):
                                                                    $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" readonly ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));" value="'.$sizeData[$sizeval].'" style="width: 100%;"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                endforeach;
                                                                $csize .= '</div>';
                                                                ?>

                                                                <td class='<?=$columntrid?> colorsizeqty'>
                                                                    <?=$csize?>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                <td class='<?=$columntrid?> kimballcelll kimball-cell'><?=$data['KIMBALLNO']?></td>
                                                                <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                    <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                </td>
                                                            <!-- <?php
                                                            //elseif($columnCombine == 'Grmnts Color'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed' style='position:relative;'></td>
                                                            <?php
                                                            //elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                                <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                                <td class='<?=$columntrid?> text-center'></td> -->
                                                            <?php
                                                            elseif($columnCombine == 'Addition'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type="text" readonly="" class="input-small text-center row-garmentsqtyextra-input" name="garmentsextraqty" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualExtraGarmentsQty('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NROWGARMENTSQTYWITHEXTRA']?>" data-additionval="<?=$item['NADDITIONVALUE']?>" data-additiontype="<?=$item['VADDITIONTYPE']?>">
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Converter'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type="text" class="data-copier input-small text-center row-convertion-input" name="convertioninput" oninput="numberValidate($(this), $(this).val()); convertionCalculate('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NCONVERTERQTY']?>" <?=!empty($item['NCONVERSIONVALUE']) ? 'data-convertionval="'.$item['NCONVERSIONVALUE'].'"' : '';?>  <?=!empty($item['VCONVERTIOMNTYPE']) ? 'data-calinputtype="'.$item['VCONVERTIOMNTYPE'].'"' : '';?>>
                                                                    <a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopyWithCalculation($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'PN No.'): ?>
                                                                <td class="pn-no-cell <?=$columntrid?> text-center" rowspan="<?=count($gridData);?>"><input type="text" class="input-small pn-no-input text-center" name="pnnumber" style="max-width:120px; margin: 0 auto;" value="<?=$item['VPNNUMBER']?>"></td>
                                                            <?php
                                                            elseif($columnCombine == 'Order No.'):
                                                            ?>
                                                            <td class="order-no-cell <?=$columntrid?>" rowspan="<?=count($gridData);?>"><input type="text" class="input-small order-no-input" name="ordernumber" style="max-width:120px; margin: 0 auto;" value="<?=$item['VORDERNUMBER']?>"></td>                                                                    
                                                            <?php       
                                                            elseif($columnCombine == 'Code No.'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'>
                                                                    <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; margin: 0 auto; height: 40px;'><?=$data["CODENO"];?></textarea>
                                                                </td>
                                                            <?php
                                                            else:
                                                                if($columnCombine != 'Symbol'):
                                                                    $columnname = preg_replace('/[^A-Za-z0-9]/','', strtoupper($columnCombine));

                                                            ?>
                                                                    <td class='<?=$columntrid?>' style="position: relative;">
                                                                        <textarea class="custom-column-value data-copier" style="max-width:100%; margin: 0 auto; height: 40px;"><?=$data["$columnname"];?></textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                    </td>
                                                        <?php
                                                                endif;
                                                            endif;

                                                        endforeach;
                                                    ?>
                                                         <td class='totalqty'>
                                                                <div class='row no-gap'>
                                                                    <div class='cell'>
                                                                        <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$data['NROWTOTALQTY']?>'>
                                                                    </div>
                                                                    <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class='remarks' style='position:relative;'>
                                                                <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'><?=$data['VREMARKS']?></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                                </a>
                                                            </td>
                                                            <?php
                                                            if(in_array('Symbol', $checkedOption)):
                                                                $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower('Symbol')).$getItemId;
                                                                $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                                $count = $accessoriesModel->getData("SELECT nid FROM accessories_images WHERE nworkorderitemid = $itemId");
                                                                $counter = is_array($count) ? 4 - count($count) : 4;
                                                            ?>
                                                                <td class="symbol-cell <?=$columntrid?> text-center" rowspan="<?=count($gridData);?>">
                                                                    <input type="hidden" class="symbol-count" value="<?=$counter?>">
                                                                    <div class="<?=$parentId?>-symbol">
                                                                        <input type='file' name='attachment[]' multiple data-role='file' data-mode='drop' onchange='checkimage($(this), <?=$counter?>); checkvalidformat($(this));' class='symbol-input'><small>Allowed file format: jpg/png/jpeg/gif, Max allowed : 4 images.</small>
                                                                        <br>
                                                                        <small class="fg-red"><?=is_array($count) ? count($count) : 0; ?> symbol(s) added.</small>
                                                                    </div>
                                                                
                                                            </td>
                                                            <?php
                                                            endif;
                                                            ?>
                                                    </tr>
                                                    <?php
                                                    else:
                                                    ?>
                                                        <tr class="data-row appended-row">
                                                        <?php
                                                            foreach ($checkedOption as $ckey => $columnCombine):
                                                                $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                                $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                                if($columnCombine == 'Country'):
                                                                    $explodeCountry = explode(',', $data['COUNTRY']);
                                                                    $dataForCountry = '';
                                                                    $dataForCountry .= "<select multiple style='max-width:110px; min-height:100px; margin: 0 auto;' class='custom-column-value'>";
                                                                    
                                                                    foreach ($allCountry as $ckey => $country):
                                                                        if(in_array(trim($country), $explodeCountry)):
                                                                            $dataForCountry .= "<option value='".$country."' selected>".$country."</option>";
                                                                        else:
                                                                            $dataForCountry .= "<option value='".$country."'>".$country."</option>";
                                                                        endif;
                                                                    endforeach;
                                                                    $dataForCountry .= "</select>"; ?>
                                                                    <td class='<?=$columntrid?>'><?=$dataForCountry?></td>
                                                            <?php
                                                                elseif($columnCombine == 'Color Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <td class='<?=$columntrid?>-qty'>
                                                                        <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                        <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'Size Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> size-celll-fixed'><?=$data['SIZENAME']?></td>
                                                                    <td class='<?=$columntrid?>-qty'>
                                                                        <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                        <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                    </td>
                                                                   
                                                                <?php
                                                                elseif($columnCombine == 'Color & Size Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <?php
                                                                    $csize = '<div class="row no-gap">';
                                                                    $sizeName =  explode(',', $item['VSIZENAME']);
                                                                    foreach ($sizeName as $skey => $sizeval):
                                                                        $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" readonly value="'.$sizeData[$sizeval].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                    endforeach;
                                                                    $csize .= '</div>';
                                                                    ?>
                                                                    <td class='<?=$columntrid?> colorsizeqty'><?=$csize?></td>
                                                                <?php
                                                                elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <?php
                                                                    if($item["NEXCELUPLOAD"] == 1 && in_array('Country', $customColumn)):
                                                                    ?>
                                                                    <td class='<?=$columntrid?>'><input type="text" style="min-width: 80px; max-width:100%; margin: 0 auto;" class="input-small text-center custom-column-value" value="<?=$data['COUNTRY']?>" readonly></td>
                                                                    <?php
                                                                    endif;
                                                                    ?>
                                                                    <td class='<?=$columntrid?> kimball-cell kimballcelll'><?=$data['KIMBALLNO']?></td>
                                                                    <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                    <?php
                                                                    $csize = '<div class="row no-gap">';
                                                                    $sizeName =  explode(',', $item['VSIZENAME']);
                                                                    foreach ($sizeName as $skey => $sizeval):
                                                                        $csize .= '<div class="cell size-'.$sizeval.' bg-gray border bd-light" style="min-width: 60px;"><input type="text" class="csize-input input-small text-center size-'.$sizeval.'-input colorsizewiseqtyinput" data-sizename="'.$sizeval.'" readonly value="'.$sizeData[$sizeval].'" style="width: 100%;" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualGarmentsQty("'.$parentId.'", $(this));"><input type="hidden" class="csaddition-qty-hidden additionsize-'.$sizeval.'-input" value="'.$workorderOpt->actualQty($sizeData[$sizeval], $item['VADDITIONTYPE'], $item['NADDITIONVALUE']).'"></div>';
                                                                    endforeach;
                                                                    $csize .= '</div>';
                                                                    ?>

                                                                    <td class='<?=$columntrid?> colorsizeqty'>
                                                                        <?=$csize?>
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                                ?>
                                                                    <td class='<?=$columntrid?> color-celll-fixed'><?=$data['COLORNAME']?></td>
                                                                    <td class='<?=$columntrid?> kimballcelll kimball-cell'><?=$data['KIMBALLNO']?></td>
                                                                    <td class='<?=$columntrid?> lot-cell'><?=$data['LOTNO']?></td>
                                                                    <td class='<?=$columntrid?>-qty'>
                                                                        <input type='hidden' class='addition-qty-hidden' value='<?=$workorderOpt->actualQty($data['NROWGARMENTSQTY'], $item['VADDITIONTYPE'], $item['NADDITIONVALUE'])?>'>
                                                                        <input type='text' class='input-small text-center garments-qty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); manualGarmentsQty("<?=$parentId;?>", $(this));' name='garmentsqty' readonly style='max-width:120px; margin: 0 auto;' value='<?=$data['NROWGARMENTSQTY']?>'>
                                                                    </td>
                                                               
                                                                <?php
                                                                elseif($columnCombine == 'Addition'):
                                                                ?>
                                                                    <td class='<?=$columntrid?>' style='position:relative;'>
                                                                        <input type="text" readonly="" class="input-small text-center row-garmentsqtyextra-input" name="garmentsextraqty" ondblclick="inputEnable($(this));" oninput="numberValidate($(this), $(this).val()); manualExtraGarmentsQty('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NROWGARMENTSQTYWITHEXTRA']?>" data-additionval="<?=$item['NADDITIONVALUE']?>" data-additiontype="<?=$item['VADDITIONTYPE']?>">
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'Converter'):
                                                                ?>
                                                                    <td class='<?=$columntrid?>' style='position:relative;'>
                                                                        <input type="text" class="data-copier input-small text-center row-convertion-input" name="convertioninput" oninput="numberValidate($(this), $(this).val()); convertionCalculate('<?=$parentId?>', $(this));" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$data['NCONVERTERQTY']?>" <?=(!empty($item['NCONVERSIONVALUE']) ? 'data-convertionval="'.$item['NCONVERSIONVALUE'].'"' : '');?>   <?=!empty($item['VCONVERTIOMNTYPE']) ? 'data-calinputtype="'.$item['VCONVERTIOMNTYPE'].'"' : '';?>>
                                                                        <a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopyWithCalculation($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                    </td>
                                                                <?php
                                                                elseif($columnCombine == 'PN No.'):
                                                                    
                                                                elseif($columnCombine == 'Order No.'):

                                                                elseif($columnCombine == 'Symbol'):
                                                                     
                                                                elseif($columnCombine == 'Code No.'):
                                                                ?>
                                                                    <td class='<?=$columntrid?>'>
                                                                        <textarea class='code-no-input custom-column-value data-copier' name='codenumber' style='max-width:100%; margin: 0 auto; height: 40px;'><?=$data["CODENO"];?></textarea>
                                                                    </td>
                                                                <?php
                                                                else:
                                                                    $columnname = preg_replace('/[^A-Za-z0-9]/','', strtoupper($columnCombine));
                                                                ?>
                                                                    <td class='<?=$columntrid?>' style="position: relative;">
                                                                        <textarea class="custom-column-value data-copier" style="max-width:100%; margin: 0 auto; height: 40px;"><?=$data["$columnname"];?></textarea><a class="tally copybtn" href="javascript:void(0)" style="position: absolute;top: 1px;right: 1px;" onclick="dataCopy($(this), '<?=$columntrid?>', '<?=$parentId?>');"><span class="mif-copy"></span></a>
                                                                    </td>
                                                            <?php
                                                                endif;
                                                            ?>
                                                               
                                                            <?php
                                                            endforeach;
                                                        ?>
                                                            <td class='totalqty'>
                                                                <div class='row no-gap'>
                                                                    <div class='cell'>
                                                                        <input type='text' readonly class='input-small text-center row-totalqty-input' ondblclick='inputEnable($(this));' oninput='numberValidate($(this), $(this).val()); rowSum("<?=$parentId;?>")' name='rowtotalqty' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$data['NROWTOTALQTY']?>'>
                                                                    </div>
                                                                    <div class='cell text-center bg-light' style='width:10px; line-height:25px; overflow:hidden;'><span class='text-bold'><?=$item['VQTYUNIT']?></span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class='remarks' style='position:relative; overflow: hidden;'>
                                                                <textarea style='max-width: 100%; height:40px; margin: 0 auto;' class='remarks-input data-copier' name='vremarks'><?=$data['VREMARKS']?></textarea>
                                                                <a class='tally copybtn' href='javascript:void(0)' style='position: absolute;top: 1px;right: 1px;' onclick='dataCopy($(this), "remarks", "<?=$parentId;?>")'><span class='mif-copy'></span>
                                                                </a>
                                                                <div class="row-removeBtn ribbed-darkRed" onclick="rowRemover($(this), '<?=$parentId?>');"><span class="mif-cross"></span></div>
                                                            </td>                                                           
                                                        </tr>
                                                    <?php
                                                    endif;
                                                    ?>

                                            <?php
                                                endforeach;
                                            ?>
                                                <tr style="background: #e0f0f1;">
                                                        <td style="font-weight:bold; position:relative;" class="text-right grandQtyCell"><p style="width: 155px;margin: 0px;">Quantity Grand Total</p></td>
                                                    <?php
                                                        foreach ($checkedOption as $ckey => $columnCombine):
                                                            $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower($columnCombine)).$getItemId;
                                                            $columntrid = preg_replace('/-+/', '-', $columntrid);
                                                            if($columnCombine == 'Color Wise Qty' || $columnCombine == 'Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                    <input type="text" class="input-small text-center garmentsgrandtotal" name="garmentsgrandtotal" style="min-width: 80px; max-width:120px; margin: 0 auto;" value="<?=$item["NTOTALGARMENTSQTY"]?>" readonly="">
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Color & Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                <input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>'>
                                                            </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball/Color/Size Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <?php
                                                                if($item["NEXCELUPLOAD"] == 1 && in_array('Country', $customColumn)):
                                                                ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <?php
                                                                endif;
                                                                ?>
                                                                <td class='<?=$columntrid?> kimballcelll'></td>
                                                                <td class='<?=$columntrid?>'></td>
                                                                
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type='hidden' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>'>
                                                                </td>
                                                            <?php
                                                            elseif($columnCombine == 'Kimball & Color Wise Qty'):
                                                            ?>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?> kimballcelll'></td>
                                                                <td class='<?=$columntrid?>'></td>
                                                                <td class='<?=$columntrid?>-qty'>
                                                                   <input type='text' class='input-small text-center garmentsgrandtotal' name='garmentsgrandtotal' style='min-width: 80px; max-width:120px; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTY"]?>' readonly>
                                                                </td>
                                                            <!-- <?php
                                                            //elseif($columnCombine == 'Grmnts Color'):
                                                            ?>
                                                                <td class='<?=$columntrid?> color-celll-fixed' style='position:relative;'></td>
                                                            <?php
                                                            //elseif($columnCombine == 'Grmnts Color/Kimball/Lot'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'></td>
                                                                <td class='<?=$columntrid?> text-center kimball-cell kimballcelll'></td>
                                                                <td class='<?=$columntrid?> text-center'></td> -->
                                                            <?php
                                                            elseif($columnCombine == 'Addition'):
                                                            ?>
                                                                <td class='<?=$columntrid?>' style='position:relative;'>
                                                                    <input type='text' class='input-small text-center garmentsextragrandtotal' name='garmentsextragrandtotal' style='min-width: 80px; max-width:100%; margin: 0 auto;' value='<?=$item["NTOTALGARMENTSQTYWITHEXTRA"]?>' readonly>
                                                                </td>
                                        
                                                            <?php
                                                            else:
                                                                if($columnCombine != 'Symbol'):
                                                            ?> 
                                                                    <td class='<?=$columntrid?>' style="position: relative;"></td>
                                                        <?php   endif;
                                                            endif;

                                                        endforeach;
                                                    ?>
                                                        <td class="grandtotalqty">
                                                            <div class="row no-gap">
                                                                <div class="cell">
                                                                    <input type="text" readonly="" class="input-small text-center grand-totalqty-input" name="grandqty" style="min-width: 80px; max-width:100%; margin: 0 auto;" value="<?=$item["NTOTALQTY"]?>">
                                                                </div>
                                                                <div class="cell text-center bg-light" style="width:10px; line-height:25px; overflow:hidden;">
                                                                    <span class="text-bold grandunit"><?=$item['VQTYUNIT']?></span>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class='remarks' style='position:relative;'></td>
                                                        <?php
                                                            if(in_array('Symbol', $checkedOption)):
                                                                $columntrid = preg_replace("/^'|[^A-Za-z0-9\'-]|'$/", "-", strtolower('Symbol')).$getItemId;
                                                                $count = $accessoriesModel->getData("SELECT nid FROM accessories_images WHERE nworkorderitemid = $itemId");
                                                                $counter = is_array($count) ? 4 - count($count) : 4;
                                                            ?>
                                                                <td class="<?=$columntrid?> text-center"></td>
                                                            <?php
                                                            endif;
                                                            ?>
                                                    </tr>
                                                
                                                <?php
                                                endif;
                                                ?>
                                            
                                            </table>
                                            <?php
                                            $attachment = $accessoriesModel->getData("SELECT nid, bimage, vfileformate FROM accessories_images WHERE nworkorderitemid = $itemId");
                                            if(is_array($attachment)):
                                            ?>
                                            <table class="subcompact cell-border table searchordertable" style="margin-top: 0.5rem; margin-bottom: 0.5rem;">
                                                <tr>
                                                    <td style="background: #e0f0f1; width:115px; font-weight:bold;" class="text-center">Attachment(s)</td>

                                                <?php
                                                foreach ($attachment as $key => $images):
                                                    $img = $images['BIMAGE']->load();
                                                ?>
                                                    <td style="padding-left: 4px; text-align: center; background: #e0f0f1;"><img src="data:<?=$images['VFILEFORMATE']?>;base64,<?=base64_encode($img)?>" style="max-width: 80px;margin: 0 auto;">
                                                        <a href="javascript:void(0)" class="tally alert" onclick="deleteAttchment(<?=$images['NID']?>, $(this), '<?=$parentId?>')" style="position: absolute; line-height: 20px;">Delete <span class="mif-bin icon"></span></a>
                                                    </td>
                                                <?php     
                                                endforeach;
                                                ?>
                                                </tr>
                                            </table>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                            <?php
                                            if($item['VDATAFILLTYPE'] == 'manual-data'):
                                            ?>
                                                <input type="hidden" class="addrowdisabler" value="<?=$rowDisable;?>">
                                            <?php
                                            endif;
                                        endforeach;
                                        ?>
                                    </div>
                                </div>
                                <div class='addition-popup' style="display: none;">
                                    <div class='addittion-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="tableclass">
                                        <input type="hidden" name="columnClass" value="" class="columnclass">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Add Extra Garments Quantity</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <input type='text' class='input-small text-center addition-qty' name='addition' oninput="numberValidate($(this), $(this).val());" style='margin: 0 auto;' value='0'>
                                            </div>
                                            <div class='cell'>
                                                <select name='additiontype' class='addition-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='parcent'>(%) of total garments</option>
                                                    <option value='qty'>Pcs. Added</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success addition-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success addition-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='converter-popup' style="display: none;">
                                    <div class='converter-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="contableclass">
                                        <input type="hidden" name="columnClass" value="" class="concolumnclass">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Apply Quantity Conversion Rules</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 0px;padding: 6px;">
                                            <div class='cell-12'>
                                                <select name='convertiontype' class='convertion-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='' id='convertiontype1' data-caltype='multiply'></option>
                                                     <option value='' id='convertiontype2' data-caltype='divided'></option>
                                                </select>
                                            </div>
                                            <div class="cell-12 extraCalAdded mt-1">
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center; margin-top: -4px;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success convertion-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success converter-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='datafill-popup' style="display: none;">
                                    <div class='datafill-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="datafilltableclass">
                                        <input type="hidden" name="columnClass" value="" class="datafillcolumnclass">
                                        <input type="hidden" name="dataname" value="" class="datafilldataname">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Data Fill Type</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <select name='datafilltype' class='datafill-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='Fixed'>Fixed</option>
                                                    <option value='Manuall'>Manually Select</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='datafill-popup1' style="display: none;">
                                    <div class='datafill-popup1-container'>
                                        <input type="hidden" name="tableClass" value="" class="datafilltableclass1">
                                        <input type="hidden" name="columnClass" value="" class="datafillcolumnclass1">
                                        <input type="hidden" name="dataname" value="" class="datafilldataname1">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Data Fill Type</p>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px;">
                                            <div class='cell-12'>
                                                <select name='datafilltype' class='datafill1-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='Fixed'>Fixed</option>
                                                    <option value='Manuall'>Manually Select</option>
                                                </select>
                                            </div>
                                            <div class='cell-12 repeater-content'>
                                                <label>Data Repeat</label>
                                                 <select name='datarepeat' class='datarepeat-type' style='height: 26px;line-height: 24px;font-size: 14px;padding: 0px 0px;'>
                                                    <option value='1'>1 time</option>
                                                    <option value='2'>2 times</option>
                                                    <option value='3'>3 times</option>
                                                    <option value='4'>4 times</option>
                                                    <option value='5'>5 times</option>
                                                    <option value='6'>6 times</option>
                                                    <option value='7'>7 times</option>
                                                    <option value='8'>8 times</option>
                                                    <option value='9'>9 times</option>
                                                    <option value='10'>10 times</option>
                                                    <option value='11'>11 times</option>
                                                    <option value='12'>12 times</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-customize-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success datafill-customize-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class='excel-popup' style="display: none;">
                                    <div class='excel-popup-container'>
                                        <input type="hidden" name="tableClass" value="" class="exceltableclass">
                                        <input type="hidden" name="dataid" value="" class="exceldataid">
                                        <input type="hidden" name="dataname" value="" class="excelitemname">
                                        <input type="hidden" name="dataunit" value="" class="excelitemunit">
                                        <p style="margin-top: 0px;background: #006d77;color: #fff;padding: 0px 6px 5px 6px;">Import File (.xlsx or .csv)</p>
                                        <div class='row no-gap' style="margin: 0 auto;margin-top: 15px;padding: 6px;">
                                            <div class='cell'>
                                                <input type="file" data-role="file" data-mode="drop" class="excel-file" onchange="checkValidFile($(this));">
                                            </div>
                                        </div>
                                        <div class='row no-gap' style="margin: 0 auto;padding: 6px; text-align: center;">
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success excel-setting border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-done icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                                    <span class="caption">Continue</span>
                                                </a>
                                            </div>
                                            <div class="cell">
                                                <a href="javscript:void(0)" class="image-button success excel-cancel bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                                    <span class='mif-cross icon' style="height: 22px; line-height: 22px; font-size: .7rem; width: 23px;"></span>
                                                    <span class="caption">Cancel</span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell-sm-12 extra-notes" style="margin-top: -10px;">
                                        <table class='subcompact cell-border table searchordertable extraNotes-table' style='margin-top: 0.5rem; margin-bottom: 0.5rem;'>
                                            <tr>
                                                <td style='background: #e0f0f1; width:130px; font-weight:bold; text-align:center;'>Note</td>
                                                <td class='text-center bg-white'>
                                                    <textarea data-role='textarea' width='100%' name='extranotes' class='extranotes'><?=$masterData[0]['VEXTRANOTES']?></textarea>
                                                </td>                                       
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row submit-section text-center d-flex flex-justify-center">
                                    <button type="button" name="workorder-update" data-type="<?=$masterData[0]['VSTATUS']?>" class="workorder-update image-button border bd-dark-hover secondary mr-2">
                                    <span class='mif-checkmark icon'></span>
                                    <span class="caption text-bold">Update Work Order</span>
                                    </button>
                                    <a  href="workorder.php?page=edit&id=<?=$id?>" class="image-button warning ml-2-md border bd-dark-hover">
                                    <span class='mif-refresh icon'></span>
                                    <span class="caption text-bold" >Refresh</span>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                else:
                    $pageOpt->redirectWithscript($pageOpt->previousPageUrl(), 'Invalid work order id!');
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