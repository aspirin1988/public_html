<?
function db_connect($sql_host, $sql_user, $sql_password) {
        return mysql_connect($sql_host, $sql_user, $sql_password);
}

function db_select_db($sql_db) {
        return mysql_select_db($sql_db) || die("Could not connect to SQL db");
}


function db_query($query) {
		global $debug_mode;

		$result = mysql_query($query);

		#
		# Auto repair
		#
		if( !$result && preg_match("/'(\S+)\.(MYI|MYD)/",mysql_error(), $m) ){
			$stm = "REPAIR TABLE $m[1]";
			error_log("Repairing table $m[1]", 0);
			if ($debug_mode == 1 || $debug_mode == 3) {
				$mysql_error = mysql_errno()." : ".mysql_error();
				echo "<B><FONT COLOR=DARKRED>Repairing table $m[1]...</FONT></B>$mysql_error<BR>";
				flush();
			}
			$result = mysql_query($stm);
			if (!$result)
				error_log("Repaire table $m[1] is failed: ".mysql_errno()." : ".mysql_error(), 0);
			else
				$result = mysql_query($query); # try repeat query...
		}
		if (db_error($result, $query) && $debug_mode==1)
			exit;
		return $result;
}

function db_result($result, $offset) {
        return mysql_result($result, $offset);
}

function db_fetch_row($result) {
        return mysql_fetch_row($result);
}

function db_fetch_array($result, $flag=MYSQL_ASSOC) {
    return mysql_fetch_array($result, $flag);
}

function db_free_result($result) {
        @mysql_free_result($result);
}

function db_num_rows($result) {
       return mysql_num_rows($result);
}

function db_insert_id() {
       return mysql_insert_id();
}

function db_affected_rows() {
	return mysql_affected_rows();
}

#
# Execute mysql query and store result into associative array with
# column names as keys...
#
function func_query($query) {

        $result = false;
        if ($p_result = db_query($query)) {
 	       while($arr = db_fetch_array($p_result))
				$result[]=$arr;
				db_free_result($p_result);
        }

        return $result;

}

#
# Execute mysql query and store result into associative array with
# column names as keys and then return first element of this array
# If array is empty return array().
#
function func_query_first($query) {
		if ($p_result = db_query($query)) {
			$result = db_fetch_array($p_result);
			db_free_result($p_result);
        }
        return is_array($result)?$result:array();

}

#
# Execute mysql query and store result into associative array with
# column names as keys and then return first cell of first element of this array
# If array is empty return false.
#
function func_query_first_cell($query) {
	if ($p_result = db_query($query)) {
		$result = db_fetch_row($p_result);
		db_free_result($p_result);
	}
	return is_array($result)?$result[0]:false;//
}

function db_error($mysql_result, $query) {
	global $debug_mode, $error_file_size_limit, $error_file_path, $PHP_SELF;
	global $config, $login, $REMOTE_ADDR, $current_location;
	
	if ($mysql_result)
		return false;
	else {
		$back_trace = func_get_backtrace();

		$mysql_error = mysql_errno()." : ".mysql_error();
			$err_str  = "Date        : ".date("d-M-Y H:i:s")."\n";
			$err_str .= "Site        : ".$current_location."\n";
			$err_str .= "Script      : ".$PHP_SELF."\n";
			$err_str .= "Remote IP   : $REMOTE_ADDR\n";
			$err_str .= "Logged as   : $login\n";
			$err_str .= "SQL query   : $query\n";
			$err_str .= "Error code  : ".mysql_errno()."\n";
			$err_str .= "Description :\n\n".mysql_error()."\n";
			$err_str .= "Backtrace   :\n".implode("\n", $back_trace);
			
		
			echo "<B><FONT COLOR=DARKRED>INVALID SQL: </FONT></B>$mysql_error<BR>";
			echo "<B><FONT COLOR=DARKRED>SQL QUERY FAILURE:</FONT></B> $query <BR>";
			flush();
		}
		
	return true;
}


