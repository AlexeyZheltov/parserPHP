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

	empty($_GET['page']) ? $page = 0 : $page = $_GET['page'];

	if (!empty($_GET['id_site'])) {
		udpate_link_whith_data($SITE['settings']['period_update']);
	}

	if (!empty($_GET['check_tegs'])) {
		check_site_on_correct_tegs($SITE['settings']['period_update']);
	}
	
	if (!empty($_GET['block'])) {
		$id_site = $_GET['block'];
		$query = "UPDATE parser_site_list
					SET block = 1
					WHERE id_site = '$id_site' 
					LIMIT 1";
		$result = mysqlQuery($query);
	}

	// Проходим по всем сайтам
	$query = "SELECT * FROM parser_site_list  
	          WHERE  ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_update)) > " . $SITE['settings']['period_update'] . "*60*60*24 ) AND 
			          block = 0 AND 
					  last_update > 0 AND 
					  run=0 
			  LIMIT " . $page * 1000 . ", 1000"; //
	$result = mysqlQuery($query);
	$i = 0;
	$period_update = 60*60*24*$SITE['settings']['period_update'];	
	
	while ($res_site = mysql_fetch_assoc($result)) {		$site_array[$i] = $res_site;  // массив с данными сайтов

		$site_array[$i]['update_now'] = check_update_now($res_site['last_update'], $period_update);

		// Определяем количество просканированных ссылок
		$id_site = $res_site['id_site'];
		$query_1 = "SELECT COUNT(*) FROM parser_link_list
					WHERE id_site = '$id_site' AND
						  run = 1 AND isdata = 1";
		$result_1 = mysqlQuery($query_1);
		$row = mysql_fetch_row($result_1);
		$site_array[$i]['prosses'] = $row[0];

		// Определяем количество оставшихся
		$query_1 = "SELECT COUNT(*) FROM parser_link_list
					WHERE id_site = '$id_site' AND
								isdata = 1";
		$result_1 = mysqlQuery($query_1);
		$row = mysql_fetch_row($result_1);
		$site_array[$i]['all'] = $row[0];

		$i++;
	}

	// Проходим по всем сайтам
	$query = "SELECT COUNT(*) FROM parser_site_list WHERE  ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_update)) > '$period_update' ) AND block = 0 AND last_update > 0";
	$result = mysqlQuery($query);
	$row = mysql_fetch_row($result);
	$count_page = $row[0]/1000;  
?>