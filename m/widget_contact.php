<?php
require_once 'admin/config/ProjectConfig.php';
ProjectConfig::setup();
@ini_set('display_errors', 'off');

$action = (isset($_POST['action']) ? $_POST['action'] : 'sendemail');

$messages = array();
if ($action == 'upload')
{
    function _phpIniSizeToInt($size)
    {
        preg_match('/^([0-9]+)\s*(P|T|G|M|K){0,1}/i', $size, $matches);
        $value = isset($matches[1]) ? (int) $matches[1] : 0;
        $unit = isset($matches[2]) ? strtoupper($matches[2]) : '';
        switch($unit) {
            case 'P':
                $value *= 1024;
            case 'T':
                $value *= 1024;
            case 'G':
                $value *= 1024;
            case 'M':
                $value *= 1024;
            case 'K':
                $value *= 1024;
                break;
        }
        return $value;
    }
    
    $uploaded = true;
	if (empty($_FILES) || !isset($_FILES['file']))
	{
	   	$uploaded = false;
	   	array_push($messages, "Your file size exceeds maximum permitted file size. Please check your server configuration.");
	}
    else
    {
		try 
        {
            
            $fileMap = array();
            $date = date('Y-m-d', time());
            $originalFilename = $_FILES['file']['name'];
            foreach($_FILES as $key => &$file)
            {
                $newName =  'contact-' . $date .'-'. uniqid() .'-' . rand(1000,9999);
                $fileMap[$newName] = $file['name'];
                $file['name'] = $newName;
            }


		    $upload = new Zend_File_Transfer_Adapter_Http();
		    $upload->setDestination(MOTO_ADMIN_DIR . '/_tmp');
			$dir = MOTO_ADMIN_DIR . '/_tmp';
//		    $upload->addFilter(new MotoFilterFileUniqueRename());
		    $upload->addValidator('ExcludeExtension', false, array('php', 'exe', 'com', 'py', 'sh', 'php5', 'php4', 'php3'));
		 	$uploadMaxSize = min(
				_phpIniSizeToInt(ini_get('post_max_size')),
				_phpIniSizeToInt(ini_get('upload_max_filesize')));

		    $upload->addValidator('Size', false, $uploadMaxSize);
		    if (!($uploaded = $upload->receive()))
		    {
		    	$uploaded = false;
		    	$errors = $upload->getMessages();
		    	foreach ($errors as $key => $message)
		    	{
		    		array_push($messages, $message);
		    	}
		    }
            $filename = $upload->getFileName('file');
		    if ($uploaded && is_string($filename) && file_exists($filename))
		    {
				MotoUtil::fixFilePermission( $filename );
			    $info = pathinfo($filename);
                $filename = $info['basename'];
            }
            else
            {
                throw new Exception('Ooops');
            }

		} catch (Exception $e) {
		    $uploaded = false;
		    $messages = array($e->getMessage());
		}
    }

	$result = array(
		'success' => $uploaded,
	);
	if ($uploaded)
	{
		$result['file'] = $filename;
		$result['original'] = $originalFilename;
	}
	else
	{
		$result['errors'] = $messages;
	}

	header ('Content-Type:application/json');
	echo json_encode($result);

	exit;
    
}

$result = array(
	'status' => true,
	'message' => '',
	'code' => 0,
);
try
{
	$form = new MotoForm($_POST);
	$form->dispatch();
	$result = $form->getResult();
}
catch(Exception $e)
{
	$result['status'] = false;
	$result['message'] = $e->getMessage();
	$result['code'] = $e->getCode();
}

function mail_contact_answer($result)
{
	error_reporting(0);
	header('Content-Type:application/json');
	echo json_encode($result);
    exit;
}

mail_contact_answer($result);
