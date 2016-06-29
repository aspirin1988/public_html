
<h2>Разделы</h2>
<div class="border_wrapp"><div class="wrapp rb">
	<?recur_show_tree($action_var,$start_cat,"submenub");?>
</div></div>
<h3>Наименования</h3>
<div class="border_wrapp"><div class="wrapp rb">

	<?
	if (!empty($products)){
	/*
	$navigation_script="$action_var?cat=$cat&";
	include "$root_dir/tpl/navigation.tpl";*/
	?>

<form action="" method="POST" name="updatedata">
<input type="hidden" name="mode" value="updateall">
<input type="hidden" name="order_tbl_array" value=""/>

<table id="sorprodtable" class="stbl" style="width:100%">
 	<tr align="center" class="nodrop nodrag">
		<td width="10" class="headerstyle">#</td>
		<td width="10" class="headerstyle">id</td>
		<td width="100" class="headerstyle">Изображение</td>
		<td width="100" class="headerstyle">Артикул</td>
		<td class="headerstyle">Наименование</td>
		<td class="headerstyle">Цена</td>
		<td width="100" class="headerstyle"><?=$lng["lbl_move"]?></td>
		<td width="100" class="headerstyle"><?=$lng["lbl_active"]?></td>
		<td width="100" class="headerstyle"><?=$lng["lbl_delete"]?></td>
 	</tr>
	<?foreach ($products as $a=>$b){?>
	<tr align="center">
		<input type=hidden name='posted_data[<?=$b["id"]?>][id]'>
		<td class="headerstyle"><?=$a?></td>
		<td class="headerstyle"><?=$b["id"]?></td>
		<td>
			<?
				if (!empty($b['icon']))
					echo '<img style="width:100%;height:auto;margin:5px" src="'.$b['icon'].'">';
			?>
		</td>
		<td><?=$b["p1"]?></td>
		<td>
		<?if ($id!=$b["id"]){?>
		<a href="<?=$action_var?>?cat=<?=$b["cat"]?>&id=<?=$b["id"]?>&mode=show&page=<?=$page?>"><?=$b["name"]?></a>
		<?}else echo("<span class=marker><b>$b[name]</b></span>");?>
		</td>
		<td width="100"><?=$b["price"]?></td>
		<td><input type="checkbox" name='posted_data[<?=$b["id"]?>][move]'/></td>
		<td><input type="checkbox" name='posted_data[<?=$b["id"]?>][active]' <?if ($b["active"]=="Y") echo("checked");?>/></td>
		<td><input type="checkbox" name='posted_data[<?=$b["id"]?>][del]'/></td>
	</tr>
<?}?>
</table>		
	<p align="right">
	Переместить <?print_select($move_categoryes,$move_category_value,"move_category_value")?>
	</p>
	<p align="center">
		<input class="btn rb" type="submit" name='ent' value="Обновить">
	</p>
	</form>
	<?}else echo '<p align="center"><i>нет наименований</i></p>';?>
</div></div>
	<h2>
	<?
		 if ($actionmode=="add")
			  echo "$lng[lbl_add]";
		 else echo "$lng[lbl_update] | <a href=\"$action_var?cat=$cat\">$lng[lbl_add]</a>";
	?>
	</h2>
