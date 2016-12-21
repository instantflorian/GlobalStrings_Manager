<?php 
$aAllFields = getAllContents();
#wb_dump($aAllFields);
include __DIR__.'/head.tpl.php';
?><?php 
#wb_dump($oCfg->use_trash, 'tras');
?>
<div class="be_half">	
		<h2>Liste der Strings:</h2>
		<?php if(!empty($aAllFields)): ?>
		<!--
		<form name="order_by_form" action="<?=$toolUrl?>&pos=modify_strings" method="post">
			<?=$admin->getFTAN(); ?><?=$TOOL_TEXT['ORDER_BY'] ?>: 
			<select name="order_by">
				<?php 
				foreach(getOrderByArray() as $opt): ?>
				<option<?=($opt['sel'] == 1) ? ' selected="selected"' : '' ?> value="<?=$opt['name']?>">
					<?=$TOOL_TEXT[$opt['name']] ?>
				</option>
				<?php endforeach; ?>
			</select><input type="submit" name="change_order_by" value="go!">
		</form>
		-->
		<table class="stringsList">		
			<?php
				foreach($aAllFields as $id=>$field):			
				$sRestriction = ($field['restricted'] == 0 ? 'un' : '').'restricted';
				$sTrashed = (isTrashedEntity($id) ? '' : 'not_').'trashed';
			?>	
			<tr>
				<td>
				<?php if(canModifyRestricted()): ?>
					<img src="<?=$icons?>/<?=$sRestriction?>.png" alt="[<?=$sRestriction?>]" title="<?=$TOOL_TEXT[strtoupper($sRestriction).'_INFO'] ?>">
				<?php else: ?>
					<img src="<?=$icons?>/<?=$sRestriction?>.png" alt="" title="GlobalString">
				<?php endif; ?>
				</td>
				<td>
					<span  title="<?=$TEXT['MODIFY']?>" style="<?=($sTrashed == 'trashed') ? "text-decoration: line-through;" : '' ?>">
						<a href="#fields_<?=$id?>"><?=$field['name']?></a>
					</span>
				</td>
				<td>
					<?php if($sTrashed == 'trashed'): ?>
						<img src="<?=$icons?>/trash_16.png" alt="" title="<?=$TEXT['DELETED'] ?>">
					<?php endif; ?>
				</td>
				<td style="text-align:right;">
					<span style="font-family:monotype,courier;font-weight:600">[<?=$field['type']?>]</span>
				</td>
			</tr>
			<?php endforeach; ?> 
		</table>
		<?php else: ?>
		<div class="be_infobox">Es sind noch keine Strings angelegt worden.</div>
		<?php endif; ?>
</div>

<div class="be_settings be_half">
	<form name="add_field" action="<?=$toolUrl?>&pos=modify_strings" method="post" autocomplete="off">
		<?=$admin->getFTAN(); ?>
		<div class="headingRow"><?=$TEXT['ADD'] ?> GlobalString</div>						
		<div class="settingRow">
			<label class="settingName">String-<?=$TEXT['TYPE']?>:</label>
			<div class="settingValue">
				<select id="field_type" name="field_type">
					<?php if(count($aStringTypes) > 1) : ?>
					<option disabled="disabled" selected="selected" value="" style="font-weight:bold;"><?=$TEXT['PLEASE_SELECT'] ?> . . .</option>					
					<?php endif; ?>	
					<?php foreach($aStringTypes as $type): ?>	
						<option value="<?=$type ?>"><?=$type ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>	
		<div class="settingRow">
			<label class="settingName">Field-Name:</label>
			<div class="settingValue"><input type="text" name="field_name" value="<?=(isset($sFieldName) && $sFieldName != '' ? $sFieldName : '')?>">
			<p class="formHint"><?=$TOOL_TEXT['NAME_WRONG_REGEX'] ?></p>
			</div>
		</div>
		<div class="buttonsRow"><input type="submit" name="add_new_field" value="<?=$TEXT['SAVE']?>"></div>
	</form>
</div>	
<div style="clear:both;"></div>
<?php if(!empty($aAllFields)): ?>
<hr>	
	<?php
foreach($aAllFields as $id=>$field): 
	$sHilite = (isset($_GET['hilite']) && $_GET['hilite'] == $id) ? ' hilite' : '';
	$fieldIDKey = $admin->getIDKEY($id);
	$sRestriction = ($field['restricted'] == 0 ? 'un' : '').'restricted';
	$sTrashed = (isTrashedEntity($id) ? '' : 'not_').'trashed';
