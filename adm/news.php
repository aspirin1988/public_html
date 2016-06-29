<?php
session_start();
include "localconf.php";

$action_var="news.php";

$type_news=Array();
$type_news['news']="новости";
$type_news['events']="мероприятия";

$avail=array(
"Y"=>$lng["lbl_yes"],
"N"=>$lng["lbl_no"]
);

$mode=get_post_var("mode");
if (!empty($_GET["mode"]))	$mode=$_GET["mode"];

//$t=get_post_var("t");
if (!empty($_POST["t"])) $t=$_POST["t"]; else $t="news";
if (!empty($_GET["t"]))	$t=$_GET["t"];

$posted_data=get_post_var("posted_data");

if (empty($posted_data["active"])) $act="N"; else $act="Y";

$nid=get_get_var("nid");
if (!empty($_POST["nid"]))	$nid=$_POST["nid"];


$icon="";	
$image="";
	if (!empty($_FILES["image"]["tmp_name"])){
	$icon=", icon='".get_image_data($_FILES["image"]["tmp_name"],100,$_SESSION["login"])."'";
	$image=", image='".get_image_data($_FILES["image"]["tmp_name"],500,$_SESSION["login"])."'";
	}	

switch ($mode) {

case "edit":{
	$posted_data=func_query_first("SELECT *  FROM $sql_tbl[news] WHERE id='$nid'");
	$actionmode="update";
	
	$images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$nid' and md5='news' order by orderby");
	if (!empty($images))
	foreach ($images as $a=>$b){
		$images[$a]['icon']=str_replace('/assets/image/news/','/img/newsicons/',$b['image_path']);
		$images[$a]['image']=$b['image_path'];
	}
		
		//admin/image.php?f=&h=120
	$posted_data['imgcount']=count($images);
}break;

case "add":{
		if (is_array($posted_data)){ 
		db_query("INSERT INTO $sql_tbl[news] (name,active,time,description,news) VALUES ('$posted_data[name]','$act','".time()."','$posted_data[description]','$posted_data[news]')");
		$cat=db_insert_id();
		$ddate=$posted_data["m"]."/".$posted_data["d"]."/".$posted_data["y"];
		db_query("UPDATE $sql_tbl[news] SET  date='".strtotime ($ddate)."' $icon $image WHERE id='$cat'");//url='$cat',
		}
		header("Location:$action_var?t=$posted_data[type]");
	}break;
	
case "update":{
	if (is_array($posted_data)){ 
		$ddate=$posted_data["m"]."/".$posted_data["d"]."/".$posted_data["y"];
		$ddate=strtotime ($ddate);
		db_query("UPDATE $sql_tbl[news] SET name='$posted_data[name]', description='$posted_data[description]', date='$ddate', news='$posted_data[news]', time='".time()."' $icon $image WHERE id='$nid'");
		//type='$posted_data[type]',
		$m_action=get_post_var("action_mode");
		$i=0;
		$_posted_data=get_post_var("posted_date");
		if (!empty($_posted_data))
		foreach ($_posted_data as $a=>$v) 
		{
		$move="";
		$avail="";
			
			if (!empty($v["item"])&&($m_action=="hide")) $avail=" ,avail='N'"; 
			if (!empty($v["item"])&&($m_action=="show")) $avail=" ,avail='Y'"; 

			db_query("UPDATE $sql_tbl[gallery] SET orderby='$i', alt='$v[alt]' $avail WHERE id='$a'");	
	
			if (!empty($v["item"])&&($m_action=="delete")){
				$del=func_query_first("SELECT * FROM $sql_tbl[gallery] WHERE id='$a' ");
				un_link($del['image_path']);
				db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$a'");
			}
			$i++;
		}
	}
	header("Location:$action_var?nid=$nid&mode=edit&t=$posted_data[type]");

	}break;	
case "update_group":{
	if (is_array($posted_data)) 
			foreach ($posted_data as $p=>$v){ 
				if (!empty($v["del"])){
				db_query("DELETE FROM $sql_tbl[news] WHERE id='$p'");
				unset($posted_data[$p]);
				}
			if (empty($v["active"])) $act="N"; else $act="Y";
			
			if (empty($v["archive"])) $archive="N"; else $archive="Y";


				db_query("UPDATE $sql_tbl[news] SET active='$act', archive='$archive' WHERE id='$p'");
		}	
		header("Location:$action_var");
	}break;
}

$query="SELECT * FROM $sql_tbl[news] where 1 ";//type='$t'


	require "../include/navigation.php";
	$slimit="LIMIT $first_page, $objects_per_page";
	
	if (!empty($_GET["page_all"])) $slimit="";
	
	$news=func_query ("$query ORDER BY date DESC $slimit");
//echo "$query ORDER BY date DESC $slimit";
if (is_array($news) )
foreach ($news as $ac=>$bc) 
	$news[$ac]["date"]=strftime($config["timeformat"],$bc["date"]);

//r_print_r($news);


if (empty($posted_data))
{
	$posted_data["id"]="";
	$posted_data["description"]="";
	$posted_data["active"]="Y";
	$posted_data["date"]=time();//strftime($config["timeformat"],time());
	$posted_data["time"]=strftime($config["timeformat"],time());
	$posted_data["news"]="";
	$posted_data["icon"]="";
	$posted_data["name"]="";
	$posted_data["type"]=$t;
	
	$actionmode="add";
}	

$posted_data["date"]=strftime($config["timeformat"],$posted_data["date"]);

$ddata=explode (".",$posted_data["date"]);;

$posted_data["d"]=$ddata[0];
$posted_data["y"]=$ddata[2];
$posted_data["m"]=$ddata[1];

//IBLOCK_ID =15
function t_time ($t){
$time=explode(" ",$t);
	$time_a=explode("-",$time[0]);
	$time_b=explode(":",$time[1]);
	//2011-03-28 08:31:08
	return mktime($time_b[0],$time_b[1],$time_b[2], $time_a[1],$time_a[2],$time_a[0]); 
}

$action_array=Array();
$action_array[" "]=" ";
$action_array["hide"]=$lng["lbl_hide"];
$action_array["show"]=$lng["lbl_in_hide"];
$action_array["delete"]=$lng["lbl_delete"];

$title=$lng["lbl_news"];
$locationvar="news";

include "../tpl/admin/home.tpl";
?>