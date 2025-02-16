
<!-- Main Sidebar Container -->




<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background:#372c71">
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
    <a href="index.php" class="brand-link">
      <img src="images/logo110x51.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light">Fkl ERP System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar" style="background:#372c71">
    <?php $userInfo = $auth->loggedUser();?>
      <!-- Sidebar user (optional) -->
    

   
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

      


        <?php   if($auth->verifyUserPermission('dashboard', 2)
            || $auth->verifyUserPermission('role', 'super admin')
            || $auth->verifyUserPermission('role', 'admin')
          ): ?>     
          <li class="nav-item">
            <a href="index_yarn_store.php"  class="nav-link">
            <i class="nav-icon fas fa-user"></i>
              <p>
                Yarn Store
              </p>  
          </a>
          </li>  
          <?php
                if($auth->verifyUserPermission('checked', 1)
                || $auth->verifyUserPermission('checked', 'requisitionentry')
                || $auth->verifyUserPermission('role', 'super admin')
                || $auth->verifyUserPermission('role', 'admin')
                ):
            ?>
              <li class="nav-header">Yarn Receive</li>
              <?php if( $auth->verifyUserPermission('checked', 'requisitionentry')
                      || $auth->verifyUserPermission('role', 'super admin')
                      || $auth->verifyUserPermission('role', 'admin')): ?>
              <li class="nav-item">
                <a href="requisition_entry.php?page=all-requisition-entry" class="nav-link">
                  <i class="nav-icon fas fa-shopping-basket"></i>
                  <p>
                  Receive Entry
                  </p>
                </a>
              </li>
            <?php endif; ?>
             
          <?php endif;?>
         
        
          
        <?php endif;?>
        
     
          
          
      
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>







