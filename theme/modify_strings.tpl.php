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
		<form name="order_by_form" action="<?php echo $toolUrl?>&pos=modify_strings" method="post">
			<?php echo $admin->getFTAN(); ?><?php echo $TOOL_TEXT['ORDER_BY']; ?>: 
			<select name="order_by">
				<?php 
				foreach(getOrderByArray() as $opt): ?>
				<option<?php echo ($opt['sel'] == 1) ? ' selected="selected"' : '' ?> value="<?php echo $opt['name']?>">
					<?php echo $TOOL_TEXT[$opt['name']]; ?>
				</option>
				<?php endforeach; ?>
			</select><input type="submit" name="change_order_by" value="go!">
		</form>
		-->
		<table id="myTable" class="cell-border compact stripe">		
		<thead>
			<tr>
				
				<th><?php echo $TOOL_TEXT['NAME']; ?></th>				
				<th><?php echo $TOOL_TEXT['TYPE']; ?></th>	
				<th><?php echo $TOOL_TEXT['DATE_CREATED']; ?></th>
				<th><?php echo $TOOL_TEXT['DATE_MODIFIED']; ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
				foreach($aAllFields as $id=>$field):			
				$sRestriction = ($field['restricted'] == 0 ? 'un' : '').'restricted';
				$sTrashed = (isTrashedEntity($id) ? '' : 'not_').'trashed';
			?>	
			<tr>
				<td>
				<?php if(canModifyRestricted()): ?>
					<img src="<?php echo $icons?>/<?php echo $sRestriction?>.png" alt="[<?php echo $sRestriction?>]" title="<?php echo $TOOL_TEXT[strtoupper($sRestriction).'_INFO']; ?>">
				<?php else: ?>
					<img src="<?php echo $icons?>/<?php echo $sRestriction?>.png" alt="" title="GlobalString">
				<?php endif; ?>
				
					<span  title="<?php echo $TEXT['MODIFY']?>" style="<?php echo ($sTrashed == 'trashed') ? "text-decoration: line-through;" : '' ?>">
						<a href="#a<?php echo $id?>"><?php echo $field['name']?></a>
					</span>
					<?php if($sTrashed == 'trashed'): ?>
						<img src="<?php echo $icons?>/trash_16.png" alt="" title="<?php echo $TEXT['DELETED']; ?>">
					<?php endif; ?>
				</td>
				<td>
					<?php echo $field['type']?>
				</td>
				<td>					
					<?php echo date (DATE_FORMAT.' '.TIME_FORMAT,$field['add_when']+TIMEZONE);?>
				</td>
				<td>
					<?php if ($field['edit_when']!=0) {
					echo date (DATE_FORMAT.' '.TIME_FORMAT,$field['edit_when']+TIMEZONE);
					}
					?>
				</td>
			</tr>
			<?php endforeach; ?> 
			</tbody>
		</table>
		<link rel="stylesheet" type="text/css" href="<?php echo WB_URL ?>/modules/global_strings/js/datatables.min.css" />
		<script type="text/javascript" src="<?php echo WB_URL ?>/modules/global_strings/js/datatables.min.js"></script>

		<script>
		$(document).ready( function () {
			$('#myTable').DataTable({
				"pageLength": 5
			});
		} );
		</script>
		<?php else: ?>
		<div class="be_infobox">Es sind noch keine Strings angelegt worden.</div>
		<?php endif; ?>
</div>



<div class="be_settings be_half">
	<form name="add_field" action="<?php echo $toolUrl?>&pos=modify_strings" method="post" autocomplete="off">
		<?php echo $admin->getFTAN(); ?>
		<div class="headingRow"><?php echo $TEXT['ADD']; ?> GlobalString</div>						
		<div class="settingRow">
			<label class="settingName">String-<?php echo $TEXT['TYPE']?>:</label>
			<div class="settingValue">
				<select id="field_type" name="field_type">
					<?php if(count($aStringTypes) > 1) : ?>
					<option disabled="disabled" selected="selected" value="" style="font-weight:bold;"><?php echo $TEXT['PLEASE_SELECT']; ?> . . .</option>					
					<?php endif; ?>	
					<?php foreach($aStringTypes as $type): ?>	
						<option value="<?php echo $type ?>"><?php echo $type ?></option>
					<?php endforeach ?>
				</select>
			</div>
		</div>	
		<div class="settingRow">
			<label class="settingName">Field-Name:</label>
			<div class="settingValue"><input type="text" name="field_name" value="<?php echo (isset($sFieldName) && $sFieldName != '' ? $sFieldName : '')?>">
			<p class="formHint"><?php echo $TOOL_TEXT['NAME_WRONG_REGEX']; ?></p>
			</div>
		</div>
		<div class="buttonsRow"><input type="submit" name="add_new_field" value="<?php echo $TEXT['SAVE']?>"></div>
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
<a name="a<?php echo $id?>" class="anchor"></a>
<form id="fields_<?php echo $id?>" name="update_field" action="<?php echo $toolUrl?>&pos=modify_strings" method="post" autocomplete="off">
	<?php echo $admin->getFTAN(); ?>
	<input type="hidden" name="field_id" value="<?php echo $id ?>">
