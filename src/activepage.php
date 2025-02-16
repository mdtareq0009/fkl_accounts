<?php 
/**
 * Active page class for highlight navigation active page and many more active page functional works.
 * Author: Md Jakir Hosen. 
 **/

namespace accessories;

class activepage
{
	protected $page;
	public $pageTree;
	protected $currentFile;
	protected $pageFirst;
    	public $pageTitle;
    	public $filterFormDate;
    	public $filterToDate;
        public $filterOrderNo;
        public $filterPoNo;
        public $filterSupplier;
        public $filterWoNo;
	private $directoryFolderName;
	public function __construct($pageData)
	{       
		$this->directoryFolderName = directory == '/' ? '/' : '/'.directory.'/';
		$this->currentFile = explode('/', $_SERVER['PHP_SELF']);
		if(is_array($pageData)){
		    $this->page = $pageData;
		}else{
			$this->page['page'] = '';
		}
	}
    
    public function previousPageUrl(){
    	if (isset($this->page)) {
            if($this->page['page'] == 'create-new'){
                if(isset($_SERVER['HTTP_REFERER'])){
                    $url = $_SERVER['HTTP_REFERER'];
                    $setPreviousPage = explode('page=', $url);
                    if(substr(end($setPreviousPage), 0, 7) == 'details'){
                        $urlTemp = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                        setcookie('previouspage', $urlTemp, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                    }elseif(end($setPreviousPage) == 'create-new'){
                        $urlTemp = $this->pageFirst();
                        $url = $urlTemp;
                        setcookie('previouspage', $urlTemp, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                    }elseif(substr(end($setPreviousPage), 0, 4) == 'edit'){
                        $urlTemp = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                        setcookie('previouspage', $urlTemp, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                    }elseif(substr(end($setPreviousPage), 0, 8) == 'newissue'){
                        $urlTemp = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                        setcookie('previouspage', $urlTemp, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
 		    }elseif(substr(end($setPreviousPage), 0, 4) == 'copy'){
                        $urlTemp = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                        setcookie('previouspage', $urlTemp, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                    }else{
                        setcookie('previouspage', $url, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                    }
                }else{
                	$url = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                }
                return $url; 
		    }elseif($this->page['page'] == 'details'){
                $url = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                setcookie('previouspage', $url, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                return $url;
            }elseif($this->page['page'] == 'newissue'){
                $url = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                setcookie('previouspage', $url, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                return $url;
            }elseif($this->page['page'] == 'edit'){
                $url = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                setcookie('previouspage', $url, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                return $url;
	    }elseif($this->page['page'] == 'copy'){
                $url = isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst();
                setcookie('previouspage', $url, ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                return $url;
            }else{
		 setcookie('previouspage', $_SERVER['REQUEST_URI'], ['expires' => time() + (43200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
                return;
		    }
		} 
    }

    public function pageFirst(){
        $this->pageFirst = end($this->currentFile).'?page='.$this->pageTree[str_replace('.php', '', end($this->currentFile))][0];
        return $this->pageFirst;
    }

    public function appTitle($separator=""){
        $getPageTitle = (isset($this->pageTitle[str_replace('.php', '', end($this->currentFile))]) ? $this->pageTitle[str_replace('.php', '', end($this->currentFile))] : '');
        if(!empty(trim($this->page['page']))){
            $title = is_array($getPageTitle) ? isset($getPageTitle[$this->page['page']]) ? $getPageTitle[$this->page['page']].' '.$separator.' ' : '' : '';
        }else{
            $title = is_array($getPageTitle) ? end($getPageTitle).' '.$separator.' ' : '';
        }
        return $title;
    }

    public function currentPageClass(){
        if (isset($this->page)) {
            if($this->page['page'] == 'create-new' || $this->page['page'] == 'details' || $this->page['page'] == 'edit' || $this->page['page'] == 'newissue' || $this->page['page'] == 'copy'){
                $getPreviousPage = explode('page=', isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : $this->pageFirst());
                $getClass = end($getPreviousPage);       
            }else{
                $getClass = $this->page['page'];
            }
            return $getClass;
        }
    }


    public function redirectWithscript($destination='', $alert=''){
    	if(!empty($destination)){
    		echo "<script type='text/javascript'>".(!empty($alert) ? 'alert(\''.$alert.'\');' : '')."window.location.href = '".$destination."';</script>";
    	}else{
    		echo "<script type='text/javascript'>alert('redirectWithscript($destination) function perameter is empty!');window.location.href = '".$this->pageFirst()."';</script>";
    	}
    }

    public function setRequestedPage($param){
        setcookie('accredirectpage', $param, ['expires' => time() + (1200), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
    }

    public function getRequestedPage(){
        $url = isset($_COOKIE['accredirectpage']) ? $_COOKIE['accredirectpage'] : 'index.php';
        return $url;
    }

    public function deleteRequestedPage(){
        setcookie('accredirectpage', '', ['expires' => time() - (3600), 'path' => "$this->directoryFolderName", 'httponly' => true, 'samesite' => 'Strict']);
    }

    // public function dateWiseFilter($formDate='', $toDate=''){
    //     if(!empty($formDate) && !empty($toDate)){
    //         $this->filterFormDate = $formDate;
    //         $this->filterToDate = $toDate;
    //         $_SESSION['filterWhere'] = "AND to_date(master.vcreatedat, 'dd-mm-yy') >= to_date('$formDate', 'dd-mm-yy') AND to_date(master.vcreatedat, 'dd-mm-yy') <= to_date('$toDate', 'dd-mm-yy')";
    //     }else{
    //         unset($_SESSION['filterWhere']);
    //         echo "<script type='text/javascript'>alert('Form date and To date is required!');window.location.href = '".end($this->currentFile)."?page=".$this->page['page']."';</script>";        
    //     }
    // }
    public function dateWiseFilter($formDate='', $toDate='',$orderno='',$pono='',$supplier='',$wono=''){
        if((!empty($formDate) && !empty($toDate)) || !empty($orderno) || !empty($pono) || !empty($supplier) || !empty($wono)){
            $this->filterFormDate = $formDate;
            $this->filterToDate = $toDate;
            $this->filterOrderNo = $orderno;
            $this->filterPoNo = $pono;
            $this->filterSupplier = $supplier;
            $this->filterWoNo = $wono;

            if(!empty($orderno)){
                $_SESSION['filterWhere'] = "AND trim(upper(VORDERNUMBERORFKLNUMBER))=trim(upper('$orderno'))";
            }
            elseif(!empty($pono)){
                $_SESSION['filterWhere'] = "AND trim(upper(VPONUMBER))=trim(upper('$pono'))";
            }
            elseif(!empty($wono)){
                $_SESSION['filterWhere'] = "AND master.NID=trim($wono)";
            }
            elseif(!empty($supplier)){
                $_SESSION['filterWhere'] = "AND trim(upper(s.vname)) like trim(upper('%$supplier%'))";
            }
            else{
                $_SESSION['filterWhere'] = "AND to_date(master.vcreatedat, 'dd-mm-yy') >= to_date('$formDate', 'dd-mm-yy') AND to_date(master.vcreatedat, 'dd-mm-yy') <= to_date('$toDate', 'dd-mm-yy')";
            }
        }else{
            unset($_SESSION['filterWhere']);
            echo "<script type='text/javascript'>alert('Form date and To date is required!');window.location.href = '".end($this->currentFile)."?page=".$this->page['page']."';</script>";        
        }
    }


    public function filterWhere(){
        if (isset($_SESSION['filterWhere'])) {
            return $_SESSION['filterWhere'];
        }else{
            return " AND to_date(master.vcreatedat, 'dd-mm-yy') >= trunc(SYSDATE-1) AND to_date(master.vcreatedat, 'dd-mm-yy') < trunc(SYSDATE+5)";
            // return "AND to_date(master.vcreatedat, 'dd-mm-yy') >= trunc(SYSDATE-60) AND to_date(master.vcreatedat, 'dd-mm-yy') < trunc(SYSDATE+5)";

        }
    }
    public function filterWhereClose(){
        unset($_SESSION['filterWhere']);
    }

    public function previousPageUrlCommon(){
        if(isset($_SERVER['HTTP_REFERER'])){
            if(str_replace($_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'], '', $_SERVER['HTTP_REFERER']) == $_SERVER['REQUEST_URI']){
                $url =  isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : 'index.php';
            }else{
                $url = $_SERVER['HTTP_REFERER'];
            }
        }else{
            $url =  isset($_COOKIE['previouspage']) ? $_COOKIE['previouspage'] : 'index.php';
        }
        return $url;
    }
    
}