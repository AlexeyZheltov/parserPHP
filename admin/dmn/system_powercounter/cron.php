<?php
/*******************************************************************************
* Система учета посещаемости сайта
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 28.07.2012
*******************************************************************************/
  ////////////////////////////////////////////////////////////
  // Система учёта посещаемости сайта - PowerCounter
  // 2003-2011 (C) IT-студия SoftTime (http://www.softtime.ru)
  // Поддержка: http://www.softtime.ru/forum/
  // Симдянов И.В. (simdyanov@softtime.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Левин А.В (loki_angel@mail.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // Пытаемся снять ограничение на время выполнения архивации
  @set_time_limit(0);
  // Выставляем уровень обработки ошибок
  // (http://www.softtime.ru/info/articlephp.php?id_article=23)
  error_reporting(E_ALL & ~E_NOTICE);

//echo  $_SERVER['DOCUMENT_ROOT'];
  // Абсолютный путь к папке system_powercounter
  $abspath = "/home/templ.ru/www/admin/dmn/system_powercounter/";

  // Адрес сервера
  $dblocation = "localhost";
  // Имя базы данных, на хостинге или локальной машине
  $dbname = "templ";
  $dbname = "templ";
  // Имя пользователя базы данных
  $dbuser = "root";
  // и его пароль
  $dbpasswd = "";

  // Устанавливаем соединение с базой данных
  $dbcnx = mysql_connect($dblocation,$dbuser,$dbpasswd);
  if (!$dbcnx)
  {
    exit("<P>В настоящий момент сервер базы данных не доступен,
          поэтому корректное отображение страницы невозможно.</P>");
  }
  // Выбираем базу данных
  if (! @mysql_select_db($dbname,$dbcnx) )
  {
    exit("<P>В настоящий момент база данных не доступна, поэтому
          корректное отображение страницы невозможно.</P>");
  }

  @mysql_query("SET NAMES 'cp1251'");

  if(!function_exists('get_magic_quotes_gpc'))
  {
    function get_magic_quotes_gpc()
    {
      return false;
    }
  }
  // Выставляем часовой пояс
  @date_default_timezone_set("Europe/Moscow");

  // Устанавливаем соединение с базой данных
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

  // Формирование WHERE-условий
  require_once("{$abspath}utils.where.php");
  // Выполнение запроса
  require_once("{$abspath}utils.query_result.php");
  // Функция для получения последнего заархивированного дня
  require_once("{$abspath}utils.begin_day_arch.php");

  // Библиотека функций архивации
  require_once("{$abspath}utils.hits.php");
  require_once("{$abspath}utils.ip.php");
  require_once("{$abspath}utils.client.php");
  require_once("{$abspath}utils.robots.php");
  require_once("{$abspath}utils.enterpoints.php");
  require_once("{$abspath}utils.deep.php");
  require_once("{$abspath}utils.time.php");
  require_once("{$abspath}utils.refferer.php");
  require_once("{$abspath}utils.num_search.php");
  require_once("{$abspath}utils.search.php");

  try
  {
    // Последний полный день
    $last_day = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
    // Начало архивации данных
    $begin_day = begin_day_arch($tbl_ip, $tbl_arch_clients) ;
    // Количество дней, подлежащих архивации
    $days = ceil(($last_day - $begin_day)/24/60/60);
    // Блок архивации
    if($days)
    {
      ///////////////////////////////////////////////////
      // Архивируем информацию в ежедневные таблицы
      ///////////////////////////////////////////////////
      archive_client          ($tbl_ip, $tbl_arch_clients);
      archive_hit_hosts       ($tbl_ip, $tbl_arch_hits);
      archive_robots          ($tbl_ip, $tbl_arch_robots);
      archive_num_searchquery ($tbl_searchquerys, $tbl_arch_num_searchquery);
      archive_searchquery     ($tbl_searchquerys, $tbl_arch_searchquery);
      archive_refferer        ($tbl_refferer, $tbl_arch_refferer);
      archive_ip              ($tbl_ip, $tbl_arch_ip);
      archive_enterpoints     ($tbl_ip, $tbl_pages, $tbl_arch_enterpoint);
      archive_time            ($tbl_ip, $tbl_arch_time, $tbl_arch_time_temp);
      archive_deep            ($tbl_ip, $tbl_arch_deep);

      ///////////////////////////////////////////////////
      // Удаляем старые записи
      ///////////////////////////////////////////////////
      $query = "SELECT MAX(putdate) FROM $tbl_arch_hits";
      $arh = mysql_query($query);
      if(!$arh) exit("Сбой при удалении старых записей");
      if(mysql_num_rows($arh) > 0)
      {
        $last_date_arch = mysql_result($arh,0);
        $arr[] = "DELETE FROM $tbl_ip WHERE putdate <= '$last_date_arch'";
        $arr[] = "DELETE FROM $tbl_refferer WHERE putdate <= '$last_date_arch'";
        $arr[] = "DELETE FROM $tbl_searchquerys WHERE putdate <= '$last_date_arch'";
        foreach($arr as $query)
        {
          if(!mysql_query($query))
          {
            throw new ExceptionMySQL(mysql_error(),
                                     $query,
                                    "Ошибка выполнения запроса");
          }
        }
      }

      ///////////////////////////////////////////////////
      // Архивируем информацию в еженедельные таблицы
      ///////////////////////////////////////////////////
      archive_client_week          ($tbl_arch_clients, $tbl_arch_clients_week);
      archive_hit_hosts_week       ($tbl_arch_hits, $tbl_arch_hits_week);
      archive_robots_week          ($tbl_arch_robots, $tbl_arch_robots_week);
      archive_num_searchquery_week ($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_week);
      archive_refferer_week        ($tbl_arch_refferer, $tbl_arch_refferer_week);
      archive_ip_week              ($tbl_arch_ip, $tbl_arch_ip_week);
      archive_searchquery_week     ($tbl_arch_searchquery, $tbl_arch_searchquery_week);
      archive_enterpoints_week     ($tbl_arch_enterpoint, $tbl_arch_enterpoint_week);
      archive_time_week            ($tbl_arch_time, $tbl_arch_time_week);
      archive_deep_week            ($tbl_arch_deep, $tbl_arch_deep_week);

      ///////////////////////////////////////////////////
      // Архивируем информацию в ежемесячные таблицы
      ///////////////////////////////////////////////////
      archive_clients_month         ($tbl_arch_clients, $tbl_arch_clients_month);
      archive_hit_hosts_month       ($tbl_arch_hits, $tbl_arch_hits_month);
      archive_robots_month          ($tbl_arch_robots, $tbl_arch_robots_month);
      archive_num_searchquery_month ($tbl_arch_num_searchquery, $tbl_arch_num_searchquery_month);
      archive_refferer_month        ($tbl_arch_refferer, $tbl_arch_refferer_month);
      archive_ip_month              ($tbl_arch_ip, $tbl_arch_ip_month);
      archive_searchquery_month     ($tbl_arch_searchquery, $tbl_arch_searchquery_month);
      archive_enterpoints_month     ($tbl_arch_enterpoint, $tbl_arch_enterpoint_month);
      archive_time_month            ($tbl_arch_time, $tbl_arch_time_month);
      archive_deep_month            ($tbl_arch_deep, $tbl_arch_deep_month);
    }
  }
  catch(ExceptionObject $exc)
  {
    require("../utils/exception_object.php");
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php");
  }
  catch(ExceptionMember $exc)
  {
    require("../utils/exception_member.php");
  }
?>