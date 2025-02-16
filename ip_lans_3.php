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
                                        <li class="breadcrumb-item active" style="color: #ffdc2f;">Products</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <section class="content">
                        <div class="container-fluid">
                            <?php

                            if ($_GET['page'] == 'all-ip-lans'):

                                if ($auth->verifyUserPermission('checked', 'unit') 
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):
                            ?>
                                   
                                   <?php include_once('ip_lans_2.php'); ?>

                                    <div class="row">
                                        <div class="col-12">

                                            <div class="card">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">IP Lan List</h3>
                                                </div>
                                                <!-- /.card-header -->
                                                <div class="card-body p-2">
                                                    <table id="example2" class="table table-bordered table-striped example1">

                                                        <thead>
                                                            <tr>

                                                                <th>SL No.</th>
                                                                <th>IP Category</th>
                                                                <th>IP Lan</th>
                                                                <th>IP Lan Current Status</th>
                                                                <th>Created At</th>
                                                                <th>Created By</th>
                                                                <th>Last Updated At</th>
                                                                <th>Last Updated By</th>
                                                                <?php if (($auth->verifyUserPermission('checked', 'ip_lan_edit') == true) || ($auth->verifyUserPermission('checked', 'ip_lan_del') == true) || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')) { ?>
                                                               
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
                                        <h5 class="modal-title" id="modalLabel1">Add IP Category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form id="ipLanForm">
                                        <div class="modal-body">
                                                    <div class="card-body p-2" id="category">
                                                        <div class="form-group">
                                                            <label for="categoryname">IP Category Name</label>
                                                            <input type="text" id="categoryname" class="form-control form-control-sm form-control-border categoryname" placeholder="Enter Category Name" />
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

                        $('[data-mask]').inputmask();

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
                                    'excel', 'print'
                                ],
                                language: {
                                    lengthMenu: 'Per Page: _MENU_'
                                }
                            });

                        getProducts(table);
                        getCategory();

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

                                    let quaryType = 'delete-ip-lans';
                                    let fd = new FormData();
                                    fd.append('type', JSON.stringify(types));
                                    fd.append('formName', quaryType);
                                    
                                    $.ajax({
                                        url: 'action/ip-lan-action.php',  
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


                        $('#ipLanForm').on('submit', function(e) {
                            e.preventDefault(); 
                           let  category = {
                                 V_NAME : $('#categoryname').val(),
                                 N_ID : '',
                                 csrf : "<?= $db->csrfToken() ?>"
                            }

                            if (category.V_NAME == '' || category.V_NAME == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a IP Category name'
                                })
                                return;
                            }

                            let quaryType = 'add-ip-categories';


                            let fd = new FormData();
                            fd.append('type', JSON.stringify(category));
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

                                        $('#ipLanForm')[0].reset();
                                        getCategory();
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

                        $('#dataForm').on('submit', function(e) {
                            e.preventDefault(); 

                           
                           let  types = {
                                 V_NAME: $('.v_name').val(),
                                 N_IP_CATEGORY_ID : $('.cat_id').val(),
                                 N_ID          : $('#nid').val(),
                                 csrf          : $('#csrf').val()
                            }

                            if (types.V_NAME == '' || types.V_NAME == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a IP Lan name'
                                })
                                return;
                            }
                            if (types.N_IP_CATEGORY_ID == '' || types.N_IP_CATEGORY_ID == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Select a IP Category name'
                                })
                                return;
                            }

                            let url = 'action/ip-lan-action.php';
                            let quaryType = 'add-ip-lans';

                            if (types.N_ID != '') {
                                quaryType = 'edit-ip-lans';
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

                    async function resetfrom() {
                            $('#dataForm')[0].reset();
                            resetSelectBoxes();
                           
                    }
                    
                    function resetSelectBoxes() {
                        $('.cat_id').val('').trigger('change');
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
                   
                    function getProducts(table) {
                        $.ajax({
                            url: 'data/ip-lan.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                console.log(res);
                                
                                $('.example1 tbody').empty();
                                res.forEach((row, sl) => {
                                    const row1 = `<tr data-id="${row.N_ID}">
                                            <td>${sl + 1}</td>
                                            <td>${row.CAT_NAME}</td>
                                            <td>${row.V_NAME}</td>
                                            <td>
                                                ${$.trim(row.C_IS_ACTIVE)=='n'?'<span class="badge badge-pill badge-primary">Not Assign</span>':($.trim(row.C_IS_ACTIVE)=='a'?'<span class="badge badge-pill badge-success"> Assign</span>':'')}    
                                            </td>                                        
                                            <td>${row.DT_CREATED_AT}</td>
                                            <td>${row.CREATEDUSER}</td>
                                            <td>${row.DT_UPDATED_AT}</td>
                                            <td>${row.UPDATEDUSER?row.UPDATEDUSER:'N/A'}</td>
                                            <td align="center">
                                             <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'ip_lan_edit') == true)) { ?>
                                                <button class="btn btn-outline-primary btn-xs" 
                                                data-row="${encodeURIComponent(JSON.stringify(row))}" 
                                                onClick="editType(this)">
                                                <i class="fas fa-pen"></i>
                                                </button>
                                                <?php  } ?>
                                                <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'ip_lan_del') == true)) { ?>
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
                        $('#brand .v_name').val(rowData.V_NAME); 
                        $('.cat_id').val(rowData.CAT_ID).trigger('change.select2');
                        $('.nid').val(rowData.N_ID);     
                    }

                </script>
        <?php
    else:
        $auth->redirect403();
    endif;
endif;
?>