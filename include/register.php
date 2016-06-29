<?
include_once "mail.php";
$actionmode="adduser";

function m__specialchars($val){
	if (is_array($val))
	foreach ($val as $a=>$b) $val[$a]=m_specialchars($b);
	return $val;
}

if (empty($_POST['register_posted_data'])){
	$register_posted_data['firstname']='';
	$register_posted_data['lastname']=''; 
	$register_posted_data['p_name']=''; 
	
	$register_posted_data['fio']='';
	$register_posted_data['email']='';
	$register_posted_data['telephone']='';
	$register_posted_data['index']='';
	$register_posted_data['address']='';
	$register_posted_data['organization']='';
	$register_posted_data['image_protect']='';
	$register_posted_data['town']='';
	$register_posted_data['description']='';
	$register_posted_data['delivery']='0';
	$register_posted_data['skype']='';
	
	}else{
	$register_posted_data=$_POST['register_posted_data'];
	
	$register_posted_data=m__specialchars($register_posted_data);	
} 

if (empty($register_posted_data['opt_value'])) $register_posted_data['opt_value']='';

function check_fio($fio){
	
	if ($fio=='') return false;
	$fio=explode(' ',$fio);
	if (count($fio)!=2) return false;
	if ( (strlen($fio[0])<2)&&(strlen($fio[1])<2) ) return false;
	//&&(strlen($fio[2])<2)
	
	return true;
}

function get_val($a,$b){
	if ($b!='') return $a.": ".$b."\n";
}

$mode=get_post_var("mode");
if (!empty($_GET["mode"])) $mode=$_GET["mode"];

if ($access=="1") $rheader="Профайл"; else $rheader="Получить Прайс-Лист - Регистрация";

$merror="";
$mok="";
$register_ok="";
$tomail="";

$merror='';

$delivery_set[0]="";
$delivery_set[1]="Автотрейдинг";
$delivery_set[2]="Байкал-Сервис";
$delivery_set[3]="Грузовозофф";
$delivery_set[4]="Деловые Линии";
$delivery_set[5]="Желдор Экспедиция";
$delivery_set[6]="Другое";

function check_data(){
global $register_posted_data,$sql_tbl; 
$merror='';

	if (!func_check_email($register_posted_data['email'])) 
		$merror.="<li>Введите верный E-mail;</li>";
		elseif (func_query_first_cell("SELECT COUNT(*) FROM $sql_tbl[custumers] WHERE email='$register_posted_data[email]'")!=0){
			$merror.="<li>Такой e-mail уже существует</li>";
			$register_posted_data['email']="";
		}
		
	if (preg_match('/^-?\d+[\.|\,]?\d+$/', $register_posted_data["telephone"])==""){
		$merror.="<li>Укажите верный телефон;</li>";
		$register_posted_data["telephone"]="";
	}
	if ($register_posted_data['town']=='') $merror.="<li>Укажите город;</li>";
	
	return $merror;
}

