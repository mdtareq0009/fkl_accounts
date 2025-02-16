
<?php include_once('inc/head.php');
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyUserPermission('dashboard', 3)
    || $auth->verifyUserPermission('role', 'super admin')
    || $auth->verifyUserPermission('role', 'admin')
    ):
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

                <?php include_once('inc/navigation_system.php'); ?>

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
                                <?php if($auth->verifyUserPermission('dashboard', 3)
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):?>
                                    <?php
                                        if($auth->verifyUserPermission('checked', 1) 
                                        || $auth->verifyUserPermission('checked', 'category')
                                        || $auth->verifyUserPermission('checked', 'brand')
                                        || $auth->verifyUserPermission('checked', 'color')
                                        || $auth->verifyUserPermission('checked', 'size')
                                        || $auth->verifyUserPermission('checked', 'unit')
                                        || $auth->verifyUserPermission('checked', 'ip_category')
                                        || $auth->verifyUserPermission('checked', 'product')
                                        || $auth->verifyUserPermission('role', 'super admin')
                                        || $auth->verifyUserPermission('role', 'admin')
                                        ):
                                    ?>
                                     <div class="col-md-12 text-center">
                                        <h1>System</h1>
                                     </div>
                                    <div class="col-12 col-md-6">
                                            <p style="margin-left: 12px; font-size:20px;"><i class="fab fa-product-hunt"></i> Product</p>
                                            <?php if( $auth->verifyUserPermission('checked', 'category') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="categories.php?page=all-categories" style="font-size:15px;">
                                                    <i class="fas fa-chart-pie"></i> Category Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'brand') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="brands.php?page=all-brands" style="font-size:15px;">
                                                    <i class="fas fa-bullhorn"></i> Brand Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'color') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="colors.php?page=all-colors" style="font-size:15px;">
                                                    <i class="fas fa-spinner"></i> Color Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'size') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="sizes.php?page=all-sizes" style="font-size:15px;">
                                                    <i class="fas fa-circle-notch"></i> Size Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'size') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="sizes.php?page=all-sizes" style="font-size:15px;">
                                                    <i class="fas fa-sort-amount-up-alt"></i> UOM Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'product') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="products.php?page=all-products" style="font-size:15px;">
                                                    <i class="fab fa-product-hunt"></i> Product Entry
                                                </a>
                                            <?php endif;?>
                                     </div>
                                    <?php endif;?>
                                    <?php
                                        if($auth->verifyUserPermission('checked', 1) 
                                        || $auth->verifyUserPermission('checked', 'ip_category')
                                        || $auth->verifyUserPermission('checked', 'ip_type')
                                        || $auth->verifyUserPermission('checked', 'ip_lan')
                                        || $auth->verifyUserPermission('role', 'super admin')
                                        || $auth->verifyUserPermission('role', 'admin')
                                        ):
                                    ?>
                                    <div class="col-12 col-md-6">
                                            <p style="margin-left: 12px; font-size:20px;"><i class="fas fa-sitemap"></i> IP LAN</p>
                                            <?php if( $auth->verifyUserPermission('checked', 'ip_category') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="ip_categories.php?page=all-ip-categories" style="font-size:15px;">
                                                    <i class="fas fa-chart-pie"></i> IP Category Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'ip_type') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="ip_types.php?page=all-ip-types" style="font-size:15px;">
                                                    <i class="fas fa-bullhorn"></i> IP Type Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'ip_lan') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="ip_lans.php?page=all-ip-lans" style="font-size:15px;">
                                                    <i class="fas fa-spinner"></i> IP LAN Entry
                                                </a>
                                            <?php endif;?>
                                     </div>
                                    <?php endif;?>
                                    <?php
                                        if($auth->verifyUserPermission('checked', 1) 
                                        || $auth->verifyUserPermission('checked', 'department')
                                        || $auth->verifyUserPermission('checked', 'designation')
                                        || $auth->verifyUserPermission('checked', 'employee')
                                        || $auth->verifyUserPermission('role', 'super admin')
                                        || $auth->verifyUserPermission('role', 'admin')
                                        ):
                                    ?>
                                    <div class="col-12 col-md-6">
                                            <p style="margin-left: 12px; font-size:20px;"><i class="fas fa-user"></i> Employee</p>
                                            <?php if( $auth->verifyUserPermission('checked', 'department') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="departments.php?page=all-departments" style="font-size:15px;">
                                                    <i class="fas fa-chart-pie"></i> Department Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'designation') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="designations.php?page=all-designations" style="font-size:15px;">
                                                    <i class="fas fa-bullhorn"></i> Designation Entry
                                                </a>
                                            <?php endif;?>
                                            <?php if( $auth->verifyUserPermission('checked', 'employee') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                                                <a class="btn btn-app" href="employees.php?page=all-employees" style="font-size:15px;">
                                                    <i class="fas fa-spinner"></i> Employee Entry
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