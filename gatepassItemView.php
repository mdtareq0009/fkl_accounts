<!DOCTYPE html>
<?php
ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
if($auth->authUser()):
	if($auth->verifyUserPermission('gatepass', 11)):
$accessoriesModel = new accessoriescrud($db->con);
$managerFeature = $auth->getManagerFeature();
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
    .data-font{
        font-size: 22px !important;
        font-weight: 600 !important;
        /* font-family: 'Times'; */
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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="work-order" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Gate Pass Item List</h4>
                    </div>
                    <div class="cell-md-8">
                        <a class="float-right button success small" href="gatepass_create_item_name.php"> Create</a>
                    </div>
                </div>
                <div class="card" style="font-size: 18px; font-weight:400;">
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
                                data-table-info-title="Showing from $1 to $2 of $3 Gatepass Item List"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                                data-on-table-create = "">
                            <thead>
                                <!-- <th>Sl</th>						 -->
                            					
                                <th class="text-center sortable-column">Item Name</th>						
                                <th class="text-center sortable-column">Item Group</th>						
                                <th class="text-center sortable-column">Created By</th>						
                                <th class="text-center sortable-column">Unit</th>						
                               
                                <!-- <th class="text-center">Action</th> -->
                                <!-- <th></th> -->
                            </thead>
                            <tbody class="text-center">
                            <?php
                                   
                                    $data=$accessoriesModel->getData("select i.*,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = i.created_by) AS USER_NAME 
                                    ,(SELECT unit FROM vw_all_employeeinformation@crypton U WHERE U.empid = i.created_by) AS unit
                                    from erp.INV_Item_name i where (created_by = '$userId'
                                    OR created_by IN ('$managerFeature') OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR 
                                    (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')");
                                 
                                    $i=0;
                                    foreach($data as $row){
                                        $i++;
                            ?>
                                <tr class="text-center data-font">
                               

                                    <td class="text-center data-font"><?php echo $row['NAME'] ?></td>
                                    <td class="text-center data-font"><?php echo $row['TYPE'] ?></td>
                                    <td class="text-center data-font"><?php echo $row['USER_NAME'] ?></td>
                                    <td class="text-center data-font"><?php echo $row['UNIT'] ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <br><br><br><br>
                        <div class="report">
                            <p style=" line-height: 0">---------------</p>
                            <p style=" line-height: 0">Approved By</p>
                        </div>
                        <?php
    else:
    ?>
    <div class="input-small row mt-3">
        <div class="input-small cell-md-12 d-flex flex-justify-center flex-align-center">
            <div class="input-small display1 m-2 text-center text-bold" style="color: #d4d4d4;">FKL Gate Pass System</div>
        </div>
    </div>
    <?php
    endif;
else:
    $auth->redirect403();
endif;
?>
                </div>
            <!-- </div> -->
        </div>
<?php include_once('inc/footer.php'); ?>

<script>
$(document).ready(function() {
    $('#print-js').click(function(e){
   $('.report').css('display','block')
    });

});
</script>
</body>
</html>