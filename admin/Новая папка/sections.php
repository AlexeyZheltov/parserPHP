<?php
/*******************************************************************************
* ИЗМЕНЕНИЕ РАЗДЕЛОВ САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 22.07.2012
*******************************************************************************/

  // Установка переменной доступа к файлам
  define('GVS_ACCSESS', true);

  // Модуль безопасности
  require_once("mod_security.php");

  // По умолчанию главный раздел
  if (is_null($_GET['current'])) { $_GET['current'] = 1; }

  // Сохранение изменений
  if (!empty($_POST['save']))
  {
    // Получаем список разделов
    $query = "SELECT * FROM site_sections
              WHERE previous = '" . $_GET['current'] . "'";

    $result = mysqlQuery($query);

    while ($res_sections = mysql_fetch_assoc($result))
    {
      $SITE['current'][] = $res_sections;
    }

    $i = 0;

    foreach ($SITE['current'] as $stroka)
    {
      $query = "UPDATE site_sections
                SET name     = '" . $_POST['name_'     . $stroka['id']] . "',
                    visible  = '" . $_POST['visible_'  . $stroka['id']] . "',
                    keywords = '" . $_POST['keywords_' . $stroka['id']] . "',
                    title    = '" . $_POST['title_'    . $stroka['id']] . "',
                    template = '" . $_POST['template_' . $stroka['id']] . "',
                    content  = '" . $_POST['content_'  . $stroka['id']] . "',
                    module   = '" . $_POST['module_'   . $stroka['id']] . "'
                WHERE id = '" . $stroka['id'] . "'";
      $result = mysqlQuery($query);
    }

    unset ($SITE['current']);
  }
////////////////////////////////////////////////////////////////////////////////

   // Удаление раздела
  if (!empty($_GET['del']))
  {
    $query = "DELETE FROM site_sections
              WHERE id       = '" . $_GET['del'] . "'
               OR  previous = '" . $_GET['del'] . "'";
    $result = mysqlQuery($query);
  }
////////////////////////////////////////////////////////////////////////////////

  // Добавить раздел
  if (!empty($_POST['add']))
  {
    $query = "INSERT INTO site_sections (name, visible, keywords, template, content, module, previous)
                             VALUES ('" . $_POST['name_add'] . "',
                                     '" . $_POST['visible_add'] . "',
                                     '" . $_POST['keywords_add'] . "',
                                     '" . $_POST['template_add'] . "',
                                     '" . $_POST['content_add'] . "',
                                     '" . $_POST['module_add'] . "',
                                     '" . $_GET['current'] . "')";
    $result = mysqlQuery($query);
  }



////////////////////////////////////////////////////////////////////////////////
  // Получаем разделы в текущем

  $query = "SELECT * FROM site_sections
            WHERE previous = '" . $_GET['current'] . "'";
  $result = mysqlQuery($query);

  while ($res_sections = mysql_fetch_assoc($result))
  {

    $SITE['current'][] = $res_sections;
  }
////////////////////////////////////////////////////////////////////////////////

  // Данные текущего раздела
  $current = $_GET['current'];
  $query = "SELECT * FROM site_sections
            WHERE id = '$current'
            LIMIT 1";
  $result = mysqlQuery($query);

  while ($res_sections = mysql_fetch_assoc($result))
  {
    $SITE['previous'] = $res_sections;
  }
////////////////////////////////////////////////////////////////////////////////

  // Дерево разделов
  $i = 0;
  $previous = $current;
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

  // Подключаем шаблон
  $SITE['content'] = 'sections/main.tpl';
  include './templates/main.php';

?>
