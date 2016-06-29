<?php
session_start(); 
include "localconf.php";
$action_var="catalogue.php";

$actionmode="add";
$root_catalog_category=func_query_first ("SELECT * FROM $sql_tbl[categories] where location LIKE 'catalogue/' ");

$mode=get_get_var("mode");
if (!empty($_POST["mode"]))	$mode=$_POST["mode"];

if (!empty($_POST["cid"]))	$cid=$_POST["cid"]; else $cid=0;
if (!empty($_GET["cid"]))	$cid=$_GET["cid"];
if (!empty($_POST["page"]))	$page=$_POST["page"];


$cat=get_get_var("cat");
if (!empty($_POST["cat"]))	$cat=$_POST["cat"];

$posted_data=get_post_var("posted_data");

$dimage="";
$dicon="";
$show_fragments=false;
$ajax=get_get_var("ajax");
switch ($mode) {
	case "update_table":{
		$valuexy='';
		$c_color='';
		$c_value='';
		if ($posted_data['size_type']=="s" ){
			$valuexy=implode('|sc_x|',$posted_data['sc_x']).'|rows|'.implode('|sc_y|',$posted_data['sc_y']).'|rows|'.$posted_data['s_xy'];
			$c_color=implode(';',$posted_data['sc_color']);
			$c_value=implode(';',$posted_data['s_size']);
		}
		if ($posted_data['size_type']=="m" ){
			$valuexy=implode('|sc_x|',$posted_data['mc_x']).'|rows|'.implode('|sc_y|',$posted_data['mc_y']).'|rows|'.$posted_data['m_xy'];
			$c_color=implode(';',$posted_data['mc_color']);
			$c_value=implode(';',$posted_data['m_size']);
		}	
		
		db_query("UPDATE $sql_tbl[catalogue] SET valuexy='$valuexy', c_color='$c_color', c_value='$c_value', c_type='$posted_data[size_type]' WHERE id='$cid'");
		header("Location:$action_var?cat=$cat&mode=edit&cid=$cid#fragment-3");
		
	}break;
	case "update_group":{
		$i=0;
		if (is_array($posted_data)) 
		foreach ($posted_data as $p=>$v){
			if (!empty($v['dell'])){
				$del=func_query_first("SELECT * FROM $sql_tbl[gallery] WHERE id='$p' ");
					if (!empty($del)){
					$del['image']=$del['id']."_image.".$del['image'];
					un_link("../img/gallery/".$del['image']);
					db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$p'");
					unset($posted_data[$p]);
					}
			}else {
				db_query("UPDATE $sql_tbl[gallery] SET alt='".m_specialchars($v['alt'])."', orderby='$i'  WHERE id='$p'");
				$i++;
			}
		}	
		header("Location:$action_var?cat=$cat&mode=edit&cid=$cid#fragment-2");
	}break;
	case "addclone":{
	$merror='';
	if (!empty($posted_data["active"])) $posted_data["active"]="Y"; else $posted_data["active"]="N";
	if (empty($posted_data['article']))	$merror.='<li>Введите артикул</li>';
	
	$id='0';
	if ($merror==''){
		$url=translit($posted_data['article']).".html";
		db_query("INSERT INTO $sql_tbl[catalogue] (url,cat,article,description,active,t_price,model,price_a,price_b,price_c,t_price_t,t_price_a,t_price_b,t_price_c) VALUES ('$url','$cat','".m_specialchars($posted_data['article'])."','$posted_data[description]','$posted_data[active]','$posted_data[t_price]','".m_specialchars($posted_data['model'])."','$posted_data[price_a]','$posted_data[price_b]','$posted_data[price_c]','$posted_data[t_price_t]','$posted_data[t_price_a]','$posted_data[t_price_b]','$posted_data[t_price_c]')");
		$id=db_insert_id();
		
		$posted_data=func_query_first ("SELECT * FROM $sql_tbl[catalogue] where id='$cid'");
		db_query("UPDATE $sql_tbl[catalogue] SET valuexy='$posted_data[valuexy]', c_color='$posted_data[c_color]', c_value='$posted_data[c_value]', c_type='$posted_data[c_type]' WHERE id='$id'");
		
		if 	($ajax=='') header("Location:$action_var?cat=$cat&cid=$id&mode=edit&m=1");
	}
	if ($merror=='') $merror='ok'; else $merror='<ul>'.$merror.'</ul>';

	}break;
	case "add":{
	$merror='';
	if (!empty($posted_data["active"])) $posted_data["active"]="Y"; else $posted_data["active"]="N";
	if (empty($posted_data['article']))	$merror.='<li>Введите артикул</li>';
	
	$id='0';
	if ($merror==''){
		$url=translit($posted_data['article']).".html";//."_".$posted_data['model']
		db_query("INSERT INTO $sql_tbl[catalogue] (url,cat,article,description,active,t_price,model,price_a,price_b,price_c,t_price_t,t_price_a,t_price_b,t_price_c) VALUES ('$url','$cat','".m_specialchars($posted_data['article'])."','$posted_data[description]','$posted_data[active]','$posted_data[t_price]','".m_specialchars($posted_data['model'])."','$posted_data[price_a]','$posted_data[price_b]','$posted_data[price_c]','$posted_data[t_price_t]','$posted_data[t_price_a]','$posted_data[t_price_b]','$posted_data[t_price_c]')");
		$id=db_insert_id();
		if 	($ajax=='') header("Location:$action_var?cat=$cat&cid=$id&mode=edit&m=1");
	}
	if ($merror=='') $merror='ok'; else $merror='<ul>'.$merror.'</ul>';
	}break;
case "all_update":{	
	if (is_array($posted_data)){
		$mcat=$posted_data['mcat'];
		foreach ($posted_data as $a=>$v) 
			if (!empty($v["del"])){
			$del_img=func_query ("SELECT * FROM $sql_tbl[gallery] WHERE md5='catalogue' and category='$a'");
			if (!empty($del_img))
			{
				foreach ($del_img as $c=>$d)
				if (!empty($d["image"])) un_link("../images/gallery/".$d["id"]."_image.".$d["image"]);
				db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$d[id]'");
			}
				
				db_query("DELETE FROM $sql_tbl[catalogue] WHERE id='$a'");
 				unset($posted_data[$a]);
 			}
			$i=0;
			foreach ($posted_data as $a=>$v) {
				$i++;
				
				if (empty($v["new"])) $new='N'; else $new='Y';
				
				if (empty($v["active"])) $active='N'; else $active='Y';
				db_query("UPDATE $sql_tbl[catalogue] SET new='$new', active='$active', order_by='$i' WHERE id='$a'");
				
				if (!empty($v["mv"])) 
				db_query("UPDATE $sql_tbl[catalogue] SET cat='$mcat' WHERE id='$a'");
			}
	}
	
    header("location:$action_var?cat=$cat");
}break;
case "update":{
    if ( !empty($posted_data) ){
    	
    	if (!empty($posted_data["active"])) $posted_data["active"]="Y"; else $posted_data["active"]="N";
    	
   		$c_value="";
   		$c_color="";
   		$c_type="";
    		
   		if (!empty($posted_data["size_type"])){
   			$c_type=" ,c_type='".$posted_data["size_type"]."'";
   			if ($posted_data["size_type"]=="m"){
    				$c_color=", c_color='".implode (";",$posted_data["mc_color"])."'";
    				$c_value=", c_value='".implode (";",$posted_data["m_size"])."'";
   			}
   			if ($posted_data["size_type"]=="s"){
    				$c_color=", c_color='".implode (";",$posted_data["sc_color"])."'";
    				$c_value=", c_value='".implode (";",$posted_data["s_size"])."'";
   			}
    			
    		}
			if (empty($posted_data['url']))
			$posted_data['url']=translit($posted_data['article']).".html";//."_".$posted_data['model']
    		db_query("UPDATE $sql_tbl[catalogue] SET  url='$posted_data[url]', t_price='$posted_data[t_price]', article='".m_specialchars($posted_data['article'])."', description='$posted_data[description]', active='$posted_data[active]', model='".m_specialchars($posted_data['model'])."', price_a='$posted_data[price_a]', price_b='$posted_data[price_b]', price_c='$posted_data[price_c]', t_price_t='$posted_data[t_price_t]', t_price_a='$posted_data[t_price_a]', t_price_b='$posted_data[t_price_b]', t_price_c='$posted_data[t_price_c]' $c_value $c_color $c_type WHERE id='$cid'");
			//, cat='$posted_data[cat]'

    	header("location:$action_var?cid=$cid&cat=$cat&mode=edit&m=2");
    	}else header("location:$action_var");
}break;

case "edit":{
    $posted_data=func_query_first ("SELECT * FROM $sql_tbl[catalogue] where id='$cid'");
    
    if ($posted_data["c_value"]=="")
    for ($i=0;$i<255;$i++) $posted_data["c_value"][$i]=0;
    else $posted_data["c_value"]=explode (";",$posted_data["c_value"]);
    
    if ($posted_data["c_color"]=="")
    	 for ($i=0;$i<255;$i++) $posted_data["c_color"][$i]="1";
    else $posted_data["c_color"]=explode (";",$posted_data["c_color"]);
    $valuexy=explode('|rows|',$posted_data['valuexy']);
	if (!empty($valuexy[0])) $posted_data['valuex']=explode('|sc_x|',$valuexy[0]);
	if (!empty($valuexy[1])) $posted_data['valuey']=explode('|sc_y|',$valuexy[1]);
	
	
	if (empty($valuexy[2]))	$posted_data['xy']=''; else $posted_data['xy']=$valuexy[2];
	
    $actionmode="update";
    $show_fragments=true;
    }break;
case "clone":{
    $posted_data=func_query_first ("SELECT * FROM $sql_tbl[catalogue] where id='$cid'");
    
    if ($posted_data["c_value"]=="")
    for ($i=0;$i<255;$i++) $posted_data["c_value"][$i]=0;
    else $posted_data["c_value"]=explode (";",$posted_data["c_value"]);
    
    if ($posted_data["c_color"]=="")
    	 for ($i=0;$i<255;$i++) $posted_data["c_color"][$i]="1";
    else $posted_data["c_color"]=explode (";",$posted_data["c_color"]);
    $valuexy=explode('|rows|',$posted_data['valuexy']);
	if (!empty($valuexy[0])) $posted_data['valuex']=explode('|sc_x|',$valuexy[0]);
	if (!empty($valuexy[1])) $posted_data['valuey']=explode('|sc_y|',$valuexy[1]);
	
	
	if (empty($valuexy[2]))	$posted_data['xy']=''; else $posted_data['xy']=$valuexy[2];
	
    //$actionmode="add";//update
    $show_fragments=true;
    $actionmode="addclone";
    }break;
case "getImageList":{
	$ajax='true';
	echo $imageList;
}break;
case "addclone":{
    if ( !empty($posted_data) ){
    	
    	if (!empty($posted_data["active"])) $posted_data["active"]="Y"; else $posted_data["active"]="N";
		db_query("INSERT INTO $sql_tbl[catalogue] (cat,article) VALUES ('$cat','$posted_data[article]')");
		$t=func_query_first ("SELECT * FROM $sql_tbl[catalogue] where id='$cid'");

		$cid=db_insert_id();
    	
   		$c_value="";
   		$c_color="";
   		$c_type="";
    		
   		if (!empty($posted_data["size_type"])){
    			
    			$c_type=" ,c_type='".$posted_data["size_type"]."'";
    			
    			if ($posted_data["size_type"]=="m"){
    				
    				$c_color=", c_color='".implode (";",$posted_data["mc_color"])."'";
    				$c_value=", c_value='".implode (";",$posted_data["m_size"])."'";
    			}
    			
    			if ($posted_data["size_type"]=="s"){
    				$c_color=", c_color='".implode (";",$posted_data["sc_color"])."'";
    				$c_value=", c_value='".implode (";",$posted_data["s_size"])."'";
    			}
    			
    		}
    		
    		
    		db_query("UPDATE $sql_tbl[catalogue] SET  t_price='$posted_data[t_price]', article='$posted_data[article]',fabrics_of_top='$posted_data[fabrics_of_top]', description='$posted_data[description]',lining='$posted_data[lining]', active='$posted_data[active]', cat='$posted_data[cat]', model='$posted_data[model]', price_a='$posted_data[price_a]', price_b='$posted_data[price_b]', price_c='$posted_data[price_c]', t_price_t='$posted_data[t_price_t]', t_price_a='$posted_data[t_price_a]', t_price_b='$posted_data[t_price_b]', t_price_c='$posted_data[t_price_c]' $c_value $c_color $c_type WHERE id='$cid'");

		$dicon="";
		$dimage="";
		if (!empty($_FILES["image"])&&(!$_FILES["image"]["error"])) {
			$dimage="image='".get_image_data($_FILES["image"]["tmp_name"],775,$_SESSION["login"])."'";
			$dicon=",icon='".get_image_data($_FILES["image"]["tmp_name"],222,$_SESSION["login"])."'";
			
		}else{
			$dimage="image='".addslashes($t["image"])."'";
			$dicon=",icon='".addslashes($t["icon"])."'";

		}
		db_query("UPDATE $sql_tbl[catalogue] SET $dimage $dicon WHERE id='$cid'");
		
		
    	header("location:$action_var?cid=$cid&cat=$posted_data[cat]&mode=edit&page=$page");
    	}else header("location:$action_var");
}break;

case "update_txt_cat":{
	db_query("UPDATE $sql_tbl[languages] SET value='$posted_data[text]' WHERE code='RU' AND name='txt_text_prise'");
    }break;
}    

	$query="SELECT * FROM $sql_tbl[catalogue] WHERE cat='$cat' ";
	require "../include/navigation.php";
	$slimit="LIMIT $first_page, $objects_per_page";
	

	if (!empty($_GET["page_all"])) $slimit="";
	
	$catalogue=func_query ("$query ORDER BY order_by  $slimit");

