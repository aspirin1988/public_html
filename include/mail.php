<?

 function file_ext ($filename)  {
    $path_info = pathinfo($filename);
    return $path_info['extension'];
  }
$domain='../'; 
function multipart_mail($from, $to, $subject, $text,$cc=null) {

       global $domain; //Не забудьте проинициализировать 
       $domain='../';
	   $headers ="From: $from\n";
       $headers.="Date: ".date("r")."\n";

       $headers.="Return-Path: $from\n";
       $headers.="X-Mailer: zm php script\n";
       $headers.="MIME-Version: 1.0\n";

       $headers.="Content-Type: multipart/alternative;\n";
       $baseboundary="------------".strtoupper(md5(uniqid(rand(), true)));

       $headers.="  boundary=\"$baseboundary\"\n";
       $headers.="This is a multi-part message in MIME format.\n";
       $message="--$baseboundary\n";

       $message.="Content-Type: text/plain; charset=utf-8\n";//windows-1251
       $message.="Content-Transfer-Encoding: 7bit\n\n";
       $info=$text;
	   $text_plain=str_replace('<p>',"\n",$text);

       $text_plain=str_replace('<b>',"",$text_plain);
       $text_plain=str_replace('</b>',"",$text_plain);

       $text_plain=str_replace('<br>',"\n",$text_plain);
       $text_plain= preg_replace('/<a(\s+)href="([^"]+)"([^>]+)>([^<]+)/i',"

\$4\n\$2",$text_plain);
       $message.=strip_tags($text_plain);
       //
	   $message.=strip_tags($info)."\n\n\n\n";//Its simple text. Switch to HTML view!

       $message.="--$baseboundary\n";
       $newboundary="------------".strtoupper(md5(uniqid(rand(), true)));

       $message.="Content-Type: multipart/related;\n";
       $message.="  boundary=\"$newboundary\"\n\n\n";
       $message.="--$newboundary\n";
       $message.="Content-Type: text/html; charset=utf-8\n";//windows-1251

       $message.="Content-Transfer-Encoding: 7bit\n\n";
       $message.=($text)."\n\n";

	   preg_match_all('/img(.*)src="([^"]+)"/i',$text,$m);
       if (isset($m[2])) {
               $img_f=$m[2];
               if (is_array($img_f)) {
                      foreach ($img_f as $k => $v) {
                               $img_f[$k]=str_replace($domain.'/','',$v);
							   //str_ireplace($domain.'/','',$v);
                       }
               }
       }
       $attachment_files=$img_f;
		if (is_array($attachment_files)) {
		foreach($attachment_files as $filename)  {
	   $file_content = file_get_contents($domain.$filename,true);
       $mime_type='image/png';
       if(function_exists("mime_content_type"))  {

               $mime_type=mime_content_type($filename);
       }
       else {
               switch (file_ext($filename))    {
                       case 'jpg': $mime_type='image/jpeg';break;
                       case 'gif': $mime_type='image/gif';break;
                       case 'png': $mime_type='image/png';break;
                       default:$mime_type='image/jpeg';break;
               }
       }

                               $message=str_replace($filename,'cid:'.basename($filename),$message);

                               $filename=basename($filename);
                               $message.="--$newboundary\n";
                               $message.="Content-Type: $mime_type;\n";
                               $message.=" name=\"$filename\"\n";

                               $message.="Content-Transfer-Encoding: base64\n";
                               $message.="Content-ID: <$filename>\n";
                               $message.="Content-Disposition: inline;\n";
                               $message.=" filename=\"$filename\"\n\n";

                               $message.=chunk_split(base64_encode($file_content));
       }
       }
       $message.="--$newboundary--\n\n";
       $message.="--$baseboundary--\n";

       mail($to, $subject, $message , $headers);
}

function kmail( $from, $to, $subj, $text, $filename) {

//$text=iconv("cp1251","utf-8",$text);
//$subj=iconv("cp1251","utf-8",$subj);
//$f = fopen($filename[0],"rb");
//$ff = fopen($filename[1],"rb");

$un = strtoupper(uniqid(time()));
$head = "From: $from\n";
$head .= "To: $to\n";
$head .= "Subject: $subj\n";
//$head .= "Reply-To: $from\n";
$head .= "Mime-Version: 1.0\n";
$head .= "Content-Type:multipart/mixed;";
$head .= "boundary=\"----------".$un."\"\n\n";
$zag = "------------".$un."\nContent-Type:text/html; charset=windows-1251\n";
$zag .= "Content-Transfer-Encoding: 7bit\n\n$text\n\n";

if (!is_array($filename)){
	$_filename=array();
	$_filename[]=$filename;
	$filename=$_filename;
}
	foreach ($filename as $a=>$b){
		$f = fopen($b,"rb");
		$zag .= "------------".$un."\n";
		$zag .= "Content-Type: application/octet-stream;";
		$zag .= "name=\"".basename($a)."\"\n";
		$zag .= "Content-Transfer-Encoding:base64\n";
		$zag .= "Content-Disposition:attachment;";
		$zag .= "filename=\"".basename($a)."\"\n\n";
		$zag .= chunk_split(base64_encode(fread($f,filesize($b))))."\n";
		fclose($f);
	}

return @mail("$to", "$subj", $zag, $head);
}

?>