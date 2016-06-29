<?php
session_start();
include "localconf.php";

$locationvar="products";
$action_var="products.php";

$actionmode="add";

$mode=get_get_var("mode");
if (!empty($_POST["mode"])) $mode=$_POST["mode"]; 

$extrafilds=get_get_var("extrafilds");
if (!empty($_POST["extrafilds"])) $extrafilds=$_POST["extrafilds"]; 

$eid=get_get_var("eid");
if (!empty($_POST["eid"])) $eid=$_POST["eid"]; 

$id=get_post_var("id");
if (!empty($_GET["id"])) $id=$_GET["id"];
$move_category_value=get_post_var("move_category_value");
$start_cat=func_query_first_cell("SELECT categoryid FROM $sql_tbl[categories] WHERE location='catalogue/' ");

if (!empty($_GET["cat"])) $cat=$_GET["cat"]; else $cat=$start_cat;
if (!empty($_POST["cat"])) $cat=$_POST["cat"];

if (!empty($_POST["posted_data"])) $posted_data=$_POST["posted_data"];
$alldata='';

$image_action['0']='';
$image_action['1']='Скрыть';
$image_action['2']='Отобразить';
$image_action['3']='Удалить';
$image_action['4']='Переместить';

$action[0]='Действие';
$action[1]='Скрыть';
$action[2]='Показать';
$action[3]='Удалить';


$action_image=get_post_var('action_image');

function data_images($id,$value,$pref){
	global $sql_tbl;
	$posted_data['p1']=$value;
	$file=$value.'_lens.jpg';
	if (file_exists('../tmp/'.$file)){
		db_query("UPDATE $sql_tbl[gallery] SET avail='Y' WHERE category='$id' ");	
		db_query("INSERT INTO $sql_tbl[gallery] (image,date,category,avail) VALUES ('jpg','".time()."','$id','N')");
			$id=db_insert_id();
			copy ('../tmp/'.$file,'../img/gallery/'.$id.$pref.'.jpg');
			//un_link('../tmp/'.$file);
		} 
}


