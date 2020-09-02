<?php
/******************************************************************************
* Конфигурационный файл системы администрирования
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 26.07.2011
*******************************************************************************/
  ////////////////////////////////////////////////////////////
  // 2003-2011 (C) IT-студия SoftTime (http://www.softtime.ru)
  ////////////////////////////////////////////////////////////
  // Если константа DEBUG определена, работает отладочный
  // вариант, в частности выводится подробные сообщения об
  // исключительных ситуациях, связанных с MySQL и ООП
  define("DEBUG", 1);
  // сейчас выставлен сервер локальной машины
  $dblocation = "localhost";
  // Имя базы данных, на хостинге или локальной машине
  //$dbname = "powercounter";
  $dbname = "parser";
  // Имя пользователя базы данных
  $dbuser = "root";
  // и его пароль
  $dbpasswd = "";

  // Аккаунты
  $tbl_accounts         = 'system_accounts';
  // Новости
  $tbl_news             = 'system_news';
  // Ответы и вопросы
  $tbl_faq              = 'system_faq';
  // CMS
  $tbl_catalog          = 'system_menu_catalog';
  $tbl_position         = 'system_menu_position';
  $tbl_paragraph        = 'system_menu_paragraph';
  $tbl_paragraph_image  = 'system_menu_paragraph_image';
  // Каталог
  $tbl_cat_catalog      = 'system_catalog';
  $tbl_cat_position     = 'system_position';
  // Блок контакты
  $tbl_contactaddress   = 'system_contactaddress';
  // Блок голосования
  $tbl_poll             = 'system_poll';
  $tbl_poll_answer      = 'system_poll_answer';
  $tbl_poll_session     = 'system_poll_session';
  // Гостевая книга
  $tbl_guestbook        = 'system_guestbook';
  // Пользователи сайта
  $tbl_users            = 'system_users';
  // Фотогалерея
  $tbl_photo_catalog    = 'system_photo_catalog';
  $tbl_photo_position   = 'system_photo_position';
  $tbl_photo_settings   = 'system_photo_settings';
 /*
  // Устанавливаем соединение с базой данных
  $dbcnx = mysql_connect($dblocation,$dbuser,$dbpasswd);
  mysql_set_charset( 'utf8' );
  if(!$dbcnx)
    exit("<P>В настоящий момент сервер базы данных не
          доступен, поэтому корректное отображение
          страницы невозможно.</P>" );
  // Выбираем базу данных
  if(! @mysql_select_db($dbname,$dbcnx))
    exit("<P>В настоящий момент база данных не доступна,
          поэтому корректное отображение страницы
          невозможно.</P>" );
 */
  //@mysql_query("SET NAMES 'cp1251'");

  if(!function_exists('get_magic_quotes_gpc'))
  {
    function get_magic_quotes_gpc()
    {
      return false;
    }
  }
/*
  // Устанавливает путь до корневой директории скрипта
  define('GVS_ROOT', str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) .'/');
  define('GVS_CONNECT', $dbcnx);
  // Если сайт в разработке устанавливаем переменную
  define('GVS_RELIASE', true);

  // Функция для запроса к БД
  function mysqlQuery($sql, $print = false) {
    $result = mysql_query($sql, GVS_CONNECT);
    if($result === false || $print) {
      $error = mysql_error();
      $trace = debug_backtrace();

      $head = $error ?'<b style="color:red">MySQL error: </b><br>
      <b style="color:green">'. $error .'</b><br><br>':NULL;

      $error_log = date("Y-m-d h:i:s") .' '. $head .'
      <b>Query: </b><br>
      <pre><span style="color:#CC0000">'     . $trace[0]['args'][0] .'</pre></span><br><br>
      <b>File: </b><b style="color:#660099">'. $trace[0]['file']    .'</b><br>
      <b>Line: </b><b style="color:#660099">'. $trace[0]['line']    .'</b>';

      // Если идет тестирование сайта, то выводим ошибку на экран
      if(defined('GVS_RELIASE')){ die($error_log); }

      // Иначе записываем в файл
      file_put_contents(GVS_ROOT .'log/mysql.log', strip_tags($error_log) ."\n", FILE_APPEND);
      header("HTTP/1.1 404 Not Found");
      die(file_get_contents(GVS_ROOT .'/404.html'));
    }
    else {
      return $result;
    }
  }   */
?>