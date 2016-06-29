<?php
try
{
	require_once 'admin/config/ProjectConfig.php';
	ProjectConfig::registerAutoload();
	ProjectConfig::loadConstants();
	ProjectConfig::startSession();
	ProjectConfig::initLog();

	$options = array(
		'searchLeftPartSize' => 20,
		'searchRightPartSize' => 20,
	);
	$search = new MotoSearch($options);
	$str = '';
	$str = (isset($_REQUEST['q']) ? $_REQUEST['q'] : $str);
	$p = (isset($_REQUEST['p']) ? $_REQUEST['p'] : false);
	echo $search->dispatch($str, $p);

}
catch(Exception $e)
{
	//echo $e->getTraceAsString();
}