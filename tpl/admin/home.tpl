<?
function _print_link($curlocvar,$linkvar,$titlelink){
	global $locationvar,$web_dir;
	if ($curlocvar==$locationvar) 
		 echo '<li class="active"><a href="'.$linkvar.'">'.$titlelink.'</a></li>';
	else echo '<li><a href="'.$linkvar.'">'.$titlelink.'</a></li>';
}
?>
		  
<html>
<head><title><?=$title?></title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<META content="" name=description>
<META content="" name=keywords>
<META http-equiv=Cache-Control content=no-cache>
<META http-equiv=Pragma content=no-cache>




<LINK href="/tpl/admin/style.css" type=text/css rel=stylesheet>
<script type="text/javascript" src="/js/jquery-1.7.2.min.js"></script>


<link rel="stylesheet" href="/js/ds/jquery.formstyler.css">
<script src="/js/ds/jquery.formstyler.min.js"></script>


<script src="/js/jquery.timers-1.2.js" type="text/javascript"></script>
<script src="/tpl/admin/js/tree/tree.js" type="text/javascript"></script>
<LINK href="/tpl/admin/js/tree/tree.css" type=text/css rel=stylesheet>

<link rel="stylesheet" type="text/css" href="/adm/js/universal-jquery-tabs-script/style.css" media="screen">
<script src="/adm/js/universal-jquery-tabs-script/tabs_localStorage.js"></script>


<script type="text/javascript" src="/js/fancybox/jquery.fancybox.js"></script>
<link rel="stylesheet" type="text/css" href="/js/fancybox/jquery.fancybox.css" media="screen" />

<script type="text/javascript" src="/ck_editor/ckeditor.js"></script> 


<script type="text/javascript" src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>


<!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
<link rel="stylesheet" type="text/css" href="img/jquery.plupload.queue.css" media="screen">
<script type="text/javascript" src="js/plupload.full.js"></script>
<script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="js/ru.js"></script>
	
<script>
$(document).ready(function(){
$("a.f_box").fancybox({'overlayOpacity' : 0.4});
$("#drop_Menu").treeview({
	animated: "fast",
	persist: "location",
	collapsed: true,
	unique: true
	});
	
	$('input, select, textarea').styler({selectSearch: false});
	
});	
</script>

</HEAD>

<body>
	<?if ($access){?>
		<div class="slogo" id="action_menu">
			<h2>Меню</h2>
			<div class="border_wrapp rb">
				<div class="wrapp rb">
					<ul class="list">
						<li><a href="/adm/categories.php">Разделы</a></li>
						<li><a href="/adm/textpage.php">Текст страницы</a></li>
						<li><a href="/adm/products.php">Каталог</a></li>
						<li><a href="/adm/comments.php">Отзывы</a></li>
						<li><a href="/adm/settings.php">Настройки</a></li>
						<li><a href="/adm/adminpass.php">Пароль администратора</a></li>
						<?/*<li><a href="/admin/languages.php">Языковы метки</a></li>*/?>
					</ul>
				</div>
			</div>
			<?include "../tpl/authbox.tpl";?>	
		</div>
		
		<div id="work_area">
			<?	
				switch ($locationvar){
		case "categoryes": include "categories.tpl";break;
		case "settings": include "settings.tpl";break;
		case "textpage": include "textpage.tpl";break;
		case "languages": include "languages.tpl";break;
		case "products": include "products.tpl";break;
		case "news": include "news.tpl";break;
		case "comments": include "coments.tpl";break;
		case "textpage": include "textpage.tpl";break;
		case "users": include "users.tpl";break;
		case "gallery": include "gallery.tpl";break;
		case "slider": include "slider.tpl";break;
		case "adminpass": include "adminpass.tpl";break;
		default:{
			echo '
				<h2>Добро пожаловать!</h2>
				<p align="center" class="txt">Для начала работы выберите желаемое действие в меню слева.</p>
				';
			}break;
		}
			?>
		</div>
	<script>
    $("#action_menu a").each(function (){
        var location = window.location.href;
        var link = this.href;
		location=location.split('?');
		
        if(location[0] == link) {
            $(this).addClass('active');
        }
    });
	</script>
	<?}else include "../tpl/authbox.tpl";?>
</body>

</html>