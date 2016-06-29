<?php
$all_categories=func_query("SELECT * FROM $sql_tbl[categories] WHERE parentid='0' AND avail='N' order by order_by");
$root_category=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE location = '/' ");

$root_category['text']=stripslashes(func_query_first_cell("SELECT text FROM $sql_tbl[textpage] WHERE parentid='$root_category[categoryid]'")); 

$cheсk_set=func_query("SELECT location,categoryid,categoryid_path FROM $sql_tbl[categories] WHERE 1 order by location, categoryid DESC");//avail='N'

$i=-1;
$location_array=Array();$categoryid_array=Array();
$location_array["/"]="1";
foreach ($cheсk_set as $a =>$b){
	if ($b['location']!='')	{
		$location_array[str_replace("/","",$b['location'])]=$b['categoryid'];
		
		if ( !empty($cheсk_set[$a+1])&&($cheсk_set[$a+1]['location']==$b['location']) )
			$location_array[str_replace("/","",$b['location'])]=$cheсk_set[$a+1]['categoryid'];
		
	}	
	$categoryid_array[$a]=$b['categoryid'];
}

$requestUri = explode("?",str_replace('index.php','',$_SERVER['REQUEST_URI']));	
$requestUri=$requestUri[0];
$cat=get_get_var('cat');
$_requestUri=str_replace("/","",$requestUri);
if (!empty($location_array[$_requestUri])) {
	$cat=$location_array[$_requestUri];
	$empty_page=false;
	}else $cat=$root_category['categoryid'];	
	
$requestUri=explode("/",$requestUri);


function text_data($cat){
global $text_page,$sql_tbl,$root_category,$empty_page;

$current_category=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$cat' ");
$croot=explode("/",$current_category["categoryid_path"]);
$current_category['category_root']=$croot[0];
$current_category['category_root_data']=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='".$current_category['category_root']."' ");
$current_category['perent_category_count']=func_query_first_cell("SELECT count(*) FROM $sql_tbl[categories] WHERE parentid='".$current_category['category_root']."' ");

$text_page=$current_category;

$t_page=func_query_first("SELECT text,articles FROM $sql_tbl[textpage] WHERE parentid='$cat' and `type`='category' "); 
if (!empty($t_page)){
	$text_page["text"]=stripslashes($t_page['text']);
	$text_page["articles"]=$t_page['articles'];
}else {
	$text_page["text"]='';
	$text_page["articles"]='';
}

$text_page["index_categoryid"]=$root_category["categoryid"];

	if (count($croot)>1){
		$sub_categories_id=$croot[count($croot)-2];
		$text_page["sub_categories"]=func_query("SELECT * FROM $sql_tbl[categories] WHERE parentid='$sub_categories_id' AND avail='N' order by order_by");
	}
	$text_page["current_sub_categories"]=func_query("SELECT * FROM $sql_tbl[categories] WHERE parentid='$cat' AND avail='N' order by order_by");
	if (!empty($text_page["current_sub_categories"]))
	foreach ($text_page["current_sub_categories"] as $a=>$b){
		if ($b['meta_descr']==''){
			$text=stripslashes(func_query_first_cell("SELECT text FROM $sql_tbl[textpage] WHERE parentid='$b[categoryid]'")); 
			$text_page["current_sub_categories"][$a]['meta_descr']=__substr(strip_tags($text), 128);
		}
		$_imgs=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$b[categoryid]' and md5='textpage' and avail='Y' order by orderby ");//LIMIT 0,1
		if (!empty($_imgs)){
		$text_page["current_sub_categories"][$a]['images']=$_imgs;
		$_img=$_imgs[0];
		$text_page["current_sub_categories"][$a]['image']=str_replace("../","/",$_img['image_path']);
		$text_page["current_sub_categories"][$a]['icon']=str_replace("../assets/image/text/","/img/texticons/",$_img['image_path'].'?w=220&h=220'); 
		}else {
			$text_page["current_sub_categories"][$a]['image']='';
			$text_page["current_sub_categories"][$a]['icon']='';
		}
	}
	unset($current_category);
}
function nav_array($categoryid_path,$html=true){
global $text_page,$sql_tbl,$cat;

$croot=explode("/",$categoryid_path);
$cnav=Array();
	if (is_array($croot))
	foreach ($croot as $a=>$b){
		$tcategory=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$b'");
		if ($tcategory['location']=='') $location="$action_var?cat=$tcategory[categoryid]";
		else $location=$tcategory['location'];
		
		if ($cat!=$b)	$cnav[$a]='<a href="'.url($location).'">'.$tcategory['category']."</a>";
		else $cnav[$a]='<a href="'.url($location).'" class="act">'.$tcategory['category'].'</a>';
	}
	if ($html){
		if (count($cnav)>1) $text_page["nav"]=implode(" » ",$cnav);else $text_page["nav"]='';	
	}else return $cnav;
	
}

