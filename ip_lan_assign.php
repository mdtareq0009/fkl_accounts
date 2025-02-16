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
                                        <li class="breadcrumb-item active" style="color: #ffdc2f;">IP Lan Assign</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <section class="content">
                        <div class="container-fluid">
                            <?php

                            if ( $_GET['page'] == 'all-ip-lan-assign' || (isset($_GET['id']) && $_GET['id'] != '')):

                                if ($auth->verifyUserPermission('checked', 'employee') 
                                || $auth->verifyUserPermission('role', 'super admin')
                                || $auth->verifyUserPermission('role', 'admin')
                                ):
                            ?>
                                    <div class="row">
                                        <div class="col-md-12 col-lg-6 offset-lg-3">
                                            <div class="card card-secondary pt-2">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">IP LAN Assign Entry</h3>
                                                </div>
                                                <form id="dataForm">
                                                        <input type="text" id="csrf" hidden  value="<?= $db->csrfToken() ?>" name="csrf" />
                                                        <input type="text" id="nid" hidden class="nid" name="N_ID" />
                                                        <input type="text" hidden id="old_ip_lan_id" class="old_ip_lan_id"  />
                                                        <div class="card-body p-2 row" id="emp">
                                                            <div class="col-md-12 col-lg-12">

                                                                <div class="form-group row">
                                                                    <label for="assigncode" class="col-sm-3 col-form-label">Assign Code</label>
                                                                    <div class="col-sm-3">
                                                                        <input type="text" id="assigncode" class="form-control form-control-sm form-control-border assign_code"  readonly/>
                                                                    </div>
                                                                    <label for="assigndate" class="col-sm-2 col-form-label">Date</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="date" id="assigndate" class="form-control form-control-sm form-control-border d_assign_date" placeholder="Enter Date"/>
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
                                                            </div>
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="form-group row">
                                                                    <label for="ip_lan_type_name" class="col-sm-3 col-form-label">IP Lan Type</label>
                                                                    <div class="col-11 col-sm-8">
                                                                        <select  class="form-control form-control-sm ip_lan_type_id select2bs4" id="ip_lan_type_name"></select>
                                                                    </div>
                                                                    <div class="col-1 col-sm-1">
                                                                        <a href="ip_types.php?page=all-ip-types" target="_blank"> 
                                                                            <button type="button" class="btn btn-outline-primary btn-sm"  id="openModal1" style="width: 100%;">
                                                                            <i class="fas fa-plus"></i>
                                                                            </button>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="ip_lan_name" class="col-sm-3 col-form-label">IP Lan</label>
                                                                    <div class="col-11 col-sm-8">
                                                                        <select  class="form-control form-control-sm ip_lan_id select2bs4" id="ip_lan_name"></select>
                                                                    </div>
                                                                    <div class="col-1 col-sm-1">
                                                                        <a href="ip_lans.php?page=all-ip-lans" target="_blank"> 
                                                                            <button type="button" class="btn btn-outline-primary btn-sm"  id="openModal1" style="width: 100%;">
                                                                            <i class="fas fa-plus"></i>
                                                                            </button>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row">
                                                                    <label for="vnote" class="col-sm-3 col-form-label">Remarks</label>
                                                                    <div class="col-sm-9">
                                                                        <textarea id="vnote" class="form-control form-control-sm  form-control-border v_note" placeholder="Enter Remarks"></textarea>
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

                          

                        $('.select2bs4').select2({
                        theme: 'bootstrap4'
                        })

                      
                        var Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                    
                        getAssingCode();
                        getDepartment();
                        getIpLanType();
                        getIpLan();

                        var ipid = '<?php echo isset($_GET['id'])?  $_GET['id'] : ''; ?>';

                        if(ipid != ''){
                            getIPAssignData();
                        }

                       
                      
                        $('#dataForm').on('submit', function(e) {
                            e.preventDefault(); 
                           let  types = {

                                 V_IP_VLAN_ASSIGN_NO: $('.assign_code').val(),
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

                    async  function resetfrom() {
                            $('#dataForm')[0].reset();
                            resetSelectBoxes();
                            await  getAssingCode();
                           
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
                            data: {'formName':'ip-assign-code'}, 
                            success: (res) => {
                                console.log
                                $('.assign_code').val(res);
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
                        var id = '<?php echo isset($_GET['id'])?  $_GET['id'] : ''; ?>';

                        var assign_status = true;
                        $('#ip_lan_name').empty();
                        $.ajax({
                            url: 'data/ip-lan.php',
                            type: 'GET',
                            data: {assign_status: id =='' ?  true : undefined}, 
                            dataType: 'json',
                            success: (res) => {
                                const row1 = `<option value="">Select IP Lan</option>`;
                                $('#ip_lan_name').append(row1);
                                res.forEach((row, sl) => {
                                    const row2 = `<option value="${row.N_ID}">${row.V_NAME}</option>`;
                                    $('#ip_lan_name').append(row2);
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
                   

                    // function editType(data) {
                    //     const rowData = JSON.parse(decodeURIComponent(data.getAttribute('data-row')));
                    //         console.log(rowData);
                    //     $('#emp .assign_code').val(rowData.V_EMPLOYEE_CODE); 
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