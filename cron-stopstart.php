<?php
	// Установка переменной доступа к файлам
	define('GVS_ACCSESS', true);

	// Подключаем конфигурационный файл
	include './config.php';
	include './functions/default.php';
	include './config_mail.php';
	
	// Получаем настройки
	$query = "SELECT name, value FROM site_settings";
	$result = mysqlQuery($query);
	while ($res_settings = mysql_fetch_assoc($result))
	{
		$SETTINGS[$res_settings['name']] = $res_settings['value'];
	}
	$delay = $SETTINGS['site_stoptime'];
	$process_count = $SETTINGS['process_count'];
	define ('PrtScnDir', $SETTINGS['PrtScnDir']);
	
	$body = "";
	
	// Получаем Количество непройденных сайтов
	$query = "SELECT COUNT(*) as SiteCount FROM parser_site_list WHERE last_update = 0 AND block = 0 AND run = 0 ";
	$result = mysqlQuery($query);
	if ($res = mysql_fetch_assoc($result)) 
	{
		$SiteCount = $res['SiteCount'];
	}
	
	// Запрашиваем сайты на сканировании
	$i = 0;
	$query = "SELECT * FROM parser_site_list  WHERE run = 1";
	$result = mysqlQuery($query);
	while ($res_site = mysql_fetch_assoc($result)) 
	{
		$SITES[$i] = $res_site; 
		$i++;
	}

	// Количество активных сайтов
	$active_count = 0;
	for ($i = 0; $i < count($SITES); $i++) 
	{
		if (date("Y-m-d H:i:s", strtotime($SITES[$i]['time_last_link']) + $delay) > date("Y-m-d H:i:s")) 
		{
			$active_count++;
			if ($active_count > $process_count) 
			{
				// Останавливаем лишнее сканирование
				$query = "UPDATE parser_site_list SET stop_scan =  1 WHERE id_site = " . $SITES[$i]['id_site'];
				$result = mysqlQuery($query);	
			}
		}	
	}

	print "<p><b>Настройки системы:</b></p>";
	print "<p>Количество процессов - $process_count </br>";
	print "Остановка сайта через - " . $delay/60 . " мин. неактивности</p>";
	print "<p><b>Статус сканирования:</b></p>";
	print "<p>Осталось сайтов - $SiteCount </br>";
	print "Активно сканируются - $active_count </br>";
	print_r($SITES);
	if ($active_count < $process_count) 
	{
		if ($SiteCount > 0) 
		{			
			// Запуск сканирования
			$query = "SELECT * FROM parser_site_list WHERE last_update = 0 AND block = 0 AND run = 0 AND stop_scan = 0";
			$result = mysqlQuery($query);

			if($site = mysql_fetch_assoc($result))
			{
				scan_site($site['id_site']);
				exit();
			}
		}
	}
	
	// Останавливаем зависшие сайты
	for ($i = 0; $i < count($SITES); $i++) 
	{
		// Проверяем время прохода последней ссылки
		if (date("Y-m-d H:i:s", strtotime($SITES[$i]['time_last_link']) + $delay) < date("Y-m-d H:i:s")) 
		{
			// Если больше задержки - Останавливаем сканирование
				$query = "UPDATE parser_site_list SET run = 0 WHERE id_site = " . $SITES[$i]['id_site'];
			$result = mysqlQuery($query);		
			$body .=  "Сайт " . $SITES[$i]['link'] . " завис, поэтому мы его приостановили.<br/>";
		}
	}
	
	
	// Запрашиваем остановленные сайты и делаем их доступными для сканирования
	$i = 0;
	$query = "SELECT * FROM parser_site_list WHERE stop_scan = 1";
	$result = mysqlQuery($query);
	while ($res_site = mysql_fetch_assoc($result)) 
	{
		// Проверяем время прохода последней ссылки
		if (date("Y-m-d H:i:s", strtotime($res_site['time_last_link']) + $delay) < date("Y-m-d H:i:s")) 
		{
			// Если больше задержки - Останавливаем сканирование
			$query = "UPDATE parser_site_list SET stop_scan = 0 WHERE id_site = " .$res_site['id_site'];
			$result2 = mysqlQuery($query);		
		}
	}

	//
	$query = "SELECT * FROM parser_process WHERE id = 1";
	$result = mysqlQuery($query);
	while ($res_site = mysql_fetch_assoc($result)) {
		// Проверяем время прохода последней ссылки
		if ($res_site['run'] == 1 && date("Y-m-d H:i:s", strtotime($res_site['date_update']) + $delay/2) < date("Y-m-d H:i:s")) {
			// Если больше задержки - Останавливаем сканирование
			
			udpate_link_whith_data($SETTINGS['period_update']);
		}
	}
	
	print($body);
	if ($body != "") 
	{
		$query = "SELECT value FROM site_settings WHERE name = 'name_parser'";
		$result = mysqlQuery($query);
		while ($res = mysql_fetch_assoc($result)) {
			$ScanInfo['SiteName'] = $res['value'];
		}
		
		$body = "<h1>" . $ScanInfo['SiteName'] . "</h1><p>" . $body . "</p>";
		
		$mail->Subject = "ПарсерИнфо " .  $ScanInfo['SiteName'];
		$mail->setFrom('crm@micro-solution.ru', 'MS-CRM');
		$mail->MsgHTML($body);
		
		$addreses = explode(";",  $SETTINGS['email']);
		for ($i = 0; $i < count($addreses); $i++) 
		{
			print($addreses[$i]);
			$mail->AddAddress($addreses[$i],$addreses[$i]);
		}

		if($mail->Send())
		{
			echo 'Письмо отправлено';
		}
		else 
		{
			echo $mail->ErrorInfo;
		}
	}
	
?>