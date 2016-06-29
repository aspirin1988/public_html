<?
	$tt=time();
	if (!empty($qheader)) echo '<h2>'.$qheader.'</h2>';
?>

	<div class="cls" id="mmesage"></div>
	
	<div id="userpicarea">
		<div id="userpic" class="upicempty"  <?if (!empty($_COOKIE['userpic'])) echo 'style="background:url(/img/userpics/'.$_COOKIE['userpic'].') center top no-repeat; "';?>></div>
		<div id="uploadbtn">загрузить аватар</div>
	</div>

	<div class=" message_box_load">
		<div class="message_box">
		<form name="q_form" method="post" id="q_form" action="" role="form" class="messageForm form-horizontal">
			<input type="hidden" name="mode" value="add_coments"/>
			<input type="hidden" name="type" value="<?=$comment_type?>"/>
			<input type="hidden" name="cat" value="<?=$comment_perent_id?>"/>
			<input type="hidden" name="icon" value="<?=$tt?>"/>
			<div class="messagebox">
				<input name="q_town" value="" class="town" type="text"/>
				
				<div class="inptxt"><input type="text" name="q_name" id="q_name" class="inp" placeholder="Имя:"></div>
				<div class="inptxt"><input type="text" name="q_email" id="q_email" value="" class="inp"  placeholder="E-mail:"/></div>
				
				<div class="textarea"><textarea rows="4" placeholder="Сообщение:" name="q_text"></textarea></div>
				
			</div>
			<br/>
			<p align="center">
				<button type="submit" id="submit_btn" name="submit" class="fbtn btn-primary bgbtn">Отправить</button>
			</p>	
		</form>
		
		</div>
	</div>

<script>
var imgupload=false;
var sendFromCrop=false;
var imgCrop=true;

function sendDataForm(){
	$("#submit_btn").val("Отправляется...");
	$(".message_box").fadeTo(10,0.5);
	$("#q_form").ajaxSubmit({
				url: "/ajax_listener.php",
				data: { mode: "add_coments"},
				success: function(data) {
					if (data=="success"){
							$("#mmesage").html("<div id='messageok'>Ваш отзыв успешно отправлен.</div>");
							$("#mmesage").oneTime(10000, function(){
								$("#messageok").slideUp(500,0);
							});
							$("#q_form").resetForm();
							$("#submit_btn").val("Отправить");
							$(".message_box").fadeTo(10,1);
						}
				}
	});
}
var croppicStat=0;
var croppicContaineroutputOptions = {
	uploadUrl:'/comments/img_save_to_file.php?icon=<?=$tt?>',
	cropUrl:'/comments/img_crop_to_file.php?icon=<?=$tt?>', 
	outputUrlId:'cropOutput',
	modal:false,
	zoomFactor:10,
	doubleZoomControls:true,
	rotateFactor:10,
	rotateControls:true,	
	customUploadButtonId:'uploadbtn',
	loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
	onAfterImgUpload: function(){ 
		croppicStat=2; 
	},
	onBeforeImgUpload: function(){ croppicStat=1; },
	onAfterImgCrop:		function(){ croppicStat=3; imgCrop=false; },
	<?if (!empty($_SESSION['UserUploadImage'])) echo "loadPicture:'".$_SESSION['UserUploadImage']."'";?>
	}
	var Cropper = new Croppic('userpic', croppicContaineroutputOptions);
	
	$("#q_form").validate({
		submitHandler:function(form) {
			if (croppicStat==2){
				if (imgCrop) Cropper.crop();	
				sendDataForm();	
			}else sendDataForm();	
				},rules: {
							"q_name": {
							required: true,
							minlength: 2
							},
							"q_email":{
								required: true,
								email: true
							},
							"q_text":{
							required: true,
							minlength: 2
							}
							},messages:{
							"q_name":"Введите ваше имя.",
							"q_email": "Введите e-mail.",
							"q_text": "Введите текст сообщения."
							}						
		});
	</script>