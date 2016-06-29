<?php
$root_catalog_category=func_query_first ("SELECT * FROM $sql_tbl[categories] where location LIKE 'catalogue/' ");

function not_empty_url($url_array){
	if (!empty($url_array)){
			$c=count($url_array);
			for ($i=$c-1;$i!=0;$i--)
				if ( strlen($url_array[$i])>0 ){
					$act_url=$url_array[$i];
					return $act_url;
				}
	}
	return '';
}
	
$requestUri = $_SERVER['REQUEST_URI'];
$p_url=not_empty_url(explode ('/',$requestUri));
$p_url=explode ('?',$p_url);
$p_url=$p_url[0];

$_products_url=func_query ("SELECT * FROM $sql_tbl[catalogue] where 1");
if (!empty($_products_url))
	foreach ($_products_url as $a=>$b){
		if ( ($b['url']==$p_url)&&($p_url!='') ){
			$locationvar='product';
			$commenttype='products';
			$product=$b;
			break;
		}
} 
if (!empty($product)){
	$product['images']=func_query("SELECT * FROM $sql_tbl[gallery] WHERE md5='catalogue' and category='$product[id]' order by orderby");
	$cat=$product['cat'];
	$_category = func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$cat' AND avail='N' ORDER BY order_by");
	$text_page['comments']=$_category['comments'];
	
	$text_page['text']='';
	$text_page["title"]=$product['article'];
    $product["c_color"]=explode (";",$product["c_color"]);
	$product["c_value"]=explode (";",$product["c_value"]);
    $valuexy=explode('|rows|',$product['valuexy']);
	if (!empty($valuexy[0])) $product['valuex']=explode('|sc_x|',$valuexy[0]); else $product['valuex']='';
	if (!empty($valuexy[1])) $product['valuey']=explode('|sc_y|',$valuexy[1]); else $product['valuey']='';
	if (empty($valuexy[2])) $product['xy']=''; else $product['xy']=$valuexy[2];
	
}

$text_page['products']=func_query ("SELECT * FROM $sql_tbl[catalogue] where cat='$cat' and active='Y' ORDER BY order_by ");
if (!empty($text_page['products']))
foreach ($text_page['products'] as $a=>$b){
	$text_page['products'][$a]['images']=func_query("SELECT * FROM $sql_tbl[gallery] WHERE md5='catalogue' and category='$b[id]' order by orderby");
}

function get_procuct_image_recur($cat,&$_img){
global $sql_tbl;
	$__products=func_query ("SELECT a.image as image, a.id as id, b.article as article FROM $sql_tbl[gallery] as a LEFT JOIN $sql_tbl[catalogue] as b ON b.cat='$cat' WHERE a.category=b.id and a.image<>'' order by b.order_by DESC, a.orderby ASC");
	if (empty($__products)){
		$categories = func_query_first("SELECT categoryid, category FROM $sql_tbl[categories] WHERE parentid='$cat' AND avail='N' ORDER BY order_by");
		if (!empty($categories))
		get_procuct_image_recur($categories['categoryid'],$_img);
	}else {
	$_img=$__products[0];
	}
}

