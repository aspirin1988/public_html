<?php
session_start(); 
include "localconf.php";
$locationvar="adminpass";
$action_var="adminpass.php";
$actionmode="add";

$work_lang="EN";

$mode=get_post_var("mode");
if (!empty($_GET["mode"])) $mode=$_GET["mode"];


$messages=get_get_var("messages");


$password=get_post_var("password");

$confirm_password=get_post_var("confirm_password");

if ($mode=="update"){
	if ($password!=$confirm_password){
	header("Location:$action_var?messages=password and confirm password do not coincide");
	exit;
	}
	 
	if ($password!=""){
		$password=text_crypt($password);
		db_query("UPDATE $sql_tbl[users] SET password='$password'  WHERE login='$_SESSION[login]'");
		header("Location:$action_var?messages=пароль изменен");
		}else header("Location:$action_var?messages=введите новый пароль");
}
$title=$lng["lbl_adminpass"];
include "../tpl/admin/home.tpl";
?>