<?php
session_start(); 
include "localconf.php";
$action_var="textpage.php";
$work_lang="EN";

$mode=get_get_var("mode");
if (!empty($_POST["mode"]))	$mode=$_POST["mode"];

$ln="RU";

$cat=get_get_var("cat");
if (!empty($_POST["cat"]))	$cat=$_POST["cat"];

$p_id=get_get_var("p_id");
if (!empty($_POST["p_id"]))	$p_id=$_POST["p_id"];

$posted_date["text"]=get_post_var("text");


if ($mode=="add_article_mode"){
	$articles=get_post_var('articles');
	//r_print_r($articles);
	if ( is_array($articles) )
		$articles=" articles='".implode(';',$articles)."' ";
		else $articles=" articles='' ";
	db_query("UPDATE $sql_tbl[textpage] SET $articles WHERE pageid='$p_id' ");	
	//echo "UPDATE $sql_tbl[textpage] SET $articles WHERE pageid='$p_id' ";
	
	header("location:$action_var?cat=$cat&p_id=$p_id&mode=edit");
	//r_print_r($articles);
}
if ($mode=="updatearticletext"){
	$p_data=get_post_var("p_data");
	if (!empty($p_data))
		foreach ($p_data as $a=>$b){
			//echo "UPDATE $sql_tbl[textpage] SET avail='Y' WHERE pageid='$a'";
			if (!empty($b['avail']))
			db_query("UPDATE $sql_tbl[textpage] SET avail='Y' WHERE pageid='$a'");
			else db_query("UPDATE $sql_tbl[textpage] SET avail='N' WHERE pageid='$a'");
			if (!empty($b['dell'])){
				$dell_images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$cat".'|'."$a' and md5='textpage'");
				if (!empty($dell_images))
					foreach	($dell_images as $e=>$m){
						un_link($m['image_path']);
						db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$m[id]'");
					}
				db_query("DELETE FROM $sql_tbl[textpage] WHERE pageid='$a' ");	
			}
		}
	header("location:$action_var?cat=$cat");
}


