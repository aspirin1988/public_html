<script>
function showFilds(svar)
{
var idvar=document.getElementById(svar);


var ssvar=idvar.style.display;
if (ssvar=="none") idvar.style.display='block'; else idvar.style.display='none';
}
</script>
<h2>Отзывы</h2>
<div class="border_wrapp" ><div class="wrapp rb">	
<?if (is_array($all_comments)){?>
    <p align="center"><?
    $navigation_script="$action_var?news=all&";
	include "$root_dir/tpl/navigation.tpl";
	?></p>
<FORM action="<?=$action_var?>" method=POST name=dell_comments>
<input type="hidden" name="mode" value="update_comments">
<table cellpadding="0" cellspacing="0" border=0 width="100%">
<?foreach ($all_comments as $key=>$b){?> 
 <tr>
	<td class="headerstyle" style="padding:3px;text-align:left"><?=$lng["lbl_name"]?>: <?=$b["name"]?> 
	<?
	if ($b["mail"]!='') echo 'E-mail: '.$b["mail"];	else echo 'e-mail не указан';
	//echo ' Отзыв/Коментарий к: '.$b["type"];
	echo ' Дата: '.$b["date"];
	?>
 </tr>
 <tr valign="top">
	<td class=mtd bgcolor="#DEDEE2" style="padding:5px;">
		<textarea name="posted_data[<?=$b["id"]?>][text]" rows="5" cols="65" style="width:100%"><?=$b["text"]?></textarea>
	<p align="right"><a href='javascript:showFilds("show_<?=$b['id']?>");' class="btn rb">Ответить</a></p>
	<?if ($b["answer"]!='') echo '<span style="font: 12px arial">(отвечено)</span>';?>
	<div style='display: none;' id='show_<?=$b['id']?>'>
		<textarea name="posted_data[<?=$b["id"]?>][answer]" rows="5" cols="65" style="width:100%"><?=$b["answer"]?></textarea>
	</div>
	</td>
 </tr>
 <tr><td class="headerstyle" style="padding:3px;text-align:left">
 
 <?print_select($action,$b["status"],"posted_data[$b[id]][status]");?>
	<input type=hidden name="posted_data[<?=$b["id"]?>][id]" value="<?=$b["siteid"]?>">
	<input <?if ($b["hide"]=='Y') echo "checked"?> type=checkbox name="posted_data[<?=$b["id"]?>][hide]"> скрыть
	<input type=checkbox name="posted_data[<?=$b["id"]?>][del]"> удалить
 </td></tr>
 <tr>
	<td height="15"><img src="<?=$web_dir?>img/e.gif" width="1" height="15"></td>
 </tr>
<?}?>
 <tr>
	<td align=right colspan=2>
	<input type=submit class="btn rb" value=<?=$lng["lbl_update"]?>>
	<input type="button" class="btn rb" value="<?=$lng["lbl_delete"]?>"
onclick="document.dell_comments.mode.value='dellcomments';document.dell_comments.submit();">
	
 	</FORM>
	</td>
 </tr>
</table>
<br>
<?}?>
	