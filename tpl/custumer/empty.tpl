<!DOCTYPE html>
<html lang="en">
     <head>
     <title><?=$text_page['title']?></title>
     <meta charset="utf-8">
     <link rel="icon" href="/images/favicon.ico">
     <link rel="shortcut icon" href="/images/favicon.ico" />
     <link rel="stylesheet" href="/css/style.css">
     <script src="/js/jquery.js"></script>

     <script src="/js/superfish.js"></script>
     <script src="/js/jquery.equalheights.js"></script>
     <script src="/js/jquery.mobilemenu.js"></script>

     </head>
     <body>
<!--==============================header=================================-->
<header> 
	<div class="container_16">
				<nav class="horizontal-nav full-width horizontalNav-notprocessed">
				<?recur_up_menu(0,'');?>
				</nav>
    </div>
</header>
<div class="page1_block " >
  <div class="container_16" style="padding-top:180px;background:url('/img/404.gif') center 10px no-repeat;">
  <p align="center">
	<span style="font: italic 20px/26px Georgia, 'Times New Roman', Times, serif;color:#792d3b;">
	Вы набрали неправильный адрес страницы.<br/> Пожалуйста, выберите интересующий Вас раздел или перейдите на <a href="/" style="color:#585858;text-decoration:underline">Главную страницу</a> сайта.
	</span>
  </p>
  </div>
</div>
<?
	include 'footer.tpl';
?>