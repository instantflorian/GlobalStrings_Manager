<?php 
/**
 * WBCE CMS AdminTool: global_strings
 *
 * This file defines the obligatory variables required for WBCE CMS
 * 
 * 
 * @platform    WBCE CMS
 * @package     global_strings
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */
 
//no direct file access
if(count(get_included_files()) == 1) die(header("Location: ../index.php", TRUE, 301));


/**
 *	Check if field_id is set and sanitize accordingly
 */
$iFieldID = isset($_GET['field_id']) ? $_GET['field_id'] : NULL;
// in case $iFieldID is set by now but is not numeric, check the ID Key
if($iFieldID != NULL && is_numeric($iFieldID) == false ){
	if ( !($iFieldID = (int) $admin->checkIDKEY('field_id', 0, $_SERVER['REQUEST_METHOD']) )) {
		$admin->print_error($MESSAGE['GENERIC_SECURITY_ACCESS']);
	}
}

/**
 *	set Action for the Switch
 */
$sAction = '';
if ($iFieldID != NULL && isset($_GET['func'])){
	if(in_array($_GET['func'], array('restore_field', 'delete_field'))){
	// ~~~~~~~~~~~~~~~~~~~~~~
		$sAction = $_GET['func'];
	}
}

// are we saving or updating any records?
if(isset($_POST) || isset($_GET['func'])){	
$sNameMatch = '/^[a-zA-Z][a-zA-Z_\-0-9]{2,}$/i';
	$aMsg = array();
	switch(true){
		
		// ~~~~~~~| add_new_field (when a new string is added in the add string form)	
		case (isset($_POST['add_new_field'])): 
			if ($admin->checkFTAN()) {
				if(!isset($_POST['field_type']) || $_POST['field_type']== ''){
					$aMsg['error'] = "{TOOL_TEXT:FIELDTYPE_NOT_SELECTED}";
				}
				if(isset($_POST['field_name'])){
					if(!preg_match($sNameMatch, $_POST['field_name'])){
						$aMsg['error'] = "{TOOL_TEXT:NAME_WRONG_REGEX}";
					}else{
						$sFieldName = $_POST['field_name']; 
							//preserve for form input in case type was not selected
					}
				}
				if(isset($_POST['field_type']) && isset($sFieldName)){					
					if(!in_array($_POST['field_type'], $aStringTypes)){
						$aMsg['error'] = "{TOOL_TEXT:UNKNOWN_FIELDTYPE}";
					}
					if(empty($aMsg)){
						$aMsg = addNewStringEntity($sFieldName, $_POST['field_type']);
						$iID = $database->get_one("SELECT MAX(id) FROM `".STRINGS_FIELDS_TBL."`");
						$params = array(
                                                        'pos' => $pos, //same
                                                        'hilite' => $iID,
                                                        'msg' => $aMsg[key($aMsg)],
                                                        'msgtype' => key($aMsg)
                                                );
						header('Location:'.$toolUrl.'&'.http_build_query($params).'#fields_'.$iID);
					}
				}
			}
		break;
		
		// ~~~~~~~| save_field_changes (when string group input changes are saved)	
		case (isset($_POST['save_field_changes'])): 
			$aMsg = array();
			if ($admin->checkFTAN()) {
				$iID = $_POST['field_id'];
				$aTmp = $_POST['save']['content'];
				$sType = key($aTmp);				
				$aContents = $_POST['save']['content'][$sType];
				$aContents['restricted'] = (array_key_exists('restricted', $_POST)) ? true : false;
				$aMsg = updateStringContents($aContents, $iID);
				$params = array(
					'pos' => $pos, //same
					'hilite' => $iID,
					'msg' => $aMsg[key($aMsg)],
					'msgtype' => key($aMsg)
				);
				header('Location:'.$toolUrl.'&'.http_build_query($params).'#fields_'.$iID);
			}
		break;	
		
		// ~~~~~~~| change_order_by	(when we select a new order by condition)
		case (isset($_POST['change_order_by'])): 
			$aMsg = array();
			if ($admin->checkFTAN()) {
				if(!preg_match($sNameMatch, $_POST['order_by'])){
					$aMsg['error'] = "{TOOL_TEXT:NAME_WRONG_REGEX}";
				}else{
					updateCfg('order_by', $_POST['order_by']);
					$aMsg['success'] = "{TOOL_TEXT:UPDATE_SUCCESS}"; 
				}
			}
		break;
		
		// ~~~~~~~| delete_field		
		case ($sAction == 'delete_field'): 
			$aMsg = removeStringEntity($iFieldID);
			$params = array(
				'pos' => $pos, //same
				'hilite' => $iFieldID,
				'msg' => $aMsg[key($aMsg)],
				'msgtype' => key($aMsg)
			);
			header('Location:'.$toolUrl.'&'.http_build_query($params).'#fields_'.$iFieldID);
		break;
		
		// ~~~~~~~| restore_field
		case ($sAction == 'restore_field'): 
			$aMsg = restoreStringEntity($iFieldID);
			$params = array(
				'pos' => $pos, //same
				'hilite' => $iFieldID,
				'msg' => $aMsg[key($aMsg)],
				'msgtype' => key($aMsg)
			);
			header('Location:'.$toolUrl.'&'.http_build_query($params).'#fields_'.$iFieldID);
		break;
		
		default:
		break;
	}
}

include __DIR__.'/theme/modify_strings.tpl.php';
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~  TEMPLATE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~|