<div class="be_settings<?php echo $sHilite;?><?php if(isTrashedEntity($id)): ?> stringDeleted<?php endif ?>">
	<div class="headingRow heading<?php echo ucfirst($sRestriction);?>">

	<img src="<?php echo $icons?>/<?php echo $sRestriction?>.png" alt="[<?php echo $sRestriction?>]" title="<?php echo $TOOL_TEXT[strtoupper($sRestriction).'_INFO']; ?>">
	"<?php echo $field['name']?>"
		<input type="text" id="droplet_<?php echo $id?>" style="width:350px" value="[[string?name=<?php echo $field['name']?>]]">
		<img class="clipper" title="copy to clipboard" data-clipboard-target="#droplet_<?php echo $id?>" src="<?php echo $icons?>/clippy.png" alt="Copy to clipboard">
		<span style="float:right; font-size:11px;">
			<a title="<?php echo $TEXT['DELETE']; ?> string: '<?php echo $field['name']; ?>' " href="<?php echo $toolUrl ?>&func=delete_field&field_id=<?php echo $fieldIDKey ?>" class="<?php echo (isTrashedEntity($id) || $oCfg->use_trash == 'no') ? 'delete' : 'trash' ?>">		
				<img src="<?php echo $icons?>/delete_16.png" alt="<?php echo $TEXT['DELETE']; ?>">
			</a>
			<?php if(isTrashedEntity($id)): ?>&nbsp;
			<a href="<?php echo $toolUrl ?>&func=restore_field&field_id=<?php echo $fieldIDKey ?>" title="<?php echo $TEXT['RESTORE']; ?> <?php echo $TOOL_TEXT['STRING']; ?>">		
				<img  src="<?php echo $icons?>/restore_16.png" alt="<?php echo $TEXT['RESTORE']; ?>">
			</a>
	<?php endif ?>
		</span>
	</div>
	<?php 
		foreach($field['content'] as $lang=>$value): 
			if(isTrashedEntity($id) == false) :		
	?>	
		<div class="singleRow<?php echo (isTrashedEntity($id) ? ' trashed' : '')?>">
			<div>
			<span title="unique=<?php echo $value['unique_id']?>">
				<img src="<?php echo WB_URL ?>/languages/<?php echo strtoupper($lang) ?>.png"> <b><?php echo $lang?></b>
			</span>
			<span style="float:right; font-size:11px;"></span>
				<!-- <div>
					<input type="text" id="droplet_u<?php echo $value['unique_id']?>" style="width:350px" value="[[field?unique=<?php echo $value['unique_id']?>]]">
					<img class="clipper" title="copy to clipboard" data-clipboard-target="#droplet_u<?php echo $value['unique_id']?>" src="<?php echo $icons?>/clippy.png" alt="Copy to clipboard">
				</div> -->
			</div>
			
			<div>			
			<?php echo draw_field(
				$field['type'], 
				$lang,  
				$id, 
				$value['unique_id'], 
				$value['content']
				); ?>
			</div>
		</div>
		<?php else: ?>
		&nbsp;&nbsp;<img src="<?php echo WB_URL ?>/languages/<?php echo strtoupper($lang) ?>.png"> <b><?php echo $lang?></b> UniqueID: <?php echo $value['unique_id']?>
		<?php endif; 
		endforeach;  
	?>
	<?php if($sTrashed != 'trashed'): ?>
	<div class="buttonsRow">
		<?php if(canModifyRestricted() && (getStringsCfg()->use_restrictions == 'yes')) : ?>
			<span style="float:left">
				<input type="checkbox" name="restricted" id="restr<?php echo $id?>" value="1"<?php echo ($field['restricted'] == 1) ? ' checked="checked"' : '' ?>>
				<label for="restr<?php echo $id?>"><?php echo $TOOL_TEXT['CHECK_RESTRICTED']?></label>
			</span>
			<?php endif ?>
		<input type="submit" name="save_field_changes" value="<?php echo $TEXT['SAVE']?>">
	</div>
	<?php endif; ?>
</div>
</form>
<?php endforeach; 
endif; // if(!empty($aAllFields)): 
?>
<?php include __DIR__.'/foot.tpl.php'; ?>