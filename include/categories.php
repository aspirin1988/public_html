<?php
$actionmode="add";
$mode=get_post_var("mode");
$posted_data=get_post_var("posted_data");
$posted_data=get_post_var("posted_data");

if (!empty($posted_data)&&($mode!='dell') ){
	$posted_data["category"]=mysql_real_escape_string($posted_data["category"]);
	$posted_data["description"]=mysql_real_escape_string($posted_data["description"]);
	$posted_data["alt"]='';//mysql_real_escape_string($posted_data["alt"])
	$posted_data["meta_descr"]=mysql_real_escape_string($posted_data["meta_descr"]);
	$posted_data["meta_keywords"]=mysql_real_escape_string($posted_data["meta_keywords"]);
	$posted_data["title"]=mysql_real_escape_string($posted_data["title"]);
}


$cat=get_get_var("cat");
if (!empty($_POST["cat"])) $cat=$_POST["cat"];
if (!empty($_GET["mode"])) $mode=$_GET["mode"];

if (!empty($posted_data["avail"])) $avail='Y'; else $avail='N';
if (!empty($posted_data["comments"])) $comments='Y'; else $comments='N';

function update_categoryid_path()
{
global $sqltbl;
$subcats = func_query("SELECT * FROM $sqltbl WHERE 1 order by parentid");

 foreach ($subcats as $a=>$b){
	if ($b["parentid"] == 0)
			 $parent_categoryid_path = $b["categoryid"];
		else $parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sqltbl WHERE categoryid='$b[parentid]'")."/".$b["categoryid"];
		
	db_query("UPDATE $sqltbl SET categoryid_path='$parent_categoryid_path' WHERE categoryid='$b[categoryid]'");
 }

}

switch ($mode) {
case "update":{
	if (is_array($posted_data)){
		
		$location=func_query_first_cell("SELECT location FROM $sqltbl WHERE categoryid='$posted_data[parent]'");
		if ($posted_data["location"]=='') {
			$location=str_replace(".php","/",$location);
			$location=str_replace(".html","/",$location);
			if ($location{strlen($location)-1}!='/') $location=$location.'/';
			$posted_data["location"]=$location.translit($posted_data["category"])."/"; //.html
		}
		
		if ($posted_data["parent"] == 0)
			 $parent_categoryid_path = $posted_data["categoryid"];
		else $parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sqltbl WHERE categoryid='$posted_data[parent]'")."/".$posted_data["categoryid"];
		
		
		if (!empty($posted_data["subcategories"])) 
				  $subcategories=" subcategories='Y', ";
			 else $subcategories=" subcategories='N', ";
		
		if (!empty($posted_data["showicon"])) 
				  $showicon=" showicon='Y', ";
			 else $showicon=" showicon='N', ";	 
		if (!empty($posted_data["readalso"])) 
				  $readalso=" readalso='Y', ";
			 else $readalso=" readalso='N', ";	 	 
		
		db_query("UPDATE $sqltbl SET date='".time()."', $readalso $showicon $subcategories location='$posted_data[location]', parentid='$posted_data[parent]', categoryid_path='$parent_categoryid_path', category='$posted_data[category]', avail='$avail', description='$posted_data[description]', meta_descr='".htmlspecialchars($posted_data["meta_descr"])."', meta_keywords='".htmlspecialchars($posted_data["meta_keywords"])."', title='$posted_data[title]' WHERE categoryid='$posted_data[categoryid]'");
		
		update_categoryid_path();
		
		if ( isset($_FILES['category_icon'])&&($_FILES['category_icon']["error"]==0) ){
			$img_type=image_type($_FILES['category_icon']["type"]);
			$id=$posted_data['categoryid'];
			image_resize("../img/categories/".$id."_image.".$img_type,$_FILES['category_icon']["tmp_name"],0,640,75);
			db_query("UPDATE $sql_tbl[categories] SET icon='$img_type' WHERE categoryid='$posted_data[categoryid]'");
		}
			if (!empty($posted_data['dell_icon'] )){
				$dell_image = func_query_first_cell("SELECT icon FROM $sqltbl WHERE categoryid='$posted_data[categoryid]'");
				un_link("../img/categories/".$posted_data['categoryid']."_image.".$dell_image);
				un_link("../img/categories/icons/".$posted_data['categoryid']."_image.".$dell_image);
				db_query("UPDATE $sql_tbl[categories] SET icon='' WHERE categoryid='$posted_data[categoryid]'");
			}
		
		}	
		header("Location:$action_var?cat=$posted_data[categoryid]&mode=edit");
		//header("Location:$action_var"); 
	}break;
	case "edit":{
	$posted_data=func_query_first("SELECT * FROM $sqltbl WHERE categoryid='$cat'");
	if (!empty($posted_data)) $actionmode="update"; else $actionmode="add";
	}break;
	case "add":{
	if ($posted_data["category"]!=""){
			
		db_query("INSERT INTO $sqltbl (parentid,category,description,avail,alt,meta_descr,meta_keywords,title) VALUES ('$posted_data[parent]','".htmlspecialchars($posted_data["category"])."','$posted_data[description]','$avail','$posted_data[alt]','$posted_data[meta_descr]','$posted_data[meta_keywords]','$posted_data[title]')");
			$cat = db_insert_id();
		
		$location=func_query_first_cell("SELECT location FROM $sqltbl WHERE categoryid='$posted_data[parent]'");
		if ($posted_data["location"]=='') {
			$location=str_replace(".php","/",$location);
			$location=str_replace(".html","/",$location);
			$posted_data["location"]=$location.translit($posted_data["category"])."/";//.html
		}
			db_query("UPDATE $sqltbl SET date='".time()."', location='$posted_data[location]' WHERE categoryid='$cat'");
			if ($posted_data["parent"] == 0)
					$parent_categoryid_path = $cat;
				else $parent_categoryid_path = func_query_first_cell("SELECT categoryid_path FROM $sqltbl WHERE categoryid='$posted_data[parent]'")."/".$cat;
				db_query("UPDATE $sqltbl SET parentid='$posted_data[parent]', categoryid_path='$parent_categoryid_path' WHERE categoryid='$cat'");
		}
	header("Location:$action_var");
	}break;
case "dell":{
if (is_array($posted_data))
		foreach ($posted_data as $p=>$v) {
			if (!empty($v["avail"])) 
				  $avail=" ,avail='Y' ";
			 else $avail=" ,avail='N' ";
			
			if (!empty($v["comments"])) 
				  $comments=" ,comments='Y' ";
			 else $comments=" ,comments='N' ";
			
			db_query("UPDATE $sqltbl SET order_by='$v[order_by]' $avail $comments WHERE categoryid='$p'");
			}
	}break;				
}

if (empty($posted_data)){
 	$posted_data["category"]="";
 	$posted_data["description"]="";
 	$posted_data["meta_descr"]="";
 	$posted_data["meta_keywords"]="";
 	$posted_data["alt"]="";
 	$posted_data["avail"]='N';
 	$posted_data["categoryid"]=0;
 	$posted_data["parentid"]=0;
 	$posted_data["title"]="";
 	$posted_data["location"]="";
 	}
 
$arrow=Array();
$arrow[""]="";
arrow_show_tree(0,$sql_tbl['categories']);
$all_categories=$arrow;
?>