switch ($mode) {
case "add":{
	$fio=explode(" ",$register_posted_data['fio']);
	$register_posted_data['lastname']=$fio[0]; 
	if (!empty($fio[1])) $register_posted_data['firstname']=$fio[1]; else $register_posted_data['firstname']='';
	if (!empty($fio[2])) $register_posted_data['p_name']=$fio[2]; else $register_posted_data['p_name']='';
	
	if ($register_posted_data['fio']=='') $merror.="<li>Укажите ваши Фамилию Имя Отчество;</li>"; 
	$merror.=check_data();
	
	if (!empty($register_posted_data['delivery']))
		$register_posted_data['delivery']=$delivery_set[$register_posted_data['delivery']];
	
	if (empty($register_posted_data['opt_value']))
		$merror.="<li>Выберите, оптовые или розничные цены вас интересуют;</li>";
	
	if (($_SESSION['number']!=$register_posted_data['image_protect'])||($_SESSION['number']=="")||($_SESSION['number']==""))
		$merror.="<li>Код, изображённый на картинке, введен не верно.</li>";
	
	if ($merror==''){
	
	$interest='';
	if (!empty($register_posted_data["interest_a"])) $interest.="Школьная форма\n";
	if (!empty($register_posted_data["interest_b"])) $interest.="Мужской ассортимент\n";
	
	
	if ($register_posted_data['opt_value']=="1") $interest.="оптовые цены\n";
	if ($register_posted_data['opt_value']=="2") $interest.="розничные цены\n";
	
	
	if ($interest!='') 
		$register_posted_data["description"]="Интересует прайс лист по следующим наименованиям: \n".$interest;
		else $register_posted_data["description"]='';
	
		$password=func_bf_crypt($register_posted_data['image_protect'],"victor");
		db_query("INSERT INTO $sql_tbl[custumers] (skype,description,delivery,lastname,firstname,p_name,password,fio,telephone,`index`,address,organization,register,email,region) VALUES ('$register_posted_data[skype]','$register_posted_data[description]','$register_posted_data[delivery]','$register_posted_data[lastname]','$register_posted_data[firstname]','$register_posted_data[p_name]','$password','$register_posted_data[fio]','$register_posted_data[telephone]','$register_posted_data[index]','$register_posted_data[address]','$register_posted_data[organization]','".time()."','$register_posted_data[email]','$register_posted_data[town]')");
		
		$info=get_val('Фамилия: ',$register_posted_data['fio']);
		$info.=get_val('Телефон: ',$register_posted_data['telephone']);
		$info.=get_val('Индекс: ',$register_posted_data['index']);
		$info.=get_val('Адрес: ',$register_posted_data['address']);
		$info.=get_val('Организация: ',$register_posted_data['organization']);
		$info.=get_val('Город: ',$register_posted_data['town']);
		
		if ($interest!='')	$info.="Прайс-лист на:\n".$interest."\n";
				
		$info.='E-mail: '.$register_posted_data['email'];
	
		$info=str_replace("\n","<br/>",$info);
		$subject="Новая заявка на прайс лист от ".strftime($config["timeformat"]);
		
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],"hronos@land.ru", $subject, $info);
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$config["email"], $subject, $info);
		
		$url=$_SERVER['SERVER_NAME'].'/register.php?mode=enter&key='.func_bf_crypt($register_posted_data['image_protect'],"skey");
		//или перейдя по ссылке:<a href="'.$url.'">'.$url.'</a>';
		$info='
		Здравствуйте!
		Вы подали заявку на получение прайс листа на сайте bostonwears.ru.
		
		Вы можете поменять свои данные, авторизовавшись на сайте, испльзуя:
		e-mail:'.$register_posted_data['email'].'
		пароль:'.$register_posted_data['image_protect'];

		$info=str_replace("\n","<br/>",$info);
		//multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$register_posted_data['email'], $subject, $info);

		header("location: register.php?m=3");
	}
 
 }break;
 case "logout":{
	$_SESSION["logged"]=0;
	$_SESSION["login"]="";
	
	if (!empty($_POST["redirect"]))
 			 header("Location: ".$_POST["redirect"]);
 		else header("Location: index.html");
	
 }break;
 case "updateuserinfo":{
	
	if ($register_posted_data['firstname']=='') $merror.="<li>Введите имя</li>"; 
	if ($register_posted_data['lastname']=='') $merror.="<li>Введите фамилию</li>"; 
	if ($register_posted_data['p_name']=='') $merror.="<li>Введите отчество</li>"; 
	
	if ($merror==''){
		$sql="UPDATE $sql_tbl[custumers] SET 
		firstname='$register_posted_data[firstname]',
		lastname='$register_posted_data[lastname]', 
		p_name='$register_posted_data[p_name]',
		telephone='$register_posted_data[telephone]', 
		skype='$register_posted_data[skype]',
		`index`='$register_posted_data[index]',
		address='$register_posted_data[address]',
		delivery='$register_posted_data[delivery]',
		organization='$register_posted_data[organization]'
		WHERE email='$_SESSION[login]' AND usertype='C'";
		//echo $sql;
		db_query($sql);	
		header("Location: $action_var?m=4");
	}
 
 }break;
 case "shpassword": {
	$actionmode="change_password_mode";
	
	if (empty($register_posted_data['password']))
		$merror.="<li>Введите новый пароль</li>"; 
	
	if ($register_posted_data['password']!=$register_posted_data['kpassword'])
		$merror.="<li>Подтверждение пароля не совпадает.</li>"; 	
	
	if ($merror==''){
		db_query("UPDATE $sql_tbl[custumers] SET password='".func_bf_crypt($register_posted_data['password'],"victor")."' WHERE email='$_SESSION[login]' AND usertype='C'");	
		header("Location: $action_var?m=11");
	}
 }break;
 
 case "login": {
 	$username=get_post_var("username");
 	$password=get_post_var("password");
	
 	$location="";
 	if ( ($username!="")&&($password!="") ){
 	
 	$user_info = func_query_first("SELECT * FROM $sql_tbl[custumers] WHERE email='$username' AND usertype='C' ");
 	
	if ( (!empty($user_info))&&($password==func_bf_decrypt($user_info["password"],"victor")) ){//&&($user_info["status"]=="Y")
 		$_SESSION["login"]=$username;
		$_SESSION["logged"]=1;
 		db_query("UPDATE $sql_tbl[custumers] SET last_login='".time()."' WHERE email='$_SESSION[login]' AND usertype='C'");
 		$location="register.php?m=6";
 	}else{
 		$_SESSION["logged"]=0;
 		$location="register.php?m=7";
 	}
	if (!empty($user_info["status"])&&($user_info["status"]=="X"))
 			 $location="register.php?m=12";
		if (!empty($user_info["status"])&&($user_info["status"]=="M"))
 			 $location="register.php?m=8";
 		if (!empty($user_info["status"])&&($user_info["status"]=="B"))
 			 $location="register.php?m=9";
  }
 header("Location: $location");
 }break;
 case "change_password": {
 	$actionmode="change_password_mode";
	$rheader="Сменить пароль";
 }break;
 case "conform_password": {
 	$actionmode="conform_password_mail";
	$rheader="Напомнить пароль";
 }break;
 case "conform_password_mail": {
 	$actionmode="conform_password_mail";
	
	if ($register_posted_data["email"]!=''){
		$user_info = func_query_first("SELECT * FROM $sql_tbl[custumers] WHERE email='$register_posted_data[email]' AND usertype='C'");
		if (!empty($user_info)){
			$info='
			Кто-то, возможно вы сами, запросил логин и пароль на сайте '.$_SERVER['SERVER_NAME'].'
			
			логин: '.$user_info["email"].'
			пароль: '.func_bf_decrypt($user_info["password"],"victor").'
			
			<font size="-1">Если это письмо пришло к вам по ошибке - просим прощения за неудобство. 
			Отвечать на это письмо не нужно.</font>
			';	
		$info=str_replace("\n","<br/>",$info);
		multipart_mail('no-reply@'.$_SERVER['SERVER_NAME'],$user_info["email"], "Восстановление пароля", $info);
		header("Location: $action_var?m=5");
		}else $merror="Введенному логину и паролю не соответствует ни одна запись.";
	}
	
	$rheader="Напомнить пароль";
 }break;
 case "delete": {
 	$actionmode="delete_login";
 }break;
 
 case "delete_login": {
 	db_query("UPDATE $sql_tbl[custumers] SET status='X' WHERE email='$_SESSION[login]' AND usertype='C'");	
	//db_query("DELETE FROM $sql_tbl[custumers] WHERE email='$_SESSION[login]'");
	$_SESSION["logged"]=0;
	$_SESSION["login"]="";

 	header("Location: $action_var?m=10");
 }break;
	
}

if ($access=="1"){
	$price_file=func_query_first("SELECT * FROM $sql_tbl[files] WHERE active='Y' ");
	if (empty($_POST["mode"])){
		$register_posted_data=$user_data;	
	}
} 
?>