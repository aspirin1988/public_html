<?
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

function image_resize($outfile,$infile,$neww,$newh,$quality){
	$prop = getimagesize($infile);
	
	
	if ( ($neww!="0")&&($newh!="0") ){
		$newHeight=$newh;
		$newWidth=ceil($prop[0]*$newHeight/$prop[1]);
		
		if ($newWidth<$neww)
			while ($newWidth!=$neww){
			$newWidth++;
			$newHeight=ceil($prop[1]*$newWidth/$prop[0]);
			}
		$newHeight=$newh;
		if ($newWidth<=$neww){
			$newWidth=$neww;
			$newHeight=ceil($prop[1]*$newWidth/$prop[0]);
		}
	}elseif ($neww!="0"){
		$newWidth=$neww;
		$newHeight=ceil($prop[1]*$newWidth/$prop[0]);
		$neww=$newWidth;$newh=$newHeight;
	}elseif ($newh!="0"){
		$newHeight=$newh;
		$newWidth=ceil($prop[0]*$newHeight/$prop[1]);
		$neww=$newWidth;$newh=$newHeight;
	}
	
	$im1=imagecreatetruecolor($newWidth,$newHeight);
	$im2=imagecreatetruecolor($neww,$newh);
	switch( image_type($prop["mime"]) ){
		case 'jpg':{
			$img=imagecreatefromjpeg($infile);
			
			imagecopyresampled($im1,$img,0,0,0,0,$newWidth,$newHeight,$prop[0],$prop[1]);
			if ($newWidth>$neww){
				$_x=round ( ($newWidth-$neww)/2 );
				imagecopyresampled($im2,$im1,0,0,$_x,0,$neww,$newHeight,$neww,$newHeight);
			}else imagecopyresampled($im2,$im1,0,0,0,0,$neww,$newh,$neww,$newh);
			
			
			if ($outfile=='') header("Content-Type: image/jpg");
			imagejpeg($im2,$outfile,$quality);
			imagedestroy($im1);imagedestroy($img);
		}break;
		case 'gif':{
			$img=imagecreatefromgif($infile);
			
			imagecopyresampled($im1,$img,0,0,0,0,$newWidth,$newHeight,$prop[0],$prop[1]);
			if ($newWidth>$neww){
				$_x=round ( ($newWidth-$neww)/2 );
				imagecopyresampled($im2,$im1,0,0,$_x,0,$neww,$newHeight,$neww,$newHeight);
			}else imagecopyresampled($im2,$im1,0,0,0,0,$neww,$newh,$neww,$newh);
			
			if ($outfile=='') header("Content-Type: image/gif");
			imagegif($im1);
			imagedestroy($im1);imagedestroy($img);
		}break;
		case 'png':{
		$res = imageCreateFromPng($infile);
		
		$tmp = imageCreateTrueColor($newWidth, $newHeight);
		imageAlphaBlending($tmp, false);
		imageSaveAlpha($tmp, true);
		
		imagecopyresampled($im1,$img,0,0,0,0,$newWidth,$newHeight,$prop[0],$prop[1]);
			if ($newWidth>$neww){
				$_x=round ( ($newWidth-$neww)/2 );
				imagecopyresampled($im2,$im1,0,0,$_x,0,$neww,$newHeight,$neww,$newHeight);
			}else imagecopyresampled($im2,$im1,0,0,0,0,$neww,$newh,$neww,$newh);
			
			
		if ($outfile=='') header("Content-Type: image/png");
		imagePng($tmp);//,$outfile
		
		imagedestroy($tmp);imagedestroy($res);
		}break;
	}
}

