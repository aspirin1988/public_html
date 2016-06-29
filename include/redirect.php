<?
	
	$__requestUri = explode("?",str_replace('index.php','',$_SERVER['REQUEST_URI']));	
	$__requestUri=$__requestUri[0];
	
	$__requestUri = explode("/",$__requestUri);	
	
	$old_location=func_query("SELECT old_location,location,categoryid,categoryid_path FROM $sql_tbl[categories] WHERE 1");
	if ( !empty($old_location) ){
		foreach ($old_location as $a=>$b)
			$old_location[str_replace("/","",$b['old_location'])]=$b['categoryid'];
	}
	$t_act_url=array_pop($__requestUri);
	if ( ($t_act_url=='')||count($__requestUri)>1 )
		$t_act_url=array_pop($__requestUri);
	if (!empty($old_location[$t_act_url])){
			$redirect_category=func_query_first("SELECT * FROM $sql_tbl[categories] WHERE categoryid='".$old_location[$t_act_url]."' ");	
			header("HTTP/1.1 301 Moved Permanently");
			header('Refresh: 2; URL='.url($redirect_category['location']));
			exit();	
		}else header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
			
	
	
	//echo $t_act_url;
	
	//header("HTTP/1.1 301 Moved Permanently");
	//header('Refresh: 2; URL=/index.php');//http://www.tigir.com/php.htm
?>