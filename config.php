<?php
/******************************************************************************
* ГЛАВНЫЙ КОНФИГАРУЦИОННЫЙ ФАЙЛ САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0
* @date 26.07.2011
*******************************************************************************/
  // Модуль безопасности (открытие все системы)
  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/libs/mod_security.php");

////////////////////////////////////////////////////////////////////////////////
//                        НАСТРОЙКИ СОЕДИНЕНИЯ С БД                           //
////////////////////////////////////////////////////////////////////////////////
   // Сервер БД
   define('GVS_DB_SERVER', 'localhost');
   // Пользователь БД
   define('GVS_DB_USER', 'root');
   // Пароль БД
   define('GVS_DB_PASSWORD', '');
   // Название базы
   define('GVS_DB_DATABASE', 'parser');
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////////////////
//                             ПОДКЛЮЧЕНИЕ К БД                               //
////////////////////////////////////////////////////////////////////////////////
  $db_connect = mysql_connect(GVS_DB_SERVER, GVS_DB_USER, GVS_DB_PASSWORD ) or die(GVS_NO_CONNECT);
  define('GVS_CONNECT', $db_connect);
  mysql_select_db(GVS_DB_DATABASE, GVS_CONNECT )or die(GVS_NO_DB_SELECT);
  mysql_set_charset( 'utf8' );
//  mysql_query ("SET NAMES 'CP1251'");
 // mysql_query ("SET CHARACTER SET 'CP1251'");
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

  // Устанавливает путь до корневой директории скрипта
  define('GVS_ROOT', str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) .'/');

  // Если сайт в разработке устанавливаем переменную
  define('GVS_RELIASE', true);

  // Максимальное количество запросов в секунду
  define('GVS_FAST_UPDATE', 5);

  // Устанавливает путь до корневой директории скрипта
  define('GVS_HOST', 'http://'. $_SERVER['HTTP_HOST'] .'/');

  // Устанавливаем путь до файла-ошибки
  define('GVS_ERROR', GVS_ROOT . 'content/pages/error.tpl');

  // Устанавливаем путь до файла-ошибки 404
  define('GVS_ERROR_404', GVS_ROOT . 'content/pages/404.html');

  // Устанавливаем путь до файла-ошибки 404
  define('GVS_ERROR_403', GVS_ROOT . 'content/pages/403.html');

  // Дебаггер
  define('GVS_TRACE', true);
  include GVS_ROOT . 'libs/debug.php';

  // Подключаем модуль обработки запросов БД
  include GVS_ROOT . 'libs/mysql.php';


?>