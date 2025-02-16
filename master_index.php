

<?php 
use accessories\accessoriescrud;
if(!$auth->authUser()):
    $pageOpt->deleteRequestedPage();
    $pageOpt->setRequestedPage($_SERVER['REQUEST_URI']);
    $auth->loginPageRedirect();
else:
    $accessoriesModel = new accessoriescrud($db->con);
    $userid = $auth->loggedUserId();
?>
  

                <?php include_once('inc/topbar.php'); ?>

                <?php include_once('inc/dashboard_navigation.php'); ?>

<?php  ?>
                <div class="content-wrapper">

                    <div class="content-header  px-3  py-0"  style="background:#372c71">
                        <div class="container-fluid">
                            <div class="row mb-2">
                            <div class="col-sm-6">
                                <ol class="breadcrumb float-sm-left">
                                <li class="breadcrumb-item" ><a href="#" style="color: #fff;">Home</a></li>
                                <li class="breadcrumb-item active" style="color: #ffdc2f;"> <?php echo isset($breadcom) ? $breadcom : ''; ?></li>
                                </ol>
                            </div>
                            </div>
                        </div>
                    </div>
                    <?php echo isset($content) ? $content : ''; ?>
                </div>




    <?php
    include_once('inc/footer.php');
    ?>
    <?php echo isset($script_content) ? $script_content : ''; ?>
<?php
endif;
?>