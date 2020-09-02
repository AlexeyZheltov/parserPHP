<?php
/*******************************************************************************
* ВЫВОД ССЫЛОК ИСКЛЮЧЕНИЙ
* @author Желтов Алексей
* @copyright ©
* @version 1
* @date 04.01.2013
*******************************************************************************/
  // Модуль безопасности
  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/mod_security.php");

  empty($_GET['id_site']) ? $id_site = 0 : $id_site = $_GET['id_site'];

  empty($_GET['page']) ? $page = 1 : $page = $_GET['page'];	
 
    if (!empty($_GET['block']))
  {
	$id_site = $_GET['id_site'];
	$query = "UPDATE parser_site_list
				SET block = 1
				WHERE id_site = '$id_site' 
				LIMIT 1";
	$result = mysqlQuery($query);
  }
  
  // Проходим по всем сайтам
  $query = "SELECT * FROM parser_data
                     WHERE id_site = '$id_site'
                     LIMIT " . ($page - 1) * 5000 . ", 5000";
  $result = mysqlQuery($query);

  $i = 0;
  while ($data_list = mysql_fetch_assoc($result))
  {    $data_array[] = $data_list;  // массив с данными ссылок
  }


  
	// Проходим по всем сайтам
  $query = "SELECT COUNT(*) FROM parser_data
                     WHERE id_site = '$id_site'";
  $result = mysqlQuery($query);
$row = mysql_fetch_row($result);
$count_page = $row[0]/5000;  


?>