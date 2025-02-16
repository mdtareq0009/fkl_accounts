<!DOCTYPE html>
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

        <body class="m4-cloak h-vh-100">
            <div class="preloader" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, 0.8); left: 0;">
                <div data-role="activity" data-type="square" data-style="color" style="position: relative;top: 50%; left: 50%;"></div>
            </div>
            <div class="success-notification" style="display: none; position: absolute;z-index: 1035;width: 100%;height: 100vh;top: 0;background: rgba(255, 255, 255, .93); left: 0;">

            </div>
            <div data-role="navview" data-toggle="#paneToggle" data-expand="xxl" data-compact="xl" data-active-state="true">
                <?php include_once('inc/navigation.php'); ?>
                <div class="navview-content h-100">
                    <?php include_once('inc/topbar.php'); ?>
                    <div class="content-inner h-100" style="overflow-y: auto">

                        <?php
                        /*================================================
               
                ================================================*/
                        if ($_GET['page'] == 'all-types'):
                            if ($auth->verifyUserPermission('types', 4)):
                        ?>
                                <link href="https://cdn.jsdelivr.net/npm/vuetify@2.6.0/dist/vuetify.min.css" rel="stylesheet">

                                <div id="types">
                                    <div class="row-fluid">
                                        <div class="d-flex flex-justify-center">
                                            <div class="cell-lg-12">
                                                <div data-role="panel" data-title-caption="Add Type" data-title-icon="<span class='mif-plus'></span>" class="groups-form-panel" data-collapsible="false">
                                                    <div class="p-1">
                                                        <form @submit.prevent="save" class="groups-form d-flex flex-justify-center">
                                                            <!-- <input type="hidden" name="csrf" v-model="type.csrf"> -->
                                                            <!-- <input type="hidden" name="formName" value="add-groups"> -->
                                                            <div class="cell-sm-5 p-4 bg-white ">
                                                                <div class="row">
                                                                    <div class="cell-sm-12">
                                                                        <div class="form-group">
                                                                            <label>Type Name<span class="fg-red">*</span></label>
                                                                            <input type="text" class="input-small required-field" v-model="type.VNAME" placeholder="Enter Type Name">
                                                                            <span class="invalid_feedback">Type name is required.</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="cell-12 d-flex flex-justify-end mt-2">
                                                                        <button type="submit" name="group-submit" v-bind:disabled="progress ? true : false" class="image-button border bd-dark-hover success mr-2">
                                                                            <span class='mif-done icon'></span>
                                                                            <span class="caption text-bold">Save</span>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="row-fluid">

                                            <div class="cell-md-12">
                                                <div data-role="panel" data-title-caption="All Type" data-collapsible="false" data-title-icon="<span class='mif-stack'></span>">
                                                    <div class="ml-1 mr-1">
                                                        <div class="select-groups">

                                                            <a class="btn-master print-btn" href="javascript:">
                                                                <span class="mif-print"></span> Print
                                                            </a>

                                                            <div class="search">
                                                                <label for="search" style="font-size: 12px;">Search:</label>
                                                                <input type="text" class="search-filter" v-model="searchQuery" @input="changeItemsPerPage" placeholder="Search...." />
                                                            </div>

                                                            <span class="form-group">
                                                                <label style="font-size: 12px;">Per page: </label>
                                                                <select v-model="itemsPerPage" @change="changeItemsPerPage" class="input-small required-field-select">
                                                                    <option v-for="option in perPageOptions" :key="option.key" :value="option.key">{{ option.name }}</option>
                                                                </select>
                                                            </span>
                                                            
                                                        </div>
                                                    </div>
                                                    <table class="table striped table-border cell-border subcompact mt-1 accessories-table-common">
                                                        <thead class="tr-color">
                                                            <tr>
                                                                <td>SL No.</td>
                                                                <td>Name</td>
                                                                <td>Created At</td>
                                                                <td>Created By</td>
                                                                <td>Last Updated At</td>
                                                                <td>Last Updated By</td>
                                                                <?php if (($auth->verifyUserPermission('types', 2) == true) || ($auth->verifyUserPermission('types', 3) == true)) { ?>
                                                                    <td>Action</td>
                                                                <?php } ?>
                                                            </tr>
                                                        </thead>
                                                        <tr v-for="(row,sl) in paginatedData" :key="sl">
                                                            <td width="5%">{{ sl+1 }}</td>
                                                            <td>{{ row.VNAME }}</td>
                                                            <td>{{ row.VCREATEDAT }}</td>
                                                            <td>{{ row.CREATEDUSER }}</td>
                                                            <td>{{ row.VLASTUPDATEDAT }}</td>
                                                            <td>{{ row.UPDATEDUSER }}</td>
                                                            <?php if (($auth->verifyUserPermission('types', 2) == true) || ($auth->verifyUserPermission('types', 3) == true)) { ?>
                                                                <td width="11%" align="center">
                                                                    <?php if ($auth->verifyUserPermission('types', 2) == true) { ?>
                                                                        <a class="blue btn-master btn-edit" href="javascript:" @click="editType(row)">
                                                                            <span class="mif-history"></span> Edit
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if ($auth->verifyUserPermission('types', 3) == true) { ?>
                                                                        <a class="red btn-master btn-delete" href="javascript:" @click="deleteType(row.NID)">
                                                                            <span class="mif-bin"></span> Del
                                                                        </a>
                                                                    <?php } ?>
                                                                </td>
                                                            <?php } ?>
                                                        </tr>
                                                    </table>
                                                    <div v-if="itemsPerPage !== 'all'" class="pagination-page d-flex flex-justify-center" style="top:0px;" id="pagination">

                                                        <span class="prev-pagination" v-bind:style="{backgroundColor: currentPage === 1 ? '#999' : ''}"><button @click="prevPage" :disabled="currentPage === 1" >
                                                                < Prev</button></span>
                                                        <span v-for="page in pagesToDisplay" :key="page" class="page-button">
                                                            <button
                                                                v-if="page !== '...'"
                                                                @click="goToPage(page)"
                                                                :class="{ active: currentPage === page }"
                                                                class="pagination-number">
                                                                {{ page }}
                                                            </button>
                                                            <span v-if="page === '...'" style="margin-right: 5px;font-size: 20px;">...</span>
                                                        </span>
                                                        <!-- <span style="margin: 0px 5px;font-size:11px;">Page {{ currentPage }} of {{ totalPages }}</span> -->
                                                        <span class="next-pagination" v-bind:style="{backgroundColor: currentPage === totalPages ? '#999' : ''}"><button @click="nextPage" :disabled="currentPage === totalPages">Next ></button></span>
                                                    </div>
                                                    <div v-if="itemsPerPage !== 'all' " class="pagination-page d-flex flex-justify-center" style="top:0px;" id="pagination">

                                                            <span class="" style="height: 20px; color: #1E3E62;margin: 0px 5px;font-size: 15px;position: relative;">Page {{ currentPage }} of {{ totalPages }}</span>
                                                    </div>
                                                </div>
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
                </div>
            </div>
            <?php include_once('inc/footer.php'); ?>
            <script src="assets/js/vue/vue.min.js"></script>
            <script src="assets/js/vue/axios.min.js"></script>
            <script src="assets/js/vue/vue-select.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                Vue.component('v-select', VueSelect.VueSelect);
                new Vue({
                    el: '#types',
                    data() {
                        return {
                            type: {
                                NID: '',
                                VNAME: '',
                                csrf: '<?= $db->csrfToken() ?>'
                            },
                            types: [],

                            isLoading: false,
                            currentPage: 1,
                            itemsPerPage: 25,
                            pageLoadCount: 0,
                            perPageOptions: [

                                {
                                    key: 'all',
                                    name: 'All'
                                },
                                {
                                    key: '25',
                                    name: 25
                                },
                                {
                                    key: '50',
                                    name: 50
                                },
                                {
                                    key: '100',
                                    name: 100
                                },
                                {
                                    key: '200',
                                    name: 200
                                },
                                {
                                    key: '500',
                                    name: 500
                                }
                            ], 
                            searchQuery: '',
                            progress: false
                        }
                    },
                    computed: {
                         /// for Start Pagination 
                        totalPages() {
                            return Math.ceil(this.filteredItems.length / this.itemsPerPage);
                        },

                        filteredItems() {
                           
                            const trimmedQuery = this.searchQuery.trim();
                            if (trimmedQuery) {
                                const filtered = this.types.filter(item => {
                                    return Object.keys(item).some(key => {
                                        return String(item[key])
                                            .toLowerCase()
                                            .includes(trimmedQuery.toLowerCase());
                                    });
                                });
                                console.log('Filtered Items:', filtered);
                                return filtered;
                            }
                            return this.types;
                        },

                        paginatedData(searchQuery) {
                            if (this.itemsPerPage === 'all') {
                                
                                return this.filteredItems;
                            }
                            const start = (this.currentPage - 1) * this.itemsPerPage;
                            const end = start + this.itemsPerPage;
                            return this.filteredItems.slice(start, end);
                        },

                        pagesToDisplay() {
                            const total = this.totalPages;
                            const current = this.currentPage;
                            const maxVisibleAroundCurrent = 1; 
                            const pages = [];

                            for (let i = 1; i <= 3 && i <= total; i++) {
                                pages.push(i);
                            }

                            
                            if (current > 4) {
                                pages.push('...');
                            }
                            for (let i = current - maxVisibleAroundCurrent; i <= current + maxVisibleAroundCurrent; i++) {
                                if (i > 3 && i < total - 2) {
                                    pages.push(i);
                                }
                            }
                            
                            if (current < total - maxVisibleAroundCurrent - 2) {
                                pages.push('...');
                            }
                            
                            for (let i = total - 2; i <= total && i > 3; i++) {
                                if (!pages.includes(i)) {
                                    pages.push(i);
                                }
                            }
                           
                            this.cleanMiddleEllipses(pages);

                            return pages;
                        },
                         /// for End Pagination 
                    },

                    watch: {
                         /// for Start Pagination 
                        currentPage() {
                            this.pageLoadCount++;
                        },
                        itemsPerPage() {
                            this.currentPage = 1;
                            this.pageLoadCount++;
                        }
                         /// for End Pagination 
                    },
                    mounted() {
                        this.pageLoadCount++;
                    },

                    created() {
                        this.getTypes();
                    },

                    methods: {
                        /// for Start Pagination 
                        goToPage(page) {
                            if (page !== '...') {
                                this.currentPage = page;
                            }
                        },
                        cleanMiddleEllipses(pages) {
                            // Remove pages like 11 if they fall between ellipses
                            for (let i = 1; i < pages.length - 1; i++) {
                                if (pages[i] === '...' && pages[i + 1] !== '...') {
                                    if (Math.abs(pages[i + 1] - pages[i - 1]) === 2) {
                                        pages.splice(i, 0, pages[i - 1] + 1);
                                    }
                                }
                            }
                        },

                        nextPage() {
                            if (this.currentPage < this.totalPages) {
                                this.currentPage++;
                            }
                        },
                        prevPage() {
                            if (this.currentPage > 1) {
                                this.currentPage--;
                            }
                        },
                        changeItemsPerPage() {
                            this.currentPage = 1; 
                        },

                    /// for End Pagination 
                        editType(row) {
                            this.type = {
                                NID: row.NID,
                                VNAME: row.VNAME,
                                csrf: '<?= $db->csrfToken() ?>'
                            }
                        },

                        save() {
                            this.progress = true;
                            let url = `action/curd-action.php`;
                            let quaryType = 'add-types';
                            if (this.type.NID != '') {
                                quaryType = 'edit-types';
                            }
                            let fd = new FormData();
                            fd.append('type', JSON.stringify(this.type));
                            fd.append('formName', quaryType);
                            axios.post(url, fd).then(res => {
                                console.log(res.data);
                                this.progress = false;
                                alert(res.data.successmsg);
                                if (res.data.status) {
                                    this.progress = false;
                                    this.clear();
                                    this.getTypes();
                                } else {
                                    this.progress = false;
                                }
                            })
                        },
                        clear() {
                            this.type = {
                                NID: '',
                                VNAME: '',
                                csrf: '<?= $db->csrfToken() ?>'
                            }
                        },
                        async getTypes() {
                            if (this.isLoading) return;
                            this.isLoading = true;
                            this.loading = true;
                            await axios.get(`data/types.php`)
                                .then(res => {
                                    this.types = res.data;
                                })
                                .catch(err => {
                                    console.error("Error loading data:", err);
                                    this.error = true;
                                })
                                .finally(() => {
                                    this.isLoading = false;
                                    this.loading = false;
                                });
                        },

                        deleteType(data){
                                Swal.fire({
                                    title: '<strong style="color:#ffc107;font-size:18px;padding-top:5px;">Are you sure!</strong>',
                                    html: '<strong style="color:#ffc107;font-size:16px">Want to delete this?</strong>',
                                    showDenyButton: true,
                                    position: "top",
                                    width: 300,
                                    confirmButtonText: `Ok`,
                                    confirmButtonColor: "#3085d6",
                                    cancelButtonColor: "#d33",
                                    color: "#fff",
                                    denyButtonText: `Cancel`,
                                    }).then((result) => {
                                    if (result.isConfirmed) {
                                        let quaryType = 'delete-types';
                                        let fd = new FormData();
                                        fd.append('data', data);
                                        fd.append('formName', quaryType);
                                
                                        axios.post(`action/curd-action.php`, fd).then(res => {
                                            let r = res.data;
                                            Swal.fire({
                                                icon: 'success',
                                                position: "top",
                                                title:  `<strong style="color:linear-gradient(90deg, #48c6ef 0%, #6f86d6 100%);font-size:20px;">${r.successmsg}</strong>`,
                                                showConfirmButton: false,
                                                timer: 1500
                                            })
                                            this.getTypes();
                                        }).catch(error => {
                                            let e = error.response.data;

                                            if(e.hasOwnProperty('message')){
                                                if(e.hasOwnProperty('errors')){
                                                    Object.entries(e.errors).forEach(([key, val])=>{
                                                        this.$toaster.error(val[0]);
                                                    })
                                                }else{
                                                    this.$toaster.error(e.message);
                                                }
                                            }else{
                                                this.$toaster.error(e);
                                            }
                                        })
                                    }
                                })
                            },
                        deleteT444ype(data) {
                            let quaryType = 'delete-types';
                            let fd = new FormData();
                            fd.append('data', data);
                            fd.append('formName', quaryType);
                            let confirm_msg = confirm('Are You Sure?');
                            if (confirm_msg) {
                                axios.post(`action/curd-action.php`, fd).then(res => {
                                    console.log(res.data);
                                    this.progress = false;
                                    alert(res.data.successmsg);
                                    if (res.data.status) {
                                        this.getTypes();
                                    }
                                })
                            }
                        }
                    }
                })
            </script>

        </body>
<?php
    else:
        $auth->redirect403();
    endif;
endif;
?>

</html>