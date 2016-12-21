<?php

// install Droplet again with the new import function
include __DIR__.'/functions/droplets.functions.php';
$sDropletFile = __DIR__.'/droplets/string.php';
if(is_readable($sDropletFile)){
	if(importDropletFromFile($sDropletFile)){
		echo 'Droplet <b>string</b> upgraded successfully.';
	}
}
