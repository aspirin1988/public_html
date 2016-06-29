<?php
$usertype="C";
$web_dir="tpl/custumer/";

require "config.php";

session_start();
setcookie($cookie_array_name."[cookietest]",time(),time(),"/");
 if (!empty($_COOKIE["$cookie_array_name[cookietest]"]))
 	  $cookietest=1;
 else $cookietest=0;

?>