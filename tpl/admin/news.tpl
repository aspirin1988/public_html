<!-- Load Queue widget CSS and jQuery -->
<link rel="stylesheet" type="text/css" href="img/jquery.plupload.queue.css" media="screen">
<!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
<script type="text/javascript" src="js/plupload.full.js"></script>
<script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="js/ru.js"></script>
<script type="text/javascript" src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>


<div class="w_bg" style="font:normal 12px Arial">
Тип:
<?
	if (!empty($type_news))
	foreach ($type_news as $a=>$b){
		if ($a==$t)	echo '<b>'.$b.'</b>';
		else echo ' <a href="'.$action_var.'?nid='.$nid.'&t='.$a.'#nfragment-1">'.$b.'</a> ';
		}
?>
</div>
	<?
		if ($actionmode!="add")	echo("<p><a href=$action_var>Добавить новость</a></p>");
	?>
	
	<div class="section">

	<ul class="tabs">
		<li class="ttabs" class="current">Список новостей</li>
		<li class="ttabs">Новость</li>
		<li class="ttabs">Изображения <?if (!empty($posted_data['imgcount'])) echo "( $posted_data[imgcount] )"?></li>
	</ul>
	<div class="box visible">
	<div class="wrap" style="margin:0px;padding:0px">
	<form action="<?=$action_var?>" method="POST" name="processcategory">
	<input type="hidden" name="mode" value="update_group"/>
	<?if (is_array($news)){?>
		<p align=center>
			<?$navigation_script="$action_var?t=$t&";include "$root_dir/tpl/navigation.tpl";?>
		</p>
	<?}?>
	<table cellpadding="0" cellspacing="0" width="100%" class=stbl>
 	<tr align=center>
		<td width=10 class="headerstyle">#</td>
		<td width=100% class="headerstyle"><?=$lng["lbl_description"]?></td>
		<td width=10 class="headerstyle"><?=$lng["lbl_archive"]?></td>
		<td width=100 class="headerstyle"><?=$lng["lbl_active"]?></td>
		<td width=100 class="headerstyle"><?=$lng["lbl_delete"]?></td>
 	</tr>
	<?foreach ($news as $i => $l){?>
	<tr valign=top>
		<td class=mtd>
			<?=$i+1?>
			<input type='hidden' name="posted_data[<?=$l["id"]?>][id]" value='<?=$l["id"]?>'>
		</td>
		<td class=mtd>
			<a href="<?=$action_var?>?nid=<?=$l["id"]?>&mode=edit&page=<?=$page?>&t=<?=$t?>#nfragment-1"><b><?=$l["date"]?></b></a>	
			<p><?echo strip_tags($l["description"])."...";?>
		</td>
		<td class=mtd width="100" align=center>
			<input type="checkbox" name="posted_data[<?=$l["id"]?>][archive]" <?if ($l["archive"]=="Y") echo("checked");?>   >
		</td>
		<td class=mtd width="100" align=center>
			<input type="checkbox" name="posted_data[<?=$l["id"]?>][active]" <?if ($l["active"]=="Y") echo("checked");?>   >
		</td>
		<td class=mtd width="60" align=center><input type=checkbox name='posted_data[<?=$l["id"]?>][del]'></td>
	</tr>
	<?}?>
	<tr>	
		<td align=right colspan=5 bgcolor="#ffffff">
			<input class=btn type=submit value='<?=$lng["lbl_update"]?>'>&nbsp;
		</td>
	</tr>
	</table>
	</form>
	</div>
	</div>
	<form ENCTYPE="multipart/form-data" action="<?=$action_var?>" method=POST name="newsformp">
	<input type="hidden" name="mode" value="<?=$actionmode?>"/>
	<input type="hidden" name="nid" value="<?=$nid?>"/>	

	<div class="box">
	<table cellpadding="0" cellspacing="0" width="100%"  class="stbl">
		<tr>
		<td class=mtd valign=top><b><?=$lng["lbl_date"]?></b></td>
		<td class=mtd>
		
		<input type="text" name="posted_data[d]" value="<?=$posted_data["d"]?>" size=2 maxlength="2">
		.
		<input type="text" name="posted_data[m]" value="<?=$posted_data["m"]?>" size=2 maxlength="2">
		.
		<input type="text" name="posted_data[y]" value="<?=$posted_data["y"]?>" size=2 maxlength="2">
		</td>
	</tr>
	<tr>
		<td class=mtd valign=top><b><?=$lng["lbl_name"]?></b></td>
		<td class=mtd><input style="width:350px" type="text" name="posted_data[name]" value="<?=$posted_data["name"]?>"/></td>
	</tr>
	<tr>
		<td class=mtd valign=top><b><?=$lng["lbl_description"]?></b></td>
		<td class=mtd>
		<textarea cols="80" id="editor1" name="posted_data[description]" rows="10" ><?=$posted_data["description"]?></textarea>
		<script>
		CKEDITOR.replace( 'editor1', {
			width: '100%',
			height: 400
		} );
		</script>

	<?/*
		include_once "../ck_editor/ckeditor.php";
		$CKEditor = new CKEditor();
		$CKEditor->basePath = '../ck_editor/';
		$CKEditor->config['width'] = "100%";
		$CKEditor->config['height']="305";
		$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
		$CKEditor->editor('posted_data[description]', $posted_data["description"]);
	*/	
 	?>
		</td>
	</tr>
	<tr>
		<td class=mtd valign=top><b><?=$lng["lbl_text"]?></b></td>
		<td class=mtd>
		<textarea cols="80" id="editor2" name="posted_data[news]" rows="10" ><?=$posted_data["news"]?></textarea>
		<script>
		CKEDITOR.replace( 'editor2', {
			width: '100%',
			height: 400
		} );
		</script>

	<?
		/*$CKEditor = new CKEditor();
		$CKEditor->basePath = '../ck_editor/';
		$CKEditor->config['width'] = "100%";
		$CKEditor->config['height']="305";
		$CKEditor->textareaAttributes = array("cols" => 80, "rows" => 10);
		$CKEditor->editor('posted_data[news]', $posted_data["news"]);
		*/
 	?>
		</td>
	</tr>
	<tr>
		<td class=mtd><b>Тип:</b></td>
		<td><?print_select($type_news,$posted_data['type'],'posted_data[type]')?></td>
	</tr>
	<tr>
		<td class=mtd><b><?=$lng["lbl_active"]?></b></td>
		<td><input type="checkbox" name="posted_data[active]" <?if ($posted_data["active"]=="Y") echo("checked");?>   ></td>
	</tr>
	<tr>
 		<td colspan=2><input type=submit class=btn value='<?if ($actionmode=="add") echo("$lng[lbl_add]");else echo("$lng[lbl_update]");?>'></td>
 	</tr>
	</table>	
	</div>
	<div class="box">
		<table width="100%">
