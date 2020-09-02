<?php
/*******************************************************************************
* ИЗМЕНЕНИЕ МЕНЮ САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 22.07.2012
*******************************************************************************/

  // Установка переменной доступа к файлам
  define('GVS_ACCSESS', true);

  // Модуль безопасности
  require_once("mod_security.php");

  // Добавление меню
  if (!empty($_POST['add']))
  {
    $query = "INSERT INTO site_menu (name, link, visible, priority)
                             VALUES ('" . $_POST['name_add'] . "',
                                     '" . $_POST['link_add'] . "',
                                     '" . $_POST['visible_add'] . "',
                                     '" . $_POST['priority_add'] . "')";
    $result = mysqlQuery($query);
  }

  // Удаление меню
  if (!empty($_GET['del']))
  {
    $query = "DELETE FROM site_menu
              WHERE id = '" . $_GET['del'] . "'";
    $result = mysqlQuery($query);
  }

  // Сохранение изменений
  if (!empty($_POST['save']))
  {
    // Получаем меню
    $query = "SELECT * FROM site_menu
              ORDER BY priority";
    $result = mysqlQuery($query);

    while ($res_menu = mysql_fetch_assoc($result))
    {
      $SITE['menu'][] = $res_menu;
    }

    $i = 0;

    foreach ($SITE['menu'] as $stroka)
    {
      $i++;
      $query = "UPDATE site_menu
                SET name     = '" . $_POST['cell_' . $i . '_2'] . "',
                    link     = '" . $_POST['cell_' . $i . '_3'] . "',
                    visible  = '" . $_POST['cell_' . $i . '_4'] . "',
                    priority = '" . $_POST['cell_' . $i . '_5'] . "'
                WHERE id = '" . $stroka['id'] . "'";
      $result = mysqlQuery($query);
    }

    unset ($SITE['menu']);
  }





  // Получаем меню
  $query = "SELECT * FROM site_menu
            ORDER BY priority";
  $result = mysqlQuery($query);

  while ($res_menu = mysql_fetch_assoc($result))
  {
    $SITE['menu'][] = $res_menu;
  }






  // Подключаем шаблон
  $SITE['content'] = 'menu/main.tpl';
  include './templates/main.php';

?>
