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

  if (!empty($_POST['add_block']) and !empty($_POST['like_str']))
  {
    if (!empty($_POST['id_site']))
    {
    	$id_site = $_POST['id_site'];
    }
    $like_str = $_POST['like_str'];
    $query = "INSERT INTO parser_block_list (id_site, like_str) VALUE ('$id_site', '$like_str')";
    $result = mysqlQuery($query);
  }

	if (!empty($_POST['clear'])) {
	  
		$query_link_list = "SELECT * FROM parser_link_list
						 WHERE id_site = '$id_site'";
		$result_link_list = mysqlQuery($query_link_list);	
		$count = 0;
		while ($link = mysql_fetch_assoc($result_link_list)) {
			$block = 0;
			$query = "SELECT * FROM parser_block_list
						 WHERE id_site = '$id_site' OR id_site = 0";
			$result = mysqlQuery($query);
		
			while ($block_like = mysql_fetch_assoc($result)) {
				$str_like = str_replace ("%", "", $block_like['like_str']);
				$str_like = str_replace ("\\", "", $str_like);

				$pos = strpos($link['link'], $str_like);
				if ($pos === false)
				{
						
				}
				else 
				{
					$block = 1; 
					break;
				}
			}
			if ($block == 1) {
				$count++;
				$id_link_del = $link['id_link'];
				$query_del = "DELETE FROM parser_link_list
								WHERE id_link = '$id_link_del'";
				$result_del = mysqlQuery($query_del);
			}
			
		}
		echo "Удалено " . $count . " строк";	}

   if (!empty($_GET['del']))
   {
     $del_block = $_GET['del'];     $query = "DELETE FROM parser_block_list
                     WHERE id_block = '$del_block'";
     $result = mysqlQuery($query);   }


  // Проходим по всем сайтам
  $query = "SELECT * FROM parser_block_list
                     WHERE id_site = '$id_site'";
  $result = mysqlQuery($query);

  $i = 0;
  while ($block_link = mysql_fetch_assoc($result))
  {    $blocks_array[] = $block_link;  // массив с данными ссылок
  }


?>