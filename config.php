<?
include "db_config.php";

$bd_name_prefix="cars_bds_";

$sql_tbl=array(
	"categories" => $bd_name_prefix."categories",
	"sales_outlets" => $bd_name_prefix."sales_outlets",
	"products" => $bd_name_prefix."products",
	"catalogue" => $bd_name_prefix."catalogue",
	"textpage" => $bd_name_prefix."textpage",
	"custumers" => $bd_name_prefix."custumers",
	"users" => $bd_name_prefix."users",
	"languages" => $bd_name_prefix."languages",
	"comments" => $bd_name_prefix."comments",
	"news" => $bd_name_prefix."news",
	"comments" => $bd_name_prefix."comments",
	"gallery" => $bd_name_prefix."gallery",
	"config" => $bd_name_prefix."config",
	"albums" => $bd_name_prefix."albums",
	"extra_filds" => $bd_name_prefix."extra_filds",
	"slider" => $bd_name_prefix."slider"
);

define ('DIR_CUSTOMER', '/customer');
define ('DIR_ADMIN', '/admin');

if (!empty($usertype))
switch ($usertype){
	case "A":$redirect="admin";break;
	case "C":$redirect="custumer";break;
}

#
# Include functions
#

@include_once($root_dir."/include/func.php");

@include_once($root_dir."/include/blowfish.php");
$blowfish = new ctBlowfish();

if (!($link=db_connect($sql_host, $sql_user, $sql_password))) {
 	printf("Ошибка при соединении с MySQL ! ");
 	exit();
}
  
if (!mysql_select_db($sql_db, $link)) {
 	printf("Ошибка базы данных !");
	exit();
} 

	db_query("set character_set_client='utf8'");//cp1251
	db_query("set character_set_results='utf8'");//cp1251
	db_query("set collation_connection='utf8_general_ci'");//cp1251

$languages = array ();
$languages[0]["code"]="US";
$languages[0]["value"]="English";
$languages[1]["code"]="RU";
$languages[1]["value"]="Русский";
$user_data = array ();
if (empty($usertype)) $usertype="C";

$access=0;
if (!empty($_SESSION["logged"])&&$_SESSION["logged"]){
	if ($usertype=="A")
		$user_data = func_query_first("SELECT * FROM $sql_tbl[users] WHERE login='$_SESSION[login]' AND usertype='$usertype' AND status='Y'");
	if ( (!empty($user_data["usertype"]))&&($user_data["usertype"]==$usertype) ) 
		 $access=1;
	else $access=0;
}	
if ($access==0) unset ($user_data);

$site_language="RU";
$labels = func_query("SELECT name, value FROM $sql_tbl[languages] WHERE code = '$site_language'");
$lng=array();
	foreach ($labels as $c=>$v) 
		$lng[$v["name"]] = str_replace("\n",'<br />',$v["value"]);
unset ($labels);
$config=Array();
$setup = func_query ("SELECT name,value FROM $sql_tbl[config]");
if (is_array($setup))
foreach ($setup as $c=>$d)
	$config[$d["name"]]=str_replace("\n",'<br />',$d["value"]);
unset ($setup);
?>