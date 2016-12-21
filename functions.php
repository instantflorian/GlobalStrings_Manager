<?php
/**
 * @platform    WebsiteBaker Community Edition
 * @package     GlobalStrings Manager
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php", TRUE, 301));

$aPossibleStringTypes = array('textarea', 'shorttext', 'wysiwyg');

$sDirname = basename(dirname(__FILE__));
if(!defined('STRINGS_CFG_TBL'))           define('STRINGS_CFG_TBL', TABLE_PREFIX ."mod_".$sDirname."_cfg");
if(!defined('STRINGS_FIELDS_TBL'))     define('STRINGS_FIELDS_TBL', TABLE_PREFIX ."mod_".$sDirname."_fields");
if(!defined('STRINGS_CONTENTS_TBL')) define('STRINGS_CONTENTS_TBL', TABLE_PREFIX ."mod_".$sDirname."_contents");

if(!isset($toolUrl))	  $toolUrl     = ADMIN_URL.'/admintools/tool.php?tool='.$sDirname;
if(!isset($modulePath))   $modulePath  = WB_PATH.'/modules/'.$sDirname;
if(!isset($toolPath))     $toolPath    = WB_PATH.'/modules/'.$sDirname;
if(!isset($module_name))  $module_name = 'GlobalStrings Manager';

$icons = WB_URL.'/modules/'.$sDirname.'/icons';


global $TOOL_TEXT;
// include useful common functions
include_once('functions/common.functions.php');
//include this AdminTool's specific functions
include_once('functions/global_strings.functions.php');