function data_parser($alldata,$mmode){
	global $sql_tbl,$id,$cat;
	$_alldata=explode("\r\n",$alldata);
		//r_print_r($_alldata);
		$i=0;
		foreach ($_alldata as $a=>$b){
			$b=trim(preg_replace('/\s{2,}/', ' ', $b));
			//trim($b);
			$i++;
			$__row=explode(" ",$b);
			$art=$_data[$i][0]=array_shift($__row);
			$_name='';
			foreach ($__row as $a=>$b)
				if (!is_numeric($b)) $_name.=' '.array_shift($__row); else break;
			$_data[$i][1]=trim($_name);
			
			$_data[$i][2]=$ball=array_shift($__row);
			$_data[$i][3]=$bo=array_shift($__row);
			array_shift($__row);
			$_data[$i][4]=$p_price=array_shift($__row);
			array_shift($__row);
			$_data[$i][5]=$price=array_shift($__row);
		}
	
		if ( !empty($_data) )
			foreach ($_data as $a=>$b){
				
				$posted_data['p1']=mysql_real_escape_string($b[0]);
				$posted_data['name']=mysql_real_escape_string($b[1]);
				$posted_data['points']=$b[2];
				$posted_data['bo']=$b[3];
				$posted_data['partner_price']=str_replace(",",".",str_replace(".",'',$b[4]));
				$posted_data['price']=str_replace(",",".",str_replace(".",'',$b[5]));
				$posted_data['description']='';
				$posted_data['type']='';
				$posted_data['p3']='';
				$posted_data['p4']='';
				
				$posted_data['url']=translit($posted_data['name']).'.html';
				if ($mmode=='add'){
					$sql="INSERT INTO $sql_tbl[products] (points,bo,partner_price,`date`,url,`type`,name,cat,p1,p2,p3,p4,price,description) VALUES ('$posted_data[points]','$posted_data[bo]','$posted_data[partner_price]','".time()."','$posted_data[url]','$posted_data[type]','$posted_data[name]', '$cat','$posted_data[p1]','".translit($posted_data["type"])."','$posted_data[p3]','$posted_data[p4]','$posted_data[price]', '$posted_data[description]')";
					//db_query($sql);
				}
				if ($mmode=='update'){
					$sql="UPDATE $sql_tbl[products] SET active='Y', points='$posted_data[points]', bo='$posted_data[bo]', partner_price='$posted_data[partner_price]',	update_date='".time()."', price='$posted_data[price]', name='$posted_data[name]',p1='$posted_data[p1]' WHERE id='$id'";	
					//db_query($sql);
				}
				if ($mmode=='updateall'){
					if (func_query_first_cell("SELECT count(*) FROM $sql_tbl[products] WHERE p1='$posted_data[p1]' ")==0){
							$sql="INSERT INTO $sql_tbl[products] (points,bo,partner_price,`date`,url,`type`,name,cat,p1,p2,p3,p4,price,description) VALUES ('$posted_data[points]','$posted_data[bo]','$posted_data[partner_price]','".time()."','$posted_data[url]','$posted_data[type]','$posted_data[name]', '$cat','$posted_data[p1]','".translit($posted_data["type"])."','$posted_data[p3]','$posted_data[p4]','$posted_data[price]', '$posted_data[description]')";
							db_query($sql);
							$id=db_insert_id();
						} else {
						$sql="UPDATE $sql_tbl[products] SET active='Y', points='$posted_data[points]', bo='$posted_data[bo]', partner_price='$posted_data[partner_price]',	update_date='".time()."', price='$posted_data[price]', name='$posted_data[name]',p1='$posted_data[p1]' WHERE p1='$posted_data[p1]' ";	
						db_query($sql);
						$id=func_query_first_cell("SELECT id FROM $sql_tbl[products] WHERE p1='$posted_data[p1]' ");
					}
					data_images($id,$posted_data['p1'],'_image');
				}
				if ($mmode=='update_all_extrafilds'){
					
					if (func_query_first_cell("SELECT count(*) FROM $sql_tbl[extra_filds] WHERE cat='$id' and name='$posted_data[name]' ")==0){
						$sql="INSERT INTO $sql_tbl[extra_filds] (description,cat,points,bo,partner_price,price,name,`date`)	VALUES ('$posted_data[name]','$id','$posted_data[points]','$posted_data[bo]','$posted_data[partner_price]','$posted_data[price]','$posted_data[p1]','".time()."')";
						db_query($sql);
						$e__id=db_insert_id();
					}else {
						$sql="UPDATE $sql_tbl[extra_filds] SET points='$posted_data[points]', bo='$posted_data[bo]', description='$posted_data[name]', partner_price='$posted_data[partner_price]', price='$posted_data[price]' WHERE cat='$id' and p1='$posted_data[p1]' "; 
						db_query($sql);
						$e__id=func_query_first_cell("SELECT id FROM $sql_tbl[extra_filds] WHERE cat='$id' and p1='$posted_data[p1]' ");
					}
					$file=$posted_data['p1'].'_lens.jpg';
					if (file_exists('../tmp/'.$file)){
						db_query("UPDATE $sql_tbl[extra_filds] SET p1='jpg' WHERE id='$e__id' ");
						//un_link('../img/gallery/'.$e__id.'_variation.jpg');
						copy('../tmp/'.$file,'../img/gallery/'.$e__id.'_variation.jpg');
					} 
				}
			}
}
$show_fragments=false;
//echo $mode;
switch ($mode) {
	
	case "dell_extra_filds": {
		if (is_array($posted_data))
			foreach ($posted_data as $a=>$v) 
				if (!empty($v["del"])){
					un_link("../img/gallery/".$a."_variation.jpg");
					un_link("../img/gallery/".$a."_variation.gif");
					db_query("DELETE FROM $sql_tbl[extra_filds] WHERE id='$a'");
					unset($posted_data[$a]);
				}
	header("location:$action_var?id=$id&cat=$cat&mode=show");
	}break;
	case "update_extra_filds": {
	if ($extrafilds['name']!=""){
		db_query("UPDATE $sql_tbl[extra_filds] set partner_price='$extrafilds[partner_price]', price='$extrafilds[price]', name='$extrafilds[name]',description='$extrafilds[description]' WHERE id='$eid' ");
		
		if (isset($_FILES["extrafilds"])&&($_FILES["extrafilds"]["error"]==0)) {
			$del=func_query_first("SELECT * FROM $sql_tbl[extra_filds] WHERE id='$eid' ");
			un_link("../img/gallery/".$eid."_variation.".$del['p1']);
		
			$img_type=image_type($_FILES["extrafilds"]["type"]);
			put_file($_FILES["extrafilds"]["tmp_name"],"../img/gallery/".$eid."_variation.".$img_type);
			db_query("UPDATE $sql_tbl[extra_filds] SET p1='$img_type' WHERE id='$eid'");
		}
		
 	header("location:$action_var?id=$id&cat=$cat&eid=$eid&mode=show");	
 	}
 }break;
 
 case "add_extra_filds": {
	if ($extrafilds['name']!=""){
		$tt_price=func_query_first("SELECT * FROM $sql_tbl[products] WHERE id='$id' ");
		if (empty($extrafilds['price'])) $extrafilds['price']=$tt_price['price'];
		if (empty($extrafilds['partner_price'])) $extrafilds['partner_price']=$tt_price['partner_price'];
		
		db_query("INSERT INTO $sql_tbl[extra_filds] (price,partner_price,name,`date`,cat,description) VALUES ('$extrafilds[price]','$extrafilds[partner_price]','$extrafilds[name]','".time()."','$id','$extrafilds[description]')");
		$eid=db_insert_id();
		if (isset($_FILES["extrafilds"])&&($_FILES["extrafilds"]["error"]==0)) {
			$img_type=image_type($_FILES["extrafilds"]["type"]);
			put_file($_FILES["extrafilds"]["tmp_name"],"../img/gallery/".$eid."_variation.".$img_type);
			db_query("UPDATE $sql_tbl[extra_filds] SET p1='$img_type' WHERE id='$eid'");
		}
 	header("location:$action_var?id=$id&cat=$cat&mode=show");	//&eid=$eid
 	}
 }break;
 case "add": {
 	$posted_data["name"]=trim($posted_data["name"]);
	if ($posted_data["name"]!=""){
		if ($posted_data['url']=='') $posted_data['url']=translit($posted_data['name']).'.html';
		db_query("INSERT INTO $sql_tbl[products] (partner_price,`date`,url,`type`,name,cat,p1,p2,p3,p4,price,description) VALUES ('$posted_data[partner_price]','".time()."','$posted_data[url]','$posted_data[type]','$posted_data[name]', '$cat','$posted_data[p1]','".translit($posted_data["type"])."','$posted_data[p3]','$posted_data[p4]','$posted_data[price]', '$posted_data[description]')");
		$id=db_insert_id();
		db_query("UPDATE $sql_tbl[products] SET meta_description='$posted_data[meta_description]', meta_keywords='$posted_data[meta_keywords]', title='$posted_data[title]' WHERE id='$id'");
		
 	header("location:$action_var?id=$id&cat=$cat&mode=show");	
 	}
 }break;
case "update": {
	$posted_data["name"]=trim($posted_data["name"]);
	if ($posted_data['name']!=''){
		$posted_data['description']=addslashes($posted_data['description']);
		if ($posted_data['url']=='') $posted_data['url']=translit($posted_data['name']).'.html';
		
		db_query("UPDATE $sql_tbl[products] SET meta_description='$posted_data[meta_description]', meta_keywords='$posted_data[meta_keywords]', title='$posted_data[title]', partner_price='$posted_data[partner_price]', update_date='".time()."', url='$posted_data[url]', price='$posted_data[price]', `type`='$posted_data[type]', name='$posted_data[name]', description='$posted_data[description]', p1='$posted_data[p1]',p2='".translit($posted_data["type"])."',p3='$posted_data[p3]',p4='$posted_data[p4]' WHERE id='$id'");
		header("location:$action_var?cat=$cat&id=$id&mode=show");	
	}else $merror="Введите название продукта.";
}break;

 case "show": {
 	$show_fragments=true;
	$posted_data=func_query_first("SELECT * FROM $sql_tbl[products] where id='$id' ");
	$extra_filds=func_query("SELECT * FROM $sql_tbl[extra_filds] WHERE cat='$id' ");
 	if (!empty($posted_data['id'])) $id=$posted_data['id'];
	$actionmode="update";
 }break;
 
 case "updateall": {
	if (is_array($posted_data)){
		foreach ($posted_data as $a=>$v) 
			if (!empty($v["del"])){
				$dell_images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$a' and md5='gallery' ");
				if (!empty($dell_images))
					foreach ($dell_images as $e=>$f){
						un_link("../img/gallery/".$f['id']."_icon.".$f['icon']);
						un_link("../img/gallery/".$f['id']."_image.".$f['image']);
						db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$f[id]'");
					}
				$dell_e_fild=func_query("SELECT * FROM $sql_tbl[extra_filds] WHERE cat='$a' ");
				if (!empty($dell_e_fild))
					foreach ($dell_e_fild as $e=>$f){
						un_link("../img/gallery/".$f['id']."_variation.".$f['p1']);
						db_query("DELETE FROM $sql_tbl[extra_filds] WHERE id='$f[id]'");
					}
						
				db_query("DELETE FROM $sql_tbl[products] WHERE id='$a'");
 				unset($posted_data[$a]);
 			}
		//r_print_r($posted_data);
		$i=0;
			foreach ($posted_data as $a=>$v) {
				if (empty($v["active"])) $active='N'; else $active='Y';
				db_query("UPDATE $sql_tbl[products] SET active='$active', orderby='$i' WHERE id='$a'");
				
				if (!empty($v["move"])&&($move_category_value!=""))
				db_query("UPDATE $sql_tbl[products] SET cat='$move_category_value' WHERE id='$a'");	
			$i++;
			}
		}
		header("location:$action_var?cat=$cat");
 }break;
 case "dell_images": {
	//r_print_r($_POST);
	//r_print_r($posted_data);
	//echo $action_image;
	$i=0;
	if ( !empty($posted_data) )
		foreach ($posted_data as $a=>$b){
			if (!empty($b['dell'])){
			if ($action_image=='1')
				db_query("UPDATE $sql_tbl[gallery] SET avail='Y' WHERE id='$a'");
			if ($action_image=='2')
				db_query("UPDATE $sql_tbl[gallery] SET avail='N' WHERE id='$a'");
			if ($action_image=='3'){
				$del=func_query_first("SELECT * FROM $sql_tbl[gallery] WHERE id='$a' ");
				un_link("../img/gallery/".$del['id']."_icon.".$del['icon']);
				un_link("../img/gallery/".$del['id']."_image.".$del['image']);
				
				db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$a'");
				}
			}
			db_query("UPDATE $sql_tbl[gallery] SET orderby='$i', alt='$b[alt]' WHERE id='$a'");
			$i++;
		}
	 header("location:$action_var?cat=$cat&id=$id&mode=show");	
 }break;
 
} 
	$image_cat=$id;
	include '../include/gallery.php'; 
	$query ="SELECT * FROM $sql_tbl[products] WHERE cat='$cat' ";
	require "../include/navigation.php";
	if (!empty($_GET["page_all"])) 
	$slimit="";
	else $slimit="LIMIT $first_page, $objects_per_page";
	$products = func_query ("$query ORDER BY  orderby");//
	if (!empty($products))
		foreach ($products as $a=>$b){
			$images=func_query ("SELECT * FROM $sql_tbl[gallery] WHERE category='$b[id]' and md5='gallery' ORDER BY orderby ASC LIMIT 0,1");
			$images=$images[0];
			if (!empty($images["image"]))
			$products[$a]['icon']="/img/gallery/icons/".$images['id']."_image.".$images["image"];
			else $products[$a]['icon']='';
		}
		
