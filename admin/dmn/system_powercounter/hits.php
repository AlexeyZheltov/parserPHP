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
 // Выставляем уровень обработки ошибок
  Error_Reporting(E_ALL & ~E_NOTICE);

  // Установка переменной доступа к файлам
  define('GVS_ACCSESS', true);

  // Подключаем общий конфигурационный файл
  include_once str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/config.php";

  // Подключаем конфигурационный файл системы посещаемости
  require_once("config.php");

  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");
  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");
  // Подключаем блок отображения текста в окне браузера
  require_once("../utils/utils.print_page.php");
  // Формирование WHERE-условий
  require_once("utils.where.php");
  // Выполнение запроса
  require_once("utils.query_result.php");

  $title='Хосты&nbsp;и&nbsp;хиты';
  $pageinfo='На этой странице вы видите общую статистику
  по посетителям сайта. <br><b>Хосты</b> – это количество
  уникальных посетителей Вашего сайта, <b>хиты</b> – это
  общее количество показов сайта.  <br>При переходе по
  ссылкам "<b>Сегодня</b>", "<b>Вчера</b>" отображается
  детальная почасовая статистика посещений за выбранный день.
  При переходе по ссылкам "<b>За 7 дней</b>" и "<b>За 30 дней
  </b>" отображается детальная суточная статистика за эти
  периоды времени.';

  try
  {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // Включаем массив временных интервалов
    require_once("time_interval.php");

    // Запрашиваем данные за пять временных интервалов
    // определённых в файле time_interval.php
    for($i = 0; $i < 5; $i++)
    {
      list($hits_total[$i],
           $hits[$i],
           $hosts_total[$i],
           $hosts[$i]) = show_ip_host($time[$i]['begin'],
                                      $time[$i]['end']);
    }
  ?>
  <table class="table"
       width="100%"
       border="0"
       cellpadding="0"
       cellspacing="0">
  <tr class="header" align="center">
    <td width=<?= 100/6 ?>% align=center>&nbsp;</td>
    <td width=<?= 100/6 ?>% align=center>Сегодня</td>
    <td width=<?= 100/6 ?>% align=center>Вчера</td>
    <td width=<?= 100/6 ?>% align=center>За 7 дней</td>
    <td width=<?= 100/6 ?>% align=center>За 30 дней</td>
    <td width=<?= 100/6 ?>% align=center>За всё время</td>
  </tr>
  <tr><td class=field>Засчитанные хосты</td>
    <?php
      foreach($hosts as $value)
        echo "<td align=center><p>$value</p></td>";
    ?>
  </tr>
  <tr><td class=field>Хосты</td>
    <?php
      foreach($hosts_total as $value)
        echo "<td align=center><p>$value</p></td>";
    ?>
  </tr>
  <tr><td class=field>Засчитанные хиты</td>
    <?php
      foreach($hits as $value)
        echo "<td align=center><p>$value</p></td>";
    ?>
  </tr>
  <tr><td class=field>Хиты</td>
    <?php
      foreach($hits_total as $value)
        echo "<td align=center><p>$value</p></td>";
    ?>
  </tr>
  </table>
  <?php
    // Включаем завершение страницы
    require_once("../utils/bottomcounter.php");
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

  // Функция возвращает массив из четырёх переменных (для интервала):
  // общее количества хитов,
  // количество засчитанных хитов,
  // количество хостов,
  // количество засчитанных хостов.
  // $begin - число дней, которое необходимо вычесть из текущей даты,
  // для того чтобы получить начальную точку временного интервала
  // $end - число дней, которое необходимо вычесть из текущей даты,
  // для того чтобы получить конечную точку воменного интервала
  function show_ip_host($begin = 1, $end = 0)
  {
    // Объявляем имена таблиц глобальными
    global $tbl_arch_hits, $tbl_arch_hits_month, $tbl_ip, $tbl_ip_unique;
    // Обнуляем хиты и хосты
    $hosts_total = 0;
    $hosts       = 0;
    $hits_total  = 0;
    $hits        = 0;

    //////////////////////////////////////////////////////////
    // Исходим из таблицы соответствия
    //            begin end
    // сегодня      1    0  - это извлекаем из $tbl_ip
    // вчера        2    1  - это извлекаем из $tbl_arch_hits
    // неделя       7    0  - это извлекаем из $tbl_arch_hits
    // месяц       30    0  - это извлекаем из $tbl_arch_hits
    // всё время    0    0  - это извлекаем из $tbl_arch_month
    //////////////////////////////////////////////////////////

    // Формируем WHERE-условие для временного интервала
    $where = where_interval($begin, $end);

	// Сегодня
    if($begin == 1 && $end == 0)
    {
      // Общее количество хитов
      $query_hit_total = "SELECT COUNT(*)
                          FROM $tbl_ip $where";
      // Засчитанные хиты
      $query_hit       = "SELECT COUNT(*) FROM $tbl_ip
                          $where AND systems!='none' AND
                                 systems NOT LIKE 'robot_%'";
      // Подсчитываем количество IP-адресов (хостов)
      if(IP_UNIQUE_USE > 0)
      {
        // Точный учет
        $query_host_total = "SELECT COUNT(*)
                             FROM $tbl_ip_unique $where";
      }
      else
      {
        // Приближенный учет
        $query_host_total = "SELECT COUNT(DISTINCT ip)
                             FROM $tbl_ip $where";
      }
      // Подсчитываем количество уникальных посетителей за сутки
      $query_host      = "SELECT COUNT(DISTINCT ip)
                          FROM $tbl_ip
                          $where AND systems!='none' AND
                                 systems NOT LIKE 'robot_%'";

      return array(query_result($query_hit_total),
                   query_result($query_hit),
                   query_result($query_host_total),
                   query_result($query_host));
    }

    // Всё время
    if($begin == 0 && $end == 0)
    {
      // Общее число хитов
      $query_hit_total = "SELECT SUM(hits_total) FROM $tbl_arch_hits";
      // Засчитанные хиты
      $query_hit       = "SELECT SUM(hits) FROM $tbl_arch_hits";
      // Подсчитываем число IP-адресов (хостов)
      if(IP_UNIQUE_USE > 0)
      {
        // Точный учет
        $query_host_total = "SELECT COUNT(*) FROM $tbl_ip_unique";
      }
      else
      {
        // Приближенный учет
        $query_host_total = "SELECT SUM(hosts_total) FROM $tbl_arch_hits";
      }
      // Подсчитываем число уникальных посетителей за сутки
      $query_host   = "SELECT SUM(host) FROM $tbl_arch_hits";

      // Если запросы выполнениы удачно,
      // получаем результат
      $hits_total  += query_result($query_hit_total);
      $hits        += query_result($query_hit);
      if(IP_UNIQUE_USE > 0)
      {
        // Точный учет
        $hosts_total = query_result($query_host_total);
      }
      else
      {
        // Приближенный учет
        $hosts_total += query_result($query_host_total);
      }
      $hosts       += query_result($query_host);

      // Получаем самое старое число из таблицы $tbl_arch_hits,
      // всё, что позже берём из таблицы $tbl_arch_hits_month
      $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) AS data FROM $tbl_arch_hits";
      $last_day = query_result($query);
      if($last_day)
      {
        $where = "WHERE putdate < '".date("Y-m-01", $last_date)."'";
        // Общее число хитов
        $query_hit_total = "SELECT SUM(hits_total)
                            FROM $tbl_arch_hits_month $where";
        // Засчитанные хиты
        $query_hit       = "SELECT SUM(hits)
                            FROM $tbl_arch_hits_month $where";
        if(IP_UNIQUE_USE == 0)
        {
          // Подсчитываем число IP-адресов (хостов)
          $query_host_total= "SELECT SUM(hosts_total)
                              FROM $tbl_arch_hits_month $where";
        }
        // Подсчитываем число уникальных посетителей за сутки
        $query_host   = "SELECT SUM(host)
                            FROM $tbl_arch_hits_month $where";

        // Если запросы выполнениы удачно,
        // получаем результат
        $hits_total  += query_result($query_hit_total);
        $hits        += query_result($query_hit);
        if(IP_UNIQUE_USE == 0)
        {
          $hosts_total += query_result($query_host_total);
        }
        $hosts       += query_result($query_host);
      }
    }
    // Общий случай
    else
    {
      // Общее число хитов
      $query_hit_total = "SELECT SUM(hits_total)
                          FROM $tbl_arch_hits $where";
      // Засчитанные хиты
      $query_hit       = "SELECT SUM(hits)
                          FROM $tbl_arch_hits $where";
      if(IP_UNIQUE_USE > 0)
      {
        // Точный учет
        if($begin == 2 && $end == 1)
        {
          // Вчера
          $query_host_total = "SELECT COUNT(*)
                               FROM $tbl_ip_unique  $where";
        }
        else if($begin == 7 && $end == 0)
        {
          // Неделя
          $query_host_total = "SELECT COUNT(*)
                               FROM $tbl_ip_unique WHERE putdate >= DATE_FORMAT(NOW(),'%Y-%m-%d') - INTERVAL '6' DAY";
        }
        else if($begin == 30 && $end == 0)
        {
          // Месяц
          $query_host_total = "SELECT COUNT(*)
                               FROM $tbl_ip_unique WHERE putdate >= DATE_FORMAT(NOW(),'%Y-%m-%d') - INTERVAL '29' DAY";
        }
      }
      else
      {
        // Подсчитываем число IP-адресов (хостов)
        $query_host_total = "SELECT SUM(hosts_total)
                            FROM $tbl_arch_hits $where";
      }
      // Подсчитываем число уникальных посетителей за сутки
      $query_host      = "SELECT SUM(host)
                          FROM $tbl_arch_hits $where";

      // Если запросы выполнениы удачно,
      // получаем результат
      $hits_total  += query_result($query_hit_total);
      $hits        += query_result($query_hit);
      if(IP_UNIQUE_USE > 0)
      {
        // Точный учет
        $hosts_total = query_result($query_host_total);
      }
      else
      {
        // Приближенный учет
        $hosts_total += query_result($query_host_total);
      }
      $hosts       += query_result($query_host);
    }
    // Возвращаем результат
    return array($hits_total, $hits, $hosts_total, $hosts);
  }
?>