<tr>
	<td class="text"  valign="center">
	<input id="order_array" name="order_array" type="hidden" value="" />
					<div id="imageList">
					<?if (!empty($images)){
						foreach ($images as $a=>$b){
							echo'<div class="icon">
								<div class="imgbox"><img src="'.$b['icon'].'"/></div>
								<br/><textarea name="posted_date['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
								<br/><input name="posted_date['.$b['id'].'][item]" type="checkbox">'; 
								if ($b['avail']=='N') echo '<br/>скрыто';
							echo '</div>';
						}
						}else echo '<p align="center">нет загруженных изображений</p>';?>
					</div>
					
						<div class="cls"></div>
						<table id="action_mode_box" <?if (empty($images)) echo "style='display:none'";?>>
							<tr>
								<td style="color:#333;font:normal 12px arial"><b>Действие</b></td>
								<td><?print_Select ($action_array," ","action_mode");?></td>
							</tr>
						</table>
					
					</td>
					<td width="550" valign="top">
					<div class="wrap">
						<div id="uploader"></div>
					</div>
					<script type="text/javascript">
					// Convert divs to queue widgets when the DOM is ready
					$(function() {
					$("#uploader").pluploadQueue({
					// General settings
					runtimes : 'html5,gears,flash,silverlight,browserplus',
					url : '/admin/upload.php?mode=news&pref=<?=str_replace(".","_",$posted_data["date"])?>&cat=<?=$nid?>',
					max_file_size : '10mb',
					chunk_size : '1mb',
					unique_names : true,

					// Resize images on clientside if we can width : 900, 
					resize : {height : 1600, quality : 100},//

					// Specify what files to browse for
					filters : [
						{title : "Image files", extensions : "jpg,gif,png"}
					],

					// Flash settings
					flash_swf_url : 'js/plupload.flash.swf',
					silverlight_xap_url : 'js/plupload.silverlight.xap',
					init : {
						FileUploaded: function(up, file, info) {
						var uploader = $('#uploader').pluploadQueue();
						if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
							$.get("/admin/ajax_listener.php?mode=news&cat=<?=$nid?>",function(data){
								$("#imageList").html(data);
								$("#action_mode_box").fadeIn("fast");
							});
						}
					}
				}
			});
		});

	$("#imageList").sortable({
	    placeholder: "ui-state-highlight",
	    opacity: 0.6,
	    update: function(event, ui) {
			$("input#order_array").val($('#imageList').sortable('serialize'));
	    }
	});
	</script>
	</td>
</tr>
	<tr>
 		<td colspan=2><input type=submit class=btn value='<?if ($actionmode=="add") echo("$lng[lbl_add]");else echo("$lng[lbl_update]");?>'></td>
 	</tr>
</table>

	</div>
	</form>
	
	</div>
	
