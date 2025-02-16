<!DOCTYPE html>
<?php
ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);

if($auth->authUser()):
	if($auth->verifyUserPermission('checked', 2)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeatureAll();
		// $masterData = $accessoriesModel->getData("SELECT master.nid, master.vponumber, master.vblockorderinfo, master.vissue, REGEXP_REPLACE(LISTAGG(goods.vname, ', ') WITHIN GROUP (ORDER BY items.nid ASC), '([^,]+)(, \\1)+', '\\1') AS itemname, master.vordernumberorfklnumber, master.vtype, master.vpublisheduser, employee.vempname AS createduser, master.vapproveduser, approvedemployee.vempname AS approveduser, master.vpublishedat, master.ncheckedstatus, master.vcheckeduser, checkedby.vempname AS checkeduser, REGEXP_REPLACE(LISTAGG(UPPER(orderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  orderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfostylename, REGEXP_REPLACE(LISTAGG(orderinfoks.nks_id, ', ') WITHIN GROUP (ORDER BY orderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS orderinfoksksid, orderinfo.vname AS orderinfobuyername, REGEXP_REPLACE(LISTAGG(UPPER(ksorderinfo.vstylename), ', ') WITHIN GROUP (ORDER BY  ksorderinfo.vpart ASC), '([^,]+)(, \\1)+', '\\1') AS ksstylename, REGEXP_REPLACE(LISTAGG(ksorderinfo.vordernumber, ', ') WITHIN GROUP (ORDER BY ksorderinfo.norderid ASC), '([^,]+)(, \\1)+', '\\1') AS ksordernumber, ksorderinfo.vname AS ksbuyername FROM accessories_workordermaster master LEFT JOIN accessories_workorderitems items ON items.nworkordermasterid = master.nid LEFT JOIN ERP.mer_vw_orderinfo orderinfo ON orderinfo.vordernumber = master.vordernumberorfklnumber LEFT JOIN erp.mer_ks_master orderinfoks ON orderinfoks.nordercode = orderinfo.norderid AND (TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.dshipdate, 'dd-mm-yy'))) OR TO_CHAR(extract(year from TO_DATE(master.vcreatedat, 'dd-mm-yyyy'))) = TO_CHAR(extract(year from TO_DATE(orderinfo.duserdate, 'dd-mm-yy')))) LEFT JOIN ERP.mer_ks_master kmaster ON kmaster.nks_id IN (TO_NUMBER(REGEXP_REPLACE (upper(vordernumberorfklnumber), '[A-Z]', 0))) LEFT JOIN ERP.mer_vw_orderinfo ksorderinfo ON ksorderinfo.norderid = kmaster.nordercode LEFT JOIN hrm_employee employee ON employee.vemployeeid = master.vpublisheduser LEFT JOIN hrm_employee approvedemployee ON approvedemployee.vemployeeid = master.vapproveduser LEFT JOIN hrm_employee checkedby ON checkedby.vemployeeid = master.vcheckeduser LEFT JOIN accessories_goods goods ON goods.nid = items.ngoodsid WHERE master.vstatus = 'publish' AND master.ncheckedstatus = 0 AND master.nacceptencestatus = 0 AND master.ndeletedstatus = 0 AND (master.vcreateduser = '$userid' OR master.vcreateduser IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'admin' OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userid') = 'super admin') GROUP BY master.nid, master.vponumber, master.vblockorderinfo, master.vissue, master.vordernumberorfklnumber, master.vtype, master.vpublisheduser, employee.vempname, master.vpublishedat, master.ncheckedstatus, master.vcheckeduser, checkedby.vempname, master.vapproveduser, approvedemployee.vempname, orderinfo.vname, ksorderinfo.vname ORDER BY master.nid DESC");

        // echo "<pre>";
        // print_r($masterData);
        // // exit();
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
    <div data-role="panel" data-title-caption="Checked List for Merchandiser" data-collapsible="false" data-title-icon="<span class='mif-done_all'></span>">
        <div class="ml-1 mr-1">
            <table 
                class="table striped table-border cell-border subcompact mt-1 accessories-table-common checked-workorder-table"
                data-role="table"
                data-cls-table-top="row"
                data-cls-search="cell-md-7 cell-sm-6"
                data-cls-rows-count="cell-md-5 cell-sm-6"
                data-rows="20"
                data-rows-steps="-1, 20, 30, 50, 100, 150"
                data-show-activity="false"
                data-rownum-title="No."
                data-rownum="true"
                data-search-threshold="1000"
                data-cls-table-container="checked-workorder-table"
                data-source="data/checked-workorder.php"
                data-horizontal-scroll="true"
                data-table-info-title="Showing from $1 to $2 of $3 Approved Work Order(s)"
                data-on-draw="preloaderClose()"
                data-on-data-load="preloaderStart()"
                >
                
                <?php
                if($auth->verifyUserPermission('checked', 2)):
                ?>
                <tfoot>
                <tr>
                    <td colspan="10" style="padding: 1px;"></td>
                    <td style="padding: 1px;"><button class="image-button success" type="button" onclick="workorderViewOperation('checked-workorder-table', 'merchandiser', 'checkedworkorder.php');" style="height: 22px; padding: 0 2px 0 0;"><span class="mif-done_all icon" style="height: 22px; line-height: 22px; font-size: .9rem; width: 20px;"></span><span class="caption" style="margin-left: 2px;">Merchandiser</span></button></td>
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