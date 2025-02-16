<?php
ob_start();
session_start();
ob_flush();
$filterFormDate = "";
$filterToDate = "";

if(!defined('directory')):
      define('directory', 'it_asset');
endif;

date_default_timezone_set('ASIA/DHAKA'); //Set server default timezone.

require_once 'vendor/autoload.php';

use accessories\dbconfig;
use accessories\activepage;
use accessories\auth;

// $db = new dbconfig('assets', 'assets', '192.168.100.157:1522/erp'); //Database connection class.
// $db = new dbconfig('assets', 'assets', 'localhost:1521/orcl'); //Database connection class.
// $db = new dbconfig('local_asset_db', '123456', 'localhost:1521/orcl'); //Database connection class.
$db = new dbconfig('it_asset', 'it_asset', '192.168.100.157:1522/erp'); //Database connection class.
$auth = new auth($db->con);

// echo $auth->userPermission()->VFKLID();

$page['page'] = isset($_GET['page']) ? $_GET['page'] : '';
$pageOpt = new activepage($page);

/**
**Intialize themes setting.
**/

$pageOpt->pageTree = array(
       'index' => array('dashboard'),
       'workorder' => array('published', 'drafts', 'approved', 'accepted', 'all-work-order'),
       'suppliers' => array('all-suppliers'),
       'goods' => array('all-goods'),
       'types' => array('all-types'),
       'goods-options' => array('all-goods-options'),
       'groups' => array('all-groups'),
       'subgroups' => array('all-subgroups'),
       'materials-unit' => array('all-units'),
       'users' => array(''),
       'user-permission' => array('all-users'),
       'trash' => array('deleted-workorder'),
       'help' => array('need-help'),
       'login' => array('')
        //page tree array key will be the page file name without .php extension and array value will be all $_get[page] veriable values. Example, workorder.php page key is 'workorder' and value is ('pending', 'approved', 'accepted', 'all-work-order'). 
);
$pageOpt->pageTitle = array(
       'index' => array('dashboard' => 'Dashboard'),
       'workorder' => array('published' => 'Published Workorders', 'drafts' => 'Draft Workorders', 'approved' => 'Approved Workorders', 'accepted' => 'Accepted Workorders', 'all-work-order' => 'All Workorders', 'create-new' => 'Create Workorder', 'details' => 'Workorder Details', 'edit' => 'Edit Workorder', 'newissue' => 'Workorder Re-issue'),
       'suppliers' => array('all-suppliers' => 'All Suppliers', 'create-new' => 'Add Supplier', 'edit' => 'Edit Supplier'),
       'goods' => array('all-goods' => 'All Goods', 'create-new' => 'Add Goods', 'edit' => 'Edit Goods'),
       'types' => array('all-types' => 'All Types', 'create-new' => 'Add Types', 'edit' => 'Edit Types'),
       'goods-options' => array('all-goods-options' => 'All Goods Options', 'create-new' => 'Add Option', 'edit' => 'Edit Option'),
       'groups' => array('all-groups' => 'All Groups', 'create-new' => 'Add Group', 'edit' => 'Edit Group'),
       'subgroups' => array('all-subgroups' => 'All Sub-groups', 'create-new' => 'Add Sub-group', 'edit' => 'Edit Sub-group'),
       'materials-unit' => array('all-units' => 'All MOU', 'create-new' => 'Add MOU', 'edit' => 'Edit MOU'),
       'users' => array('usersdetails' => 'User Details', 'change-password' => 'Change Password'),
       'user-permission' => array('all-users' => 'All Users', 'create-new' => 'Add new user', 'edit' => 'Edit user rule'),
       'trash' => array('deleted-workorder' => 'Deleted Workorders'),
       'help' => array('need-help' => 'Help'),
       'login' => array('login' => 'Login')
);

/**
**Intialize themes setting end.
**/
if(isset($_GET['workorder-date-filter'])):
      $pono = isset($_GET['pono'])?$_GET['pono']:'';
      $wono = isset($_GET['wono'])?$_GET['wono']:'';
      $supplier = isset($_GET['supplier'])?$_GET['supplier']:'';
      $orderno = isset($_GET['orderno'])?$_GET['orderno']:'';
      $formdate = isset($_GET['formdate'])?$_GET['formdate']:'';
      $todate = isset($_GET['todate'])?$_GET['todate']:'';
      $pageOpt->dateWiseFilter($formdate, $todate,$orderno,$pono,$supplier,$wono);
      // $pageOpt->dateWiseFilter($_GET['formdate'], $_GET['todate'],$_GET['orderno'],$_GET['pono'],$_GET['supplier'],$_GET['wono']);
endif;

if(isset($_GET['logout'])):
	if($_GET['logout'] == 1):
		$auth->forceLogout();
	endif;
endif;
?>