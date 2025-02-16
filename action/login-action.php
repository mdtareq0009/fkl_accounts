<?php
require_once('../ini.php');

if(isset($_POST['fklid']) && isset($_POST['password'])):
  $fklid = $_POST['fklid'];
  $password = $_POST['password'];
  $status = $auth->userVerify($fklid, $password);
  if($status == 'success'):
    $user = array(
         'loginStats' => 'success',
         'redirecturl' => $pageOpt->getRequestedPage()
     );
     echo json_encode($user);
  elseif($status == 'not permitted'):
    $user = array(
         'loginStats' => 'failed',
         'errorval' => 'not permit'
     );
     echo json_encode($user);
  elseif($status == 'failed'):
    $user = array(
         'loginStats' => 'failed',
         'errorval' => 'not registered'
     );
     echo json_encode($user);
  endif;
endif;

?>