function func_get_backtrace($skip=0) {
	$result = array();
	if (!function_exists('debug_backtrace')) {
		$result[] = '[func_get_backtrace() is supported only for PHP version 4.3.0 or better]';
		return $result;
	}
	$trace = debug_backtrace();

	if (is_array($trace) && !empty($trace)) {
		if ($skip>0) {
			if ($skip < count($trace))
				$trace = array_splice($trace, $skip);
			else
				$trace = array();
		}

		foreach ($trace as $item) {
			$result[] = $item['file'].':'.$item['line'];
		}
	}

	if (empty($result)) {
		$result[] = '[empty backtrace]';
	}

	return $result;
}


#
# Remove parameters from QUERY_STRING by name
#
function func_qs_remove($qs, $param_name) {
	$pn = preg_quote($param_name,"!")."(\[[^&]*\])?";
	$qs = preg_replace("!(&?)(".$pn.")=\w*!S", "", $qs);
	$qs = preg_replace("!^&!S", "", $qs);
	return $qs;
}


function get_post_var($var_name){
global $_POST;
return !empty($_POST[$var_name])?$_POST[$var_name]:"";
}

function get_get_var($var_name){
global $_GET;
return !empty($_GET[$var_name])?$_GET[$var_name]:"";
}

 
function func_delete_cat_category($cat) {
	global $sql_tbl;

	$catpair = func_query_first("SELECT categoryid_path, parentid FROM $sql_tbl[cat_categories] WHERE categoryid='$cat'");
	if (empty($catpair)) # category is missing// === false
		return 0;
#
# Delete subcategories
#
	$categoryid_path = $catpair["categoryid_path"];
	$parent_categoryid = $catpair["parentid"];
	
	$subcats = func_query("SELECT categoryid FROM $sql_tbl[cat_categories] WHERE categoryid='$cat' OR categoryid_path LIKE '$categoryid_path/%'");

	if (is_array($subcats))
		while(list($key,$subcat)=each($subcats)) {
			$cat_id=$subcat["categoryid"];
			
			$delete_products=func_query("SELECT * FROM $sql_tbl[catalogue] WHERE cat='$cat_id'");
			if (is_array($delete_products))
				foreach($delete_products as $f => $m)
					func_delete_product($m["id"]);
			
			db_query("DELETE FROM $sql_tbl[cat_categories] WHERE categoryid='$cat_id'");
			
		}
#
# Delete associated data
#

	return $parent_categoryid;

}

function func_delete_image($a) {
	global $sql_tbl;
	$del_img=func_query("SELECT * FROM $sql_tbl[images] WHERE parent='$a'");
	if (is_array($del_img))
 			foreach ($del_img as $x=>$z) {
					un_link("../images/".$z["id"]."_s.".$z["t_s_image"]);
					un_link("../images/".$z["id"].".".$z["t_i_image"]);
					db_query("DELETE FROM $sql_tbl[images] WHERE parent='$z[id]'");
					}
}

function func_delete_product($a) {
	global $sql_tbl;
	func_delete_image($a);
	db_query("DELETE FROM $sql_tbl[orders] WHERE pid='$a'");
	db_query("DELETE FROM $sql_tbl[products_propertes] WHERE pid='$a'");
	db_query("DELETE FROM $sql_tbl[catalogue] WHERE id='$a'");				
}

function func_delete_category($cat) {
	global $sql_tbl;

	$catpair = func_query_first("SELECT categoryid_path, parentid FROM $sql_tbl[categories] WHERE categoryid='$cat'");
	if (empty($catpair)) # category is missing// === false
		return 0;
#
# Delete subcategories
#
	$categoryid_path = $catpair["categoryid_path"];
	$parent_categoryid = $catpair["parentid"];
	
	$subcats = func_query("SELECT categoryid FROM $sql_tbl[categories] WHERE categoryid='$cat' OR categoryid_path LIKE '$categoryid_path/%'");

	if (is_array($subcats))
		while(list($key,$subcat)=each($subcats)) {
			$cat_id=$subcat["categoryid"];
			db_query("DELETE FROM $sql_tbl[categories] WHERE categoryid='$cat_id'");
			db_query("DELETE FROM $sql_tbl[textpage] WHERE parentid='$cat_id'");
		}
#
# Delete associated data
#
	return $parent_categoryid;
}

