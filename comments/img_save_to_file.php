<?php
    session_start();
	
	$imagePath = "temp/";

	$allowedExts = array("gif", "jpeg", "jpg", "png", "GIF", "JPEG", "JPG", "PNG");
	$temp = explode(".", $_FILES["img"]["name"]);
	$extension = end($temp);
	 
	if ( in_array($extension, $allowedExts))
	  {
	  if ($_FILES["img"]["error"] > 0)
		{
			 $response = array(
				"status" => 'error',
				"message" => 'ERROR Return Code: '. $_FILES["img"]["error"],
			);			
		}
	  else
		{
		  
		  $_SESSION['icon']=$tt=$_GET['icon'];	
		  
	      
		  $filename = $_FILES["img"]["tmp_name"];
		  list($width, $height) = getimagesize( $filename );
		  
		  $_FILES["img"]["name"]=$imagePath.$tt.'.'.$extension;
		   
		  move_uploaded_file($filename, $_FILES["img"]["name"]);
		  
		  $_SESSION['UserUploadImage']='/comments/'.$_FILES["img"]["name"];	
		  
		  $response = array(
			"status" => 'success',
			"url" => '/comments/'.$_FILES["img"]["name"],
			"width" => $width,
			"height" => $height
		  );
		  
		}
	  }
	else
	  {
	   $response = array(
			"status" => 'error',
			"message" => 'something went wrong, most likely file is to large for upload. check upload_max_filesize, post_max_size and memory_limit in you php.ini',
		);
	  }
	  
	  print json_encode($response);

?>
