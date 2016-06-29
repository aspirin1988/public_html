<?php
session_start(); 
include "localconf.php";
$action_var="comments.php";

$mode=get_get_var("mode");
$posted_data=get_post_var("posted_data");

if (!empty($_POST["mode"]))
	$mode=$_POST["mode"];



switch ($mode) {
case "dellcomments":{
	if (is_array($posted_data))
		foreach ($posted_data as $a=>$v) 
			if (!empty($v["del"])) db_query("DELETE FROM $sql_tbl[comments] WHERE id='$a'");
	header("location:$action_var");
	}break;

case "update_comments":{
	if (is_array($posted_data))
		foreach ($posted_data as $a=>$v) {
			if (!empty($v["hide"])) $hide='Y'; else $hide='N';
			db_query("UPDATE $sql_tbl[comments] SET text='$v[text]',answer='$v[answer]', hide='$hide', status='$v[status]' WHERE id='$a'");
			}
	header("location:$action_var");
	}break;
}

$action=array (
	"M" => "На модерации",
	"H" => "Скрыт",
	"Y" => "Промодерирован",
);

$amount_comment=array (
"5" => "5",
"10" => "10",
"25" => "25",
"50" => "50"
);
$direct_array=array (
"DESC" => $lng["lbl_decrease"],
"ASC" => $lng["lbl_increase"]
);

$query ="SELECT * FROM $sql_tbl[comments] ";
	require "../include/navigation.php";
	
	if (!empty($_GET["page_all"])) 
	$slimit="";
	else $slimit="LIMIT $first_page, $objects_per_page";
	

	if (!empty($_GET["comentid"]))
		{
		$comentid=$_GET["comentid"];
	 	$query.=" AND id='$comentid' ORDER BY date DESC";
	 	}else $query.="ORDER BY date DESC $slimit";
	 
	$all_comments = func_query ("$query");
	if (!empty($all_comments))
	foreach ($all_comments as $a=>$b)
	{
		switch($b['type']){
			case "product":{
			$t=func_query_first("SELECT * FROM $sql_tbl[catalogue] WHERE cat='$b[cat]'");
			$all_comments[$a]['type']=$t['article'];
			}break;
			case "":{
				if ($b['cat']!='0'){
					$t=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$b[cat]' ");
					$all_comments[$a]['type']=$t['category'];
				}
			}break;
		}
		$all_comments[$a]["date"]=strftime($config["timeformat"],$b["date"]);
	}

$title=$lng["lbl_coments"];
$locationvar="comments";

include "../tpl/admin/home.tpl";
?>