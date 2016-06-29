<?php

if (file_exists("./admin/actions/precheck.php"))
{
	$testing_from = "";
	require_once "./admin/actions/precheck.php";
}

require_once 'admin/config/ProjectConfig.php';
ProjectConfig::registerAutoload();
ProjectConfig::loadConstants();
ProjectConfig::startSession();
ProjectConfig::initLog();
/* Init templating engine. See http://components.symfony-project.org/templating/documentation */
$config = MotoFrontController::loadConfig();
$filename = dirname(__FILE__).'/admin/templates/website/%name%.tpl.php';
if (
	isset($config["underConstruction"]) && $config["underConstruction"] == "true" &&
		(!isset($_GET["preview"]) || $_GET["preview"] != "true")
	)
	{
		$themeName = "standard_white";
		if (isset($config["underConstructionTheme"]) && $config["underConstructionTheme"] != "")
			$themeName = $config["underConstructionTheme"];
		$filename = dirname(__FILE__) . '/admin/templates/sections/coming_soon/' . $themeName . ".tpl.php";
		if (!file_exists($filename))
		{
			echo "<h1>Under Construction</h1>";
			exit;
		}
	}
$loader = new sfTemplateLoaderFilesystem($filename);
$engine = new sfTemplateEngine($loader);

$helperSet = new sfTemplateHelperSet(array(
  new sfTemplateHelperAssets(),
  new sfTemplateHelperJavascripts(),
  new sfTemplateHelperStylesheets()
));
eval('$helperSet->get(\'assets\')->setBasePath(MOTO_ROOT_URL);');
$engine->setHelperSet($helperSet);
try
{
	$controller = new MotoFrontController($engine);
	echo $controller->dispatch();
}
catch(Exception $e)
{
	//echo $e->getMessage();
}