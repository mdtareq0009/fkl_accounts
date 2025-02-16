<div data-role="appbar" class="pos-absolute fg-white" style="background: #006d77;">
    <p class="fg-light m-0 fg-white pl-1 topbar-heading" style="text-transform: uppercase;"><img src="images/logo110x51.png"> <span>Fkl ERP System</span></p>
    <a href="javascript:void(0)" class="app-bar-item d-block d-none-xl" id="paneToggle" style="font-size: 26px;width: 60px;height: 26px;line-height: 26px;"><span class="mif-menu"></span></a>
    <div class="app-bar-container ml-auto">
        <div class="app-bar-container">
            <?php $userInfo = $auth->loggedUser();?>
            <a href="javascript:void(0)" class="app-bar-item">
                <div style="width: 33px;"><?=$userInfo['picture']?></div>
                <span class="ml-2 app-bar-name text-bold"><?=$userInfo['name']?></span>
            </a>
            <div class="user-block shadow-1" data-role="collapse" data-collapsed="true">
                <div class="fg-white p-2 text-center" style="background: #103642;">
                    <div style="width:125px; margin: 0 auto; height: 110px;"><?=$userInfo['picture']?></div>
                    <div class="h4 mb-0"><?=$userInfo['name']?></div>
                    <div><?=$userInfo['designation']?></div>
                    <div><?=ucwords($userInfo['role'])?>  <span class="mif-flag"></span></div>
                </div>
                <div class="bg-white d-flex flex-justify-between flex-equal-items p-2 bg-light">
                    <a href="users.php?page=change-password" class="image-button ml-1 fg-white topbarbtn"  style="height: 25px;">
                        <span class="mif-cog icon" style="height: 25px; line-height: 25px; font-size: .9rem; width: 23px;"></span>
                        <span class="caption text-bold">Change Password</span>
                    </a>
                    <a href="?logout=1" class="image-button ml-1 fg-white icon-right topbarbtn" style="height: 25px;">
                        <span class="mif-settings-power icon" style="height: 25px; line-height: 25px; font-size: .9rem; width: 33px;"></span>
                        <span class="caption text-bold">Logout</span>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>