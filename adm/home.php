<?php
session_start(); 
include "localconf.php";
$action_var="home.php";
$title=$lng["lbl_administration"];
$locationvar="main_admin";
include "../tpl/admin/home.tpl";
?>