function article_list($articles){
global $sql_tbl;	
		$articles=explode(';',$articles);
		if (!empty($articles))
			foreach ($articles as $w=>$q){
				$_q=explode('|',$q);
				if (empty($_q[1]))
					 $articles[$w]=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='$q'");
				else $articles[$w]=func_query_first("SELECT * FROM $sql_tbl[textpage] WHERE pageid='$_q[1]'");
				
				$_images=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$q' and md5='textpage' LIMIT 0,1");
				if (!empty($_images)){
					$_images=$_images[0];
					$articles[$w]['icon']=str_replace('../assets/image/text/','',$_images['image_path']);
				}else unset($articles[$w]);
			}
return 	$articles;
}

text_data($cat);
nav_array($text_page['categoryid_path']);

$cheсk_articles_url=func_query("SELECT * FROM $sql_tbl[textpage] WHERE `type`='article' ");
//r_print_r($cheсk_articles_url);
$i=-1;
$location_articles_url=Array();
$location_array["/"]="1";
if (!empty($cheсk_articles_url))
foreach ($cheсk_articles_url as $a =>$b)
	if ($_requestUri==str_replace("/","",$b['location'])){
		$cat=$b['parentid'];
		text_data($cat);
		$_cat=$cat;$cat='';
		$_nav=nav_array($text_page['categoryid_path'],false);
		$cat=$_cat;
		$_nav[]='<a href="'.url($b['location']).'" class="act">'.$b['category']."</a>";
		$text_page["nav"]=implode(" » ",$_nav);
		
		$text_page['pageid']=$b['pageid'];
		$text_page['text']=$b['text'];
		$text_page['title']=$b['title'];
		$text_page['category']=$b['category'];
		$text_page['meta_keywords']=$b['meta_keywords'];
		$text_page['meta_descr']=$b['meta_descr'];
		$text_page['location']=$b['location'];
		$text_page['description']=$b['description'];
		$text_page['date']=$b['date'];
		$text_page['showicon']=$b['showicon'];
		$text_page['readalso']=$b['readalso'];
		$text_page['type']='article';
		
		$text_page['subcategories']=$b['subcategories'];
		$articles=article_list($b['articles']);
		$empty_page=false;
	}
	//r_print_r($text_page);
	if (!empty($text_page['articles'])) $articles=article_list($text_page['articles']);
	if (empty($text_page['pageid'])) $text_page['pageid']='';
	$text_page["article_list"]=func_query("SELECT avail,category, pageid, icon, location, description FROM $sql_tbl[textpage] WHERE `type`='article' and parentid='$cat' and avail='Y' and icon<>'' and icon<>'' and pageid<>'$text_page[pageid]' ");

	if ($text_page["title"]=='') $text_page["title"]=$text_page["category"];
	//if ($text_page["meta_keywords"]=='') $text_page["meta_keywords"]=$config["root_keywords"];
	//if ($text_page["meta_descr"]=='') $text_page["meta_description"]=$config["root_description"];
		//else $text_page["meta_description"]=$text_page["meta_descr"]; 
?>