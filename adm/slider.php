<?php
session_start();
include "localconf.php";
$locationvar="slider";
$action_var="slider.php";

$title=$lng["lbl_gallery"];

$actionmode="add";
$merror=""; 

$action_array=Array();
$action_array[" "]=" ";
$action_array["move"]=$lng["lbl_move"];
$action_array["hide"]=$lng["lbl_hide"];
$action_array["show"]=$lng["lbl_in_hide"];
$action_array["delete"]=$lng["lbl_delete"];


$mode=get_get_var("mode");
if (!empty($_POST["mode"]))	$mode=$_POST["mode"];

$id=get_get_var("id");
if (!empty($_POST["id"])) $id=$_POST["id"];

$aid=get_get_var("aid");
if (!empty($_POST["aid"])) $aid=$_POST["aid"];

$cat='0';
if (!empty($_GET["cat"])) $cat=$_GET["cat"];
if (!empty($_POST["cat"]))$cat=$_POST["cat"];

if (!empty($_POST["posted_data"]))
	$posted_data=$_POST["posted_data"];

function image_action($name,$fildname,$id){
global $sql_tbl;
$img_type="";

	if ( isset($_FILES[$name])&&($_FILES[$name]["error"]==0) ){
		$img_type=image_type($_FILES[$name]["type"]);
		put_file($_FILES[$name]["tmp_name"],"../img/slider/".$id."_$fildname.".$img_type);
		db_query("UPDATE $sql_tbl[slider] SET $fildname='$img_type' WHERE id='$id'");
	}
	
	return $img_type;
}
$album_data='';	
$album_data_action_mode="add_album";

switch ($mode){
    case "add":{
    	if ( !empty($_FILES) ){
			$rr=func_query_first_cell ("SELECT max(orderby) FROM $sql_tbl[slider] where 1 ");
			for ($i=0;$i<1;$i++){
			 	if ( ($_FILES["image".$i]["error"]==0) )
				{
				if (empty($posted_data["alt".$i])) $alt=$posted_data["alt".$i];
				db_query("INSERT INTO $sql_tbl[slider] (cat,date,alt,avail,orderby) VALUES('$cat','".time()."','$alt','N','$rr')");
				
				$id=db_insert_id();
				
				image_action("image".$i,"image",$id);
				}
				
			}
    		 // header("location:$action_var?cat=$cat");
    	}
		
		header("location:$action_var");
    }break;
case "update":{
	
	if (!empty($_FILES["image"])&&(!$_FILES["image"]["error"]))
	image_action("image","image",$posted_data['id']);
	
	if (!empty($posted_data['alt'])){
		db_query("UPDATE $sql_tbl[slider] SET alt='$posted_data[alt]' WHERE id='$posted_data[id]'");
	}	
    
	header("location:$action_var?cat=$cat&id=$id&mode=edit");
	
}break;
case "update_all":{
	if ( !empty($posted_data) ){
		$m_action=get_post_var("action_mode");
		
		if (!empty($_POST["move_category"])) $m_category=$_POST["move_category"];
    	else $m_category="0";
		$i=0;
		foreach ($posted_data as $a=>$v) 
		{
		$move="";
		$avail="";
			
			if (!empty($v["item"])&&($m_action=="move")) $move=" ,cat='$m_category'"; 
			if (!empty($v["item"])&&($m_action=="hide")) $avail=" ,avail='Y'"; 
			if (!empty($v["item"])&&($m_action=="show")) $avail=" ,avail='N'"; 

			$v['url']=addslashes($v['url']);
			db_query("UPDATE $sql_tbl[slider] SET url='$v[url]', orderby='$i', alt='$v[alt]' $avail $move WHERE id='$a'");	
	
			if (!empty($v["item"])&&($m_action=="delete")){
				$del=func_query_first("SELECT * FROM $sql_tbl[slider] WHERE id='$a' ");
			
				un_link("../img/slider/".$del['id']."_image.".$del['image']);
					
				db_query("DELETE FROM $sql_tbl[slider] WHERE id='$a'");
			}
			$i++;
		}
	
	}

	header("location:$action_var?cat=$cat");//
    }break;
	case "edit":{
    $posted_data=func_query_first ("SELECT * FROM $sql_tbl[slider] where id='$id'");
		$posted_data["image"]="/img/slider/".$posted_data['id']."_image.".$posted_data["image"];
	
    $actionmode="update";
    }break;
	case "add_album":{
		if (!empty($posted_data["album"])){
			db_query("INSERT INTO $sql_tbl[albums] (parentid,category) VALUES('$cat','$posted_data[album]')");
			$aid=db_insert_id();
			header("location:$action_var?cat=$cat&aid=$aid&id=$id&mode=album_edit");
		}
    }break;
	case "album_edit":{
		$album_data=func_query_first_cell ("SELECT category FROM $sql_tbl[albums] where categoryid='$aid'");
		$album_data_action_mode="update";
    }break;
	case "action_album":{
	if ( !empty($posted_data) ){
		$m_action=get_post_var("action_mode");
		
		if (!empty($_POST["m_category"])) $m_category=$_POST["m_category"];
    	else $m_category="0";
		$i=0;
		//r_print_r($posted_data);
		
		foreach ($posted_data as $a=>$v){
		$move="";
		$avail="";
			
			if (!empty($v["item"])&&($m_action=="move")) $move=" ,parentid='$m_category'"; 
			if (!empty($v["item"])&&($m_action=="hide")) $avail=" ,avail='Y'"; 
			if (!empty($v["item"])&&($m_action=="show")) $avail=" ,avail='N'"; 

			db_query("UPDATE $sql_tbl[albums] SET order_by='$i' $avail $move WHERE categoryid='$a'");

			if (!empty($v["item"])&&($m_action=="delete")){
				$delete_galary_info=func_query("SELECT * FROM $sql_tbl[slider] WHERE category='$a' and md5='gallery' ");	
					if (!empty($delete_galary_info))
						foreach ($delete_galary_info as $m=>$l){
							un_link("../img/slider/".$l['id']."_image.".$l['image']);
							db_query("DELETE FROM $sql_tbl[slider] WHERE id='$l[id]' and md5='gallery' ");
						}
				db_query("DELETE FROM $sql_tbl[albums] WHERE categoryid='$a'  ");
			}
			
			$i++;
		}
	
	}
	header("location:$action_var?cat=$cat&aid=$aid&id=$id&mode=album_edit");
	//header("location:$action_var?cat=$cat");
    }break;
}    

if (empty($posted_data)){
		$posted_data["name"]="";
		$posted_data["alt"]="";
		$posted_data["category"]=$cat;
		$posted_data["image"]="";
		$posted_data["icon"]="";
}
			
$arrow=Array();
$arrow["0"]="";
arrow_show_tree(0,$sql_tbl["categories"]);
$all_categories=$arrow;
unset($arrow);


if (!empty($albums))
foreach ($albums as $a=>$b)
	if ($b['categoryid']!=$aid) $albom_all_categories[$b['categoryid']]=$b['category'];


$images = func_query ("SELECT * FROM $sql_tbl[slider] WHERE cat='$cat' ORDER BY orderby ");//and md5='gallery'
if (is_array($images))
	foreach ($images as $a=>$v){
		$images[$a]["image"]="/img/slider/".$v['id']."_image.".$v["image"];
		$images[$a]["icon"]="/img/slider/".$v['id']."_image.".$v["image"];
		}
	
$title=$lng["lbl_slider"];
include "../tpl/admin/home.tpl";
?>