<FORM action="<?=$action_var?>" method="POST" name="asl_form">
	<table cellpadding="0" cellspacing="0" border=0 width="100%" bgcolor="#EAE9EF">
	<tr bgcolor="#27632C">
		<td height="30" class=upmenu><?=$title?></td>
		<td height="30" class=upmenu align=right>
			<B><?=$lng["lbl_current_language"]?>:</B>&nbsp;
			<SELECT name="site_language" onChange='javascript: document.asl_form.submit();'>
			<?foreach ($languages as $a=>$b){?>
			<OPTION value="<?=$b["code"];?>" <?if ($site_language==$b["code"]) print "selected";?>><?=$b["value"];?></OPTION>
			<?}?>
			</SELECT>&nbsp;
			</td>
	</tr>
	</table>
</FORM>