if ( ($mode=="add_textpage")||($mode=="update_textpage") ){
	$posted_date=get_post_var('posted_date');
	$posted_date['text']=get_post_var("text");
	
	if (!empty($posted_date["subcategories"])) $subcategories=", subcategories='Y' "; else $subcategories=", subcategories='N' ";
	if (!empty($posted_date["showicon"])) $showicon=", showicon='Y' "; else $showicon=", showicon='N' ";	 
	if (!empty($posted_date["readalso"])) $readalso=", readalso='Y' "; else $readalso=", readalso='N' ";	
	
	$posted_date["text"]=mysql_real_escape_string($posted_date["text"]);
	$posted_date["category"]=mysql_real_escape_string($posted_date["category"]);
	$posted_date["location"]=mysql_real_escape_string($posted_date["location"]);
	
	if ( ($posted_date['text']!='') ){
		if ($mode=='add_textpage'){
			db_query("INSERT INTO $sql_tbl[textpage] (parentid) VALUES ('$posted_date[parent]')");	
			$p_id = db_insert_id();	
		}
		
		if ( isset($_FILES['category_icon'])&&($_FILES['category_icon']["error"]==0) ){
			$img_type=image_type($_FILES['category_icon']["type"]);
			$id=$p_id;
			
			$dell_icon=func_query_first_cell("SELECT icon FROM $sql_tbl[textpage] WHERE pageid='$p_id' ");
			un_link('../icon/categories/'.$p_id.'_article.'.$dell_icon);
			
			image_resize("../img/categories/".$id."_article.".$img_type,$_FILES['category_icon']["tmp_name"],0,640,75);
			db_query("UPDATE $sql_tbl[textpage] SET icon='$img_type' WHERE pageid='$p_id' ");
		}
		
		
		if ($posted_date['location']==''){
			$location=func_query_first_cell("SELECT location FROM $sql_tbl[categories] WHERE categoryid='$cat'");
		
			$location=str_replace(".php","/",$location);
			$location=str_replace(".html","/",$location);
			if ($location{strlen($location)-1}!='/') $location=$location.'/';
			$posted_date["location"]=$location.translit($posted_date["category"]).".html";
		}
	//echo "UPDATE $sql_tbl[textpage] SET parentid='$posted_date[parent]', description='$posted_date[description]', text='$posted_date[text]', meta_descr='$posted_date[meta_descr]', meta_keywords='$posted_date[meta_keywords]',title='$posted_date[title]', type='$posted_date[type]', category='$posted_date[category]', location='$posted_date[location]' $subcategories $showicon $readalso WHERE pageid='$p_id'";
	db_query("UPDATE $sql_tbl[textpage] SET parentid='$posted_date[parent]', description='$posted_date[description]', text='$posted_date[text]', meta_descr='$posted_date[meta_descr]', meta_keywords='$posted_date[meta_keywords]',title='$posted_date[title]', type='$posted_date[type]', category='$posted_date[category]', location='$posted_date[location]' $subcategories $showicon $readalso WHERE pageid='$p_id'");
	
	
	}
	
	$posted_data_img=get_post_var("posted_date_img");
	if ( !empty($posted_data_img) ){
		$m_action=get_post_var("action_mode");
		
		if (!empty($_POST["m_category"])) $m_category=$_POST["m_category"];
    	else $m_category="0";
		$i=0;
		foreach ($posted_data_img as $a=>$v) 
		{
		$move="";
		$avail="";
			
			if (!empty($v["item"])&&($m_action=="move")) $move=" ,category='$m_category'"; 
			if (!empty($v["item"])&&($m_action=="hide")) $avail=" ,avail='N'"; 
			if (!empty($v["item"])&&($m_action=="show")) $avail=" ,avail='Y'"; 

			db_query("UPDATE $sql_tbl[gallery] SET orderby='$i', alt='$v[alt]' $avail $move WHERE id='$a'");	
	
			if (!empty($v["item"])&&($m_action=="delete")){
				$del=func_query_first("SELECT * FROM $sql_tbl[gallery] WHERE id='$a' ");
	
				un_link($del['image_path']);
				db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$a'");
			}
			$i++;
		}
	}
	//header("location:$action_var?cat=$posted_date[parent]&p_id=$p_id&mode=edit");
	
}
if ($mode=="update"){
	
	/*if ($posted_date["type"]=='textpage'){
		$posted_date["text"]=mysql_real_escape_string($posted_date["text"]);
		$posted_date["category"]=mysql_real_escape_string($posted_date["category"]);
		$posted_date["location"]=mysql_real_escape_string($posted_date["location"]);
		
		db_query("UPDATE $sql_tbl[textpage] SET text='$posted_date[text]', meta_descr='$posted_date[meta_descr]',meta_keywords='$posted_date[meta_keywords]',title='$posted_date[title]',type='$posted_date[type]', category='$posted_date[category]', location='$posted_date[location]' WHERE parentid='$p_id'");
	}*/
	
	if (func_query_first_cell("SELECT count(*) FROM $sql_tbl[textpage] WHERE parentid='$cat'")==0)
		 db_query("INSERT INTO $sql_tbl[textpage] (parentid,text) VALUES ('$cat', '".mysql_real_escape_string($posted_date["text"])."')");
		 else db_query("UPDATE $sql_tbl[textpage] SET text='".mysql_real_escape_string($posted_date["text"])."' WHERE parentid='$cat'");
	$posted_data=get_post_var("posted_data");
	$posted_data_img=get_post_var("posted_date_img");
	
	if ( !empty($posted_data_img) ){
		$m_action=get_post_var("action_mode");
		
		if (!empty($_POST["m_category"])) $m_category=$_POST["m_category"];
    	else $m_category="0";
		$i=0;
		foreach ($posted_data_img as $a=>$v){
			$move="";
			$avail="";
			
			if (!empty($v["item"])&&($m_action=="move")) $move=" ,category='$m_category'"; 
			if (!empty($v["item"])&&($m_action=="hide")) $avail=" ,avail='N'"; 
			if (!empty($v["item"])&&($m_action=="show")) $avail=" ,avail='Y'"; 

			db_query("UPDATE $sql_tbl[gallery] SET orderby='$i', alt='$v[alt]' $avail $move WHERE id='$a'");	
	
			if (!empty($v["item"])&&($m_action=="delete")){
				$del=func_query_first("SELECT * FROM $sql_tbl[gallery] WHERE id='$a' ");
	
				un_link($del['image_path']);
				db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$a'");
			}
			$i++;
		}
	}
	$posted_data["categoryid"]=$cat;
	
	$_POST["posted_data"]=$_POST["posted_date"];
	$_POST["posted_data"]["categoryid"]=$cat;
	
	$sqltbl = $sql_tbl["categories"];
	include "../include/categories.php";
	//if ($posted_date["type"]=='category'){}
	
}
$posted_date['type']='textpage';

