<?
function rdate($time) {
	$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
	return '<span class="dd">'.date('d',$time).'</span><span class="db">'.$MonthNames[date('n',$time)-1].'<br/>'.date('Y',$time).'</span>';
}

	if (!empty($nid)){
	echo '<h2>Новости</h2>';
		echo'<div class="news"><br>';
			echo '<span class="w-news">'.rdate($current_news['date']).'</span>';
			if (!empty($current_news['icon'])) {
				echo '<img class="iconboder" style="float:left" src="'.$current_news['icon'].'" alt="'.$current_news['name'].'" >';
				array_shift($current_news['images']);
			}
			
			//echo '<span class="data">'.strftime($config["timeformat"],$current_news['date']).'</span><br><br>';
			if 	(strip_tags($current_news['news'])=='') $current_news['news']=$current_news['description'];
			echo $current_news['news'];
			
			if ( !empty($current_news['images']) ){
			echo '<h2>Изображения</h2>';
			foreach ($current_news['images'] as $a=>$b)
				echo '<div class="iconboder" style="width:182px"><a href="'.$b['image'].'" class="f_box"><img src="'.$b['icon'].'" alt="" ></a></div>';
			}	
		echo '</div>';
}



//r_print_r($news);
if (!empty($news)){
		if (!empty($nid))
		echo '<div class="cls"></div>
		<hr class="hr">
		<h2>Читайте так же</h2>'; else echo '<h1>Новости за <strong>'.$act_year.' год </strong></h1>';
			foreach ($news as $a=>$b){
				echo '
					<div class="blog_news">';
						if ($b['date']!='0')
							echo '<span class="w-news">'.rdate($b['date']).'</span>';
						if (!empty($b['icon'])) 
							echo '<img src="'.$b['icon'].'" class="iconboder" alt="'.$b['name'].'" >';
						
							echo strip_tags($b['description']);
						echo '<br/><a href="'.url($b['location']).'" class="btn">Подробнее</a>';
						/*if (!empty($b['icon'])){
							echo '<div class="pad1">'.$b['description'].'</div>';
							
							}
						else echo '<div class="pad1">'.strip_tags($b['description']).' <a href="'.url($b['location']).'" class="more">»</a></div>';	
						*/
					echo '
					</div>
					<div class="clearfix "></div>
					<hr class="bggt"/>
				';
			}
		echo '<div class="navpages">';
			$news_pages->show_navigation_page();
		echo '</div>';
	}
	
	
?>