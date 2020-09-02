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
  if (empty($_GET['view'])) { $_GET['view'] = "all"; }
   empty($_GET['page']) ? $page = 1 : $page = $_GET['page'];
   
  if (!empty($_GET['del']))
  {
	if ($_GET['del'] == "all") {
		$query = "DELETE FROM parser_link_list
						WHERE id_site = '" . $_GET['id_site'] . "'";
	}
	else {
			$id_link = $_GET['del'];
		$query = "DELETE FROM parser_link_list
						WHERE id_link = '$id_link'";
	}
	$result = mysqlQuery($query);  }

	if (!empty($_GET['block'])) 
	{
		$query = "UPDATE parser_link_list
				  SET del = 1
				  WHERE id_link = '" . $_GET['block'] . "'";	
		$result = mysqlQuery($query);
	}
  
 	if (!empty($_GET['unblock'])) 
	{
		$query = "UPDATE parser_link_list
				  SET del = 0
				  WHERE id_link = '" . $_GET['unblock'] . "'";	
		$result = mysqlQuery($query);
	} 
  
	if ($_GET['view'] == "lost") 
	{
		  $query = "SELECT * FROM parser_link_list
							 WHERE id_site = '$id_site' AND run = 0
							 LIMIT " . ($page - 1) * 5000 . ", 5000";	
							 
		  $query2 = "SELECT COUNT(*) FROM parser_link_list
                     WHERE id_site = '$id_site' AND run = 0";		
	}
	elseif ($_GET['view'] == "run") 
	{
		  $query = "SELECT * FROM parser_link_list
							 WHERE id_site = '$id_site' AND run = 1
							 ORDER BY id_link  	
							 LIMIT " . ($page - 1) * 5000 . ", 5000";	
				  $query2 = "SELECT COUNT(*) FROM parser_link_list
                     WHERE id_site = '$id_site' AND run = 1";							
	
	}
	elseif ($_GET['view'] == "del") 
	{
		  $query = "SELECT * FROM parser_link_list
							 WHERE id_site = '$id_site' AND del = 1
							 ORDER BY id_link  	
							 LIMIT " . ($page - 1) * 5000 . ", 5000";		
			  $query2 = "SELECT COUNT(*) FROM parser_link_list
                     WHERE id_site = '$id_site' AND del = 1";	
	}	
	else
	{
		  $query = "SELECT * FROM parser_link_list
							 WHERE id_site = '$id_site'
							 ORDER BY id_link 	
							 LIMIT " . ($page - 1) * 5000 . ", 5000";			
			  $query2 = "SELECT COUNT(*) FROM parser_link_list
                     WHERE id_site = '$id_site'";	
	}

  $result = mysqlQuery($query);

  $i = 0;
  while ($link_list = mysql_fetch_assoc($result))
  {    $links_array[] = $link_list;  // массив с данными ссылок
  }

  $result = mysqlQuery($query2);
$row = mysql_fetch_row($result);
$count_page = $row[0]/5000;  

?>