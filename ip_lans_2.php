
                                    <div class="row">
                                        <div class="col-md-12 col-lg-4 offset-lg-4">
                                            <div class="card card-secondary pt-2">
                                                <div class="card-header p-2">
                                                    <h3 class="card-title">IP Lan Entry</h3>
                                                </div>
                                                <form id="dataForm">
                                                        <input type="text" id="csrf" hidden  value="<?= $db->csrfToken() ?>" name="csrf" />
                                                        <input type="text" id="nid" hidden class="nid" name="N_ID" />
                                                        <div class="card-body p-2 row" id="brand">
                                                            <div class="col-md-12 col-lg-12">
                                                                <div class="form-group row">
                                                                    <label class="col-sm-3 col-form-label">IP Category</label>
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
                                                                    <label for="vname" class="col-sm-3 col-form-label">IP Lan Name</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" id="vname" class="form-control form-control-sm form-control-border v_name" style="font-size: 18px;" data-inputmask="'alias': 'ip'" data-mask />
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
