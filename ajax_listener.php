<?
include "localconf.php";

$ajax=true;
include "include/comments.php";


 // json_encode function recovering
    if (!function_exists('json_encode')) {
    function json_encode($arr) {
    $parts = array();
    $is_list = false;
    if (!is_array($arr)) return;
    if (count($arr)<1) return '{}';
    //Find out if the given array is a numerical array
    $keys = array_keys($arr);
    $max_length = count($arr)-1;
    if(($keys[0] == 0) and ($keys[$max_length] == $max_length)) {//See if the first key is 0 and last key is length - 1
    $is_list = true;
    for($i=0; $i<count($keys); $i++) { //See if each key correspondes to its position
    if($i !== $keys[$i]) { //A key fails at position check.
    $is_list = false; //It is an associative array.
    break;
    }
    }
    }
     
    foreach($arr as $key=>$value) {
    if(is_array($value)) { //Custom handling for arrays
    if($is_list) $parts[] = json_encode($value); /* :RECURSION: */
    else $parts[] = '"' . $key . '":' . json_encode($value); /* :RECURSION: */
    } else {
    $str = '';
    if(!$is_list) $str = '"' . $key . '":';
    //Custom handling for multiple data types
    if(is_numeric($value)) $str .= $value; //Numbers
    elseif($value === false) $str .= 'false'; //The booleans
    elseif($value === true) $str .= 'true';
    else $str .= '"' . addslashes($value) . '"'; //All other things
    // :TODO: Is there any more datatype we should be in the lookout for? (Object?)
    $str = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $str);
    $parts[] = $str;
    }
    }
    $json = implode(',',$parts);
     
    if($is_list) return '[' . $json . ']';//Return numerical JSON
    return '{' . $json . '}';//Return associative JSON
    }
    }

    function json_fix_cyr($var){
		if (is_array($var)) {
			$new = array();
			foreach ($var as $k => $v) $new[json_fix_cyr($k)] = json_fix_cyr($v);
			$var = $new;
		} elseif (is_object($var)) {
    $vars = get_class_vars(get_class($var));
		foreach ($vars as $m => $v) $var->$m = json_fix_cyr($v);
    } elseif (is_string($var)) 
		$var = iconv(DEFAULT_CHARSET, 'utf-8', $var);
    return $var;
    }
	

if (get_get_var('mode')=="get_city_set"){
	$region=intval(get_get_var('region'));
	$_cityes=func_query("SELECT * FROM $sql_tbl[city] WHERE region_id='$region'");
	
	$city_id=array();
	$name=array();
	
	foreach ($_cityes as $a => $b){
		$name[$a]=$b['name'];//iconv("cp1251","utf-8",);
		$city_id[$a]=$b['city_id'];
	}
	echo '{"city_values":'.json_encode( $name );
	echo ',"city_id":'.json_encode($city_id).'}';
}
if (get_get_var('mode')=="get_sale_points"){
	include "include/head.php";
	include "include/dealers.php";
	echo '
	<div id="where_by" class="container_16">
		<h2>Адреса магазинов</h2>
		<h3>г. Ульяновск</h3>
		'.sales_ul_sets(' ','grid_4 maxheight ',2).'<div class="cls"></div>
		<h3>Россия</h3>
		'.sales_sets(' ','grid_4 maxheight ',4).'<div class="cls"></div>
	</div>';
}

if (get_get_var('mode')=="add_coments"){
	$ajax=true;
	
	include "include/comments.php";
}

if (get_post_var('mode')=="get_market_adress"){
	$text_page['location']='';
	$requestUri='';
	include 'include/dealers.php';
	$city_name=get_post_var('city');
	get_markets_adress($city_name);
}

if (get_post_var('mode')=="add_simple_order"){
		$subject="Запрос на бронирование на ".$_SERVER['SERVER_NAME'].' '.strftime($config["timeformat"],time());
		$info='<pre>';
			$info.="<br/>Имя: ".$_POST['q_name'];
			$info.="<br/>Телефон: ".$_POST['q_phone'];
		$info.="</pre>";
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$config["email"], $subject, $info);
		echo 'success';
	}



