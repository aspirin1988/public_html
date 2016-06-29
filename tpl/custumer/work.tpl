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
		
		<script src="/js/social-likes/social-likes.min.js"></script>  
		<link rel="stylesheet" href="/js/social-likes/social-likes_flat.css"/>
		
		
		<script src="/js/work.js"></script>  
		
</head>
<body class="work-page">
	<header id="work-header">
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
		<div id="work-logo-box" class="container">
			<div id="logo" class="col-xs-12 col-sm-3 col-md-3">
				<a href="/" title="прокат эксклюзивных автомобилей"><img src="/img/logo.png" alt="прокат эксклюзивных автомобилей" /></a>
				<span>прокат эксклюзивных автомобилей</span>
			</div>
			<span class="net">
				<a href="/"></a>
			</span>
			<div id="contacts" class="hidden-xs">
				<span class="phone">+7 701 110 30 11</span>
				<span class="phone">+7 705 555 29 54</span>
			</div>
		</div>
	</header>
	<div id="work-content">
		<div class="container">
		<?
		$left_said_bar=false;
		if (!empty($text_page['current_sub_categories'])|| (!empty($text_page['sub_categories'])) ){
			$left_said_bar=true;
			echo '<div id="left-menu" class="col-xs-12 col-sm-4 col-md-3"><ul>';
				if ( !empty($text_page['current_sub_categories']) )
				foreach ($text_page['current_sub_categories'] as $a=>$b){
							if ($b['title']=='') $b['title']=$b['category'];
							if ($cat==$b['categoryid'])
								 echo '<li class="active"><a href="'.url($b['location']).'" title="'.$b['title'].'" class="active">'.$b['category'].'</a></li>';
							else echo '<li><a href="'.url($b['location']).'" title="'.$b['title'].'">'.$b['category'].'</a></li>';
						}
				if (!empty($text_page['sub_categories'])) {
					foreach ($text_page['sub_categories'] as $a=>$b){
							if ($b['title']=='') $b['title']=$b['category'];
							if ($cat==$b['categoryid'])
								 echo '<li class="active"><a href="'.url($b['location']).'" title="'.$b['title'].'" class="active">'.$b['category'].'</a></li>';
							else echo '<li><a href="'.url($b['location']).'" title="'.$b['title'].'">'.$b['category'].'</a></li>';
						}
				}		
						
				echo '</ul></div>';
			}
		
		
		if ($locationvar=='product'){
			include 'projects.tpl';
		}else{
			if ($left_said_bar)
				 echo '<div class="col-xs-12 col-sm-8 col-md-9">';
			else echo '<div class="col-xs-12 col-sm-12 col-md-12">';
			?>		
		
			<div class="text-content <?if (!empty($text_page['type'])&&($text_page['type']=='article')) echo 'article_text'?>">
			<?
				if ( (strpos($text_page['text'],'h1')===false)||($text_page['text']=='') ) $text_page['text']='<h1>'.$text_page['category'].'</h1>'.$text_page['text'];
				echo $text_page['text'];
				//r_print_r($text_page);
			?>
			<hr class="up"/>
			<p align="center">Поделиться:
			<div class="social-likes" style="text-align:center">
				<div class="vkontakte" title="Поделиться ссылкой во Вконтакте">Вконтакте</div>
				<div class="odnoklassniki" title="Поделиться ссылкой в Одноклассниках">Одноклассники</div>
				<div class="facebook" title="Поделиться ссылкой на Фейсбуке">Facebook</div>
				<div class="twitter" title="Поделиться ссылкой в Твиттере">Twitter</div>
				<div class="mailru" title="Поделиться ссылкой в Моём мире">Мой мир</div>
				<div class="plusone" title="Поделиться ссылкой в Гугл-плюсе">Google+</div>
			</div>
			</p>
			<hr class="dw"/>
			<?
			switch ($text_page['location']){
				case 'otzivi/':{
					include 'comments.tpl';
				}break;
			}
			?>
			</div>
		</div>
		<?
		switch ($text_page['location']){
			case 'catalogue/':{
				$category=func_query("SELECT * FROM $sql_tbl[categories] WHERE parentid='36' and avail='N' and icon<>'' ");
				if ( !empty($category) ) foreach ($category as $a=>$b){?>
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
				<?}
			}break;
		}
			if ( !empty($all_products) )
				foreach ($all_products as $a=>$b){?>
				<div class="pp-card col-xs-12 col-sm-3 col-md-3">
					<div class="p-card">
					<a href="<?=url($b['url'])?>"><img src="<?=$b['icon']?>" alt=""/></a>
					<h3><?=$b['name']?></h3>
					<a href="<?=url($b['url'])?>" class="next">Подробнее</a>
					</div>
				</div>
				<?}
		}?>
		</div>
	</div>

	<?if ($text_page['location']=='/o_nas/'){?>
		<h3 class="white_header" style="margin-bottom:30px">Почему выбирают нас?</h3>
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
		<?}?>
	
	<?if (!empty($text_page["article_list"])){?>
		<div class="container">
			
			<div class="row">
			<?
			if (strip_tags($text_page["text"])!='') echo '<h3 class="w-wht">Читайте так же</h3>';
			//$_count=count($text_page["article_list"])/3;
			foreach ($text_page["article_list"] as $a=>$b){?>
				<div class="pp-card col-xs-12 col-sm-6 col-md-6">
					<div class="a-card">
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<a href="<?=url($b['location'])?>"><img src="/img/categories/<?=$b['pageid']?>_article.<?=$b['icon']?>" alt="<?=$b['category']?>"/></a>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<a href="<?=url($b['location'])?>" class="des-next">
								<h3><?=$b['category']?></h3>
								<p><?=$b['description']?></p>
							</a>
						</div>
					</div>
					</div>
				</div>	
			<?}?>
			</div>
		</div>				
	<?}?>
	
	<br/>
	<footer id="footer">
		<div class="container">
			<p align="center">
				<strong><?=$config['title']?></strong>
				&copy; Все права защищены, <?=strftime("%Y",time())?>
				<br/><br/><a href="/flash.php" style="color:#3a3a3a">Flash версия</a>
			</p>
		</div>
	</footer>
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
*/
?>
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

	
</body>	
</html>