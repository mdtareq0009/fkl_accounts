<div class="navview-pane" style="background: #103642;">
    <div class="d-flex flex-align-center close" style="background-color: #103642;">
        <button class="pull-button m-0 ribbed-darkTeal">
        <span class="mif-menu fg-white" style="font-size: 28px; width: 28px; height: 28px; line-height: 28px;"></span>
        </button>
        <h2 class="fg-light company-text" style="margin: 0 auto !important; text-align: center; font-size: 22.5px;"> <img src="images/LOGO_157x60.png" style="width: 115px;"></h2>
    </div>
    
    <ul class="navview-menu" id="side-menu" style="background: #103642;">
        <li class="item-header text-center ribbed-darkTeal fg-white text-bold" style="margin-left: -8px;"><span class="mif-navigation"></span> Navigation</li>
        <li class="border-bottom bd-darkTeal">
            <a href="index.php" class="dashboard">
                <span class="icon"><span class="mif-meter"></span></span>
                <span class="caption">Dashboard</span>
            </a>
        </li>
       
      
        <!-- ========================= -->

        <?php
                if($auth->verifyUserPermission('checked', 1) || $auth->verifyUserPermission('checked', 'category')):
        ?>
        <li class="border-bottom bd-darkTeal">
            <a href="javascript:void(0)" class="settings dropdown-toggle">
                <span class="icon"><span class="mif-cogs"></span></span>
                <span class="caption">User Permission</span>
            </a>
            <ul class="navview-menu setting stay-open" data-role="dropdown" style="background: #489fb5;">

              <?php if(
                $auth->verifyUserPermission('checked', 'category') || $auth->verifyUserPermission('role', 'super admin')
                
                ): ?>
                <li class="border-bottom bd-darkTeal">
                  <a href="user-permission.php?page=all-users" class="all-categories">
                    <span class="icon"><span class="mif-users"></span></span>
                    <span class="caption"> User List</span>
                  </a>
                </li>
               <?php
                 endif;
                ?>
            </ul>
        </li>
        <?php
        endif;
        ?>
<!--    
        <li class="border-bottom bd-darkTeal">
            <a href="help.php?page=need-help" class="need-help">
                <span class="icon"><span class="mif-help"></span></span>
                <span class="caption">Help</span>
            </a>
        </li>                      -->
    </ul>
    <div class="w-100 text-center text-small data-box p-2">
        <div><img src="images/beta.png" style="width: 55px;background: #fff;border-radius: 13px; transform: rotate(-27deg);"></div>
    </div>

    <div class="w-100 text-center text-small data-box p-2 border-top bd-grayMouse" style="position: absolute; bottom: 0">
        <div>&copy; All Rights Reserved by Fakir Group</div>
        <div>Developed by <img src="images/FKLIT.png" style="width: 75px;background: #fff;border-radius: 13px 0px 13px 0px;margin-left: 5px;padding: 2px;"></div>
    </div>
</div>