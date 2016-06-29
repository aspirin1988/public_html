<?php
	$objects_per_page=10;
	
	$total_products_in_search = count (func_query ($query));
	$total_nav_pages = ceil ($total_products_in_search/$objects_per_page)+1;


if (empty($_GET["page"])) 
		 $page=1;
	else $page=$_GET["page"];

if (!empty($_POST["page"])) $page=$_POST["page"];

if ($page<=0) $page=1;

$config_max_nav_pages=5;
$first_page = $objects_per_page*($page-1);

#
# $total_super_pages - how much groups of pages exists
#
$total_super_pages = (($total_nav_pages-1) / ($config_max_nav_pages ? $config_max_nav_pages : 1));

#
# $current_super_page - current group of pages
#
$current_super_page = ceil($page / $config_max_nav_pages);

#
# $start_page - start page number in the list of navigation pages
#
$start_page = $config_max_nav_pages * ($current_super_page - 1);

#
# $total_pages - the maximum number of pages to display in the navigation bar
# plus $start_page
$total_pages = ($total_nav_pages>$config_max_nav_pages ? $config_max_nav_pages+1 : $total_nav_pages) + $start_page;

#
# Cut off redundant pages from the tail of navigation bar
#
if ($total_pages > $total_nav_pages)
	$total_pages = $total_nav_pages;
	
if ($page > 1 and $page >= $total_pages) {
	$page = $total_pages - 1;
	$start_page=$page-1;
	$first_page = $objects_per_page*($page-1);
}

$navigation_page=$page;
$start_page += 1;
?>