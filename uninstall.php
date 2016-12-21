<?php
/**
 * uninstall.php
 * This file executes a DROP TABLE query while the module is being uninstalled
 * 
 * 
 * @platform    WBCE CMS
 * @package     GlobalStrings Manager
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php", TRUE, 301));

define('TABLE', TABLE_PREFIX . 'mod_global_strings');
// remove $sTable from WB database
$aTables = array(
	TABLE.'_cfg',
	TABLE.'_strings',
	TABLE.'_contents'
);
// drop tables
foreach ($aTables as $row){
	if($database->query("DROP TABLE IF EXISTS `".$row."`")){
		echo "<p>DB Table <b>".$row."</b> was dropped successfully!</p>";
	}
}

// delete droplet 
$database->query("DELETE FROM `".TABLE_PREFIX."mod_droplets` WHERE `name` = 'string'");
