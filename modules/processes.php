<?php
/*******************************************************************************
* ����� �������� ������������ �� �����
* @author ������ �������
* @copyright �
* @version 1.1
* @date 07.08.2012
*******************************************************************************/
  // ������ ������������
  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/mod_security.php");

  @set_time_limit(0);
  
  	// �������� �� ���� ������
	$query = "SELECT COUNT(*) FROM parser_site_list WHERE block = 0";
	$result = mysqlQuery($query);
	$row = mysql_fetch_row($result);
	$count_page = $row[0]/1000;  
  
  empty($_GET['page']) ? $page = 1 : $page = $_GET['page'];
  
  

  if (!empty($_GET['id_site']))
  {
    if ($_GET['id_site'] != 0) 
	{ 
		scan_site($_GET['id_site']); 
	}
	if ($_GET['id_site'] == "all") 
	{
		$query = "SELECT * FROM parser_site_list  WHERE ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_update)) > " . $SITE['settings']['period_update'] . "*60*60*24 ) AND block = 0 AND run = 0 ";
		$result = mysqlQuery($query);

		while ($site = mysql_fetch_assoc($result))
		{
			scan_site($site['id_site']);
			echo "</br>";
		}
	}
  }

  if (!empty($_GET['stop_site']))
  {
	$id_site = $_GET['stop_site'];
	$query = "UPDATE parser_site_list
				SET stop_scan = 1
				WHERE id_site = '$id_site'
				LIMIT 1";
	$result = mysqlQuery($query);
  }

  // �������� �� ���� ������
  $query = "SELECT * FROM parser_site_list  WHERE block = 0 ORDER BY last_update LIMIT " . ($page - 1) * 1000 . ", 1000"; //AND id_site >= $page * 1000 AND id_site <= $page * 1000 + 1000"; //LIMIT " . ($page - 1) * 1000 . ", 1000"; //
  $result = mysqlQuery($query);
  $i = 0;
  
  $period_update = 60*60*24*$SITE['settings']['period_update'];
  
  while ($res_site = mysql_fetch_assoc($result))
  {	$site_array[$i] = $res_site;  // ������ � ������� ������

	// ��������� ������������� ���������� ������
	$site_array[$i]['update_now'] = check_update_now($res_site['last_update'], $period_update);
	


	
	// ���������� ���������� ���������������� ������
	$id_site = $res_site['id_site'];

	// ���������� ���������� ����������
	$query_1 = "SELECT COUNT(*) FROM parser_link_list
				WHERE id_site = '$id_site' AND
							block = 0";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array[$i]['lost'] = $row[0];

	// ���������� ���������� ����������
	$query_1 = "SELECT COUNT(*) FROM parser_data
				WHERE id_site = '$id_site'";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array[$i]['database'] = $row[0];
	

	
	
	
	
	$i++;
  }


?>