$images=func_query ("SELECT * FROM $sql_tbl[gallery] WHERE category='$id' and md5='gallery' ORDER BY orderby ASC ");//LIMIT 0,1
if (!empty($images))
	foreach ($images as $a=>$b){
		$images[$a]['icon']="/img/gallery/icons/".$b['id']."_image.".$b["image"];
		$images[$a]['image']="/img/gallery/".$b['id']."_image.".$b["image"];
	}
/*
$filelist = glob("../tmp/*.jpg");
foreach($filelist as $a=>$b){
	$new_name=str_replace('_327','_lens',$b);
	rename ($b,$new_name);
}*/

//r_print_r($filelist);
if (empty($posted_data)){
	$posted_data["url"]="";
	$posted_data["partner_price"]="0.0";
	$posted_data["type"]="";
	$posted_data["name"]="";
	$posted_data["p1"]="";
	$posted_data["p2"]="";
	$posted_data["p3"]="";
	$posted_data["p4"]="";
	$posted_data["p5"]="";
	$posted_data["p6"]="";
	$posted_data["p7"]="";
	$posted_data["price"]="0.0";
	$posted_data["description"]="";
	
	$posted_data["meta_description"]="";
	$posted_data["meta_keywords"]="";
	$posted_data["title"]="";
	
}

