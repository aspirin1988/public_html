<?php
function image_action($name,$fildname,$id){
global $sql_tbl;
$img_type="";
	if ( isset($_FILES[$name])&&($_FILES[$name]["error"]==0) ){
		$img_type=image_type($_FILES[$name]["type"]);
		$_img_type=$_FILES[$name]["type"];
		put_file($_FILES[$name]["tmp_name"],"../img/gallery/".$id."_$fildname.".$img_type);
		db_query("UPDATE $sql_tbl[gallery] SET $fildname='$img_type', ".$fildname."_type='$_img_type' WHERE id='$id'");
	}
	return $img_type;
}
	
switch ($mode){
    case "add_images":{
    	if ( !empty($_FILES) ){
			for ($i=0;$i<5;$i++){
			 	if ( isset($_FILES["image".$i])&&($_FILES["image".$i]["error"]==0) )
				{
				if (empty($posted_data["alt".$i])) $alt=$posted_data["alt".$i];
				db_query("INSERT INTO $sql_tbl[gallery] (date,category,alt,avail) VALUES('".time()."','$image_cat','$alt','N')");
				$id=db_insert_id();
				image_action("image".$i,"image",$id);
				}
				if (isset($_FILES["icon".$i])&&($_FILES["icon".$i]["error"]==0))
				image_action("icon".$i,"icon",$id);
			}
    		  header("location:$action_var?cat=$cat&id=$image_cat&mode=show");
    	}else header("location:$action_var");
    }break;
case "update_image":{
	
	if (!empty($_FILES["image"])&&(!$_FILES["image"]["error"]))
	image_action("image","image",$posted_data['id']);

	if (!empty($_FILES["icon"])&&(!$_FILES["icon"]["error"]))
	image_action("icon","icon",$posted_data['id']);
	
	if (!empty($posted_data['alt'])){
		db_query("UPDATE $sql_tbl[gallery] SET alt='$posted_data[alt]' WHERE id='$posted_data[id]'");
	}	
    
	header("location:$action_var?cat=$cat&id=$id&mode=edit");
	
}break;


case "update_all_images":{
	if ( !empty($posted_data) ){
		$m_action=get_post_var("action_mode");
		
		if (!empty($_POST["m_category"])) $m_category=$_POST["m_category"];
    	else $m_category="0";
		
		foreach ($posted_data as $a=>$v) 
		{
		$move="";
		$avail="";
			
			if (!empty($v["item"])&&($m_action=="move")) $move=" ,category='$m_category'"; 
			if (!empty($v["item"])&&($m_action=="hide")) $avail=" ,avail='Y'"; 
			if (!empty($v["item"])&&($m_action=="show")) $avail=" ,avail='N'"; 

			db_query("UPDATE $sql_tbl[gallery] SET orderby='$v[orderby]' $avail $move WHERE id='$a'");	
	
			if (!empty($v["item"])&&($m_action=="delete")){
				$del=func_query_first("SELECT * FROM $sql_tbl[gallery] WHERE id='$a' ");
				
				//r_print_r($del);
			
				un_link("../img/gallery/".$del['id']."_icon.".$del['icon']);
				un_link("../img/gallery/".$del['id']."_image.".$del['image']);
					
				db_query("DELETE FROM $sql_tbl[gallery] WHERE id='$a'");
			}
		}
	
	}

	header("location:$action_var?cat=$cat");
    }break;
case "edit_image":{
    $posted_data=func_query_first ("SELECT * FROM $sql_tbl[gallery] where id='$id'");
	
		$posted_data["image"]="/img/gallery/".$posted_data['id']."_image.".$posted_data["image"];
		$posted_data["icon"]="/img/gallery/".$posted_data['id']."_icon.".$posted_data["icon"];
	
    $actionmode="update";
    }break;
}    

$arrow=Array();
arrow_show_tree(0,$sql_tbl['categories']);
$all_categories=$arrow;

$images = func_query ("SELECT * FROM $sql_tbl[gallery] WHERE category='$id' ORDER BY orderby ASC");//DESC
if (!empty($images))
	foreach ($images as $a=>$v){
		$images[$a]["image"]="/img/gallery/".$v['id']."_image.".$v["image"];
		if ($v["icon"]=='')
				 $images[$a]["icon"]="/img/gallery/icons/".$v['id']."_image.".$v["image"];
			else $images[$a]["icon"]="/img/gallery/".$v['id']."_icon.".$v["icon"];
	}
	
?>