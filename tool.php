<?php
/**
 * WebsiteBaker CMS AdminTool: wbSeoTool
 *
 * tool.php
 * 
 * @platform    CMS WebsiteBaker 2.8.x
 * @package     wbSeoTool
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php", TRUE, 301));
// user needs permission for admintools OR pages
if(!$admin->get_permission('admintools')) {
	exit("insuficient privileges");
}
// Load Functions File
$sFunctionsFile = __DIR__.'/functions.php';	
if(file_exists($sFunctionsFile)) require_once $sFunctionsFile;
// include language file (old way)
include WB_PATH.'/framework/module.functions.php';
include get_module_language_file($sDirname);
$pos = isset($_GET["pos"]) ? $_GET["pos"] : 'modify_strings';
// FEE link? (Comes from the FE to change FEE setting)
if(isset($_GET['backlink']) && isset($_GET['fee'])){
	if(in_array($_GET['fee'], array('yes', 'no'))){
		updateCfg('fe_edit', $_GET['fee']);
		header('Location:'.$_GET['backlink']);
	}
}

include __DIR__.'/functions/droplets.functions.php';
if(isset($_GET['func']) && ($_GET['func'] == "install_droplet")){
	$sDropletFile = __DIR__.'/droplets/string.php';
	if(is_readable($sDropletFile)){
		#wb_dump(dirname($sDropletFile), '$sDropletFile');
		if(importDropletFromFile($sDropletFile)){
			$sMsg = '&msg=&msgtype=success';
			$params = array(
                                'pos' => $pos, //same
                                'msg' => 'TOOL_TEXT:DROPLET_REINSTALLED',
                                'msgtype' => 'success'
			);
			header('Location:'.$toolUrl.'&'.http_build_query($params));
		}
	}else{ 
		$aMsg['error'] = str_replace(WB_PATH, '[ROOT]', $sDropletFile).' was not found!';
	}
}

// initiate $aMsg array for later output.
$aMsg = array();
$config = getStringsCfg();
$oCfg = getStringsCfg();
$aStringTypes = explode(',', $oCfg->string_types); // wysiwyg, textarea, shorttext

// include the file based on $_GET['pos']
// ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$sFileName = '';
switch ($pos) {
	// no switch yet
	case 'help':           $sFileName = 'help';           break;
	case 'modify_config':  $sFileName = 'modify_config';  break;
	case 'modify_strings': 
	default:               $sFileName = 'modify_strings'; break;
}

$sFile = __DIR__.'/'.$sFileName.'.php';
if(file_exists($sFile)) {
	include $sFile;
} else {
	echo "file <tt>{$sFileName}.php</tt> not found";
}