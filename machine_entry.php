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
                                        <li class="breadcrumb-item active" style="color: #ffdc2f;">Machine Entry</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <section class="content">
                        <div class="container-fluid">
                            <?php

                            if ( $_GET['page'] == 'all-machine-entry' || (isset($_GET['id']) && $_GET['id'] != '')):
                                if ($auth->verifyUserPermission('checked', 'employee') 
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):
                            ?>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-12">
                                            <div class="card card-secondary pt-2">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">Machine Entry</h3>
                                                </div>
                                                <form id="dataForm">
                                                        <div class="card-body p-2 row" id="emp">
                                                            <input type="text" id="csrf" hidden  value="<?= $db->csrfToken() ?>" name="csrf" />
                                                            <input type="text" id="nid" hidden class="nid" name="N_ID" />
                                                            <input type="text" hidden id="old_ip_lan_id" class="old_ip_lan_id"  />

                                                            <div class="col-12 col-md-4 col-lg-4">
                                                                <div class="card">
                                                                        <div class="card-header py-0 px-1" style="background: #FC8F54;color: aliceblue;">
                                                                            <h3 class="card-title">Machine Details</h3>
                                                                        </div>
                                                                        <!-- /.card-header -->
                                                                <div class="card-body p-2">


                                                                <div class="form-group row">
                                                                    <label for="machine_id" class="col-sm-3 col-form-label">Machine ID</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" id="machine_id" class="form-control form-control-sm form-control-border machine_id machine_code"  readonly/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="req_no" class="col-sm-3 col-form-label">DDN/Req No</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" id="req_no" class="form-control form-control-sm form-control-border req_no"/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    
                                                                    <label for="d_purhcase_date" class="col-sm-3 col-form-label">Purhcase Date</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="date" id="d_purhcase_date" class="form-control form-control-sm form-control-border d_purhcase_date" placeholder="Enter Purhcase Date"/>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group row">
                                                                    <label for="d_purhcase_date" class="col-sm-3 col-form-label">Machine Name</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="text" id="d_purhcase_date" class="form-control form-control-sm form-control-border d_purhcase_date" placeholder="Enter Machine Name"/>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="v_total_cost" class="col-sm-3 col-form-label">Total Cost</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="text" id="v_total_cost" class="form-control form-control-sm form-control-border v_total_cost" placeholder="Enter Total Cost"/>
                                                                    </div>
                                                                </div>    
                                                                <div class="form-group row">
                                                                    <label for="v_description" class="col-sm-3 col-form-label">Description</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <textarea id="v_description" class="form-control form-control-sm form-control-border v_description" placeholder="Enter Description"></textarea>
                                                                    </div>
                                                                </div>    
                                                                <div class="form-group row">
                                                                    <label for="assignCheckbox" class="col-sm-3 col-form-label">is Assign</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="checkbox" id="assignCheckbox" class="is_assign" value="is_assign" >
                                                                    </div>
                                                                </div>    
                                                                
                                                            </div>
                                                            </div>
                                                                <div class="card" id="isAssign" style="display: none;">
                                                                        <div class="card-header py-0 px-1" style="background: #FC8F54;color: aliceblue;">
                                                                            <h3 class="card-title">Assign Details</h3>
                                                                        </div>
                                                                        <!-- /.card-header -->
                                                                        <div class="card-body p-2">
                                                                <div class="form-group row">
                                                                    <label for="assign_type" class="col-sm-3 col-form-label">Assign Type</label>
                                                                    <div class="col-sm-9">
                                                                        <select id="assign_type" class="form-control form-control-sm assign_type select2bs4">
                                                                            <option value="" selected>Select Type</option>
                                                                            <option value="Individual">Individual</option>
                                                                            <option value="Department">Department</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="department_name" class="col-sm-3 col-form-label">Department</label>
                                                                    <div class="col-11 col-sm-8">
                                                                        <select  class="form-control form-control-sm department_id select2bs4" onchange="getEmployee()"  id="department_name"></select>
                                                                    </div>
                                                                    <div class="col-1 col-sm-1">
                                                                        <a href="departments.php?page=all-departments" target="_blank">
                                                                            <button type="button" class="btn btn-outline-primary btn-sm"  style="width: 100%;">
                                                                                <i class="fas fa-plus"></i>
                                                                            </button>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                            <label for="employee_name" class="col-sm-3 col-form-label">Employee</label>
                                                                            <div class="col-11 col-sm-8">
                                                                            <select   class="form-control form-control-sm employee_id select2bs4"  id="employee_name"></select>
                                                                            </div>
                                                                            <div class="col-1 col-sm-1">
                                                                                <a href="employees.php?page=all-employees" target="_blank"> 
                                                                                    <button type="button" class="btn btn-outline-primary btn-sm" id="openModal2" style="width: 100%;">
                                                                                    <i class="fas fa-plus"></i>
                                                                                    </button>
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            <label for="current_password" class="col-sm-3 col-form-label">Current Password</label>
                                                                            <div class="col-11 col-sm-9">
                                                                                <input type="text" id="current_password" class="form-control form-control-sm form-control-border current_password" placeholder="Enter Current Password"/>
                                                                            </div>
                                                                        </div>   
                                                                <div class="form-group row">
                                                                    <label for="d_handover_date" class="col-sm-3 col-form-label">Hand Over Date</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="date" id="d_handover_date" class="form-control form-control-sm form-control-border d_handover_date" placeholder="Enter Purhcase Date"/>
                                                                    </div>
                                                                    <label for="d_new_password_date" class="col-sm-3 col-form-label">Next Pwd Date</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="date" id="d_new_password_date" class="form-control form-control-sm form-control-border d_new_password_date" placeholder="Enter Purhcase Date"/>
                                                                    </div>
                                                                </div>
                                                                
                                                                <div class="form-group row">
                                                                    <label for="ip_address" class="col-sm-3 col-form-label">IP Address</label>
                                                                    <div class="col-11 col-sm-9">
                                                                        <input type="text" id="ip_address" class="form-control form-control-sm form-control-border ip_address" placeholder="Enter IP Address" readonly/>
                                                                    </div>
                                                                </div>   
                                                            </div>
                                                            </div>
                                                            </div>
                                                            
                                                            <div class="col-12 col-md-8 col-lg-8">
                                                                <div class="row">

                                                                    <div class="col-md-12 col-lg-12">  
                                                                        <div class="card">
                                                                            <div class="card-header py-0 px-1" style="background: #62825D;color: aliceblue;">
                                                                                <h3 class="card-title">Component Details</h3>
                                                                            </div>
                                                                            <div class="card-body p-2">
                                                                            <div class="row">
                                                                                <div class="col-12 col-md-12 col-lg-6 px-2">
                                                                                    <div class="form-group row my-2">
                                                                                        <label for="category_id" class="col-sm-3 col-form-label">Receive No</label>
                                                                                        <div class="col-11 col-sm-9">
                                                                                            <select class="form-control form-control-sm receive_id select2bs4"  id="receive_id" onchange="getProduct()"></select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row my-2">
                                                                                        <label for="product_id" class="col-sm-3 col-form-label">Product</label>
                                                                                        <div class="col-11 col-sm-9">
                                                                                            <select   class="form-control form-control-sm product_id select2bs4" onchange="getReceiveDetailsData()"  id="product_id"></select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row my-2 serial_hide" style="display: none;">
                                                                                        <label for="serial_no" class="col-sm-3 col-form-label" >Serial no</label>
                                                                                        <div class="col-11 col-sm-9">
                                                                                            <select class="form-control form-control-sm serial_no select2bs4"   id="serial_no"></select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row my-2">
                                                                                        <label for="warranty" class="col-sm-3 col-form-label">Warranty</label>
                                                                                        <div class="col-11 col-sm-9">
                                                                                            <input type="text" id="warranty" class="form-control form-control-sm form-control-border warranty empty_field" readonly placeholder="Enter Warranty" />
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="form-group row my-2">
                                                                                        <label for="lifetime" class="col-sm-3 col-form-label">Lifetime</label>
                                                                                        <div class="col-11 col-sm-9">
                                                                                            <input type="text" id="lifetime" class="form-control form-control-sm form-control-border lifetime empty_field" readonly placeholder="Enter Lifetime"/>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-12 col-md-12 col-lg-6 px-2">
                                                                                    
                                                                                    
                                                                                    <div class="form-group row my-2">
                                                                                        <label for="current_stock" class="col-sm-3 col-form-label">Cur. Stock</label>
                                                                                        <div class="col-11 col-sm-9">
                                                                                            <input type="text" id="current_stock" class="form-control form-control-sm form-control-border current_stock empty_field" readonly/>
                                                                                        </div>
                                                                                    </div>

                                                                                
                                                                                <div class="form-group row my-2">
                                                                                    
                                                                                    <label for="quantity" class="col-sm-3 col-form-label">Quantity</label>
                                                                                    <div class="col-11 col-sm-9">
                                                                                        <input type="text" id="quantity" class="form-control form-control-sm form-control-border quantity empty_field"/>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group row my-2">
                                                                                    <label for="note" class="col-sm-3 col-form-label">Note</label>
                                                                                    <div class="col-11 col-sm-9">
                                                                                        <input type="text" id="note" class="form-control form-control-sm form-control-border note empty_field"/>
                                                                                    </div>
                                                                                </div>
                                                                                    <div class="form-group row my-2 text-right">
                                                                                        <div class="col-11 col-sm-12">
                                                                                            <button type="button" id="addToCartButton" class="btn btn-outline-success btn-sm">ADD TO CART</button>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 col-lg-12">
                                                                                    <div class="card">
                                                                                        <div class="card-header py-0 px-1" style="background: #605EA1;color: aliceblue;">
                                                                                            <h3 class="card-title">Component List</h3>
                                                                                        </div>
                                                                                        <!-- /.card-header -->
                                                                                        <div class="card-body p-0">
                                                                                            <table id="example2" class="table table-bordered table-striped example1">

                                                                                                <thead>
                                                                                                    <tr class="tr-color">
                                                                                                        <th style="width: 70px;">SL No.</th>
                                                                                                        <th>Name</th>
                                                                                                        <th>model</th>
                                                                                                        <th>searial no</th>
                                                                                                        <th>Qty</th>
                                                                                                        <th style="text-align: center;width: 50px;">Action</th>
                                                                                                    </tr>
                                                                                                </thead>
                                                                                                <tbody></tbody>
                                                                                            </table>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12 col-lg-12">
                                                                                    <div class="form-group text-right">
                                                                                        <button type="submit" class="btn btn-outline-success  btn-sm">Save</button>
                                                                                    </div>
                                                                                </div>
                                                                                </div>
                                                                            </div>
                                                                                

                                                                            </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    

                                                                    
                                                                </div>
                                                            </div>
                                                    </form>
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
        <style>
            .table td, .table th {
                    padding: 0rem 0.3rem !important;
                }
                .table .tr-color{
                    background: lightsteelblue;
                    color: black;
                }
        </style>
                <script>
                    $(document).ready(function() {

                        $('#isAssign').hide();

                        $('.select2bs4').select2({
                            theme: 'bootstrap4'
                        });
                        $('.select2bs4').on('select2:open', function() {
                            // Focus the search input inside the Select2 dropdown
                            setTimeout(function() {
                                $('.select2bs4').next('.select2').find('.select2-search__field').focus();
                            }, 10);  // Delay added to ensure the dropdown is fully open
                        });

                        getCategory();

                        $('#product_id').on('change', function() {
                            var product_id = $(this).val();
                            if(product_id){
                               $.ajax({
                                   url: 'data/received-product-details.php',
                                   type: 'GET',
                                   data: { product_id: product_id },
                                   success: function(res){
                                       if (res.length > 0) {
                                           var productDetails = res[0];
                                           $('#model').val(productDetails.V_MODEL_NO || '');
                                           $('#warranty').val(productDetails.V_WARRENTY || '');
                                           $('#lifetime').val(productDetails.V_LIFETIME || '');
                                           $('#quantity').val(productDetails.N_QUANTITY || '');
                                           $('#note').val(productDetails.V_NOTE || '');
                                       } else {
                                           alert('No data found for the selected product.');
                                       }
                                   },
                                   error: function(err){
                                       console.error('Error fetching product details:', err);
                                       Swal.fire('Error', 'Error fetching product details.', 'error');
                                       $('#category_id').empty();
                                       $('#product_id').empty();
                                   }
                               });
                            }
                        });


                        $('#assignCheckbox').change(function() {
                        if ($(this).is(':checked')) {
                            $('#isAssign').show();  // Show the element if checkbox is checked
                        } else {
                            $('#isAssign').hide();  // Hide the element if checkbox is unchecked
                        }
                    });

                      
                        var cartItems = [];

                        $("#addToCartButton").click(function() {

                            var name = $('.product_name').val();
                            var model = $('.model').val();
                            var serial = $('.serial').val();
                            var qty = $('.qty').val();

                            // Add the item to the cart array
                            var item = {
                                name: name,
                                model: model,
                                serial: serial,
                                qty: qty
                            };
                            cartItems.push((item)); // Add to array



                            // Generate a new row with the data
                            var newRow = `<tr><td style="text-align: center;width: 70px;"></td>
                                .<td>${name}</td>
                                <td>${model}</td><td>${serial}</td><td>${qty}</td>
                                <td style="text-align: center;width: 50px;"><button class="removeItem btn btn-outline-danger btn-sm" style="padding: 0rem 0.5rem;"><i class="fas fa-trash-alt"></i></button></td>`; // Button to remove item

                            // Add the new row to the table body
                            $("#example2 tbody").append(newRow);

                            // Update the serial number (SL No.)
                            updateSerialNumbers();
                            $('.empty_field').val('');
                            console.log(cartItems);

                        });

                        $("#example2").on("click", ".removeItem", function() {
                            // Get the index of the row
                            var rowIndex = $(this).closest("tr").index();
                            
                            // Remove the item from the array based on the row index
                            cartItems.splice(rowIndex, 1);

                            // Remove the row from the table
                            $(this).closest("tr").remove();

                            // Update the serial numbers (SL No.)
                            updateSerialNumbers();
                    });

                      
                        var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                    
                        getAssingCode();

                        getDepartment();
                        getReceiveData();

                        var ipid = '<?php echo isset($_GET['id'])?  $_GET['id'] : ''; ?>';

                        if(ipid != ''){
                            getIPAssignData();
                        }

                       
                      
                        $('#dataForm').on('submit', function(e) {
                            e.preventDefault(); 
                           let  types = {

                                 V_IP_VLAN_ASSIGN_NO: $('.machine_code').val(),
                                 D_ASSIGN_DATE      : $('.d_assign_date').val(),
                                 N_EMPLOYEE_ID      : $('.employee_id').val(),
                                 N_DEPARTMENT_ID    : $('.department_id').val(),
                                 N_IP_VLAN_TYPE_ID  : $('.ip_lan_type_id').val(),
                                 N_IP_VLAN_ID       : $('.ip_lan_id').val(),
                                 old_N_IP_VLAN_ID   : $('.old_ip_lan_id').val(),
                                 V_NOTE             : $('.v_note').val(),
                                 N_ID               : $('#nid').val(),
                                 csrf               : $('#csrf').val()
                            }

                            if (types.N_DEPARTMENT_ID == '' || types.N_DEPARTMENT_ID == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a Department name'
                                })
                                return;
                            }
                            if (types.N_EMPLOYEE_ID == '' || types.N_EMPLOYEE_ID == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a Employee name'
                                })
                                return;
                            }
                            if (types.N_IP_VLAN_TYPE_ID == '' || types.N_IP_VLAN_TYPE_ID == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Enter a IP Lan Type No.'
                                })
                                return;
                            }
                           
                            if (types.N_IP_VLAN_ID == '' || types.N_IP_VLAN_ID == null) {
                                Toast.fire({
                                            icon: 'info',
                                            title: 'Please Select a IP Lan name'
                                })
                                return;
                            }
                            

                            let quaryType = 'add-ip-assign';

                        if (types.N_ID != '') {
                            quaryType = 'edit-ip-assign';
                        }

                            let fd = new FormData();
                            fd.append('type', JSON.stringify(types));
                            fd.append('formName', quaryType);

                            // console.log(quaryType);
                            Swal.fire({
                                title: 'Are you sure?',
                                text: 'Do you want to Add this record?',
                                icon: 'success',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, Added it!',
                                cancelButtonText: 'Cancel'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    
                                    

                            $.ajax({
                                url:'action/ip-assign-action.php', 
                                type: 'POST',
                                data: fd, 
                                contentType: false,  
                                processData: false, 
                                success: function(res) {
                                    
                                    console.log(res['status']);
                                    
                                    if (res.status) {

                                        Swal.fire('Added!', 'The record has been Added.', 'success');

                                        $('#dataForm')[0].reset();
                                        resetSelectBoxes();
                                        getAssingCode();
                                        getIPAssignData();
                                    } else {
                                        Toast.fire({
                                            icon: 'warning',
                                            title: res.successmsg
                                        })
                                    }
                                },
                                error: function(err) {
                                    console.error('Error deleting data:', err);
                                    Swal.fire('Error', 'There was an error deleting the record.', 'error');
                                }
                            });
                        }
                    });
                        });
                        
                        
                        
                    });

                    async  function isAssingCheck() {
                           $('#isAssign').show();
                           
                    }
                    async  function resetfrom() {
                            $('#dataForm')[0].reset();
                            resetSelectBoxes();
                            await  getAssingCode();
                           
                    }
                  
                    function updateSerialNumbers() {
                        $("#example2 tbody tr").each(function(index) {
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
                            data: {'formName':'machine-code'}, 
                            success: (res) => {
                                console.log
                                $('.machine_code').val(res);
                            }
                        });
                    }

                    function getReceiveData() {
                        $('#receive_id').empty();
                        $.ajax({
                            url: 'data/receive.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                const row1 = `<option value="">Select Ref No</option>`;
                                $('#receive_id').append(row1);
                                res.forEach((row, sl) => {
                                    const row2 = `<option value="${row.N_ID}">${row.V_REF_NO}</option>`;
                                    $('#receive_id').append(row2);
                                });
                            }
                        });
                    }
                    function getReceiveDetailsData() {
                        var receive_id = $('.receive_id').val();
                        var product_id = $('.product_id').val();
                
                        $.ajax({
                            url: 'data/receive_details.php',
                            type: 'GET',
                            data: {receive_id:receive_id,product_id:product_id}, 
                            dataType: 'json',
                            success: (res) => {
                                console.log(res);
                                $('.lifetime').val(res[0].V_LIFETIME);
                                $('.warranty').val(res[0].V_WARRENTY);
                                $('.current_stock').val(res[0].CURRENT_QTY);
                                
                                var details_id = res[0].DETAILS_ID;

                                
                                $('.serial_hide').hide();
                                $('#serial_no').empty();

                                if(res[0].IS_SERIAL_ITEM == 'Yes'){
                                    $('.serial_hide').show();
                                    $.ajax({
                                    url: 'data/receive_details_serial.php',
                                    type: 'GET',
                                    data: {receive_id:receive_id,product_id:product_id,details_id:details_id}, 
                                    dataType: 'json',
                                    success: (res2) => {
                                        console.log(res2);
                                        
                                        const row1 = `<option value="">Select Serial</option>`;
                                            $('#serial_no').append(row1);
                                            res2.forEach((row, sl) => {
                                                const row2 = `<option value="${row.SR_DETAILS_ID}">${row.V_SERIAL_NO}</option>`;
                                                $('#serial_no').append(row2);
                                            });
                                    }
                                });
                            }else{
                                $('.serial_hide').hide();
                                $('#serial_no').empty();
                            }

                            }
                        });
                    }
                    function getDepartment() {
                        $('#department_name').empty();
                        $.ajax({
                            url: 'data/receive_details.php',
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
                    function getCategory() {
                        $('#category_id').empty();
                        $.ajax({
                            url: 'data/category.php',
                            type: 'GET',
                            dataType: 'json',
                            success: (res) => {
                                const row1 = `<option value="" selected disabled>Select Category</option>`;
                                $('#category_id').append(row1);
                                if(res[0] != 'empty'){
                                    res.forEach((row, sl) => {
                                        const row2 = `<option value="${row.N_ID}" >${row.V_NAME}</option>`;
                                        $('#category_id').append(row2);
                                    });
                                }
                                
                            }
                        });
                    }
                    function getProduct() {

                        var receive_id = $('.receive_id').val();
                        
                        if(receive_id=='' || receive_id == null){
                            $('#product_id').empty();
                        }else{
                            $('#product_id').empty();
                            $.ajax({
                                url: 'data/receive_details.php',
                                type: 'GET',
                                data: {receive_id:receive_id}, 
                                dataType: 'json',
                                success: (res) => {
                                    const row1 = `<option value="" selected disabled>Select Product</option>`;
                                    $('#product_id').append(row1);
                                    if(res != 'empty'){
                                    res.forEach((row, sl) => {
                                        const row2 = `<option value="${row.PRODUCT_ID}">${row.PRODUCT_NAME}</option>`;
                                        $('#product_id').append(row2);
                                    });
                                }
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

                                   $('#emp .machine_code').val(res[0].V_IP_VLAN_ASSIGN_NO); 
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
                   

                    // function editType(data) {
                    //     const rowData = JSON.parse(decodeURIComponent(data.getAttribute('data-row')));
                    //         console.log(rowData);
                    //     $('#emp .machine_code').val(rowData.V_EMPLOYEE_CODE); 
                    //     $('#emp .employee_id').val(rowData.V_EMPLOYEE_ID); 
                    //     $('#emp .v_name').val(rowData.V_EMPLOYEE_NAME); 
                    //     $('#emp .v_mobile_no').val(rowData.V_MOBILE_NO); 
                    //     $('#emp .v_pbix_no').val(rowData.V_PBIX_NO); 
                    //     $('#emp .v_address').val(rowData.V_ADDRESS);
                    //     $('.department_id').val(rowData.DEPARTMENT_ID).trigger('change.select2');
                    //     $('.designation_id').val(rowData.DESIGNATION_ID).trigger('change.select2');
                    //     $('.nid').val(rowData.N_ID);     
                    // }

                </script>
        <?php
    else:
        $auth->redirect403();
    endif;
endif;
?>