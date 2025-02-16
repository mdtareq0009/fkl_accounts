<!DOCTYPE html>
<?php
include_once('inc/head.php');
use accessories\accessoriescrud;

if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyUserPermission('user permission', 1)):
    $accessoriesModel = new accessoriescrud($db->con);
    $userid = $auth->loggedUserId();
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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="setting" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-user-check"></span></span> Users Permission</h4>
                    </div>
                    <div class="cell-md-8">
                        <?php
                        if(!empty($pageOpt->previousPageUrl())): ?>
                            <a href="<?=$pageOpt->previousPageUrl()?>" class="image-button success place-right-md place-right bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                                <span class='mif-arrow-left icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                <span class="caption text-bold">Back</span>
                            </a>
                            <?php
                            if($_GET['page'] != 'create-new'):
                            ?>
                            <a href="user-permission.php?page=create-new" class="image-button success place-right-md border mr-2 place-right bd-dark-hover" style="height: 22px;">
                                <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                <span class="caption text-bold">Add New</span>
                            </a>
                            <?php
                            endif;
                            ?>
                        <?php
                        else: ?>
                            <a href="user-permission.php?page=create-new" class="image-button success place-right-md border place-right bd-dark-hover" style="height: 22px;">
                                <span class='mif-plus icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                                <span class="caption text-bold">Add New</span>
                            </a>
                   
                        <?php
                        endif ?>
                    </div>
                </div>
                <?php
                /*================================================
                
                ================================================*/
                if($_GET['page'] == 'create-new'):
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
                        <div data-role="panel" data-title-caption="Add New User" data-title-icon="<span class='mif-plus'></span>" class="userpermission-form-panel" data-collapsible="false">

                            <div class="p-1">
                                <form method="POST" action="" class="userformsubmit">
                                    <div class="row">
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Enter FKLID<span class="fg-red">*</span></label>
                                                <input type="text" required class="input-small userfinder-input required-field" name="VFKLID" placeholder="Enter FKLID">
                                                <span class="user-finder fg-green"></span>
                                                <span class="invalid_feedback">FKLID is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Enter Manager/Asst. Manger ID</label>
                                                <input type="text" disabled class="input-small manager-finder-input" name="VMANAGERID" placeholder="Enter Manager/Asst. Manager ID">
                                                <span class="manager-finder fg-green"></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Enter P.O. Prefix<span class="fg-red">*</span></label>
                                                <input type="text" disabled required class="input-small required-field poprefix" name="VPURCHASECODEPREFIX" placeholder="Ex. A or B or C">
                                                <span class="invalid_feedback">P.O. Prefix is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Select Role<span class="fg-red">*</span></label>
                                                 <select data-role="select" name="VROLE" required disabled class="input-small userrole"  data-filter="false">
                                                    <option value="user">User</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="super admin">Super Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                   

<!-- =====================================================Buyer Wise Manager Permission============================================= -->
                                    <div class="cell-md-4">
                                        <label>Select Buyer For Manager<span class="fg-red">*</span></label>
                                        <select data-role="select" required multiple name="BUYER_NMAE[]" class="input-small">
                                            <optgroup label="Buyer Name">
                                                <?php 
                                                $buyers = $accessoriesModel->getData("select nbuyerid,vname from erp.mer_buyername");
                                                foreach($buyers as $buyer){ ?>
                                                <option value="<?php echo $buyer['NBUYERID']; ?>"><?php echo $buyer['VNAME']; ?></option>
                                                <?php } ?>
                                            </optgroup>
                                        </select>
                                    </div>

