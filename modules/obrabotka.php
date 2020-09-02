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
	
	$load_msg = "";
	
	if (!empty($_POST['load']))
	{
		// Проверяем загружен ли файл
		if(is_uploaded_file($_FILES["f_cvs"]["tmp_name"]))
		{
			echo "Загрузка файла...   ";
			flush();
			// Если файл загружен успешно, перемещаем его
			// из временной директории в конечную
			move_uploaded_file($_FILES["f_cvs"]["tmp_name"], GVS_ROOT . "files/CVS/" . $_FILES["f_cvs"]["name"]);
			
			echo "<b>ОК.</b><br />   ";
			flush();
			
			$load_msg = "Файл загружен.";
				
			$row = 1;
			$handle = fopen(GVS_ROOT . "files/CVS/" . $_FILES["f_cvs"]["name"], "r");
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) 
			{
				if ($row == 1) 
				{
					$row++;
					continue;
				}

				$row++;
				$delete = $data[1];
				$id_link = $data[0];

				// блокируем или разблокируем ссылку parser_site_list
				$query = "SELECT id_link FROM parser_data WHERE id_data = '$id_link' LIMIT 1";
				
				$result = mysqlQuery($query);
				while ($site = mysql_fetch_assoc($result))
				{
					$id_link = $site['id_link'];
				}
				
				// блокируем или разблокируем ссылку parser_site_list
				$query = "UPDATE parser_link_list
							 SET del = '$delete'
							 WHERE id_link = '$id_link'";
				$result = mysqlQuery($query);

				echo "$id_link = $delete - ОК.<br />";
				flush();
			}
			fclose($handle);
			
			$load_msg .= " Изменения внесены в БД";
			
		} 
		else 
		{
			$load_msg = "Ошибка загрузки файла";
		}
	}
?>