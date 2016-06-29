<div class="col-xs-12 col-sm-9 col-md-9">
	<div class="text-content">
		<nav id="b-stamp"><?=$text_page['nav']?></nav>
			<div class="row">
				<div class="col-xs-12 col-sm-5 col-md-5">
					<?
						echo '<a href="'.$product["image"].'" id="bigimgurl" rel="f_box" title="'.$product['name'].'" class="f_box"><img id="bigimg" class="img-responsive" alt="'.$product['name'].'" src="'.$product["icon"].'"></a>';
						if ( !empty($product["images"]) ){
								echo '<div class="iconset"><div id="iconset">';
									foreach ($product["images"] as $e=>$m)
										echo '<a href="'.$m["image"].'" class="f_box" rel="f_box" title="'.$product['name'].'"><img alt="" class="f_box" src="'.str_replace("icons","sicons",$m["icon"]).'"></a>';
								echo '</div><a class="s_arrows_left"></a><a class="s_arrows_right"></a></div>';
							}
					?>
				</div>
				<div class="col-xs-12 col-sm-7 col-md-7">
				<h1><?=$product['name']?></h1>
				<? echo $product['description']?>
				
				<br/><p align="center"><a href="/ajax_listener.php?mode=get_order_form&id=<?=$product['id']?>" class="f__box bgbtn">Забронировать</a></p>
				
				</div>
			</div>
			
			
			
			<hr class="up"/>
	</div>
</div>
<?
$all_products=get_products_list(" active='Y'  and id<>'$product[id]' and cat='$product[cat]' ",'');
if (!empty($all_products)){?>
	<div class="row cls">
	<h3 class="w-wht">Другие модели</h3>
	<?foreach ($all_products as $a=>$b){?>
		<div class="pp-card col-xs-12 col-sm-3 col-md-3">
			<div class="p-card">
				<a href="<?=url($b['url'])?>" title="<?=$b['name']?>"><img src="<?=$b['icon']?>" alt=""/></a>
				<h3><?=$b['name']?></h3>
				<a href="<?=url($b['location'])?>" title="<?=$b['name']?>" class="next">Подробнее</a>
			</div>
		</div>
	<?}?>
	</div>
<?}?>

<script>
	$('#iconset').carouFredSel({
			auto: false, prev: '.s_arrows_left',next: '.s_arrows_right', 
				items: {
					height:102,
					width:102,
					visible : {min: 1,max: 3}
				}, 
				height:150,
				responsive: true,
				easing:'easeOutQuart',
				scroll: 1,
				mousewheel: false,
				swipe: {onMouse: true, onTouch: true}
		});
</script>