function text_crypt_symbol($c) {
# $c is ASCII code of symbol. returns 2-letter text-encoded version of symbol

        global $START_CHAR_CODE;

        return chr($START_CHAR_CODE + ($c & 240) / 16).chr($START_CHAR_CODE + ($c & 15));
}

function text_crypt($s, $is_blowfish = false) {
    global $START_CHAR_CODE, $CRYPT_SALT, $merchant_password, $current_area, $blowfish, $config;

    if ($s == "")
        return $s;
	if($is_blowfish && $merchant_password && ($current_area == 'A' || ($current_area == 'P')) && $blowfish) {
		$s = trim($s);
		$result = "B".func_crc32($s).func_bf_crypt($s, $merchant_password);
	} else {
    	$enc = rand(1,255); # generate random salt.
	    $result = "S".text_crypt_symbol($enc); # include salt in the result;
    	$enc ^= $CRYPT_SALT;
	    for ($i = 0; $i < strlen($s); $i++) {
    	    $r = ord(substr($s, $i, 1)) ^ $enc++;
	        if ($enc > 255)
        	    $enc = 0;
    	    $result .= text_crypt_symbol($r);
	    }
	}
    return $result;
}

function text_decrypt_symbol($s, $i) {
# $s is a text-encoded string, $i is index of 2-char code. function returns number in range 0-255

        global $START_CHAR_CODE;

        return (ord(substr($s, $i, 1)) - $START_CHAR_CODE)*16 + ord(substr($s, $i+1, 1)) - $START_CHAR_CODE;
}

function text_decrypt($s) {
    global $START_CHAR_CODE, $CRYPT_SALT, $merchant_password, $current_area, $blowfish;

    if ($s == "")
        return $s;
	$crypt_method = substr($s, 0, 1);
	$s = substr($s, 1);
	if($crypt_method == 'B') {
		if($merchant_password && ($current_area == 'A' || ($current_area == 'P')) && $blowfish) {
			$crc32 = substr($s, 0, 4);
			$s = substr($s, 4);
			$result = func_bf_decrypt($s, $merchant_password);
			if(func_crc32($result) != $crc32) {
				$result = func_get_langvar_by_name('err_data_corrupted');
			}
		} else {
			return false;
		}
    } elseif($crypt_method != 'B') {
        if($crypt_method != 'S') {
            $s = $crypt_method.$s;
        }
    	$enc = $CRYPT_SALT ^ text_decrypt_symbol($s, 0);
		$result = "";
    	for ($i = 2; $i < strlen($s); $i+=2) { # $i=2 to skip salt
	        $result .= chr(text_decrypt_symbol($s, $i) ^ $enc++);
        	if ($enc > 255)
    	        $enc = 0;
	    }
	}
    return $result;
}

function Edit_Tree($ParentID,$style,$param="",$tbl) {
global $cat,$action_var,$lng;
$result=func_query("SELECT * FROM $tbl WHERE parentid ='$ParentID' ORDER BY order_by");
	if (!empty($result)){
		if ($result[0]['location']=='index.html') echo '<ul id="drop_Menu" class="treeview-red treeview">'; else echo '<ul>';
			foreach ($result as $a=>$row){
				echo '<li>';
					$ID1 = $row["categoryid"];
					
					if ($cat!=$ID1)
						 echo("<A HREF='$action_var?cat=$ID1&$param' class=$style>".$row["category"]."</A>");
					else echo("<A HREF='$action_var?cat=$ID1&$param' class='act'>".$row["category"]."</A>");
			
					echo " <input size=3 type=text name='posted_data[$row[categoryid]][order_by]' value='$row[order_by]'> ";
					echo $lng["lbl_hide"]." <input size=2 type=checkbox name='posted_data[$row[categoryid]][avail]'"; 
					if ($row["avail"]=="Y") print "checked"; echo ">";
					echo $lng["lbl_comments"]." <input size=2 type=checkbox name='posted_data[$row[categoryid]][comments]'"; 
					if ($row["comments"]=="Y") print "checked"; echo ">";			
					echo " $lng[lbl_delete]<input type=checkbox name='posted_data[$row[categoryid]][del]'>";	
					
					Edit_Tree($ID1,$style,$param,$tbl);
			
				echo '</li>';
			}
			echo '</ul>';
	}
}