function array_show_tree($ParentID,$tbl) {
global $link,$sql_tbl,$arrow;
	$sSQL = "SELECT categoryid_path,categoryid, category, parentid FROM $tbl WHERE parentid='$ParentID' AND avail='N' ORDER BY order_by";
	$result = db_query($sSQL, $link);
	if (mysql_num_rows($result) > 0) {
			while ( $row = mysql_fetch_array($result) ) {
				$categoryid_path=explode("/",$row["categoryid_path"]);
				$tname='';
				foreach ($categoryid_path as $ac=>$ab){
					if ($ac>0) $tname.="/".func_query_first_cell("SELECT category FROM $tbl where categoryid='$ab' ");
					else $tname=func_query_first_cell("SELECT category FROM $tbl where categoryid='$ab' ");
				}
				$ID1 = $row["categoryid"];
				$arrow[$ID1]=$tname;
			array_show_tree($ID1,$tbl);
		}
	}
}

$_type=func_query("SELECT DISTINCT type FROM $sql_tbl[products] ");
$type=array();
if (!empty($_type))
foreach ($_type as $a=>$b){
	if ($b['type']=="")
		 $type[""]=$b['type'];
	else $type[$b['type']]=$b['type'];
}


if (!empty($eid))
{
	$extrafilds=func_query_first("SELECT * FROM $sql_tbl[extra_filds] WHERE id='$eid' ");
	$extrafilds['image']="../img/gallery/".$extrafilds['id']."_variation.".$extrafilds['p1'];
	$extrafilds['mode']="update_extra_filds";
}

if (empty($extrafilds)){
	$extrafilds['partner_price']='';
	$extrafilds['price']='';
	$extrafilds['name']='';
	$extrafilds['description']='';
	$extrafilds['mode']="add_extra_filds";
}


$arrow=Array();
$arrow[""]=" ";
array_show_tree($start_cat,$sql_tbl['categories']);
$move_categoryes=$arrow;
$title='Каталог';
include "../tpl/admin/home.tpl";
?>