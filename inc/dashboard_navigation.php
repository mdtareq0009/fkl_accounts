
<!-- Main Sidebar Container -->




<aside class="main-sidebar sidebar-dark-primary elevation-4"  style="background:#372c71">
    <!-- Brand Logo -->
     <style>
      .brand-link .brand-image{
        width: 55px;
        float: left;
        line-height: .8;
         margin-left: 0rem;
        margin-right: 0rem;
        margin-top: 1px;
        max-height: 33px;
      }
     </style>
    <a href="/" class="brand-link">
      <img src="images/logo110x51.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light">Fkl ERP System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar"  style="background:#372c71">
    <?php $userInfo = $auth->loggedUser();?>
      <!-- Sidebar user (optional) -->
      <div class="user-panel d-flex">
        <div class="image" style="padding-top: 5px;color: yellow;">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        </div>
        <div class="info" style="color: yellow;">
          <a href="#" class="d-block">Dashboard</a>
        </div>
      </div>

   
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <?php
            if($auth->verifyUserPermission('dashboard', 1)):
        ?>
          <li class="nav-item">
            <a href="index_it_asset.php" class="nav-link">
              <i class="nav-icon fas fa-laptop-house" style="width: 40px;"></i>
              <p>IT Assets</p>
            </a>
          </li>
        <?php
        endif;
        ?>
        <?php
            if($auth->verifyUserPermission('dashboard', 2)
            || $auth->verifyUserPermission('role', 'super admin')
            || $auth->verifyUserPermission('role', 'admin')
            ):
        ?>
          <!-- <li class="nav-item">
            <a href="index_yarn_store.php" class="nav-link">
                <i class="fas fa-yen-sign" style="padding:0px 15px"></i>
                <p>Yarn Store</p>
            </a>
          </li> -->
        <?php
        endif;
        ?>
        <?php
            if($auth->verifyUserPermission('dashboard', 3)
            || $auth->verifyUserPermission('role', 'super admin')
            || $auth->verifyUserPermission('role', 'admin')
            ):
        ?>
          <li class="nav-item">
            <a href="index_system.php" class="nav-link">
                <i class="fas fa-cog" style="padding:0px 15px"></i>
                <p>System</p>
            </a>
          </li>
        <?php
        endif;
        ?>
         
      
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>







