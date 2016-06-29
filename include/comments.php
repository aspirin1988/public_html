<?php
include_once "mail.php";

if (empty($commenttype)) $commenttype='';

$mode=get_get_var("mode");
if (!empty($_POST["mode"]))	$mode=$_POST["mode"];

$name="";
$text="";
$mail="";
$posted_data=get_post_var("posted_data");


$comments_merror="";
$subscribe_merror='';
if ($mode=="subscribe"){
	$email=get_post_var('email');
	$town=get_post_var('town');
	$redirect=get_post_var('redirect');
	
	if ($email=="") $subscribe_merror.="<li>Введите ваш e-mail;</li>";
	if ($town=="")  $subscribe_merror.="<li>Укажите ваш город;</li>";
	
	if ($subscribe_merror==""){
		$telephone=get_post_var('telephone');
		if (func_query_first_cell("SELECT count(*) FROM $sql_tbl[usersmail] WHERE email='$email'")==0)
			db_query ("INSERT INTO $sql_tbl[usersmail] (telephone,email,`type`,town,data) VALUES ('$telephone','$email','S','$town',".time().")");
		if (empty($ajax)) Header("Location: $redirect?m=1"); else echo '1';
	}else {
		$subscribe_merror="<div class='error_message'><strong>Ошибка!</strong><ul>".$subscribe_merror.'</ul></div>';
		echo $subscribe_merror;
	}
	
}

if ($mode=="zakazat_zvonok"){
	$name=get_post_var('q_name');
	$text=get_post_var('q_text');
	$q_telephone=get_post_var('q_telephone');
	$q_town=get_post_var('q_town');
	
	if ($name=="") $comments_merror.="<li>$lng[lbl_enter_your_name];</li>";
	if ($q_telephone=="") $comments_merror.="<li>Введите ваш номер телефона;</li>";
	if ($text=="") $comments_merror.="<li>$lng[lbl_enter_text_message];</li>";
	
	if ($comments_merror==""){
	
		if ($q_town==""){ 
		$text = htmlspecialchars($text);
			
		$info='<pre>';
		$info.="<br/>Имя: $name";
		$info.="<br/>Телефон: $q_telephone";
		$info.="<br/>Вопрос:<br>$text<br>";
		$info.="</pre>";

		$subject="Новая заявка на обратный звонок на bostonwears.ru от ".strftime($config["timeformat"],time());
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],"hronos@land.ru", $subject, $info);
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$config["email"], $subject, $info);
		}
			if (empty($ajax)) Header("Location: $redirect?m=1"); else echo '1';
	}else echo $comments_merror;
	
	
	
}
if ($mode=="add_q"){
	$page=get_post_var("page");
	$redirect=get_post_var("redirect");

	
	$name=get_post_var('q_name');
	$email=get_post_var('q_email');
	$text=get_post_var('q_text');
	$q_town=get_post_var('q_town');
	
	$cat=get_post_var("cat");
	$commenttype=get_post_var("direct");
	
	$ajax=true;
	
	if ($name=="") $comments_merror.="<li>$lng[lbl_enter_your_name];</li>";
	if ($text=="") $comments_merror.="<li>$lng[lbl_enter_text_message];</li>";
	if (!func_check_email($email)) $comments_merror.="<li>Введите ваш e-mail;</li>";

	$cookie_cryptcode="";
	//if (($_SESSION['number']!=$posted_data["cryptcode3"])||($_SESSION['number']=="")||($_SESSION['number']==""))
	//	$comments_merror.="<li>$lng[lbl_image_enter_error];</li>";
		
	if ($comments_merror==""){
	
	$ttype[1]='директору';
	$ttype[2]='отзыв';
	$ttype[3]='предложение';
	$ttype[4]='жалоба';
	
		if ($q_town==""){ 
		$today=time();
		$text = htmlspecialchars($text);
		if ($config["commentlimit"]<strlen($text))
		$text = substr($text, 0, $config["commentlimit"]);
			
			if ($commenttype!='') $commenttype=$ttype[$commenttype];
			db_query ("INSERT INTO $sql_tbl[comments] (`type`,name, text, date,mail,cat,status) VALUES ('$commenttype','$name', '$text', '$today','$email','$cat','M')");

			
		$info='<pre>';
		$info.="<br/>Имя: $name";
		$info.="<br/>E-Mail: $email";
		$info.="<br/>Сообщеие:<br>$text<br>";
		$info.="</pre>";

		$subject="Новое сообщение на bostonwears.ru от ".strftime($config["timeformat"],time());
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],"hronos@land.ru", $subject, $info);
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$config["email"], $subject, $info);
		}
			if (empty($ajax)) Header("Location: $redirect?m=1"); else echo '1';
	}else echo $comments_merror;
}

