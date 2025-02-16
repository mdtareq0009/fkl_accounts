
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
  

                <?php include_once('inc/topbar.php'); ?>

                <?php include_once('inc/dashboard_navigation.php'); ?>

                <div class="content-wrapper">
                    
                    <div class="content-header  px-3  py-0"  style="background:#372c71">
                        <div class="container-fluid">
                            <div class="row mb-2">
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item" ><a href="#" style="color: #fff;">Home</a></li>
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
                                <div class="col-lg-2 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-info">
                                    <div class="inner">
                                    <a href="index_system.php" style="color: #fff;">
                                        <h3>IT Assets</h3>
                                    </a>
                                    </div>
                                    <div class="icon">
                                    <i class="fas fa-laptop-house"></i>
                                       
                                    </div>
                                    <a href="index_it_asset.php" class="small-box-footer">See All <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                                <?php endif;?>
                                <?php if($auth->verifyUserPermission('dashboard', 2)
                                 || $auth->verifyUserPermission('role', 'super admin')
                                 || $auth->verifyUserPermission('role', 'admin')
                                ):?>
                                    <!-- <div class="col-lg-2 col-6">
                                      
                                        <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>Yran Store</h3>
                                        </div>
                                        <div class="icon">
                                        <i class="fas fa-yen-sign"></i>
                                        </div>
                                        <a href="index_yarn_store.php" class="small-box-footer">See All <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div> -->
                                <?php endif;?>
                                <?php if($auth->verifyUserPermission('dashboard', 3)
                                        || $auth->verifyUserPermission('role', 'super admin')
                                        || $auth->verifyUserPermission('role', 'admin')
                                ):?>
                                    <div class="col-lg-2 col-6">
                                        <!-- small box -->
                                        <div class="small-box bg-danger">
                                            <div class="inner">
                                            <a href="index_system.php" style="color: #fff;">
                                                <h3>System</h3>
                                            </a>
                                        </div>
                                        <div class="icon">
                                        <i class="fas fa-cog"></i>
                                        </div>
                                        <a href="index_system.php" class="small-box-footer">See All <i class="fas fa-arrow-circle-right"></i></a>
                                        </div>
                                    </div>
                                <?php endif;?>
                                </div>
                            </div>
                    </section>
                </div>




    <?php
    include_once('inc/footer.php');
    ?>
<?php
endif;
?>