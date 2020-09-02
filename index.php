<?php
/*******************************************************************************
* ГЛАВНЫЙ РОУТЕР САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.2
* @date 21.07.2012
*******************************************************************************/

 // Запускаем сессию
 // session_start();

  // Устанавливаем уровень ошибок
 // error_reporting(E_ALL);

  // Установка переменной доступа к файлам
  define('GVS_ACCSESS', true);

  // Подключаем конфигурационный файл
  include './config.php';



  // Получаем URL
  $GET['page'][0] = "main";
  $previous = 0; // предыдущий раздел 0 - корень
  if(!empty($_GET['route']))
  {
    $GET['page'] = explode('/', trim($_GET['route'], '/'));
    $previous = 1;

    // Установка переменной доступа к файлам
    define('GVS_URL', $_GET['route']);
  }
  else
  {    define('GVS_URL', "main");  }
////////////////////////////////////////////////////////////////////////////////

  // Получаем меню
  $query = "SELECT name, link FROM site_menu
            WHERE visible = 1
            ORDER BY priority";
  $result = mysqlQuery($query);

  while ($res_menu = mysql_fetch_assoc($result))
  {
    $SITE['menu'][] = $res_menu;
  }
   // Получаем настройки
  $query = "SELECT name, value FROM site_settings";
  $result = mysqlQuery($query);

  while ($res_settings = mysql_fetch_assoc($result))
  {
	$SITE['settings'][$res_settings['name']] = $res_settings['value'];
  }
  
  define ('PERIOD_UPDATE', $SITE['settings']['period_update']);
  define ('PrtScnDir', $SITE['settings']['PrtScnDir']);

////////////////////////////////////////////////////////////////////////////////




  // Получаем данные о разделе
  for ($i = 0; $i < count($GET['page']); $i++)
  {
    $query = "SELECT id,    previous, keywords,
                     title, module,  template, content
              FROM site_sections
              WHERE visible = 1 AND
                    previous = '" . $previous . "' AND
                    name     = '" . $GET['page'][$i] . "'
              LIMIT 1";

    $result = mysqlQuery($query);
    if ($res_sections = mysql_fetch_assoc($result))
    {
      $previous = $res_sections['id'];
    }
    else
    {
      header("HTTP/1.1 404 Not Found");
      exit(file_get_contents(GVS_ERROR_404));
    }
  }
////////////////////////////////////////////////////////////////////////////////
  // Подключаем счётчик системы посещаемости
 // require_once("./admin/count.php");
////////////////////////////////////////////////////////////////////////////////

  // Объединяем массивы
  $SITE = array_merge($SITE, $res_sections);
  
  

  
   // Подключаем основные функции
  include './functions/default.php';

  // Подключаем модуль и шаблон
  include GVS_ROOT . 'modules/'   . $SITE['module'];
  include GVS_ROOT . 'pageconstructor/' . $SITE['template'];
?>