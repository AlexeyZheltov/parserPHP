<?php
/*******************************************************************************
* СБОРЩИК СТРАНИЦЫ САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0
* @date 08.07.2012
*******************************************************************************/

  // Модуль безопасности
  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/mod_security.php");

  // Подключаем заголовок
  include './content/tamplates/tpl/main/head.tpl';
  // Подключаем меню
  include './content/tamplates/tpl/main/menu.tpl';
  // Подключаем контент
  include './content/pages/' . $SITE['content'];
  // подвал
  include './content/tamplates/tpl/main/footer.tpl';
?>