if (empty($posted_data)){
	$posted_data["image"]="";
	$posted_data["article"]="";
	$posted_data["compound"]="";
	$posted_data["description"]="";
	$posted_data["price"]="";
	$posted_data["active"]="Y";
	$posted_data["cat"]=$cat;
	$posted_data["fabrics_of_top"]="";
	$posted_data["t_price"]="";
	$posted_data["lining"]="";
	$posted_data["model"]="";
	
	$posted_data["price_a"]="";
	$posted_data["price_b"]="";
	$posted_data["price_c"]="";
	
	$posted_data["t_price_t"]="";
	$posted_data["t_price_a"]="";
	$posted_data["t_price_b"]="";
	$posted_data["t_price_c"]="";
	$posted_data["url"]="";
	$posted_data["xy"]="";
}

$prices[0]="";
$prices[1]=$lng["lbl_size"].": 28-30";
$prices[2]=$lng["lbl_size"].": 32,34,36";
$prices[3]=$lng["lbl_size"].": 38,40,42";
$prices[4]=$lng["lbl_size"].": 44,46,48";
$prices[5]=$lng["lbl_size"].": 44-54";
$prices[6]=$lng["lbl_size"].": 58-60";
$prices[7]=$lng["lbl_size"].": 62-70";

$posted_date["text"]=func_query_first_cell("SELECT value FROM $sql_tbl[languages] WHERE code='RU' AND name='txt_text_prise'");

$arrow=Array();
arrow_show_tree($root_catalog_category['categoryid'],$sql_tbl['categories']);
$all_categories=$arrow;


$m=get_get_var("m");
switch ($m){
	case '1':$message_ok='Данные успешно добавлены';break; 
	case '2':$message_ok='Данные успешно обновлены';break;
}

$images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE md5='catalogue' and category='$cid' order by orderby");

$title=$lng["lbl_praislist"];
$locationvar="catalogue";
if ($ajax=="") include "../tpl/admin/home.tpl";
?>