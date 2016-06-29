<form action="login.php" method="post" name="authform">
	<?
	if ($access){?><br/><br/>
		<div class="border_wrapp">
		<div class="wrapp rb" style="text-align:center">
		<?
		echo ("<h2>".$_SESSION["login"]."</h2>");
		echo $lng["lbl_logged_in"];
		?>
		<input type="hidden" name="mode" value="logout">
		<input type="hidden" name="redirect" value="<?=$_SERVER["REQUEST_URI"]?>">
		<br/><br/><input type="submit" class="btn rb" name="enter" value='<?=$lng["lbl_logoff"]?>'>
		</div></div>
	<?}else{?>
		<div id="login" class="rb login1">
		<div class="orangebg rb">
			<?=$lng["lbl_username"]?><BR>
			<input type="text" name="username" size="16" value=""><BR>
			<?=$lng["lbl_password"]?><BR>
			<input type="password" name="password" size="16" value="">
			<input type="hidden" name="mode" value="login">
			<input type="hidden" name="redirect" value="<?=$_SERVER["REQUEST_URI"]?>">
			<p class="btn rb"><input type="submit" name="enter" class="btn rb" value="<?=$lng["lbl_log_in"]?>"></p>
		</div>
		</div>
	<?}?>
</form>