<h2>Сменить пароль</h2>
	<div class="border_wrapp rb">
		<div class="wrapp rb">
		<?
			if (!empty($messages)) echo '<div class="okmessage rb">'.$messages.'</div>';
		?>
	<FORM action='<?=$action_var?>' method=POST name=updatepassword>
	<input type="hidden" name="mode" value="update">
	<table cellpadding="0" cellspacing="0" border=0 width="100%">
	<tr>
	<td width=200 class=headerstyle><?=$lng["lbl_login"]?></td>
	<td  class=mtd><?=$_SESSION["login"]?></td>
	</tr>
	<tr>
	<td class=headerstyle><?=$lng["lbl_password"]?></td>
	<td  class=mtd>
	<input type='password' name="password" value='' style="width:200">
	</td>
	</tr>
	<tr>
	<td  class=headerstyle><?=$lng["lbl_confirm_password"]?></td>
	<td  class=mtd>
	<input type='password' name="confirm_password" value='' style="width:200">
	</td>
	</tr>
	</table>
	<p align="center"><input class="btn rb" type=submit value='<?=$lng["lbl_update"]?>'></p>
	</FORM>
	</div>
</div>