<!-- ===================================================Buyer Wise Manager Permission=========================================== -->

                                    </div>
                                    <div class="row permission-table" style="display: none;">
                                        <table class="table row-border cell-border permissiontable">
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-meter"></span> Dashboard</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Dashboard                                                    
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;"><input type="checkbox" data-role="checkbox" name="VDASHBOARD[]" data-style="2" data-caption="Received & Delivery Ledger" value="1" class="permission-checkbox-common super-adminpermission">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-assignment"></span> Work Order</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Drafts Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="Create" value="1" class="permission-checkbox-common super-adminpermission">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="Edit" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="Delete" value="5">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Published Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Edit" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Delete" value="5">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Print" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Work order approval" value="7">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="RollBack" value="10">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Approved Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="Print" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="Work order accept" value="8">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="RollBack" value="10">
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Accepted Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="Print" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="Re-issue" value="9">
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    All Work order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VALLWORKORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VALLWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VALLWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-cogs"></span> Settings</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Suppliers
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Groups
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Sub Groups
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Materials Unit
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Goods Options
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Goods
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Users Permission
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission only-super-admin" disabled type="checkbox" data-role="checkbox" name="VUSERPERMISSION[]" data-style="2" data-caption="User Permission" value="1">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-bin"></span> Trash</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Trash
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VTRASH[]" data-style="2" data-caption="Trash" value="1">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="row submit-section text-center d-flex flex-justify-center mt-2">
                                        <button type="button" class="image-button border permission-data-submit bd-dark-hover success">
                                            <span class='mif-checkmark icon'></span>
                                            <span class="caption text-bold">Save</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                elseif($_GET['page'] == 'edit'):
                    $id = isset($_GET['id']) ? $_GET['id'] : 0;
                    if($accessoriesModel->checkDataExistence("SELECT nid FROM ACCESSORIES_USERSPERMISSION WHERE nid = $id") == 'exist'):
                        $paermissionData = $accessoriesModel->getData("SELECT nid, vfklid, users.vempname AS username, vmanagerid, manager.vempname AS manager, vpurchasecodeprefix, vrole, vdraftworkorder, vpublishedworkorder, vapprovedwororder, vacceptedworkorder, vallworkorder, vdashboard, vsuppliers, vgroups, vsubgroups, vmou, vgoodsoptions, vgoods, vuserpermission, vtrash,buyer_nmae FROM ACCESSORIES_USERSPERMISSION LEFT JOIN hrm_employee users ON users.vemployeeid = vfklid LEFT JOIN hrm_employee manager ON manager.vemployeeid = vmanagerid WHERE nid = $id");
                        $draftWoPermission      = explode(',', $paermissionData[0]['VDRAFTWORKORDER']);
                        $publishWoPermission    = explode(',', $paermissionData[0]['VPUBLISHEDWORKORDER']);
                        $approvedWoPermission   = explode(',', $paermissionData[0]['VAPPROVEDWORORDER']);
                        $acceptedWoPermission   = explode(',', $paermissionData[0]['VACCEPTEDWORKORDER']);
                        $allWoPermission        = explode(',', $paermissionData[0]['VALLWORKORDER']);
                        $dashboardPermission    = explode(',', $paermissionData[0]['VDASHBOARD']);
                        $suppliersPermission    = explode(',', $paermissionData[0]['VSUPPLIERS']);
                        $groupPermission        = explode(',', $paermissionData[0]['VGROUPS']);
                        $subgroupPermission     = explode(',', $paermissionData[0]['VSUBGROUPS']);
                        $mouPermission          = explode(',', $paermissionData[0]['VMOU']);
                        $goodsoptionPermission  = explode(',', $paermissionData[0]['VGOODSOPTIONS']);
                        $goodsPermission        = explode(',', $paermissionData[0]['VGOODS']);
                        $userPermission         = explode(',', $paermissionData[0]['VUSERPERMISSION']);
                        $trashPermission        = explode(',', $paermissionData[0]['VTRASH']);
                        $buyerPermission        = explode(',', $paermissionData[0]['BUYER_NMAE']);
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
                        <div data-role="panel" data-title-caption="Edit User" data-title-icon="<span class='mif-pencil'></span>" class="userpermission-form-panel" data-collapsible="false">
                            <div class="p-1">
                                <form method="POST" action="" class="userformupdate">
                                    <div class="row">
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Enter FKLID<span class="fg-red">*</span></label>
                                                <input type="text" required class="input-small required-field" readonly name="VFKLID" placeholder="Enter FKLID" value="<?=$paermissionData[0]['VFKLID']?>">
                                                <span class="user-finder fg-green"><?=$paermissionData[0]['USERNAME']?></span>
                                                <span class="invalid_feedback">FKLID is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Enter Manager/Asst. Manger ID</label>
                                                <input type="text" class="input-small manager-finder-input" name="VMANAGERID" placeholder="Enter Manager/Asst. Manager ID" value="<?=$paermissionData[0]['VMANAGERID']?>">
                                                <span class="manager-finder fg-green"><?=$paermissionData[0]['MANAGER']?></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Enter P.O. Prefix<span class="fg-red">*</span></label>
                                                <input type="text" required class="input-small required-field poprefix" name="VPURCHASECODEPREFIX" placeholder="Ex. A or B or C" value="<?=$paermissionData[0]['VPURCHASECODEPREFIX']?>">
                                                <span class="invalid_feedback">P.O. Prefix is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-3">
                                            <div class="form-group">
                                                <label>Select Role<span class="fg-red">*</span></label>
                                                 <select data-role="select" name="VROLE" required class="input-small userrole"  data-filter="false">
                                                    <option value="user" <?=$paermissionData[0]['VROLE'] == 'user' ? 'selected' : '';?>>User</option>
                                                    <option value="admin" <?=$paermissionData[0]['VROLE'] == 'admin' ? 'selected' : '';?>>Admin</option>
                                                    <option value="super admin" <?=$paermissionData[0]['VROLE'] == 'super admin' ? 'selected' : '';?>>Super Admin</option>
                                                </select>
                                            </div>
                                        </div>

        <!-- =====================================================Buyer Wise Manager Permission============================================= -->
                                    <div class="cell-md-4">
                                        <label>Select Buyer For Manager<span class="fg-red">*</span></label>
                                        <select data-role="select" required multiple name="BUYER_NMAE[]" class="input-small">
                                            <optgroup label="Buyer Name">
                                                <?php 
                                                $buyersName = $accessoriesModel->getData("select nbuyerid,vname from erp.mer_buyername");
                                                is_array($buyersName);
                                                foreach($buyersName as $buyerName){
                                                    if(in_array($buyerName['NBUYERID'],$buyerPermission))
                                                    $str_flag="selected";
                                                    else 
                                                    $str_flag="";
                                                    ?>
                                                <option value="<?php echo $buyerName['NBUYERID']; ?>" <?php echo $str_flag; ?>><?php echo $buyerName['VNAME'] ?> </option>
                                                <?php } ?>
                                            </optgroup>
                                        </select>
                                    </div>
    <!-- ===================================================Buyer Wise Manager Permission=========================================== -->

                                    </div>
                                    <div class="row permission-table">
                                        <table class="table row-border cell-border permissiontable">
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-meter"></span> Dashboard</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Dashboard                                                    
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;"><input type="checkbox" data-role="checkbox" name="VDASHBOARD[]" data-style="2" data-caption="Received & Delivery Ledger" <?=(in_array(1, $dashboardPermission) ? 'checked' : '' )?> value="1" class="permission-checkbox-common super-adminpermission">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-assignment"></span> Work Order</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Drafts Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input type="checkbox" data-role="checkbox" <?=(in_array(1, $draftWoPermission) ? 'checked' : '' )?> name="VDRAFTWORKORDER[]" data-style="2" data-caption="Create" value="1" class="permission-checkbox-common super-adminpermission">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $draftWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $draftWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $draftWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="Edit" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(5, $draftWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VDRAFTWORKORDER[]" data-style="2" data-caption="Delete" value="5">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Published Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Edit" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(5, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Delete" value="5">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(6, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Print" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(7, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="Work order approval" value="7">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(10, $publishWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VPUBLISHEDWORKORDER[]" data-style="2" data-caption="RollBack" value="10">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Approved Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $approvedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $approvedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $approvedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(6, $approvedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="Print" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(8, $approvedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="Work order accept" value="8">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(10, $approvedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VAPPROVEDWORORDER[]" data-style="2" data-caption="RollBack" value="10">
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Accepted Work Order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $acceptedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $acceptedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $acceptedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(6, $acceptedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="Print" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(9, $acceptedWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VACCEPTEDWORKORDER[]" data-style="2" data-caption="Re-issue" value="9">
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    All Work order
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $allWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VALLWORKORDER[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $allWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VALLWORKORDER[]" data-style="2" data-caption="View List" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $allWoPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VALLWORKORDER[]" data-style="2" data-caption="View Details" value="3">
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-cogs"></span> Settings</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Suppliers
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $suppliersPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $suppliersPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $suppliersPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $suppliersPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUPPLIERS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Groups
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $groupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $groupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $groupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $groupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGROUPS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Sub Groups
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $subgroupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $subgroupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $subgroupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $subgroupPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VSUBGROUPS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Materials Unit
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $mouPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $mouPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $mouPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $mouPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VMOU[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Goods Options
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $goodsoptionPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $goodsoptionPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $goodsoptionPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $goodsoptionPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODSOPTIONS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Goods
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $goodsPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="Create" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $goodsPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="Edit" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $goodsPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="Delete" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $goodsPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGOODS[]" data-style="2" data-caption="View List" value="4">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Users Permission
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission only-super-admin" <?=(in_array(1, $userPermission) ? 'checked' : '' )?> <?=($paermissionData[0]['VROLE'] == 'super admin' ? '' : 'disabled' )?> type="checkbox" data-role="checkbox" name="VUSERPERMISSION[]" data-style="2" data-caption="User Permission" value="1">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr class="fg-white" style="background: #1a404d;">
                                                <th colspan="2" class="text-left" style="padding: 1px 4px;"><span class="mif-bin"></span> Trash</th>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Trash
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $trashPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VTRASH[]" data-style="2" data-caption="Trash" value="1">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="row submit-section text-center d-flex flex-justify-center mt-2">
                                        <button type="button" class="image-button border permission-data-update bd-dark-hover secondary">
                                            <span class='mif-checkmark icon'></span>
                                            <span class="caption text-bold">Update</span>
                                        </button>
                                        <a href="user-permission.php?page=edit&id=<?=$id?>" class="image-button border bd-dark-hover warning ml-2">
                                            <span class='mif-refresh icon'></span>
                                            <span class="caption text-bold">Refresh</span>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
                else:
                    $pageOpt->redirectWithscript($pageOpt->previousPageUrl(), 'Invalid user id!');
                endif;
            elseif($_GET['page'] == 'all-users'):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="All Users" data-collapsible="false" data-title-icon="<span class='mif-users'></span>">
                        
                        <div class="ml-1 mr-1">
                            <table 
                                class="table striped table-border cell-border subcompact mt-1 accessories-table-common"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7 cell-sm-6"
                                data-cls-rows-count="cell-md-5 cell-sm-6"
                                data-rows="15"
                                data-rows-steps="-1, 18, 30, 50, 100, 150"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="all-users-table"
                                data-source="data/all-users.php"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from $1 to $2 of $3 User(s)"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                            >
                               
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php
	// echo "<pre>";
	// print_r($data);
	// exit();

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