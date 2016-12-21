<?php 
// useful common functions

/**
 * Get Languages that are active on the installation
 * will return an associative array 
 *  Array
 *  (
 *		[DE] => Germany
 *		[EN] => English
 *  )
 */
if (!function_exists('getActiveLanguages')) {
	function getActiveLanguages(){
		global $database;
		$sQueryAddons = (
			"SELECT DISTINCT a.`directory`, a.`name`, 							
			IF( p.language IS NOT NULL , 1, 0 ) AS active			
			FROM ".TABLE_PREFIX."addons a 
				LEFT JOIN ".TABLE_PREFIX."pages p 
				ON a.directory = p.language 
			WHERE type = 'language'"
		);
		$aLangs = array();
		if($oAddons = $database->query($sQueryAddons)){
			// Loop through addons
			while($rec = $oAddons->fetchRow(MYSQL_ASSOC)){
				if($rec['active'] == true){
						$aLangs[$rec['directory']] = $rec['name'];
				}
			}			
		}
		return $aLangs;
	}
}

if (!function_exists('L_')) {
/**
 *	processTranslation
 *	-----------------------
 *
 * Correct format would be:
 *     L_('ARRAY:KEY'); or
 *     L_('{ARRAY:KEY}'); 
 * example:
 *     L_('TEXT:ACTIVE');
 *     L_('{TEXT:ACTIVE}');
 *
 *	@author Christian M. Stefan <stefek@designthings.de>	
 *	@param  string	
 *	@param  bool	
 *	@return string Translated String
 */	
	function L_($str, $bShowMissing = false){
		$sRetVal = '';
		if(strpos($str, ':') !== false){
			$str = str_replace(' ', '', $str);
			if(strpos($str, '{') !== false){
				preg_match_all('/{(.*?)}/', $str, $out);
				$tmp = explode(':',$out[1][0]);
			}else{ 
				$tmp = explode(':',$str);
			}
			$arr = $tmp[0];	
			$key = $tmp[1];	
			if(is_array($GLOBALS[$arr]) && array_key_exists($key, $GLOBALS[$arr])){		
				$sRetVal = $GLOBALS[$arr][$key];
			}else{
				$bShowMissing = true;
				if($bShowMissing){
					$sRetVal = "<span style='color:purple'>";
					$sRetVal .= (is_array($GLOBALS[$arr]) == false) ? 'Array '.$arr.' does not exist.<br>' : '';
					$sRetVal .= "<b>Missing Translation:</b> <input style=\"width:450px\" type=\"text\" value=\"$".$arr."['".$key."']\"></span>";
				}else{ 
					$key = str_replace('_', ' ', $key).'.';
					$sRetVal = $key;
				}
			}
		}else{
			$sRetVal = $str;
		}
		return $sRetVal;
	}
}
	
if (!function_exists('updateRecordFromArray')) {
    /**
     *	updateRecordFromArray
     *	-----------------------
     *	@author 	Christian M. Stefan <stefek@designthings.de>
     *	
     *	@param array	$aInsertArray - prepared Array of field-names and values to be updated in table
     *	@param string	$sTableName   - table name to be used for the query
     *	@param string	$sWhereField  - field name for WHERE clause
     *	@param int      $iWhereId     - id to use as unique key (may be $item_id, $section_id etc.)
     *
     *	@return executes a database query
     */
    function updateRecordFromArray($aInsertArray = array(), $sTableName = '', $sWhereField = '', $iWhereId = ''){
        if (isset($sTableName) && is_array($aInsertArray) && !empty($sWhereField) && !is_null($iWhereId)) {
            global $database;
            $aCollect = array();
            foreach ($aInsertArray as $k => $v) {
                $aCollect[] = "`".$k."` = '".$v."', ";
            }
            $sValues = implode("", $aCollect);
            $sValues = substr($sValues, 0, -2);
            $sQuery  = "UPDATE `%s` SET %s WHERE `%s` = '%d'";
            // execute the UPDATE query
			if($database->query(sprintf($sQuery, $sTableName, $sValues, $sWhereField, $iWhereId))){				
				return true; 
			}else{ 
				return false; //$database->get_error();
			}
        }
    }
}
if(!function_exists('wb_dump')){
	/**
	 * This is a simple function to show var_dump or print_r output
	 * in a predefined wrapper
         * INFO: this function will be available in FE/BE everywhere in WBCE 1.2.x
	 **/
	function wb_dump($mVar = '', $sHeading='', $bShowWithVarDump = false){
		echo '<pre style="background: lightyellow; padding:6px; margin:4px; border: 1px dotted red;">';
			if('' != $sHeading){
				echo '<b style="color: blue;">'.$sHeading.':</b><hr />';
			}
			if($bShowWithVarDump){
				if(is_array($mVar)){
					var_dump($mVar);
				}elseif(!is_array($mVar) && '' != $mVar){
					var_dump($mVar);
				}else{		
					echo '<i>~ (empty) ~</i>';
				}
			}else{
				if(is_array($mVar) || is_object($mVar)){
					print_r($mVar);
				}elseif(!is_array($mVar) && '' != $mVar){
					echo($mVar);
				}else{		
					echo '<i>~ (empty) ~</i>';
				}
			}
		echo '</pre>';	
	}
}


