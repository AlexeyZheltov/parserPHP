<?php
/******************************************************************************
* Конфигурационный файл системы учета посещаемости
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 28.07.2011
*******************************************************************************/
  ////////////////////////////////////////////////////////////
  // Система учёта посещаемости сайта - PowerCounter
  // 2003-2011 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Левин А.В. (loki_angel@mail.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////

  // Выставляем уровень обработки ошибок)
  error_reporting(E_ALL & ~E_NOTICE);

  // Модуль безопасности (открытие все системы)
  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/mod_security.php");

  // Количество позиций на одной странице
  $pnumber = 20;

  $tbl_ip                        = 'powercounter_ip';
  $tbl_pages                     = 'powercounter_pages';
  $tbl_links                     = 'powercounter_links';
  $tbl_thits                     = 'powercounter_thits';
  $tbl_refferer                  = 'powercounter_refferer';
  $tbl_searchquerys              = 'powercounter_searchquerys';
  $tbl_ip_unique                 = 'powercounter_ip_unique';

  $tbl_cities                    = 'powercounter_cities';
  $tbl_ip_compact                = 'powercounter_ip_compact';
  $tbl_regions                   = 'powercounter_regions';

  $tbl_arch_hits                 = 'powercounter_arch_hits';
  $tbl_arch_ip                   = 'powercounter_arch_ip';
  $tbl_arch_clients              = 'powercounter_arch_clients';
  $tbl_arch_robots               = 'powercounter_arch_robots';
  $tbl_arch_refferer             = 'powercounter_arch_refferer';
  $tbl_arch_searchquery          = 'powercounter_arch_searchquery';
  $tbl_arch_num_searchquery      = 'powercounter_arch_num_searchquery';
  $tbl_arch_enterpoint           = 'powercounter_arch_enterpoint';
  $tbl_arch_deep                 = 'powercounter_arch_deep';
  $tbl_arch_time                 = 'powercounter_arch_time';
  $tbl_arch_time_temp            = 'powercounter_arch_time_temp';

  $tbl_arch_hits_week            = 'powercounter_arch_hits_week';
  $tbl_arch_robots_week          = 'powercounter_arch_robots_week';
  $tbl_arch_ip_week              = 'powercounter_arch_ip_week';
  $tbl_arch_clients_week         = 'powercounter_arch_clients_week';
  $tbl_arch_refferer_week        = 'powercounter_arch_refferer_week';
  $tbl_arch_searchquery_week     = 'powercounter_arch_searchquery_week';
  $tbl_arch_num_searchquery_week = 'powercounter_arch_num_searchquery_week';
  $tbl_arch_enterpoint_week      = 'powercounter_arch_enterpoint_week';
  $tbl_arch_deep_week            = 'powercounter_arch_deep_week';
  $tbl_arch_time_week            = 'powercounter_arch_time_week';

  $tbl_arch_hits_month           = 'powercounter_arch_hits_month';
  $tbl_arch_robots_month         = 'powercounter_arch_robots_month';
  $tbl_arch_ip_month             = 'powercounter_arch_ip_month';
  $tbl_arch_clients_month        = 'powercounter_arch_clients_month';
  $tbl_arch_refferer_month       = 'powercounter_arch_refferer_month';
  $tbl_arch_searchquery_month    = 'powercounter_arch_searchquery_month';
  $tbl_arch_num_searchquery_month= 'powercounter_arch_num_searchquery_month';
  $tbl_arch_enterpoint_month     = 'powercounter_arch_enterpoint_month';
  $tbl_arch_deep_month           = 'powercounter_arch_deep_month';
  $tbl_arch_time_month           = 'powercounter_arch_time_month';

  // Число самых активных IP-адресов, которые архивируются
  // в суточные, недельные и месячные таблицы
  define("IP_NUMBER", 20);
  // Число самых активных точек входа, которые архивируются
  // в суточные, недельные и месячные таблицы
  define("ENTERPOINT_NUMBER", 20);
  // Число самых распространённых рефереров, которые архивируются
  // в суточные, недельные и месячные таблицы
  define("REFFERER_NUMBER", 20);
  // Число самых распространённых запросов с Yandex, которые архивируются
  // в суточные, недельные и месячные таблицы
  define("YANDEX_NUMBER", 20);
  // Число самых распространённых запросов с Rambler, которые
  // архивируются в суточные, недельные и месячные таблицы
  define("RAMBLER_NUMBER", 20);
  // Число самых распространённых запросов с Google, которые архивируются
  // в суточные, недельные и месячные таблицы
  define("GOOGLE_NUMBER", 20);
  // Число самых распространённых запросов с Google, которые архивируются
  // в суточные, недельные и месячные таблицы
  define("APORT_NUMBER", 20);
  // Число самых распространённых запросов с MSN, которые архивируются
  // в суточные, недельные и месячные таблицы
  define("MSN_NUMBER", 20);
  // Если константа принимает значение 0 не производится попытка получить
  // хост для IP-адреса, если константа принимает значение 1 - адрес
  // преобразуется. Значение 0 применяется для ускорения обработки отчёта
  // IP-адреса, когда  канал сервера не позволяет расшифровать доменные
  // имена IP-адресов достаточно
  // быстро
  define("HOST_BY_ADDR", 0);
  // E-mail на который отправляется почтовый отчёт
  define("EMAIL_ADDRESS", "someone@somewhere.ru");
  // Использовать более точный учет IP-адресов?
  define("IP_UNIQUE_USE", 0);
?>