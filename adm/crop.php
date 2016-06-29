<?
include 'localconf.php';
session_start();
$w=get_get_var('w');
$h=get_get_var('h');

$f=get_get_var('f');
$p=get_get_var('param');
$t=get_get_var('type');

$_SESSION['f']=get_get_var('ff');;

?>
<html>
<head>
	<script src="/js/jquery.js"></script>
	<script src="croppic/assets/js/jquery.mousewheel.min.js"></script>
   	<script src="croppic/croppic.min.js"></script>
    <script src="croppic/assets/js/main.js"></script>
	
	<link href="croppic/assets/css/croppic.css" rel="stylesheet">
	<style>
	#cropContainerPreload{
		margin:0px auto;
		width:<?=$w?>px;
		height:<?=$h?>px;
	}
	</style>
</head>
<body>
<div id="cropContainerPreload"></div>
<script>
var croppicContainerPreloadOptions = {
	uploadUrl:'/admin/croppic/img_save_to_file.php',
	cropUrl:'/admin/croppic/img_crop_to_file.php',
	loadPicture:'<?=$f?>',
	enableMousescroll:true,
	loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
	onBeforeImgUpload: function(){ console.log('onBeforeImgUpload') },
	onAfterImgUpload: function(){ console.log('onAfterImgUpload') },
	onImgDrag: function(){ console.log('onImgDrag') },
	onImgZoom: function(){ console.log('onImgZoom') },
	onBeforeImgCrop: function(){ console.log('onBeforeImgCrop') },
	onAfterImgCrop:function(){ console.log('onAfterImgCrop') },
	onReset:function(){ console.log('onReset') },
	onError:function(errormessage){ console.log('onError:'+errormessage) }
	}
var cropContainerPreload = new Croppic('cropContainerPreload', croppicContainerPreloadOptions);
</script>
</body>
</html>