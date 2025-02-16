<!DOCTYPE html>
<?php
// ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
// $accessoriesModel = new accessoriescrud($db->con);
if($auth->authUser()):
	if($auth->verifyUserPermission('checked', 7)):
		$accessoriesModel = new accessoriescrud($db->con);
		$userid = $auth->loggedUserId();
		$managerFeature = $auth->getManagerFeatureAll();
?>
<style>
    .report{
        display: none;
    }
</style>
<script src="./js/xlsx.full.min.js"></script>

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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="work-order" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Work Order Top Sheet List</h4>
                    </div>
                </div>

                <!-- <div class="card"> -->
                    <div class="ml-2 mr-2" style="background-color: white;" id="print-js">
                        <!-- <table class="table row-border cell-border border row-hover cell-hover subcompact tableData" data-role="table" id="dataTable"> -->
                            <table 
                class="table striped table-border cell-hover row-hover cell-border subcompact mt-1 accessories-table-common topsheet-workorder-table" data-role="table" id="topsheet-workorder-table"
                data-cls-table-top="row"
                data-cls-search="cell-md-7 cell-sm-6"
                data-cls-rows-count="cell-md-5 cell-sm-6"
                data-rows="20"
                data-rows-steps="-1, 20, 30, 50, 100, 150"
                data-show-activity="false"
                data-rownum-title="No."
                data-rownum="true"
                data-search-threshold="1000"
                data-cls-table-container="management-workorder-table"
                data-horizontal-scroll="true"
                data-table-info-title="Showing from $1 to $2 of $3 Topsheet Work Order(s)"
                data-on-draw="preloaderClose()"
                data-on-data-load="preloaderStart()">
                            <thead>
                                <!-- <th class="text-center">Sl.</th> -->
                                <th class="text-center">Topsheet No</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Name</th>
                                <th class="text-center">Action</th>
                            </thead>
                            <tbody>
                            <?php
                                    $data=$accessoriesModel->getData("select 
                                    DISTINCT to_number(VTOPSHEETNO) as VTOPSHEETNO,
                                    VTOPSHEETDATE,H.VEMPNAME 
                                    from accessories_workordermaster M 
                                    LEFT JOIN hrm_employee H ON M.VTOPSHEETUSER = H.VEMPLOYEEID 
                                    WHERE VTOPSHEETNO IS NOT NULL order by to_number(vtopsheetno) desc
                                    ");
                                        
                                    $i=0;
                                    if ($data === null || empty($data) || $data=='Table is empty...') {
                                        echo "";
                                    }else{
                                    foreach($data as $row){
                                        $i++;
                            ?>
                                <tr>
                                    <!-- <td class="text-center"><?php //echo $i; ?></td> -->
                                    <td class="text-center"><?php echo 'Topsheet-No-'.$row['VTOPSHEETNO'] ?></td>
                                    <td class="text-center"><?php echo $row['VTOPSHEETDATE'] ?></td>
                                    <td class="text-center"><?php echo $row['EMPNAME'] ?></td>
                                    <td class="text-center">
                                        <a href="./reports/topsheet-new.php?topsheetno=<?php echo $row['VTOPSHEETNO']; ?>" title="View Topsheet" class="button float-left info small ml-5 text-center"><span class="mif-eye"></span></a>
                                        <!-- <button id="exportBtn" class="button ml-2 small info">Export to Excel</button> -->
                                    </td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                <!-- </div> -->
            <!-- </div> -->
        </div>

<?php include_once('inc/footer.php'); ?>

<script src="./js/FileSaver.min.js"></script>
<script>
$(document).ready(function () {
        $("#exportBtn").click(function () {
            // Extract table data
            var data = [];
            var rows = $("#topsheet-workorder-table tr");
            
            rows.each(function () {
                var row = [];
                $(this).find('th, td').each(function () {
                    row.push($(this).text());
                });
                data.push(row);
            });
            
            // Create a worksheet from the array of arrays
            var ws = XLSX.utils.aoa_to_sheet(data);
            
            // Create a new workbook
            var wb = XLSX.utils.book_new();
            
            // Append the worksheet to the workbook
            XLSX.utils.book_append_sheet(wb, ws, 'Sheet1');
            
            // Export the workbook to Excel
            XLSX.writeFile(wb, 'Topsheet.xlsx');
        });
    });
</script>

</body>
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
</html>