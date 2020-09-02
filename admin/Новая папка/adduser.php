<?php
  ///////////////////////////////////////////////////
  // Система авторизации HTTP-Basic авторизации
  // 2003-2005 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Симдянов И.В. (simdyanov@softtime.ru)
  ///////////////////////////////////////////////////
  // Устанавливаем соединение с базой данных
  require_once("config.php");
  // Модуль безопасности
  require_once("security_mod.php");

  // Проверяем переданы ли имя пользователя и пароль
  if(empty($_POST['name'])) exit("Не указано имя нового пользователя");
  if(empty($_POST['pass'])) exit("Не указан пароль пользователя");

  // Проверяем равны ли пароли
  if($_POST['pass'] != $_POST['pass_again']) exit("Пароли не равны");

  // Проверяем, чтобы не было никаких SQL-инъекций
  if (!get_magic_quotes_gpc())
  {
     $_POST['name'] = mysql_escape_string($_POST['name']);
     $_POST['pass'] = mysql_escape_string($_POST['pass']);
  }
  $_POST['name'] = str_replace("'","`",$_POST['name']);
  $_POST['pass'] = str_replace("'","`",$_POST['pass']);

  // Если всё нормально создаём нового пользователя 
  $query = "INSERT INTO userlist VALUES (NULL,'$_POST[name]','".md5($_POST['pass'])."')";
  if(mysql_query($query))
  {
   echo "Новый пользователь создан";
    echo "<HTML><HEAD>
            <META HTTP-EQUIV='Refresh' CONTENT='0; URL=index.php'>
            </HEAD></HTML>";
  }
  else
  {
    exit("Ошибка при добавлении нового пользователя");
  }
?>