if (!function_exists('db_table_exists')) {
    function db_table_exists($sTable){
        $rQuery = $GLOBALS['database']->query('SHOW TABLES LIKE "' . $sTable . '"');
        return ($rQuery->numRows() != 0);
    }
}

if (!function_exists('insertRow')) {
    /**
     *	updateRecordFromArray
     */
    function insertRow($table, array $data){
		global $database;
        $retVal = false;
        $parameters = array();
        foreach ($data as $column => $value) {
            $parameters[] = "`".trim($column)."` = '".$value."', ";             
        }
        $sValues = implode("", $parameters);
        $sValues = substr($sValues, 0, -2);
				echo '<br>(1)';
        $strQuery =  sprintf("INSERT INTO `%s` SET %s", $table, $sValues);
				echo '<br>'.$strQuery;
        if($database->query($strQuery)){          
				echo '<br>(2)';          
            $retVal = true; 
        }else{ 
				echo '<br>(3)';
            $retVal = $database->get_error();
        }
        return $retVal;
    }
}

if (!function_exists('updateRow')) {
	function updateRow($table = '', $primaryKey = '', $data = array()){
		global $database;
        $retVal = false;
        if (isset($data[$primaryKey])) {
            $parameters = array();
            foreach ($data as $column => $value) {
                $parameters[] = "`".trim($column)."` = '".$value."', ";             
            }
            $sValues = implode("", $parameters);
            $sValues = substr($sValues, 0, -2);
            $sqlRowCheck = "SELECT COUNT(*) FROM `".$table."` WHERE `".$primaryKey."` = '".$data[$primaryKey]."'";
            if ($database->get_one($sqlRowCheck)) {
                $strQuery = sprintf("UPDATE `%s` SET %s WHERE `%s` = '%s'", $table, $sValues, $primaryKey, $data[$primaryKey]);
            } else { 
                $strQuery = sprintf("INSERT INTO `%s` SET %s", $table, $sValues);
            }
            if($database->query($strQuery)){                    
                $retVal = true; 
            }else{ 
                $retVal = $database->get_error();
            }
        }
        return $retVal;
    }
}



if(!function_exists('exportDropletToXML')){
/**
 * exportDropletToXML ( *** temporary function ***)
 * will be reworked to present the file for download
 * ~~~~~~~~~~~~~~~~~~
 *
**/
	function exportDropletToXML($sDropletName){
		global $database;
		$sSql = "SELECT `name`, `description`, `comments`, `code` 
					FROM `".TABLE_PREFIX."mod_droplets` 
					WHERE `name` = '".$sDropletName."'
					LIMIT 1";
		
		$result = $database->query($sSql)->fetchRow(MYSQL_ASSOC);
		$sRetVal = "<pre class='code'><textarea style='width:80%;height:500px;'><?xml version='1.0' encoding='utf-8'?>".PHP_EOL;
		$sRetVal .= "<droplet>".PHP_EOL;
		foreach($result as $key => $value) {
			$sRetVal .=  "\t<".$key.">".$value."</".$key.">".PHP_EOL;
		}
		$sRetVal .= "</droplet>".PHP_EOL;
		$sRetVal .= "</textarea>".PHP_EOL;
		$sRetVal .= "</pre>".PHP_EOL;
		echo $sRetVal;
	}
}

