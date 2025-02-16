<?php 
/**
 * dependent page class for all dropdown dependent data retrive from database and customization for multiple dropdown.
 * Author: Md Jakir Hosen. 
 **/

namespace accessories;

class dependentdata
{
	private $con;
	public function __construct($db)
	{
         $this->con = $db;
	}

	public function dropdownCommon($table, $optionValue, $option, $selected='', $order='ASC'){
		/**
		 **Function use guideline**

		 *$table parameter for database table name, $optionValue and $option parameter for <option value='optionValue'>option</option>.

		 *$selected parameter for which option is default selected. $selected parameter value must be equal to $option name, by default its empty.

		 *$order parameter for data retrieve order, by default its ASC order.

		**/
		//echo "SELECT $optionValue, $option FROM $table ORDER BY $option $order";
		if(empty($table) || empty($optionValue) || empty($option)){
          return ;
		}else{
			if(strtolower($option) == strtolower($optionValue)){
				$dropdownCommonSql = oci_parse($this->con, "SELECT $option FROM $table ORDER BY $option $order");
			}else{
				$dropdownCommonSql = oci_parse($this->con, "SELECT $optionValue, $option FROM $table ORDER BY $option $order");
			}
            oci_execute($dropdownCommonSql);
            $dataAppend = '';
            while ($row = oci_fetch_assoc($dropdownCommonSql)) {
        	    if($row[$optionValue] == $selected){
        		    $dataAppend .= "<option value='".$row[$optionValue]."' selected>".$row[$option]."</option>";
        	    }else{
        		    $dataAppend .= "<option value='".$row[$optionValue]."'>".$row[$option]."</option>";
        	    }
            }
            oci_free_statement($dropdownCommonSql);
            oci_close($this->con);
            return $dataAppend;
		}
        
	}

	public function dropdown($table, $optionValue, $option, $selected='', $order='ASC'){
		/**
		 **Function use guideline**

		 *$table parameter for database table name, $optionValue and $option parameter for <option value='optionValue'>option</option>.

		 *$selected parameter for which option is default selected. $selected parameter value must be equal to $option name, by default its empty.

		 *$order parameter for data retrieve order, by default its ASC order.

		**/
		//echo "SELECT $optionValue, $option FROM $table ORDER BY $option $order";
		if(empty($table) || empty($optionValue) || empty($option)){
          return ;
		}else{
			if(strtolower($option) == strtolower($optionValue)){
				$dropdownCommonSql = oci_parse($this->con, "SELECT $option FROM $table ORDER BY $option $order");
			}else{
				$dropdownCommonSql = oci_parse($this->con, "SELECT $optionValue, $option FROM $table ORDER BY $option $order");
			}
            oci_execute($dropdownCommonSql);
			
            $dataAppend = '';
            while ($row = oci_fetch_assoc($dropdownCommonSql)) {
				// echo "<pre>";
				// print_r($row);
        	    if($row[$optionValue] == $selected){
        		    $dataAppend .= "<option value='".$row[$optionValue]."' selected>".$row[$option]."</option>";
        	    }else{
        		    $dataAppend .= "<option value='".$row[$optionValue]."'>".$row[$option]."</option>";
        	    }
            }
            oci_free_statement($dropdownCommonSql);
            oci_close($this->con);
            // return $dataAppend;
            return  urlencode($dataAppend);
		}
        
	}

	public function dropdownInput($table, $optionValue, $option, $where, $vlaue, $selected='', $order='ASC'){
		// echo $where. '='.$vlaue;
				if(empty($table) || empty($optionValue) || empty($option)){
				  return ;
				}else{
					if(strtolower($option) == strtolower($optionValue)){
						$dropdownCommonSql = oci_parse($this->con, "SELECT $option FROM $table WHERE $where=$vlaue ORDER BY $option $order");
						// $dropdownCommonSql = oci_parse($this->con, "SELECT $option FROM $table WHERE $option LIKE '%$vlaue%' ORDER BY $option $order");
					}else{
						$dropdownCommonSql = oci_parse($this->con, "SELECT $optionValue, $option FROM $table WHERE $where=$vlaue ORDER BY $option $order");
						// $dropdownCommonSql = oci_parse($this->con, "SELECT $optionValue, $option FROM $table WHERE $option LIKE '%$vlaue%' ORDER BY $option $order");
					}
					oci_execute($dropdownCommonSql);
					$dataAppend = '';
					while ($row = oci_fetch_assoc($dropdownCommonSql)) {
						if($row[$optionValue] == $selected){
							$dataAppend .= "<option value='".$row[$optionValue]."' selected>".$row[$option]."</option>";
						}else{
							$dataAppend .= "<option value='".$row[$optionValue]."'>".$row[$option]."</option>";
						}
					}
					oci_free_statement($dropdownCommonSql);
					oci_close($this->con);
					return $dataAppend;
				}
				
			}


	public function __destruct(){
        return oci_close($this->con);
    }
    
}