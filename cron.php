<?php
	/*******************************************************************************
	* ГЛАВНЫЙ РОУТЕР САЙТА
	* @author Желтов Алексей
	* @copyright ©
	* @version 1.0.2
	* @date 21.07.2012
	*******************************************************************************/

	// Установка переменной доступа к файлам
	define('GVS_ACCSESS', true);

	// Подключаем конфигурационный файл
	include './config.php';
	include './config_mail.php';
	// Подключаем основные функции
	include './functions/default.php';
  
	$ScanInfo['Speed-data'] = get_speed_scan_data(0, 3*60);
	$ScanInfo['Speed-link'] = get_speed_scan_link(0, 3*60);
	$period_update = 60*60*24*$SITE['settings']['period_update'];

	// Проходим по всем сайтам
	$i = 0;
	$query = "SELECT * FROM parser_site_list  WHERE run = 1";
	$result = mysqlQuery($query);

	while ($res_site = mysql_fetch_assoc($result)) {
		$site_array[$i] = $res_site;  // массив с данными сайтов
		$id_site = $res_site['id_site'];
		// Проверяем необходимость обновления данных
		$site_array[$i]['update_now'] = check_update_now($res_site['last_update'], $period_update);

		// Определяем скорость
		$site_array[$i]['Speed-data'] = get_speed_scan_data($id_site, 30);
		$site_array[$i]['Speed-link'] = get_speed_scan_link($id_site, 30);
		
		// Определяем количество просканированных ссылок
		$query_1 = "SELECT COUNT(*) FROM parser_link_list
					WHERE id_site = '$id_site' AND
						  block   = 0 AND
						  run     = 1";
		$result_1 = mysqlQuery($query_1);
		$row = mysql_fetch_row($result_1);
		$site_array[$i]['prosses'] = $row[0];

		// Определяем количество оставшихся
		$query_1 = "SELECT COUNT(*) FROM parser_link_list
					WHERE id_site = '$id_site' AND
						  block   = 0 AND
						  run     = 0";
		$result_1 = mysqlQuery($query_1);
		$row = mysql_fetch_row($result_1);
		$site_array[$i]['lost'] = $row[0];// - $site_array[$i]['prosses'];

		// Определяем количество объявлений
		$query_1 = "SELECT COUNT(*) FROM parser_data
					WHERE id_site = '$id_site'";
		$result_1 = mysqlQuery($query_1);
		$row = mysql_fetch_row($result_1);
		$site_array[$i]['database'] = $row[0];

		$i++;
	}
	
	$query = "SELECT COUNT(*) as SiteCount FROM parser_site_list  WHERE last_update = 0 AND block = 0 AND run = 0 ";
	$result = mysqlQuery($query);
	while ($res = mysql_fetch_assoc($result)) {
		$ScanInfo['SiteCount'] = $res['SiteCount'];
	}

	$query = "SELECT value FROM site_settings WHERE name = 'name_parser'";
	$result = mysqlQuery($query);
	while ($res = mysql_fetch_assoc($result)) {
		$ScanInfo['SiteName'] = $res['value'];
	}

	$body = "<h1>" . $ScanInfo['SiteName'] . "</h1>
	
		<p>Скорость прохода по ссылкам: " . number_format($ScanInfo['Speed-link'], 0, ',', ' ') . " в час.</p>
		<p>Скорость сбора объявлений: "   . number_format($ScanInfo['Speed-data'], 0, ',', ' ') . " в час.</p>";
		
		
	$body .= '
	<p>В настоящий момент запущено <b>' . count($site_array) . '</b> сайтов.</p>
	<p>Осталось сайтов ' . $ScanInfo['SiteCount'] . "</p>";
		
	$body .= '		
		<table  border="1" style="width: 100%;">
		<tr>
			<th style="width: 150px;">Наименование</th>
			<th style="width: 50px;">Run</th>
			<th style="width: 50px;">Left</th>
			<th style="width: 50px;">Объявл.</th>
			<th style="width: 25px;">Ссылок<br/>в час</th>
			<th style="width: 25px;">Объявл<br/>в час</th>
		</tr>';
	
	for ($i = 0; $i < count($site_array); $i++) {
		$body .= '<tr>
			<td style="word-wrap: break-word;">' . $site_array[$i]['link'];
		
		if (date("Y-m-d H:i:s", strtotime($site_array[$i]['time_last_link']) + 60*5) < date("Y-m-d H:i:s")) {
			$body .=  "Скорее всего сканирование не идет!<br/>";
		}
		
		$body .= '</td>
			<td>' . number_format($site_array[$i]['prosses'], 0, ',', ' ') . '</td>
			<td>';
		if($site_array[$i]['database']/$site_array[$i]['prosses'] <0.5 and $site_array[$i]['lost'] > 5000) {
			$body .= "Много лишних ссылок, проверьте исключения!";
		} 
		$body .= number_format($site_array[$i]['lost'], 0, ',', ' ');
		$body .= '</td>
			<td>' . number_format($site_array[$i]['database'], 0, ',', ' ') . '</td>
			<td>' . number_format($site_array[$i]['Speed-link'], 0, ',', ' ') . '</td>
			<td>' . number_format($site_array[$i]['Speed-data'], 0, ',', ' ') . '</td>';
			
	}
	
	$body .= '<tr>
			<th>ИТОГО</th>
			<th>' . number_format($run_count, 0, ',', ' ') . '</th>
			<th>' . number_format($lost_count, 0, ',', ' ') . '</th>
			<th>' . number_format($data_count, 0, ',', ' ') . '</th>
			<th>' . number_format($data_count / ($run_count + $lost_count), 2, ',', ' ') . '</th>
			<th>

			</th>	
		</tr>
	</table>';
		
	
	$mail->Subject = "ПарсерИнфо " .  $ScanInfo['SiteName'];
	$mail->setFrom('crm@micro-solution.ru', 'MS-CRM'); //From = 'crm@micro-solution.ru';
	$mail->MsgHTML($body);
	$address = 'zheltov@micro-solution.ru';
	$mail->AddAddress($address,'zheltov@micro-solution.ru');
	$address = 'omoiseev@micro-solution.ru';
	$mail->AddAddress($address,'omoiseev@micro-solution.ru');

	if($mail->Send()){
		echo 'Письмо отправлено';
	}
	else{
		echo $mail->ErrorInfo;
	}
	
	print($body);
	
?>