<div class="border_wrapp"><div class="wrapp rb" id="container-1">
	<?if (!empty($meror)) echo $meror;?>
	
	<?/*<ul class="tabs">
		<li class="ttabs" class="current">Общие данные</li>
		<li class="ttabs">Изображения</li>
	</ul>*/?>
	
	<div id="fragment-1" class="box visible frame_box">
	
	<form action="" method="POST" name="adddata">
	<input type="hidden" name="mode" value="<?=$actionmode?>"/>
	<input type="hidden" name="cat" value="<?=$cat?>"/>
	<input type="hidden" name="id" value="<?=$id?>"/>
	<input type="hidden" name="posted_data[type]" value=""/>
	<input type="hidden" name='posted_data[p3]' value=''/>
	<input type="hidden" name='posted_data[p2]' value=''/>
	<input type="hidden" name='posted_data[p4]' value=''/>
	<input type="hidden" name='posted_data[type]' value=''> 	
	<input type="hidden" name='posted_data[partner_price]' value=''> 
	
	<table class="stbl" style="width:100%">
	<tr> 
		<td class="headerstyle">Наименование</td>
		<td><input class="inp rb" type="text" name='posted_data[name]' value='<?=$posted_data["name"]?>'></td>
		<td></td>
	</tr> 
	<tr> 
		<td class="headerstyle">Ссылка</td>
		<td><input class="inp rb" type="text" size=50  name='posted_data[url]' value='<?=$posted_data["url"]?>'></td>
		<td></td>
	</tr> 
	<tr> 
		<td class="headerstyle">Заголовок</td>
		<td><input class="inp rb" type="text" name='posted_data[title]' value='<?=$posted_data["title"]?>'></td>
		<td></td>
	</tr> 
	<tr> 
		<td class="headerstyle">Описание</td>
		<td><textarea class="rb" style="width:100%" rows="4" name='posted_data[meta_description]'><?=$posted_data["meta_description"]?></textarea></td>
		<td></td>
	</tr> 
	<tr> 
		<td class="headerstyle">Ключевые слова</td>
		<td><textarea class="rb" style="width:100%" rows="4" name='posted_data[meta_keywords]'><?=$posted_data["meta_keywords"]?></textarea></td>
		<td></td>
	</tr> 
	<tr> 
		<td class="headerstyle">Артикул</td>
		<td><input class="inp rb" type="text" name='posted_data[p1]' value='<?=$posted_data["p1"]?>'></td>
		<td></td>
	</tr> 
	<tr>
		<td class="headerstyle"><?=$lng["lbl_description"]?></td>
		<td>
		<textarea cols="80" id="editor1" name="posted_data[description]" rows="10" ><?=$posted_data["description"]?></textarea>
		<script>
		CKEDITOR.replace( 'editor1', {
			width: '100%',
			height: 400
		} );
		</script>
 		</td>
 	</tr>
	<tr>
		<td class="headerstyle">Цена</td>
		<td><input class="inp rb" type="text" name='posted_data[price]' value='<?=$posted_data["price"]?>'></td>
		<td></td>
	</tr> 
	
	
 	
	<tr>
		<td colspan=2 align="center"><input class="btn rb" type="submit" name='ent' value="Отправить"></td>
	</tr>
	</table>
	</form>
	
	<?/*
	</div>
	<div id="fragment-2" class="box frame_box">
	*/?>
	
	<?if ( ($show_fragments)&&($mode!="clone") ){?>
	<form method="post" action=""/>
		<input id="order_array" name="order_array" type="hidden" value="" />
		<input type="hidden" name="id" value="<?=$id?>"/>
		<input type="hidden" name="cat" value="<?=$cat?>"/>
		<input type="hidden" name="imageListVal" id="imageListVal" value=""/>
		
		<input name="mode" type="hidden" value="dell_images"/>
			
			<?
			if (!empty($images)){
			echo '<h4>Загруженные изображения</h4>
			<div id="imageList">';
			foreach ($images as $a=>$b){
			echo'
			<div class="icon rb">
				<span><img class="rb" src="'.$b['icon'].'"/></span>
				<a href="'.$b['image'].'" class="f_box"><img src="/tpl/admin/plus.png"/></a>
				<a href="crop.php?w=300&h=300&f='.$b['image'].'&ff=../..'.str_replace('icon','icon',$b["icon"]).'" class="k-icon"><img src="/tpl/admin/crop.png"/></a>
				<br/><textarea name="posted_data['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
				<br/><input name="posted_data['.$b['id'].'][dell]" type="checkbox"/> 
			</div>';
			}//../../models/$posted_data["type"]/bigicon/
			echo '</div>';
		}	else echo '<p align="center">Нет загруженых изображений</p>';
		?>
			<br style="clear: both" />
			<p align="right"><?print_select($action,$action_image,"action_image");?></p>
			<p align="center"><input name="vle" type="submit"  value="Обновить/Удалить отмеченые" class="btn rb"/></p>
		</form>
		</div>
		
		
		
		
		
	
	<form method="post" action="upload.php">
		<div id="uploader" style="height: 330px;">
			<p>Ваш браузер не потдерживает Flash, Silverlight, Gears, BrowserPlus или HTML5.</p>
		</div>
		<br style="clear: both" />
	</form>
	<?}?>
		
	</div>
	
</div></div>

<script src="/admin/croppic/croppic.min.js" type="text/javascript"></script>
<link href="/admin/croppic/assets/css/croppic.css" type="text/css" rel="stylesheet">


<div id="f1">
	<div id="cropContainerPreload"></div>
</div>

<script type="text/javascript">
	$(".k-icon").fancybox({
		maxWidth	: '70%',
		maxHeight	: '70%',
		fitToView	: false,
		width		: '330',
		height		: '330',
		autoSize	: true,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		type	 : 'iframe'
	});
	


	$('#sorprodtable').tableDnD({
        onDrop: function(table, row) {
			$("input#order_tbl_array").val($("#sorprodtable").tableDnDSerialize());
        }
    });
	
	$(function(){
	  $("#sortable").sortable({
	    placeholder: "ui-state-highlight",
	    opacity: 0.6,
	    update: function(event, ui) {
			$("input#s_order_array").val($('#sortable').sortable('serialize'));
	    }
	  });
	});
		$(document).ready(function() {
		$("a.fancy_box").fancybox(
			{
				'overlayOpacity' : 0.4
			}
		);	
	});
	
	
	  $("#imageList").sortable({
	    placeholder: "ui-state-highlight",
	    opacity: 0.6,
	    update: function(event, ui) {
			$("input#imageListVal").val($('#imageList').sortable('serialize'));
	    }
	  });
	
	
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
		url : '/admin/-upload.php?type=<?=$posted_data['type']?>&cat=<?=$id?>',
		max_file_size : '25mb',
		chunk_size : '0.5mb',
		unique_names : true,
		// Resize images on clientside if we can width : 900, 
		resize : {height : 1024, quality : 80},

		// Specify what files to browse for
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"}
		],
		flash_swf_url : 'js/plupload.flash.swf',
		silverlight_xap_url : 'js/plupload.silverlight.xap',
		init : {
			FileUploaded: function(up, file, info) {
			var uploader = $('#uploader').pluploadQueue();
			if (uploader.files.length === (uploader.total.uploaded + uploader.total.failed)) {
				window.location.replace("/admin/products.php?id=<?=$posted_data['id']?>&cat=<?=$cat?>&mode=show");
			}
			}
		}
	});
});
</script>
<?}?>
