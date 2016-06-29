<script type="text/javascript" src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>

<div class="wrap">
	<?recur_show_tree($action_var,0,"submenub","");//&mode=edit?>
</div>
<style>

.icon
{
	height:250px;
	float:left;
	text-align:center;
	padding:10px; margin:3px;
	border:#cccccc 1px solid;
	border-radius:5px;
	background:#f2f4f6;
}	
.icon img,.icon textarea {border:#cccccc 3px solid;}
.div_clear
{
	clear: both;
}

</style>


<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class=mtd>
	
<?if (!empty($images)){?>
	<h4>Загруженные изображения</h4>
	<form method="post" action="" name="image_list">
	<input id="order_array" name="order_array" type="hidden" value="" />
	<input type="hidden" name="mode" value="update_all"/>
	<div id="imageList">
	<?foreach ($images as $a=>$b){
		echo'
		<div class="icon">
			<img width="200" src="'.$b['icon'].'"/>
			<br/><textarea name="posted_data['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
			<br/><input name="posted_data['.$b['id'].'][url]" type="text" value="'.$b['url'].'">
			<br/><input name="posted_data['.$b['id'].'][item]" type="checkbox">';
			if ($b['avail']=='Y') echo '<br/>скрыт';
		echo'</div>';
	}
	echo'<br style="clear: both" />';
	?>
	</div>
	<p align="right">
	Переместить в
	<?print_Select ($all_categories,$cat,"move_category","style='width:250px'");?>
	
	Действие &nbsp;
	<?print_Select ($action_array," ","action_mode");?>
	<input type=submit value="<?=$lng["lbl_update"]?>" class=btn>
	</p	>
</form>
<?}?>		
	

<br clear=all>
<br>
<b>
<?
	if ($actionmode=="add")	echo("$lng[lbl_add]");
	else echo("$lng[lbl_update] | <a href=$action_var class=red>$lng[lbl_add]</a>");
?>
</b>

<?if ($merror!="") echo("<font color=red><ul type=1>$merror</ul></font>");?>

<div id="uploader">	
<FORM ENCTYPE="multipart/form-data" action="" method=POST name=addImage>
<input type="hidden" name="mode" value="<?=$actionmode?>">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="cat" value="<?=$cat?>">
<table cellspacing="0" cellpadding="0" border="0" class=tblstyle>
<tr class=headerstyle>
	<td width=200></td><td>
	</td>
</tr>
<?if ($actionmode!="update"){
	for ($img=0;$img<1;$img++){?>
<tr class=headerstyle>
	<td><?=$lng["lbl_image"]?> </td><td>
	<input type="file" size="25" name="image<?=$img?>">
	</td>
</tr>

<tr class=headerstyle>
	<td><?=$lng["lbl_alt"]?></td><td>
	<textarea name="posted_data[alt<?=$img?>]"></textarea>
	</td>
</tr>
<tr><td height=5>&nbsp;</td></tr>
	<?}
	}else{?>
	<input type="hidden" name="posted_data[id]" value="<?=$posted_data["id"]?>">
	<tr valign=top>
	<td class=mtd>
	<?if ($posted_data["icon"]!=""){?>
		<a href="<?=$posted_data["image"]?>" target=_blank>
		<img src="<?=$posted_data["icon"]?>" class=apic alt="<?=$b["alt"]?>" border=0>
		</a>
	<?}?>
	<div>
	<br>Иконка<input type="file" size="25" name="icon">
	<br>Изображение <input type="file" size="25" name="image">
	</div>
	</td><td class=mtd nowrap>
	<?=$lng["lbl_alt"]?> <input type="text" style="width:90%" name="posted_data[alt]" value="<?=$posted_data["alt"]?>">
	</td>
</tr>
<?}?>
<tr>
	<td colspan=3 class=headerstyle align=right>
	<input type=submit value="<?=$lng["lbl_$actionmode"]?>" class=btn>
	</td>
</tr>
</table>
</div>
</FORM>

</td>
</tr>
</table>
<script>
	$("#imageList").sortable({
	    placeholder: "ui-state-highlight",
	    opacity: 0.6,
	    update: function(event, ui) {
			$("input#order_array").val($('#imageList').sortable('serialize'));
	    }
	});
</script>