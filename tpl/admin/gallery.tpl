<script src="/js/jquery.timers-1.2.js" type="text/javascript"></script>
<script src="/tpl/admin/js/jquery.history_remote.pack.js" type="text/javascript"></script>

<LINK href="/tpl/admin/css/style.css" type="text/css" rel="stylesheet"/>
<LINK href="/tpl/admin/css/tabs.css" type="text/css" rel="stylesheet"/>

<!-- Load Queue widget CSS and jQuery -->
<link rel="stylesheet" type="text/css" href="img/jquery.plupload.queue.css" media="screen">
<!-- Load plupload and all it's runtimes and finally the jQuery queue widget -->
<script type="text/javascript" src="js/plupload.full.js"></script>
<script type="text/javascript" src="js/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="js/ru.js"></script>
<script type="text/javascript" src="js/jquery.tablednd_0_5.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.5.custom.min.js"></script>

<div class="wrap">
	<h3>Разделы</h3>
		<?recur_show_tree($action_var,0,"submenub","&id=$id&aid=$aid&mode=$mode");?>
</div>
<?if ($cat!=''){?>
<?if (!empty($albums)){?>
	<div class="wrap">
	<FORM action="" method=POST name="action_album">
	<input type="hidden" name="mode" value="action_album">
		<h3>Альбомы</h3>
		<table width="100%">
			<tr>
				<td class="headerstyle">название</td>
				<td class="headerstyle" width="100">отметить</td>
			</tr>
			<?foreach ($albums as $sa=>$b){?>
				<tr>
					<td class=mtd>
					<?
						if ($aid==$b['categoryid']) echo $b['category'];
						else echo "<a href='$action_var?cat=$cat&id=$id&aid=$b[categoryid]&mode=$mode'>".$b['category'].'</a>';
					?>
					</td>
					<td>
						<input name="posted_data[<?=$b['categoryid']?>][item]" type="checkbox"> 
					</td>
				</tr>
			<?}?>
			<tr>
				<td colspan="2" class=mtd align="right">
				Переместить в категорию &nbsp;
				<?print_Select ($all_categories,$posted_data["category"],"m_category");?>

				Действие <?print_Select ($action_array," ","action_mode");?>
				<input type=submit value="<?=$lng["lbl_update"]?>" class=btn>
				</td>
			</tr>
		</table>
	</form>
	</div>
<?}?>
<div class="wrap">
<h3>Добавить/редактировать альбом</h3>
<FORM action="" method=POST name="addalbum">
<input type="hidden" name="mode" value="<?=$album_data_action_mode?>">
<input type="hidden" name="albumid" value="<?=$albumid?>">
<input type="hidden" name="cat" value="<?=$cat?>">
<table cellspacing="2" cellpadding="0" border="0" class=tblstyle>
<tr class=headerstyle>
	<td width=200>Название</td>
	<td><input type="text" size=25 name="posted_data[album]" value="<?=$album_data?>"></td>
	<td><input type=submit value="<?=$lng["lbl_send"]?>" class=btn></td>
</tr>
</table>
</FORM>
</div>

<?if (!empty($images)){?>
	<div class="wrap">
	<h3>Загруженные изображения</h3>
	<form method="post" action="gallery.php" name="image_list">
	<input id="order_array" name="order_array" type="hidden" value="" />
	<input type="hidden" name="mode" value="update_all"/>
	<input name="cat" type="hidden" value="<?=$cat?>" />
	<div id="imageList">
	<?foreach ($images as $a=>$b){
		echo'
		<div class="icon">
			<div class="imgbox" style="text-align:center"><img src="'.$b['icon'].'?w=0&h=140"/></div>
			<br/><textarea name="posted_data['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
			<br/><input name="posted_data['.$b['id'].'][item]" type="checkbox"> 
		</div>';
	}
	echo'<br style="clear: both" />';
	?>
	</div>
	<p align=right>
Переместить в категорию &nbsp;
<?print_Select ($albom_all_categories,$posted_data["category"],"move_category");?>

Действие &nbsp;
<?print_Select ($action_array," ","action_mode");?>
<input type=submit value="<?=$lng["lbl_update"]?>" class=btn>
</p>
</form>
</div>	
<?}?>	

<?}?>
<?if ($aid!=''){?>
<div class="wrap">

<h3>
<?
	if ($actionmode=="add")	echo("$lng[lbl_add]");
	else echo("$lng[lbl_update] | <a href=$action_var class=red>$lng[lbl_add]</a>");
?>
</h3>

<?if ($merror!="") echo("<font color=red><ul type=1>$merror</ul></font>");?>

<div id="uploader">	
<FORM ENCTYPE="multipart/form-data" action="<?=$action_var?>" method=POST name=addImage>
<input type="hidden" name="mode" value="<?=$actionmode?>">
<input type="hidden" name="id" value="<?=$id?>">
<input type="hidden" name="cat" value="<?=$cat?>">
<table cellspacing="0" cellpadding="0" border="0" class=tblstyle>
<tr class=headerstyle>
	<td width=200></td><td>
	</td>
</tr>
<?if ($actionmode!="update"){
	for ($img=0;$img<5;$img++){?>
<tr class=headerstyle>
	<td><?=$lng["lbl_icon"]?> 105 x 69
	</td><td>
	<input type="file" size="25" name="icon<?=$img?>">
	</td>
</tr>
<tr class=headerstyle>
	<td><?=$lng["lbl_image"]?> 685 x 450</td><td>
	<input type="file" size="25" name="image<?=$img?>">
	</td>
</tr>

<tr class=headerstyle>
	<td><?=$lng["lbl_alt"]?></td><td>
	<input type="text" size=25 name="posted_data[alt<?=$img?>]" value="">
	</td>
</tr>
<tr><td height=5>&nbsp;</td></tr>
	<?}
	}else{?>
	<input type="hidden" name="posted_data[id]" value="<?=$posted_data["id"]?>">
	<tr valign=top>
	<td class=mtd>
	<?if ($posted_data["icon"]!=""){?>
		<a href="<?=$posted_data["image"]?>" target=_blank>
		<img src="<?=$posted_data["icon"]?>" class=apic alt="<?=$b["alt"]?>" border=0>
		</a>
	<?}?>
	<div>
	<br>Иконка<input type="file" size="25" name="icon">
	<br>Изображение <input type="file" size="25" name="image">
	</div>
	</td><td class=mtd nowrap>
	<?=$lng["lbl_alt"]?> <input type="text" style="width:90%" name="posted_data[alt]" value="<?=$posted_data["alt"]?>">
	</td>
</tr>
<tr>
	<td class=mtd colspan=3>
	<?=$lng["lbl_category"]?>
	<?print_Select ($all_categories,$posted_data["category"],"posted_data[category]");?>
	<p>
	</td>
</tr>
<?}?>
<tr>
	<td colspan=3 class=headerstyle align=right>
	<input type=submit value="<?=$lng["lbl_$actionmode"]?>" class=btn>
	</td>
</tr>
</table>
</div>
</FORM>
</div>



<a href="gallery.php" id="loadmore" style="display:none">Загрузить ещё</a>

<script type="text/javascript">
// Convert divs to queue widgets when the DOM is ready
$(function() {
	function log() {
		var str = "";

		plupload.each(arguments, function(arg) {
			var row = "";

			if (typeof(arg) != "string") {
				plupload.each(arg, function(value, key) {
					// Convert items in File objects to human readable form
					if (arg instanceof plupload.File) {
						// Convert status to human readable
						switch (value) {
							case plupload.QUEUED:
								value = 'QUEUED';
								break;

							case plupload.UPLOADING:
								value = 'UPLOADING';
								break;

							case plupload.FAILED:
								value = 'FAILED';
								break;

							case plupload.DONE:
								value = 'DONE';
								break;
						}
					}

					if (typeof(value) != "function") {
						row += (row ? ', ' : '') + key + '=' + value;
					}
				});

				str += row + " ";
			} else { 
				str += arg + " ";
			}
		});

		$('#log').append(str + "\n");
	}
 
	$("#uploader").pluploadQueue({
		// General settings
		runtimes : 'html5,gears,flash,silverlight,browserplus',
		url : '/admin/upload.php?mode=gallery&cat=<?=$aid?>',
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
		// Silverlight settings
		silverlight_xap_url : 'js/plupload.silverlight.xap',
		// PreInit events, bound before any internal events
		
		// Post init events, bound after the internal events
		init : {
			FileUploaded: function(up, file, info) {
			var uploader = $('#uploader').pluploadQueue();
			if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
				window.location.replace("gallery.php?cat=<?=$cat?>&aid=<?=$aid?>&mode=album_edit&tt=<?=time()?>");
			}
			},

			ChunkUploaded: function(up, file, info) {
				// Called when a file chunk has finished uploading
				//log('[ChunkUploaded] File:', file, "Info:", info);
			},

			Error: function(up, args) {
				// Called when a error has occured
				//log('[error] ', args);
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
<?}?>