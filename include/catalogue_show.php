<?
function get_product_url($pid){
global $sql_tbl;
	$category=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$pid' ");
	$_location=$category['location'];
	$_location=str_replace('.html','',$_location);
	if ($_location{strlen($_location)-1}!='/') $_location=$_location.'/';
	return $_location;
}

function get_products_list($sql,$limit=''){
global $sql_tbl;
	$products=func_query("SELECT * FROM $sql_tbl[products] WHERE $sql order by orderby ASC $limit");
	if ( !empty($products) )
	foreach ( $products as $a=>$b){
		$products[$a]['url']=get_product_url($b['cat']).$b['url'];
		$_img=func_query("SELECT * FROM $sql_tbl[gallery] WHERE md5='gallery' and category='$b[id]' ORDER BY orderby ASC ");

		if ( !empty($_img) ){
			foreach ($_img as $c=>$d){
				$_img[$c]['image']='/img/gallery/'.$d['id'].'_image.'.$d['image'];
				$_img[$c]['icon']='/img/gallery/icons/'.$d['id'].'_image.'.$d['image'];
			}
			$products[$a]['images']=$_img;
			$products[$a]['image']=$_img[0]['image'];;
			$products[$a]['icon']=$_img[0]['icon'];
		}
	}
	return $products;
}


$all_products=get_products_list(" active='Y'  ",'');

if (!empty($all_products))
	foreach ($all_products as $a=>$b){
		//$all_products[$a]['url']=get_product_url($b['cat']).$b['url'];
		if (str_replace('/','',$_requestUri)==str_replace('/','',$all_products[$a]['url']) ){
			$empty_page=false;
			$product=$b;
			$cat=$b['cat'];
			text_data($b['cat']);
			nav_array($text_page['categoryid_path']);
			
			//r_print_r($text_page);
			$text_page['text']='';
			$text_page['category_root_data']['location']='projects/';
			
			if ($product['title']!='') $text_page['title']=$product['title']; else $text_page["title"]=$product['name'];
			if ($product['meta_description']!='') $text_page['meta_descr']=$product['meta_description'];
			if ($product['meta_keywords']!='') $text_page['meta_keywords']=$product['meta_keywords'];
			
			$text_page['location']='product';
			$locationvar='product';
			$commenttype='products';
			
			$product['nextProduct']='';
			$product['prevProduct']='';
			$nextprev=get_products_list(" active='Y' and cat='$b[cat]' ",'');
			if ( !empty($nextprev) ) 
				foreach ($nextprev as $e=>$f) 
					if ($f['id']==$product['id']){
						if (!empty($nextprev[$e-1])) $product['prevProduct']=$nextprev[$e-1];
						if (!empty($nextprev[$e+1])) $product['nextProduct']=$nextprev[$e+1];
						break;	
					}
			
			break;
		}
	} 
	
	$rep_prd='';
	
	preg_match_all("/object\((.*)\)/",$text_page['text'],$m);
	if (!empty($m)){$m=$m[0];
			foreach ($m as $e=>$q){
				$_q=$q;
				$_q=str_replace('object(','',$_q);
				$_q=str_replace(')','',$_q);
				$rep_prd[]=$_q;
				$_products=get_products_list(" active='Y' and id IN($_q) ",'');
				$pset=srrows("col-sm-2",$_products);
				$text_page['text']=str_replace($q,$pset.'<div class="cls"></div>',$text_page['text']);
			}
		}
	if (!empty($rep_prd)) {
		$rep_prd=implode(',',$rep_prd);
		$rep_prd=" and id not in ($rep_prd) ";
	}
	echo $rep_prd;
	$all_products=get_products_list(" active='Y' and cat='$text_page[categoryid]' $rep_prd ",'');	
?>