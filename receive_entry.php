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

            <!--- Breadcrumbs Starts --->
            <div class="content-header px-3  py-0" style="background:#4B4376">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item"><a href="#" style="color: #fff;">Home</a></li>
                                <li class="breadcrumb-item active" style="color: #ffdc2f;">Receive Entry</li>
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

                    if ( $_GET['page'] == 'all-receive-entry' || (isset($_GET['id']) && $_GET['id'] != '')):

                        if ($auth->verifyUserPermission('checked', 'employee')
                        || $auth->verifyUserPermission('role', 'super admin')
                        || $auth->verifyUserPermission('role', 'admin')
                        ):
                    ?>


                    <div class="card card-secondary pt-2">
                        <form id="dataForm">
                            <div class="card-body p-2" id="emp">
                                <!-- CSRF and Hidden Inputs -->
                                <input type="text" id="csrf" hidden value="<?= $db->csrfToken() ?>" name="csrf" />
                                <input type="text" id="nid" hidden class="nid" name="N_ID" />
                                <!--                                        <input type="text" hidden id="old_ip_lan_id" class="old_ip_lan_id" />-->

                                <div class="row">
                                    <!-- Receive Entry Form Starts -->
                                    <div class="col-12 col-md-5 col-lg-5">
                                        <div class="card pb-4">
                                            <div class="card-header py-0 px-1" style="background: #DFF2EB; color: #1F4529;">
                                                <h2 class="card-title">Receive Info</h2>
                                            </div>
                                            <div class="card-body p-2">
                                                <div class="form-group row">
                                                    <label for="receive_code" class="col-sm-3 col-form-label">Receive No</label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="receive_code" class="form-control form-control-sm receive_code" readonly />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="ref_code" class="col-sm-3 col-form-label">Ref No <span class="text-danger">*</span> </label>
                                                    <div class="col-sm-9">
                                                        <input type="text" id="ref_code" class="form-control form-control-sm ref_code" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="assigndate" class="col-sm-3 col-form-label">Date <span class="text-danger">*</span> </label>
                                                    <div class="col-sm-9">
                                                        <input type="date" id="assigndate" class="form-control form-control-sm d_date" placeholder="Enter Date" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="store_name" class="col-sm-3 col-form-label">Store</label>
                                                    <div class="col-11 col-sm-9">
                                                        <select class="form-control form-control-sm store_id select2bs4" id="store_name"></select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="department_name" class="col-sm-3 col-form-label">Delivery Department <span class="text-danger">*</span> </label>
                                                    <div class="col-11 col-sm-8">
                                                        <select class="form-control form-control-sm delivery_department_id select2bs4" id="department_name"></select>
                                                    </div>
                                                    <div class="col-1 col-sm-1">
                                                        <a href="departments.php?page=all-departments" target="_blank">
                                                            <button type="button" class="btn btn-outline-primary btn-sm" style="width: 100%;">
                                                                <i class="fas fa-plus"></i>
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                                <!--                                                    <div class="form-group text-right my-4">-->
                                                <!--                                                        <button type="button" class="btn btn-outline-danger mr-2" onclick="resetfrom()">Reset</button>-->
                                                <!--                                                        <button type="submit" class="btn btn-outline-success ">Save</button>-->
                                                <!--                                                    </div>-->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Receive Entry Form Ends -->

                                    <!-- Product Details Form Starts -->
                                    <div class="col-12 col-md-7 col-lg-7">
                                        <div class="row">
                                            <div class="col-md-12 col-lg-12">
                                                <div class="card">
                                                    <div class="card-header py-0 px-1" style="background: #DFF2EB; color: #1F4529;">
                                                        <h2 class="card-title">Product Details</h2>
                                                    </div>
                                                    <div class="card-body p-2">
                                                        <div class="row">
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-group row align-items-center mb-4">
                                                                    <div class="col-auto text-lg">
                                                                        <div class="form-check form-check-inline mr-4">
                                                                            <input type="radio" id="assignSingle" name="is_assign" class="form-check-input is_assign" value="single" checked />
                                                                            <label for="assignSingle" class="form-check-label">Single</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input type="radio" id="assignSerial" name="is_assign" class="form-check-input is_assign" value="serial" />
                                                                            <label for="assignSerial" class="form-check-label">Serial</label>
                                                                        </div>
                                                                        <div class="form-check form-check-inline">
                                                                            <input type="checkbox" id="is_machine" name="is_assign" class="form-check-input is_machine"  value="Yes"/>
                                                                            <label for="is_machine" class="form-check-label">Is Machine</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="row">
                                                            <div class="col-12 col-md-12 col-lg-6 px-2">
                                                                <div class="form-group row">
                                                                    <label for="category_name" class="col-sm-3 col-form-label">Category <span class="text-danger">*</span></label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <select class="form-control form-control-sm category_id select2bs4" id="category_name" onchange="getProduct()"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="product_name" class="col-sm-3 col-form-label">Product <span class="text-danger">*</span> </label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <select class="form-control form-control-sm product_id select2bs4" id="product_name"></select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="quantity" class="col-sm-3 col-form-label">Quantity <span class="text-danger">*</span></label>
                                                                    <div class="col-11 col-sm-9 singleContent">
                                                                        <input type="number" id="assigncode" min="1" oninput="validity.valid||(value='');" onwheel="this.blur()"
                                                                               class="form-control form-control-sm qty_serial qty empty_field text-center" placeholder="Enter Quantity" />
                                                                    </div>
                                                                    <div class="col-11 col-sm-8  serialContent">
                                                                        <input type="number" id="assigncode" class="form-control form-control-sm  qty_serial qty empty_field text-center"/>
                                                                    </div>
                                                                    <div class="col-11 col-sm-1 serialContent">
                                                                        <button type="button" class="btn btn-outline-primary btn-sm"  id="openModal1" style="width: 100%;height: 25px;padding: 0;" >+</button>
                                                                    </div>

                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="model" class="col-sm-3 col-form-label">Model <span class="text-danger">*</span></label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="text" id="model" class="form-control form-control-sm model empty_field" placeholder="Enter Model" />
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 col-md-12 col-lg-6 px-2">
                                                                <div class="form-group row">
                                                                    <label for="warranty" class="col-sm-3 col-form-label">Warranty</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="text" id="warranty" class="form-control form-control-sm warranty empty_field" placeholder="Enter Warranty" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="lifetime" class="col-sm-3 col-form-label">Lifetime</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="text" id="lifetime" class="form-control form-control-sm lifetime empty_field" placeholder="Enter Lifetime" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="note" class="col-sm-3 col-form-label">Note</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="text" id="note" class="form-control form-control-sm note empty_field" placeholder="Enter Note" />
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <div class="col-12 text-right">
                                                                        <button type="button" id="addToCartButton" class="btn btn-outline-success note ">ADD TO CART</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Product Details Form Ends -->
                                </div>

                                <!--- Product Cart List Starts --->
                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="card">
                                            <div class="card-header py-0 px-1" style="background: #DFF2EB; color: #1F4529;">
                                                <h2 class="card-title">Product List</h2>
                                            </div>
                                            <div class="card-body table-responsive p-0">
                                                <table id="productCartTable" class="table table-bordered table-striped example1 table-hover">
                                                    <thead>
                                                    <tr class="tr-color text-center">
                                                        <th style="width: 70px;">SL No.</th>
                                                        <th style="" width="40%">Product</th>
                                                        <th>Category</th>
                                                        <th>Model</th>
                                                        <th>Warranty</th>
                                                        <th>Lifetime</th>
                                                        <th>Note</th>
                                                        <th>Qty</th>
                                                        <th>Is Machine</th>
                                                        <th style="text-align: center;">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody></tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--- Product Cart List Ends --->

                                <div class="row">
                                    <div class="col-md-12 col-lg-12">
                                        <div class="form-group my-4 mx-2 text-right">
