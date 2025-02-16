<!DOCTYPE html>
<?php
ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);

if($auth->authUser()):
	if($auth->verifyUserPermission('checked', 4)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeatureAll();
?>
<style>
    .report{
        display: none;
    }
</style>

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
            <div class="row-fluid">
<div class="cell-md-12">
    <div data-role="panel" data-title-caption="Proceed for Merchandising GM" data-collapsible="false" data-title-icon="<span class='mif-done_all'></span>">
        <div class="ml-1 mr-1">
            <table 
                class="table striped table-border cell-border subcompact mt-1 accessories-table-common merchandisergm-workorder-table"
                data-role="table"
                data-cls-table-top="row"
                data-cls-search="cell-md-7 cell-sm-6"
                data-cls-rows-count="cell-md-5 cell-sm-6"
                data-rows="-1"
                data-rows-steps="-1, 20, 30, 50, 100, 150"
                data-show-activity="false"
                data-rownum-title="No."
                data-rownum="true"
                data-search-threshold="1000"
                data-cls-table-container="merchandiser-manager-workorder-table"
                data-source="data/merchandiser-manager-workorder.php"
                data-horizontal-scroll="true"
                data-table-info-title="Showing from $1 to $2 of $3 Approved Work Order(s)"
                data-on-draw="preloaderClose()"
                data-on-data-load="preloaderStart()"
                >
                
                <?php
                if($auth->verifyUserPermission('checked', 4)):
                ?>
                <tfoot>
                <tr>
                    <td colspan="10" style="padding: 1px;"></td>
                    <td style="padding: 1px;"><button class="image-button success" type="button" onclick="workorderViewOperation('merchandisergm-workorder-table', 'merchandisergm', 'merchandisermanagerworkorder.php');" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-done_all icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Checked by MGM</span></button></td>
                    <td style="padding: 1px;"></td>
                </tr>
                </tfoot>
                <?php
                endif;
                ?>
            </table>
        
        </div>
    </div>
</div>
</div>
<?php
    else:
    ?>
    <div class="row mt-3">
        <div class="cell-md-12 d-flex flex-justify-center flex-align-center">
            <div class="display1 m-2 text-center text-bold" style="color: #d4d4d4;">Accessories Store Automation System</div>
        </div>
    </div>
    <?php
    endif;
else:
    $auth->redirect403();
endif;
?>

                <!-- <h1>header</h1> -->
            </div>
        </div>
<?php include_once('inc/footer.php'); ?>