function waterMark($original, $watermark, $placement = 'bottom=5,right=5', $destination = null) {

	$original = urldecode($original);
	$info_o = @getImageSize($original);
	if (!$info_o) return false;
	$info_w = @getImageSize($watermark); 
	if (!$info_w) return false; 

	list($vertical, $horizontal)=explode(',', $placement);
	list($vertical, $sy) =explode('=', $vertical);
	list($horizontal, $sx)=explode('=', $horizontal);
	
   switch (trim($vertical)) { 
      case 'bottom': 
         $y = $info_o[1] - $info_w[1] - (int)$sy; 
         break; 
      case 'middle': 
         $y = ceil($info_o[1]/2) - ceil($info_w[1]/2) + (int)$sy; 
         break; 
      default: 
         $y = (int)$sy; 
         break; 
      } 

   switch (trim($horizontal)) { 
      case 'right': 
         $x = $info_o[0] - $info_w[0] - (int)$sx; 
         break; 
      case 'center': 
         $x = ceil($info_o[0]/2) - ceil($info_w[0]/2) + (int)$sx; 
         break; 
      default: 
         $x = (int)$sx; 
         break; 
      } 

   header("Content-Type: ".$info_o['mime']); 

   $original = @imageCreateFromString(file_get_contents($original)); 
   $watermark = @imageCreateFromString(file_get_contents($watermark)); 
   imageAlphaBlending($watermark, false);imageSaveAlpha($watermark, true);
   
   $out = imageCreateTrueColor($info_o[0],$info_o[1]); 

   imageCopy($out, $original, 0, 0, 0, 0, $info_o[0], $info_o[1]); 
   if( ($info_o[0] > 250) && ($info_o[1] > 250) )
   {
   imageCopy($out, $watermark, $x, $y, 0, 0, $info_w[0], $info_w[1]);
   }

   switch ($info_o[2]) { 
      case 1: 
         imageGIF($out); 
         break; 
      case 2: 
         imageJPEG($out,"",95); 
         break; 
      case 3: 
         imagePNG($out); 
         break; 
         } 

   imageDestroy($out); 
   imageDestroy($original); 
   imageDestroy($watermark); 

   return true;
} 

$file=$_SERVER['DOCUMENT_ROOT'].$_SERVER['REQUEST_URI'];
if (strpos($file,"/icons")>0){
	$file=str_replace("/icons","",$file);

	if ( isset($_GET['h']) ) $h=$_GET['h']; else $h=300;
	if ( isset($_GET['w']) ) $w=$_GET['w']; else $w=300;
	
	$file=explode("?",$file); $file=$file[0];
	
	image_resize("",$file,$w,$h,100);
	}elseif (strpos($file,"/sicons")>0){
	$file=str_replace("/sicons","",$file);
	image_resize("",$file,102,102,90);
	}elseif (strpos($file,"/big1")>0){
	$file=str_replace("/big1","",$file);
	image_resize("",$file,1600,0,90);
	}elseif (strpos($file,"/gallery_icons")>0){
	$file=str_replace("/gallery_icons","",$file);
	image_resize("",$file,160,173,90);
}elseif (strpos($file,"/gallery/icons/")>0){
	$file=str_replace("icons/","",$file);
	image_resize("",$file,120,120,90);
}elseif (strpos($file,"newsicons")>0){
	if ( !empty($_GET['h']) ) $h=$_GET['h']; else $h=180;
	if ( !empty($_GET['w']) ) $w=$_GET['w']; else $w=180;
	
	$file=str_replace("/img/newsicons/","/assets/image/news/",$file);
	image_resize("",$file,$w,$h,100);
}elseif (strpos($file,"texticons")>0){
	if ( !empty($_GET['h']) ) $h=$_GET['h']; else $h=300;
	if ( !empty($_GET['w']) ) $w=$_GET['w']; else $w=300;
	$file=str_replace("/img/texticons/","/assets/image/text/",$file);
	$file=explode("?",$file); $file=$file[0];
	
	image_resize("",$file,$w,$h,100);
}elseif (strpos($file,"projectsicon")>0){
	$file=str_replace("/img/projectsicon/","/assets/image/text/",$file);
	image_resize("",$file,0,145,90);
}elseif (strpos($file,"catalogue")>0){
	$file=str_replace("/catalogue","",$file);
	waterMark($file, "watermark.png", "middle=155,center=5"); 
}


?>