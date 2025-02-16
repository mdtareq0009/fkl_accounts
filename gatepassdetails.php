<!DOCTYPE html>
<?php
ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);
$managerFeature = $auth->getManagerFeature();
$userId = $auth->loggedUserId();
if(isset($_GET['gp_master_id']))
$gp_master_id = $_GET['gp_master_id'];
if(isset($_GET['success']) && $_GET['success']==true)
echo "<script>alert('Successfully Inserted')</script>";
elseif(isset($_GET['update']) && $_GET['update']==true)
echo "<script>alert('Successfully Updated')</script>";
?>
<style>
    .report{
        display: none;
    }
    .bg-color{
        background-color: #e4e4e4;
        font-weight: 600;
        font-size: 14px;
        font-family: 'Times New Roman', Times, serif;
        /* color:#fff; */
    }
    .data-font{
        font-weight: 400;
        font-size: 14px;
        font-family: 'Times New Roman', Times, serif;
    }
    p{
        font-weight: 400;
        font-size: 18px;
        font-family: 'Times New Roman', Times, serif;
    }
    .custom-button-bg{
        background-color: #004d6f !important;
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
            <div class="card" style="font-size: 20px;">
            <div class="content-inner h-100" style="overflow-y: auto">
                <div class="row border-bottom bd-lightGray pl-1 mr-1 ribbed-lightGray" style="margin-left: 0px;">
                    <div class="cell-md-8">
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Gate Pass Details</h4>
                    </div>
                    <div class="float-right">
                        <a href="reports/gatepass_report.php?gp_master_id=<?php echo $gp_master_id ?>"  title="Gate Pass Report Pdf" target="_blank" class="button small secondary rounded"><span class="mif-print ml-2"></span> Print</a>
                        <a href="gatepassEntry.php"  title="Create Gate Pass" target="_blank" class="button small success rounded"><span class="mif-plus ml-2"></span> Create</a>
                        <?php 
                             $checkEditPermission = $accessoriesModel->getData("select * from gatepass_masterdata where id=$gp_master_id");
                             if($checkEditPermission[0]['HEAD_OF_DEPARTMENT_STATUS'] != 1){
                        ?>
                        <a href="editGatepass.php?gp_master_id=<?php echo $gp_master_id ?>"  title="Edit Gate Pass" target="_blank" class="button small info rounded"><span class="mif-pencil ml-2"></span> Edit</a>
                        <?php } ?>
                    </div>
                </div>
                
                        <?php
                            $masterData = $accessoriesModel->getData("select m.id,to_recipient,m.gp_no,gp_date,m.userid,total_qty,address,LISTAGG(orderinformation,',') within group(order by i.id asc) as orderinfo,m.return_date
                            ,FROM_SOURCE,m.received_status,m.received_by,m.created_by,inventory_approved_by,inventory_approved_status
                            ,HEAD_OF_DEPARTMENT_STATUS,HEAD_OF_DEPARTMENT_BY,APPROVED_BY,APPROVED_STATUS,SECURITY_PASS_STATUS,SECURITY_PASS_BY
                            ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS USER_NAME
                            ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.received_by) AS received_NAME
                            ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.created_by) AS created_NAME
                            ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.inventory_approved_by) AS inventory_NAME
                            ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.HEAD_OF_DEPARTMENT_BY) AS headofdepartment_NAME
                            ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.approved_BY) AS approved_NAME
                            ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.security_pass_BY) AS security_NAME
                            ,(SELECT designation FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS DESIGNATION
                            ,(SELECT EMPNAME FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.SECURITY_PASS_BY) AS SECURITY_NAME
                            from gatepass_itemdata i left join gatepass_masterdata m on m.id=i.gp_master_id and (m.DELETE_STATUS=0 or m.delete_status=null)
                            where m.id=$gp_master_id and (m.DELETE_STATUS=0 or m.delete_status=null)
                            group by m.gp_no,to_recipient,gp_date,m.userid,total_qty,address,m.id,FROM_SOURCE,m.received_status,m.received_by,m.created_by
                            ,inventory_approved_by,inventory_approved_status,HEAD_OF_DEPARTMENT_STATUS,HEAD_OF_DEPARTMENT_BY,APPROVED_BY,APPROVED_STATUS,SECURITY_PASS_STATUS
                            ,SECURITY_PASS_BY,M.RETURN_DATE");
                        ?>
                        <!-- <div class="container"> -->

                        <!-- </div> -->
                    <div class="cell-md-10 cell-lg-10 cell-sm-10 float-left">
                       
                    <table class="table border row-border cell-border subcompact" style="background-color: white;">
                        <tr>
                            <td class="bg-color">Gp No.</td>
                            <td class="data-font"><?php echo $masterData[0]['GP_NO'] ?></td>
                            <td class="bg-color">Date</td>
                            <td class="data-font"><?php echo $masterData[0]['GP_DATE'] ?></td>
                            <td class="bg-color">Address</td>
                            <td class="data-font"><?php echo $masterData[0]['ADDRESS'] ?></td>
                            <td class="bg-color">Return Date</td>
                            <td class="data-font"><?php echo isset($masterData[0]['RETURN_DATE']) ? $masterData[0]['RETURN_DATE'] : 'Not Returnable' ?></td>
                        </tr>
                        <tr>
                            <td class="bg-color">To</td>
                            <td class="data-font"><?php echo $masterData[0]['TO_RECIPIENT'] ?></td>
                            <td class="bg-color">From</td>
                            <td class="data-font"><?php echo $masterData[0]['FROM_SOURCE'] ?></td>
                            <td class="bg-color">Name</td>
                            <td class="data-font"><?php echo $masterData[0]['USER_NAME'] ?></td>
                            <td class="bg-color">Designation</td>
                            <td class="data-font"><?php echo $masterData[0]['DESIGNATION'] ?></td>
                        </tr>
                    </table>
                    <table class="table border row-border cell-border subcompact" style="background-color: white;">
                        <th class="bg-color">Order Information</th>
                        <th class="bg-color">Goods Description</th>
                        <th class="bg-color">Quantity</th>
                        <th class="bg-color">Unit</th>
                        <th class="bg-color">Remark's</th>
                    <?php
                            $itemData = $accessoriesModel->getData("select i.*,(SELECT MRD_TRIM_UNIT_NAME FROM INV.MRD_TRIM_UNIT MTU WHERE MTU.MRD_TRIM_UNIT_ID= i.unit) AS unit_name
                            from gatepass_itemdata i where gp_master_id=$gp_master_id");
                            foreach($itemData as $item){
                        ?>
                        <tr>
                            <td><?php echo $item['ORDERINFORMATION'] ?></td>
                            <td><?php echo $item['DESCRIPTION'] ?></td>
                            <td><?php echo $item['QTY'] ?></td>
                            <td><?php echo $item['UNIT_NAME'] ?></td>
                            <td><?php echo $item['REMARKS'] ?></td>
                        </tr>
                        <?php } ?>
                        <tr class="bg-color" style="font-size: 18px;font-weight:600;">
                            <td></td>
                            <td class="text-center"><strong>Total Qty</strong></td>
                            <td><?php echo $masterData[0]['TOTAL_QTY'] ?></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <div class="cell-md-2 cell-lg-2 cell-sm-2 float-right bg-white mt-2">
                    <div class="card">
                        <table class="table row-border subcompact">
                            <tr>
                                <!-- <div class="card-header"> -->
                                    <p class="bg-color text-center" style="font-size: 22px;font-weight:600;">Current Status</p>
                                <!-- </div> -->
                            </tr>
                        <!-- ==================== -->
                        <tr>
                            <!-- <div> -->
                                <?php if(!empty($masterData[0]['HEAD_OF_DEPARTMENT_STATUS'])){ ?>
                                    <td>
                                        <p class="" style="color:green" title="<?php echo "Accepted By ".$masterData[0]['HEADOFDEPARTMENT_NAME'] ?>" >Dept. Head Accepted</p><p style="font-size:12px;text-align:center;margin-top:0px;"><?php echo "Accepted By ".$masterData[0]['HEADOFDEPARTMENT_NAME'] ?></p>
                                    </td>
                                    <?php }else{ ?>
                                        <td>
                                            <p class="float-left" style="color:#c8c8c8" id="departmentalhead">Dept. Head Accepted</p>
                                        </td>
                                            <?php if($auth->verifyUserPermission('gatepass', 4)){ ?>
                                                <td>
                                                    <a onclick="headOfDepartmentData(this.id)" class="button mini warning fg-white rounded mif-done float-right headofdepartment custom-button-bg" id="<?php echo $masterData[0]['ID'] ?>" title="Click to Accept by Dept. head"></a> 
                                                </td>
                                                <?php } } ?>
                                            <!-- </td> -->
                        <!-- </div> -->
                    </tr>
                        <!-- ==================== -->
                        <!-- <tr>
                            <div>
                                <?php //if(!empty($masterData[0]['APPROVED_STATUS'])){ ?>
                                    <h4 class="float-left" style="color:green" title="<?php //echo "Approved By ".$masterData[0]['APPROVED_NAME'] ?>"> Approved</h4>
                                <?php //}else{ ?>
                                <h4 class="float-left" style="color:#c8c8c8"> Approved</h4>
                                <?php //if($auth->verifyUserPermission('gatepass', 5)){ ?>
                            <a onclick="approvedData(this.id)" class="button mini warning fg-white rounded mif-done float-right mt-4" id="<?php //echo $masterData[0]['ID'] ?>" title="Click to Approve"></a> 
                            <?php //} } ?>
                        </div>
                    </tr> -->
                        <!-- ======================= -->
                        <tr>
                            <?php if(!empty($masterData[0]['INVENTORY_APPROVED_STATUS'])){ ?>
                                <td>
                                    <p class="" style="color:green" title="<?php echo "Received By ". $masterData[0]['INVENTORY_NAME'] ?>">Inventory Received</p><p style="font-size:12px;text-align:center;margin-top:0px;"><?php echo "Received By ".$masterData[0]['INVENTORY_NAME'] ?></p>
                                </td>
                                
                                <?php }else{ ?>
                                    <td>
                                        <p class="float-left" style="color:#c8c8c8" id="inventory">Inventory Received</p>
                                    </td>
                                    <?php if($auth->verifyUserPermission('gatepass', 3) && $masterData[0]['HEAD_OF_DEPARTMENT_STATUS']==1){ ?>
                                    <td>
                                        <a onclick="inventoryData(this.id)" class="button mini warning fg-white rounded mif-done float-right custom-button-bg inventory" id="<?php echo $masterData[0]['ID'] ?>" title="Click to Receive by inventory"></a> 
                                    </td>
                                <?php } } ?>
                        </tr>
                        <!-- ==================== -->
                        <tr>
                            <?php if(!empty($masterData[0]['SECURITY_PASS_STATUS'])){ ?>
                                <td>
                                    <p class="" style="color:green" title="<?php echo "Approved By ".$masterData[0]['SECURITY_NAME'] ?>">Management Approved</p><p style="font-size:12px;text-align:center;margin-top:0px;"><?php echo "Approved By ".$masterData[0]['SECURITY_NAME'] ?></p>
                                </td>
                                <?php }else{ ?>
                                <td>
                                    <p class="float-left" style="color:#c8c8c8" id="security">Management Approved</p>
                                </td>
                                <?php if($auth->verifyUserPermission('gatepass', 8) && $masterData[0]['INVENTORY_APPROVED_STATUS']==1){ ?>
                                <td>
                                    <a onclick="securitypassData(this.id)" class="button mini warning fg-white rounded mif-done float-right custom-button-bg security" id="<?php echo $masterData[0]['ID'] ?>" title="Click to Approve by Management"></a> 
                                </td>
                                <?php } } ?>
                        </tr>
                        <!-- ==================== -->
                    </table>
                </div>
                </div>
                </div>
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