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

        <!--- Breadcrumbs Starts --->
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
        <!--- Breadcrumbs Ends --->

        <!-- Main Content Starts-->
        <section class="content">
            <div class="container-fluid">
                <?php

                if ($_GET['page'] == 'all-products'):

                    if ($auth->verifyUserPermission('checked', 'create_product')
                    || $auth->verifyUserPermission('role', 'super admin')
                    || $auth->verifyUserPermission('role', 'admin')
                    ):
                ?>

                <!--- Form Area Starts--->
                <div class="row">
                    <div class="col-md-12 col-lg-10 offset-lg-1">
                        <div class="card card-secondary pt-2">
                            <div class="card-header p-2">
                                <h3 class="card-title">Product Entry</h3>
                            </div>
                            <form id="dataForm">
                                <input type="text" id="csrf" hidden  value="<?= $db->csrfToken() ?>" name="csrf" />
                                <input type="text" id="nid" hidden class="nid" name="N_ID" />
                                <div class="card-body p-2 row" id="product">
                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group row">
                                            <label for="vproductcode" class="col-sm-3 col-form-label">Product Code</label>
                                            <div class="col-sm-9">
                                                <input type="text" id="vproductcode" class="form-control form-control-sm form-control-border product_code"  readonly/>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="category_name" class="col-sm-3 col-form-label">Category <span class="text-danger">*</span></label>
                                            <div class="col-11 col-sm-8">
                                                <select  class="form-control form-control-sm cat_id select2bs4"  id="category_name"></select>
                                            </div>
                                            <div class="col-1 col-sm-1">
                                                <button type="button" class="btn btn-outline-primary btn-sm"  id="openModal1" style="width: 100%;">
                                                <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="brand_name" class="col-sm-3 col-form-label">Brand</label>
                                            <div class="col-11 col-sm-8">
                                            <select class="form-control form-control-sm brand_id select2bs4"  id="brand_name"></select>
                                            </div>
                                            <div class="col-1 col-sm-1">
                                                <button type="button" class="btn btn-outline-primary btn-sm" id="openModal2" style="width: 100%;">
                                                <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="product_name" class="col-sm-3 col-form-label">Product Name <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <input type="text" id="product_name" class="form-control form-control-sm form-control-border v_name"  placeholder="Enter Product Name" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <div class="form-group row">
                                            <label for="unit_name" class="col-sm-3 col-form-label">Unit <span class="text-danger">*</span></label>
                                            <div class="col-11 col-sm-8">
                                            <select name="" class="form-control form-control-sm form-control-border unit_id select2bs4"  id="unit_name"></select>
                                            </div>
                                            <div class="col-1 col-sm-1">
                                                <button type="button" class="btn btn-outline-primary btn-sm"  id="openModal3" style="width: 100%;">
                                                <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="opening_stock" class="col-sm-3 col-form-label">Opening Stock</label>
                                            <div class="col-sm-9">
                                            <input type="number" id="opening_stock" class="form-control form-control-sm form-control-border opening_stock" placeholder="Enter Opening Stock" />
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="note" class="col-sm-3 col-form-label">Note</label>
                                            <div class="col-sm-9">
                                                <textarea name="note" id="note" class="form-control form-control-sm  form-control-border note" placeholder="Enter Note"></textarea>
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
                <!--- Form Area Ends--->

                <!--- List Area Starts--->
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
                                            <th>Code</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Brand</th>
                                            <th>Unit</th>
                                            <th>Opening Stock</th>
                                            <th>Note</th>
                                            <th>Created At</th>
                                            <th>Created By</th>
                                            <th>Last Updated At</th>
                                            <th>Last Updated By</th>
                                            <?php if (($auth->verifyUserPermission('checked', 'product_edit') == true) || ($auth->verifyUserPermission('checked', 'product_del') == true) || $auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin')) { ?>

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
                <!--- List Area Ends--->


                <!-- category Model start -->
                <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel1">Add Ccategory</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="categoryForm">
                            <div class="modal-body">
                                        <div class="card-body p-2" id="category">
                                            <div class="form-group">
                                                <label for="categoryname">Category Name</label>
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


                <!-- brand Model start -->
                <div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="modalLabel2" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel2">Add Brand</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="brandForm">
                            <div class="modal-body">
                                        <div class="card-body p-2" id="category">
                                            <div class="form-group">
                                                <label for="brandname">Brand Name</label>
                                                <input type="text" id="brandname" class="form-control form-control-sm form-control-border brandname" placeholder="Enter Brand Name" />
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


                <!-- Unit Model start -->
                <div class="modal fade" id="modal3" tabindex="-1" role="dialog" aria-labelledby="modalLabel3" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel3">Add Unit</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="unitForm">
                            <div class="modal-body">
                                        <div class="card-body p-2" id="category">
                                            <div class="form-group">
                                                <label for="unitname">Unit Name</label>
                                                <input type="text" id="unitname" class="form-control form-control-sm form-control-border unitname" placeholder="Enter Unit Name" />
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
                <!-- Unit Model end -->


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
        <!-- Main Content Ends-->


    </div>

    <?php include_once('inc/footer.php'); ?>
        
    <script>
        $(document).ready(function() {

            // Category Modal
            $('#openModal1').on('click', function() {
                $('#modal1').modal('show');
            });

            // Brand Modal
            $('#openModal2').on('click', function() {
                $('#modal2').modal('show');
            });

            // Unit Modal
            $('#openModal3').on('click', function() {
                $('#modal3').modal('show');
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
            getProductCode();
            getCategory();
            getBrand();
            getUnit();

            // Delete Record
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

                        let actionType = 'delete-products';
                        let fd = new FormData();
                        fd.append('productForm', JSON.stringify(types));
                        fd.append('actionType', actionType);

                        $.ajax({
                            url: 'action/products-action.php',
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


            // Add Category
            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();
               let  category = {
                     V_NAME : $('#categoryname').val(),
                     N_ID : '',
                     csrf : "<?= $db->csrfToken() ?>"
                }

                if (category.V_NAME == '' || category.V_NAME == null) {
                    Toast.fire({
                                icon: 'info',
                                title: 'Please Enter a Category name'
                    })
                    return;
                }

                let quaryType = 'add-categories';


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

                            $('#categoryForm')[0].reset();
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

            // Add Brand
            $('#brandForm').on('submit', function(e) {
                e.preventDefault();
               let  brand = {
                     V_NAME : $('#brandname').val(),
                     N_ID : '',
                     csrf : "<?= $db->csrfToken() ?>"
                }

                if (brand.V_NAME == '' || brand.V_NAME == null) {
                    Toast.fire({
                                icon: 'info',
                                title: 'Please Enter a Brand name'
                    })
                    return;
                }

                let quaryType = 'add-brands';


                let fd = new FormData();
                fd.append('type', JSON.stringify(brand));
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

                            $('#brandForm')[0].reset();
                            getBrand();
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

            // Add Unit
            $('#unitForm').on('submit', function(e) {
                e.preventDefault();
               let  unit = {
                     V_NAME : $('#unitname').val(),
                     N_ID : '',
                     csrf : "<?= $db->csrfToken() ?>"
                }

                if (unit.V_NAME == '' || unit.V_NAME == null) {
                    Toast.fire({
                                icon: 'info',
                                title: 'Please Enter a Unit name'
                    })
                    return;
                }

                let quaryType = 'add-units';


                let fd = new FormData();
                fd.append('type', JSON.stringify(unit));
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

                            $('#unitForm')[0].reset();
                            getUnit();
                            $('#modal3').modal('hide');
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

            // Product Form Submit
            $('#dataForm').on('submit', function(e) {
                e.preventDefault();
               let  productFormData = {
                     V_PRODUCT_CODE: $('.product_code').val(),
                     N_CATEGORY_ID : $('.cat_id').val(),
                     N_UNIT_ID     : $('.unit_id').val(),
                     N_BRAND_ID    : $('.brand_id').val(),
                     V_PRODUCT_NAME: $('.v_name').val(),
                     OPENING_STOCK : $('.opening_stock').val(),
                     V_NOTE        : $('.note').val(),
                     N_ID          : $('#nid').val(),
                     csrf          : $('#csrf').val()
                }

                if (productFormData.V_PRODUCT_NAME == '' || productFormData.V_PRODUCT_NAME == null) {
                    Toast.fire({
                                icon: 'info',
                                title: 'Please Enter a Product name'
                    })
                    return;
                }
                if (productFormData.N_UNIT_ID == '' || productFormData.N_UNIT_ID == null) {
                    Toast.fire({
                                icon: 'info',
                                title: 'Please Select a Unit name'
                    })
                    return;
                }
                if (productFormData.N_CATEGORY_ID == '' || productFormData.N_CATEGORY_ID == null) {
                    Toast.fire({
                                icon: 'info',
                                title: 'Please Select a Category name'
                    })
                    return;
                }
                // if (productFormData.N_BRAND_ID == '' || productFormData.N_BRAND_ID == null) {
                //     Toast.fire({
                //                 icon: 'info',
                //                 title: 'Please Select a Brand name'
                //     })
                //     return;
                // }

                let actionType = 'add-products';

                if (productFormData.N_ID != '') {
                    actionType = 'edit-products';
                }

                let formData = new FormData();
                formData.append('productForm', JSON.stringify(productFormData));
                formData.append('actionType', actionType);

                $.ajax({
                    url: 'action/products-action.php',
                    type: 'POST',
                    data: formData,
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
                            getProductCode();

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
                await  getProductCode();

        }
        function resetSelectBoxes() {
            $('.cat_id').val('').trigger('change');
            $('.brand_id').val('').trigger('change');
            $('.unit_id').val('').trigger('change');
        }



        function getProductCode(table) {

            $.ajax({
                url: 'data/helper.php',
                type: 'GET',
                data: {'formName':'product-code'},
                success: (res) => {
                    console.log
                    $('.product_code').val(res);
                }
            });
        }
        function getCategory() {
            $('#category_name').empty();
            $.ajax({
                url: 'data/category.php',
                type: 'GET',
                dataType: 'json',
                success: (res) => {
                    const row1 = `<option value="">Select Category</option>`;
                    $('#category_name').append(row1);
                    res.forEach((row, sl) => {
                        const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                        $('#category_name').append(row2);
                    });
                }
            });
        }
        function getUnit() {
            $('#unit_name').empty();
            $.ajax({
                url: 'data/unit.php',
                type: 'GET',
                dataType: 'json',
                success: (res) => {
                    const row1 = `<option value="">Select Unit</option>`;
                    $('#unit_name').append(row1);
                    res.forEach((row, sl) => {
                        const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                        $('#unit_name').append(row2);
                    });
                }
            });
        }
        function getBrand() {
            $('#brand_name').empty();
            $.ajax({
                url: 'data/brand.php',
                type: 'GET',
                dataType: 'json',
                success: (res) => {
                    const row1 = `<option value="">Select Brand</option>`;
                    $('#brand_name').append(row1);
                    res.forEach((row, sl) => {
                        const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                        $('#brand_name').append(row2);
                    });
                }
            });
        }
        function getProducts(table) {
            $.ajax({
                url: 'data/product.php',
                type: 'GET',
                dataType: 'json',
                success: (res) => {
                    console.log(res);

                    $('.example1 tbody').empty();
                    res.forEach((row, sl) => {
                        const row1 = `<tr data-id="${row.N_ID}">
                                <td>${sl + 1}</td>
                                <td>${row.V_PRODUCT_CODE}</td>
                                <td>${row.V_PRODUCT_NAME}</td>
                                <td>${row.CAT_NAME}</td>
                                <td>${row.BRAND_NAME ?? 'N / A'}</td>
                                <td>${row.UNIT_NAME ?? 'N / A'}</td>
                                <td>${row.OPENING_STOCK ?? '---'}</td>
                                <td>${row.V_NOTE ?? 'N / A'}</td>
                                <td>${row.DT_CREATED_AT}</td>
                                <td>${row.CREATEDUSER}</td>
                                <td>${row.DT_UPDATED_AT}</td>
                                <td>${row.UPDATEDUSER ?row.UPDATEDUSER :'N / A'}</td>
                                <td align="center">
                                 <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'product_edit') == true)) { ?>
                                    <button class="btn btn-outline-primary btn-xs"
                                    data-row="${encodeURIComponent(JSON.stringify(row))}"
                                    onClick="editType(this)">
                                    <i class="fas fa-pen"></i>
                                    </button>
                                    <?php  } ?>
                                    <?php if ($auth->verifyUserPermission('role', 'super admin') || $auth->verifyUserPermission('role', 'admin') || ($auth->verifyUserPermission('checked', 'product_del') == true)) { ?>
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
            $('#dataForm .product_code').val(rowData.V_PRODUCT_CODE);
            $('#dataForm .v_name').val(rowData.V_PRODUCT_NAME);
            $('#dataForm .note').val(rowData.V_NOTE);

            $('.cat_id').val(rowData.CAT_ID).trigger('change.select2');
            $('.brand_id').val(rowData.BRAND_ID).trigger('change.select2');
            $('.unit_id').val(rowData.UNIT_ID).trigger('change.select2');

            $('#dataForm .opening_stock').val(rowData.OPENING_STOCK);
            $('.nid').val(rowData.N_ID);
        }

    </script>

    <?php
    else:
        $auth->redirect403();
    endif;
endif;

?>