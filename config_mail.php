<?php
	require_once('./modules/phpmailer/class.phpmailer.php');
	require_once('./modules/phpmailer/class.smtp.php');
	$mail = new PHPMailer();
	
	$mail->CharSet    = "utf-8"; 
	$mail->SMTPDebug = 1;
	//$mail->Mailer = "smtp";
	$mail->isSMTP(); 
	$mail->Host = "smtp.yandex.ru";    
	$mail->SMTPAuth = true;
	$mail->Username   = "crm@micro-solution.ru";  
	$mail->Password   = "microcrmsolution";
	$mail->SMTPSecure = "ssl";
	$mail->Port       = 465;                  
	//$mail->SmtpSend(); 
	
?>