<?php
	/*******************************************************************************
	* ВЫВОД ПРОЦЕССА СКАНИРОВАНИЯ НА ЭКРАН
	* @author Желтов Алексей
	* @copyright ©
	* @version 1.1
	* @date 07.08.2012
	*******************************************************************************/
	// Модуль безопасности
	if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/mod_security.php");

	@set_time_limit(0);

	if (!empty($_GET['stop_site'])) {
		$id_site = $_GET['stop_site'];
		$query = "UPDATE parser_site_list
				  SET stop_scan = 1, run = 0
				  WHERE id_site = '$id_site'
				  LIMIT 1";
		$result = mysqlQuery($query);
	}
	
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

/*
		// Определяем количество заблокированных объявлений
		$query_1 = "SELECT COUNT(*) FROM parser_link_list
					WHERE id_site = '$id_site' AND 
						  del     = 1";
		$result_1 = mysqlQuery($query_1);
		$row = mysql_fetch_row($result_1);
		$site_array[$i]['delete'] = $row[0];
*/
		// Определяем количество объявлений
		$query_1 = "SELECT COUNT(*) FROM parser_data
					WHERE id_site = '$id_site'";
		$result_1 = mysqlQuery($query_1);
		$row = mysql_fetch_row($result_1);
		$site_array[$i]['database'] = $row[0];

		$i++;
	}
?>