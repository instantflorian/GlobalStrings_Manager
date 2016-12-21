<?php
/**
 * 
 * @platform    WebsiteBaker Community Edition CMS
 * @package     GlobalStrings Manager
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */
 
/**
 *  ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
	Definde CONSTANTS to use in functions instead of long table names
 *	~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
**/
$sDirname = basename(dirname(__FILE__));
if(!defined('STRINGS_CFG_TBL'))           define('STRINGS_CFG_TBL', TABLE_PREFIX ."mod_".$sDirname."_cfg");
if(!defined('STRINGS_FIELDS_TBL'))     define('STRINGS_FIELDS_TBL', TABLE_PREFIX ."mod_".$sDirname."_fields");
if(!defined('STRINGS_CONTENTS_TBL')) define('STRINGS_CONTENTS_TBL', TABLE_PREFIX ."mod_".$sDirname."_contents");
	



/**
   *
    * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
       PUBLIC FUNCTIONS TO USE ALONG WITH [[string]] droplet
    * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   *
**/ 
if(!function_exists('getStringByName')){
	function getStringByName($sName){	
		global $database;
		$sMsg = '';
		$sContent = '';
		$sNameMatch = '/^[a-zA-Z][a-zA-Z_\-0-9]{2,}$/i';
		if(!preg_match($sNameMatch, $sName)){
			if (true == _stringsCheckAdminPerms()) {
				$sMsg .= '<span style="color:red">Invalid argument for name (droplet: "string")</span>';
			}
		}
		if($iID = _getStringIdFromName($sName)){
			if($sContent = $database->get_one(
			"SELECT `content` FROM `". STRINGS_CONTENTS_TBL ."` 
				WHERE `id` = '".$iID."' AND `language` = '".LANGUAGE."'")){				
				$sContent = str_replace('{SYSVAR:MEDIA_REL}', WB_URL.MEDIA_DIRECTORY, $sContent);
			}	
		}else{ 
			if (true == _stringsCheckAdminPerms()) {
				$sMsg .= '<span style="color:red">String not found: '.$sName.', '.LANGUAGE.' (droplet:"string")</span">';
			}
		}
		return ($sMsg == '') ? _drawEditLink($sContent, $iID) : $sMsg;
	}
}

if(!function_exists('getStringByUniqueID')){
	function getStringByUniqueID($iUID){	
		global $database;
		$sMsg = '';
		$sContent = '';
		if(!is_numeric($iUID)){
			$sMsg .= "Invalid argument for UniqueID, numbers only";
		}
		$iID = _getStringIdFromUnique($iUID);
		if('1' == $database->get_one(
			"SELECT `display` FROM `". STRINGS_FIELDS_TBL ."` WHERE `id` = ".$iID)
		){
			if($sContent = $database->get_one(
				"SELECT  `content` FROM `". STRINGS_CONTENTS_TBL ."` WHERE `unique_id` = '".$iUID."'")
			){	
				$sContent = str_replace('{SYSVAR:MEDIA_REL}', WB_URL.MEDIA_DIRECTORY, $sContent);	
			}
		}
		if($sContent == '' && _stringsCheckAdminPerms()) {
			$sMsg .= '<span style="color:red">String not found: String not found (unique: '.$iUID.')</span>';
		}
		return ($sMsg == '') ? _drawEditLink($sContent, $iID) : $sMsg;
	}
}


if(!function_exists('getFeeSwitch')){
	function getFeeSwitch(){
		$sLink = '';
		if(_stringsCheckAdminPerms() == true){
			global $wb, $TEXT;
			$sNewCfg = (false == _isFeeEnabled()) ? 'yes' : 'no';
			$sURL = ADMIN_URL.'/admintools/tool.php?tool=global_strings&fee='.$sNewCfg.'&backlink='.$wb->link;
			$sTEXT = strtolower($TEXT[(_isFeeEnabled()) ? 'ENABLED' : 'DISABLED']);
			$sLink = '<a id="fee_strings" href="'.$sURL.'#fee_strings">String FEE is '.$sTEXT.'</a>';
			$sCssFile = '';
			$aPositions = array(
				// use override if css file exists in template
				WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/css/global_strings_fee.css',
				WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/global_strings_fee.css',
				dirname(__FILE__).'/global_strings_fee.css',
			);
			foreach($aPositions as $file){				
				if(file_exists($file)){
					$sCssFile = str_replace(WB_PATH, WB_URL, $file); break;
				}
			}
			$sLink .= '<link href="'.$sCssFile.'" rel="stylesheet" type="text/css">';
		}
		return $sLink;
	}
}


/**
   *
    * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
                                 functions not used publicly  
    * ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
   *
**/ 

if(!function_exists('_isFeeEnabled')){
	function _isFeeEnabled(){
		return($GLOBALS['database']->get_one(
				"SELECT `value` FROM `". STRINGS_CFG_TBL ."` WHERE `name` = 'fe_edit'"
			) == 'yes') ? true : false;
	}
}
if(!function_exists('_drawEditLink')){
	function _drawEditLink($sContent, $iID){
		if(_stringsCheckAdminPerms() == false){
			return $sContent;
		}else{
			global $database;
				
			if("yes" == $database->get_one("SELECT `value` FROM `".STRINGS_CFG_TBL."` WHERE `name` = 'fe_edit'")) {					
				// check if this $iID is a restricted one and check for permissions of the user 
				if('1' == $database->get_one(
					"SELECT `restricted` FROM `".STRINGS_FIELDS_TBL."` WHERE `id` = ".$iID)
					&& (_canModifyRestricted()) == false){					
					return $sContent;	
				}					
				$sLink = ADMIN_URL.'/admintools/tool.php?tool=global_strings&pos=modify_strings&hilite='.$iID.'#fields_'.$iID;
				$sContent = '<div class="stringFeeContent">'.$sContent.'
					<span class="stringFeeBELink"><a href="'.$sLink.'" target="_blank">[edit]</a></span>
				</div>';
			}
		}
		return $sContent;
	}
}

if(!function_exists('_getStringIdFromName')){
	function _getStringIdFromName($sName){
		return	$GLOBALS['database']->get_one(
			"SELECT `id` FROM `". STRINGS_FIELDS_TBL ."` 
				WHERE `name` = '".$sName."' AND `display` <> 0"
		);
	}
}

if(!function_exists('_getStringIdFromUnique')){
	function _getStringIdFromUnique($iUID){
		return	$GLOBALS['database']->get_one(
			"SELECT `id` FROM `".STRINGS_CONTENTS_TBL."` WHERE `unique_id` = ".$iUID
		);
	}
}

if(!function_exists('_canModifyRestricted')){
	function _canModifyRestricted(){
		return (
			$GLOBALS['wb']->ami_group_member('1') == true
		) ? true : false;  
	}
}

if(!function_exists('_stringsCheckAdminPerms')){
	function _stringsCheckAdminPerms(){
		return (
			$GLOBALS['wb']->is_authenticated() 
			&& in_array('admintools', $_SESSION['SYSTEM_PERMISSIONS'])
		) ? true : false;
	}
}
