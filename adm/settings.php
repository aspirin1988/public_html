<?php
session_start(); 

include "localconf.php";
$action_var="settings.php";

$mode=get_post_var("mode");

if ($mode=="update")
	{
	if (!empty($_POST["post_date"]))
	{$post_date=$_POST["post_date"];
		//$trans = get_html_translation_table(HTML_ENTITIES);
		foreach ($post_date as $c=>$d)
		db_query("update $sql_tbl[config] set value='".htmlspecialchars($d)."' where name='$c'");
	}
	Header("Location:settings.php");
	}
	
$lives=array (  
	"0" => "запретить вывод списка св€тых",
	"1" => "жити€ св€тых в отдельных строчках",
	"2" => "жити€ св€тых в одном параграфе",
	"3" => "жити€ основных св€тых в отдельных строчках",
	"4" => "жити€ основных св€тых в одном параграфе",
	"5" => "жити€ основных св€тых и новомучеников в отдельных строчках (ћосковска€ патриархи€)",
	"6" => "жити€ основных св€тых и новомучеников в одном параграфе (ћосковска€ патриархи€)"
);

$trp=array (  
	"0" => "запретить вывод тропарей",
	"1" => "выводит тропари с заголовком (слово “ропари)",
	"2" => "выводит тропари без заголовка"
);

$scripture=array (  
	"0" => "запретить вывод  ≈вангельских чтений",
	"1" => "выводит ≈вангельские чтени€ с заголовком (слово ≈вангельские чтени€)",
	"2" => "выводит ≈вангельские чтени€ без заголовка",
);


$selectvalue = array ("10", "25", "50");
$checkbox = array (
"Y" => $lng["lbl_yes"],
"N" => $lng["lbl_no"]
);

$sort=array (  
"DESC" => $lng["lbl_decrease"],
"ASC" => $lng["lbl_increase"]
);

$amount_comment=array (
"5" => "5",
"10" => "10",
"25" => "25",
"50" => "50"
);

$setup = func_query ("SELECT name,value,type,comment FROM $sql_tbl[config]");
	
$title=$lng["lbl_settings"];
$locationvar="settings";
include "../tpl/admin/home.tpl";
?>