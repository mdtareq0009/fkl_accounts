<!DOCTYPE html>
<?php include_once('inc/head.php');
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    $accessoriesModel = new accessoriescrud($db->con);
    $userid = $auth->loggedUserId();
?>
<body class="m4-cloak h-vh-100">
   <div class="preloader" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, 0.8); left: 0;">
        <div data-role="activity" data-type="square" data-style="color" style="position: relative;top: 50%; left: 50%;"></div>
    </div>
    <div data-role="navview" data-toggle="#paneToggle" data-expand="xxl" data-compact="xl" data-active-state="true">
        <?php include_once('inc/navigation.php'); ?>
        <div class="navview-content h-100">
            <?php include_once('inc/topbar.php'); ?>
            <div class="content-inner h-100" style="overflow-y: auto">
                <div class="row border-bottom bd-lightGray pl-1 mr-1 ribbed-lightGray" style="margin-left: 0px;">
                    <div class="cell-md-4">
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="i" data-page="d" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-user"></span></span> Users</h4>
                    </div>
                    <div class="cell-md-8">
                        <a href="<?=$pageOpt->previousPageUrlCommon()?>" class="image-button success place-right-md place-right bg-red bg-darkRed-hover border bd-dark-hover" style="height: 22px;">
                            <span class='mif-arrow-left icon' style="height: 22px; line-height: 22px; font-size: .9rem; width: 23px;"></span>
                            <span class="caption text-bold">Back</span>
                        </a>
                    </div>
                </div>
                <?php
                if($_GET['page'] == 'usersdetails'):
                $getID = $_GET['fklid'];
                $userinfo = $accessoriesModel->getUser($getID);
                ?>
                <div class="row-fluid">
                    <div class="cell-md-12">
                        <div data-role="panel" data-title-caption="User Details" data-collapsible="false" data-title-icon="<span class='mif-list'></span>">
                        <?php if(is_array($userinfo)): ?>
                            <div class="user-info">
                                <div style="width: 175px;">
                                    <?=$userinfo['picture']?>
                                </div>
                                    <p><b>Name</b> - <?=$userinfo['name']?><br>
                                    <span><b>Designation</b> - <?=$userinfo['designation']?></span><br><span><b>Department</b> - <?=$userinfo['department']?></span><br><span><b>User Role</b> - <?=ucwords($userinfo['role'])?></span>
                                    </p>
                                </div>
                        <?php else: ?>
                            <div class="remark alert">
                                You do not have permission to see this user information or this FKLID is invalid!
                            </div>
                            <div class="user-info" style="filter: blur(5px);">
                                    <img src="images/fake-image.jpg">
                                    <p><b>Name</b> - User Name Here<br>
                                    <span><b>Designation</b> - User Designation</span><br><span><b>Department</b> - User Department</span><br><span><b>User Role</b> - User Role</span>
                                    </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
           <?php
           elseif($_GET['page'] == 'change-password'):
            ?>
            <div class="row-fluid">
                <div class="cell-md-12">
                    <div data-role="panel" data-title-caption="Change Password" data-collapsible="false" data-title-icon="<span class='mif-cog'></span>" class="password-reset-form">

                    <div class="row">
                        <div class="cell-sm-7 p-2">
                            <div class="remark primary">
                               <b>Remember</b>, if you change your password from here, the password of your all ERP modules will be updated.
                            </div>
                            <form method="POST" action="" class="password-reset" autocomplete="off" onsubmit="changePassword('password-reset', 'users');">
                                <input type="hidden" name="csrf" class="csrf" value="<?=$db->csrfToken()?>">
                                <input type="hidden" name="formName" value="change-password">
                                <div class="row mb-2">
                                    <label class="cell-sm-3">Current Password<span class="fg-red">*</span></label>
                                    <div class="cell-sm-9 required-cell">
                                        <input type="password"  name="cpass" value="" class="required-field input-small" placeholder="Enter Current Password" autocomplete="off">
                                        <span class="invalid_feedback">Current password is required.</span>
                                    </div>
                                </div>
                                 <div class="row mb-2">
                                    <label class="cell-sm-3">New Password<span class="fg-red">*</span></label>
                                    <div class="cell-sm-9 required-cell">
                                        <input type="password"  name="npass" value=""  class="newpassword required-field input-small" placeholder="Enter New Password">
                                        <span class="invalid_feedback">New password is required.</span>
                                    </div>
                                </div>
                                 <div class="row mb-2">
                                    <label class="cell-sm-3">Confirm Password<span class="fg-red">*</span></label>
                                    <div class="cell-sm-9 required-cell">
                                        <input type="password"  name="conpass" value=""  class="newpasswordconfirm required-field input-small" placeholder="Re-enter New Password">
                                        <span class="invalid_feedback">Confirm password is required.</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="cell">
                                        <button type="submit" class="image-button border bd-dark-hover secondary mr-2">
                                            <span class='mif-done icon'></span>
                                            <span class="caption text-bold">Update</span>
                                        </button>
                                        <!-- <button type="submit" class="button primary "><span class="icon"><span class="mif-done"></span></span> Update</button> -->
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="cell-sm-4 p-3">
                            <div class="user-info">
                                <?php $userInfo = $auth->loggedUser(); ?>
                                <div style="width: 150px;">
                                    <?=$userInfo['picture']?>
                                </div>
                                <p><b>Name</b> - <?=$userInfo['name']?><br>
                                <span><b>Designation</b> - <?=$userInfo['designation']?></span><br><span><b>Department</b> - <?=$userInfo['department']?></span><br><span><b>User Role</b> - <?=ucwords($userInfo['role'])?></span>
                                </p>

                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>
        <?php
        else:
            $pageOpt->redirectWithscript('index.php', 'Requested page is invalid!');
        endif;
        ?>
    </div>
</div>
</div>
</div>
<?php include_once('inc/footer.php'); ?>
</body>
<?php endif; ?>
</html>