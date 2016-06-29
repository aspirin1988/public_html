<div class="wrap">
<b>Разделы</b><br/>
	<div class="category_scroll">
	<?recur_show_tree($action_var,$root_catalog_category['categoryid'],"submenub");?>
	</div>
</div>

<script src="/js/jquery.form.js" type="text/javascript"></script>
<script src="/js/jquery.validate.min.js" type="text/javascript"></script>
<script src="/js/jquery.timers-1.2.js" type="text/javascript"></script>
<script src="/tpl/admin/js/jquery.history_remote.pack.js" type="text/javascript"></script>
<script type="text/javascript" src="/tpl/admin/js/tabs.js"></script>
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



<?if (is_array($catalogue)){?>
<FORM action="" method=POST name="updatedata">
<input type="hidden" name="mode" value="all_update"/>
<input type="hidden" name="cat" value="<?=$cat?>"/>
<input type="hidden" name="order_tbl_array" id="order_tbl_array" value="">
<div class="wrap">
<div id="cat_box">
<?
	foreach ($catalogue as $a=>$b){
		if ($b['cicon']=='Y') $cicon='sdw'; else $cicon='';
		if ($b['id']==$cid) $vactive='vactive'; else $vactive='';
		
		echo '
		<div class="citembox '.$b['active'].' '.$cicon.' '.$vactive.'">
			<input type=hidden name="posted_data['.$b["id"].'][order]" value="'.$a.'">
			<div class="citem">
				<a href="'.$action_var.'?cat='.$b["cat"].'&cid='.$b["id"].'&mode=edit#fragment-1">'.$b['icon'].'</a>
				<textarea name="posted_data['.$b["id"].'][article]">'.$b["article"].'</textarea>
			</div>
			<div class="cbox">
				<input type="checkbox" name="posted_data['.$b["id"].'][action]"> 
				<a href="'.$action_var.'?cat='.$b["cat"].'&cid='.$b["id"].'&mode=clone#fragment-1"><img src="/img/clone_icon.png"></a>
			</div>
			<div class="mrk">'.$b['new'].'</div>
		</div>';
	}
?>
</div>

<div class="cls"></div>
<p align="right">
Переместить в: <?print_select($all_categories,$cat,"mcat");?>
</p>
<p>
Действие: <?print_select($action,$actionvalue,"actionvalue");?> <input class="btn" type=submit name='ent' value='Обновить'> 
</p>

</div>
</FORM>
<?}?>


<div class="wrap" id="container-1">
<h2>
	<?if ($actionmode=="add") echo("$lng[lbl_add]");else echo("$lng[lbl_update]");?>
	<?if ($actionmode=="update") echo("<a href='$action_var?cat=$cat' class='hdr'>$lng[lbl_add]</a>");?>
