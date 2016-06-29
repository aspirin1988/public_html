<br>
<?php 
$tpage=$page;
if ($total_pages-1 > 1){?>
Страницы: &nbsp;
	<?if ($navigation_page > 1){?><a href="<?=$navigation_script?>page=1" class=navtext><img align="absmiddle" src="/img/larrow_2.gif" width="9" height="9" alt=":)" border=0 hspace=3 vspace=3></a>
	<a href="<?=$navigation_script?>page=<?=$navigation_page-1?>" class=navtext><img align="absmiddle" src="/img/larrow.gif" width="9" height="9" alt=":)" border=0 hspace=3 vspace=3></a>
	<?}
$up_limit=$page-ceil($config_max_nav_pages/2)+1;
if ($up_limit<=1) $up_limit=1;
$total_pages=$up_limit+$config_max_nav_pages;
if ($total_pages>=$total_nav_pages) 
	{$total_pages=$total_nav_pages;
	 $up_limit=$total_nav_pages-$config_max_nav_pages;
	}
if ($up_limit<=1) $up_limit=1;
for ($page=$up_limit;!($page>=$total_pages);$page++){
	if ($page == $navigation_page){?>&nbsp;<?=$page?>&nbsp;
	<?}else{?>
	&nbsp;<a href="<?=$navigation_script?>page=<?=$page?>" class=navtext><?=$page?></a>&nbsp;
	<?}
if ($page+1==$total_pages){
		if ($navigation_page < $total_super_pages*$config_max_nav_pages){?>...&nbsp; <?=$total_nav_pages-1?><a href="<?=$navigation_script?>page=<?=$navigation_page+1?>"><img align="absmiddle"  src="/img/rarrow.gif" width="9" height="9" alt=":)" border=0 hspace=3 vspace=3></a><?}
	}
}
if ($navigation_page!=$total_nav_pages-1){?><a href="<?=$navigation_script?>page=<?echo($total_nav_pages-1);?>"><img align="absmiddle"  src="/img/rarrow_2.gif" width="9" height="9" alt=":)" border=0 hspace=3 vspace=3></a><?}?>
	<?if (empty($page_all)){?>&nbsp; <a href="<?=$navigation_script?>page=1&page_all=y" class=navtext>Показать все</a><?}else{?>&nbsp; Показать все<?}?>
<?}
$page=$tpage;
?>