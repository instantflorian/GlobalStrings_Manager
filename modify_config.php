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
if(count(get_included_files())==1) die(header("Location: ../index.php",TRUE,301));
// possible string types
if(isset($_POST['save_config'])){
	if ($admin->checkFTAN()) {
		$aMsg = array();
		$aFields = array();
		$aFields[] = 'textarea';
		foreach($admin->get_post('fields') as $key=>$val){
			if($val != true) continue;
			$aFields[] = $key;
		}	
		updateCfg('string_types', implode(',', $aFields));
		updateCfg('lang_fallback', $admin->get_post('language_fallback'));
		updateCfg('use_templates', $admin->get_post('use_templates'));
		updateCfg('use_trash', $admin->get_post('use_trash'));
		updateCfg('fe_edit', $admin->get_post('fe_edit'));
		if(isset($_POST['use_restrictions'])){
			updateCfg('use_restrictions', $admin->get_post('use_restrictions'));
		}
		if(!$database->get_error) {	
			$params = array(
					'pos' => $pos, //same
					'msg' => 'TOOL_TEXT:CONFIG_UPDATE_SUCCESS',
					'msgtype' => 'success'
			);
			header('Location:'.$toolUrl.'&'.http_build_query($params));
		}else{ 
			$aMsg['error'] = $database->get_error();
		}
	}
} 

include __DIR__.'/theme/modify_config.tpl.php';		
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~  TEMPLATE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~|