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
                                        <li class="breadcrumb-item active" style="color: #ffdc2f;">Employees</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <section class="content">
                        <div class="container-fluid">
                            <?php

                            if ($_GET['page'] == 'all-employees'):

                                if ($auth->verifyUserPermission('checked', 'employee') 
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):
                            ?>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-10 offset-lg-1">
                                            <div class="card card-secondary pt-2">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">Employee Entry</h3>
                                                </div>
                                                <form id="dataForm">
                                                        <input type="text" id="csrf" hidden  value="<?= $db->csrfToken() ?>" name="csrf" />
                                                        <input type="text" id="nid" hidden class="nid" name="N_ID" />
                                                        <div class="card-body p-2 row" id="emp">
                                                            <div class="col-md-6 col-lg-6">

                                                                <div class="form-group row">
                                                                    <label for="vemployeecode" class="col-sm-3 col-form-label">Employee Code</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" id="vemployeecode" class="form-control form-control-sm form-control-border employee_code"  readonly/>
                                                                    </div>
                                                                </div>

                                                                 <div class="form-group row">
                                                                        <label for="vemployeeid" class="col-sm-3 col-form-label">Employee ID</label>
                                                                        <div class="col-sm-9">
                                                                        <input type="text" id="vemployeeid" class="form-control form-control-sm form-control-border employee_id" placeholder="Enter Employee ID"/>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group row">
                                                                    <label for="department_name" class="col-sm-3 col-form-label">Department</label>
                                                                    <div class="col-11 col-sm-8">
                                                                        <select  class="form-control form-control-sm department_id select2bs4"  id="department_name"></select>
                                                                    </div>
                                                                    <div class="col-1 col-sm-1">
                                                                        <button type="button" class="btn btn-outline-primary btn-sm"  id="openModal1" style="width: 100%;">
                                                                        <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>

                                                                <div class="form-group row">
                                                                    <label for="designation_name" class="col-sm-3 col-form-label">Designation</label>
                                                                    <div class="col-11 col-sm-8">
                                                                    <select   class="form-control form-control-sm designation_id select2bs4"  id="designation_name"></select>
                                                                    </div>
                                                                    <div class="col-1 col-sm-1">
                                                                        <button type="button" class="btn btn-outline-primary btn-sm" id="openModal2" style="width: 100%;">
                                                                        <i class="fas fa-plus"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 col-lg-6">
                                                                <div class="form-group row">
                                                                    <label for="vname" class="col-sm-3 col-form-label">Name</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" id="vname" class="form-control form-control-sm form-control-border v_name"  placeholder="Enter Name" />
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group row">
                                                                    <label for="designation_name" class="col-sm-3 col-form-label">Mobile</label>
                                                                    <div class="col-sm-9">
                                                                    <input type="number" id="vname" class="form-control form-control-sm form-control-border v_mobile_no" placeholder="Enter Mobile" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="designation_name" class="col-sm-3 col-form-label">PBIX No.</label>
                                                                    <div class="col-sm-9">
                                                                    <input type="number" id="vname" class="form-control form-control-sm form-control-border v_pbix_no" placeholder="Enter PBIX No." />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="vname" class="col-sm-3 col-form-label">Address</label>
                                                                    <div class="col-sm-9">
                                                                        <textarea name="vname" id="vname" class="form-control form-control-sm  form-control-border v_address" placeholder="Enter Address"></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group text-right">
                                                                    <button type="button" class="btn btn-outline-danger  btn-sm" onclick="resetfrom()">Reset</button>
                                                                    <button type="submit" class="btn btn-outline-success  btn-sm">Save</button>
                                                            </div>
                                                            </div>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">

                                            <div class="card">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">Product List</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body p-2">
                                                    <table id="example2" class="table table-bordered table-striped example1">

                                                        <thead>
                                                            <tr>

                                                                <th>SL No.</th>
                                                                <th>Emp. ID</th>
                                                                <th>Emp. Code</th>
                                                                <th>Name</th>
                                                                <th>Department</th>
                                                                <th>Designation</th>
                                                                <th>Mobile</th>
                                                                <th>PBIX No.</th>
                                                                <th>Address</th>
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
        
                <script>
                    $(document).ready(function() {

                            $('#openModal1').on('click', function() {
                            $('#modal1').modal('show'); 
                            });

                            $('#openModal2').on('click', function() {
                                $('#modal2').modal('show'); 
                            });

                        $('.select2bs4').select2({
                        theme: 'bootstrap4'
                        })
                       
                        var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                    
                        
                        // Initialize DataTable with buttons
                        let table = $('#example2').DataTable({
                                responsive: true,
                                pageLength: 10,  // Default number of records per page
                                lengthMenu: [10, 25, 50, 100],  // Options for records per page
                                paging: true,  // Enable pagination
                                lengthChange: true, // Allow users to change the number of rows per page
                                autoWidth: false,
                                dom: 'lBfrtip',  // 'l' for length menu, 'B' for buttons, 'f' for search, 'r' for processing, 't' for table, 'i' for info, 'p' for pagination
                                buttons: [
                                    'copy', 'csv', 'excel', 'pdf', 'print'
                                ],
                                language: {
                                    lengthMenu: 'Per Page: _MENU_'
                                }
                            });

                        getProducts(table);
                        getEmpCode();
                        getDepartment();
                        getDesination();

                        $(document).on('click', '.deleteId', function() {
                            var row = $(this).closest('tr'); 
                            var rowId = row.data('id');  
                            
                            let  types = {
                                 C_STATUS : 'd',
                                 N_ID :  rowId,
                                 csrf : $('#csrf').val()
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

                                    let quaryType = 'delete-employees';
                                    let fd = new FormData();
                                    fd.append('type', JSON.stringify(types));
                                    fd.append('formName', quaryType);
                                    
                                    $.ajax({
                                        url: 'action/employees-action.php',  
                                        type: 'POST',                   
                                        data: fd,                      
                                        contentType: false,             
                                        processData: false, 
                                        success: (response) => {
                                            Swal.fire('Deleted!', 'The record has been deleted.', 'success');

                                            // row.remove();
                                            getProducts(table);
                                        },
                                        error: (err) => {
                                            console.error('Error deleting data:', err);
                                            Swal.fire('Error', 'There was an error deleting the record.', 'error');
                                        }
                                    });
                                }
                            });
                        });


                        $('#departmentForm').on('submit', function(e) {
                            e.preventDefault(); 
                           let  department = {
                                 V_NAME : $('#departmentname').val(),
                                 N_ID : '',
                                 csrf : "<?= $db->csrfToken() ?>"
                            }

                            if (department.V_NAME == '' || department.V_NAME == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a Department name'
                                })
                                return;
                            }

                            let quaryType = 'add-departments';


                            let fd = new FormData();
                            fd.append('type', JSON.stringify(department));
                            fd.append('formName', quaryType);

                            console.log(quaryType);
                            $.ajax({
                                url: 'action/curd-action.php', 
                                type: 'POST',
                                data: fd, 
                                contentType: false,  
                                processData: false, 
                                success: function(res) {
                                    
                                    console.log(res['status']);
                                    
                                    if (res.status) {

                                        Toast.fire({
                                            icon: 'success',
                                            title: res.successmsg
                                        })

                                        $('#departmentForm')[0].reset();
                                        getDepartment();
                                        $('#modal1').modal('hide');
                                    } else {
                                        Toast.fire({
                                            icon: 'warning',
                                            title: res.successmsg
                                        })
                                    }
                                },
                                error: function(err) {
                                    console.error("Error in AJAX request:", err);
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Something went wrong. Please try again later.'
                                        })
                                }
                            });
                        });

                        $('#designationForm').on('submit', function(e) {
                            e.preventDefault(); 
                           let  designation = {
                                 V_NAME : $('#designationname').val(),
                                 N_ID : '',
                                 csrf : "<?= $db->csrfToken() ?>"
                            }

                            if (designation.V_NAME == '' || designation.V_NAME == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a Designation name'
                                })
                                return;
                            }

                            let quaryType = 'add-designations';


                            let fd = new FormData();
                            fd.append('type', JSON.stringify(designation));
                            fd.append('formName', quaryType);

                            console.log(quaryType);
                            $.ajax({
                                url: 'action/curd-action.php', 
                                type: 'POST',
                                data: fd, 
                                contentType: false,  
                                processData: false, 
                                success: function(res) {
                                    
                                    console.log(res['status']);
                                    
                                    if (res.status) {

                                        Toast.fire({
                                            icon: 'success',
                                            title: res.successmsg
                                        })

                                        $('#designationForm')[0].reset();
                                        getDesination();
                                        $('#modal2').modal('hide');
                                    } else {
                                        Toast.fire({
                                            icon: 'warning',
                                            title: res.successmsg
                                        })
                                    }
                                },
                                error: function(err) {
                                    console.error("Error in AJAX request:", err);
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Something went wrong. Please try again later.'
                                        })
                                }
                            });
                        });
                      2
                        $('#dataForm').on('submit', function(e) {
                            e.preventDefault(); 
                           let  types = {

                                 V_EMPLOYEE_CODE  : $('.employee_code').val(),
                                 V_EMPLOYEE_ID   : $('.employee_id').val(),
                                 V_EMPLOYEE_NAME : $('.v_name').val(),
                                 V_MOBILE_NO     : $('.v_mobile_no').val(),
                                 V_ADDRESS       : $('.v_address').val(),
                                 V_PBIX_NO       : $('.v_pbix_no').val(),
                                 N_DEPARTMENT_ID : $('.department_id').val(),
                                 N_DESIGNATION_ID: $('.designation_id').val(),
                                 N_ID            : $('#nid').val(),
                                 csrf            : $('#csrf').val()
                            }

                            if (types.V_EMPLOYEE_NAME == '' || types.V_EMPLOYEE_NAME == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a Employee name'
                                })
                                return;
                            }
                            if (types.V_MOBILE_NO == '' || types.V_MOBILE_NO == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a Mobile No.'
                                })
                                return;
                            }
                           
                            if (types.N_DEPARTMENT_ID == '' || types.N_DEPARTMENT_ID == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Select a Department name'
                                })
                                return;
                            }
                            if (types.N_DESIGNATION_ID == '' || types.N_DESIGNATION_ID == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Select a Designation name'
                                })
                                return;
                            }

                            let url = 'action/employees-action.php';
                            let quaryType = 'add-employees';

                            if (types.N_ID != '') {
                                quaryType = 'edit-employees';
                            }

                            let fd = new FormData();
                            fd.append('type', JSON.stringify(types));
                            fd.append('formName', quaryType);

                            // console.log(quaryType);
                            $.ajax({
                                url: url, 
                                type: 'POST',
                                data: fd, 
                                contentType: false,  
                                processData: false, 
                                success: function(res) {
                                    
                                    console.log(res['status']);
                                    
                                    if (res.status) {

                                        Toast.fire({
                                            icon: 'success',
                                            title: res.successmsg
                                        })

                                        $('#dataForm')[0].reset();
                                        getProducts(table);
                                        resetSelectBoxes();
                                        getEmpCode();
                                       
                                    } else {
                                        Toast.fire({
                                            icon: 'warning',
                                            title: res.successmsg
                                        })
                                    }
                                },
                                error: function(err) {
                                    console.error("Error in AJAX request:", err);
                                        Toast.fire({
                                            icon: 'error',
                                            title: 'Something went wrong. Please try again later.'
                                        })
                                }
                            });
                        });
                        
                        
                    });

                    async  function resetfrom() {
                            $('#dataForm')[0].reset();
                            resetSelectBoxes();
                            await  getEmpCode();
                           
                    }
                    function resetSelectBoxes() {
                        $('.department_id').val('').trigger('change');
                        $('.designation_id').val('').trigger('change');
                    }
                  
                 

                    function getEmpCode(table) {

                        $.ajax({
                            url: 'data/helper.php',
                            type: 'GET',
                            data: {'formName':'employee-code'}, 
                            success: (res) => {
                                console.log
                                $('.employee_code').val(res);
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
               
                    function getDesination() {
                        $('#designation_name').empty();
                        $.ajax({
                            url: 'data/designation.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                const row1 = `<option value="">Select Designation</option>`;
                                $('#designation_name').append(row1);
                                res.forEach((row, sl) => {
                                    const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                                    $('#designation_name').append(row2);
                                });
                            }
                        });
                    }
                    
                    function getProducts(table) {
                        $.ajax({
                            url: 'data/employee.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                console.log(res);
                                $('.example1 tbody').empty();
                                res.forEach((row, sl) => {
                                    const row1 = `<tr data-id="${row.N_ID}">
                                            <td>${sl + 1}</td>
                                            <td>${row.V_EMPLOYEE_ID}</td>
                                            <td>${row.V_EMPLOYEE_CODE}</td>
                                            <td>${row.V_EMPLOYEE_NAME}</td>
                                            <td>${row.DEPARTMENT_NAME}</td>
                                            <td>${row.DESIGNATION_NAME}</td>
                                            <td>${row.V_MOBILE_NO}</td>
                                            <td>${row.V_PBIX_NO}</td>
                                            <td>${row.V_ADDRESS}</td>
                                            <td>${row.DT_CREATED_AT}</td>
                                            <td>${row.CREATEDUSER}</td>
                                            <td>${row.DT_UPDATED_AT}</td>
                                            <td>${row.UPDATEDUSER?row.UPDATEDUSER:'N/A'}</td>
                                            <td align="center">
                                             <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'emp_edit') == true)) { ?>
                                                <button class="btn btn-outline-primary btn-xs" 
                                                data-row="${encodeURIComponent(JSON.stringify(row))}" 
                                                onClick="editType(this)">
                                                <i class="fas fa-pen"></i>
                                                </button>
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

                                table.clear();
                                table.rows.add($('.example1 tbody tr'));
                                table.draw();
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