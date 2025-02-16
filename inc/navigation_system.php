
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

        <?php  if($auth->verifyUserPermission('dashboard', 3)
         || $auth->verifyUserPermission('role', 'super admin')
         || $auth->verifyUserPermission('role', 'admin')
        ): ?>     
          <li class="nav-item">
            <a href="index_system.php"  class="nav-link">
            <i class="nav-icon fas fa-cog"></i>
            <p>
                System
              </p>  
          </a>
          </li>  
          <?php
              if($auth->verifyUserPermission('role', 'super admin')):
              ?>
          <li class="nav-item">
                  <a  href="user-permission.php?page=all-users" class="nav-link">
                    <i class="nav-icon fas fa-cog"></i>
                    
                    <p>Users Permission</p>
                  </a>
          </li>
          <?php endif; ?>
          <?php
              if($auth->verifyUserPermission('checked', 1) 
              || $auth->verifyUserPermission('checked', 'category')
              || $auth->verifyUserPermission('checked', 'brand')
              || $auth->verifyUserPermission('checked', 'color')
              || $auth->verifyUserPermission('checked', 'size')
              || $auth->verifyUserPermission('checked', 'unit')
              || $auth->verifyUserPermission('checked', 'product')
              || $auth->verifyUserPermission('checked', 'yarn_product')
              || $auth->verifyUserPermission('role', 'super admin')
              || $auth->verifyUserPermission('role', 'admin')
              ):
              ?>
        
              <li class="nav-header">Setup</li>
          
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fab fa-product-hunt"></i>
                  <p>
                    Product
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <?php if( $auth->verifyUserPermission('checked', 'category') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="categories.php?page=all-categories" class="nav-link">
                        <i class="fas fa-chart-pie nav-icon"></i>
                        <p>Category Entry</p>
                      </a>
                    </li>
                  <?php endif; ?>
                  <?php if($auth->verifyUserPermission('checked', 'brand') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="brands.php?page=all-brands" class="nav-link">
                        <i class="fas fa-bullhorn nav-icon"></i>
                        <p>Brand Entry</p>
                      </a>
                    </li>
                  <?php endif;?>
                  <?php if($auth->verifyUserPermission('checked', 'color') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="colors.php?page=all-colors" class="nav-link">
                        <i class="fas fa-spinner nav-icon"></i>
                        <p>Color Entry</p>
                      </a>
                    </li>
                  <?php endif;?>
                  <?php if($auth->verifyUserPermission('checked', 'size') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="sizes.php?page=all-sizes" class="nav-link">
                        <i class="fas fa-circle-notch nav-icon"></i>
                        <p>Size Entry</p>
                      </a>
                    </li>
                  <?php endif;?>
                  <?php if($auth->verifyUserPermission('checked', 'unit') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="units.php?page=all-units" class="nav-link">
                        <i class="fas fa-sort-amount-up-alt nav-icon"></i>
                        <p>UOM Entry</p>
                      </a>
                    </li>
                  <?php endif;?>

                  <?php if($auth->verifyUserPermission('checked', 'product') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="products.php?page=all-products" class="nav-link">
                        <i class="fab fa-product-hunt nav-icon"></i>
                        <p>Product Entry</p>
                      </a>
                    </li>
                  <?php endif;?>

                  <?php if($auth->verifyUserPermission('checked', 'yarn_product') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item" style="display:none">
                      <a href="products_yarn.php?page=all-products" class="nav-link">
                        <i class="fab fa-product-hunt nav-icon"></i>
                        <p>Yarn Product Entry</p>
                      </a>
                    </li>
                  <?php endif;?>
                </ul>
              </li>

          <?php endif;?>
          <?php
              if($auth->verifyUserPermission('checked', 1) 
              || $auth->verifyUserPermission('checked', 'ip_category')
              || $auth->verifyUserPermission('checked', 'ip_type')
              || $auth->verifyUserPermission('checked', 'ip_lan')
              || $auth->verifyUserPermission('checked', 'ip_lan_assign')
              || $auth->verifyUserPermission('role', 'super admin')
              || $auth->verifyUserPermission('role', 'admin')
              ):
              ?>
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-broadcast-tower"></i>
                  <p>
                    IP Lan
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  <?php if( $auth->verifyUserPermission('checked', 'ip_category') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="ip_categories.php?page=all-ip-categories" class="nav-link">
                        <i class="fas fas fa-chart-pie nav-icon"></i>
                        <p>IP Category Entry</p>
                      </a>
                    </li>
                  <?php endif; ?>
                  <?php if( $auth->verifyUserPermission('checked', 'ip_type') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="ip_types.php?page=all-ip-types" class="nav-link">
                        <i class="fas fa-sitemap nav-icon"></i>
                        <p>IP Type Entry</p>
                      </a>
                    </li>
                  <?php endif; ?>
                  <?php if( $auth->verifyUserPermission('checked', 'ip_lan') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                    <li class="nav-item">
                      <a href="ip_lans.php?page=all-ip-lans" class="nav-link">
                        <i class="fas fa-globe-europe nav-icon"></i>
                        <p>IP Lan Entry</p>
                      </a>
                    </li>
                  <?php endif; ?>
                </ul>
              </li>
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
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-user-tie"></i>
                    <p>
                    Employee
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <?php if( $auth->verifyUserPermission('checked', 'department')
                        || $auth->verifyUserPermission('role', 'super admin')
                        || $auth->verifyUserPermission('role', 'admin')): ?>
                      <li class="nav-item">
                        <a href="departments.php?page=all-departments" class="nav-link">
                          <i class="fas fas fa-book nav-icon"></i>
                          <p>Department Entry</p>
                        </a>
                      </li>

                    <?php endif; ?>

                  <?php if( $auth->verifyUserPermission('checked', 'designation')
                      || $auth->verifyUserPermission('role', 'super admin')
                      || $auth->verifyUserPermission('role', 'admin')): ?>
                      <li class="nav-item">
                          <a href="designations.php?page=all-designations" class="nav-link">
                              <i class="fas fa-user-graduate nav-icon"></i>
                              <p>Designation Entry</p>
                          </a>
                      </li>
                  <?php endif; ?>

                    <?php if( $auth->verifyUserPermission('checked', 'employee') || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')): ?>
                      <li class="nav-item">
                        <a href="employees.php?page=all-employees" class="nav-link">
                          <i class="fas fas fa-user-secret nav-icon"></i>
                          <p>Employee Entry</p>
                        </a>
                      </li>
                    <?php endif; ?>
                  
                  </ul>
                </li>
           <?php endif;?>
         
        
          
        <?php endif;?>
          
          
      
          
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>







