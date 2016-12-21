<?php
/**
 * install.php
 *
 * This file will CREATE TABLE in the DB while installation
 * The TABLE will provide configuration settings needed for the Admin-Tool
 * 
 * @platform    WBCE CMS
 * @package     GlobalStrings
 * @author      Christian M. Stefan (Stefek)
 * @copyright   Christian M. Stefan
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 */

//no direct file access
if(count(get_included_files())==1) die(header("Location: ../index.php", TRUE, 301));
	
// install Droplet
include __DIR__.'/functions/droplets.functions.php';
$sDropletFile = __DIR__.'/droplets/string.php';
if(is_readable($sDropletFile)){
	if(importDropletFromFile($sDropletFile)){
		echo 'Droplet <b>string</b> installed successfully.';
	}
}
			
// get functions file
require __DIR__.'/functions.php'; 	
// check if we should install any DB TABLE's or if this is an upgrade
if(db_table_exists(STRINGS_CFG_TBL) == true) {
	exit;
}

$aQueries = array();
// _cfg table
$aQueries[] = "CREATE TABLE IF NOT EXISTS `".STRINGS_CFG_TBL."` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (id)
)  ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";


// _fields table
$aQueries[] = "CREATE TABLE IF NOT EXISTS `".STRINGS_FIELDS_TBL."` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `name`       varchar(255) NOT NULL DEFAULT '',
  `type`       varchar(32) NOT NULL DEFAULT '',
  `tpl`        text NOT NULL DEFAULT '',
  `add_when`   int(12) NOT NULL DEFAULT '0',
  `add_by`     int(11) NOT NULL DEFAULT '0',
  `edit_when`  int(12) NOT NULL DEFAULT '0',
  `edit_by`    int(11) NOT NULL DEFAULT '0',
  `display`    int(1) NOT NULL DEFAULT '1',
  `restricted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";
// display (0 = deleted, 1= visible)
// restricted (0 = no,anyone may edit content; 1= only users of group_id = 1 have access)

// _contents table
$aQueries[] = "CREATE TABLE IF NOT EXISTS `".STRINGS_CONTENTS_TBL."` (
  `unique_id` int(11) NOT NULL AUTO_INCREMENT,
  `id`        int(11) NOT NULL DEFAULT '0',
  `language`  varchar(3) NOT NULL DEFAULT '',
  `content`   text NOT NULL DEFAULT '',
  PRIMARY KEY (unique_id)
) ENGINE=MyIsam AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci ;";

// add droplet to mod_droplets table

echo '<br>Installing: ';
foreach($aQueries as $sSql){
	$database->query($sSql);
	echo '. . . '; 
}
// add config settings
$aConfig = array(
	'string_types' => 'textarea,shorttext',
	'lang_fallback' => 'no',
	'use_tpl' => 'no',
	'use_trash' => 'yes',
	'use_restrictions' => 'no',
	'fe_edit' => 'no',
	'order_by' => 'add_when-desc',
);

foreach($aConfig as $name=>$value){
	if(createCfg($name, $value)){
		echo '<br><span style="color:green;font-weight:bold">'.$name.' => '.$value.'</span>';
	}
}

