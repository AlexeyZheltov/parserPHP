<?php
/*******************************************************************************
* Меню системы администрирования сайта
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 25.07.2012
*******************************************************************************/
  // Выставляем уровень обработки ошибок
  error_reporting(E_ALL & ~E_NOTICE);

  // Текущий раздел
  $name = basename($_SERVER['PHP_SELF'])
?>
  <div <?php if('index.php'               == $name) echo 'class=active'; ?>><a class=menu href=index.php>Системные аккаунты</a></div>
  <div <?php if('menu_site.php'           == $name) echo 'class=active'; ?>><a class=menu href=menu_site.php>Меню сайта</a></div>
  <div <?php if('sections.php'            == $name) echo 'class=active'; ?>><a class=menu href=sections.php>Разделы сайта</a></div>

  <div><a class=menu href="../system_powercounter">Система учета посещяемости сайта</a></div>