<!--                                                    <button type="button" class="btn btn-outline-danger mr-2" onclick="resetfrom()">Reset</button>-->
                                            <button type="submit" class="btn btn-outline-success px-4 py-2">Save</button>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>


                     <!--- Add Serial Modal --->
                    <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modalLabel1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalLabel1">Add Serial</h5>
                                <button type="button" class="close addToSerialQty" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="categoryForm">
                                <div class="modal-body">
                                    <div class="card-body p-2" id="category">
                                        <div class="form-group row">
                                            <label for="serial_no" class="col-12 col-sm-3 col-form-label">Serial No</label>
                                            <div class="col-11 col-sm-8">
                                                <input type="text" id="serial_no" class="form-control form-control-sm  serialno empty_field_serial" autofocus="true"/>
                                            </div>
                                            <label for="serial_no" class="col-12 col-sm-1 col-form-label" style="display:none">Qty</label>
                                            <div class="col-11 col-sm-3"  style="display:none">
                                                <input type="number" value="1" min="1" oninput="validity.valid||(value='');" onwheel="this.blur()" class="form-control form-control-sm  serial_qty"/>
                                            </div>
                                            <div class="col-1 col-sm-1">
                                                <button type="button" class="btn btn-outline-primary btn-sm"  id="addToSerialButton" >Add</button>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <table id="serialTable" class="table table-bordered table-striped">
                                                <thead>
                                                <tr class="tr-color">
                                                    <th style="width: 70px;">SL No.</th>
                                                    <th>Serial No</th>
                                                    <th>Qty</th>
                                                    <th style="text-align: center;width: 50px;">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-outline-success addToSerialQty btn-sm" data-dismiss="modal">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                    <!--- Add Serial Modal --->

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
            <!-- Main Content Starts-->

        </div>



        <?php include_once('inc/footer.php'); ?>
        <style>
            .table td, .table th {
                    padding: 0rem 0.3rem !important;
                }
                .table .tr-color{
                background:#F5F4B3;
                    color: black;
                }
                .table .tr-color-2{
                    border-bottom: 2px solid #ccc;
                }
                
                /* .table .tr-color-2 td{
                    vertical-align: middle;
                } */

            input[type="number"]::-webkit-outer-spin-button,
            input[type="number"]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }
            input[type="number"] {
                -moz-appearance: textfield;
            }

        </style>


        <script>
            $(document).ready(function() {

                $('#openModal1').on('click', function() {
                    $('#modal1').modal('show');
                });

                $('.select2bs4').select2({
                theme: 'bootstrap4'
                })


                var cartItems = [];
                var serialItems = [];
                var selectedSerialItems = [];
                var totalQuantity = 0;
                $("input[name='is_assign']").on('change', function() {
                    isSerial()
                });
                function isSerial() {
                // Initial check to display content based on the default checked radio button
                        serialItems.length = 0;
                        $("#serialTable tbody").empty();
                    if ($("#assignSingle").is(":checked")) {
                        // Show the content for "Single" and hide "Serial"
                        $(".qty_serial").prop("disabled", false);
                        $(".singleContent").show();
                        $(".serialContent").hide();
                    } else if ($("#assignSerial").is(":checked")) {
                        // Show the content for "Serial" and hide "Single"
                        $(".qty_serial").prop("disabled", true);
                        $(".serialContent").show();
                        $(".singleContent").hide();
                    }
                }
                isSerial();


                function triggerAction() {
                    var name = $('.product_name').val();
                    var serial = $('.serialno').val();
                    var qty = $('.serial_qty').val();

                    if(serial==''){
                        alert('Please Enter Serial Number');
                        return false;
                    }

                    var item = {
                        serial: serial,
                        qty: qty
                    };

                    let cartInd = serialItems.findIndex(p => p.serial == serial.trim());


                   if(cartInd > -1){
                    alert('Serial Number already exists in Serial List');
                    return false;
                   }else{
                       serialItems.push((item));
                       var newRow = `<tr>
                       <td style="text-align: center;width: 70px;"></td>
                       <td>${serial}</td><td>${qty}</td>
                       <td style="text-align: center;width: 50px;"><button class="removeSerialItem btn btn-outline-danger btn-sm" style="padding: 0rem 0.5rem;"><i class="fas fa-trash-alt"></i></button></td>`;

                       $("#serialTable tbody").append(newRow);
                       updateSerialitemNumbers();
                       $('.empty_field_serial').val('');
                       $('.serial_qty').val(1);
                    }

                    // console.log(serialItems);
                }

                $("#addToSerialButton").click(function() {
                    triggerAction();
                });

                $(document).keydown(function(event) {
                    if ($("#assignSerial").is(":checked")) {
                         if (event.keyCode === 27) {  // Escape key (keyCode 27)
                                $('#modal1').modal('show');

                            }
                        }
                });

                $(".addToSerialQty").click(function() {

                    var quantity = 0;
                    serialItems.forEach(element => {
                        quantity += parseFloat(element.qty);
                    });
                    $('.qty_serial').val(Math.round(quantity));

                });


                $("#addToCartButton").click(function() {

                    if($('.category_id').val() == null || $('.category_id').val() == ''){
                        alert('Select a Category');
                    return false;
                    }

                    if($('.product_id').val() == null || $('.product_id').val() == ''){
                        alert('Select a Product');
                    return false;
                    }

                    if($('.qty').val() == 0 || $('.qty').val() == ''){
                        alert('Product quantity is required');
                    return false;
                    }

                    let productQty = parseInt($('.qty').val());
                    if (isNaN(productQty)) {
                        alert('Invalid quantity');
                        return false;
                    }

                    // $('.category_id option:selected').text();
                    var category_name = $('.category_id option:selected').text();
                    var category_id = $('.category_id').val();
                    var product_name = $('.product_id option:selected').text();
                    // var is_machine = ('.is_machine:checked').val() === 'Yes' ? 'Yes' : 'No';
                    var is_machine = $('.is_machine:checked').val() === 'Yes' ? 'Yes' : 'No';
                    var product_id = $('.product_id').val();
                    var model = $('.model').val();
                    var warranty = $('.warranty').val();
                    var life_time = $('.lifetime').val();
                    var qty = $('.qty').val();
                    var note = $('.note').val();
                    var is_serial = $('.is_assign:checked').val() === 'serial' ? 'Yes' : 'No';

                    var serialData = [];
                    var quantity = 0;
                    // serialItems.forEach(element => {
                    //     serialData.push(element.serial);
                    //     quantity +=element.qty;
                    //
                    // });
                    serialItems.forEach(element => {
                        serialData.push({
                            serial_no: element.serial,
                            qty: element.qty
                        });
                        quantity += parseFloat(element.qty);
                    });
                    var item = {
                        is_serial: is_serial,
                        category_name: category_name,
                        category_id: category_id,
                        product_id: product_id,
                        model: model,
                        warranty: warranty,
                        life_time: life_time,
                        is_machine: is_machine,
                        qty: qty,
                        note: note,
                        serialItems: serialData,
                    };
                    // console.log(item);
                    let cartInd = cartItems.findIndex(p => p.product_id == product_id.trim());

                    if (cartInd > -1) {
                        alert('This Product already exists in cart');
                            $('.category_id').val('').trigger('change');
                        return;
                    }

                    cartItems.push((item));
                    totalQuantity += productQty; // Update total quantity

                    var newRow = `<tr class="tr-color-2">
                        <td style="text-align: center; background:#F5F4B3;color:#000"></td>
                        <td style="background:#ccedcc;color:#000;font-size:18px;border-bottom: 2px solid #ccc;" width="40%"><span class="text-bold">${product_name}</span> <br>`;

                    newRow += serialData.length > 0
                        ? serialData.map(item => `<span style="font-size:16px;"><span class="text-primary text-bold">S/N:</span> ${item.serial_no} , <span class="text-red text-bold">Qty:</span> ${item.qty}</span> | `).join('')
                        : '';

                    newRow += `</td>
                        <td contenteditable="true" class="text-center">${category_name}</td>
                        <td contenteditable="true" class="text-center">${model}</td>
                        <td class="text-center">${warranty}</td>
                        <td class="text-center">${life_time}</td>
                        <td class="text-center">${note}</td>
                        <td class="text-center">${qty}</td>
                        <td class="text-center">${is_machine}</td>
                        <td style="text-align: center;">
                            <button class="removeItem btn btn-outline-danger btn-sm" style="padding: 0rem 0.5rem;">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>`;

                    $("#productCartTable tbody").append(newRow);


                    updateProductCartSerial();
                    $('.empty_field').val('');
                    $('.category_id').val('').trigger('change');
                    $('.product_id').val('').trigger('change');
                    ///if reset radio button
                    // $("#assignSingle").prop("checked", true);

                    $("#serialTable tbody").empty();
                    serialItems.length = 0;

                });

                $("#productCartTable").on("click", ".removeItem", function() {
                    var rowIndex = $(this).closest("tr").index();
                    var removedQty = parseInt(cartItems[rowIndex].qty);
                    totalQuantity -= removedQty; // Update total quantity
                    cartItems.splice(rowIndex, 1);
                    $(this).closest("tr").remove();
                    updateProductCartSerial();
                });

                $("#serialTable").on("click", ".removeSerialItem", function() {
                    var rowIndex = $(this).closest("tr").index();
                    serialItems.splice(rowIndex, 1);
                    $(this).closest("tr").remove();
                    updateSerialitemNumbers();
                    var quantity = 0;
                    serialItems.forEach(element => {
                        quantity += parseFloat(element.qty);
                    });
                    $('.qty_serial').val(Math.round(quantity));


                });

                var Toast = Swal.mixin({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            });

                getAssingCode();
                getDepartment();
                getStore();
                getCategory();


                var ipid = '<?php echo isset($_GET['id'])?  $_GET['id'] : ''; ?>';

                if(ipid != ''){
                    getIPAssignData();
                }



                $('#dataForm').on('submit', function(e) {
                    e.preventDefault();
                   let  receiveInfo = {
                       N_ID                     :   $('#nid').val(),
                       csrf                     :   $('#csrf').val(),
                       V_DRM_NO                 :   $('.receive_code').val(),
                       V_REF_NO                 :   $('.ref_code').val(),
                       D_DATE                   :   $('.d_date').val(),
                       N_STORE_ID               :   $('.store_id').val(),
                       N_DELIVERY_DEPARTMENT_ID :   $('.delivery_department_id').val(),
                       V_NOTE                   :   $('.note').val(),
                       N_RECEIVE_QTY            :   totalQuantity,
                    }
                    console.log(receiveInfo);

                    if (receiveInfo.N_DELIVERY_DEPARTMENT_ID == '' || receiveInfo.N_DELIVERY_DEPARTMENT_ID == null) {
                        Toast.fire({
                                    icon: 'warning',
                                    title: 'Please Enter a Department name'
                        })
                        return;
                    }
                    if (receiveInfo.V_REF_NO == '' || receiveInfo.V_REF_NO == null) {
                        Toast.fire({
                                    icon: 'warning',
                                    title: 'Reference No. is Required'
                        })
                        return;
                    }
                    if (receiveInfo.D_DATE == '' || receiveInfo.D_DATE == null) {
                        Toast.fire({
                                    icon: 'warning',
                                    title: 'Date is Required'
                        })
                        return;
                    }

                    let actionType = 'add-receive-product';

                    if (receiveInfo.N_ID != '') {
                        actionType = 'edit-receive-product';
                    }

                    let formData = new FormData();
                    formData.append('receiveInfoForm', JSON.stringify(receiveInfo));
                    formData.append('productDetailsForm', JSON.stringify(cartItems));
                    formData.append('actionType', actionType);
                    // console.log(receiveInfo);
                    // console.log(cartItems);


                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'Do you want to Add this record?',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Added it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                        url:'action/receive-product-action.php',
                        type: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(res) {
                            if (res.status) {
                                Swal.fire('Added!', 'The record has been Added.', 'success');

                                $('#dataForm')[0].reset();
                                resetSelectBoxes();

                                // Clear cartItems array
                                cartItems.length = 0;
                                totalQuantity = 0;
                                // Clear the product cart table UI
                                $("#productCartTable tbody").empty();
                            } else {
                                Toast.fire({
                                    icon: 'warning',
                                    title: res.successmsg
                                })
                            }
                        },
                        error: function(err) {
                            console.error('Error deleting data:', err);
                            Swal.fire('Error', 'There was an error inserting the record.', 'error');
                        }
                    });
                        }
                    });
                });

            });

            async  function resetfrom() {
                    $('#dataForm')[0].reset();
                    resetSelectBoxes();
                    await  getAssingCode();

            }

            function updateProductCartSerial() {
                $("#productCartTable tbody tr").each(function(index) {
                    $(this).find("td:first").text(index + 1); // SL No. starts at 1
                });
            }
            function updateSerialitemNumbers() {
                $("#serialTable tbody tr").each(function(index) {
                    $(this).find("td:first").text(index + 1); // SL No. starts at 1
                });
            }
            function resetSelectBoxes() {
                $('.department_id').val('').trigger('change');
                $('.employee_id').val('').trigger('change');
                $('.ip_lan_type_id').val('').trigger('change');
                $('.ip_lan_id').val('').trigger('change');
            }
            function getAssingCode() {
                $.ajax({
                    url: 'data/helper.php',
                    type: 'GET',
                    data: {'formName':'receive-code'},
                    success: (res) => {
                        console.log
                        $('.receive_code').val(res);
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
                        const row1 = `<option value="" selected disabled>Select Category</option>`;
                        $('#category_name').append(row1);
                        if(res[0] != 'empty'){
                            res.forEach((row, sl) => {
                                const row2 = `<option value="${row.N_ID}" >${row.V_NAME}</option>`;
                                $('#category_name').append(row2);
                            });
                        }
                    }
                });
            }
            function getProduct() {

                var category_id = $('.category_id').val();

                if(category_id=='' || category_id == null){
                    $('#product_name').empty();
                }else{
                        $('#product_name').empty();
                    $.ajax({
                        url: 'data/product.php',
                        type: 'GET',
                        data: {category_id:category_id},
                        dataType: 'json',
                        success: (res) => {
                            const row1 = `<option value="" disabled selected>Select Product</option>`;
                            $('#product_name').append(row1);
                            if(res != 'empty'){
                            res.forEach((row, sl) => {
                                const row2 = `<option value="${row.N_ID}">${row.V_PRODUCT_NAME}</option>`;
                                $('#product_name').append(row2);
                            });
                        }
                        }
                    });
                }


            }
            function getStore() {
                $('#store_name').empty();
                $.ajax({
                    url: 'data/store.php',
                    type: 'GET',
                    dataType: 'json',
                    success: (res) => {
                        const row1 = `<option value="">Select Store</option>`;
                        $('#store_name').append(row1);
                        res.forEach((row, sl) => {
                            const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                            $('#store_name').append(row2);
                        });
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
            function getEmployee() {

                var department_id = $('.department_id').val();

                if(department_id=='' || department_id == null){
                    $('#employee_name').empty();
                }else{
                    console.log(department_id);
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
            async function getIPAssignData() {

                 var id = '<?php echo isset($_GET['id'])?  $_GET['id'] : ''; ?>';


                    $.ajax({
                        url: 'data/iplanassign.php',
                        type: 'GET',
                        data: {id:id},
                        dataType: 'json',
                        success: (res) => {
                           console.log(res[0]);

                           $('#emp .assign_code').val(res[0].V_IP_VLAN_ASSIGN_NO);
                           $('#emp .d_assign_date').val(res[0].D_ASSIGN_DATE);
                           setTimeout((p)=>{
                               $('.department_id').val(res[0].DEPARTMENT_ID).trigger('change.select2');
                           },1000);
                           setTimeout((p)=>{
                               $('.employee_id').val(res[0].EMPLOYEE_ID).trigger('change.select2');
                           },2000);
                           setTimeout((p)=>{
                               $('.ip_lan_type_id').val(res[0].IP_LAN_TYPE_ID).trigger('change.select2');
                           },2000);
                           setTimeout((p)=>{
                               $('.ip_lan_id').val(res[0].IP_LAN_ID).trigger('change.select2');
                           },3000);

                           $('#emp .v_note').val(res[0].V_NOTE);
                           $('#nid').val(res[0].N_ID);

                           $('#old_ip_lan_id').val(res[0].IP_LAN_ID);
                        }
                    });

            }


        </script>


        <?php
    else:
        $auth->redirect403();
    endif;
endif;
?>