if (get_post_var('mode')=="add_order"){
	$id=get_post_var('id');
	$products=func_query_first("SELECT * FROM $sql_tbl[products] WHERE active='Y' and id='$id' order by orderby ASC ");
	if (!empty($products)){
		$subject="Бронирование на ".$_SERVER['SERVER_NAME'].' '.strftime($config["timeformat"],time());
	
		$info='<pre>';
			$info.="<br/>Имя: ".$_POST['q_name'];
			$info.="<br/>Телефон: ".$_POST['q_phone'];
			$info.="<br/>Автомобиль: ".$products['name'];
		$info.="</pre>";
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$config["email"], $subject, $info);
		echo 'success';
	}else echo 'error';	
}

	
if (get_get_var('mode')=="get_order_form"){
	$id=get_get_var('id');
	$products=func_query_first("SELECT * FROM $sql_tbl[products] WHERE active='Y' and id='$id' order by orderby ASC ");
	//r_print_r($products);
	$name='Забронировать';
	if (!empty($products)){
		$name.='<br/><strong>'.$products['name'].'</strong>';
	}
?>

<div id="order-form" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<form id="orderForm" class="form-horizontal" method="post">
	<input type="hidden" name="id" value="<?=$id?>"/>
	<div class="m_message_box">
		<h2><?=$name?></h2>
		<div class="mmesage"></div>
		<div class="input-group">
			<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			<input type="text" class="form-control" name="q_name" id="q_name" placeholder="Введите, как к вам обращаться:">
		</div>
		<br />
		<div class="input-group">
			<span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
			<input type="text" class="form-control" name="q_phone" id="q_phone" placeholder="Укажите номер телефона:">
		</div>
		<br/>
		<p align="center"><button type="submit" class="bgbtn btn-primary">Отправить</button></p>
	</div>
	</form>
		
	</div>
	
	<script>
	$("#orderForm").validate({
		submitHandler:function(form){
			
			$("#orderForm .bgbtn").val("Отправляется...");
			$("#orderForm .m_message_box").fadeTo(10,0.5);
			$("#orderForm").ajaxSubmit({
			url: "/ajax_listener.php",
			data: { mode: "add_order"},
			success: function(data) {
					if (data=="success"){
						$("#orderForm .mmesage").html("<div id='messageok'>Ваша заявка успешно отправлена. Мы свяжемся с вами в ближайшее время!</div>");
						$("#orderForm .mmesage").oneTime(10000, function(){
								$("#messageok").slideUp(500,0);
						});
						$("#orderForm").resetForm();
						$("#orderForm .bgbtn").val("Отправить");
						$("#orderForm .m_message_box").fadeTo(10,1);
					}else alert(data);
				}
			});
			
			
				},rules: {
					"q_name": {
					required: true,
					minlength: 2
					},
					"q_phone":{
						required: true,
						phone_valid:true
					},
				},
				messages:{
					"q_name":"Введите ваше имя.",
					"q_phone": "Укажите верный номер телефона."
				}
		});
		
		jQuery.validator.addMethod("phone_valid", function(value, element) {
		var phone_reg = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;
		return this.optional(element) || phone_reg.test(value);
	}, "Please specify a valid phone number.");
		
	</script>
<?
	
}


if (get_get_var('mode')=="get_simple_order_form"){?>

<div id="order-form" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<form id="orderForm" class="form-horizontal" method="post">
	<div class="m_message_box">
		<h2>Забронировать</h2>
		<div class="mmesage"></div>
		<div class="input-group">
			<span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			<input type="text" class="form-control" name="q_name" id="q_name" placeholder="Введите, как к вам обращаться:">
		</div>
		<br />
		<div class="input-group">
			<span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span></span>
			<input type="text" class="form-control" name="q_phone" id="q_phone" placeholder="Укажите номер телефона:">
		</div>
		<br/>
		<p align="center"><button type="submit" class="bgbtn btn-primary">Отправить</button></p>
	</div>
	</form>
		
	</div>
	
	<script>
	$("#orderForm").validate({
		submitHandler:function(form){
			
			$("#orderForm .bgbtn").val("Отправляется...");
			$("#orderForm .m_message_box").fadeTo(10,0.5);
			$("#orderForm").ajaxSubmit({
			url: "/ajax_listener.php",
			data: { mode: "add_simple_order"},
			success: function(data) {
					if (data=="success"){
						$("#orderForm .mmesage").html("<div id='messageok'>Ваша заявка успешно отправлена. Мы свяжемся с вами в ближайшее время!</div>");
						$("#orderForm .mmesage").oneTime(10000, function(){
								$("#messageok").slideUp(500,0);
						});
						$("#orderForm").resetForm();
						$("#orderForm .bgbtn").val("Отправить");
						$("#orderForm .m_message_box").fadeTo(10,1);
					}else alert(data);
				}
			});
			
			
				},rules: {
					"q_name": {
					required: true,
					minlength: 2
					},
					"q_phone":{
						required: true,
						phone_valid:true
					},
				},
				messages:{
					"q_name":"Введите ваше имя.",
					"q_phone": "Укажите верный номер телефона."
				}
		});
		
		jQuery.validator.addMethod("phone_valid", function(value, element) {
		var phone_reg = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;
		return this.optional(element) || phone_reg.test(value);
	}, "Please specify a valid phone number.");
		
	</script>
<?
	
}



?>