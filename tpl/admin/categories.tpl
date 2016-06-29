	<?if (is_array($all_categories)){?>
	<h2>Разделы</h2>
	<div class="border_wrapp" ><div class="wrapp rb">	
		<FORM action=<?=$action_var?> method=POST name=addcategory>
		<input type="hidden" name="mode" value="dell">
		<?Edit_Tree(0,"submenub","mode=edit",$sqltbl);?>
		<input type=submit class="btn rb" value='<?=$lng["lbl_update"]?>'>
	</FORM>
	</div></div>
	<?}?>
	
	<h2>
	<?
	if ($actionmode=="add")	echo("$lng[lbl_add]");
	else echo("$lng[lbl_update] | <a href=$action_var>$lng[lbl_add]</a>");
	?>
	</h2>
	<div class="border_wrapp" ><div class="wrapp rb">	
	<FORM action="<?=$action_var?>" method=POST name=addcategory>
	<input type="hidden" name="mode" value="<?=$actionmode?>">
	<input type="hidden" name="posted_data[categoryid]" value="<?=$posted_data["categoryid"]?>">
	<table cellpadding="0" cellspacing="0" class=stbl width="100%">
 	<tr>
		<td class=headerstyle width=50%><?=$lng["lbl_category"]?></td>
		<td class=headerstyle width=50%><?=$lng["lbl_add"]?>...</td>
		<td class=headerstyle width=150><?=$lng["lbl_hide"]?></td>
 	</tr>
	<tr>
		<td><input type=text name="posted_data[category]" value="<?=$posted_data["category"]?>" style="width:100%"></td>
		<td>
		<?
		print_select($all_categories,$posted_data["parentid"],"posted_data[parent]");
		?>
		</td>
	<td><input type="checkbox" name="posted_data[avail]" <?if ($posted_data["avail"]=="Y") echo("checked");?>   >
	</td>
 </tr>
 <tr>
 	<td	colspan=4 class=mtd>
 	<p><b><?=$lng["lbl_link"]?></b><br>
 	<input type='text' style="width:100%" name="posted_data[location]" value="<?=$posted_data["location"]?>"/>
 	</td>
 </tr>
 <tr>
 	<td	colspan=4 class=mtd>
 	<p><b><?=$lng["lbl_description"]?></b><br>
 	<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_data[description]"><?=$posted_data["description"]?></TEXTAREA>
 	</td>
 </tr>
 <tr>
 	<td	colspan=4 class=headerstyle>SEO:</td>
 </tr>
 <tr>
 	<td	colspan=4 class=mtd>	
 	<p><b><?=$lng["lbl_title"]?></b><br>
 	<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_data[title]"><?=$posted_data["title"]?></TEXTAREA>
 	<p><b><?=$lng["lbl_meta_description"]?></b><br>
 	<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_data[meta_descr]"><?=$posted_data["meta_descr"]?></TEXTAREA>
 	<p><b><?=$lng["lbl_meta_keywords"]?></b><br>
 	<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_data[meta_keywords]"><?=$posted_data["meta_keywords"]?></TEXTAREA>
 	<p><b><?=$lng["lbl_alt"]?></b><br>
 	<input type=text name="posted_data[alt]" value="<?=$posted_data["alt"]?>" style="width:100%">
 	</td>
 </tr>
<?if ($mode=='edit'){?>
	<tr>
		<td	colspan=4 class=mtd>
			<input type="checkbox" name="posted_data[subcategories]" <?if ($posted_data["subcategories"]=="Y") echo("checked");?> > Показывать подразделы
			<input type="checkbox" name="posted_data[showicon]" <?if ($posted_data["showicon"]=="Y") echo("checked");?> > Показывать иконки
		</td>
	</tr>
<?}?>
</table>
<p align="center"><input type=submit class="btn rb" value='<?if ($actionmode=="add") echo($lng["lbl_add"]);else echo($lng["lbl_update"]);?>'>	
</FORM>
</div></div>