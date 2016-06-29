<?
include_once "navigation_class.php";


$gallery_mode="imageout";
if (!empty($requestUri[2])){
	if ($requestUri[2]=="gallery"){
		$text_page['text']='';
		if ( is_numeric($requestUri[3]) ){
			$gallery = func_query_first ("SELECT * FROM $sql_tbl[albums] WHERE categoryid='".$requestUri[3]."' ORDER BY order_by ");
			
			$text_page['title']=$gallery['title'];
			if ($text_page['title']=='') $text_page['title']=$gallery['category'];
			
			$gallery['images'] = func_query ("SELECT * FROM $sql_tbl[gallery] WHERE category='".$requestUri[3]."' and md5='gallery' ORDER BY orderby ");
			
			if (!empty($gallery['images']))
				foreach ($gallery['images'] as $a=>$v){
					$gallery['images'][$a]["image"]=$v['id'].'_image.'.$v['image'];
				}
			$text_page['nav']="<a href='/press/gallery/'>Фотогалерея</a> » <a href='/press/gallery/".$requestUri[3]."'>".$text_page['title']."</a>";
		}else {
			$gallery_mode="category_imageout";
			$text_page['title']=$lng['lbl_gallery'];
		}
		
	$locationvar=$requestUri[2];
	$__root_category_data=func_query_first ("SELECT * FROM $sql_tbl[categories] WHERE location='/ulsrc/'");
	
	$text_page['category_root_data']['title']=$__root_category_data['title'];
	$text_page['description']=$__root_category_data['description'];
	$text_page['meta_keywords']=$__root_category_data['meta_keywords'];
	$text_page['meta_descr']=$__root_category_data['meta_descr'];
	
	}
}
?>