</h2>
	<?if (!empty($message_ok)) echo '<p class="ok_message">'.$message_ok.'</p>';?>
    <ul>
        <li><a href="#fragment-1"><span>Общие данные</span></a></li>
        <li><a href="#fragment-2"><span>Изображения</span></a></li>
        <li><a href="#fragment-3"><span>Ростовка</span></a></li>
    </ul>
    
	<div id="fragment-1" class="frame_box">
	<div class="m_message"></div>
	<form enctype="multipart/form-data" id="addproduct" action="<?=$action_var?>" method="POST" name="addp">
	<input type="hidden" name="mode" value="<?=$actionmode?>"/>
	<input type="hidden" name="cid" value="<?=$cid?>"/>
	<input type="hidden" name="cat" value="<?=$cat?>"/>
	<input type="hidden" name="page" value="<?=$page?>"/>
	
	<table cellpadding="0" width="100%" cellspacing="0" border="0" class="enterForm">
 	<tr>
		<td width="120" class="hint"><?=$lng["lbl_article"]?></td>
		<td class="value"><textarea class="inp" id="posted_data[article]" name="posted_data[article]"><?=$posted_data["article"]?></textarea></td>
	</tr>
	<tr>
		<td class="hint"><?=$lng["lbl_model"]?></td>
		<td class="value">
		<textarea class="inp" id="posted_data[model]" name="posted_data[model]"><?=$posted_data["model"]?></textarea>
		</td>
	</tr>
	<?/*
	<tr>
		<td class="hint"><?=$lng["lbl_title"]?></td>
		<td class="value"><input type="text" class="inp" name="posted_data[title]" id="posted_data[title]" value="<?=$posted_data["title"]?>"/></td>
	</tr>
	<tr>
		<td class="hint"><?=$lng["lbl_meta_keywords"]?></td>
		<td class="value"><textarea class="inp" id="posted_data[meta_keywords]" name="posted_data[meta_keywords]"><?=$posted_data["meta_keywords"]?></textarea></td>
	</tr>
	<tr>
		<td class="hint"><?=$lng["lbl_meta_description"]?></td>
		<td class="value"><textarea class="inp" id="posted_data[meta_description]" name="posted_data[meta_description]"><?=$posted_data["meta_description"]?></textarea></td>
	</tr>
	*/?>
	<tr>
		<td class="hint">Ссылка</td>
		<td class="value"><input type="text" class="inp" name="posted_data[url]" id="posted_data[url]" value="<?=$posted_data["url"]?>"/></td>
	</tr>
	<tr>
		<td class="hint">Тип</td>
		<td class="value">
		<input type="text" class="inp" name="posted_data[type]" id="p_data_type" value="<?=$posted_data["type"]?>"/>
		<?print_select($vtype,$posted_data["type"],"posted_data[type]",'class="cnt sinp" id="type"');?>
		</td>
	</tr>
	<tr>
		<td class="hint">Цвет</td>
		<td class="value">
		<input type="text" class="inp" name="posted_data[color]" id="p_data_color" value="<?=$posted_data["color"]?>"/>
		<?print_select($vcolor,$posted_data["color"],"posted_data[color]",'class="cnt sinp" id="color" ');?>
		</td>
	</tr>
	<tr>
		<td class="hint">Кол-во пуговец</td>
		<td class="value"><input type="text" class="inp" name="posted_data[buttons]" id="posted_data[buttons]" value="<?=$posted_data["buttons"]?>"/></td>
	</tr>
	<tr>
		<td class="hint">Силуэт</td>
		<td class="value">
		<input type="text" class="inp" name="posted_data[silhouette]" id="p_data_silhouette" value="<?=$posted_data["silhouette"]?>"/>
		<?print_select($vsilhouette,$posted_data["silhouette"],"posted_data[silhouette]",'class="cnt sinp" id="silhouette" ');?>
		</td>
	</tr>
	<tr>
		<td class="hint"><?=$lng["lbl_description"]?></td>
		<td class="value">
		<?if ($cat!=""){?>
		<textarea cols="80" id="editor1" name="posted_data[description]" rows="10" ><?=$posted_data["description"]?></textarea>
		<script>
		CKEDITOR.replace( 'editor1', {
			width: '100%',
			height: 400
		} );
		</script>
		<?}?>
		</td>
	</tr>
	<tr>
		<td class="hint"><?=$lng["lbl_price"]?></td>
		<td>
		<table cellpadding="0" cellspacing="0" border="0" class="enterForm">
		<tr>
			<td><?print_select($prices,$posted_data["t_price_t"],"posted_data[t_price_t]",'class="sinp"');?></td>
			<td><?print_select($prices,$posted_data["t_price_a"],"posted_data[t_price_a]",'class="sinp"');?></td>
			<td><?print_select($prices,$posted_data["t_price_b"],"posted_data[t_price_b]",'class="sinp"');?></td>
			<td><?print_select($prices,$posted_data["t_price_c"],"posted_data[t_price_c]",'class="sinp"');?></td>
		</tr>
		<tr>
			<td><input class="sinp" type="text" name="posted_data[t_price]" value="<?=$posted_data["t_price"]?>"/></td>
			<td><input class="sinp" type="text" name="posted_data[price_a]" value="<?=$posted_data["price_a"]?>"/></td>
			<td><input class="sinp" type="text" name="posted_data[price_b]" value="<?=$posted_data["price_b"]?>"/></td>
			<td><input class="sinp" type="text" name="posted_data[price_c]" value="<?=$posted_data["price_c"]?>"/></td>
		</tr>	
		</table>
		</td>
	</tr>
	<tr>
		<td class="hint"><?=$lng["lbl_active"]?></td>
		<td><input <?if ($posted_data["active"]=="Y")echo("checked");?> name="posted_data[active]" type="checkbox"></td>
	</tr>
	
	<tr>
		<td class="hint"><?=$lng["lbl_category"]?></td>
		<td><?print_select($all_categories,$posted_data["cat"],"posted_data[cat]",'class="sinp"');?></td>
	</tr>
	<tr>
		<td class="hint">Наличие в магазинах</td>
		<td><?print_select($sales_outlets,$posted_data["sales_outlets"],"posted_data[sales_outlets][]",' multiple size="10" ');?></td>
	</tr>
	<tr>
 		<td>&nbsp;</td><td><input class="btn" type=submit value='<?if ($actionmode=="add")	echo("$lng[lbl_add]");else echo("$lng[lbl_update]");?>'>
 	</td>
 	</tr>
	
	</table>
	</FORM>
		
		
    </div>
    <div id="fragment-2" class="frame_box">
	<?if ( ($show_fragments)&&($mode!="clone") ){?>
	<form method="post" action=""/>
		<input id="order_array" name="order_array" type="hidden" value="" />
		<input type="hidden" name="cid" value="<?=$cid?>"/>
		<input type="hidden" name="cat" value="<?=$cat?>"/>
		<input name="mode" type="hidden" value="update_group"/>
			<div id="imageList">
			<?if (!empty($images)){
			echo '<h4>Загруженные изображения</h4>';
			foreach ($images as $a=>$b){
			echo'
			<div class="icon">
				<img src="image.php?h=150&f=/img/gallery/'.$b['id']."_image.".$b['image'].'&id='.$b['id'].'"/>
				<br/><textarea name="posted_data['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
				<br/><input name="posted_data['.$b['id'].'][dell]" type="checkbox"/> Удалить
			</div>';
			}
			echo '<br style="clear: both" />
			<input name="vle" type="submit" value="Обновить/Удалить отмеченые" class="btn"/>';
		}	
		?>
		</div>
	</form>
	<form method="post" action="upload.php">
		<div id="uploader" style="height: 330px;">
			<p>Ваш браузер не потдерживает Flash, Silverlight, Gears, BrowserPlus или HTML5.</p>
		</div>
		<br style="clear: both" />
	</form>
	<?}?>
	</div>
    <div id="fragment-3" class="frame_box">
	<?if ($show_fragments){?>
	<form enctype="multipart/form-data" id="addproduct" action="<?=$action_var?>" method="POST" name="addp">
	<input type="hidden" name="mode" value="update_table"/>
	<input type="hidden" name="cid" value="<?=$cid?>"/>
	<input type="hidden" name="cat" value="<?=$cat?>"/>
	<input type="hidden" name="page" value="<?=$page?>"/>
       <?include "table.tpl";?>
	</form>
    <?}?>
	</div>
