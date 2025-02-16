
<?php include_once('inc/head.php');
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyUserPermission('dashboard', 1)):
    $accessoriesModel = new accessoriescrud($db->con);
    $userid = $auth->loggedUserId();
?>
<style>
    .btn-app{
        border-radius: 3px;
        /* background-color: #f8f9fa; */
        border: 1px solid #ddd;
        color: #000;
        font-size: 12px;
        height: 100px;
        margin: 0 0 10px 10px;
        min-width: 120px;
        padding: 30px 5px;
        position: relative;
        text-align: center;
    }
</style>
                <?php include_once('inc/topbar.php'); ?>

                <?php include_once('inc/navigation.php'); ?>

                <div class="content-wrapper">
                    
                    <div class="content-header  px-3  py-0"  style="background:#372c71">
                        <div class="container-fluid">
                            <div class="row mb-2">
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item" ><a href="index.php" style="color: #fff;">Home</a></li>
                                <li class="breadcrumb-item active" style="color: #ffdc2f;">Dashboard</li>
                                </ol>
                            </div>
                            </div>
                        </div>
                    </div>

                    <section class="content">
                            <div class="container-fluid">
                                <!-- Small boxes (Stat box) -->
                                
                                <div class="row">
                                <?php if($auth->verifyUserPermission('dashboard', 1)
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):?>
                                    <?php
                                        if($auth->verifyUserPermission('checked', 1) 
                                        || $auth->verifyUserPermission('checked', 'iplanassign')
                                        || $auth->verifyUserPermission('checked', 'ipassignrecord')
                                        || $auth->verifyUserPermission('role', 'super admin')
                                        || $auth->verifyUserPermission('role', 'admin')
                                        ):
                                    ?>
                                     <div class="col-md-12 text-center">
                                        <h1>IT Asset</h1>
                                     </div>
                                    <div class="col-12 col-md-4">
                                            <p style="margin-left: 12px; font-size:20px;"><i class="fas fa-clipboard"></i> IP LAN</p>
                                            <?php if( $auth->verifyUserPermission('checked', 'iplanassign') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="ip_lan_assign.php?page=all-ip-lan-assign" style="font-size:15px;">
                                                    <i class="fas fa-clipboard-check"></i> IP Lan Assign Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'ipassignrecord') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="ip_assign_record.php?page=all-ip-assign-record" style="font-size:15px;">
                                                    <i class="fas fa-list"></i> IP Assign Record
                                                </a>
                                            <?php endif;?>
                                     </div>
                                    <?php endif;?>

                                    
                              
                                    <?php
                                        if($auth->verifyUserPermission('checked', 1) 
                                        || $auth->verifyUserPermission('checked', 'receive')
                                        || $auth->verifyUserPermission('checked', 'requisitionrecord')
                                        || $auth->verifyUserPermission('role', 'super admin')
                                        || $auth->verifyUserPermission('role', 'admin')
                                        ):
                                    ?>
                                    <div class="col-12 col-md-4">
                                            <p style="margin-left: 12px; font-size:20px;"><i class="fas fa-receipt"></i> Asset Receive</p>
                                            <?php if( $auth->verifyUserPermission('checked', 'receive') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="receive_entry.php?page=all-receive-entry" style="font-size:15px;">
                                                    <i class="fas fa-clipboard-check"></i>  Receive Entry
                                                </a>
                                            <?php endif;?>
                                     </div>
                                    <?php endif;?>

                                    
                            
                                    <?php
                                        if($auth->verifyUserPermission('checked', 1) 
                                        || $auth->verifyUserPermission('checked', 'machineentry')
                                        || $auth->verifyUserPermission('role', 'super admin')
                                        || $auth->verifyUserPermission('role', 'admin')
                                        ):
                                    ?>
                                    <div class="col-12 col-md-4">
                                            <p style="margin-left: 12px; font-size:20px;"><i class="fas fa-desktop"></i> Asset Machine</p>
                                            <?php if( $auth->verifyUserPermission('checked', 'machineentry') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="machine_entry.php?page=all-machine-entry" style="font-size:15px;">
                                                    <i class="fas fa-clipboard-check"></i>  Machine Entry
                                                </a>
                                            <?php endif;?>
                                     </div>
                                    <?php endif;?>

                                    
                                <?php endif;?>
                                </div>
                            </div>
                    </section>


                </div>

    <?php
    include_once('inc/footer.php');
    ?>
   
<?php
 else:
    $auth->redirect403();
endif;
endif;
?>