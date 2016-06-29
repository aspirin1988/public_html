<?if (is_array($setup)){?>
<br>
<FORM action=settings.php method=POST name=setupform>
<input type="hidden" name="mode" value="update">
<table cellpadding="0" cellspacing="0" border=0 width="100%" bgcolor="#ffffff">
<tr><td align=center>
<table cellpadding="0" cellspacing="0" border=0 bgcolor="#ffffff">
 <?foreach ($setup as $a=>$b){?>
 <tr>
	<td align=right width="50%" class="headerstyle"><?=$b["comment"]?></td>
	<td class=mtd>
	<?
	
	switch ($b["name"]){
		case "lives":print_select($lives,$b["value"],"post_date[".$b["name"]."]");break;
		case "trp":print_select($trp,$b["value"],"post_date[".$b["name"]."]");break;
		case "scripture":print_select($scripture,$b["value"],"post_date[".$b["name"]."]");break;	
	default:{
	if ($b["name"]=="sort")
		print_select($sort,$b["value"],"post_date[".$b["name"]."]");
	elseif ($b["name"]=="amount_comment")
		print_select($amount_comment,$b["value"],"post_date[".$b["name"]."]");
	elseif ($b["type"]=="text"){
	?><INPUT type="text" name="post_date[<?=$b["name"]?>]" value="<?=$b["value"]?>">
	<?}elseif ($b["type"]=="textarea"){?>
	<TEXTAREA style="width:100%" cols="35" rows="6" name="post_date[<?=$b["name"]?>]"><?=$b["value"]?></TEXTAREA>	
	<?}elseif ($b["type"]=="checkbox"){
	print_select($checkbox,$b["value"],"post_date[".$b["name"]."]");
	}
	}break;
	}?>
	</td>
 </tr>
 <?}?>
 <tr><td align="center" colspan=2><br><INPUT type="submit" class="btn rb" value="<?=$lng["lbl_update"]?>"></td></tr>
</table>
</td></tr>
</table>
</FORM>
<?}?>