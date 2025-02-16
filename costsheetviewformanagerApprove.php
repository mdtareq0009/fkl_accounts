<!DOCTYPE html>
<?php
ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);
$managerFeature = $auth->getManagerFeatureAll();
// $managerFeature = $auth->allGetManagerFeature($managerFeature);
$managerFeature = "'" . str_replace(",", "','", addslashes($managerFeature)) . "'";

$userId = $auth->loggedUserId();
if(isset($_GET['success']) && $_GET['success']==true)
echo "<script>alert('Successfully Inserted')</script>";
elseif(isset($_GET['update']) && $_GET['update']==true)
echo "<script>alert('Successfully Updated')</script>";
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
                <div class="row border-bottom bd-lightGray pl-1 mr-1 ribbed-lightGray" style="margin-left: 0px;">
                    <div class="cell-md-4">
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="work-order" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Cost Sheet Acceptence</h4>
                    </div>
                    <div class="cell-md-8">
                        <a class="float-right button success small" href="costsheet-.php"> Create</a>
                    </div>
      
                <div class="card">
                    <!-- <div class="container" id="print-js"> -->
                        <div class="report text-center">
                            <img src="./images/LOGO_157x60.png" style="line-height: 0;" alt="No Img" srcset="" class="report text-center">
                            <p class="report text-center" style="font-size: 12px;line-height:0">AN ISO 9001 : 2008, SCR, BSCI, SEDEX, ORGANIC & OEKO TEX CERTIFIED COMPANY</p>
                        </div>
                        <!-- <table class="table row-border cell-border border row-hover cell-hover subcompact" data-rownum="true" data-role="table"> -->
                            <table class="table table-border cell-border cell-hover row-hover subcompact mt-1 accessories-table-common published-workorder-table"
                                data-role="table"
                                data-cls-table-top="row"
                                data-cls-search="cell-md-7 cell-sm-6"
                                data-cls-rows-count="cell-md-5 cell-sm-6"
                                data-rows="20"
                                data-rows-steps="-1, 18, 30, 50, 100, 150"
                                data-show-activity="false"
                                data-rownum-title="No."
                                data-rownum="true"
                                data-search-threshold="1000"
                                data-cls-table-container="published-workorder"
                                data-horizontal-scroll="true"
                                data-table-info-title="Showing from $1 to $2 of $3 Cost Sheet Approval"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                                data-on-table-create = "">
                            <thead>
                                <!-- <th>Sl</th>						 -->
                                <th class="text-center sortable-column">Approve</th>						
                                <th class="text-center sortable-column">P.C. No.</th>						
                                <th class="text-center sortable-column">Date</th>						
                                <th class="text-center sortable-column">User</th>						
                                <th class="text-center sortable-column">Buyer</th>
                                <th class="text-center sortable-column">Style</th>
                                <th class="text-center sortable-column">Season</th>
                                <th class="text-center sortable-column">O.Price</th>
                                <!-- <th class="text-center sortable-column">Dept.</th> -->
                                <th class="text-center sortable-column">Order<br>Qty</th>
                                <th class="text-center sortable-column">Piece</th>
                                <th class="text-center sortable-column">Fabric<br> Cost</th>
                                <th class="text-center sortable-column">Trim<br>Cost</th>
                                <th class="text-center sortable-column">Other<br>Cost</th>
                                <th class="text-center sortable-column">Material<br>Cost</th>
                                <th class="text-center sortable-column">CM</th>
                                <th class="text-center sortable-column">Can Be</th>
                                <th class="text-center sortable-column">Profit</th>
                                <th class="text-center sortable-column">FOB</th>
                                <th class="text-center sortable-column">Unit<br>Price</th>
                                <th class="text-center sortable-column">Total Value</th>
                                <th class="text-center">Action</th>
                                <!-- <th></th> -->
                            </thead>
                            <tbody class="text-center">
                            <?php
                                    $data=$accessoriesModel->getData("SELECT 
                                    MPM_SL,MPM_EUSER,MPM_NO,
                                    TO_CHAR(MPM_EDATE,'dd-MM-yyyy') AS MPM_EDATE,MPM_ETIME,MPM_NO,TO_DATE(MPM_CDATE,'dd-MM-yyyy') AS MPM_CDATE,
                                    MPM_BUYER_ID,MRD_BUYER_STYLE_ID,MRD_BUYER_SEASON_ID,MRD_BUYER_DEPT_ID,MPM_PACK_TYPE,MPM_PACK_NUMBER,MPM_ORDER_QTY,MPF_TOTAL_FAB_PRICE,
                                    MPT_TOTAL_TRIM_PRICE,MPO_TOTAL_PRICE,MPC_TOTAL_PROFIT,MPC_TOTAL_EXCES,MPC_TOTAL_CM,MPM_TOTAL_FABRIC_PRICE,MPM_TOTAL_TRIM_PRICE,MPM_TOTAL_OTHER_PRICE,
                                    MPM_TOTAL_MATERIAL_PRICE,MPM_TOTAL_CM_PRICE,MPM_TOTAL_CB_PRICE,MPM_PROFIT_PRICE,MPM_FOB_PRICE,MPM_UNIT_PRICE,MPM_TOTAL_PRICE,MPM_REMARKS,(select listagg(offer.offer_price,',') within group (order by offer.mpop_sl asc) from inv.mrd_precosting_offer_price offer 
where offer.mpm_no = mpm.mpm_no)
as offerprice,published_status,approval_status,MPM_OFFER_PRICE,approve_manager_status,approve_manager_by,
                                    (SELECT vempname FROM erp.hrm_vw_employeeinfo U WHERE U.vemployeeid = MPM.MPM_EUSER) AS USER_NAME,(SELECT VNAME FROM ERP.MER_BUYERNAME MB WHERE MB.NBUYERID = MPM.MPM_BUYER_ID ) AS BUYER_NAME,
                                    (SELECT MRD_BUYER_STYLE_NAME FROM inv.MRD_BUYER_STYLE MBS WHERE MBS.MRD_BUYER_STYLE_ID = MPM.MRD_BUYER_STYLE_ID) AS BUYER_STYLE,
                                    (SELECT VSEASSONNAME FROM ERP.MER_SEASSONNAME MS WHERE MS.NSEASSONCODE = MPM.MRD_BUYER_SEASON_ID) AS BUYER_SEASON,
                                    (SELECT MD.VDEPTNAME FROM ERP.MER_DEPARTMENT MD WHERE MD.NDEPTID = MPM.MRD_BUYER_DEPT_ID AND MPM.MPM_BUYER_ID=MD.NBUYERID) AS BUYER_DEPT,
                                    (SELECT mip.mrd_item_pack_name FROM inv.mrd_item_pack mip WHERE mip.mrd_item_pack_id = MPM.MPM_PACK_TYPE) AS pack_type
                                FROM inv.
                                    MRD_PRECOSTING_MASTER  MPM WHERE PUBLISHED_status=1 and DELETE_STATUS=0 AND (MPM_EUSER = '$userId'
OR MPM_EUSER IN ($managerFeature) OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR 
(SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')
                                ORDER BY MPM_SL DESC");
                              
                                    $i=0;
                                    if($data != 'Table is empty...'){
                                    foreach($data as $row){
                                        $i++;
                            ?>
                                <tr class="text-center">
                                    <!-- <td class="text-center"><?php //echo $i; ?></td> -->
                                    <td class="text-center">
                                    <?php if ($row['APPROVE_MANAGER_STATUS'] == 0 or $row['APPROVE_MANAGER_STATUS']== null ){ ?>
                                        <a onclick="acceptData(this.id)" class="button small warning fg-white rounded mif-done" title="Click to Accept" id="<?php echo $row['MPM_NO'] ?>"></a> 
                                        <?php }else{ ?>
                                        <a class="button small success fg-white rounded mif-done_all" title="Already Accepted" id="<?php echo $row['MPM_NO'] ?>"></a> 
                                        <?php } ?>
                                    </td>
                                    <td class="text-center"><strong><?php echo $row['MPM_NO'] ?></strong></td>
                                    <td class="text-center"><?php echo $row['MPM_CDATE'] ?></td>
                                    <td class="text-center"><?php echo $row['USER_NAME'] ?></td>
                                    <td class="text-center"><?php echo $row['BUYER_NAME'] ?></td>
                                    <td class="text-center"><a href="costreport.php?master_id=<?php echo $row['MPM_NO'] ?>" target="_blank"><?php echo $row['MRD_BUYER_STYLE_ID'] ?></a></td>
                                    <!-- <td class="text-center"><a href="costSheetReport.php?master_id=<?php echo $row['MPM_NO'] ?>" target="_blank"><?php //echo $row['MRD_BUYER_STYLE_ID'] ?></a></td> -->
                                    <td class="text-center"><?php echo $row['BUYER_SEASON'] ?></td>
                                    <!-- <td class="text-center"><?php //echo $row['OFFERPRICE'] ?></td> -->
                                    <!-- <td class="text-center"><?php //echo $row['BUYER_DEPT'] ?></td> -->
                                    <td class="text-center"><?php echo $row['MPM_OFFER_PRICE'] ?></td>
                                    <td class="text-center"><?php echo $row['MPM_ORDER_QTY'] ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_PACK_NUMBER'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPF_TOTAL_FAB_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_TOTAL_TRIM_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_TOTAL_OTHER_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_TOTAL_MATERIAL_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_TOTAL_CM_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_TOTAL_CB_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_PROFIT_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_FOB_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_UNIT_PRICE'],2) ?></td>
                                    <td class="text-center"><?php echo round($row['MPM_TOTAL_PRICE'],2) ?></td>
                                    <td class="text-center">
                                        <a href="costreport.php?master_id=<?php echo $row['MPM_NO'] ?>"  title="Click to View Cost Sheet" target="_blank"><span class="mif-eye"></span></a>
                                        <!-- <a href="costsheetrevise.php?master_id=<?php //echo $row['MPM_NO'] ?>"  title="Click to Revise Cost Sheet" target="_blank"><span class="mif-ambulance mif-2x ml-2"></span></a> -->
                                        <!-- <a href="costSheetReport.php?master_id=<?php //echo $row['MPM_NO'] ?>"  title="View Cost Sheet" target="_blank"><span class="mif-eye"></span></a> -->
                                        <!-- <a href="copycostsheet.php?copy_id=<?php //echo $row['MPM_NO'] ?>" class="ml-2 mr-2" title="Copy Cost Sheet"><span class="mif-copy"></span></a> -->
                                        <?php //if ($row['PUBLISHED_STATUS'] == 0 or $row['PUBLISHED_STATUS']== null){ ?>
                                        <!-- <a href="editcostsheet.php?master_id=<?php //echo $row['MPM_NO'] ?>" class="ml-2 mr-2 edit" title="Edit Cost Sheet" id="<?php //echo $row['MPM_NO'].'edit' ?>"><span class="mif-pencil"></span></a> -->
                                        <?php //} ?>
                                        <!-- <?php //if($auth->verifyUserPermission('trash', 1)):?>
                                        <a href="costsheetSubmit.php?deleteData=<?php //echo $row['MPM_NO'] ?>" title="Delete Cost Sheet" class=""><span class="mif-bin"></span></a>
                                        <?php //endif; ?> -->
                                    </td>
                                    <!-- <td class="text-center">Delete</td> -->
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                        <br><br><br><br>
                        <div class="report">
                            <p style=" line-height: 0">---------------</p>
                            <p style=" line-height: 0">Approved By</p>
                        </div>
                    <!-- </div> -->
                </div>
            </div>
        </div>
<?php include_once('inc/footer.php'); ?>

</body>
</html>