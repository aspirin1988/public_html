<?php
session_start(); 
include "localconf.php";

$mode=get_post_var("mode");

$posted_data=get_post_var("posted_data");

if (empty($language)) $language = "RU";
if (empty($topic)) $topic = "Labels";
if (empty($filter)) $filter = "";

$topics = array ("Labels", "Text", "Errors", "E-Mail", "Languages", "Countries");

if (!$topic) $topic = $topics [0];

switch ($mode){
	case "update": {
	$language=$_POST["language"];
	if (is_array($posted_data)) 
			foreach ($posted_data as $key=>$v) 
				db_query("UPDATE $sql_tbl[languages] SET value='$v[value]' WHERE name='$v[name]' AND code='$language'");
		header("location:languages.php");
	};break;
	case "add": {
	$language=$_POST["language"];
	$topic=$_POST["topic"];
	if (is_array($posted_data)) 
		foreach ($languages as $key=>$v) 
			db_query ("INSERT INTO $sql_tbl[languages] (code, name, value, topic) VALUES ('$v[code]','".addslashes ($posted_data["name"])."', '".addslashes ($posted_data["value"])."','".addslashes ($topic)."')");
			
		header("location:languages.php");
	};break;
	case "dell": {
	if (is_array($posted_data)) 
			foreach ($posted_data as $p=>$v) 
			if (!empty($v["del"]))
			db_query("DELETE FROM $sql_tbl[languages] WHERE name='".$v["name"]."'");
	};break;
	case "selectlanguage":{
	$language=$_POST["language"];
	$asl=$language;
	$topic=$_POST["topic"];
	$filter=$_POST["filter"];
	};break;
}

$topic_condition = " AND topic='$topic' ";
$query = "SELECT * FROM $sql_tbl[languages] WHERE code='$language' AND (name LIKE '%$filter%' or value LIKE '%$filter%') $topic_condition order by name"; 

	require "../include/navigation.php";
	
	if (!empty($_GET["page_all"])) 
	$slimit="";
	else $slimit="LIMIT $first_page, $objects_per_page";
	
	$edit_lang = func_query ("$query $slimit");

$title=$lng["lbl_languages"];
$action_var="languages.php";
$locationvar="languages";
include "../tpl/admin/home.tpl";
?>