$_img=Array();
function simple_recur_announce_catalogue($id){
global $sql_tbl,$simple_announce_catalogue,$empty_categoryes;
	$categories = func_query("SELECT location,categoryid_path,categoryid, category, parentid,meta_descr FROM $sql_tbl[categories] WHERE parentid='$id' AND avail='N' ORDER BY order_by");
	
	if (!empty($categories)){
		//$simple_announce_catalogue.="<ul>";
		foreach ($categories as $a=>$b){
			$images=array();
			get_procuct_image_recur($b['categoryid'],$images);
			
			if (!empty($images))
			{
				if (!empty($images['image']))
					$img='/img/image.php?f=gallery/'.$images['id'].'_image.'.$images['image'].'&amp;h=137';
					else $img='/img/empty_men.jpg';
				$simple_announce_catalogue.='
				<div class="catalog_item">
					<a href="/'.$b['location'].'" title="'.$b['category'].'" class="image_cat">
					<span class="upcatline"></span>
					<img src="'.$img.'" alt="'.$b['category'].'" title="'.$b['category'].'"/>
					<span class="upcatline"></span>
					</a>
					<a href="/'.$b['location'].'" title="'.$b['category'].'" class="cat">
					'.$b['category'].'
					</a>
				</div>';
			}else{
				$img='/img/empty_men.jpg';
				$simple_announce_catalogue.='
				<div class="catalog_item">
					<a href="/'.$b['location'].'" title="'.$b['category'].'" class="image_cat">
					<span class="upcatline"></span>
					<img src="'.$img.'" alt="'.$b['category'].'" title="'.$b['category'].'"/>
					<span class="upcatline"></span>
					</a>
					<a href="/'.$b['location'].'" title="'.$b['category'].'" class="cat">
					'.$b['category'].'
					</a>
				</div>';
			}
			/*
			$products_count=func_query_first_cell("SELECT count(*) FROM $sql_tbl[catalogue] WHERE cat='$b[categoryid]' and active='Y'");	
			//$simple_announce_catalogue.="<li>";
			//$simple_announce_catalogue.='<h2><a href="/'.$b['location'].'" title="'.$b['category'].'">'.$b['category']."</a></h2>";
				if ($products_count!="0"){
					$products=func_query("SELECT * FROM $sql_tbl[catalogue] WHERE cat='$b[categoryid]' and active='Y' order by order_by LIMIT 0,3");
					foreach ($products as $ca=>$cb)
						$images=func_query ("SELECT * FROM $sql_tbl[gallery] WHERE category='$cb[id]' ORDER BY orderby ASC");
						$_products['products']=$products;
						$_products['url']=$b['location'];
						$_products['product_count']=$products_count;
				
				if (!empty($images[0]['image']))
					$img='/img/image.php?f=gallery/'.$images[0]['id'].'_image.'.$images[0]['image'].'&amp;h=137';
					else $img='/img/empty_men.jpg';
				$simple_announce_catalogue.='
				<div class="catalog_item">
					<a href="/'.$b['location'].'" title="'.$b['category'].'" class="image_cat">
					<span class="upcatline"></span>
					<img src="'.$img.'" alt="'.$b['category'].'" title="'.$b['category'].'"/>
					<span class="upcatline"></span>
					</a>
					<a href="/'.$b['location'].'" title="'.$b['category'].'" class="cat">
					'.$b['category'].'
					</a>
				</div>';
				}
				unset($products);
				if ($products_count=="0"){
					simple_recur_announce_catalogue($b['categoryid']); 
				}else{
				 
				 $empty_categoryes[]=$b['categoryid'];
				}
				*/
		}
		//$announce_catalogue[]="</ul>";
	}	
}

function recur_announce_catalogue($id){
global $sql_tbl,$announce_catalogue,$d;
	$d++;
	$categories = func_query("SELECT location,categoryid_path,categoryid, category, parentid FROM $sql_tbl[categories] WHERE parentid='$id' AND avail='N' ORDER BY order_by");
	r_print_r($categories);
	if (!empty($categories)){
		$announce_catalogue[]="<ul>";
		foreach ($categories as $a=>$b){
			$products_count=func_query_first_cell("SELECT count(*) FROM $sql_tbl[catalogue] WHERE cat='$b[categoryid]' and active='Y'");	
			$announce_catalogue[]="<li>";
			$announce_catalogue[]='<h2><a href="/'.$b['location'].'" title="'.$b['category'].'">'.$b['category']."</a></h2>";
				
				if ($products_count!="0"){
					$products=func_query("SELECT * FROM $sql_tbl[catalogue] WHERE cat='$b[categoryid]' and active='Y' order by order_by DESC LIMIT 0,3");
					foreach ($products as $ca=>$cb)
						$products[$ca]['images']=func_query ("SELECT * FROM $sql_tbl[gallery] WHERE category='$cb[id]' ORDER BY orderby ASC");
				$_products['products']=$products;
				$_products['url']=$b['location'];
				$_products['product_count']=$products_count;
				$announce_catalogue[]=$_products;
				}
				unset($products);
				if ($products_count=="0") recur_announce_catalogue($b['categoryid']); 
				
				$announce_catalogue[]="</li>";
		}
		$announce_catalogue[]="</ul>";
	}	
}
?>