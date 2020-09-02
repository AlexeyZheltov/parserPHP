<?php
/*******************************************************************************
* ИЗМЕНЕНИЕ РАЗДЕЛА САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
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

  // Данные текущего раздела
  // По умолчанию главный раздел
  if (is_null($_GET['id'])) { $_GET['id'] = 1; }
  $query = "SELECT * FROM site_sections
            WHERE id = '" . $_GET['id'] . "'
            LIMIT 1";
  $result = mysqlQuery($query);

  while ($res_sections = mysql_fetch_assoc($result))
  {
    $SITE['section'] = $res_sections;
  }
////////////////////////////////////////////////////////////////////////////////

  // Дерево разделов
  $i = 0;
  $previous = $_GET['id'];
  while ($previous != 0) {
    $query = "SELECT name, previous FROM site_sections
          WHERE id = '$previous'
          LIMIT 1";
    $result = mysqlQuery($query);

    if ($res_sections = mysql_fetch_assoc($result))
    {
      $sections[$i] = $res_sections;
      $previous = $res_sections['previous'];
      $i++;
    }
    else
    {
      $previous = 0;
    }
  }

  if (!empty($sections)) {
  $sections = array_reverse($sections);
  $sections[0]['name'] = substr(GVS_HOST, 0, -1);
  $str = "";
    foreach ($sections as $section)
    {
      $str .= $section['name'] . "/";
    }
  }
////////////////////////////////////////////////////////////////////////////////

  if (empty($_POST)) {
    // Переменные формы
    if (empty($_REQUEST['name']))     $_REQUEST['name']     = $SITE['section']['name'];
    if (empty($_REQUEST['title']))    $_REQUEST['title']    = $SITE['section']['title'];
    if (empty($_REQUEST['visible']))  $_REQUEST['visible']  = $SITE['section']['visible'];
    if (empty($_REQUEST['keywords'])) $_REQUEST['keywords'] = $SITE['section']['keywords'];
    if (empty($_REQUEST['template'])) $_REQUEST['template'] = $SITE['section']['template'];
    if (empty($_REQUEST['content']))  $_REQUEST['content']  = $SITE['section']['content'];
    if (empty($_REQUEST['module']))   $_REQUEST['module']   = $SITE['section']['module'];
  }
////////////////////////////////////////////////////////////////////////////////

  // Создаем форму
  $name = new field_text("name",
                         "Название страницы",
                         true,
                         $_REQUEST['name']);

  $title = new field_text("title",
                          "Заголовок страницы",
                          true,
                          $_REQUEST['title']);

  $visible = new field_text_int("visible",
                            "Отобразить (1)/ скрыть (0) меню",
                             true,
                             $_REQUEST['visible']);

  $keywords = new field_text("keywords",
                             "keywords",
                              true,
                              $_REQUEST['keywords']);

  $template = new field_text("template",
                             "Шаблон",
                             true,
                             $_REQUEST['template']);

  $content = new field_text("content",
                            "Контент",
                            true,
                            $_REQUEST['content']);

  $module = new field_text("module",
                           "Програмный модуль",
                           true,
                           $_REQUEST['module']);

  $form = new form(array ("name"     => $name,
                          "title"    => $title,
                          "visible"  => $visible,
                          "keywords" => $keywords,
                          "template" => $template,
                          "content"  => $content,
                          "module"   => $module),
                    "Сохранить",
                    "field");
////////////////////////////////////////////////////////////////////////////////

  // Сохранение изменений
  if (!empty($_POST))
  {
    // Проверяем корректность заполнения HTML-формы
    // и обрабатываем текстовые поля
    $error = $form->check();

    if ($form->fields['visible']->value != 0 and $form->fields['visible']->value != 1)
    {      $error[] = 'Поле "' . $form->fields['visible']->name . '" может принимать значения 0 или 1';    }

    if (empty($error)) {
      $query = "UPDATE site_sections
                SET name     = '" . $_POST['name'] . "',
                    visible  = '" . $_POST['visible'] . "',
                    keywords = '" . $_POST['keywords'] . "',
                    title    = '" . $_POST['title'] . "',
                    template = '" . $_POST['template'] . "',
                    content  = '" . $_POST['content'] . "',
                    module   = '" . $_POST['module'] . "'
                WHERE id = '" . $_GET['id'] . "'";
      $result = mysqlQuery($query);
      $page_msg = "Данные успешно обновлены";

      // Возвращаемся в список разделов
      header("Location: sections.php?current=" . $SITE['section']['previous']);
      exit();
    }
  }
////////////////////////////////////////////////////////////////////////////////

  // Данные переменные определяют название страницы и подсказку.
  $title = 'Изменение раздела сайта';
  $pageinfo = '<p class=help>Актуализируйте данные раздела.</p>';


  // Включаем заголовок страницы
  require_once("../utils/top.php");

  echo '<a class=title>Раздел <a class=title href="' . $str . '">' . $str . '</a></a><p>&nbsp;</p>';
///////////////////////////////////////////////////////////////////////////////

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

  if (!empty($page_msg))
  {    echo "<span style=\"color:green\">$page_msg</span><br>";  }
//////////////////////////////////////////////////////////////////////////

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>