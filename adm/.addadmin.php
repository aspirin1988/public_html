<?php
include "localconf.php";
$login="victor";
$ps="admin";
$ps=text_crypt($ps);
print "<pre>";

if (func_query_first_cell("SELECT count(*) FROM $sql_tbl[users] WHERE login='$login' AND usertype='A' AND password='$ps'")!=0)
	{
	print "пользователь $login существует";
	}else {
	db_query("INSERT INTO $sql_tbl[users] (login, password, usertype) VALUES ('$login','$ps','A')");
	$user="";
	$user=func_query_first("SELECT login, password FROM $sql_tbl[users] WHERE login='$login' AND usertype='A' AND password='$ps'");	
	if ($user!="") print_r($user);
	else print "(";
	
}
?>
