<?php include __DIR__.'/head.tpl.php'; 
	#wb_dump();
?>
<form action="<?=$toolUrl.'&pos=modify_config' ?>"  method="post" name="config_editor">
<input type="hidden" name="use_templates" value="0">
<?=$admin->getFTAN(); ?>  
<div class="be_settings">
	<div class="headingRow"><?php echo $TEXT['SETTINGS'] ?></div>	
	<div class="settingRow">
		<label class="settingName">Multilingual:</label>	
		<div class="settingValue">
			<ul style="list-style:none;">
			<?php foreach(getActiveLanguages() as $LC=>$lang):?>
				<li><img src="<?=THEME_URL ?>/images/flags/<?=strtolower($LC) ?>.png"> <?=$LC ?> <?=$lang ?></li>
			<?php endforeach; ?>
			</ul>
			<p class="settingHint"><?=$TOOL_TEXT['ALL_LANG_INFO']?></p>
		</div>
	</div>
	<div class="settingRow">
		<label class="settingName">FE-Edit Link:</label>	
		<div class="settingValue">
				<input name="fe_edit" id="fe_edit_yes" type="radio" <?=($oCfg->fe_edit == 'yes' ? ' checked' : '')?> value="yes">
				<label for="fe_edit_yes"><?=$TEXT['ENABLED'] ?></label>
				<input name="fe_edit" id="fe_edit_no" type="radio" <?=($oCfg->fe_edit == 'no' ? ' checked' : '')?> value="no">
				<label for="fe_edit_no"><?=$TEXT['DISABLED'] ?></label>
			<p class="settingHint"><?=$TOOL_TEXT['FE_EDIT_INFO']?></p>
		</div>
	</div>
	<div class="settingRow">
		<label class="settingName"><?=$TEXT['PAGE_TRASH'] ?>/<?=$TEXT['RESTORE'] ?>:</label>	
		<div class="settingValue">
				<input name="use_trash" id="use_trash_yes" type="radio" <?=($oCfg->use_trash == 'yes' ? ' checked' : '')?> value="yes">
				<label for="use_trash_yes"><?=$TEXT['ENABLED'] ?></label>
				<input name="use_trash" id="use_trash_no" type="radio" <?=($oCfg->use_trash == 'no' ? ' checked' : '')?> value="no">
				<label for="use_trash_no"><?=$TEXT['DISABLED'] ?></label>
			<p class="settingHint"><?=$TOOL_TEXT['TRASH_INFO'] ?></p>
		</div>
	</div>
	<?php if(canModifyRestricted()): ?>			
	<div class="settingRow">
		<label class="settingName"><?=$TOOL_TEXT['CHECK_RESTRICTED']?>:</label>	
		<div class="settingValue">
				<input name="use_restrictions" id="restr_yes" type="radio" <?=($oCfg->use_restrictions == 'yes' ? ' checked' : '')?> value="yes">
				<label for="restr_yes"><?=$TEXT['ENABLED'] ?></label>
				<input name="use_restrictions" id="restr_no" type="radio" <?=($oCfg->use_restrictions == 'no' ? ' checked' : '')?> value="no">
				<label for="restr_no"><?=$TEXT['DISABLED'] ?></label>
			<p class="settingHint">
				<b><?=$TEXT['ENABLED'] ?>:</b> <?=$TOOL_TEXT['RESTRICTED_INFO']?><br>
				<b><?=$TEXT['DISABLED'] ?>:</b> <?=$TOOL_TEXT['UNRESTRICTED_INFO']?><br>
			</p>
		</div>
	</div>	
	<?php endif; ?>
	<!--
	<div class="settingRow">
		<label class="settingName">Use language Fallback:</label>	
		<div class="settingValue">
			<select id="language_fallback" name="language_fallback">
				<option disabled="disabled" value=""><?=$TEXT['PLEASE_SELECT'] ?></option>
				<option value="none"<?=($oCfg->lang_fallback == 'none' ? ' selected' : '')?>>~~ <?=$TEXT['NONE'] ?> ~~</option>
				<?php foreach(getActiveLanguages() as $LC=>$lang):?>	
					<option value="<?=$LC ?>"<?=($oCfg->lang_fallback == $LC ? ' selected' : '')?>>[<?=$LC ?>] <?=$lang ?></option>
				<?php endforeach ?>
			</select>
			<p class="settingHint">Dev. Info: Fallback funktioniert noch nicht</p>
		</div>
	</div>	
	-->
	<div class="settingRow">
		<label class="settingName">String-<?=$TEXT['TYPE']?>:</label>	
		<div class="settingValue">
			<div id="">
				<?php foreach($aPossibleStringTypes as $type): ?>
					<input type="checkbox" id="<?=$type ?>" name="fields[<?=$type ?>]" value="1" 
					<?=(in_array($type,$aStringTypes ) || $type == 'textarea') ? 'checked' : '' 
					?><?=($type == 'textarea') ? ' disabled' : '' ?>/>
					<label for="<?=$type ?>"><?=ucfirst($type) ?></label>					
				<?php endforeach ?>
			</div>
		</div>
	</div>	
	<div class="buttonsRow">
		<span style="float:left;padding:6px;"><a href="<?php echo $toolUrl ?>">&laquo; <?php echo $TEXT['BACK'] ?></a></span>
		<input type="submit" name="save_config" value="<?php echo $TEXT['SAVE'] ?>" />
	</div>
</div>
</form>
<?php include __DIR__.'/foot.tpl.php'; ?>