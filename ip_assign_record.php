<?php
include_once('inc/head.php');

use accessories\accessoriescrud;
use accessories\dependentdata;

if (!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    if ($auth->verifyNavigationPermission('types')):
        $accessoriesModel = new accessoriescrud($db->con);
        $appsDependent = new dependentdata($db->con);
?>



                <?php include_once('inc/topbar.php'); ?>

                <?php include_once('inc/navigation.php'); ?>

                <div class="content-wrapper" id="categories">

                    <div class="content-header px-3  py-0" style="background:#4B4376">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ol class="breadcrumb float-sm-left">
                                        <li class="breadcrumb-item"><a href="#" style="color: #fff;">Home</a></li>
                                        <li class="breadcrumb-item active" style="color: #ffdc2f;">IP Assign Record</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <section class="content">
                        <div class="container-fluid">
                            <?php

                            if ($_GET['page'] == 'all-ip-assign-record'):

                                if ($auth->verifyUserPermission('checked', 'employee') 
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):
                            ?>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card card-warning pt-2">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">IP Assign Record</h3>
                                                </div>
                                                <form id="dataForm">
                                                        <div class="card-body p-2" id="emp">
                                                                <div class="form-group row">
                                                                    <label for="vemployeecode" class="pr-4 pl-2 pb-2">Filter: </label>
                                                                    <div class="col-10 col-sm-1">
                                                                        <select name="" id="" class="form-control form-control-sm form-control-border searchtype" onchange="changeFilter()">
                                                                            <option value="">All</option>
                                                                            <option value="employee">By Employee</option>
                                                                            <option value="iplan">By IP Lan</option>
                                                                            <option value="type">By IP Lan Type</option>
                                                                        </select>
                                                                    </div>
                                               
                                                                        <label for="vemployeeid" class="col-4 col-sm-1 col-form-label employee" style="display: none;">Department</label>
                                                                        <div class="col-8 col-sm-2 employee" style="display: none;">
                                                                             <select  class="form-control form-control-sm department_id select2bs4" onchange="getEmployee()"  id="department_name"></select>
                                                                            
                                                                        </div>
                                                                        
                                                                        <label for="vemployeeid" class="col-4 col-sm-1 col-form-label employee" style="display: none;">Employee</label>
                                                                        <div class="col-8 col-sm-2 employee" style="display: none;">
                                                                            <select   class="form-control form-control-sm employee_id select2bs4"  id="employee_name"></select>
                                                                        </div>
                                                                        
                                                                        <label for="vemployeeid" class="col-4 col-sm-1 col-form-label iplan" style="display: none;">IP Lan Category</label>
                                                                        <div class="col-8 col-sm-2 iplan" style="display: none;">
                                                                            <select  class="form-control form-control-sm cat_id select2bs4" onchange="getIpLan()"  id="category_name"></select>
                                                                        </div>
                                                                        <label for="vemployeeid" class="col-4 col-sm-1 col-form-label iplan" style="display: none;">IP Lan</label>
                                                                        <div class="col-8 col-sm-2 iplan" style="display: none;">
                                                                        
                                                                            <select  class="form-control form-control-sm ip_lan_id select2bs4" id="ip_lan_name"></select>
                                                                        </div>

                                                                        <label for="vemployeeid" class="col-4 col-sm-1 col-form-label iplantype" style="display: none;">IP Lan Type</label>
                                                                        <div class="col-8 col-sm-2 iplantype" style="display: none;">
                                                                                <select  class="form-control form-control-sm ip_lan_type_id select2bs4" id="ip_lan_type_name"></select>
                                                                        </div>
                                                                        <label for="vemployeeid" class="pr-4 pl-2 pb-2" >Date</label>
                                                                        <div class="col-8 col-sm-1">
                                                                            <input type="date" id="assigndate" class="form-control form-control-sm form-control-border fromdate" placeholder="Enter Date"/>
                                                                        </div>
                                                                        <label for="vemployeeid" class="pr-4 pl-2 pb-2">to</label>
                                                                        <div class="col-8 col-sm-1">
                                                                            <input type="date" id="assigndate" class="form-control form-control-sm form-control-border todate" placeholder="Enter Date"/>
                                                                        </div>
                                                                
                                                                    
                                                                    <div class="col-12 col-sm-1">
                                                                        <button type="button" class="btn btn-primary btn-sm" id="openModal2" onclick="getIPLansassing()" style="width: 100%;">
                                                                        <i class="fas fa-search"></i> Search
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                   

                                    <div class="row" id="showrecord">
                                            <div class="col-md-12 col-lg-12">
                                                
                                                <div class="form-group row">
                                                    <div class="col-2 col-sm-1">
                                                        <button type="button" class="btn btn-success btn-sm" id="openModal2" style="width: 100%;">
                                                        <i class="fas fa-print"></i> Print
                                                        </button>
                                                    </div>
                                                    <div class="col-2 col-sm-1">
                                                        <button type="button" class="btn btn-danger btn-sm" onclick="getPDFFile()" id="openModal2" style="width: 100%;">
                                                        <i class="fas fa-print"></i> PDF
                                                        </button>
                                                    </div>
                                                </div>   
                                        </div>
                                        <div class="col-12">

                                            <div class="card">
                                                <div class="card-body table-responsive p-0" >
                                                    <table  class="table table-bordered table-striped example1 table-hover text-nowrap" id="getPDF">

                                                        <thead>
                                                            <tr>
                                                                <th>SL No.</th>
                                                                <th>Assign ID</th>
                                                                <th>Name</th>
                                                                <th>Department</th>
                                                                <th>Employee</th>
                                                                <th> Type</th>
                                                                <th>Category</th>
                                                                <th>IP Lan</th>
                                                                <th>Remarks</th>
                                                                <th>Status</th>
                                                                <th>Created At</th>
                                                                <th>Created By</th>
                                                                <th>Last Updated At</th>
                                                                <th>Last Updated By</th>
                                                                <?php if (($auth->verifyUserPermission('checked', 'emp_edit') == true) || ($auth->verifyUserPermission('checked', 'emp_del') == true) || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')) { ?>
                                                               
                                                                    <th>Action</th>
                                                                <?php } ?>

                                                            </tr>
                                                        </thead>
                                                        <tbody></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <!-- category Model start -->

                            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel1" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel1" >Add Department</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="departmentForm">
                                        <div class="modal-body">
                                                    <div class="card-body p-2" id="category">
                                                        <div class="form-group">
                                                            <label for="departmentname">Department Name</label>
                                                            <input type="text" id="departmentname" class="form-control form-control-sm form-control-border departmentname" placeholder="Enter Department Name" />
                                                        </div>
                                                        <div class="form-group text-right">
                                                            
                                                        </div>
                                                    </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-success  btn-sm">Save</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                                </div>
                                   
                                 <!-- category Model end -->
                            
                                 
                            <!-- brand Model start -->
                       
                            <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel2" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalLabel2">Add Designation</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="designationForm">
                                        <div class="modal-body">
                                                    <div class="card-body p-2" id="category">
                                                        <div class="form-group">
                                                            <label for="designationname">Designation Name</label>
                                                            <input type="text" id="designationname" class="form-control form-control-sm form-control-border designationname" placeholder="Enter Designation Name" />
                                                        </div>
                                                        <div class="form-group text-right">
                                                            
                                                        </div>
                                                    </div>
                                            </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-outline-success  btn-sm">Save</button>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                                   
                        <!-- brand Model end -->
                        
                            

                            <?php
                                else:
                                    $auth->redirect403();
                                endif;
                            else:
                                $pageOpt->redirectWithscript($pageOpt->pageFirst(), 'Requested page is invalid!');
                            endif;
                            ?>
                        </div>
                    </section>
                </div>

                <?php include_once('inc/footer.php'); ?>
                <script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>

                <script>
                    $(document).ready(function() {
                        var currentDate = new Date().toISOString().split('T')[0]; 
                        $('.todate').val(currentDate);
                        $('.fromdate').val(currentDate);

                        $('.select2bs4').select2({
                        theme: 'bootstrap4'
                        })
                       
                        var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                    

                        getIPLansassing();
                        getDepartment();
                        getCategory();
                        getIpLanType();

                        $(document).on('click', '.deleteId', function() {
                            var row = $(this).closest('tr'); 
                            var rowId = row.data('id');  
                            
                            let  types = {
                                 C_STATUS : 'd',
                                 N_ID :  rowId,
                                 csrf : '<?= $db->csrfToken() ?>'
                            }
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'Do you want to delete this record?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, delete it!',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {

                                    let quaryType = 'delete-ip-assign';
                                    let fd = new FormData();
                                    fd.append('type', JSON.stringify(types));
                                    fd.append('formName', quaryType);
                                    
                                    $.ajax({
                                        url: 'action/ip-assign-action.php',  
                                        type: 'POST',                   
                                        data: fd,                      
                                        contentType: false,             
                                        processData: false, 
                                        success: (response) => {
                                            Swal.fire('Deleted!', 'The record has been deleted.', 'success');

                                            // row.remove();
                                            getIPLansassing();
                                        },
                                        error: (err) => {
                                            console.error('Error deleting data:', err);
                                            Swal.fire('Error', 'There was an error deleting the record.', 'error');
                                        }
                                    });
                                }
                            });
                        });


                        
                    });

                    async  function resetfrom() {
                            $('#dataForm')[0].reset();
                            resetSelectBoxes();
                           
                    }


                    function changeFilter() {

                        var filter_type= $('.searchtype').val();
                     
                        if(filter_type=='employee'){
                            resetSelectBoxes();
                            $('.employee').show();
                            $('#showrecord').hide();
                            $('.iplan').hide();
                            $('.iplantype').hide();
                        }else if(filter_type=='iplan'){
                            resetSelectBoxes();
                            $('.iplan').show();
                            $('#showrecord').hide();
                            $('.employee').hide();
                            $('.iplantype').hide();
                        
                        }else if(filter_type=='type'){
                            resetSelectBoxes();
                            $('#showrecord').hide();
                            $('.iplantype').show();
                            $('.iplan').hide();
                            $('.employee').hide();
                        }else{
                            resetSelectBoxes();
                            $('#showrecord').hide();
                            $('.iplan').hide();
                            $('.iplantype').hide();
                            $('.employee').hide();
                        }
                           
                    }

                    function resetSelectBoxes() {
                        $('.department_id').val('').trigger('change');
                        $('.employee_id').val('').trigger('change');
                        $('.cat_id').val('').trigger('change');
                        $('.ip_lan_id').val('').trigger('change');
                        $('.ip_lan_type_id').val('').trigger('change');
                    }


                 

                    async function getPDFFile() {

                        var todate        = $('.todate').val();
                        var fromdate      = $('.fromdate').val();
                        var category_id   = $('.cat_id').val();
                        var department_id = $('.department_id').val();
                        var employee_id   = $('.employee_id').val();
                        var iplan_id      = $('.ip_lan_id').val();
                        var iplan_type_id = $('.ip_lan_type_id').val();


                        const filter = {
                            todate : todate,
                            fromdate : fromdate,
                            category_id : category_id !='' || category_id !=null ? category_id : '',
                            department_id : department_id !='' || department_id !=null ? department_id : '',
                            employee_id : employee_id !='' || employee_id !=null ? employee_id : '',
                            iplan_id : iplan_id !='' || iplan_id !=null ? iplan_id : '',
                            iplan_type_id : iplan_type_id !='' || iplan_type_id !=null ? iplan_type_id : ''
                        }

                        $.ajax({
                                url: 'data/iplanassign.php',  // URL to your backend endpoint
                                method: 'GET',
                                data: filter, 
                                dataType: 'json',
                                success: async function(res) {

                                    console.log(Array.isArray(res));  // Log to verify if response is an array

                                    if (!Array.isArray(res)) {
                                        alert('The response is not in expected format');
                                        return;
                                    }

                                    // When data is fetched, create the PDF
                                    const { PDFDocument } = PDFLib;

                                    // Create a new PDF document
                                    const pdfDoc = await PDFDocument.create();
                                    let page = pdfDoc.addPage([816, 1056]);
                                    const { height } = page.getSize();

                                    // Set up fonts and text
                                    const font = await pdfDoc.embedFont(PDFLib.StandardFonts.Helvetica);

                                    // Set initial yPosition for the table
                                    let yPosition = height - 30;
                                    const cellMargin = 5;
                                    const tableWidth = 1250;
                                    const columnWidths = [50, 80, 80, 80, 80, 80, 80, 80, 80, 80, 200, 200]; // Define widths for each column
                                    const columnTitles = ['ID', 'Date', 'Assign No', 'Department', 'Employee', 'IP Type', 'Category', 'IP LAN', 'Note', 'Assign Type', 'Created At', 'Updated At'];

                                    // Draw the table headers
                                    columnTitles.forEach((title, index) => {
                                        page.drawText(title, {
                                            x: 5 + columnWidths.slice(0, index).reduce((a, b) => a + b, 0), 
                                            y: yPosition,
                                            font,
                                            size: 8,
                                            color: PDFLib.rgb(0, 0, 0)
                                        });
                                    });

                                    yPosition -= 20;  // Move down for the row content

                                    // Draw the table rows
                                    res.forEach((user, index) => {
                                        const rowData = [
                                            index + 1 || 'N/A',  // Ensure fallback if the data is missing
                                            user.D_ASSIGN_DATE || 'N/A',
                                            user.V_IP_VLAN_ASSIGN_NO || 'N/A',
                                            user.DEPARTMENT_NAME || 'N/A',
                                            user.EMPLOYEE_NAME || 'N/A',
                                            user.IP_LAN_TYPE_NAME || 'N/A',
                                            user.IP_CATEGORY_NAME || 'N/A',  // Ensure this is available in the data
                                            user.IP_LAN_NAME || 'N/A',       // Ensure this is available in the data
                                            user.V_NOTE || 'N/A',
                                            user.V_CURRENT_ASSIGN_TYPE || 'N/A',
                                            user.DT_CREATED_AT || 'N/A',    // Ensure this is available in the data
                                            user.DT_UPDATED_AT || 'N/A'     // Ensure this is available in the data
                                        ];

                                        // Draw each column for the current row
                                        rowData.forEach((data, colIndex) => {
                                            page.drawText(String(data), {
                                                x: 2 + columnWidths.slice(0, colIndex).reduce((a, b) => a + b, 0) + cellMargin,
                                                y: yPosition,
                                                font,
                                                size: 8,
                                                color: PDFLib.rgb(0, 0, 0)
                                            });
                                        });

                                        yPosition -= 20;  // Move down for the next row

                                        // If yPosition goes too low, create a new page
                                        if (yPosition < 40) {
                                            page = pdfDoc.addPage([816, 1056]);  // Add a new page
                                            yPosition = height - 30;  // Reset position for the new page
                                        }
                                    });

                                    // Save the PDF and trigger the download
                                    const pdfBytes = await pdfDoc.save();
                                    const blob = new Blob([pdfBytes], { type: 'application/pdf' });
                                    const url = URL.createObjectURL(blob);

                                    const link = document.createElement('a');
                                    link.href = url;
                                    link.download = 'user_data_table.pdf';
                                    link.click();
                                },
                                error: function() {
                                    alert('Error fetching data from server');
                                }
                            });
                            
                    }
                  
                 

                   
                    function getDepartment() {
                        $('#department_name').empty();
                        $.ajax({
                            url: 'data/department.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                const row1 = `<option value="">Select Department</option>`;
                                $('#department_name').append(row1);
                                res.forEach((row, sl) => {
                                    const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                                    $('#department_name').append(row2);
                                });
                            }
                        });
                    }
                    function getIpLanType() {
                        $('#ip_lan_type_name').empty();
                        $.ajax({
                            url: 'data/ip_type.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                const row1 = `<option value="">Select IP Lan Type</option>`;
                                $('#ip_lan_type_name').append(row1);
                                res.forEach((row, sl) => {
                                    const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                                    $('#ip_lan_type_name').append(row2);
                                });
                            }
                        });
                    }


                    function getIpLan() {

                        var category_id = $('.cat_id').val();

                    
                        if(category_id =='' || category_id == null){
                            $('#ip_lan_name').empty();
                        }else{
                            $('#ip_lan_name').empty();
                            
                            $.ajax({
                                url: 'data/ip-lan.php',
                                type: 'GET',
                                data: {category_id:category_id}, 
                                dataType: 'json',
                                success: (res) => {
                                    const row1 = `<option value="">Select IP Lan</option>`;

                                    $('#ip_lan_name').append(row1);
                                 
                                    
                                    if(res != 'empty'){
                                        res.forEach((row, sl) => {
                                            const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                                            $('#ip_lan_name').append(row2);
                                        });
                                    }
                                }
                            });
                        }

                    }
               
                    function getEmployee() {

                        var department_id = $('.department_id').val();
                     

                        if(department_id=='' || department_id == null){
                            $('#employee_name').empty();
                        }else{
                            $('#employee_name').empty();
                            $.ajax({
                                url: 'data/employee.php',
                                type: 'GET',
                                data: {department_id:department_id}, 
                                dataType: 'json',
                                success: (res) => {
                                    const row1 = `<option value="">Select Employee</option>`;
                                    $('#employee_name').append(row1);
                                    res.forEach((row, sl) => {
                                        const row2 = `<option value="${row.N_ID}">${row.V_EMPLOYEE_NAME} - ${row.V_EMPLOYEE_ID}</option>`;
                                        $('#employee_name').append(row2);
                                    });
                                }
                            });
                        }


                    }

                    function getCategory() {
                        $('#category_name').empty();
                        $.ajax({
                            url: 'data/ip_category.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                const row1 = `<option value="">Select IP Category</option>`;
                                $('#category_name').append(row1);
                                res.forEach((row, sl) => {
                                    const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                                    $('#category_name').append(row2);
                                });
                            }
                        });
                    }


				
                
                    function getIPLansassing() {
                        var todate        = $('.todate').val();
                        var fromdate      = $('.fromdate').val();
                        var category_id   = $('.cat_id').val();
                        var department_id = $('.department_id').val();
                        var employee_id   = $('.employee_id').val();
                        var iplan_id      = $('.ip_lan_id').val();
                        var iplan_type_id = $('.ip_lan_type_id').val();


                        const filter = {
                            todate : todate,
                            fromdate : fromdate,
                            category_id : category_id !='' || category_id !=null ? category_id : '',
                            department_id : department_id !='' || department_id !=null ? department_id : '',
                            employee_id : employee_id !='' || employee_id !=null ? employee_id : '',
                            iplan_id : iplan_id !='' || iplan_id !=null ? iplan_id : '',
                            iplan_type_id : iplan_type_id !='' || iplan_type_id !=null ? iplan_type_id : ''
                        }



                        $.ajax({
                            url: 'data/iplanassign.php',
                            type: 'GET',
                            dataType: 'json',
                            data: filter, 
                            success: (res) => {
                                console.log(res);
                                $('#showrecord').show();
                                $('.example1 tbody').empty();
                                if(res!="empty"){
                                res.forEach((row, sl) => {
                                    const row1 = `<tr data-id="${row.N_ID}">
                                            <td>${sl + 1}</td>
                                            <td>${row.D_ASSIGN_DATE}</td>
                                            <td>${row.V_IP_VLAN_ASSIGN_NO}</td>
                                            <td>${row.DEPARTMENT_NAME}</td>
                                            <td>${row.EMPLOYEE_NAME}</td>
                                            <td>${row.IP_LAN_TYPE_NAME}</td>
                                            <td>${row.IP_CATEGORY_NAME}</td>
                                            <td>${row.IP_LAN_NAME}</td>
                                            <td>${row.V_NOTE}</td>
                                            <td>${row.V_CURRENT_ASSIGN_TYPE}</td>
                                            <td>${row.DT_CREATED_AT}</td>
                                            <td>${row.CREATEDUSER}</td>
                                            <td>${row.DT_UPDATED_AT}</td>
                                            <td>${row.UPDATEDUSER?row.UPDATEDUSER:'N/A'}</td>
                                            <td align="center">
                                             <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'emp_edit') == true)) { ?>
                                                <a href= "ip_lan_assign.php?page=all-ip-lan-assign&id=${row.N_ID}">
                                                <button class="btn btn-outline-primary btn-xs" 
                                                data-row="${encodeURIComponent(JSON.stringify(row))}" 
                                                onClick="editType(this)">
                                                <i class="fas fa-pen"></i>
                                                </button>
                                                </a>
                                                <?php  } ?>
                                                <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'emp_del') == true)) { ?>
                                                <button class="btn  btn-outline-danger btn-xs deleteId">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <?php  } ?>
                                            </td>
                                        </tr>`;

                                        $('.example1 tbody').append(row1);
                                    });
                                }else{
                                    const emptyrow1 = `<tr>
                                        <td colspan="15" style="text-align: center;">
                                        <span>No Data Found</span>
                                        </td>
                                    </tr>`;
                                    $('.example1 tbody').append(emptyrow1);
                                }


                                // table.clear();
                                // table.rows.add($('.example1 tbody tr'));
                                // table.draw();
                            },
                            error: (err) => {
                                console.error("Error loading data:", err);
                                console.error("Response:", err.responseText);
                            }
                        });
                    }

          

                    function editType(data) {
                        const rowData = JSON.parse(decodeURIComponent(data.getAttribute('data-row')));
                            console.log(rowData);
                        $('#emp .employee_code').val(rowData.V_EMPLOYEE_CODE); 
                        $('#emp .employee_id').val(rowData.V_EMPLOYEE_ID); 
                        $('#emp .v_name').val(rowData.V_EMPLOYEE_NAME); 
                        $('#emp .v_mobile_no').val(rowData.V_MOBILE_NO); 
                        $('#emp .v_pbix_no').val(rowData.V_PBIX_NO); 
                        $('#emp .v_address').val(rowData.V_ADDRESS);
                        $('.department_id').val(rowData.DEPARTMENT_ID).trigger('change.select2');
                        $('.designation_id').val(rowData.DESIGNATION_ID).trigger('change.select2');
                        $('.nid').val(rowData.N_ID);     
                    }

                </script>
        <?php
    else:
        $auth->redirect403();
    endif;
endif;
?>