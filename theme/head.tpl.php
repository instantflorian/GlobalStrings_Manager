<?php 
// we need this to prevent: "Warning: Cannot modify header information - headers already sent"
// http://forum.wbce.org/viewtopic.php?pid=5213#p5213



// was a Message set somewhere that should be displayed to the user?
if(isset($_GET['msg'])){
	$type = isset($_GET['msgtype']) ? (string) $_GET['msgtype'] : 'infobox';	
	$aMsg[$type] = $_GET['msg'];
}
if(!empty($aMsg)){
	$key = key($aMsg);
	foreach($aMsg as $msg): ?>
	<div class="be_infobox be_<?=$key ?>"><?=L_($msg) ?></div>
<?php endforeach;
}

$sDropletName = "string";
if(isDroplet($sDropletName) == false):
?>
<div class="be_infobox be_error">
<h2>Droplet "<?=$sDropletName?>" is missing, please click the link below to reinstall.</h2>
<a href="<?=$toolUrl?>&func=install_droplet" class="button">Install Droplet</a>
</div>
<?php endif; ?>
<noscript><?=$TOOL_TEXT['NOSCRIPT_MESSAGE']; ?></noscript>
<div class="pane">
	<div class="pageinfo">
		<div class="pagetitle"></div>
	</div>
	<nav class="tabs">
		<ul>
			<li class="page-title">
				<span>Admin-Tool: <strong><?=$module_name ?></strong></span>
			</li>
			<li>
				<a class="tabHelp tab<?=($pos == 'help' ? ' sel' : '')?>" href="<?=$toolUrl?>&pos=help">
					<?=$MENU['HELP'] ?>
				</a>
			</li>
			<li>
				<a class="tabModify tab<?=($pos == 'modify_config' ? ' sel' : '')?>" href="<?=$toolUrl?>&pos=modify_config">
					<?=$TEXT['SETTINGS'] ?>
				</a>
			</li>
			<li>
				<a class="tabConfig tab<?=($pos == 'modify_strings' ? ' sel' : '')?>" href="<?=$toolUrl?>&pos=modify_strings">
					<?=$TOOL_TEXT['MANAGE_STRINGS'] ?>
				</a>
			</li>
		</ul>					
	</nav>
	
<?php 
/*
<!-- </div class="pane"> -->
// closing div in the foot.tpl.php
*/ ?>