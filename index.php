<?
include "localconf.php";
$locationvar="work";
$action_var=$locationvar.".php";
$tabl=$sql_tbl["categories"];

$empty_page=true;

include "include/head.php";
include "include/news.php";
include "include/gallary.php";
include "include/comments.php";

function print_up_sub_menu($url){
	global $sql_tbl;
	$category=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE location='$url' ");
	$category=func_query("SELECT * FROM $sql_tbl[categories] WHERE parentid='$category[categoryid]' AND avail='N' order by order_by ");
	if (!empty($category))
	foreach ($category as $a=>$b){
		echo "<a href='$b[location]'>$b[category]</a>\n";
	}
}

	$images_text_page=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$cat' and md5='textpage' and avail='Y' order by orderby");
	if (!empty($images_text_page)){
		$imageset='';
			$_images_text_page=$images_text_page[0];
			if (!empty($_images_text_page)){
			$b=$_images_text_page;
			$_icon=str_replace("../assets/image/text/","/img/texticons/",$b['image_path']);
				$b['image_path']=str_replace("../","/",$b['image_path']);
				if ($b['alt']=='') $b['alt']=$text_page['title'];
				$imageset='
				<div class="imgbox imgset">
					<a href="'.$b['image_path'].'" rel="f_box" title="'.$b['alt'].'" class="dot_name f_box">
					<span class="icon">
							<img src="'.$_icon.'" alt="'.$b['alt'].'"/>
					</span>
					<span class="imgborder">&nbsp;</span>
					</a>
				</div>
				';
			if ($text_page['showicon']=='Y') {
				$text_page['text']=$imageset.$text_page['text'];	
				array_shift($images_text_page);
				}
			$imageset='';
			}
		
		/*if ( !empty($images_text_page) ){
		foreach ($images_text_page as $a=>$b)
			$imageset.=imghtmldata($b);
			$imageset.='<div class="cls"></div>';	
		}*/	
	
		if ( strrpos($text_page['text'],"#images")>0 )
			$text_page['text']=str_replace("#images",$imageset,$text_page['text']);
			else $text_page['text'].=$imageset;
	}

	
		
	include "include/catalogue_show.php";
	
	//include "include/albums.php";
	
	$sliders = func_query ("SELECT * FROM $sql_tbl[slider] WHERE cat='$cat' and avail='N' ORDER BY orderby ");
	if (is_array($sliders))
	foreach ($sliders as $a=>$v){
		$sliders[$a]["image"]="/img/slider/".$v['id']."_image.".$v["image"];
		$sliders[$a]["icon"]="/img/slider/".$v['id']."_image.".$v["image"];
		}
	
	//r_print_r($text_page);
	
	if ($empty_page) include 'include/redirect.php';
	
	
	function add_s_tags($text){
		preg_match_all('/cars\(.*?\)/',$text,$m);
		$_i=1;
		if (!empty($m)){
			$m=$m[0];
			foreach ($m as $e=>$q){
				$_q=$q;
				$_q=str_replace('cars(','',$_q);
				$_q=str_replace(')','',$_q);
				$cars_set=explode(':',$_q);
				$carset='';
				//r_print_r($cars_set);
				if (is_array($cars_set))
					foreach ($cars_set as $e=>$f){
						$b=get_products_list(" active='Y' and id='$f' ",'');
						$b=$b[0];
						$carset.='
						<div class="pp-card col-xs-12 col-sm-3 col-md-3">
							<div class="p-card">
								<a href="'.url($b['url']).'"><img src="'.$b['icon'].'" alt=""/></a>
								<h3>'.$b['name'].'</h3>
								<a href="'.url($b['url']).'" class="next">Подробнее</a>
							</div>
						</div>
						';
					}
				$text=str_replace($q,'<div id="p_list_'.$_i.'c" class="car-list"><div class="row p-list" id="p_list_'.$_i.'">'.$carset.'</div><div class="a_left"></div><div class="a_right"></div></div>',$text);
				$_i++;
			}
		}	
		return $text;
	}
	
	
	$text_page['text']=add_s_tags($text_page['text']);
	
	
	include "tpl/custumer/index.tpl";
?>