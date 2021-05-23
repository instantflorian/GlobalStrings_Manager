<?php include __DIR__.'/head.tpl.php'; 
	#wb_dump();
?>
<form action="<?php echo $toolUrl.'&pos=modify_config' ?>"  method="post" name="config_editor">
<input type="hidden" name="use_templates" value="0">
<?php echo $admin->getFTAN(); ?>  
<div class="be_settings">
	<div class="headingRow"><?php echo $TEXT['SETTINGS']; ?></div>	
	<div class="settingRow">
		<label class="settingName">Multilingual:</label>	
		<div class="settingValue">
			<ul style="list-style:none;">
			<?php foreach(getActiveLanguages() as $LC=>$lang):?>
				<li><img src="<?php echo WB_URL; ?>/languages/<?php echo strtoupper($LC) ?>.png"> <?php echo $LC ?> <?php echo $lang; ?></li>
			<?php endforeach; ?>
			</ul>
			<p class="settingHint"><?php echo $TOOL_TEXT['ALL_LANG_INFO']?></p>
		</div>
	</div>
	<div class="settingRow">
		<label class="settingName">FE-Edit Link:</label>	
		<div class="settingValue">
				<input name="fe_edit" id="fe_edit_yes" type="radio" <?php echo ($oCfg->fe_edit == 'yes' ? ' checked' : '')?> value="yes">
				<label for="fe_edit_yes"><?php echo $TEXT['ENABLED']; ?></label>
				<input name="fe_edit" id="fe_edit_no" type="radio" <?php echo ($oCfg->fe_edit == 'no' ? ' checked' : '')?> value="no">
				<label for="fe_edit_no"><?php echo $TEXT['DISABLED']; ?></label>
			<p class="settingHint"><?php echo $TOOL_TEXT['FE_EDIT_INFO']?></p>
		</div>
	</div>
	<div class="settingRow">
		<label class="settingName"><?php echo $TEXT['PAGE_TRASH']; ?>/<?php echo $TEXT['RESTORE']; ?>:</label>	
		<div class="settingValue">
				<input name="use_trash" id="use_trash_yes" type="radio" <?php echo ($oCfg->use_trash == 'yes' ? ' checked' : '')?> value="yes">
				<label for="use_trash_yes"><?php echo $TEXT['ENABLED']; ?></label>
				<input name="use_trash" id="use_trash_no" type="radio" <?php echo ($oCfg->use_trash == 'no' ? ' checked' : '')?> value="no">
				<label for="use_trash_no"><?php echo $TEXT['DISABLED']; ?></label>
			<p class="settingHint"><?php echo $TOOL_TEXT['TRASH_INFO']; ?></p>
		</div>
	</div>
	<?php if(canModifyRestricted()): ?>			
	<div class="settingRow">
		<label class="settingName"><?php echo $TOOL_TEXT['CHECK_RESTRICTED']?>:</label>	
		<div class="settingValue">
				<input name="use_restrictions" id="restr_yes" type="radio" <?php echo ($oCfg->use_restrictions == 'yes' ? ' checked' : '')?> value="yes">
				<label for="restr_yes"><?php echo $TEXT['ENABLED']; ?></label>
				<input name="use_restrictions" id="restr_no" type="radio" <?php echo ($oCfg->use_restrictions == 'no' ? ' checked' : '')?> value="no">
				<label for="restr_no"><?php echo $TEXT['DISABLED']; ?></label>
			<p class="settingHint">
				<b><?php echo $TEXT['ENABLED']; ?>:</b> <?php echo $TOOL_TEXT['RESTRICTED_INFO']?><br>
				<b><?php echo $TEXT['DISABLED']; ?>:</b> <?php echo $TOOL_TEXT['UNRESTRICTED_INFO']?><br>
			</p>
		</div>
	</div>	
	<?php endif; ?>
	<!--
	<div class="settingRow">
		<label class="settingName">Use language Fallback:</label>	
		<div class="settingValue">
			<select id="language_fallback" name="language_fallback">
				<option disabled="disabled" value=""><?php echo $TEXT['PLEASE_SELECT']; ?></option>
				<option value="none"<?php echo ($oCfg->lang_fallback == 'none' ? ' selected' : '')?>>~~ <?php echo $TEXT['NONE']; ?> ~~</option>
				<?php foreach(getActiveLanguages() as $LC=>$lang):?>	
					<option value="<?php echo $LC ?>"<?php echo ($oCfg->lang_fallback == $LC ? ' selected' : '')?>>[<?php echo $LC ?>] <?php echo $lang ?></option>
				<?php endforeach ?>
			</select>
			<p class="settingHint">Dev. Info: Fallback funktioniert noch nicht</p>
		</div>
	</div>	
	-->
	<div class="settingRow">
		<label class="settingName">String-<?php echo $TEXT['TYPE']?>:</label>	
		<div class="settingValue">
			<div id="">
				<?php foreach($aPossibleStringTypes as $type): ?>
					<input type="checkbox" id="<?php echo $type ?>" name="fields[<?php echo $type ?>]" value="1" 
					<?php echo (in_array($type,$aStringTypes ) || $type == 'textarea') ? 'checked' : '' 
					?><?php echo ($type == 'textarea') ? ' disabled' : '' ?>/>
					<label for="<?php echo $type ?>"><?php echo ucfirst($type) ?></label>					
				<?php endforeach ?>
			</div>
		</div>
	</div>	
	<div class="buttonsRow">
		<span style="float:left;padding:6px;"><a href="<?php echo $toolUrl ?>">&laquo; <?php echo $TEXT['BACK']; ?></a></span>
		<input type="submit" name="save_config" value="<?php echo $TEXT['SAVE']; ?>" />
	</div>
</div>
</form>
<?php include __DIR__.'/foot.tpl.php'; ?>