function recur_show_tree($action_var,$ParentID,$style,$param="") {
global $sql_tbl,$cat,$z;
	$z++;
	$result=func_query("SELECT * FROM ".$sql_tbl["categories"]." WHERE parentid ='$ParentID' ORDER BY order_by");
	if (!empty($result)){
		if ($z==1) echo '<ul id="drop_Menu" class="treeview-red treeview">'; else echo '<ul>';
		foreach ($result as $a=>$row){
		$ID1 = $row["categoryid"];
				echo("<li> ");
					 if ($cat!=$ID1)	
					 echo("<a href='$action_var?cat=".$ID1."$param' class=$style>".$row["category"]."</a>");
				else echo("<a href='$action_var?cat=".$ID1."$param' class=act>".$row["category"]."</a>");
			recur_show_tree($action_var,$ID1,$style,$param);
		echo '</li>';
		}
	echo '</ul>';
	}
}
function recur_list_show_tree($action_var,$ParentID) {
global $sql_tbl,$cat,$z;
	$z++;
	$result=func_query("SELECT * FROM ".$sql_tbl["categories"]." WHERE parentid ='$ParentID' ORDER BY order_by");
	if (!empty($result)){
		if ($z==1) echo '<ol class=\"dd-list\" >'; else echo '<ol>';
		$text_page=func_query("SELECT * FROM ".$sql_tbl["textpage"]." WHERE parentid ='$ParentID' and type='article' ");
				if (!empty($text_page)){
					//echo '<ol>';
					foreach	($text_page as $q=>$m){
						if ($m['category']=='') $m['category']=__substr(strip_tags($m['text']), 100);
						echo("<li class=\"dd-item\" data-id=\"z$m[pageid]\"><div class=\"dd-handle\">".$m['category']."<input type=\"hidden\" name=\"articles[]\" value='$ParentID|$m[pageid]'/></div></li>");
					}
					//echo '</ol>';
				}
			
		
		foreach ($result as $a=>$row){
		$ID1 = $row["categoryid"];
				echo("<li class=\"dd-item\" data-id=\"z$row[categoryid]\"><div class=\"dd-handle\">".$row["category"]."<input type=\"hidden\" name=\"articles[]\" value='$row[categoryid]'/></div>");
				$text_page=func_query("SELECT * FROM ".$sql_tbl["textpage"]." WHERE parentid ='$ID1' and type='article' ");
				if (!empty($text_page)){
					echo '<ol>';
					foreach	($text_page as $q=>$m){
						if ($m['category']=='') $m['category']=__substr(strip_tags($m['text']), 100);
						echo("<li class=\"dd-item\" data-id=\"z$m[pageid]\"><div class=\"dd-handle\">".$m['category']."<input type=\"hidden\" name=\"articles[]\" value='$row[categoryid]|$m[pageid]'/></div></li>");
						}
					echo '</ol>';
				}
			
			recur_list_show_tree($action_var,$ID1);
		echo '</li>';
		}
	echo '</ol>';
	}
}

function print_menu($cat,$class,$img)
{
global $web_dir,$sql_tbl,$action_var,$cat,$alt,$lng;
	$categoryes=func_query("SELECT categoryid, category, parentid FROM $sql_tbl[categories] WHERE parentid ='$cat' AND avail='Y' ORDER BY order_by");
	 
	if (is_array($categoryes))
	foreach ($categoryes as $a=>$b)
	{
	echo("<br>");
	print_main_link("$action_var?cat=$b[categoryid]",$b["category"],$b["categoryid"],$cat,$img,$class);
	recur_show_tree($b["categoryid"],$class);
	}
}

