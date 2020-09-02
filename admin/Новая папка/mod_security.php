<?php
/*******************************************************************************
* МОДУЛЬ БЕЗОПАСНОСТИ
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 21.07.2012
*******************************************************************************/

  // Генерация страницы ошибки при доступе вне системы
  if(!defined('GVS_ACCSESS'))
  {
     header("HTTP/1.1 404 Not Found");
     exit(file_get_contents(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . '/content/tpl/404.html'));
  }

  // Устанавливаем соединение с базой данных
  include '../config.php';

  // Подключаем модуль обработки запросов БД
  include '../libs/mysql.php';

  // Если пользователь не авторизовался - авторизуемся
  if(!isset($_SERVER['PHP_AUTH_USER']))
  {
    Header("WWW-Authenticate: Basic realm=\"Admin Page\"");
    Header("HTTP/1.0 401 Unauthorized");
    exit();
  }
  else
  {
    // Утюжим переменные $_SERVER['PHP_AUTH_USER'] и $_SERVER['PHP_AUTH_PW'],
    // чтобы мышь не проскочила
    $_SERVER['PHP_AUTH_USER'] = mysql_escape_string($_SERVER['PHP_AUTH_USER']);
    $_SERVER['PHP_AUTH_PW']   = mysql_escape_string($_SERVER['PHP_AUTH_PW']);

    $query = "SELECT pass
              FROM site_users
              WHERE name = '" . $_SERVER['PHP_AUTH_USER'] . "'";
    $lst = @mysql_query($query);
    // Если ошибка в SQL-запросе - выдаём окно
    if(!$lst)
    {
      Header("WWW-Authenticate: Basic realm=\"Admin Page\"");
      Header("HTTP/1.0 401 Unauthorized");
      exit();
    }
    // Если такого пользователя нет - выдаём окно
    if(mysql_num_rows($lst) == 0)
    {
      Header("WWW-Authenticate: Basic realm=\"Admin Page\"");
      Header("HTTP/1.0 401 Unauthorized");
      exit();
    }
    // Если все проверки пройдены, сравниваем хэши паролей
    $pass = @mysql_fetch_array($lst);
    if(md5($_SERVER['PHP_AUTH_PW']) != $pass['pass'])
    {
      Header("WWW-Authenticate: Basic realm=\"Admin Page\"");
      Header("HTTP/1.0 401 Unauthorized");
      exit();
    }
  }
?>