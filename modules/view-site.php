<?php
/*******************************************************************************
* ����� �������� ������������ �� �����
* @author ������ �������
* @copyright �
* @version 1.1
* @date 07.08.2012
*******************************************************************************/

  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/mod_security.php");

  @set_time_limit(0);
  
  

	$id_site = $_GET['id'];
	$query = "SELECT * FROM parser_site_list  WHERE  id_site = '$id_site' LIMIT 1";
	$result = mysqlQuery($query);

	while ($res_site = mysql_fetch_assoc($result))
	{
		$site_array = $res_site;	
	}
	
	if ($site_array['run'] == 1)
	{
		$site_array['status'] = "Идет сканирование";
	}
	elseif ($site_array['stop_scan'] == 1) 
	{
		$site_array['status'] = "Сканирование сайта остановлено";
	}
	elseif ($site_array['block'] == 1) 
	{
		$site_array['status'] = "Сайт просканирован";
	}	
	else 
	{
		  
		// Проверяем необходимость обновления данных
		$period_update = 60*60*24*$SITE['settings']['period_update'];
		$site_array['update_now'] = check_update_now($site_array['last_update'], $period_update);
		
		if ($site_array['update_now'] == 0)
		{
			$site_array['status'] = "Сайт не требует обновления";
		}
		else 
		{
			$site_array['status'] = "Сайт требует обновления";
		}
	}
	
	
	$query_1 = "SELECT COUNT(*) FROM parser_link_list
		WHERE id_site = '$id_site' AND
			  run = 1 AND
			  block = 0";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array['prosses'] = $row[0];
	
	$query_1 = "SELECT COUNT(*) FROM parser_link_list
				WHERE id_site = '$id_site' AND
							block = 0";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array['all'] = $row[0];	

	$site_array['lost'] = $row[0] - $site_array['prosses'];		
	
	$query_1 = "SELECT COUNT(*) FROM parser_data
		WHERE id_site = '$id_site'";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array['database'] = $row[0];
	
	// Дата последнего объявления
	$query_1 = "SELECT MAX(date_update), link FROM parser_data
				WHERE id_site = '$id_site'";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array['date_update'] = $row[0];	
	$site_array['date_link'] = $row[1];		
	
	// Определяем количество заблокированных ссылок после обработки
	$query_1 = "SELECT COUNT(*) FROM parser_link_list
				WHERE del = 1 AND id_site = '$id_site'";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array['delete'] = $row[0];


	
	


?>