function print_tbl_menu($sql_tbl,$ccat,$class,$img,$param="")
{
global $web_dir,$action_var,$cat,$alt,$lng;
$r=0;
	$categoryes=func_query("SELECT categoryid, category, parentid FROM $sql_tbl WHERE parentid ='$ccat' AND avail='N' ORDER BY order_by");
	if (is_array($categoryes))
	foreach ($categoryes as $a=>$b)
	{
print_main_link("$action_var?cat=$b[categoryid]".$param,$b["category"],$b["categoryid"],$cat,$img,$class);
recur_show_tree_tbl($cat,$sql_tbl,$action_var."?cat=",$b["categoryid"],$class,$param);
	echo("<br><br>\n");
	$r=1;
	}
return $r;	
}

function print_link($curlocvar,$linkvar,$titlelink)
{
global $locationvar,$web_dir;
 echo "<br>";
 if ($curlocvar==$locationvar) 
 	echo("<img align=absmiddle src='".$web_dir."img/menu_bar_activ.gif' width=8 height=8 hspace=10 vspace=10><span class=submenu>$titlelink</span>");
	else echo("<img align=absmiddle src='".$web_dir."img/sub_menu_bar.gif' width=8 height=8 hspace=10 vspace=10><a href=$linkvar class=submenu>$titlelink</a>");
}

function print_main_link($action_var,$name,$cat,$link,$img,$class)
{
global $locationvar,$web_dir,$alt;
$image="";
if ($img["name"]=="")
	$image="";
	elseif ($cat!=$link)
	 	 $image="<img src='".$web_dir."img/$img[name].gif' width='$img[width]' height='$img[height]' border='0' alt='$alt' hspace=10>";
	else $image="<img src='".$web_dir."img/".$img["name"]."_a.gif' width='$img[width]' height='$img[height]' border='0' alt='$alt' hspace=10>";

if ($cat!=$link)
		 echo("<a href='$action_var' class=$class>".$image.$name."</a>\n");
	else echo("<a href='$action_var' class=$class><span class=$class>".$image.$name."</span></a>\n");   
}

function print_marker($var)
{
global $merror;
	echo '<span class="marker">*</span>';
	if (($var=="")&&($merror)) 
		print '<span class="marker">«</span>';
}

function get_image_content($image)
{
$imageb="";
$fp = fopen($image,"rb");
$imageb = fread($fp, filesize($image));
fclose($fp);
return addslashes($imageb);
}

function print_select($var,$selectedvalue,$name,$js="")
{
if (!empty($var)){
echo ("<SELECT name='$name' $js>");
	foreach ($var as $c=>$d)
		{
		echo ("<OPTION value='".$c."'");
		if (is_array($selectedvalue)&&(in_array($c,$selectedvalue))) echo " selected ";
		elseif ($selectedvalue==$c) echo " selected ";
		echo(">$d</OPTION>");
		}
echo ("</SELECT>");
}
}	



function str_decrypt($str)
{
for ($i=0,$s="";$i<strlen($str);$i++) $s.=$str[$i];
return $s;
}
function num_gen($slen)
{
for ($i=0,$out_value="";$i<$slen;$i++)
	switch (rand(1,5)){
		case 1:$out_value.=chr(rand(ord("1"),ord("3")));break;
		case 2:$out_value.=chr(rand(ord("3"),ord("5")));break;
		case 3:$out_value.=chr(rand(ord("1"),ord("9")));break;
		case 4:$out_value.=chr(rand(ord("5"),ord("9")));break;
		case 5:$out_value.=chr(rand(ord("1"),ord("9")));break;
		/*
		case 1:$out_value.=chr(rand(ord("A"),ord("N")));break; 
		case 2:$out_value.=chr(rand(ord("P"),ord("Z")));break; 
		case 3:$out_value.=chr(rand(ord("1"),ord("9")));break;
		case 4:$out_value.=chr(rand(ord("a"),ord("n")));break;
		case 5:$out_value.=chr(rand(ord("p"),ord("z")));break;
		*/
		}
	for ($i=0,$value=0;$i<$slen-1;$i++,$value+=$i*ord($out_value{$i}));
return $out_value;
}
function str_crypt($str)
{
for ($i=strlen($str),$s="";$i>0;$i--,$s.=$str[$i]); return $s;
}
function put_file($tmp_name,$name)
{
if (file_exists($name))
    	un_link($name);
    	move_uploaded_file ($tmp_name, $name);
    	chmod($name, 0644);
}

