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

                <?php include_once('inc/navigation_system.php'); ?>

                <div class="content-wrapper" id="categories">

                    <div class="content-header px-3  py-0" style="background:#4B4376">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-sm-12">
                                    <ol class="breadcrumb float-sm-left">
                                        <li class="breadcrumb-item"><a href="#" style="color: #fff;">Home</a></li>
                                        <li class="breadcrumb-item active" style="color: #ffdc2f;">Unit</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
               
                    <section class="content">
                        <div class="container-fluid">
                            <?php

                            if ($_GET['page'] == 'all-units'):

                                if ($auth->verifyUserPermission('checked', 'unit') 
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):
                            ?>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-4 offset-lg-4">
                                            <div class="card card-secondary pt-2">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">Unit Entry</h3>
                                                </div>
                                                <form id="dataForm">
                                                    <div class="card-body p-2" id="brand">
                                                        <div class="form-group">
                                                            <label for="vname">Unit Name</label>
                                                            <input type="text" id="csrf" hidden  value="<?= $db->csrfToken() ?>" name="csrf" />
                                                            <input type="text" id="nid" hidden class="nid" name="N_ID" />
                                                            <input type="text" id="vname" class="form-control form-control-sm form-control-border vname" name="V_NAME" placeholder="Enter Brand Name" />
                                                        </div>
                                                        <div class="form-group text-right">
                                                            <button type="submit" class="btn btn-outline-success  btn-sm">Save</button>
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
                                                    <h3 class="card-title">Unit List</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body p-2">
                                                    <table id="example2" class="table table-bordered table-striped example1">

                                                        <thead>
                                                            <tr>

                                                                <th>SL No.</th>
                                                                <th>Name</th>
                                                                <th>Created At</th>
                                                                <th>Created By</th>
                                                                <th>Last Updated At</th>
                                                                <th>Last Updated By</th>
                                                                <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') ||  ($auth->verifyUserPermission('checked', 'unit_edit') == true) || ($auth->verifyUserPermission('checked', 'unit_del') == true)) { ?>
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
                        var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top',
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
                                    'copy', 'csv', 'excel', 'pdf', 'print', 'colvis'
                                ],
                                language: {
                                    lengthMenu: 'Per Page: _MENU_'
                                }
                            });

                        getTypes(table);

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

                                    let quaryType = 'delete-units';
                                    let fd = new FormData();
                                    fd.append('type', JSON.stringify(types));
                                    fd.append('formName', quaryType);
                                    
                                    $.ajax({
                                        url: 'action/curd-action.php',  
                                        type: 'POST',                   
                                        data: fd,                      
                                        contentType: false,             
                                        processData: false, 
                                        success: (response) => {
                                            Swal.fire('Deleted!', 'The record has been deleted.', 'success');
                                            row.remove();
                                        },
                                        error: (err) => {
                                            console.error('Error deleting data:', err);
                                            Swal.fire('Error', 'There was an error deleting the record.', 'error');
                                        }
                                    });
                                }
                            });
                        });

                        $('#dataForm').on('submit', function(e) {
                            e.preventDefault(); 
                           let  types = {
                                 V_NAME : $('#vname').val(),
                                 N_ID : $('#nid').val(),
                                 csrf : $('#csrf').val()
                            }

                            if (types.V_NAME == '' || types.V_NAME == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a Unit name'
                                })
                                return;
                            }

                            let url = 'action/curd-action.php';
                            let quaryType = 'add-units';

                            if (types.N_ID != '') {
                                quaryType = 'edit-units';
                            }

                            let fd = new FormData();
                            fd.append('type', JSON.stringify(types));
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

                                        $('#dataForm')[0].reset();
                                        getTypes(table);
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
                  
                    function getTypes(table) {
                        $.ajax({
                            url: 'data/unit.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                $('.example1 tbody').empty();
                                res.forEach((row, sl) => {
                                    const row1 = `<tr data-id="${row.N_ID}">
                                            <td>${sl + 1}</td>
                                            <td>${row.V_NAME}</td>
                                            <td>${row.DT_CREATED_AT}</td>
                                            <td>${row.CREATEDUSER}</td>
                                            <td>${row.DT_UPDATED_AT}</td>
                                            <td>${row.UPDATEDUSER}</td>
                                             <td align="center">
                                             <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'unit_edit') == true)) { ?>
                                                <button class="btn btn-outline-primary btn-xs" 
                                                data-row="${encodeURIComponent(JSON.stringify(row))}" 
                                                onClick="editType(this)">
                                                <i class="fas fa-pen"></i>
                                                </button>
                                                <?php  } ?>
                                                <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'unit_del') == true)) { ?>
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
                            }
                        });
                    }


                    function editType(data) {
                        const rowData = JSON.parse(decodeURIComponent(data.getAttribute('data-row')));
                            console.log(rowData);
                            
                        $('#brand .vname').val(rowData.V_NAME); 
                        $('#brand .nid').val(rowData.N_ID);     
                    }

                </script>
        <?php
    else:
        $auth->redirect403();
    endif;
endif;
?>
