<?
function rdate($time) {
	$MonthNames=array("Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабря");
	return '<span class="dd">'.date('d',$time).'</span><span class="db">'.$MonthNames[date('n',$time)-1].'<br/>'.date('Y',$time).'</span>';
}
?>
<!DOCTYPE HTML>
<html>
   <head>
        <meta charset="utf-8" />
        <title><?=$text_page['title']?></title>
        <meta name="description" content="<?=$text_page['meta_descr']?>" />
        <meta name="keywords" content="<?=$text_page['meta_keywords']?>" />
		
		<link rel="shortcut icon" href="/img/favicon.ico" />
		<link rel="icon" href="/img/favicon.ico">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>

		<link href="/js/smartmenu/libs/demo-assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">	
		<!-- Bootstrap core CSS -->
		
		<script src="/js/jquery.carouFredSel-6.2.1-packed.js"></script>
		<link href="/js/smartmenu/addons/bootstrap/jquery.smartmenus.bootstrap.css" rel="stylesheet">
		<script src="/js/smartmenu/libs/demo-assets/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/js/smartmenu/jquery.smartmenus.js"></script>
		<script type="text/javascript" src="/js/smartmenu/addons/bootstrap/jquery.smartmenus.bootstrap.min.js"></script>

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	
		<script type='text/javascript' src='/js/menu/js/jquery.cookie.js'></script>
		<script type='text/javascript' src='/js/menu/js/jquery.hoverIntent.minified.js'></script>
		<link rel="stylesheet" href="/js/fancybox/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
		<script type="text/javascript" src="/js/fancybox/jquery.fancybox.pack.js?v=2.1.5"></script>
		<link href="/img/mystyle.css" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="/js/jquery-migrate-1.2.1.min.js"></script>
		<script type="text/javascript" src="/js/hnav/jquery.horizontalNav.min.js"></script>
		<script type="text/javascript" src="/js/parallax.min.js"></script>
		<script src="/js/croppic/croppic.min.js"></script>  
		<link rel="stylesheet" href="/js/croppic/croppic.css"/>
		<link rel="stylesheet" href="/img/contact-form.css"/>
		<script src="/js/form/jquery.validate.min.js"></script>  
		<script src="/js/form/jquery.form.js"></script>  
		<script src="/js/jquery.timers-1.2.js"></script>  
		<script src="/js/main.js"></script>  
