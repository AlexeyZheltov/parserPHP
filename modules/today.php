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
  
  empty($_GET['page']) ? $page = 0 : $page = $_GET['page'];

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
  $query = "SELECT * FROM parser_site_list  WHERE  ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_update)) < 1*60*60*24 ) AND block = 0 AND last_update > 0 LIMIT " . $page * 1000 . ", 1000"; //
  $result = mysqlQuery($query);
  $i = 0;
  while ($res_site = mysql_fetch_assoc($result))
  {	$site_array[$i] = $res_site;  // ������ � ������� ������

	// ��������� ������������� ���������� ������
	// ������� ���������� ������
	$period_update = 60*60*24*$SITE['settings']['period_update'];
	
	$site_array[$i]['update_now'] = check_update_now($res_site['last_update'], $period_update);

	// ���������� ���������� ���������������� ������
	$id_site = $res_site['id_site'];
	$query_1 = "SELECT COUNT(*) FROM parser_link_list
				WHERE id_site = '$id_site' AND
					  run = 1 AND
					  block = 0";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array[$i]['prosses'] = $row[0];

	// ���������� ���������� ����������
	$query_1 = "SELECT COUNT(*) FROM parser_link_list
				WHERE id_site = '$id_site' AND
							block = 0";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array[$i]['lost'] = $row[0] - $site_array[$i]['prosses'];

	// ���������� ���������� ��������������� ����������
	$query_1 = "SELECT COUNT(*) FROM parser_link_list
				WHERE del = 1 AND id_site = '$id_site'";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array[$i]['delete'] = $row[0];

	// ���������� ���������� ����������
	$query_1 = "SELECT COUNT(*) FROM parser_data
				WHERE id_site = '$id_site'";
	$result_1 = mysqlQuery($query_1);
	$row = mysql_fetch_row($result_1);
	$site_array[$i]['database'] = $row[0];
	$i++;
  }

	// �������� �� ���� ������
	$query = "SELECT COUNT(*) FROM parser_site_list WHERE  ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_update)) < 1*60*60*24 ) AND block = 0 AND last_update > 0";
	$result = mysqlQuery($query);
	$row = mysql_fetch_row($result);
	$count_page = $row[0]/1000;  
?>