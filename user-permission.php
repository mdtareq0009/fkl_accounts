<!DOCTYPE html>
<?php
include_once('inc/head_login.php');
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
        <?php include_once('inc/navigation_last.php'); ?>
        <div class="navview-content h-100">
            <?php include_once('inc/topbar_lo.php');?>
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
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Enter FKLID<span class="fg-red">*</span></label>
                                                <input type="text" required class="input-small userfinder-input required-field" name="VFKLID" placeholder="Enter FKLID">
                                                <span class="user-finder fg-green"></span>
                                                <span class="invalid_feedback">FKLID is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Enter Manager/Asst. Manger ID</label>
                                                <input type="text" disabled class="input-small manager-finder-input" name="VMANAGERID" placeholder="Enter Manager/Asst. Manager ID">
                                                <span class="manager-finder fg-green"></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Enter P.O. Prefix<span class="fg-red">*</span></label>
                                                <input type="text" disabled required class="input-small required-field poprefix" name="VPURCHASECODEPREFIX" placeholder="Ex. A or B or C">
                                                <span class="invalid_feedback">P.O. Prefix is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Subordinate Id</label>
                                                <!-- <input type="text" class="input-small subordinate-finder-input" name="VSUBORDINATEID" value="<?php //echo $paermissionData[0]['SUBORDINATE']?>" placeholder="Subordinate Id:"> -->
                                                 <textarea name="VSUBORDINATEID" id="" placeholder="Subordinate Id:"></textarea>
                                                <span class="subordinate-finder fg-green"></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Subordinate-2 Id</label>
                                                <input type="text" class="input-small subordinate-finder-input-2" name="VSUBORDINATE_2" value="" placeholder="Subordinate-2 Id:">
                                                <span class="subordinate-finder-2 fg-green"></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Select Role<span class="fg-red">*</span></label>
                                                 <select data-role="select" name="VROLE" required disabled class="input-small userrole"  data-filter="false">
                                                    <option value="user">User</option>
                                                    <option value="admin">Admin</option>
                                                    <option value="super admin">Super Admin</option>
                                                </select>
                                            </div>
                                        </div>
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
                                        
                                            
                                            
                                            
                                        
                                            
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Work Order SSP
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Checked" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Merchandiser" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Manager" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Gm" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Purchase" value="5">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Audit" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Approve" value="9">
                                                            </td>
                                                        
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Print" value="10">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Details" value="11">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Management" value="12">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Top Sheet Create" value="7">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Top Sheet View" value="13">
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
                                                    Users Permission test
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
                                        
                                            <!-- <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Checked
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Checked" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Merchandiser" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Manager" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Gm" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Purchase" value="5">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Approve" value="6">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr> -->
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
                        $paermissionData = $accessoriesModel->getData("SELECT nid, vfklid, 
                        users.VEMPNAME AS username, vmanagerid, 
                        manager.VEMPNAME AS manager, vsubordinateid, vsubordinate_2 as vsubordinateid_2, 
                        subordinate.VEMPNAME AS subordinate, 
                        subordinate_2.VEMPNAME AS subordinate_2, 
                        vpurchasecodeprefix, vrole, vdashboard, vuserpermission, vchecked,vcostsheet 
                        FROM ACCESSORIES_USERSPERMISSION 
                        LEFT JOIN erp.hrm_employee users ON users.VEMPLOYEEID = vfklid 
                        LEFT JOIN erp.hrm_employee manager ON manager.VEMPLOYEEID = vmanagerid 
                        LEFT JOIN erp.hrm_employee subordinate ON subordinate.VEMPLOYEEID = vsubordinateid 
                        LEFT JOIN erp.hrm_employee subordinate_2 ON subordinate_2.VEMPLOYEEID = vsubordinate_2 
                        WHERE nid = $id");
                        $dashboardPermission    = explode(',', $paermissionData[0]['VDASHBOARD']);
                        $userPermission         = explode(',', $paermissionData[0]['VUSERPERMISSION']);
                        $checkedPermission      = explode(',', $paermissionData[0]['VCHECKED']);
                        $costsheet              = explode(',', $paermissionData[0]['VCOSTSHEET']);

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
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Enter FKLID<span class="fg-red">*</span></label>
                                                <input type="text" required class="input-small required-field" readonly name="VFKLID" placeholder="Enter FKLID" value="<?=$paermissionData[0]['VFKLID']?>">
                                                <span class="user-finder fg-green"><?=$paermissionData[0]['USERNAME']?></span>
                                                <span class="invalid_feedback">FKLID is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Enter Manager/Asst. Manger ID</label>
                                                <input type="text" class="input-small manager-finder-input" name="VMANAGERID" placeholder="Enter Manager/Asst. Manager ID" value="<?=$paermissionData[0]['VMANAGERID']?>">
                                                <span class="manager-finder fg-green"><?=$paermissionData[0]['MANAGER']?></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Enter P.O. Prefix<span class="fg-red">*</span></label>
                                                <input type="text" required class="input-small required-field poprefix" name="VPURCHASECODEPREFIX" placeholder="Ex. A or B or C" value="<?=$paermissionData[0]['VPURCHASECODEPREFIX']?>">
                                                <span class="invalid_feedback">P.O. Prefix is required.</span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Subordinate Id</label>
                                                <!-- <input type="text" class="input-small subordinate-finder-input" name="VSUBORDINATEID" value="<?php //echo $paermissionData[0]['VSUBORDINATEID']?>" placeholder="Subordinate Id:"> -->
                                                 <textarea name="VSUBORDINATEID" class="" id="" placeholder="Subordinate Id:"><?php echo $paermissionData[0]['VSUBORDINATEID']?></textarea>
                                                <span class="subordinate-finder fg-green"><?=$paermissionData[0]['SUBORDINATE']?></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Subordinate-2 Id</label>
                                                <input type="text" class="input-small subordinate-finder-input-2" name="VSUBORDINATE_2" value="<?=$paermissionData[0]['VSUBORDINATEID_2']?>" placeholder="Subordinate-2 Id:">
                                                <span class="subordinate-finder-2 fg-green"><?=$paermissionData[0]['SUBORDINATE_2']?></span>
                                            </div>
                                        </div>
                                        <div class="cell-sm-6 cell-md-2">
                                            <div class="form-group">
                                                <label>Select Role<span class="fg-red">*</span></label>
                                                 <select data-role="select" name="VROLE" required class="input-small userrole"  data-filter="false">
                                                    <option value="user" <?=$paermissionData[0]['VROLE'] == 'user' ? 'selected' : '';?>>User</option>
                                                    <option value="admin" <?=$paermissionData[0]['VROLE'] == 'admin' ? 'selected' : '';?>>Admin</option>
                                                    <option value="super admin" <?=$paermissionData[0]['VROLE'] == 'super admin' ? 'selected' : '';?>>Super Admin</option>
                                                </select>
                                            </div>
                                        </div>
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
                                            
                                    
                                            
                                        
                                            <!-- <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                   Gate Pass
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Gate Pass" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Receive" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Inventory" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Dept. Head" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(5, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Approve" value="5">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(6, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Create" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(7, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="View" value="7">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(8, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Security Pass" value="8">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(9, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Basic Entry" value="9">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(10, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Godown" value="10">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(11, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Item" value="11">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(12, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Supplier" value="12">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(13, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Party" value="13">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(14, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Gatepass Check" value="14">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(15, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Report" value="15">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(16, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Merchandising Report" value="16">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(17, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Audit" value="17">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(18, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Tally Security" value="18">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(19, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Delete" value="19">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(20, $gatepassPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VGATEPASS[]" data-style="2" data-caption="Create Driver" value="20">
                                                            </td>
                                                            
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr> -->
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Work Order SSP
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Published Check" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Merchandiser" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Manager" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Gm" value="4">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(5, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Purchase" value="5">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(6, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Audit" value="6">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(9, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Approve" value="9">
                                                            </td>
                                                        
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(7, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Top Sheet Create" value="7">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(13, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Top Sheet View" value="13">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(10, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Print" value="10">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(11, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Details" value="11">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(12, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Management" value="12">
                                                            </td>
                                                            <!-- <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?php  //(in_array(8, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Cost Sheet" value="8">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?php //(in_array(9, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Cost Sheet Settings" value="9">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?php //(in_array(10, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption="Accept" value="10">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?php //(in_array(11, $checkedPermission) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCHECKED[]" data-style="2" data-caption=" Approve" value="11">
                                                            </td> -->
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 250px; padding: 1px 4px;">
                                                    Cost Sheet
                                                </td>
                                                <td style="padding: 1px 4px;">
                                                    <table>
                                                        <tr>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(1, $costsheet) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCOSTSHEET[]" data-style="2" data-caption="Cost Sheet" value="1">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(2, $costsheet) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCOSTSHEET[]" data-style="2" data-caption="Cost Settings" value="2">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(3, $costsheet) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCOSTSHEET[]" data-style="2" data-caption="Accept" value="3">
                                                            </td>
                                                            <td style="padding: 1px 4px;">
                                                                <input class="permission-checkbox-common super-adminpermission" <?=(in_array(4, $costsheet) ? 'checked' : '' )?> type="checkbox" data-role="checkbox" name="VCOSTSHEET[]" data-style="2" data-caption=" Approve" value="4">
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
            else:
                $pageOpt->redirectWithscript($pageOpt->pageFirst(), 'Requested page is invalid!');
            endif;
            ?>
        </div>
    </div>
</div>
</div>
<?php include_once('inc/footer_login.php'); ?>
</body>
<?php
    else:
        $auth->redirect403();
    endif;
endif;
?>
</html>