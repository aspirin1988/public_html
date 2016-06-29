<?php
session_start();
include "localconf.php";
$locationvar="gallery";
$action_var="gallery.php";

$title=$lng["lbl_gallery"];

$actionmode="add";
$merror=""; 

$root_cat=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE location='$action_var' ");
if ( empty($root_cat['categoryid']) ){
	$root_cat['categoryid']=0;
	$root_cat['category']='';
}
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


if (!empty($_GET["cat"])) $cat=$_GET["cat"]; else $cat=$root_cat["categoryid"];
if (!empty($_POST["cat"]))$cat=$_POST["cat"];

if (!empty($_POST["posted_data"]))
	$posted_data=$_POST["posted_data"];

function image_action($name,$fildname,$id){
global $sql_tbl;
$img_type="";

	if ( isset($_FILES[$name])&&($_FILES[$name]["error"]==0) ){
		$img_type=image_type($_FILES[$name]["type"]);
		put_file($_FILES[$name]["tmp_name"],"../img/gallery/".$id."_$fildname.".$img_type);
		db_query("UPDATE $sql_tbl[gallery] SET $fildname='$img_type' WHERE id='$id'");
	}
	
	return $img_type;
}
$album_data='';	
$album_data_action_mode="add_album";

switch ($mode){
    case "add":{
    	if ( !empty($_FILES) ){
			$rr=func_query_first_cell ("SELECT max(orderby) FROM $sql_tbl[gallery] where category ='$cat' ");
			for ($i=0;$i<5;$i++){
			 	if ( ($_FILES["image".$i]["error"]==0)&&($_FILES["icon".$i]["error"]==0) )
				{
				if (empty($posted_data["alt".$i])) $alt=$posted_data["alt".$i];
				db_query("INSERT INTO $sql_tbl[gallery] (date,category,alt,avail,orderby) VALUES('".time()."','$cat','$alt','N','$rr')");
				
				$id=db_insert_id();
				
				image_action("image".$i,"image",$id);
				image_action("icon".$i,"icon",$id);
				}
				
			}
    		  header("location:$action_var?cat=$cat");
    	}else header("location:$action_var");
    }break;
case "update":{
	
	if (!empty($_FILES["image"])&&(!$_FILES["image"]["error"]))
	image_action("image","image",$posted_data['id']);

	if (!empty($_FILES["icon"])&&(!$_FILES["icon"]["error"]))
	image_action("icon","icon",$posted_data['id']);
	
	if (!empty($posted_data['alt'])){
		db_query("UPDATE $sql_tbl[gallery] SET alt='$posted_data[alt]' WHERE id='$posted_data[id]'");
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
			
			if (!empty($v["item"])&&($m_action=="move")) $move=" ,category='$m_category'"; 
			if (!empty($v["item"])&&($m_action=="hide")) $avail=" ,avail='Y'"; 
			if (!empty($v["item"])&&($m_action=="show")) $avail=" ,avail='N'"; 

			db_query("UPDATE $sql_tbl[gallery] SET orderby='$i', alt='$v[alt]' $avail $move WHERE id='$a'");	
	
			if (!empty($v["item"])&&($m_action=="delete")){
				$del=func_query_first("SELECT * FROM $sql_tbl[gallery] WHERE id='$a' ");
			
				un_link("../img/gallery/".$del['id']."_icon.".$del['icon']);
				un_link("../img/gallery/".$del['id']."_image.".$del['image']);
					
				db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$a'");
			}
			$i++;
		}
	
	}

	header("location:$action_var?cat=$cat");
    }break;
	case "edit":{
    $posted_data=func_query_first ("SELECT * FROM $sql_tbl[gallery] where id='$id'");
	
		$posted_data["image"]="/img/gallery/".$posted_data['id']."_image.".$posted_data["image"];
		$posted_data["icon"]="/img/gallery/".$posted_data['id']."_icon.".$posted_data["icon"];
	
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
				$delete_galary_info=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$a' and md5='gallery' ");	
					if (!empty($delete_galary_info))
						foreach ($delete_galary_info as $m=>$l){
							un_link("../img/gallery/".$l['id']."_icon.".$l['icon']);
							un_link("../img/gallery/".$l['id']."_image.".$l['image']);
							db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$l[id]' and md5='gallery' ");
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

$albums=func_query("SELECT * FROM $sql_tbl[albums] WHERE parentid='$cat' ");
			
$arrow=Array();
$arrow["0"]="";
arrow_show_tree(0,$sql_tbl["categories"]);
$all_categories=$arrow;
unset($arrow);

$albom_all_categories=array();
if (!empty($albums))
foreach ($albums as $a=>$b)
	if ($b['categoryid']!=$aid) $albom_all_categories[$b['categoryid']]=$b['category'];


$images = func_query ("SELECT * FROM $sql_tbl[gallery] WHERE category='$aid' and md5='gallery' ORDER BY orderby ");
if (is_array($images))
	foreach ($images as $a=>$v){
		$images[$a]["image"]="/img/gallery/".$v['id']."_image.".$v["image"];
		$images[$a]["icon"]="/img/gallery/icons/".$v['id']."_image.".$v["image"];
	}

$title=$lng["lbl_gallery"];
include "../tpl/admin/home.tpl";
?>