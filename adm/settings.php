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
	"0" => "��������� ����� ������ ������",
	"1" => "����� ������ � ��������� ��������",
	"2" => "����� ������ � ����� ���������",
	"3" => "����� �������� ������ � ��������� ��������",
	"4" => "����� �������� ������ � ����� ���������",
	"5" => "����� �������� ������ � ������������� � ��������� �������� (���������� ����������)",
	"6" => "����� �������� ������ � ������������� � ����� ��������� (���������� ����������)"
);

$trp=array (  
	"0" => "��������� ����� ��������",
	"1" => "������� ������� � ���������� (����� �������)",
	"2" => "������� ������� ��� ���������"
);

$scripture=array (  
	"0" => "��������� �����  ������������ ������",
	"1" => "������� ������������ ������ � ���������� (����� ������������ ������)",
	"2" => "������� ������������ ������ ��� ���������",
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