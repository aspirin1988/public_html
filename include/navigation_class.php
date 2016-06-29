<?php
class navigation{
	
	var $direct;
	var $limit;
	
	var $query;
	var $page;
	
	var $first_page;
	var $objects_per_page;
	var $total_pages;
	var $config_max_nav_pages;
	var $total_nav_pages;
	var $total_super_pages;
	
	var $navigation_script;
	
	function navigation($sqlquery,$config,$limit=10) {
		$direct=get_post_var("direct");
		$query=$sqlquery;
		
		if (!in_array($direct, array("ASC","DESC")) )
			$direct=$config["sort"];
			else $direct="ASC";
		
		//$limit=get_post_var("limit");
		if (!in_array ($limit,Array("5","10","25","50")))
			$limit=$config["amount_comment"];
		
		$objects_per_page=$limit;
		$total_products_in_search = count (func_query ($query));
		$total_nav_pages = ceil ($total_products_in_search/$objects_per_page)+1;
		
		if (empty($_GET["page"])) $page=1; else $page=$_GET["page"];
		if (!empty($_POST["page"])) $page=$_POST["page"];
		if ($page<=0) $page=1;
		
		$this->config_max_nav_pages=5;
		$config_max_nav_pages=$this->config_max_nav_pages;
		$this->first_page = $objects_per_page*($page-1);
		$total_super_pages = (($total_nav_pages-1) / ($config_max_nav_pages ? $config_max_nav_pages : 1));
		$current_super_page = ceil($page / $config_max_nav_pages);
		$start_page = $config_max_nav_pages * ($current_super_page - 1);
		$total_pages = ($total_nav_pages>$config_max_nav_pages ? $config_max_nav_pages+1 : $total_nav_pages) + $start_page;
		if ($total_pages > $total_nav_pages) $total_pages = $total_nav_pages;
		
		if ($page > 1 and $page >= $total_pages) {
		$page = $total_pages - 1;
		$start_page=$page-1;
		$this->first_page = $objects_per_page*($page-1);
		}

		$navigation_page=$page;
		$start_page += 1;
		$this->total_pages=$total_pages;
		$this->page=$page;
		$this->total_nav_pages=$total_nav_pages;
		$this->total_super_pages=$total_super_pages;
		
		$this->objects_per_page=$objects_per_page;
		
	}
	function show_navigation_page(){
		$navigation_page=$this->page;
		$navigation_script=$this->navigation_script;
		
		$config_max_nav_pages=$this->config_max_nav_pages;
		
		$total_super_pages=$this->total_super_pages;
		
		if ($this->total_pages-1 > 1){
			echo 'Страницы:';
				if ($navigation_page > 1) {
					$t_navigation_page=$navigation_page-1;
					echo '
					<a href="'.$navigation_script.'page=1"><img src="/img/larrow_2.gif" width="9" height="9" alt="" border="0"></a>';
					echo '<a href="'.$navigation_script.'page='.$t_navigation_page.'"><img src="/img/larrow.gif" width="9" height="9" alt="" border="0"></a>';
				}
			$page=$this->page;
			$up_limit=$page-ceil($config_max_nav_pages/2)+1;
			$total_nav_pages=$this->total_nav_pages;
			if ($up_limit<=1) $up_limit=1;
			$total_pages=$up_limit+$config_max_nav_pages;
			if ($total_pages>=$total_nav_pages) 
			{
				$total_pages=$total_nav_pages;
				$up_limit=$total_nav_pages-$config_max_nav_pages;
			}
			
			if ($up_limit<=1) $up_limit=1;
			
			for ($page=$up_limit;!($page>=$total_pages);$page++){
			if ($page == $navigation_page)
				 echo ' <span class="nava">'.$page.'</span> ';
			else echo'<a href="'.$navigation_script.'page='.$page.'">'.$page.'</a> ';
	
			if ($page+1==$total_pages){
				if ($navigation_page < $total_super_pages*$config_max_nav_pages){
				$tnavigation_page=$navigation_page+1;
				echo '<span class="navs"> из '.($total_nav_pages-1).'</span>';
				echo '<a href="'.$navigation_script.'page='.$tnavigation_page.'"><img src="/img/rarrow.gif" width="9" height="9" alt="" border="0"/></a>';
			}
		
		if ($navigation_page!=$total_nav_pages-1){
			echo '<a href="'.$navigation_script.'page='.($total_nav_pages-1).'">';
			echo '<img src="/img/rarrow_2.gif" width="9" height="9" alt="" border="0"/></a>';	
		}
		
		if (empty($page_all)) 
			echo ' <a href="'.$navigation_script.'page=1&amp;page_all=y">Показать все</a>';
			else echo ' <span class="navs">Показать все</span>';
		}
		}
		}
	}
	
	function show_simple_navigation_page(){
		$navigation_page=$this->page;
		$navigation_script=$this->navigation_script;
		
		$config_max_nav_pages=$this->config_max_nav_pages;
		
		$total_super_pages=$this->total_super_pages;
		
		if ($this->total_pages-1 > 1){
				if ($navigation_page > 1) {
					$t_navigation_page=$navigation_page-1;
					echo '
					<a href="'.$navigation_script.'page=1" class="nav">
					<img src="/img/larrow_2.gif" width="9" height="9" alt="" border="0"/></a>';
					echo '<a href="'.$navigation_script.'page='.$t_navigation_page.'" class="nav"><img src="/img/larrow.gif" width="9" height="9" alt="" border="0"/></a>';
				}
			$page=$this->page;
			$up_limit=$page-ceil($config_max_nav_pages/2)+1;
			$total_nav_pages=$this->total_nav_pages;
			if ($up_limit<=1) $up_limit=1;
			$total_pages=$up_limit+$config_max_nav_pages;
			if ($total_pages>=$total_nav_pages) 
			{
				$total_pages=$total_nav_pages;
				$up_limit=$total_nav_pages-$config_max_nav_pages;
			}
			
			if ($up_limit<=1) $up_limit=1;
			
			for ($page=$up_limit;!($page>=$total_pages);$page++){
			if ($page == $navigation_page)
				 echo ' <span class="nava">'.$page.'</span> ';
			else echo'[<a href="'.$navigation_script.'page='.$page.'" class="nav">'.$page.'</a>] ';
		}
		}
	}
	
	
}
?>