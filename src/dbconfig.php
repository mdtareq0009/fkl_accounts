<?php 
/**
 * Database configuration class.
 * Author: Md Jakir Hosen. 
 **/

namespace accessories;

class dbconfig
{
	private $username;
	private $password;
	private $conection;
	public $con;
	public function __construct($username, $password, $conection)
	{
		$this->username   = $username;
		$this->password   = $password;
		$this->conection  = $conection;
		$this->con = oci_connect($this->username, $this->password, $this->conection);
        if(!$this->con){
            die('Opps! Database connection failure.');
        }
        return $this->con;
	}

	public function csrfToken(){
    		if(isset($_SESSION['token'])){
    			$token = $_SESSION['token'];
    		}else{
    			$token = bin2hex(random_bytes(10));
    			$_SESSION['token'] = $token;
    		}
    		return $token;
    	}

    	public function csrfVerify($token){
    		if(isset($_SESSION['token'])){
    			if($token == $_SESSION['token']){
    				return 'success';
    			}else{
    				return 'failed';
    			}
    		}else{
    			return 'failed';
    		}
    	}

}