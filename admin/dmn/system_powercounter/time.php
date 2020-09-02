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
  // 2003-2008 (C) IT-студия SoftTime (http://www.softtime.ru)
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

  if (!isset($_GET['begin'])) $begin = 1;
  else $begin = intval($_GET['begin']);
  if (!isset($_GET['end'])) $end = 0;
  else $end = intval($_GET['end']);

  // Заголовок страницы
  $title = 'Время сеанса';
  $pageinfo = 'На этой странице вы можете видеть статистику по
  времени проведенном посетителями на вашем сайте за различные
  периоды времени.';

  try
  {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    ?>
      <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="header">
          <td width=<?= 100/5 ?>% align=center><a href='time.php?begin=1&end=0'>Сегодня</a></td>
          <td width=<?= 100/5 ?>% align=center><a href='time.php?begin=2&end=1'>Вчера</a></td>
          <td width=<?= 100/5 ?>% align=center><a href='time.php?begin=7&end=0'>За 7 дней</a></td>
          <td width=<?= 100/5 ?>% align=center><a href='time.php?begin=30&end=0'>За 30 дней</a></td>
          <td width=<?= 100/5 ?>% align=center><a href='time.php?begin=0&end=0'>За всё время</a></td>
        </tr>
      </table><br><br>
      <table class="table" width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr class="header"><td><p>Время</td><td>Пользователей</td><td>Гистограмма</td></tr>
    <?php
    // Очищаем временную таблицу $tbl_thits
    $query = "TRUNCATE TABLE $tbl_thits";
    if(!mysql_query($query))
    {
       throw new ExceptionMySQL(mysql_error(),
                                $query,
                               "Ошибка очистки временной таблицы");
    }

    // Заполняем временную страницу
    for($i = $begin; $i > $end; $i--)
    {
      // Формируем WHERE-условие для временного интервала
      $where = where_interval($begin, $end);
      $query = "INSERT INTO $tbl_thits
                SELECT ROUND((max(unix_timestamp(putdate))-min(unix_timestamp(putdate)))/60)*60+60
                FROM $tbl_ip
                $where AND systems != 'none' AND
                       systems != 'robot_yandex' AND
                       systems != 'robot_google' AND
                       systems != 'robot_rambler' AND
                       systems != 'robot_aport' AND
                       systems != 'robot_msn'
                GROUP BY ip";
      if(!mysql_query($query))
      {
         throw new ExceptionMySQL(mysql_error(),
                                  $query,
                                 "Ошибка заполнения временной таблицы");
      }
    }

    $query = "SELECT COUNT(hits) AS total
              FROM $tbl_thits
              GROUP BY hits
              ORDER BY total DESC
              LIMIT 1";
    $total = query_result($query);

    $query = "SELECT hits, COUNT(hits) AS total
              FROM $tbl_thits
              GROUP BY hits
              ORDER BY hits";
    $res = mysql_query($query);
    if(!$res)
    {
       throw new ExceptionMySQL(mysql_error(),
                                $query,
                               "Ошибка извлечения посещений");
    }
    while($time = mysql_fetch_array($res))
    {
      echo "<tr>
             <td>".gmdate("H:i", $time['hits'])."</td>
             <td>$time[total]</td>
             <td><img src=images/parm.gif
                      border=0
                      width=".(400/$total*$time['total'])."
                      height=6></td>
            </tr>";
      $host += $time['total'];
    }
    echo "<tr>
            <td>&nbsp;</td>
            <td>Хостов: $host</td>
            <td>&nbsp;</td>
          </tr>";

    echo "</table>";
    // Завершение страницы
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
?>