<?php
session_start();
if (!empty($_POST["usertype"]))	$usertype=$_POST["usertype"];
		else $usertype="";
require "../config.php";

$merchant_password = "";
$login_error = false;

if (!empty($_POST["mode"]))	$mode=$_POST["mode"];
if (empty($mode)) $mode = "";

if (!empty($_POST["redirect"]))	$redirect=$_POST["redirect"];else $redirect="/admin/home.php";

$logged=0;

if (!empty($_POST["username"]))	$username=$_POST["username"]; else $username="";

if (!empty($_POST["password"]))	$password=$_POST["password"]; else $password="";

switch ($mode) {
case "login":{
	$user_data = func_query_first("SELECT * FROM $sql_tbl[users] WHERE login='$username' AND usertype='$usertype'");// AND status='Y'
	if (!empty($user_data) && $password == text_decrypt($user_data["password"]))
		{
		$_SESSION["logged"]=1;
		$_SESSION["login"]=$username;
		$_SESSION["url"]="";
		
		}
		else {
		$_SESSION["logged"]=0;
	
		}
	}break;
case "logout":{
	$_SESSION["logged"]=0;
	$_SESSION["login"]="";
	}break;
}
//echo "Location: $redirect";
header("Location: $redirect");
?>