</div>
<script type="text/javascript">
	
	if ($(".ok_message").length>0){
		$(".ok_message").oneTime(2000, function() {
			$(".ok_message").slideUp(500,0);
		});
	}
	
	$("#addproduct").validate({
		/*submitHandler: function(form) {
		jQuery(form).ajaxSubmit({
			url: "catalogue.php?ajax=true",//ajax_listener.php
			//dataType:"json",
			success: function(data) {
				//alert(data.merror);
				//alert(data);
				if (data.merror=="ok"){
					$("#fragment-1 .m_message").html("<p class='ok_message'>Данные успешно добавлены.<\/p>");
					$("#add_from_card_"+id).oneTime(10000, function() {
						$("#fragment-1 .m_message").fadeTo(500,0);
					});
				}else{
					$("#fragment-1 .m_message").html("<div class='error_message'>"+data.merror+"</div>");
				}
				$("#fragment-1 .m_message").fadeTo(1000,1);
		    }
		});
		},*/
		rules: {
			"posted_data[article]": {
				required: true,
				minlength: 2
			}
		},
		messages: {
			"posted_data[article]": "Введите артикул"
		}
	});

	$('#container-1').tabs({ fxFade: true, fxSpeed: 'fast'<?if (!$show_fragments) echo ",disabled: [2,3]";?>});
	$("#fragment-1 .m_message").fadeTo(0,0);
	
	var cscroll=$(".category_scroll");
	if (cscroll.height()>400) cscroll.css({height:"400px"});
	
</script>


<?if ($show_fragments){?>
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
		url : '/admin/upload.php?mode=catalogue&cat=<?=$cid?>',
		max_file_size : '25mb',
		chunk_size : '200kb',//1mb
		unique_names : true,
		
		<?if ($config['uploadresize']=='Y') echo "resize : {height : 1200, quality : 90},"; // Resize images on clientside if we can width : 900, ?>

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
				//alert("catalogue.php?mode=edit&cid=<?=$cid?>&cat=<?=$cat?>#fragment-2");
				window.location.replace("/admin/catalogue.php?mode=edit&cid=<?=$cid?>&cat=<?=$cat?>&tt=<?=time()?>#fragment-2");
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
</script>
<?}?>
<script type="text/javascript">
	$(function(){
	  $("#imageList").sortable({
	    placeholder: "ui-state-highlight",
	    opacity: 0.6,
	    update: function(event, ui) {
			$("input#order_array").val($('#imageList').sortable('serialize'));
	    }
	  });
	  
	  $("#cat_box").sortable({
	    placeholder: "ui-state-highlight",
	    opacity: 0.6,
	    update: function(event, ui) {
			$("input#order_tbl_array").val($('#cat_box').sortable('serialize'));
	    }
	  });
	  
	});
	$(".cnt").change(function(){
		var _name=$(this).attr("id");
		$("#p_data_"+_name).val( $(this).text() );
	});
</script>