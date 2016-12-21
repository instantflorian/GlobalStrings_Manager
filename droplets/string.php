<?php
//:Droplet that works together with the GlobalString Manager
//:Use:<br> [[string?name=YourString]] in Template or on any CMS Page. <br> [[string?unique=7]] to get a specific unique String. (replace 7 with uniqueID you need.) <br> [[string?fee_switch=1]] to display a switch link to enable/disable FEE (frontend edit).</comments>

$RetVal = "error(droplet:string)";
if(!function_exists("getStringByName")){
	$sFile = WB_PATH."/modules/global_strings/include.php";	
	if(is_file($sFile)) require_once $sFile;
}
if(function_exists("getStringByName")){
	if(isset($name))
		$RetVal = getStringByName((string) $name);
	elseif(isset($unique))
		$RetVal = getStringByUniqueID(intval($unique));	
	elseif(isset($fee_switch))
		$RetVal = getFeeSwitch();	
}
return $RetVal;