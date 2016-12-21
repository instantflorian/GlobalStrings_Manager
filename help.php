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

include __DIR__.'/theme/head.tpl.php';
$sFile = __DIR__.'/help/'.LANGUAGE.'.htm';
if(file_exists($sFile))	include $sFile;
else include __DIR__.'/help/EN.htm'; 
include __DIR__.'/theme/foot.tpl.php';