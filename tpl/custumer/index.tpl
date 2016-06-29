<?
function rrows($rows){
	global $all_products;
	if ( !empty($all_products) )
		foreach ($all_products as $a=>$b ){
			echo '
			<div class="'.$rows.'">
					<a href="'.url($b['url']).'" class="projects p_items hvr" title="'.$b['name'].'">';
					if (!empty($b["image"]["icon"]))
						echo '<img class="img-responsive" alt="'.$b['name'].'" src="'.$b["image"]["icon"].'?w=270&amp;h=270">';
						
					echo'<span class="description hvr">
						<span class="next"></span>
						<span class="hvr">'.$b['name'].'</span>
						<span class="txt hvr">'.strip_tags($b['description']).'</span>
					</span>
					</a>
			</div>';
		}
	}


	

function recur_up_menu($ParentID,$url_string='',$pid="class='sf-menu'") {
global $link,$sql_tbl,$cat,$action_var,$text_page,$str_host,$config;

$categories=func_query("SELECT location,categoryid,categoryid_path,category, parentid FROM $sql_tbl[categories] WHERE parentid='$ParentID' and avail='N' ORDER BY order_by");
if (!empty($categories)){
	$lvl=explode("/",$categories[0]['categoryid_path']);
	$lvl=count($lvl);
		if ($lvl==1)
				 echo "<ul $pid>\n\t"; 
			else echo "<ul class=\"dropdown-menu\">\n\t";
		foreach ($categories as $a=>$row){
		 if ( ($cat==$row["categoryid"])||($text_page['category_root']==$row["categoryid"]) ) 
			echo "<li class='current '>";	else echo "<li>";
			if ($cat==$row["categoryid"])
				 echo '<a href="'.url($row['location']).'" class="active">'.$row["category"].'</a>';
			else echo '<a href="'.url($row['location']).'">'.$row["category"].'</a>';
			if ($config['show_sub_cat']=='Y')
				recur_up_menu($row["categoryid"],$url_string);
				
			echo("</li>\n\t");
		}
		echo "</ul>\n\t";		
	}
}



function recur_menu($ParentID,$url_string='',$c_root=true) {
global $link,$sql_tbl,$cat,$action_var,$text_page,$str_host,$config;

$categories=func_query("SELECT location,categoryid,categoryid_path,category, parentid FROM $sql_tbl[categories] WHERE parentid='$ParentID' and avail='N' ORDER BY order_by");
if (!empty($categories)){
		echo "<ul>\n\t";
		foreach ($categories as $a=>$row){
		 if ( ($text_page['categoryid']==$row["categoryid"])||(($text_page['category_root']==$row["categoryid"])&&$c_root) ) 
			echo "<li class='current '>";	else echo "<li>";
				if ($row["location"]!="") $location="/".$row["location"];
				else $location="/index.php?cat=".$row["categoryid"];
				
			if (($url_string!='')) {
					$_url_string=str_replace("#url#",$location,$url_string);
					$_url_string=str_replace("#category#",$row["category"],$_url_string);
					}else 	$_url_string='<a href="'.url($location).'">'.$row["category"].'</a>';
				echo $_url_string;
			
			
				recur_menu($row["categoryid"],$url_string);
				
			echo("</li>\n\t");
		}
		echo "</ul>\n\t";		
	}
}

if ($empty_page) include "tpl/custumer/empty.tpl";
	else switch ($text_page['category_root_data']['location']){
	case "/":{include "tpl/custumer/home.tpl";}break;
	//case "catalogue/":{include "tpl/custumer/catalogue.tpl";}break;
	case "product":{include "tpl/custumer/product.tpl";}break;
	case "gallary":{include "tpl/custumer/gallery.tpl";}break;
	default:{include "tpl/custumer/work.tpl";}break;
}
?>