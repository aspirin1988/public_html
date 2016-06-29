<?php

$mail_body = '';
$headers = '';
$error = false;

ksort($_POST);

foreach ($_POST as $key => $value)
{
	if ($key != 'mail_to' && $key != 'smtp_server' && $key != 'smtp_port' && $key != 'mail_from' && $key != 'mail_subject' && $key != 'plain_text')
    {
		if ( $key == 'extra' || $key == 'path_configuration' || $key == 'path_structure' || is_array($value) )
			continue;
		$key = preg_replace('/^([0-9]*:)/i', '', $key);
    	$mail_body .= '<b>'.str_replace('_',' ',$key).'</b>:<br/>';
    	$mail_body .= ''.stripslashes($value).'<br/>';
    }
}

$message = '<html><body>'.$mail_body.'</body></html>';

if (isset($_POST['plain_text']) && $_POST['plain_text'] == 'true')
{
    $message = str_replace('<br/>',"\r\n", $message);
    $message = strip_tags($message);
}
else
{
	$headers .= 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
}

$to = $_POST['mail_to'];
$from = $_POST['mail_from'];

if (isset($to) && isset($from))
{
    $headers .= 'From: ' .$from. "\r\n";
	$headers .= 'Reply-To: ' .$from. "\r\n";
	$headers .= 'X-Mailer: PHP/' . phpversion() . "\r\n";
}
else
{
	$error = true;
}

$subject = (isset($_POST['mail_subject'])) ? $_POST['mail_subject'] : '';

header ("Content-Type:text/xml");

if (!$error && @mail($to, $subject, $message, $headers))
{
	print('<response><mail>1</mail></response>');
}
else
{
	print('<response><mail>0</mail></response>');
}