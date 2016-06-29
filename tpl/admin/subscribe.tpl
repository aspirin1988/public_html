<?if (!empty($usersmail)){?>
<FORM action="<?=$action_var?>" method="POST" name="dellhistory" class="wrap">

<center>
<?$navigation_script="$action_var?";
	include "$root_dir/tpl/navigation.tpl";
?>
</center>


<input type="hidden" name=mode value="dell_from_history">
<table cellpadding="0" cellspacing="0" border=0 width="100%">
<tr>
	<td class="headerstyle">E-mail</td>
	<td class="headerstyle">Город</td>
	<td class="headerstyle">Дата</td>
	<td width="150" class="headerstyle">Удалить</td>
</tr>
<?foreach ($usersmail as $a=>$b){?>
<tr>
	<td class="mtd"><?=$b['email']?></td>
	<td class="mtd"><?=$b['town']?></td>
	<td class="mtd"><?=strftime($config["timeformat"],$b["data"])?></td>
	<td class="mtd"><input type="checkbox" name='posted_data[<?=$b["id"]?>][del]'></td>
</tr>
<?}?>
<tr>
	<td colspan="4" align="right">
		<input class=btn type=submit value='удалить отмеченные'>&nbsp;
	</td>
</tr>
</form>
</table>
<h2>Экспортировать в Excel</h2>
<form action="" method="POST" name="Excelimport" class="wrap">
<input type="hidden" name="mode" value="excel"/>
<table cellpadding="0" cellspacing="0" border=0>
	<tr>
		<td class="headerstyle">Ипортировать формы:</td>
		<td class="mtd"><?print_select($eimport,$etype,'etype')?></td>
	</tr>
	<tr>
		<td class="headerstyle">Оповещения о скидках и акциях:</td>
		<td class="mtd">
			<input name="email-or-phone" class="r-inp" value="1" type="radio"/>по SMS
			<input name="email-or-phone" class="r-inp" value="2" type="radio"/>по E-MAIL
			<input name="email-or-phone" class="r-inp" value="" type="radio"/>Не указано
		</td>	
	</tr>
	<tr>
		<td>
			<input class=btn type=submit value='импорт'>&nbsp;
		</td>
	</tr>
	
</table>


</form>
<?}?>

<?/*
<table cellpadding="0" cellspacing="0" border=0 width="100%">
<tr bgcolor="#ffffff">
	<td class=mtd>
	<br>
	<br>
	<p align=center>
	Колличество подписчиков:<?=$user_count?>
	<div align="center" style="color:green"><?=$message?></div>
	<FORM action="<?=$action_var?>" method="POST" name="process">
	<input type="hidden" name=mode value="send_to_mail">
	<div style="margin:20px;padding:10px;BACKGROUND:#eeeeee;">
	Текст рассылки:<br>
		<div style="margin:3px;padding:10px;BACKGROUND:#ddd;"><?=$text?>
		</div>
		E-mail для проверки <input class=btn type=text name="mailcheck" value='<?=$config['email']?>'>&nbsp;
		<input onclick="document.process.mode.value='sendmymaeil';document.process.submit();" class=btn type=button value='Отправить себе на email'>&nbsp;
		<input class=btn type=submit value='Отправить адресатам'>&nbsp;
	</div>
	
	
	
	</FORM>
	</td>
</tr>
</table>

<FORM action="<?=$action_var?>" method=POST name=add>
<input type="hidden" name="mode" value="update_text">
	<div style="margin:20px;padding:10px;BACKGROUND:#eeeeee;">
	Редактировать текст:
	<?
 	include("../w_editor/fckeditor.php");
 	$sBasePath = $_SERVER['PHP_SELF'];
 	$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "_samples" ) );
 	$oFCKeditor = new FCKeditor('text');
 	$oFCKeditor->Width="100%";
 	$oFCKeditor->Height="600";
  	$oFCKeditor->ToolbarSet	= 'Default';
 	$oFCKeditor->BasePath = '/w_editor/';
 	$oFCKeditor->Value		= $text;
 	$oFCKeditor->Create() ;
 	?>
	<br><input class=btn type=submit value='Обновить'>&nbsp;
	</div>	
	</FORM>
	
	</td>
 </tr>
</table>
*/?>