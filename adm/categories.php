<?php
session_start(); 
include "localconf.php";
$action_var="categories.php";
$actionmode="add";
$merror='';
$sqltbl=$sql_tbl["categories"];
include "../include/categories.php";


switch ($mode) {
	case "update":{
		//put_file($_FILES[$name]["tmp_name"],"../img/gallery/categories/".$id.".".$img_type);
		
		if (is_array($posted_data)) 
		db_query("UPDATE $sqltbl SET location='$posted_data[location]' WHERE categoryid='$posted_data[categoryid]'");
	}break;
	
	case "add":{
		if (is_array($posted_data)){
		$cat = db_insert_id();
		db_query("UPDATE $sqltbl SET location='$posted_data[location]' WHERE categoryid='$posted_data[categoryid]'");
		}
	}break;
	
	case "dell":{
	if (is_array($posted_data)) 
			foreach ($posted_data as $p=>$v) 
				if (!empty($v["del"]))
				{
				 $cat=$p;
				 $path = explode("/", func_query_first_cell("SELECT categoryid_path FROM $sqltbl WHERE categoryid = '$cat'"));	
				 
				@array_shift($path);
				$catpair = func_query_first("SELECT categoryid_path, parentid FROM $sqltbl WHERE categoryid='$cat'");
				if (!empty($catpair)){
				$categoryid_path = $catpair["categoryid_path"];
				$parent_categoryid = $catpair["parentid"];
				$subcats = func_query("SELECT categoryid FROM $sqltbl WHERE categoryid='$cat' OR categoryid_path LIKE '$categoryid_path/%'");
					if (is_array($subcats))
					while(list($key,$subcat)=each($subcats)) {
						 
						db_query("DELETE FROM $sql_tbl[textpage] WHERE parentid='$subcat[categoryid]'");
						db_query("DELETE FROM $sqltbl WHERE categoryid='$subcat[categoryid]'");
					 }
					}
				 unset($posted_data[$p]);
				 }
		 
		header("Location:$action_var");
	}break;
	
}

$title=$lng["lbl_categories"];
$locationvar="categoryes";
include "../tpl/admin/home.tpl";
?>
