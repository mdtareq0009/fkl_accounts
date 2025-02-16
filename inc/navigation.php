
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

        <?php  if($auth->verifyUserPermission('dashboard', 1)): ?>     
          <li class="nav-item">
            <a href="index_it_asset.php"  class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
                IT ASSET
              </p>  
          </a>
          </li>  

          <?php
              if($auth->verifyUserPermission('checked', 1)
              || $auth->verifyUserPermission('checked', 'iplanassign')
              || $auth->verifyUserPermission('checked', 'ipassignrecord')
              || $auth->verifyUserPermission('role', 'super admin')
              || $auth->verifyUserPermission('role', 'admin')
              ):
              ?>
              <li class="nav-header">IP LAN</li>
              <?php if( $auth->verifyUserPermission('checked', 'iplanassign')
                      || $auth->verifyUserPermission('role', 'super admin')
                      || $auth->verifyUserPermission('role', 'admin')): ?>
              <li class="nav-item">
                <a href="ip_lan_assign.php?page=all-ip-lan-assign" class="nav-link">
                  <i class="nav-icon fas fa-clipboard"></i>
                  <p>
                  IP Lan  Assign Entry
                  </p>
                </a>
              </li>
              <?php endif; ?>
              <?php if( $auth->verifyUserPermission('checked', 'ipassignrecord')
                      || $auth->verifyUserPermission('role', 'super admin')
                      || $auth->verifyUserPermission('role', 'admin')): ?>
              <li class="nav-item">
                <a href="ip_assign_record.php?page=all-ip-assign-record" class="nav-link">
                  <i class="nav-icon fas fa-list"></i>
                  <p>
                    IP Lan Assign Record
                  </p>
                </a>
              </li>
              <?php endif; ?>
          <?php endif;?>

          <?php
                if($auth->verifyUserPermission('checked', 1)
                || $auth->verifyUserPermission('checked', 'receive')
                || $auth->verifyUserPermission('role', 'super admin')
                || $auth->verifyUserPermission('role', 'admin')
                ):
            ?>
              <li class="nav-header">Asset Receive</li>
              <?php if( $auth->verifyUserPermission('checked', 'receive')
                      || $auth->verifyUserPermission('role', 'super admin')
                      || $auth->verifyUserPermission('role', 'admin')): ?>
              <li class="nav-item">
                <a href="receive_entry.php?page=all-receive-entry" class="nav-link">
                  <i class="nav-icon fas fa-shopping-basket"></i>
                  <p>
                  Receive Entry
                  </p>
                </a>
              </li>
                    <li class="nav-item">
                <a href="receive_entry.php?page=all-receive-entry" class="nav-link">
                  <i class="nav-icon fas fa-shopping-basket"></i>
                  <p>
                  Receive List
                  </p>
                </a>
              </li>
            <?php endif; ?>
             
          <?php endif;?>
          <?php
              if($auth->verifyUserPermission('checked', 1)
              || $auth->verifyUserPermission('checked', 'receive')
              || $auth->verifyUserPermission('role', 'super admin')
              || $auth->verifyUserPermission('role', 'admin')
              ):
              ?>
              <li class="nav-header">Asset Machine</li>
              <?php if( $auth->verifyUserPermission('checked', 'machineentry')
                      || $auth->verifyUserPermission('role', 'super admin')
                      || $auth->verifyUserPermission('role', 'admin')): ?>
              <li class="nav-item">
                <a href="machine_entry.php?page=all-machine-entry" class="nav-link">
                  <i class="nav-icon fas fa-clipboard"></i>
                  <p>
                  Machine Entry
                  </p>
                </a>
              </li>
           <?php endif; ?>
             
          <?php endif;?>
         
          
        <?php endif;?>


        <?php  if($auth->verifyUserPermission('dashboard', 2)): ?>     
          <li class="nav-item">
            <a href="index_it_asset.php"  class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
                IT ASSET
              </p>  
          </a>
          </li>  
        
          <?php
                if($auth->verifyUserPermission('checked', 1)
                || $auth->verifyUserPermission('checked', 'receive')
                || $auth->verifyUserPermission('role', 'super admin')
                || $auth->verifyUserPermission('role', 'admin')
                ):
            ?>
              <li class="nav-header">Asset Receive</li>
              <?php if( $auth->verifyUserPermission('checked', 'receive')
                      || $auth->verifyUserPermission('role', 'super admin')
                      || $auth->verifyUserPermission('role', 'admin')): ?>
              <li class="nav-item">
                <a href="receive_entry.php?page=all-receive-entry" class="nav-link">
                  <i class="nav-icon fas fa-shopping-basket"></i>
                  <p>
                  Receive Entry
                  </p>
                </a>
              </li>
            <?php endif; ?>
             
          <?php endif;?>
         
        
          
        <?php endif;?>
        
        <?php  if($auth->verifyUserPermission('dashboard', 3)): ?>     
          <li class="nav-item">
            <a href="index_it_asset.php"  class="nav-link">
            <i class="nav-icon fas fa-user"></i>
            <p>
                System
              </p>  
          </a>
          </li> 
        <?php  if($auth->verifyUserPermission('role', 'super admin')):
                ?>
                  <li class="nav-item">
                  <a href="index_it_asset.php"  class="nav-link">
                  <i class="nav-icon fas fa-user"></i>
                  <p>
                      System
                    </p>  
                </a>
                </li> 
            
      
        
          <?php
              if($auth->verifyUserPermission('checked', 1) 
              || $auth->verifyUserPermission('checked', 'category')
              || $auth->verifyUserPermission('checked', 'brand')
              || $auth->verifyUserPermission('checked', 'color')
              || $auth->verifyUserPermission('checked', 'size')
              || $auth->verifyUserPermission('checked', 'unit')
              || $auth->verifyUserPermission('checked', 'product')
              || $auth->verifyUserPermission('role', 'super admin')
              || $auth->verifyUserPermission('role', 'admin')
              ):
              ?>
        
              <li class="nav-header">Setup</li>
          
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
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







