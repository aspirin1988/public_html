<table cellpadding="0" cellspacing="0" border=0 width="100%">
<tr bgcolor="#ffffff">
	<td class=mtd>
	<center>
	<?if (is_array($users)){
	$navigation_script="$action_var?";
	include "$root_dir/tpl/navigation.tpl";
	?>
	</center>
	<FORM action=<?=$action_var?> method=POST name=processcategory>
	<input type="hidden" name=mode value="update_group">
	<table cellpadding="0" cellspacing="0" width="100%" class=stbl>
 	<tr align=center>
	<td width=10 class=headerstyle>#</td>
	<td width=100% class=headerstyle>пользователи</td>
	<td width=200 class=headerstyle nowrap>последний раз был</td>
	<td width=100 class=headerstyle nowrap>зарегистрирован</td>
	<td width=100 class=headerstyle nowrap>удалить</td>
 	</tr>
<?foreach ($users as $i => $l){?>
<tr valign=top>
	<input type='hidden' name="posted_data[<?=$i?>][login]" value='<?=$l["id"]?>'>
	<td class=mtd><?=$i+1?></td>
	<td class=mtd>
	<?if ($login!=$l["email"]){?>
	<a href="<?=$action_var?>?id=<?=$l["id"]?>&mode=edit&page=<?=$page?>">
	<b><?=$l["fio"]?></b>
	</a>
	<?}else{?>
	<b><?=$l["fio"]?></b>
	<?}?>
	<br>
	<?=$l["lastname"]?>
	<?=$l["firstname"]?>
	<?=$l["p_name"]?>
	</td>
	<td class=mtd><?=$l["last_login"]?></td>
	<td class=mtd><?=$l["first_login"]?></td>
	<td class=mtd width="60" align=center><input type=checkbox name='posted_data[<?=$l["id"]?>][del]'></td>
</tr>
<?	
}
?>
<tr>	
	<td align=right colspan=5 bgcolor="#ffffff">
	<input class=btn type=submit value='<?=$lng["lbl_update"]?>'>&nbsp;
	</td>
</tr>
</table>
</FORM>
<?}?>
	<br><b>
	<?
	if ($actionmode=="add")	echo("$lng[lbl_add]");
	else echo("$lng[lbl_update] | <a href=$action_var>$lng[lbl_add]</a>");
	?>
	</b>
	<?
	if ($merror!="") echo "<ul>$merror</ul>";
	?>
	<br>
	<br>
	<FORM ENCTYPE="multipart/form-data" action=<?=$action_var?> method=POST name=add>
	<input type="hidden" name="mode" value="<?=$actionmode?>">
	<input type="hidden" name="id" value="<?=$id?>">
	<table cellpadding="0" cellspacing="0" width="600"  class=tblstyle>
	<?if ($actionmode!="update"){?>
	<tr>
		<td class=headerstyle><?=$lng["lbl_email"]?></td>
		<td class=mtd>
		<INPUT class=inp type="text" name="posted_data[email]" style="width:300" value="<?=$posted_data["email"]?>">
		<?print_marker($posted_data["email"])?>
		</td>
	</tr>
	<tr>
		<td class=headerstyle><?=$lng["lbl_last_name"]?>: </td>
		<td class=mtd>
		<INPUT class=inp type="text" name="posted_data[lastname]" style="width:300" value="<?=$posted_data["lastname"]?>">
		<?print_marker($posted_data["lastname"])?>
		</td>
	</tr>
	<tr>
		<td class=headerstyle><?=$lng["lbl_first_name"]?>: </td>
		<td class=mtd>
		<INPUT class=inp type="text" name="posted_data[firstname]" style="width:300" value="<?=$posted_data["firstname"]?>">
		<?print_marker($posted_data["firstname"])?>
		</td>
	</tr>
	<tr>
		<td class=headerstyle><?=$lng["lbl_patronymic_name"]?>: </td>
		<td class=mtd>
		<INPUT class=inp type="text" name="posted_data[p_name]" style="width:300" value="<?=$posted_data["p_name"]?>">
		<?print_marker($posted_data["p_name"])?>
		</td>
	</tr>
	<tr>
		<td class=headerstyle><?=$lng["lbl_password"]?></td>
		<td class=mtd>
		<INPUT class=inp type="text" name="posted_data[password]" style="width:300" value="<?=$posted_data["password"]?>">
		<?print_marker($posted_data["password"])?>
		</td>
	</tr>
	<tr>
		<td class=headerstyle><?=$lng["lbl_once_again"]?></td>
		<td class=mtd>
		<INPUT class=inp type="text" name="posted_data[password_again]" style="width:300" value="<?=$posted_data["password_again"]?>">
		<?print_marker($posted_data["password_again"])?>
		</td>
	</tr>
	<?}else{?>
	<tr>
		<td class=mtd><?=$lng["lbl_email"]?></td>
		<td class=mtd><?=$posted_data["email"]?></td>
	</tr>
	
<tr>
	<td class=mtd><?=$lng["lbl_last_name"]?>: </td>
	<td class=mtd><INPUT class=inp type="text" name="posted_data[lastname]" style="width:300" value="<?=$posted_data["lastname"]?>"></td>
</tr>
<tr>
	<td class=mtd><?=$lng["lbl_first_name"]?>: </td>
	<td class=mtd><INPUT class=inp type="text" name="posted_data[firstname]" style="width:300" value="<?=$posted_data["firstname"]?>"></td>
</tr>
<tr>
	<td class=mtd><?=$lng["lbl_patronymic_name"]?>: </td>
	<td class=mtd><INPUT class=inp type="text" name="posted_data[p_name]" style="width:300" value="<?=$posted_data["p_name"]?>"></td>
</tr>
<tr>
	<td class=mtd><?=$lng["lbl_telephone"]?>: </td>
	<td class=mtd><INPUT class=inp type="text" name="posted_data[telephone]" style="width:300" value="<?=$posted_data["telephone"]?>"></td>
</tr>
<tr>
	<td class=mtd>Город:</td>
	<td class=mtd><INPUT class=inp type="text" name="posted_data[town]" style="width:300" value="<?=$posted_data["town"]?>"></td>
</tr>
<tr>
	<td class=mtd>Статус</td>
	<td class=mtd><?print_select($status,$posted_data["status"],"posted_data[status]");?></td>
</tr>
<tr>
	<td class=mtd>Новый пароль</td>
	<td class=mtd><INPUT class=inp type="text" name="posted_data[password]" value=""></td>
</tr>
<tr>
	<td class=mtd>Повтор нового пароля</td>
	<td class=mtd><INPUT class=inp type="text" name="posted_data[kpassword]" value=""></td>
</tr>
<tr>
	<td colspan=2><hr size=1></td>
</tr>
<tr>
		<td class=mtd>Индекс</td>
		<td class=mtd><input type="text" name="posted_data[index]" style="width:300" value="<?=$posted_data['index']?>"/></td>
	</tr>
	
	<tr>
		<td class=mtd>Адрес</td>
		<td class=mtd>
			<textarea rows="3" cols="14" style="width:300" name="posted_data[address]"><?=$posted_data['address']?></textarea>
		</td>
	</tr>
	<tr>
		<td class=mtd>Организация</td>
		<td class=mtd>
			<textarea style="width:300" rows="3" cols="14" name="posted_data[organization]"><?=$posted_data['organization']?></textarea>
		</td>
	</tr>
	<tr>
		<td class=mtd>Уведомить на e-mail</td>
		<td class=mtd>
			<INPUT type="checkbox" name="posted_data[mailsend]">
			<?if ($posted_data["mailsend"]=="Y") echo("(уже было уведомлено)");?>
		</td>
	</tr>
	
<?}?>
	<tr>
 		<td colspan=2 style="text-indent:10px"><input type=submit class=btn value='<?if ($actionmode=="add")	echo("$lng[lbl_add]");else echo("$lng[lbl_update]");?>'>
 	</td>
</tr>

</table>	
</FORM>
	</td>
 </tr>
</table>