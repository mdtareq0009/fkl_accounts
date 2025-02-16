<!DOCTYPE html>
<?php
include_once('inc/head.php');
use accessories\accessoriescrud;
use accessories\dependentdata;

if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if($auth->verifyUserPermission('trash', 1)):
        $accessoriesModel = new accessoriescrud($db->con);
        $appsDependent = new dependentdata($db->con);
?>
        <body class="m4-cloak h-vh-100">
            <div class="preloader" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, 0.8); left: 0;">
                <div data-role="activity" data-type="square" data-style="color" style="position: relative;top: 50%; left: 50%;"></div>
            </div>
            <div class="success-notification" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, .93); left: 0;">
                
            </div>
            <div data-role="navview" data-toggle="#paneToggle" data-expand="xxl" data-compact="xl" data-active-state="true">
                <?php include_once('inc/navigation.php'); ?>
                <div class="navview-content h-100">
                    <?php include_once('inc/topbar.php');?>
                    <div class="content-inner h-100" style="overflow-y: auto">
                        <div class="row border-bottom bd-lightGray pl-1 mr-1 ribbed-lightGray" style="margin-left: 0px;">
                            <div class="cell-md-4">
                                <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="trash" data-page="deleted-workorder" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-bin"></span></span> Trash</h4>
                            </div>
                            <div class="cell-md-8">

                            </div>
                        </div>
                        
                    <?php
                    if($_GET['page'] == 'deleted-workorder'):
                    ?>
                    <div class="row-fluid">
                        <div class="cell-md-12">
                            <div data-role="panel" data-title-caption="All Deleted Workorders" data-collapsible="false" data-title-icon="<span class='mif-assignment'></span>">
                                <div class="ml-1 mr-1">
                                    <table 
                                        class="table striped table-border cell-border subcompact mt-1 accessories-table-common trash-workorder-table"
                                        data-role="table"
                                        data-cls-table-top="row"
                                        data-cls-search="cell-md-7"
                                        data-cls-rows-count="cell-md-5"
                                        data-rows="18"
                                        data-rows-steps="-1, 18, 30, 50, 100, 150"
                                        data-show-activity="false"
                                        data-rownum-title="No."
                                        data-rownum="true"
                                        data-search-threshold="1000"
                                        data-cls-table-container="trash-table"
                                        data-source="data/trash.php"
                                        data-horizontal-scroll="true"
                                        data-table-info-title="Showing from $1 to $2 of $3 workorder(s)"
                                        data-on-draw="preloaderClose()"
                                        data-on-data-load="preloaderStart()"
                                    >
                                    <tfoot>
                                        <tr>
                                            <td colspan="10" style="padding: 1px;"></td>
                                            <td style="padding: 1px;" class="text-center d-flex flex-justify-between"><button class="image-button success" type="button" onclick="workorderViewOperation('trash-workorder-table', 'restore', 'workorder.php?page=drafts');" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-beenhere icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Restore</span></button>  <button class="image-button alert" type="button" onclick="workorderViewOperation('trash-workorder-table', 'remove', '');" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-bin icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Remove</span></button></td>
                                        </tr>
                                    </tfoot> 
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php
                    else:
                        $pageOpt->redirectWithscript($pageOpt->pageFirst(), 'Requested page is invalid!');
                    endif;
                    ?>
                </div>
            </div>
        </div>
        </div>
        <?php include_once('inc/footer.php'); ?>
        </body>
    <?php
    else:
     echo "<script type='text/javascript'>window.location.href = '403.php';</script>";
    endif;
endif;
?>
</html>