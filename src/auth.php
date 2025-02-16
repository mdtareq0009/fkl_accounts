<?php 
/**
 * Handle user login session & permission.
 * Author: Md Jakir Hosen. 
 **/

namespace accessories;

class auth extends accessoriescrud
{   
	private $directoryFolder;
	private $backendPermissionData;
	private $permissionData;
	public function __construct($db)
	{
		parent::__construct($db);
		$this->directoryFolder = directory == '/' ? '/' : '/'.directory.'/';
		$this->backendPermissionData = array(
			'draft workorder'    => 'VDRAFTWORKORDER',
			'publish workorder'  => 'VPUBLISHEDWORKORDER',
			'approved workorder' => 'VAPPROVEDWORORDER',
			'accepted workorder' => 'VACCEPTEDWORKORDER',
			'all workorder'      => 'VALLWORKORDER',
			'dashboard'          => 'VDASHBOARD',
			'suppliers'          => 'VSUPPLIERS',
			'groups'             => 'VGROUPS',
			'types'              => 'VTYPES',
			'subgroups'          => 'VSUBGROUPS',
			'mou'                => 'VMOU',
			'goods options'      => 'VGOODSOPTIONS',
			'goods'              => 'VGOODS',
			'user permission'    => 'VUSERPERMISSION',
			'trash'              => 'VTRASH',
			'checked'            => 'VCHECKED',
			'gatepass'           => 'VGATEPASS',
			'role'           	 => 'VROLE',
			'costsheet'          => 'VCOSTSHEET'
		);

	    $this->permissionData = $this->userPermission();
	}

	public function userVerify($fklid, $password){
		if($this->checkDataExistence("SELECT * FROM ERP.adn_userinformation WHERE vuserid = '$fklid' AND vpassword = '$password'") == 'exist'){
			$getUser = $this->getData("SELECT VEMPID FROM ERP.adn_userinformation WHERE vuserid = '$fklid' AND vpassword = '$password'");
			$fklid = isset($getUser[0]['VEMPID']) ? $getUser[0]['VEMPID'] : 0;
			if($this->checkDataExistence("SELECT * FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$fklid' AND nstatus = 1") == 'exist'){
				$_SESSION['fklAccessoriesStoreUserAuth2021validatebyAdmin012034'] = $fklid; 
				return 'success';
			}else{
				return 'not permitted';
			}
		}else{
			return 'failed';
		}
	}

	private function userPermission(){
		if($this->authUser()){
			$fklid = $this->loggedUserId();
			$permissionData = $this->getData("SELECT * FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$fklid' AND nstatus = 1");
			if(is_array($permissionData)){
				return $permissionData[0];
			}else{
				return array();
			}
		}else{
			return array();
		}
	}

	public function getManagerFeatureAll(){
		$id = $this->loggedUserId();
// 		$getFklid = $this->getData("SELECT
// LPAD('', 2 * (LEVEL - 1)) || vfklid AS vfklid    
// FROM
//     ACCESSORIES_USERSPERMISSION a
// START WITH
// vmanagerid = (select distinct vmanagerid from ACCESSORIES_USERSPERMISSION where vmanagerid='$id')
// CONNECT BY
//     PRIOR vfklid = vmanagerid");

	$getFklid = $this->getData("select vfklid from ACCESSORIES_USERSPERMISSION start with vmanagerid='$id' connect by prior vfklid=vmanagerid order by level asc");

		if(is_array($getFklid)){
			$tempArr = array();
			foreach ($getFklid as $key => $value) {
				array_push($tempArr, $value['VFKLID']);
			}
			return implode(",", $tempArr);
		}else{
			return '0000';
		}
	}


	public function getManagerFeature(){
		$id = $this->loggedUserId();
		$getFklid = $this->getData("SELECT vfklid FROM ACCESSORIES_USERSPERMISSION WHERE vmanagerid = '$id'");
		if(is_array($getFklid)){
			$tempArr = array();
			foreach ($getFklid as $key => $value) {
				array_push($tempArr, $value['VFKLID']);
			}
			return implode(",", $tempArr);
		}else{
			return '0000';
		}
	}

