<?php
/*******************************************************************************
* ДОБАВЛЕНИЕ МЕНЮ САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.2
* @date 29.07.2012
*******************************************************************************/

  // Выставляем уровень обработки ошибок
  Error_Reporting(E_ALL & ~E_NOTICE);

  // Установка переменной доступа к файлам
  define('GVS_ACCSESS', true);

  // Подключаем общий конфигурационный файл
  include str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/config.php";

  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");

  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
////////////////////////////////////////////////////////////////////////////////

  // Получаем меню
  $query = "SELECT * FROM site_menu
            ORDER BY priority";
  $result = mysqlQuery($query);

  while ($res_menu = mysql_fetch_assoc($result))
  {
    $SITE['menu'][] = $res_menu;
  }
////////////////////////////////////////////////////////////////////////////////

  if (empty($_POST)) {
    // Переменные формы
    if (empty($_REQUEST['name']))     $_REQUEST['name']     = "";
    if (empty($_REQUEST['link']))     $_REQUEST['link']     = "";
    if (empty($_REQUEST['visible']))  $_REQUEST['visible']  = 1;
    if (empty($_REQUEST['priority'])) $_REQUEST['priority'] = count($SITE['menu'])+1;
  }
////////////////////////////////////////////////////////////////////////////////

  // Создаем форму
  $name = new field_text("name",
                         "Название",
                         true,
                         $_REQUEST['name']);

  $link = new field_text("link",
                          "Ссылка",
                          true,
                         $_REQUEST['link']);

  $visible = new field_text_int("visible",
                            "Отобразить (1)/ скрыть (0) меню",
                             true,
                             $_REQUEST['visible']);

  $priority = new field_text_int("priority",
                             "Очередность",
                              true,
                              $_REQUEST['priority']);

  $form = new form(array ("name"     => $name,
                          "link"    =>  $link,
                          "visible"  => $visible,
                          "priority" => $priority),
                    "Добавить меню",
                    "field");
////////////////////////////////////////////////////////////////////////////////

  // Добавление меню
  if (!empty($_POST))
  {    // Проверяем корректность заполнения HTML-формы
    // и обрабатываем текстовые поля
    $error = $form->check();

    if (empty($error))
    {
      $query = "INSERT INTO site_menu (name, link, visible, priority)
                               VALUES ('" . $_POST['name'] . "',
                                       '" . $_POST['link'] . "',
                                       '" . $_POST['visible'] . "',
                                       '" . $_POST['priority'] . "')";
      $result = mysqlQuery($query);

      // Возвращаемся в список разделов
      header("Location: menu_site.php");
      exit();
    }
  }
////////////////////////////////////////////////////////////////////////////////

  // Данные переменные определяют название страницы и подсказку.
  $title = 'Добавление меню сайта';
  $pageinfo = '<p class=help>Здесь можно добавлить новый пункт меню.
                Название ссылки необходимо указывать без указания хостинга www.sitename.ru/.</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  // Выводим сообщения об ошибках если они имеются
  if(!empty($error))
  {
    foreach($error as $err)
    {
      echo "<span style=\"color:red\">$err</span><br>";
    }
  }

  // Выводим HTML-форму
  $form->print_form();

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>