function un_link($name)
{
if (file_exists($name))
	{
    	chmod($name, 0777);
    	unlink($name);
    }	
}

function image_type($type)
{
$image_type="";
switch($type) {
	case 'image/jpeg':$image_type = "jpg";break;
	case 'image/pjpeg':$image_type = "jpg";break;
   	case 'image/gif';$image_type = "gif";break;
   	case 'image/png':$image_type = "png";break;
	}
return $image_type;
}

function str_toupper($str)
{
for ($rez="",$i=0;$i<strlen($str);$i++) {
  $c=$str{$i};
    if( (ord($c)>=224)&&(ord($c)<=255) )
      $c=chr(ord($c)-32);
      else $c=strtoupper($c);
  $rez.=$c;
  }
return $rez;
}

function str_tolower($str)
{
for ($rez="",$i=0;$i<strlen($str);$i++) {
  $c=$str{$i};
    if( (ord($c)>=192)&&(ord($c)<=223) )
      $c=chr(ord($c)+32);
      else $c=strtolower($c);
  $rez.=$c;
  }
return $rez;
}

function r_print_r($ar)
{
if (!empty($ar))
print("<pre>");print_r($ar);print("</pre>");
}

function cat_check($cat,$tbl){
if (func_query_first_cell("SELECT count(*) FROM $tbl WHERE categoryid='$cat'")==0)
	 return 0;
else return $cat;
}

function get_image_data($imageName,$snew,$tmp_name)
{
	$old = imageCreateFromJpeg($imageName);
	$w = imageSX($old);
    $h = imageSY($old);
    
    if ($h<=$snew){
    	$w_new=$w;
    	$h_new=$h;
    	} else{
    		$w_new=ceil($w*$snew/$h);
    		$h_new=$snew;
    	}
    
    $new = imagecreatetruecolor($w_new, $h_new);
    imageCopyResized($new, $old, 0, 0, 0, 0, $w_new, $h_new, $w, $h);
    $iconName="tmp_img/".$tmp_name."_tmp.jpg";
    
    imageJpeg($new, $iconName,90);
    $image_content=get_image_content($iconName);
	un_link($iconName);
    imageDestroy($old);
	imageDestroy($new);
	return $image_content;
}

function func_check_email($email) {
#
# Simplified checking
#
	$email_regular_expression = "^([-\d\w][-.\d\w]*)?[-\d\w]@([-!#\$%&*+\\/=?\w\d^_`{|}~]+\.)+[a-zA-Z]{2,6}$";

#
# Full checking according to RFC 822
# Uncomment the line below to use it (change also check_email_script.tpl)
#	$email_regular_expression = "^[^.]{1}([-!#\$%&'*+.\\/0-9=?A-Z^_`a-z{|}~])+[^.]{1}@([-!#\$%&'*+\\/0-9=?A-Z^_`a-z{|}~]+\\.)+[a-zA-Z]{2,6}$";

	return preg_match("/".$email_regular_expression."/i", stripslashes($email));
}


function translitIt($str){
    $tr = array(
        "А"=>"a","Б"=>"b","В"=>"v","Г"=>"g",
        "Д"=>"d","Е"=>"e","Ж"=>"j","З"=>"z","И"=>"i",
        "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
        "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
        "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
        "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"i","Ь"=>"",
        "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
        "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
        "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
        "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
        "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
        "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
        "ы"=>"i","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya", 
        " "=> "_", "."=> "", "/"=> "_"
    );
    return strtr($str,$tr);
}

function translit($urlstr){
	if (preg_match('/[^A-Za-z0-9_\-]/', $urlstr)) {
    $urlstr = translitIt($urlstr);
    $urlstr = preg_replace('/[^A-Za-z0-9_\-]/', '', $urlstr);
	}
	return $urlstr;
}