?>
<form id="fields_<?=$id?>" name="update_field" action="<?=$toolUrl?>&pos=modify_strings" method="post" autocomplete="off">
	<?=$admin->getFTAN(); ?>
	<input type="hidden" name="field_id" value="<?=$id ?>">
<div class="be_settings<?=$sHilite;?><?php if(isTrashedEntity($id)): ?> stringDeleted<?php endif ?>">
	<div class="headingRow heading<?=ucfirst($sRestriction);?>">

	<img src="<?=$icons?>/<?=$sRestriction?>.png" alt="[<?=$sRestriction?>]" title="<?=$TOOL_TEXT[strtoupper($sRestriction).'_INFO'] ?>">
	"<?=$field['name']?>"
		<input type="text" id="droplet_<?=$id?>" style="width:350px" value="[[string?name=<?=$field['name']?>]]">
		<img class="clipper" title="copy to clipboard" data-clipboard-target="#droplet_<?=$id?>" src="<?=$icons?>/clippy.png" alt="Copy to clipboard">
		<span style="float:right; font-size:11px;">
			<a title="<?=$TEXT['DELETE'] ?> string: '<?=$field['name'] ?>' " href="<?=$toolUrl ?>&func=delete_field&field_id=<?=$fieldIDKey ?>" class="<?=(isTrashedEntity($id) || $oCfg->use_trash == 'no') ? 'delete' : 'trash' ?>">		
				<img src="<?=$icons?>/delete_16.png" alt="<?=$TEXT['DELETE'] ?>">
			</a>
			<?php if(isTrashedEntity($id)): ?>&nbsp;
			<a href="<?=$toolUrl ?>&func=restore_field&field_id=<?=$fieldIDKey ?>" title="<?=$TEXT['RESTORE'] ?> <?=$TOOL_TEXT['STRING'] ?>">		
				<img  src="<?=$icons?>/restore_16.png" alt="<?=$TEXT['RESTORE'] ?>">
			</a>
	<?php endif ?>
		</span>
	</div>
	<?php 
		foreach($field['content'] as $lang=>$value): 
			if(isTrashedEntity($id) == false) :		
	?>	
		<div class="singleRow<?=(isTrashedEntity($id) ? ' trashed' : '')?>">
			<div>
			<span title="unique=<?=$value['unique_id']?>">
				<img src="<?=THEME_URL ?>/images/flags/<?=strtolower($lang) ?>.png"> <b><?=$lang?></b>
			</span>
			<span style="float:right; font-size:11px;"></span>
				<!-- <div>
					<input type="text" id="droplet_u<?=$value['unique_id']?>" style="width:350px" value="[[field?unique=<?=$value['unique_id']?>]]">
					<img class="clipper" title="copy to clipboard" data-clipboard-target="#droplet_u<?=$value['unique_id']?>" src="<?=$icons?>/clippy.png" alt="Copy to clipboard">
				</div> -->
			</div>
			
			<div>			
			<?=draw_field(
				$field['type'], 
				$lang,  
				$id, 
				$value['unique_id'], 
				$value['content']
				); ?>
			</div>
		</div>
		<?php else: ?>
		&nbsp;&nbsp;<img src="<?=THEME_URL ?>/images/flags/<?=strtolower($lang) ?>.png"> <b><?=$lang?></b> UniqueID: <?=$value['unique_id']?>
		<?php endif; 
		endforeach;  
	?>
	<?php if($sTrashed != 'trashed'): ?>
	<div class="buttonsRow">
		<?php if(canModifyRestricted() && (getStringsCfg()->use_restrictions == 'yes')) : ?>
			<span style="float:left">
				<input type="checkbox" name="restricted" id="restr<?=$id?>" value="1"<?=($field['restricted'] == 1) ? ' checked="checked"' : '' ?>>
				<label for="restr<?=$id?>"><?=$TOOL_TEXT['CHECK_RESTRICTED']?></label>
			</span>
			<?php endif ?>
		<input type="submit" name="save_field_changes" value="<?=$TEXT['SAVE']?>">
	</div>
	<?php endif; ?>
</div>
</form>
<?php endforeach; 
endif; // if(!empty($aAllFields)): 
?>
<?php include __DIR__.'/foot.tpl.php'; ?>