<!DOCTYPE html>
<?php
// error_reporting(E_ALL);
// ini_set('display_errors', '1');
ini_set('memory_limit', '-1');
include_once('inc/head.php');

use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;

$accessoriesModel = new accessoriescrud($db->con);
if ($auth->authUser()):
    if ($auth->verifyNavigationPermission('draft workorder') || $auth->verifyNavigationPermission('publish workorder') || $auth->verifyNavigationPermission('approved workorder') || $auth->verifyNavigationPermission('accepted workorder') || $auth->verifyNavigationPermission('all workorder') || $auth->verifyNavigationPermission('checked') || $auth->verifyUserPermission('checked', 9) || $auth->verifyUserPermission('checked', 2) || $auth->verifyUserPermission('checked', 3) || $auth->verifyUserPermission('checked', 4) || $auth->verifyUserPermission('checked', 5) || $auth->verifyUserPermission('checked', 6) || $auth->verifyUserPermission('checked', 1) || $auth->verifyUserPermission('accepted workorder', 2)):
        // if($auth->verifyUserPermission('publish workorder', 6) || $auth->verifyUserPermission('approved workorder', 6) || $auth->verifyUserPermission('accepted workorder', 6) || $auth->verifyUserPermission('all workorder', 6) || $auth->verifyUserPermission('checked', 6) || $auth->verifyUserPermission('checked', 9) || $auth->verifyUserPermission('checked', 2) || $auth->verifyUserPermission('checked', 4) || $auth->verifyUserPermission('checked', 3) || $auth->verifyUserPermission('checked', 5)):
        $accessoriesModel = new accessoriescrud($db->con);
        $userid = $auth->loggedUserId();
        $managerFeature = $auth->getManagerFeatureAll();
?>
        <style>
            .report {
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
                    <?php include_once('inc/topbar.php'); ?>
                    <div class="content-inner h-100" style="overflow-y: auto">
                        <div class="row-fluid">
                            <button id="exportBtn" class="button ml-2 small info">Export to Excel</button>
                            <div class="cell-md-12">
                                <div data-role="panel" data-title-caption="Approved List" data-collapsible="false" data-title-icon="<span class='mif-done_all'></span>">
                                    <div class="ml-1 mr-1">
                                        <div class="row">
                                            <div class="cell-sm-12 cell-md-12">
                                                <form action="" method="GET">
                                                    <div class="row no-gap">
                                                        <div style="width: 100px;background: #1a404d;color: #fff;padding-left: 5px;font-size: 14px;line-height: 26px;height: 27px;font-weight: bold;">
                                                            <span class="mif-filter icon"></span> Date Filter</span>
                                                        </div>
                                                        <input type="hidden" name="page" value="all-work-order">
                                                        <div style="width: 10%; margin-left: 5px">
                                                            <span style="width: 100%;display: block;float: right;"><input class="input-small" type="text" data-role="calendarpicker" data-cls-calendar="compact" placeholder="Select From Date" data-format="%d-%m-%Y" name="formdate" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?= date('d-m-Y') ?>" value="<?= $pageOpt->filterFormDate; ?>"></span>
                                                        </div>
                                                        <span style="margin-left: 5px; font-weight: bold;background: #d7d7d7;padding: 1px 5px;color: #1d1d1d;">To</span>
                                                        <div style="width: 10%; margin-left: 5px;">
                                                            <span style="width: 100%;display: block;float: right;"><input class="input-small" type="text" data-role="calendarpicker" name="todate" data-cls-calendar="compact" placeholder="Select To Date" data-format="%d-%m-%Y" data-input-format="%d-%m-%Y" data-clear-button="true" data-max-date="<?= date('d-m-Y') ?>" value="<?= $pageOpt->filterToDate; ?>"></span>
                                                        </div>
                                                        <div style="width: 10%; margin-left: 5px;">
                                                            <span style="width: 100%;display: block;float: right;"><input class="input-small" type="text" name="orderno" placeholder="Order No." value="<?= $pageOpt->filterOrderNo; ?>"></span>
                                                        </div>
                                                        <div style="width: 10%; margin-left: 5px;">
                                                            <span style="width: 100%;display: block;float: right;"><input class="input-small" type="text" name="pono" placeholder="P.O. No." value="<?= $pageOpt->filterPoNo; ?>"></span>
                                                        </div>
                                                        <div style="width: 10%; margin-left: 5px;">
                                                            <span style="width: 100%;display: block;float: right;"><input class="input-small" type="text" name="supplier" placeholder="Supplier Name" value="<?= $pageOpt->filterSupplier; ?>"></span>
                                                        </div>
                                                        <div style="width: 10%; margin-left: 5px;">
                                                            <span style="width: 100%;display: block;float: right;"><input class="input-small" type="text" name="wono" placeholder="W.O. No." value="<?= $pageOpt->filterWoNo; ?>"></span>
                                                        </div>

                                                        <button type="submit" class="image-button warning ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                            <span class='mif-spinner2 icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                            <span class="caption">Load Data</span>
                                                        </button>
                                                        <?php //if(isset($_SESSION['filterWhere'])): 
                                                        ?>
                                                        <!-- <a href="workorder.php?page=all-work-order" class="image-button alert ml-1 border bd-dark-hover" style="height: 27px;" name="workorder-date-filter">
                                                <span class='mif-cross icon' style="height: 27px; line-height: 27px; font-size: .9rem; width: 23px;"></span>
                                                <span class="caption">Clear Filter</span>
                                            </a> -->
                                                        <!-- <span class="success mt-2 tally ml-5">Showing data from <strong><?php //echo $pageOpt->filterFormDate;
                                                                                                                                ?></strong> to <strong><?php //echo $pageOpt->filterToDate;
                                                                                                                                                                                            ?></strong></span> -->
                                                        <?php //endif; 
                                                        ?>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <table
                                            class="table striped table-border cell-border subcompact mt-1 accessories-table-common approved-list-workorder-table"
                                            id="approved-list-workorder-table"
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
                                            data-cls-table-container="approved-list-workorder-table"
                                            data-source="data/approved-list.php"
                                            data-horizontal-scroll="true"
                                            data-table-info-title="Showing from $1 to $2 of $3 Approved Work Order(s)"
                                            data-on-draw="preloaderClose()"
                                            data-on-data-load="preloaderStart()">
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
                    </div>
                </div>
                <?php include_once('inc/footer.php'); ?>
                <!-- <script>
            $(document).ready(function() {
                $('#exportBtn').click(function() {
                    var table = document.getElementById('approved-list-workorder-table');
                    var wb = XLSX.utils.table_to_book(table);
                    var wbout = XLSX.write(wb, { bookType: 'xlsx', bookSST: true, type: 'binary' });
                    function s2ab(s) {
                        var buf = new ArrayBuffer(s.length);
                        var view = new Uint8Array(buf);
                        for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                        return buf;
                    }
                    saveAs(new Blob([s2ab(wbout)], { type: 'application' }), 'Work-Order.xlsx');
                    // saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'Work-Order.xlsx');
                });
            });
        </script> -->


                <script>
                    $(document).ready(function() {
                        $("#exportBtn").click(function() {
                            // Extract table data
                            var data = [];
                            var rows = $("#approved-list-workorder-table tr");

                            rows.each(function() {
                                var row = [];
                                $(this).find('th, td').each(function() {
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
                            XLSX.writeFile(wb, 'Work-Order.xlsx');
                        });
                    });
                </script>


        </body>