</head>
<body class="main-index">
	<header id="header">
		<div id="upmenu" role="navigation">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			  <span id="mMenu">МЕНЮ</span>
            </button>
          </div>
          <div class="navbar-collapse collapse center">
		  <?recur_up_menu(0,'',' id="main-menu" class="nav navbar-nav -full-width " ');// sm  sm-vertical sm-rtl?>
		  </div>
		</div>
		</div>
		<div id="logo-box" class="container">
			<div id="logo" class="col-xs-12 col-sm-3 col-md-3">
				<a href="/" title="прокат эксклюзивных автомобилей"><img src="/img/logo.png" alt="прокат эксклюзивных автомобилей" /></a>
				<span>прокат эксклюзивных автомобилей</span>
			</div>
			<div id="contacts" class="hidden-xs">
				<span class="phone">+7 701 110 30 11</span>
				<span class="phone">+7 705 555 29 54</span>
			</div>
		</div>
		<div class="up-z">
			<div class="container">
				<div class="l-w-up hidden-xs" >
					<div class="l-w"> </div><div class="r-w"> </div>
				</div>
				<div class="c_wrap">
					<img src="/img/center-image.jpg" id="center-image" alt=""/>
					<a href="/ajax_listener.php?mode=get_simple_order_form" id="simple_btn" class="f__box bgbtn">Забронировать в 1 клик </a>
				</div>
				<br/><br/><br/>
				<div class="left-w hidden-xs"> </div><div class="right-w hidden-xs"> </div>
			</div>
		</div>
	</header>
	
	<div id="content" class="container" style="margin-top:-20px">
		<div class="content">
			<hr class="up"/>
			<?=$text_page['text']?>
			<hr class="dw"/>
		</div>
		<h2><strong>Забронировать автомобиль</strong> - выберите класс</h2>
		<div class="row">
			<?
			$category=func_query("SELECT * FROM $sql_tbl[categories] WHERE parentid='36' and avail='N' and icon<>'' ");
			if ( !empty($category) ) foreach ($category as $a=>$b){
			?>
			<div class="pp-card col-xs-12 col-sm-3 col-md-3">
				<div class="p-card">
					<a href="<?=url($b['location'])?>"><img src="/img/categories/<?=$b['categoryid'].'_image.'.$b['icon']?>" alt=""/></a>
					<?
					$__category=explode(' ',$b['category']);
					if (!empty($__category[1])) 
							 $b['category']=array_shift($__category).' <strong>'.implode(' ',$__category).'</strong>';
					?>
				<h3><?=$b['category']?></h3>
				<p><?=$b['description']?></p>
				<a href="<?=url($b['location'])?>" class="next">Подробнее</a>
				</div>
			</div>
			<?}?>
		</div>
		<hr class="clearfix dw"/>
		<h2><strong>Почему выбирают нас?</strong></h2>
	</div>
	
	<div class="parallax-container" data-z-index="2" data-parallax="scroll" data-image-src="/img/rial-limuzin.jpg">
		<div class="container">
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="prem-card">
				<img src="/img/i1.png" id="i1" alt=""/>
				<p>Круглосуточный заказ автомобилей</p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="prem-card">
				<img src="/img/i2.png" id="i2" alt=""/>
				<p>Всегда чистые автомобили</p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="prem-card">
				<img src="/img/i3.png" id="i3" alt=""/>
				<p>Большой опыт водителей, вежливость и скорость передвижения до пункта назначения</p>
				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-3">
				<div class="prem-card">
				<img src="/img/i4.png" id="i4" alt=""/>
				<p>Деловые поездки, встреча со свадеб, торжественных мероприятий</p>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<h3 class="w-wht">Виртуальный тур</h3>
		<div class="content">
			
			<div class="c_wrap">
				<img src="/img/3d-tur.jpg" class="center-image" alt=""/>
				<a href="/xml/modules/tour.html" class="f_swf c_btn bgbtn" title="Виртуальный тур">Запустить виртуальный тур</a>
			</div>
			
			<hr class="dw"/>
		</div>
	</div>
	

	<br/>
	<div id="comments-main">
		<div class="container">
			<h3>Отзывы</h3>	
			<?
			
	$comments=func_query("SELECT * FROM $sql_tbl[comments] Where hide='N' and cat='0' and status='Y' and `type`='user'  ORDER BY date DESC LIMIT 0,3");
	if ( !empty($comments) ){
	echo '<div id="coment-list">';
		foreach ($comments as $a=>$b){
			echo '<div class="col-xs-12 col-sm-4 col-md-4"><a name="'.$b['id'].'"></a>';
			if ($b['icon']!='') echo '<img class="ipic" src="/'.$b['icon'].'">'; else echo '<img class="ipic" src="/img/m4.png">';
			echo '
			<div class="message_box"><div class="messagebox">
					<span class="uname">'.$b['name'].', '.strftime($config["timeformat"],$b['date']).'</span>'.$b['text'].'
			</div></div></div>';
		}
	echo '</div><div class="cls"></div><br/>';
	}else echo '<p align="center">Отзывов пока нет. Оставьте свой собственный отзыв!</p>';
	?>
		<div class="cls"></div>
		<p align=center><a href="#comment_box" class="m_comments bgbtn">Оставить отзыв</a></p>
			
		<hr class="dw"/>
			
		</div>
		<div class="w2-down"> </div>
	</div>
	<br/>
	<div id="comment_box" class="container">
		<?
		$comment_type='user';
		$comment_perent_id='0';
		include 'coment_forms.tpl';
		?>
	</div>
	
	<footer>
		<p align="center" style="color:#fff">
			<strong><?=$config['title']?></strong>
			&copy; Все права защищены, <?=strftime("%Y",time())?>
			<br/><br/><a href="/flash.php" style="color:#fff">Flash версия</a>
		</p>
	</footer>
	
	<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter37730295 = new Ya.Metrika({
                    id:37730295,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    trackHash:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/37730295" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<?/*	
	<script async type="text/javascript" src="//api.pozvonim.com/widget/callback/v3/fd097c8fbe6dc1292a70ed1e08429227/connect" id="check-code-pozvonim" charset="UTF-8"></script>
	<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter32267079 = new Ya.Metrika({id:32267079,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    trackHash:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/32267079" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
*/?>	
	
</body>	
</html>