<!DOCTYPE html>
<html>
    <head>
		<title><?php $this->output('title') ?></title>
		<meta content="IE=8" http-equiv="X-UA-Compatible" />
		<meta name="viewport" content="width=<?php echo $content->website->width?>" />
		<meta name="format-detection" content="telephone=no" />
<?php 
/**
 * @var $basePath string
 * @var $websitePreloader string
 * @var $contentPreloader string
 * @var $page PageVO
 */
$style = $content->website->style;
if (isset($pageFavicon))
{
	foreach($pageFavicon as $favicon)
	{
		echo '<link '
			. (isset($favicon["rel"]) && $favicon["rel"]!="" ? ' rel="' . $favicon["rel"] . '"':'')
			. (isset($favicon["href"]) && $favicon["href"]!="" ? ' href="' . $favicon["href"] . '"':'')
			. (isset($favicon["type"]) && $favicon["type"]!="" ? ' type="' . $favicon["type"] . '"':'')
			. " />\n";
	}
} ?>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->getCharset() ?>" />
		<?php if ($this->has('meta-description')): ?>
		<meta name="description" content="<?php $this->output('meta-description') ?>" />
		<?php endif; ?>

		<?php if ($this->has('meta-keywords')): ?>
		<meta name="keywords" content="<?php $this->output('meta-keywords') ?>" />
		<?php endif; ?>

		<?php if ($this->has('meta-robots')): ?>
		<meta name="robots" content="<?php $this->output('meta-robots') ?>" />
		<?php endif; ?>

		<?php if ($this->has('meta-google-webmaster-tools')): ?>
		<meta name="verify-v1" content="<?php $this->output('meta-google-webmaster-tools') ?>" />
		<meta name="google-site-verification" content="<?php $this->output('meta-google-webmaster-tools') ?>" />
		<?php endif; ?>

		<link href="<?php echo $this->assets->getBasePath(); ?>assets/css/reset.css" rel="stylesheet" type="text/css"  />
		<style>
		<!--
		body,html {
			min-height:<?php echo max($content->website->height, (isset($page->height) ? $page->height : 0) ) ?>px;
			height:100%;
		}
		#mjs-site-preloader, body,html {
			<?php echo $style->getBackgroundColor('url(' . $basePath . $websitePreloader .') 50% 50% no-repeat');?>				
		}
		
		#mjs-preloader {
			background: url('<?php echo $basePath . $contentPreloader?>') 50% 50% no-repeat ;
		}
		-->
		</style>

		<?php $this->stylesheets->add('assets/css/style.css'); ?>

		<?php $this->javascripts->add('assets/jquery/jquery.min.js') ?>
		<?php $this->javascripts->add('assets/jquery/jquery.plugin.min.js'); ?>

        <?php $this->stylesheets->add('assets/css/colorbox/colorbox.css') ?>
		<?php $this->javascripts->add('assets/jquery/colorbox/jquery.colorbox.js') ?>
		
		<?php $this->javascripts->add('assets/js/html5.js'); ?>
		<?php $this->javascripts->add('assets/js/engine.min.js'); ?>
		<?php $this->javascripts->add('assets/js/template.min.js'); ?>

		<?php
		/*
		 * load requirements from structure.xml
		 */
			$this->htmlHelpRender->initJavascripts($this);
			$this->htmlHelpRender->initStylesheets($this);
		?>
		<?php echo $this->stylesheets ?>
		<?php echo $this->javascripts ?>
		<style>
		<!--
		#mjs-browser {
			min-width: <?php echo $content->website->width?>px;
			<?php
				echo $style->getBackgroundStyle();
			?>
		}
		#mjs-main {
			width: <?php echo $content->website->width?>px;
			min-height:<?php echo max($content->website->height, (isset($page->height) ? $page->height : 0) ) ?>px;
		}
		-->
		</style>
<!--[if lt IE 8]>
	<div style=' clear: both; text-align:center; position: relative;'>
		<a href="http://windows.microsoft.com/en-US/internet-explorer/products/ie/home?ocid=ie6_countdown_bannercode"><img src="http://storage.ie6countdown.com/assets/100/images/banners/warning_bar_0000_us.jpg" border="0" height="42" width="820" alt="You are using an outdated browser. For a faster, safer browsing experience, upgrade for free today." /></a>
	</div>
<![endif]-->
<?php
	$hookfilename = dirname(__FILE__) . '/_hook_head.tpl';
	if (file_exists($hookfilename))
	{
		echo file_get_contents($hookfilename);
	}
?>
    </head>
    
<body>
<?php
	$hookfilename = dirname(__FILE__) . '/_hook_body_top.tpl';
	if (file_exists($hookfilename))
	{
		echo file_get_contents($hookfilename);
	}
?>
    <div id="mjs-site-preloader"><?php
		if (isset($javascriptDisabledMessage))
			echo '<noscript>' . $javascriptDisabledMessage . '</noscript>';
	?></div>
    <div id="mjs-preloader"></div>
	<div id="mjs-htmlContainer"></div>
	<div id="mjs-bgContainer"></div>
	
	<div id="mjs-bg-popup"></div>

	<div id="mjs-browser" style="">
		<!-- popup overlay -->
		<div id="mjs-main" class="mjs-holder" style="">

			<div id="mjs-website-bgContainer"></div>
			<div id="mjs-website">
				<?php echo $this->htmlHelpRender->dispatch($content, 'page'); ?>
				<?php //echo $this->htmlHelpRender->holders(); ?>
			</div>
			<div class="mjs-clear"></div>
			<div id="mjs-website-topContainer"></div>

			<div id="mjs-popup-background"></div>
			<div id="mjs-popups-container">
				<div id="mjs-popup-1" class="mjs-popup">
					<?php echo $this->htmlHelpRender->dispatch($content, 'popup'); ?>
				</div>
			</div>
		</div>
			<div id="mjs-loginBox">
				<?php
					if ($websiteProtectionEnabled == "true")
					{
						echo $this->htmlHelpRender->renderLoginForm($content->website->loginForm);
					}
				?>
			</div>
	</div>
	<div id="mjs-animationContainer"></div>
	<div id="mjs-topContainer"></div>
	
<script type="text/javascript">
var _debug = {};
var response = {};
$(document).ready(function () {
	$('body, html').css('background', 'none');
	<?php if ($this->htmlHelpRender->get('page')) : ?>
		$('.mjs-layoutType-<?php echo $this->htmlHelpRender->get('page')->layoutTypeId?>, .mjs-pageType-<?php echo $this->htmlHelpRender->get('page')->pageTypeId?>').show();
	<?php endif; ?>
	response = <?php echo json_encode($this->htmlHelpRender->getResponse());?>;
	MotoJS.init(response);
});
</script>

<?php echo $this->htmlHelpRender->loadDeferJavascripts();?>

<?php 
	if ($this->has('googleAnalytics'))
		$this->output('googleAnalytics');

	$hookfilename = dirname(__FILE__) . '/_hook_body_bottom.tpl';
	if (file_exists($hookfilename))
	{
		echo file_get_contents($hookfilename);
	}
?>
</body>
</html>
