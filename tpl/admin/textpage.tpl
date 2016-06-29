	<h2>Разделы</h2>
	<div class="border_wrapp" ><div class="wrapp rb">	
		<?recur_show_tree($action_var,0,"submenub","&mode=edit");//&p_id=$p_id?>
	</div></div>

	<?if (!empty($text_pages)){?>
	<h2>Тексты страниц</h2>
	<div class="border_wrapp" ><div class="wrapp rb">	
	
	<FORM ENCTYPE="multipart/form-data" action="<?=$action_var?>" method=POST name="alladdtext">
 	<input type="hidden" name="mode" value="updatearticletext"/>
 	<input type="hidden" name="cat" value="<?=$cat?>"/>
	
	
	<table cellpadding="0" cellspacing="0" class="stbl"  width="100%" >
 	<tr align="center">
		<td class="headerstyle" width="30">#</td>
		<td class="headerstyle">Имя</td>
		<td class="headerstyle" width="70">Тип</td>
		<td class="headerstyle" width="70">Активен</td>
		<td class="headerstyle" width="70">Удалить</td>
	</tr>
		<?
		//r_print_r($text_pages);
		foreach ($text_pages as $a=>$b){
			if ($b['category']=='') $b['category']=__substr(strip_tags($b['text']), 100);
		?>
			<input type="hidden" name="p_data[<?=$b['pageid']?>][id]"/>
			<tr align="center">
				<td class="mtd"><?=($a+1)?></td>
				<td class="mtd" align="left">
				<?if ($p_id==$b['pageid'])
					echo "<b><a href='$action_var?cat=$b[parentid]&p_id=$b[pageid]&mode=edit'>$b[category]</a></b>";
					else echo "<a href='$action_var?cat=$b[parentid]&p_id=$b[pageid]&mode=edit'>$b[category]</a>";
				?>	
				</td>
				<td class="mtd">
					<?
						if ($b['type']=='category') 
							echo "<b><a href='$action_var?cat=$b[parentid]&p_id=$b[pageid]&mode=edit'>раздел</a></b>"; 
						else echo '<b>статья</b>';
					?>
				</td>
				<?if ($b["type"]=='article'){?>
				<td class="mtd"><input type="checkbox" name="p_data[<?=$b['pageid']?>][avail]" <?if ($b["avail"]=="Y") echo("checked");?>/></td>
				<td class="mtd"><input type="checkbox" name="p_data[<?=$b['pageid']?>][dell]"/></td>
				<?}else echo '<td colspan="2"></td>'?>
			</tr>
		<?}?>
	</table>
	<p align="right"><input type="submit" class="btn rb" value="Применить"/></p>
	</form>
	</div></div>
	<?}?>
	
	<h2>
		<?if ($cat.$p_id!='') 
			 echo "Редактирование <a href='$action_var?cat=$cat&mode=add_article'>Добавить</a>";
		else echo "Добавить";
		?>
	</h2>
	<div class="border_wrapp" ><div class="wrapp rb">	
 	
	<FORM ENCTYPE="multipart/form-data" action="<?=$action_var?>" method=POST name=addtext>
 	<input type="hidden" name="mode" value="<?=$actionmode?>">
 	<input type="hidden" name="cat" value="<?=$cat?>">
	<input type="hidden" name="p_id" value="<?=$p_id?>"/>
	
	<div class="section">

	<ul class="tabs">
		<li class="ttabs" class="current">Текст страницы</li>
		<li class="ttabs">Параметры страницы</li>
		<li class="ttabs">Изображения страницы</li>
	</ul>
	<div class="box visible">
		<textarea cols="80" id="editor1" name="text" rows="10" ><?=$posted_date["text"]?></textarea>
		<script>
		CKEDITOR.replace( 'editor1', {
			width: '100%',
			height: 400
		} );
		</script>
		<p><?print_select($type,$posted_date["type"],"posted_date[type]");?></p>
	</div>
	<div class="box">
	<table cellpadding="0" cellspacing="0" class=stbl width="100%">
 	<tr>
		<td class=headerstyle width=50%><?=$lng["lbl_category"]?></td>
		<td class=headerstyle width=50%><?=$lng["lbl_add"]?>...</td>
		<td class=headerstyle width=150><?=$lng["lbl_hide"]?></td>
 	</tr>
	<tr>
		<td><input type=text name="posted_date[category]" value="<?=$posted_date["category"]?>" style="width:100%"></td>
		<td><?print_select($all_categories,$posted_date["parentid"],"posted_date[parent]");?></td>
		<td><input type="checkbox" name="posted_date[avail]" <?if ($posted_date["avail"]=="Y") echo("checked");?>/></td>
	</tr>
	<tr>
		<td	colspan=4 class=mtd>
		<p><b><?=$lng["lbl_link"]?></b><br>
		<input type="text" style="width:100%" name="posted_date[location]" value="<?=$posted_date["location"]?>"/>
		</td>
	</tr>
	<tr>
		<td	colspan=4 class=mtd>
		<p><b><?=$lng["lbl_description"]?></b><br>
		<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_date[description]"><?=$posted_date["description"]?></TEXTAREA>
		</td>
	</tr>
	<?if ( ($actionmode=="update")||($actionmode=="update_textpage") ){?>
	<tr>
 	<td	colspan=4 class=mtd>
		<b><?=$lng["lbl_icon"]?></b><br/>
		<?
		if (!empty($posted_data["icon"])){
			echo '
			<img src="/img/categories/icons/'.$posted_data["categoryid"].'_image.'.$posted_data["icon"].'" style="float:left;margin:10px;">
			<br/><input type="checkbox" name="posted_data[dell_icon]" > Удалить';
		}
		?>
		<input type="file" size="25" name="category_icon">
 	</td>
	</tr>
	<?}?>
	<tr>
		<td	colspan=4 class=mtd>
			<input type="checkbox" name="posted_date[subcategories]" <?if ($posted_date["subcategories"]=="Y") echo("checked");?> > Показывать подразделы
			<input type="checkbox" name="posted_date[showicon]" <?if ($posted_date["showicon"]=="Y") echo("checked");?> > Показывать иконки
			<input type="checkbox" name="posted_date[readalso]" <?if ($posted_date["readalso"]=="Y") echo("checked");?> > Показывать "читать так же"
		</td>
	</tr>
	
	<tr>
		<td	colspan=4 class=headerstyle>SEO:</td>
	</tr>
	<tr>
		<td	colspan=4 class=mtd>	
		<p><b><?=$lng["lbl_title"]?></b><br>
		<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_date[title]"><?=$posted_date["title"]?></TEXTAREA>
		<p><b><?=$lng["lbl_meta_description"]?></b><br>
		<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_date[meta_descr]"><?=$posted_date["meta_descr"]?></TEXTAREA>
		<p><b><?=$lng["lbl_meta_keywords"]?></b><br>
		<TEXTAREA style="width:100%" cols="25" rows="4" name="posted_date[meta_keywords]"><?=$posted_date["meta_keywords"]?></TEXTAREA>
		</td>
	</tr>
	</table>	
	</div>
	<div class="box">
		<input id="order_array" name="order_array" type="hidden" value="" />
			<div id="imageList">
					<?if (!empty($images)){
						foreach ($images as $a=>$b){
							echo'<div class="icon"> 
								<span><img src="'.$b['icon'].'"/></span>
								<br/><textarea name="posted_date_img['.$b['id'].'][alt]">'.$b['alt'].'</textarea>
								<br/><input name="posted_date_img['.$b['id'].'][item]" type="checkbox">'; 
								if ($b['avail']=='N') echo '<br/>скрыто';
							echo '</div>';
						}
					}else echo '<p align="center">нет загруженных изображений</p>'?>
			</div>
			<div class="cls"></div>
						<table id="action_mode_box" <?if (empty($images)) echo "style='display:none'";?>>
							<tr>
								<td style="color:#333;font:normal 12px arial"><b>Переместить в категорию</b></td>
								<td><?print_Select ($all_categories,$cat,"m_category",'style="width:275px"');?></td>
							</tr>
							<tr>
								<td style="color:#333;font:normal 12px arial"><b>Действие</b></td>
								<td><?print_Select ($action_array," ","action_mode");?></td>
							</tr>
						</table>
					<h3>Добавить изображения</h3>
					<div class="wrap">
						<div id="uploader"></div>
					</div>
					<script type="text/javascript">
					// Convert divs to queue widgets when the DOM is ready
					<?
						if ($posted_date["type"]!='category') $_cat=$cat.'|'.$p_id; else $_cat=$cat;
					?>
					$(function() {
					$("#uploader").pluploadQueue({
					// General settings
					runtimes : 'html5,gears,flash,silverlight,browserplus',
					url : '/admin/upload.php?mode=textpage&pref=<?=$posted_date["location"]?>&cat=<?=$_cat?>',
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
							window.location='textpage.php?cat=<?=$cat?>&p_id=<?=$p_id?>&mode=edit';
							//$("#imageList").load("/admin/ajax_listener.php?mode=textpageimagelist&cat=<?=$cat?>");
							//$("#action_mode_box").fadeIn("fast");
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
					
					
	</div>
 	<p align="center"><input type="submit" class="btn rb" value="Сохранить"/></p>
 	</FORM>
	</div>
</div>


</div>

<?if ($cat!=""){?><?}?>
<?if ($p_id!=""){?>
<link rel="stylesheet" type="text/css" href="/js/nestable/style.css"/>
<script src="/js/nestable/jquery.nestable.js" type="text/javascript"></script>

<h2>Прикрепить статьи</h2>

<div class="border_wrapp" ><div class="wrapp rb">

<table class="sform" width="100%">
 <tr valign="top">
	<td id="nestable_2" class="dd bwrap">
		<form action="" method="post" name="article">
		<INPUT type="hidden" name="mode" value="add_article_mode"/>
		<INPUT type="hidden" name="cat" value="<?=$cat?>"/>
		<INPUT type="hidden" name="p_id" value="<?=$p_id?>"/>
		<?
			//r_print_r($articles);
			if (empty($articles)){
			echo "<ol class=\"dd-list\">\n\t";
			echo '<li class="dd-item" data-id="z1"><div class="dd-handle">&nbsp;</div></li>';
			echo "</ol>\n\t";
			}else {
				echo "<ol class=\"dd-list\">\n\t";
				foreach ($articles as $a=>$b)
					echo '<li class="dd-item" data-id="z'.$b['categoryid'].'"><div class="dd-handle">'.$b['category'].'<input type="hidden" name="articles[]" value="'.$b['categoryid'].'"/></div></li>';
				echo "</ol>\n\t";
			}
		?>
		<input type="submit" name="a-snd" class="btn rb" value="Прикрепить"/>
		</form>	
	</td><td id="nestable" class="dd bwrap">
		<?recur_list_show_tree("#",31);?>
	</td>
 </tr>
</table>
</div></div>
<script type="text/javascript">
	var mm=$('#nestable').nestable({
        group: 1
    }).nestable('collapseAll');
	var mm=$('#nestable_2').nestable({
        group: 1
    });
	$('.dd').nestable('collapseAll');
</script>
 
 

<?}?>