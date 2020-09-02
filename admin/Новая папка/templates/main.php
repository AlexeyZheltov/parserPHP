<?php
/*******************************************************************************
* СБОРЩИК СТРАНИЦЫ САЙТА
* @author Zheltiy
* @copyright ©
* @version 1.0
* @date 08.07.2012
*******************************************************************************/

  // Модуль безопасности
  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/mod_security.php");


  // Подключаем заголовок
  include './content/tpl/head.tpl';
  // Подключаем меню
  include './content/tpl/menu.tpl';
  // Подключаем контент
  include './content/tpl/' . $SITE['content'];
  // подвал
  include './content/tpl/footer.tpl';
?>
