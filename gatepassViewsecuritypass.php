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
    .button-bg{
        background-color: #004d6f;
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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="work-order" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Gate Pass Management Approval</h4>
                    </div>
                    <div class="cell-md-8">
                        <a class="float-right button success small" href="gatepassEntry.php"> Create</a>
                    </div>
                </div>

                <form action="" method="post">
                    <div class="cell-md-12">
                        <div class="row">
                                <div class="cell-md-2 cell-sm-2 cell-sm-2 float-left">
                                    <input type="text" name="date1" id="date1" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" data-clear-button="true" data-input-format="%d-%m-%y" value="<?=date('d-m-Y')?>" class="input-small" data-role="input">
                                </div>
                                <div class="cell-md-2 cell-sm-2 cell-lg-2">
                                    <input type="text" name="date2" id="date2" class="input-small" data-role="calendarpicker" data-cls-calendar="compact" data-format="%d-%m-%Y" data-clear-button="true" data-input-format="%d-%m-%y" value="<?=date('d-m-Y')?>" data-role="input">
                                </div>
                                <div class="cell-md-2 cell-sm-2 cell-lg-2">
                                    <input type="text" name="gp_no" id="gp_no" class="input-small" data-role="input" placeholder="GP No.">
                                </div>
                                <div class="cell-md-2 cell-sm-2 cell-lg-2">
                                    <input type="submit" name="submit" id="submit" class="button small alert">
                                </div>
                            </div>
                        </div>
                </form>
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
                                data-table-info-title="Showing from $1 to $2 of $3 Gatepass List"
                                data-on-draw="preloaderClose()"
                                data-on-data-load="preloaderStart()"
                                data-on-table-create = "">
                            <thead>
                                <!-- <th>Sl</th>						 -->
                                <th class="text-center sortable-column">Approved By</th>						
                                <th class="text-center sortable-column">Gp. No.</th>						
                                <!-- <th class="text-center sortable-column">Rev no.</th>						 -->
                                <th class="text-center sortable-column">Date</th>						
                                <th class="text-center sortable-column">User</th>						
                                <th class="text-center sortable-column">Order Info</th>
                                <th class="text-center sortable-column">Quantity</th>
                                <th class="text-center sortable-column">To</th>
                                <!-- <th class="text-center sortable-column">O.Price</th> -->
                                <th class="text-center sortable-column">Address</th>
                                <th class="text-center sortable-column">Returnable</th>
                                <th class="text-center">Action</th>
                                <!-- <th></th> -->
                            </thead>
                            <tbody class="text-center">
                            <?php
                              if(isset($_POST['date1']))
                              $date1 = $_POST['date1'];
                              if(isset($_POST['date2']))
                              $date2 = $_POST['date2'];
                              if(isset($_POST['gp_no']))
                              $gp_no = $_POST['gp_no'];
                          
                          if(isset($_POST['submit'])){
                            if(!empty($gp_no)){
                                    $data=$accessoriesModel->getData("select m.id,to_recipient,m.gp_no,gp_date,m.userid,total_qty,address,LISTAGG(orderinformation,',') within group(order by i.id asc) as orderinfo,security_pass_status,security_pass_by
                                    ,returnable,RECEIVED_STATUS,inventory_approved_status,head_of_department_status,approved_status
                                    ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS USER_NAME
                                    from gatepass_itemdata i left join gatepass_masterdata m on m.id=i.gp_master_id
                                    where inventory_approved_status=1 and m.gp_no='$gp_no' and (m.DELETE_STATUS=0 or m.delete_status=null) and (created_by = '$userId'
                                    OR created_by IN ('$managerFeature') OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR 
                                    (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')
                                    group by m.gp_no,to_recipient,gp_date,m.userid,total_qty,address,m.id,returnable,RECEIVED_STATUS,inventory_approved_status,head_of_department_status,approved_status,security_pass_status,security_pass_by order by m.gp_no desc");
                            }else{
                                    $data=$accessoriesModel->getData("select m.id,to_recipient,m.gp_no,gp_date,m.userid,total_qty,address,LISTAGG(orderinformation,',') within group(order by i.id asc) as orderinfo,security_pass_status,security_pass_by
                                    ,returnable,RECEIVED_STATUS,inventory_approved_status,head_of_department_status,approved_status
                                    ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS USER_NAME
                                    from gatepass_itemdata i left join gatepass_masterdata m on m.id=i.gp_master_id
                                    where inventory_approved_status=1 and to_date(m.gp_date,'dd/mm/yyyy') between to_date('$date1','dd/mm/yyyy') and to_date('$date2','dd/mm/yyyy') and (m.DELETE_STATUS=0 or m.delete_status=null) and (created_by = '$userId'
                                    OR created_by IN ('$managerFeature') OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR 
                                    (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')
                                    group by m.gp_no,to_recipient,gp_date,m.userid,total_qty,address,m.id,returnable,RECEIVED_STATUS,inventory_approved_status,head_of_department_status,approved_status,security_pass_status,security_pass_by order by m.gp_no desc");
                          }
                        }else{
                                        $data=$accessoriesModel->getData("select m.id,to_recipient,m.gp_no,gp_date,m.userid,total_qty,address,LISTAGG(orderinformation,',') within group(order by i.id asc) as orderinfo,security_pass_status,security_pass_by
                                        ,returnable,RECEIVED_STATUS,inventory_approved_status,head_of_department_status,approved_status
                                        ,(SELECT empname FROM vw_all_employeeinformation@crypton U WHERE U.empid = m.userid) AS USER_NAME
                                        from gatepass_itemdata i left join gatepass_masterdata m on m.id=i.gp_master_id
                                        where inventory_approved_status=1 and (security_pass_status = '0' or security_pass_status is null) and (m.DELETE_STATUS=0 or m.delete_status=null) and (created_by = '$userId'
                                        OR created_by IN ('$managerFeature') OR (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'admin' OR 
                                        (SELECT vrole FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$userId') = 'super admin')
                                        group by m.gp_no,to_recipient,gp_date,m.userid,total_qty,address,m.id,returnable,RECEIVED_STATUS,inventory_approved_status,head_of_department_status,approved_status,security_pass_status,security_pass_by order by m.gp_no desc");
                                    }
                              
                                    $i=0;
                                    foreach($data as $row){
                                        $i++;
                            ?>
                                <tr class="text-center data-font">
                                    <!-- <td class="text-center data-font"><?php //echo $i; ?></td> -->
                                    <td class="text-center data-font">
                                    <?php if ($row['SECURITY_PASS_STATUS'] == 0 or $row['SECURITY_PASS_STATUS']== null ){ ?>
                                        <a onclick="securitypassData(this.id)" class="button small warning fg-white security-page rounded mif-done" id="<?php echo $row['ID'] ?>" title="Click to approve by Management Approval"></a> 
                                        <?php }else{ ?>
                                        <a class="button small button-bg fg-white rounded mif-done_all" id="<?php echo $row['ID'] ?>" title="Alrady approved by inventory"></a> 
                                        <?php } ?>
                                    <!-- <?php //if ($row['HEAD_OF_DEPARTMENT_STATUS'] == 0 or $row['HEAD_OF_DEPARTMENT_STATUS']== null ){ ?>
                                        <a onclick="headOfDepartmentData(this.id)" class="button small warning fg-white rounded mif-done" id="<?php //echo $row['ID'] ?>" title="Click to approve by head of department"></a> 
                                        <?php //}else{ ?>
                                        <a class="button small success fg-white rounded mif-done_all" id="<?php //echo $row['ID'] ?>" title="Alrady approved by head of department"></a> 
                                        <?php //} ?>
                                    <?php //if ($row['APPROVED_STATUS'] == 0 or $row['APPROVED_STATUS']== null ){ ?>
                                        <a onclick="approvedData(this.id)" class="button small warning fg-white rounded mif-done" id="<?php //echo $row['ID'] ?>" title="Click to approve "></a> 
                                        <?php //}else{ ?>
                                        <a class="button small success fg-white rounded mif-done_all" id="<?php //echo $row['ID'] ?>" title="Alrady approved"></a> 
                                        <?php //} ?> -->
                                    </td>
                                    <td class="text-center data-font">
                                        <?php echo $row['GP_NO'] ?>
                               
                                    </td>
                            
                                    <td class="text-center data-font"><?php echo $row['GP_DATE'] ?></td>
                                    <td class="text-center data-font"><?php echo $row['USER_NAME'] ?></td>
                                    <td class="text-center data-font"><?php echo $row['ORDERINFO'] ?></td>
                                  
                               
                                    <td class="text-center data-font"><?php echo $row['TOTAL_QTY'] ?></td>
                                    <!-- <td class="text-center data-font"><?php //echo $row['OFFERPRICE'] ?></td> -->
                                    <td class="text-center data-font"><?php echo $row['TO_RECIPIENT'] ?></td>
                                    <td class="text-center data-font"><?php echo $row['ADDRESS'] ?></td>
                                    <td class="text-center data-font"><?php echo isset($row['RETURNABLE']) ? 'Returnable':'Not Returnable' ?></td>
                                  
                                    <td class="text-center data-font">
                                        <a href="gatepassdetails.php?gp_master_id=<?php echo $row['ID'] ?>"  title="View Gatepass" target="_blank"><span class="mif-eye mr-2"></span></a>
                                        <a href="reports/gatepass_report.php?gp_master_id=<?php echo $row['ID'] ?>"  title="Gate Pass Report Pdf" target="_blank"><span class="mif-print ml-2"></span></a>
                                        <!-- <a href="copycostsheet.php?copy_id=<?php //echo $row['MPM_NO'] ?>" class="ml-2 mr-2" title="Copy Cost Sheet"><span class="mif-copy"></span></a> -->

                                        <?php //if ($row['PUBLISHED_STATUS'] == 0 or $row['PUBLISHED_STATUS']== null){ ?>
                                        <!-- <a href="editcostsheet.php?master_id=<?php //echo $row['MPM_NO'] ?>" class="ml-2 mr-2 edit" title="Edit Cost Sheet" id="<?php //echo $row['MPM_NO'].'edit' ?>"><span class="mif-pencil"></span></a> -->
                                        <?php //} ?>

                                        <?php //if ($row['APPROVAL_STATUS'] == 1){ ?>
                                        <!-- <a href="costsheetrevise.php?master_id=<?php //echo $row['MPM_NO'] ?>" class="ml-2 mr-2 revise alert  info" title="Revise Cost Sheet" id="<?php //echo $row['MPM_NO'].'revise' ?>"><span class="mif-pencil fg-red mif-2x"></span></a> -->
                                        <?php //} ?>
                                        <?php //if($auth->verifyUserPermission('trash', 1)):?>
                                        <!-- <a href="costsheetSubmit.php?deleteData=<?php //echo $row['MPM_NO'] ?>" title="Delete Cost Sheet" class=""><span class="mif-bin"></span></a> -->
                                        <?php //endif; ?>
                                    </td>
                                    <!-- <td class="text-center"><a href="" class="button small alert rounded">Delete</a></td> -->
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                        <br><br><br><br>
                        <div class="report">
                            <p style=" line-height: 0">---------------</p>
                            <p style=" line-height: 0">Approved By</p>
                        </div>
                    <!-- </div> -->
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