function arrow_show_tree($ParentID,$tbl,$sid=true) {
global $link,$sql_tbl,$arrow,$cat;
	$sSQL = "SELECT categoryid_path,categoryid, category, parentid FROM $tbl WHERE parentid='$ParentID'  ORDER BY order_by";//AND avail='N'
	$result = db_query($sSQL, $link);
	if (mysql_num_rows($result) > 0) {
			while ( $row = mysql_fetch_array($result) ) {
				$categoryid_path=explode("/",$row["categoryid_path"]);
				$tname='';
				foreach ($categoryid_path as $ac=>$ab){
					if ($ac>0) $tname.="/".func_query_first_cell("SELECT category FROM $tbl where categoryid='$ab' ");
					else $tname=func_query_first_cell("SELECT category FROM $tbl where categoryid='$ab' ");
				}
				$ID1 = $row["categoryid"];
				if ($sid){
					if ($cat!=$ID1)	$arrow[$ID1]=$tname;
				}else $arrow[$ID1]=$tname;
				//$row["category"];
			arrow_show_tree($ID1,$tbl,$sid);
		}
	}
}

function image_resize($outfile,$infile,$neww,$newh,$quality){
	$prop = getimagesize($infile);
		
		if ($neww!="0"){
			$newWidth=$neww;
			$newHeight=ceil($prop[1]*$newWidth/$prop[0]);
		}else{
			$newHeight=$newh;
			$newWidth=ceil($prop[0]*$newHeight/$prop[1]);
		}
		
		
		$im1=imagecreatetruecolor($newWidth,$newHeight);
			
	switch( image_type($prop["mime"]) ){
		case 'jpg':{
			$img=imagecreatefromjpeg($infile);
			imagecopyresampled($im1,$img,0,0,0,0,$newWidth,$newHeight,$prop[0],$prop[1]);
			
			if ($outfile=='') header("Content-Type: image/jpg");
			imagejpeg($im1,$outfile,$quality);
			imagedestroy($im1);imagedestroy($img);
		}break;
		case 'gif':{
			$img=imagecreatefromgif($infile);
			imagecopyresampled($im1,$img,0,0,0,0,$newWidth,$newHeight,$prop[0],$prop[1]);
			
			if ($outfile=='') header("Content-Type: image/gif");
			imagegif($im1);
			imagedestroy($im1);imagedestroy($img);
		}break;
		case 'png':{
		$res = imageCreateFromPng($infile);
		
		$tmp = imageCreateTrueColor($newWidth, $newHeight);
		imageAlphaBlending($tmp, false);
		imageSaveAlpha($tmp, true);
		
		imageCopyResampled($tmp, $res, 0, 0, 0, 0, $newWidth,$newHeight, $prop[0], $prop[1]);
			if ($outfile=='') header("Content-Type: image/png");
		imagePng($tmp,$outfile);
		
		imagedestroy($tmp);imagedestroy($res);
		}break;
	}
}
function __substr($text, $length)
{
    $length = strripos(substr($text, 0, $length), ' ');
	//if ($length<strlen($text)) $length=strlen($text);
    return substr($text, 0, $length);
} 

function m_specialchars($s)
{
	if (!is_array($s)){
	$s=html_entity_decode($s);
	$s=htmlspecialchars($s);
	return stripcslashes($s);
	}else return $s;
}

function mspecialchars($val){
	if (is_array($val))
	foreach ($val as $a=>$b) $val[$a]=m_specialchars($b);
	return $val;
}
function url($url){
	if ($url{0}!='/') return  '/'.$url;
	else return  $url;
}

function get_s_var($var){
	$rez='';
	if (!empty($_POST[$var])) {$rez=$_POST[$var]; $_SESSION[$var]=$rez; return $rez; };
	if (!empty($_GET[$var]))  {$rez=$_GET[$var]; $_SESSION[$var]=$rez;  return $rez; }
	if ( !empty($_SESSION[$var]) ) return $_SESSION[$var];
	
	return "";
}

function f_values($b,$v){
	$a=array();
	if (!empty($b))
	foreach ($b as $c=>$d) $a[$c]=$d[$v];
	return $a;
}


?>