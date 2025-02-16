

                

<?php $title = 'Dashboard'?>
<?php include_once('inc/head.php');?>
    <?php
                
               if($auth->verifyUserPermission('dashboard', 1)):
                       $breadcom = 'Dashboard';
                   $content =  "<section class='content'>
                            <div class='container-fluid'>
                                <div class='row'>
                                <div class='col-lg-2 col-6'>
                                    <div class='small-box bg-info'>
                                    <div class='inner'>
                                        <h3>IT Assets</h3>

                                        <p></p>
                                    </div>
                                    <div class='icon'>
                                    <i class='fas fa-laptop-house'></i>
                                       
                                    </div>
                                    <a href='index_it_asset.php' class='small-box-footer'>See All <i class='fas fa-arrow-circle-right'></i></a>
                                    </div>
                                </div>

                                
                                </div>
                                </div>
                </section>";
       endif;?>

<?php
    include('master_index.php');
    ?>


