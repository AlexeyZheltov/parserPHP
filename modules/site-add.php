<?php
  if (!empty($_POST['add']) and !empty($_POST['link'])) {    $link = $_POST['link'];
    $query = "INSERT INTO parser_site_list (link) VALUE ('$link')";
    $result = mysqlQuery($query);
	
	$query = "SELECT MAX(id_site) FROM parser_site_list";
	$result = mysqlQuery($query);
	$row = mysql_fetch_row($result);
	
	header("Location: site-edit?id_site=" . $row[0]);
    
  }
?>