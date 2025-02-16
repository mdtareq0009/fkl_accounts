<!DOCTYPE html>
<?php
// ini_set('memory_limit', '-1');
include_once('inc/head.php');
use accessories\dependentdata;
use accessories\accessoriescrud;
use accessories\workorderoperation;
$accessoriesModel = new accessoriescrud($db->con);
$orderSearch='';
if(isset($_POST['btn']))
    $orderSearch = isset($_POST['ordersearch']) ? $_POST['ordersearch'] : '';

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
                        <h4 class="dashboard-section-title text-center text-left-md w-100 content-title-big" data-group="work-order" data-page="<?=$pageOpt->currentPageClass()?>" style="font-size: 1.2rem; line-height: 1; font-weight: bold; text-shadow: 1px 1px 2px #fff;"><span class="icon"><span class="mif-assignment"></span></span> Work Order</h4>
                    </div>
                        <div class="cell-md-8">
                            <div class="ml-5">
                                <a href="./reports/topsheet.php?orderSearch=<?php echo $orderSearch; ?>" class="button float-left info small ml-5 text-center"> Get Top Sheet pdf</a>
                                <button id="exportBtn" class="button ml-2 small info">Export to Excel</button>
                            </div>
                        </div>
                    </div>
                <!-- This is content -->
                <form action="" method="post">
                    <div class="row ml-5">
                        <div class="cell-md-8">
                            <div class="form-group">
                                <input type="text" name="ordersearch" placeholder="Order Search By : 12345,67890">
                            </div>
                        </div>
                        <div class="cell-md-2">
                            <div class="form-group">
                                <input type="submit" name="btn" value="Submit" class=" button">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card">
                    <div class="container" id="print-js">
                        <table class="table row-border cell-border border row-hover cell-hover subcompact tableData" id="dataTable">
                            <thead>
                                <th class="text-center">Sl.</th>
                                <th class="text-center">Order No.</th>
                                <th class="text-center">Buyer Name</th>
                                <th class="text-center">P.O. Number</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Supplier</th>
                                <th class="text-center">Item Name</th>
                                <th class="text-center">Qty</th>
                                <th class="text-center">Unit</th>
                            </thead>
                            <tbody>
                            <?php
                            $orderSearch = "'" . str_replace(",", "','", addslashes($orderSearch)) . "'";
                                if(!empty($orderSearch))
                                    $data=$accessoriesModel->getData("select distinct  m.vordernumberorfklnumber as orderno, m.vponumber,m.nid,s.vname as supplier,m.ntopsheet
                                    ,i.ntotalqty as itemqty,vtodate as to_date
                                    ,g.vname as goods,i.ntotalgarmentsqty as garmentsqty,i.vqtyunit as unit
                                    ,o.vname as buyername
                                    ,vtype
                                    from accessories_workordermaster m 
                                    left join accessories_suppliers s on m.nsupllierid = s.nid
                                    left join accessories_workorderitems i on m.nid=i.nworkordermasterid
                                    left join accessories_workorderitemdata d on i.nid=d.nworkorderitemsid
                                    left join accessories_goods g on i.ngoodsid=g.nid
                                    left join erp.mer_vw_orderinfo o on  m.vordernumberorfklnumber=o.vordernumber
                                    left join erp.mer_ks_master k on o.norderid=k.nordercode
                                    where m.vstatus='publish' 
                                    and m.vordernumberorfklnumber in (
                                    --and k.nks_id in (
                                    $orderSearch) order by m.vordernumberorfklnumber asc
                                    ");
                                        
                                    $i=0;
                                    if ($data === null || empty($data) || $data=='Table is empty...') {
                                        echo "";
                                    }else{
                                    foreach($data as $row){
                                        $i++;
                                        $orderorfklno = $row['ORDERNO'];
                                        $buyerName = $row['BUYERNAME'];
                            ?>
                                <tr>
                                    <td class="text-center"><?php echo $i; ?></td>
                                    <input type="text" hidden id="orderorfkl" value="<?php $row['ORDERNO'] ?>">
                                    <input type="text" hidden id="buyer" value="<?php $row['BUYERNAME'] ?>">
                                    <td class="text-center"><?php echo $row['ORDERNO'] ?></td>
                                    <td class="text-center"><?php echo $row['BUYERNAME'] ?></td>
                                    <td class="text-center"><?php echo $row['VPONUMBER'] ?></td>
                                    <td class="text-center"><?php echo $row['TO_DATE'] ?></td>
                                    <td class="text-center"><?php echo $row['SUPPLIER'] ?></td>
                                    <td class="text-center"><?php echo $row['GOODS'] ?></td>
                                    <td class="text-center"><?php echo $row['ITEMQTY'] ?></td>
                                    <td class="text-center"><?php echo $row['UNIT'] ?></td>
                                </tr>
                                <?php } } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<?php include_once('inc/footer.php'); ?>

<script src="./js/FileSaver.min.js"></script>
<script>
// $('#exportBtn').click(function() {
//     // Get the table element
//     var table = document.getElementById('dataTable');

//     // Iterate over each cell in the table
//     for (var i = 0; i < table.rows.length; i++) {
//         for (var j = 0; j < table.rows[i].cells.length; j++) {
//             // Convert all cell content to strings to treat dates as text
//             table.rows[i].cells[j].innerText = table.rows[i].cells[j].innerText.toString();
//         }
//     }

//     // Convert the table to a workbook
//     var wb = XLSX.utils.table_to_book(table);

//     // Convert the workbook to binary data
//     var wbout = XLSX.write(wb, { bookType: 'xlsx', bookSST: true, type: 'binary' });

//     // Function to convert binary string to ArrayBuffer
//     function s2ab(s) {
//         var buf = new ArrayBuffer(s.length);
//         var view = new Uint8Array(buf);
//         for (var i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
//         return buf;
//     }

//     // Save the workbook as a file
//     saveAs(new Blob([s2ab(wbout)], { type: 'application/octet-stream' }), 'TopSheet.xlsx');
// });

$(document).ready(function () {
        $("#exportBtn").click(function () {
            // Extract table data
            var data = [];
            var rows = $("#dataTable tr");
            
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

    // $(document).ready(function() {
    //     $('.tableData').DataTable({
    //     dom: 'Bfrtip',
    //     responsive: true,
    //     buttons: [
    //         'copy', 'csv', 'excel', 'pdf', 'print'
    //     ],
    //     "lengthMenu": [[100, "All", 50, 25], [100, "All", 50, 25]],
    //     scrollY: '700px',
    //     paging: true,
    //     });
    // });

</script>

</body>
</html>