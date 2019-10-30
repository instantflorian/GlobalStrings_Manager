<?php 
// specific functions for this AdminTool 

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));

function draw_field(
	$sType='', 
	$sLang = '', 
	$iID = 0, 
	$iUniqueID = 0, 
	$sContent = ''
	){ 	
	$sNameValue = 'save[content]['.$sType.']['.$iUniqueID.']';
	switch ($sType){ 
		case 'wysiwyg':
			require_once(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
			$sContent = str_replace('{SYSVAR:MEDIA_REL}', WB_URL.MEDIA_DIRECTORY, $sContent );			
			show_wysiwyg_editor($sNameValue, $sNameValue, $sContent, '98%', '100');
		break;
		case 'textarea':
			$sContent = str_replace('{SYSVAR:MEDIA_REL}', '[MEDIA_DIRECTORY]', $sContent );
			echo '<textarea name="'.$sNameValue.'" style="width:98%">'.$sContent.'</textarea>';		
		break;
		case 'shorttext':
			$sContent = str_replace('{SYSVAR:MEDIA_REL}', '[MEDIA_DIRECTORY]', $sContent );
			echo '<input type="text" name="'.$sNameValue.'" value="'.$sContent.'" style="width:98%">';
		break;	
	}
} 

function canModifyRestricted(){
	$oCms = isset($GLOBALS['wb']) ? $GLOBALS['wb'] : $GLOBALS['admin'];
	return ($oCms->ami_group_member('1') == true)? true : false;  
}

// query all Global Strings from DB tables
function getAllContents(){
	global $database;
	$sMediaUrl = WB_URL.MEDIA_DIRECTORY;
	$sQuery = "SELECT  * FROM `".STRINGS_FIELDS_TBL."`";
	$sQuery .= printOrderBy();	
	$oContent = $database->query($sQuery);
	$aContents = array();
	$oCms = isset($GLOBALS['wb']) ? $GLOBALS['wb'] : $GLOBALS['admin'];
	$bUseRstr = (getStringsCfg()->use_restrictions == 'yes'
					&& canModifyRestricted() == false)? 1 : 0;  
	while($c1 = $oContent->fetchRow(MYSQL_ASSOC)) {
		$sQuery = "SELECT  `content`, `language`, `id`, `unique_id` 
				FROM `".STRINGS_CONTENTS_TBL."`";
		$oContent2 = $database->query($sQuery);
		while($c2 = $oContent2->fetchRow(MYSQL_ASSOC)) {			
			$aContents[$c1['id']]['name'] = $c1['name'];
			$aContents[$c1['id']]['type'] = $c1['type'];			
			$aContents[$c1['id']]['add_when'] = $c1['add_when'];			
			$aContents[$c1['id']]['edit_when'] = $c1['edit_when'];			
			$aContents[$c1['id']]['restricted'] = $c1['restricted'];
			$aContents[$c2['id']]['content'][$c2['language']] = array(
				'content' => htmlspecialchars($c2['content']),
				'unique_id' => $c2['unique_id']
			);				
		}
	}
	if($bUseRstr){ 
		$aRestricted = array();
		foreach($aContents as $key=>$val){
			if($val['restricted'] == '1') continue;
			$aRestricted[$key] = $val;
		}
		$aContents = $aRestricted;
	}
	return $aContents;	
}

function updateStringContents($aContents, $iID){
	global $database, $admin;
	$aMsg = array();
	$aData = array(
			'edit_when' => time(),
			'edit_by' => $admin->get_user_id(),
			'restricted' => $aContents['restricted']
	);
	updateRecordFromArray($aData, STRINGS_FIELDS_TBL, 'id', intval($iID));
	$sSql = "UPDATE `".STRINGS_CONTENTS_TBL."` 
			 SET `content` = '%s' WHERE `unique_id` = '%d'";
	foreach($aContents as $unique_id=>$content){
		$sMediaUrl = WB_URL.MEDIA_DIRECTORY;
		if(ini_get('magic_quotes_gpc')==true){
			$content = $admin->strip_slashes($content);
		}
		$searchfor = '@(<[^>]*=\s*")('.preg_quote($sMediaUrl).')([^">]*".*>)@siU';
		$content = preg_replace($searchfor, '$1{SYSVAR:MEDIA_REL}$3', $content);
		$database->query(sprintf($sSql, $database->escapeString($content), $unique_id));
	}		
	$aMsg['success'] = '{TOOL_TEXT:CONTENT_UPDATED}';
	return $aMsg;
}
// add a new GlobalString field to the DB
function addNewStringEntity($field_name, $field_type){ 
	global $database,$admin;
	$aMsg = array();
	if(isEntityNameUsed($field_name) == false){
		$sSql = "INSERT INTO `".STRINGS_FIELDS_TBL."` (`name`) VALUES ('tmp_".uniqid()."')"; 
		if($database->query($sSql)){
			$iID = $database->get_one("SELECT LAST_INSERT_ID()");
			$aData = array(
				'name' => $field_name,
				'type' => $field_type,
				'tpl' => '',
				'add_when' => time(),
				'add_by' => $admin->get_user_id(), /// fehlt hier
				'edit_when' => 0,
				'edit_by' => '',
				'display' => 1,
				'restricted' => 0,
			); 
			if(true == updateRecordFromArray($aData, STRINGS_FIELDS_TBL, 'id', $iID)){	
				// prepare contents
				$sSql = "INSERT INTO `".STRINGS_CONTENTS_TBL."` 
					(`id`, `language`, `content`) VALUES 
					('".$iID."', '%s', '')"; 
				// create new empty records for each language
				$i = 0;
				foreach(array_keys(getActiveLanguages()) as $LC){
					$database->query(sprintf($sSql, $LC));
					$i++;
				}								
				$aMsg['success'] = "{TOOL_TEXT:FIELD_ADD_SUCCESS}";
			}else{ 			
				$aMsg['error'] = $database->get_error();
			}
			
		}else{		
			$aMsg['error'] = $database->get_error();
		}
	} else { 
		// hier noch distinction, ob der einfach in verwendung ist,
		// ob er gelöscht ist oder reserviert vom system (wenn z.B. use_restrictions ON ist)
		$aMsg['error'] = '{TOOL_TEXT:FIELD_NAME_IN_USE}';
	}
	return $aMsg;
}

/**
 *
 */
if (!function_exists('getStringsCfg')) {
	function getStringsCfg(){
		$aCfg = array();
		$sSql = "SELECT `name`, `value` FROM `".STRINGS_CFG_TBL."`";
		if($oCfg = $GLOBALS['database']->query($sSql)){
			while($rec = $oCfg->fetchRow(MYSQL_ASSOC)){
				$aCfg[$rec['name']] = $rec['value'];
			}
		}
		return (object) $aCfg; //for convenience
	}
}
		
/**
 * Remove a String Entity
 * will either remove completly or set to display: 0 (when trash is ON)
 */
if (!function_exists('removeStringEntity')) {
	function removeStringEntity($iID = NULL){
		global $database;
		$iID = intval($iID);
		$aMsg = array();
		$oCfg = getStringsCfg();
		if(
			($oCfg->use_trash == 'yes' && isTrashedEntity($iID) == true) //if trash active and entity already in trash
			|| $oCfg->use_trash == 'no' // or trash inactive
		){
			$database->query("DELETE FROM `".STRINGS_FIELDS_TBL."` WHERE `id` = ".$iID."");
			if($database->get_error()){
				$aMsg['error'] = "Error with: DELETE FROM `".STRINGS_FIELDS_TBL."` WHERE `id` = ".$iID."";
			}else{ 				
				$aMsg['success'] = '{TOOL_TEXT:SUCCESSFULLY_DELETED}';
			}
			$database->query("DELETE FROM `".STRINGS_CONTENTS_TBL."` WHERE `id` = ".$iID."");
				if($database->get_error()){
				$aMsg['error'] = "Error with: DELETE FROM `".STRINGS_CONTENTS_TBL."` WHERE `id` = ".$iID."";
			}
		}elseif($oCfg->use_trash == 'yes' && isTrashedEntity($iID) == false){
			if(updateRecordFromArray(array('display' => 0), STRINGS_FIELDS_TBL, 'id', $iID)){
				$aMsg['success'] = '{TOOL_TEXT:SUCCESSFULLY_TRASHED}';
				updateLastEditInfo($iID);
			}
		}
		return $aMsg;	
	}
}
/**
 * Restore a deleted String Entity
 * will either remove completly or set to display: 0 (when trash is ON)
 */
if (!function_exists('restoreStringEntity')) {
	function restoreStringEntity($iID = NULL){
		$aMsg = array();
		if(updateRecordFromArray(array('display' => 1), STRINGS_FIELDS_TBL, 'id', intval($iID))){
			updateLastEditInfo($iID);
			$aMsg['success'] = '{TOOL_TEXT:SUCCESS_RESTORE_TRASHED}';
		} else {
			$aMsg['error'] = $GLOBALS['database']->get_error();
		}
		return $aMsg;	
	}
}

function printOrderBy(){	
		$aTmp = explode('-', getStringsCfg()->order_by);
		return ' ORDER BY `'.$aTmp[0].'` '.strtoupper($aTmp[1]);	
}


function updateCfg($sName, $mValue){
	if(@getStringsCfg()->$sName){		
		global $database;
		$sprSql = "UPDATE `".STRINGS_CFG_TBL."` SET `value` = '%s' WHERE `name` = '%s'";
		$database->query(sprintf($sprSql, (string) $database->escapeString($mValue), $sName));
	}		
}

function createCfg($sName, $mValue){	
		global $database;
		$sprSql = "INSERT INTO `".STRINGS_CFG_TBL."` (`name`, `value`) VALUES ('%s', '%s')";
		if($database->query(sprintf($sprSql, $database->escapeString($sName), $database->escapeString($mValue)))){
			return (true);
		}
}

function updateLastEditInfo($iFieldID){	
		$aData = array(
			'edit_when' => time(),
			'edit_by' => $GLOBALS['admin']->get_user_id()
		);
		updateRecordFromArray($aData, STRINGS_FIELDS_TBL, 'id', intval($iFieldID));
}

function getOrderByArray(){
	$aSelections = array(
		'name-asc', 
		'name-desc', 
		'type-asc', 
		'type-desc', 
		'add_when-asc', 
		'add_when-desc', 
		'edit_when-asc', 
		'edit_when-desc' 
	);
	$RetArr = array();
	$i = 0;
	foreach($aSelections as $name){
		$RetArr[$i]['name'] = $name;
		$RetArr[$i]['sel'] = (getStringsCfg()->order_by == $name) ? true : false;	
		++$i;
	}
	return $RetArr;	
}

// check whether a distinct String Entity is in trash or not
if (!function_exists('isTrashedEntity')) {
	function isTrashedEntity($iID = NULL){
		$checkDisplayMode = $GLOBALS['database']->get_one(
			"SELECT `display` FROM `".STRINGS_FIELDS_TBL."` WHERE `id` = ".intval($iID));			
		return ($checkDisplayMode == 0) ? true : false;			
	}
}
// check whether a distinct String Entity is in trash or not
if (!function_exists('isEntityNameUsed')) {
	function isEntityNameUsed($sName = ''){
		$checkEntityNameUsed = $GLOBALS['database']->get_one(
			"SELECT `id` FROM `".STRINGS_FIELDS_TBL."` WHERE `name` = '".(string) $sName."'");			
		return ($checkEntityNameUsed == false) ? false : true;			
	}
}