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

	if (!empty($_POST['update']))
    {
		echo "<b>Блокировка tiu...</b>   ";
    	flush();
  		$query = "UPDATE parser_site_list
				  SET block = '1'
				  WHERE adres_start LIKE '%Tiu.ru%'";
       	$result = mysql_query($query);
       	if ($result === false)
       	{
       		echo "<b>ОШИБКА</b><br />";
       		die();
       	}
	   echo "<b>OK</b><br />";
       flush();

	   echo '<b color="#00FF00">Обновление парсера завершено.</b><br />';
       flush();
		/*
    	echo "<b>Создаем резервную копию таблицы parser_link_list...</b>   ";
    	flush();
        $query = "CREATE TABLE `parser_link_list_copy` as select * from `parser_link_list` where 1";
        $result = mysql_query($query);
       	if ($result === false)
       	{       		echo "<b>ОШИБКА</b><br />";
       		die();       	}
	   	echo "<b>OK</b><br />";

		////////////////////////////////////////////////////////////////////////
    	echo "<b>Создание новых столбцов в таблице parser_link_list</b>...<br />Столбец isdata...   ";
    	flush();
		$query = "ALTER TABLE `parser_link_list`
				  CHANGE COLUMN `data_null` `isdata`  int(1) NOT NULL DEFAULT 0 AFTER `num_update_last_price`";
		$result = mysql_query($query);
       	if ($result === false)
       	{
       		echo "<b>ОШИБКА</b><br />";
       		die();
       	}
	   	echo "<b>OK</b><br />";

        ////////////////////////////////////////////////////////////////////////
    	echo "Столбец link_from...   ";
    	flush();
		$query = "ALTER TABLE `parser_link_list`
				  ADD COLUMN `link_from`  int(11) NOT NULL AFTER `new`";
		$result = mysql_query($query);
       	if ($result === false)
       	{
       		echo "<b>ОШИБКА</b><br />";
       		die();
       	}
       	echo "<b>OK</b><br />";
		
       	////////////////////////////////////////////////////////////////////////
    	echo "Столбец content_empty...   ";
    	flush();
		$query = "ALTER TABLE `parser_link_list`
				  ADD COLUMN `content_empty`  int(11) NOT NULL DEFAULT 0 AFTER `link_from`";
       	$result = mysql_query($query);
       	if ($result === false)
       	{
       		echo "<b>ОШИБКА</b><br />";
       		die();
       	}
        echo "<b>OK</b>.<br /><b>Заполнение столбцов данными</b>...<br />";

        ////////////////////////////////////////////////////////////////////////
        echo "isdata = 0...   ";
    	flush();
  		$query = "UPDATE parser_link_list
				  SET isdata = 0";
		echo "<b>OK</b><br />";
		echo "isdata = 1 last_price <> 0...   ";
        flush();
       	$result = mysql_query($query);
       	if ($result === false)
       	{
       		echo "<b>ОШИБКА</b><br />";
       		die();
       	}
       	$query = "UPDATE parser_link_list
				  		SET isdata = 1 WHERE last_price <> ''";
       	$result = mysql_query($query);
       	if ($result === false)
       	{
       		echo "<b>ОШИБКА</b><br />";
       		die();
       	}
	   echo "<b>OK</b><br />";
       flush();

	   echo '<b color="#00FF00">Обновление парсера завершено.</b><br />';
       flush(); 
	   */
	}

	if (!empty($_POST['save']))
    {

		// Получаем настройки
		$query = "SELECT * FROM site_settings";
		$result = mysqlQuery($query);

		while ($res_settings = mysql_fetch_assoc($result))
		{
		  $SITE['settings'][] = $res_settings;
		}

		$i = 0;

		foreach ($SITE['settings'] as $stroka)
		{
			if (!is_array($stroka)) { continue; }
		  $query = "UPDATE site_settings
					SET name      = '" . mysql_real_escape_string($_POST['name_' . $stroka['id']]) . "',
						value     = '" . mysql_real_escape_string($_POST['value_' . $stroka['id']]) . "'
					WHERE id = '" . $stroka['id'] . "'";
		  $result = mysqlQuery($query);

		}

	}

  // Проходим по всем сайтам
  $query = "SELECT * FROM site_settings";
  $result = mysqlQuery($query);

  while ($res_settings = mysql_fetch_assoc($result))
  {	$settings_array[] = $res_settings;  // массив с данными сайтов
  }

	$_REQUEST=$settings_array
?>