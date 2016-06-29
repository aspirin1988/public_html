<?
session_start();
include "localconf.php";

$mode=get_get_var("mode"); 
$cat=get_get_var("cat"); 

if ($mode=="textpageimagelist"){
	$images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$cat' and md5='textpage' order by orderby");
	if (!empty($images))
	foreach ($images as $a=>$b){
		$images[$a]['icon']="/admin/image.php?f=".$b['image_path']."&h=120";
		echo'<div class="icon">
				<img src="'.$images[$a]['icon'].'"/>
				<br/><textarea name="posted_data['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
				<br/><input name="posted_data['.$b['id'].'][item]" type="checkbox"> 
		</div>';
	}
}
if ($mode=="news"){
	$images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$cat' and md5='news' order by orderby");
	if (!empty($images))
	foreach ($images as $a=>$b){
		$images[$a]['icon']="/admin/image.php?f=".$b['image_path']."&h=120";
		echo'<div class="icon">
				<img src="'.$images[$a]['icon'].'"/>
				<br/><textarea name="posted_data['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
				<br/><input name="posted_data['.$b['id'].'][item]" type="checkbox"> 
		</div>';
	}
}
?>