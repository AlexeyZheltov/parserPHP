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

  //@set_time_limit(0);
  empty($_GET['id_site']) ? $id_site = 0 : $id_site = $_GET['id_site'];

  if (!empty($_POST['add_white']) and !empty($_POST['like_str']))
  {
    if (!empty($_POST['id_site']))
    {
    	$id_site = $_POST['id_site'];
    }
    $like_str = $_POST['like_str'];
    $query = "INSERT INTO parser_white_list (id_site, like_str) VALUE ('$id_site', '$like_str')";
    $result = mysqlQuery($query);
  }


	if (!empty($_POST['clear']))
	{
		$query = "SELECT * FROM parser_link_list
		WHERE id_site = '$id_site'";
		$result = mysqlQuery($query);
		$count = 0;

		while ($white_link = mysql_fetch_assoc($result))
		{
			$white = 0;
			$query_like = "SELECT * FROM parser_white_list
			WHERE id_site = '$id_site' OR id_site = 0";
			$result2 = mysqlQuery($query_like);
			while ($white_like = mysql_fetch_assoc($result2))
			{
				$str_like = str_replace ("%", "", $white_like['like_str']);
				$str_like = str_replace ("\\", "", $str_like);
				//ECHO $white_like['like_str'];
				//ECHO $id_site;
				$pos = strpos($white_link['link'], $str_like);
				if ($pos === false)
				{
					
				}
				else 
				{
					$white = 1; 
					break;
				}
			}
			if ($white == 0) {
				$count++;
				$id_link_del = $white_link['id_link'];
				$query_del = "DELETE FROM parser_link_list
								WHERE id_link = '$id_link_del'";
				$result_del = mysqlQuery($query_del);
			}
		}
		echo "Удалено " . $count . " строк";	}

   if (!empty($_GET['del']))
   {
     $del_white = $_GET['del'];     $query = "DELETE FROM parser_white_list
                     WHERE id_white = '$del_white'";
     $result = mysqlQuery($query);   }


  // Проходим по всем сайтам
  $query = "SELECT * FROM parser_white_list
                     WHERE id_site = '$id_site'";
  $result = mysqlQuery($query);

  $i = 0;
  while ($white_link = mysql_fetch_assoc($result))
  {    $whites_array[] = $white_link;  // массив с данными ссылок
  }


?>