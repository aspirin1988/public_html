<FORM action=languages.php method=POST name=language_form>
<input type="hidden" name="mode" value="selectlanguage">
<br>
<table cellpadding="0" cellspacing="0" border=0 width="100%">
 <tr>
	<td width="10" bordercolor="#56AB62" rowspan=2><img src="<?=$web_dir?>img/e.gif" width="10" height="1" alt=":)"></td>
	<td width="15" height="28"><img src="<?=$web_dir?>img/menu_bar.gif" width="15" height="28" alt=":)"></td>
	<td background="<?=$web_dir?>img/bgmenu_bar.gif" class=uphead>
	<?=$lng["lbl_edit_language"];?>
	</td>
	<td width="10" bordercolor="#56AB62" rowspan=2><img src="<?=$web_dir?>img/e.gif" width="10" height="1" alt=":)"></td>
 </tr>
 <tr>
	<td bgcolor="#ffffff"></td>
	<td bgcolor="#ffffff" class=mtd>
<table cellpadding="0" cellspacing="0" width="100%" border=1 bordercolor="#27632C" bgcolor="#ffffff">
 <tr bgcolor="#CCCCCC" align=center>
	<TD width="200" class=headerstyle><?=$lng["lbl_languages"]?></TD>
	<TD class=headerstyle><?=$lng["lbl_type"]?></TD>
	<TD class=headerstyle><?=$lng["lbl_apply_filter"];?></TD>
 </tr>
 <tr align=center>
 	<td><SELECT name="language">
		<OPTION value=""<? if ($language=="") print "selected"; print ">".$lng["lbl_select_one"];?></OPTION>
<?
foreach ($languages as $a=>$b){
?>
	<OPTION value="<?=$b["code"];?>" <?if ($language==$b["code"]) print "selected";?>><?=$b["value"];?></OPTION>
<?}?>
	</SELECT>
	</td>
	<td>
	<SELECT name="topic">
	<?foreach ($topics as $a=>$b){?>
	<OPTION value="<?=$b?>" <?if ($topic==$b) print "selected";?>><?=$b?></OPTION>
	<?}?>
	</SELECT>
	</td>
	<td><INPUT type="text" size="16" name="filter" value="<?=$filter?>"></td>	
 </tr>
 <tr>
 	<td colspan=3 align=right><INPUT type="submit" value="<?=$lng["lbl_go"]?>"></td>
 </tr>
</table> 
</FORM>
    <?
    $navigation_script="$action_var?language=$language&";
	include "$root_dir/tpl/navigation.tpl";
	?>
<FORM action=languages.php method=POST name=processlang>
<input type="hidden" name="mode" value="update">
<INPUT type="hidden" name="first_page" value="<?=$first_page?>">
<INPUT type="hidden" name="filter" value="<?=$filter?>">
<INPUT type="hidden" name="language" value="<?=$language?>">
<table cellpadding="0" cellspacing="0" width="100%" border=1 bordercolor="#27632C" bgcolor="#ffffff">
 <tr bgcolor="#CCCCCC">
	<TD width="200" class=headerstyle><?=$lng["lbl_variable"]?></TD>
	<TD class=headerstyle><?=$lng["lbl_value"]?></TD>
	<TD class=headerstyle><?=$lng["lbl_delete"]?></TD>
 </tr>
 <tr>
 <?
 if (is_array($edit_lang)) 
 {
 foreach ($edit_lang as $key=>$l) 
 {
 ?>
 <tr valign=top>
	<td class=tdtext>
	<input type='hidden' name="posted_data[<?=$l["name"]?>][name]" value='<?=$l["name"]?>'>
	<?=$l["name"];?>
	</td>
	<td align=center>
	<TEXTAREA style="width:100%" cols="35" rows="6" name="posted_data[<?=$l["name"]?>][value]"><?=$l["value"];?></TEXTAREA>
	</td>
	<td width="60" align=center><input type=checkbox name='posted_data[<?=$l["name"]?>][del]'></td>
</tr>
<?	
}
?>
<tr>
	<td align=right colspan=3>
	<input type=submit value="<?=$lng["lbl_update"]?>"><input type="button" value="<?=$lng["lbl_delete"]?>" onclick="document.processlang.mode.value='dell';document.processlang.submit();">&nbsp;
	</td>
</tr>
<?} else {?>
<tr>
	<td align=center colspan=3>
	***********
	</td>
</tr>
<?	
}
?>
</table>
</form>
<br>
<b><?=$lng["lbl_add"]?></b>
<FORM action=languages.php method=POST name=addlangvalue>
<input type="hidden" name="mode" value="add">
<INPUT type="hidden" name="language" value="<?=$language?>">
<INPUT type="hidden" name="topic" value="<?=$topic?>">
<table width="100%" cellpadding="0" cellspacing="0" border=1 bordercolor="#27632C" bgcolor="#ffffff">
 <tr bgcolor="#CCCCCC">
	<TD width=200 class=headerstyle><?=$lng["lbl_variable"]?></TD>
	<TD class=headerstyle><?=$lng["lbl_value"]?></TD>
	<TD width=100 class=headerstyle><?=$lng["lbl_type"]?></TD>
 </tr>
 <tr>
 <tr valign=center>
	<td class=tdtext>&nbsp;<input type="text" name="posted_data[name]" value="">&nbsp;&nbsp;</td>
	<td align=center>
	<TEXTAREA style="width:100%" cols="35" rows="6" name="posted_data[value]"></TEXTAREA>
	</td>
	<td align=center><SELECT name="topic">
	<?foreach ($topics as $a=>$b){?>
	<OPTION value="<?=$b?>" <?if ($topic==$b) print "selected";?>><?=$b?></OPTION>
	<?}?></SELECT></td>
 </tr>
 <tr>	
	<td colspan=3 align=right><input type=submit value="<?=$lng["lbl_add"]?>">&nbsp;</td>
 </tr>
</table>
</form>
</td>
 </tr>
</table>
<br>
&nbsp;