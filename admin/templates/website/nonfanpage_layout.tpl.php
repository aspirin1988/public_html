<?php
	$isFaceBook = false;
	if (defined('TEMPLATE_TYPE') && TEMPLATE_TYPE == 'facebook')
		$isFaceBook = true;
	$app_id = '189613811101522';
	if (defined('FACEBOOK_APPLICATION_ID'))
		$app_id = FACEBOOK_APPLICATION_ID;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <title><?php $this->output('title') ?></title>
	<meta content="IE=8" http-equiv="X-UA-Compatible" />
<?php if (isset($pageFavicon))
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

	<?php $this->javascripts->add('http://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js') ?>
    <?php $this->javascripts->add('assets/motoResize.js') ?>
	<?php
	$filename = dirname(__FILE__) . '/header.tpl.php';
	if (file_exists($filename))
	{
		try
		{
			error_reporting(E_ALL ^ E_NOTICE);
			include_once($filename);
		}
		catch(Exception $e)
		{
		}
	}
	?>	
    <?php echo $this->javascripts ?>
    <?php echo $this->stylesheets ?>

    <?php if (isset($isUserBgColor) && $isUserBgColor):?>
<style type="text/css">
<!--
body {background-color: <?php echo $bgColor?>;}
-->
</style>
    <?php endif;?>

	<?php if (isset($useFlashScroll) && strtolower($useFlashScroll) == 'true'):?>
<style type="text/css">
html
{
	//overflow-y:hidden;
}
</style>
    <?php endif;?>

    <script type="text/javascript">
        var isDynamicHeight = <?php echo ( defined('DYNAMIC_HEIGHT') ? DYNAMIC_HEIGHT : 'false') ?>;
        var pageHeight = null;
		var useFlashScroll = <?php echo (isset($useFlashScroll) ? $useFlashScroll : 'false') ?>;
		var minWidth = <?php echo $websiteMinWidth ?>;
		var minHeight = <?php echo $websiteMinHeight ?>;
    </script>
	
<?php if ($isFaceBook) { ?>
<style type="text/css">
<!--
body,html,img {width:520px;margin:0;padding:0;border:0;overflow-x: hidden;}
-->
</style>
<?php } ?>

  </head>
  <body <?php echo (isset($useFlashScroll) && strtolower($useFlashScroll) == 'true' ?  'style="overflow-y:hidden"' : '') ?>>

<?php if ($isFaceBook) { ?>
<div id="fb-root"></div>
<script type="text/javascript" src="https://connect.facebook.net/en_US/all.js"></script>
<script type="text/javascript">
FB.init({appId:'<?php echo $app_id?>',cookie:true,status:true,xfbml:true});
function setScreenHeight(value)
{
    return true;
}
function getScreenRect()
{
	return {};
}
</script>
<?php } ?>


        <div id="htmlContainer" class="absolute"></div>
        <div id="container">
<?php

	$content = trim($this->get('content'));
	$tmplfile = dirname(__FILE__) . '/design.tpl.php';
	if ((file_exists($tmplfile)) && (filesize($tmplfile) > 0))
	{
		$content = str_replace('%TEMPLATE_CONTENT%', $content, implode('', file($tmplfile)));
	}
	echo $content;
	?>

    </div>
    <?php if ($this->has('googleAnalytics')): ?>
    <?php $this->output('googleAnalytics') ?>
    <?php endif; ?>
  </body>
</html>