$actionmode="add";
if ($cat!=""){
	
	$posted_date=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$cat' ");
	$posted_date['text']=func_query_first_cell("SELECT text FROM $sql_tbl[textpage] WHERE parentid='$cat' ");
	$posted_date['text']=stripcslashes($posted_date['text']);
	$images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$cat' and md5='textpage' order by orderby");
	$posted_date['type']='category';
	$actionmode="update";
		
}

$title=$lng["lbl_text_page"];
$locationvar="textpage";

$arrow=Array();
$arrow["0"]="";
arrow_show_tree(0,$sql_tbl["categories"],false);
$all_categories=$arrow;
unset($arrow);

$action_array=Array();
$action_array[" "]=" ";
$action_array["move"]=$lng["lbl_move"];
$action_array["hide"]=$lng["lbl_hide"];
$action_array["show"]=$lng["lbl_in_hide"];
$action_array["delete"]=$lng["lbl_delete"];

$text_pages=func_query("SELECT * FROM $sql_tbl[textpage] WHERE parentid='$cat'  ");//and type='textpage'
if (!empty($text_pages))
	foreach ($text_pages as $a=>$b)
	if ($b['category']=='')
		$text_pages[$a]['category']=func_query_first_cell("SELECT category FROM $sql_tbl[categories] WHERE categoryid='$b[parentid]'  ");

//r_print_r($text_pages);

$type['category']='Текст раздела';
$type['article']='Статья';

function ab_value($a,$b){
	if ($a=='') return $b; else return $a;
}

if ($p_id!=''){
	$posted_date=func_query_first("SELECT * FROM $sql_tbl[textpage] WHERE pageid='$p_id' ");
	$__d=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$cat' ");
	$posted_date['category']=ab_value($posted_date['category'],$__d['category']);
	$posted_date['location']=ab_value($posted_date['location'],$__d['location']);
	$posted_date['meta_descr']=ab_value($posted_date['meta_descr'],$__d['meta_descr']);
	$posted_date['meta_keywords']=ab_value($posted_date['meta_keywords'],$__d['meta_keywords']);
	$posted_date['title']=ab_value($posted_date['title'],$__d['title']);
	
	$actionmode='update_textpage';
	
	if ($posted_date['type']=='category') $posted_date['parentid']=$__d['categoryid'];
		else $images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$cat".'|'."$p_id' and md5='textpage'");
		//else $posted_date['parentid']=$posted_date['cat'];
	//r_print_r($posted_date);
}

if ( ($p_id.$cat=='')||($mode=='add_article') ){
	
	$actionmode='add_textpage';
	$posted_date["type"]='article';
	$posted_date["text"]='';
	$posted_date["location"]='';
	$posted_date['category']='';
	$posted_date['location']='';
	$posted_date['meta_descr']='';
	$posted_date['description']='';
	$posted_date['subcategories']='';
	$posted_date['showicon']='';
	$posted_date['readalso']='';
	$posted_date['meta_keywords']='';
	$posted_date['title']='';
	$posted_date["parentid"]=$cat;
	$posted_date['avail']='N';
}

//r_print_r($images);
if (!empty($images))
	foreach ($images as $a=>$b)
		$images[$a]['icon']=str_replace('../assets/image/text/','/img/texticons/',$b['image_path']);
		//"/admin/image.php?f=".$b['image_path']."&h=220&w=220";


if (!empty($posted_date['articles'])){
	//echo $posted_date['articles'];
	$articles=explode(';',$posted_date['articles']);
	//r_print_r($articles);
	foreach ($articles as $a=>$b){
		$b=explode('|',$b);
		if ( empty($b[1]) )
				 $articles[$a]=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='".$b[0]."'");
			else {
				$articles[$a]=func_query_first("SELECT * FROM $sql_tbl[textpage] WHERE pageid='".$b[1]."'");
				$articles[$a]['categoryid']=implode('|',$b);
			}
	}
	
}

include "../tpl/admin/home.tpl";
?>