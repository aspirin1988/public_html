<?
function show_coment($b,$stl,$txt){
	global $config;
	echo '<a name="'.$b['id'].'"></a>';
		if ($b['icon']!='') echo '<img class="ipic" src="/'.$b['icon'].'">'; else echo '<img class="ipic" src="/img/m4.png">';
			echo '<div class="message_box"><div class="messagebox">';
				echo '<span class="uname">'.$b['name'].', '.strftime($config["timeformat"],$b['date']).'</span>';
				echo $txt;
	echo '</div></div><div class="cls"></div>';
}

$cid=get_get_var('id');
if ( !empty($comments) ){
	echo '<div class="navpages" style="margin:20px auto">';$comments_pages->show_navigation_page();echo '</div>';
	echo '<div id="coment-list">';
		foreach ($comments as $a=>$b){
			echo '<a name="'.$b['id'].'"></a>';
			if ($b['icon']!='') echo '<img class="ipic" src="/'.$b['icon'].'">'; else echo '<img class="ipic" src="/img/m4.png">';
			echo '
			<div class="message_box"><div class="messagebox">
					<span class="uname">'.$b['name'].', '.strftime($config["timeformat"],$b['date']).'</span>'.$b['text'].'
			</div></div><div class="cls"></div>';
		}
	echo '</div>';
	echo '<div class="navpages" style="margin:20px auto">';$comments_pages->show_navigation_page();echo '</div>
	<hr class="up"/>';
	
}else echo '<p align="center">Отзывов пока нет</p>';
?>

<h2>Оставить отзыв</h2>
<?include 'coment_forms.tpl';?>