	// Get Manager of Manager
	public function allGetManagerFeature($id){
		$getFklid = $this->getData("SELECT vfklid FROM ACCESSORIES_USERSPERMISSION WHERE vmanagerid in ($id)");
		if(is_array($getFklid)){
			$tempArr = array();
			foreach ($getFklid as $key => $value) {
				array_push($tempArr, $value['VFKLID']);
			}
			return implode(",", $tempArr);
		}else{
			return $id;
		}
	}

	public function getSubordinateFeature(){
		$id = $this->loggedUserId();
		$getFklid = $this->getData("SELECT vsubordinateid as VFKLID FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$id'");
		if(is_array($getFklid)){
			$tempArr = array();
			foreach ($getFklid as $key => $value) {
				array_push($tempArr, $value['VFKLID']);
			}
			return implode(",", $tempArr);
		}else{
			return '0000';
		}
	}
	public function getSubordinateFeature_2(){
		$id = $this->loggedUserId();
		$getFklid = $this->getData("SELECT vsubordinate_2 as VFKLID FROM ACCESSORIES_USERSPERMISSION WHERE vfklid = '$id'");
		// $getFklid = $this->getData("SELECT vfklid FROM ACCESSORIES_USERSPERMISSION WHERE vsubordinate_2 = '$id'");
		if(is_array($getFklid)){
			$tempArr = array();
			foreach ($getFklid as $key => $value) {
				array_push($tempArr, $value['VFKLID']);
			}
			return implode(",", $tempArr);
		}else{
			return '0000';
		}
	}

	public function verifyUserPermission($pageName, $permissionValue){
		if(count($this->permissionData) == 0){
			return false;
		}else{
			$explodePermissionData = explode(',', $this->permissionData[$this->backendPermissionData[strtolower($pageName)]]);
			if(in_array($permissionValue, $explodePermissionData)){
				return true;
			}else{
				return false;
			}
		}	
	}

	public function verifyNavigationPermission($pageName){
		if(count($this->permissionData) == 0){
			return false;
		}else{
			if($this->permissionData[$this->backendPermissionData[strtolower($pageName)]] == 0){
				return false;
			}else{
				return true;
			}
		}
	}

	public function authUser(){
		if(isset($_SESSION['fklAccessoriesStoreUserAuth2021validatebyAdmin012034'])){
			if(!empty($_SESSION['fklAccessoriesStoreUserAuth2021validatebyAdmin012034'])){
			    return true;
		    }else{
		    	return false;
		    }
		}else{
			return false;
		}
	}

	public function loginPageRedirect(){
		$loginUrl = "http://".$_SERVER['HTTP_HOST'].$this->directoryFolder."login.php";
		header("location: $loginUrl");
	}

	public function redirect404(){
		$loginUrl = "http://".$_SERVER['HTTP_HOST'].$this->directoryFolder."404.php";
		header("location: $loginUrl");
	}
	public function redirect403(){
		$loginUrl = "http://".$_SERVER['HTTP_HOST'].$this->directoryFolder."403.php";
		header("location: $loginUrl");
	}

	public function loggedUser(){
		return $this->getUser($_SESSION['fklAccessoriesStoreUserAuth2021validatebyAdmin012034']);
	}

	public function loggedUserId(){
		if($this->authUser()){
			return $_SESSION['fklAccessoriesStoreUserAuth2021validatebyAdmin012034'];
		}else{
			return false;
		}
	}

	public function forceLogout(){
		setcookie('accredirectpage', '', ['expires' => time() - (3600), 'path' => '$this->directoryFolder', 'httponly' => true, 'samesite' => 'Strict']);
		session_destroy();
		$this->loginPageRedirect();
	}
}