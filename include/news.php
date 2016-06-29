<?
	$news_years=func_query("SELECT DISTINCT (FROM_UNIXTIME(date,'%Y')) as date FROM $sql_tbl[news] WHERE 1 and active='Y' order by date desc");
	foreach ($news_years as $a=>$b)
		$news_years[$a]=$b['date'];
	
	function news_data($query){
	global $sql_tbl;
		$news=func_query($query);
		if (!empty($news))
			foreach ($news as $a=>$b){
				$news[$a]['images']=func_query("SELECT * FROM $sql_tbl[gallery] WHERE category='$b[id]' and avail='Y' and md5='news' order by orderby");
					if (!empty($news[$a]['images'])){
						foreach  ($news[$a]['images'] as $c=>$d){
							$news[$a]['images'][$c]['icon']=str_replace("../assets/image/news/","/img/newsicons/",$d['image_path']);
							$news[$a]['images'][$c]['image']=str_replace("../","/",$d['image_path']);
						}
						$news[$a]['icon']=$news[$a]['images'][0]['icon'];
						$news[$a]['image']=$news[$a]['images'][0]['image'];
						unset($news[$a]['images'][0]);
					}
			$news[$a]['location']='/news/index.php?nid='.$b['id'];
			}
		return $news;
	}
	
	$nid=get_get_var('nid');
	if (!is_numeric($nid)) $nid=0;
	
	include_once "navigation_class.php";
	if (empty($archive) ) $archive='N';

	$act_year=$news_years[0];
	
	$query="SELECT * FROM $sql_tbl[news] WHERE FROM_UNIXTIME(date,'%Y')=$act_year and active='Y'  ";
	$news_pages = new navigation($query,$config,5);
	$news_pages->navigation_script="/news/?";
	
	if (!empty($_GET['page_all'])) $slimit=""; else $slimit="LIMIT ".$news_pages->first_page.",".$news_pages->objects_per_page;
		$news=news_data("$query ORDER BY date DESC $slimit");
	
	if (!empty($requestUri[2])) {
		$act_year=$requestUri[2];
		if (in_array($act_year,$news_years)){
			$text_page['location']=$text_page['category_root_data']['location']='news/';
			$text_page['text']='';
			$text_page['title']='Новости';
			
			$query="SELECT * FROM $sql_tbl[news] WHERE FROM_UNIXTIME(date,'%Y')='".$requestUri[2]."' and active='Y'  ";
			$news_pages = new navigation($query,$config,5);
			$news_pages->navigation_script="/news/".$requestUri[2]."/?";
	
			$news=news_data("$query ORDER BY date DESC $slimit");
			$empty_page=false;
		} 
	}
	
	if (!empty($nid)){
		$current_news=news_data("SELECT * FROM $sql_tbl[news] WHERE id='$nid' and active='Y'   ORDER BY date DESC $slimit");
		$current_news=$current_news[0];
		
		if (!empty($current_news['name']) ) $text_page['title']=$current_news['name'];
		
		$act_year=strftime("%Y",$current_news['date']);
		
		$news=news_data("SELECT * FROM $sql_tbl[news] WHERE FROM_UNIXTIME(date,'%Y')=$act_year and active='Y' and id<>$current_news[id] ORDER BY date DESC LIMIT 0,5");
	}
?>