if ($mode=="add_coments"){
	
	$cat=get_post_var('cat');
	$type=get_post_var('type');
	
	$q_name=get_post_var('q_name');
	$q_email=get_post_var('q_email');
	$q_text=get_post_var('q_text');
	
	$merror='';
	if ($q_name=="") $merror.="<li>Укажите ваше имя;</li>";
	if ($q_email=="") $merror.="<li>Укажите ваш E-mail;</li>";
	if ($q_text=="") $merror.="<li>Введите текст сообщения;</li>";
	
	if ($merror==''){
	$info='<pre>';
	$info.="<br/>Имя: $q_name";
	$info.="<br/>E-mail: $q_email";
	$info.="<br/>Сообщение: $q_text";
	$info.="</pre>";
	
	//session_start();
	//r_print_r($_SESSION);
	if (!empty($_SESSION['icon'])){
		$icon=$_SESSION['icon'];
		$file=str_replace('@','',$q_email);
		$file=str_replace('.','',$file);
		$icon=explode('.',$icon);
		$file=$file.'.'.$icon[1];
		setcookie ("userpic", $file,time()+3600*24*360);
		$_SESSION['userimage']='/img/userpics/'.$file;
		
		unset($_SESSION['UserUploadImage']);

		copy("$_SESSION[icon]","img/userpics/$file");//rename
		$icon="img/userpics/$file";
	}else $icon='';
	$q_text=mysql_real_escape_string($q_text);
	$q_name=mysql_real_escape_string($q_name);
	$q_email=mysql_real_escape_string($q_email);
	
	db_query ("INSERT INTO $sql_tbl[comments] (icon,`type`,name, text, date,mail,cat,status) VALUES ('$icon','$type','$q_name', '$q_text', '".time()."','$q_email','$cat','M')");

	$subject="Новое сообщение на ".$_SERVER['SERVER_NAME'].strftime($config["timeformat"],time());
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],"hronos@land.ru", $subject, $info);
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$config["email"], $subject, $info);
	if ( empty($ajax) ) Header("Location: $redirect?m=1");
		else echo 'success';
	}else $merror='<ul class="merrorbox">'.$merror.'</ul>';
}

if ($comments_merror!='') $comments_merror='<ul class="error">'.$comments_merror."</ul>";
if ( (get_get_var("m")=="1")&&($comments_merror=='')  )
	$comments_merror='<div class="okmessage">Сообщение успешно отправлено и находится в обработке.</div>';

$amount_comment=array (
"5" => "5",
"10" => "10",
"25" => "25",
"50" => "50"
);
$direct_array=array (
"DESC" => $lng["lbl_decrease"],
"ASC" => $lng["lbl_increase"]
);
if (empty($cat)) $cat=0;
$query="SELECT * FROM $sql_tbl[comments] Where hide='N' and cat='$cat' and status='Y' and `type`='$commenttype' ";

include_once "include/navigation_class.php";

$comments_pages = new navigation($query,$config,5);

if (!empty($text_page))
	$comments_pages->navigation_script=$text_page['location']."?";//nid=$nid& $action_var

if (!empty($_GET['page_all'])) $slimit="";else $slimit="LIMIT ".$comments_pages->first_page.",".$comments_pages->objects_per_page;
$comments=func_query("$query ORDER BY date DESC $slimit");
?>