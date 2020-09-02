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
  // Постраничная навигация
  require_once("../utils/utils.pager.php");
  // Формирование WHERE-условий
  require_once("utils.where.php");
  // Выполнение запроса
  require_once("utils.query_result.php");

  // Данные переменные определяют название страницы и подсказку.
  $title = 'Посуточный отчёт';

  try
  {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // $min_date - это самая ранняя дата, за которую
    // можно выбирать суточную статистику, за более ранний
    // период она попросту отсутствует
    $query = "SELECT UNIX_TIMESTAMP(MIN(putdate)) as putdate
              FROM $tbl_arch_time";
    $min_date = query_result($query);
    if(empty($min_date)) $min_date = time();

    // Выводим календарь за текущий месяц
    calendar(time(), $min_date);
    echo "<br><br>";
    // Выводим календарь за прошедщий месяц
    calendar(time() - 3600*24*date('j'), $min_date);
    echo "<br><br>";

    // Если параметр $_GET['date'] не пуст, запрашиваем IP-адреса
    // за этот день
    if(!empty($_GET['date']))
    {
      $_GET['date'] = intval($_GET['date']);

      // Извлекаем время посещения
      $query = "SELECT * FROM $tbl_arch_time
                WHERE putdate LIKE '".date("Y-m-d",$_GET['date'])."%'";
      $ipt = mysql_query($query);
      if(!$ipt)
      {
         throw new ExceptionMySQL(mysql_error(),
                                  $query,
                                 "Ошибка извлечения суточной статистики");
      }
      if(mysql_num_rows($ipt))
      {
        echo "<table class=table width=100% border=0 cellpadding=0 cellspacing=0>
                <tr class=header align=center>
                  <td width=150>Время</td>
                  <td width=150>Число обращений</td>
                  <td>Гистограмма</td>
                </tr>";
        $arch_time = mysql_fetch_array($ipt);
        unset($arch_time['id_time'], $arch_time['putdate']);
        $total = array_sum($arch_time);
        echo "<tr>
                <td>1 минута</td>
                <td>".$arch_time['visit1']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_time['visit1']/$total)."% height=6></td>
              </tr>\r\n";
        echo "<tr>
                <td>2 минуты</td>
                <td>".$arch_time['visit2']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_time['visit2']/$total)."% height=6></td>
              </tr>\r\n";
        echo "<tr>
                <td>3 минуты</td>
                <td>".$arch_time['visit3']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_time['visit3']/$total)."% height=6></td>
              </tr>\r\n";
        echo "<tr>
                <td>4 минуты</td>
                <td>".$arch_time['visit4']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_time['visit4']/$total)."% height=6></td>
              </tr>\r\n";
        for($i = 5; $i < 11; $i++)
        {
          echo "<tr>
                  <td>$i минут</td>
                  <td>".$arch_time['visit'.$i]."</td>
                  <td><img src=images/parm.gif border=0 width=".(100*$arch_time['visit'.$i]/$total)."% height=6></td>
                </tr>\r\n";
        }
        for($i = 10; $i < 60; $i = $i + 10)
        {
          echo "<tr>
                  <td>от ".$i." до ".($i+10)." минут</td>
                  <td>".$arch_time['visit'.($i+10)]."</td>
                  <td><img src=images/parm.gif border=0 width=".(100*$arch_time['visit'.($i+10)]/$total)."% height=6></td>
                </tr>\r\n";
        }
        for($i = 1; $i < 24; $i++)
        {
          echo "<tr>
                  <td>от ".$i." до ".($i+1)." часов</td>
                  <td>".$arch_time['visit'.($i+1).'h']."</td>
                  <td><img src=images/parm.gif border=0 width=".(100*$arch_time['visit'.($i+1).'h']/$total)."% height=6></td>
                </tr>\r\n";
        }
        echo "</table>";
      }
    }

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

  // Фунция выводящая календарь, в качестве параметра принимает время
  // в виде числа секунд, прошедших с полуночи 1 января 1970 года. Выводит
  // календарь для месяца даты
  function calendar($date, $min_date)
  {
    $eng = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
    $rus = array("Пн","Вт","Ср","Чт","Пт","Сб","Вс");

    // Вычисляем число дней в текущем месяце
    $dayofmonth = date('t',$date);
    // Счётчик для дней месяца
    $day_count = 1;

    // Первая неделя
    $num = 0;
    for($i = 0; $i < 7; $i++)
    {
      // Вычисляем номер дня недели для числа
      $dayofweek = date('w',mktime(0, 0, 0, date('m',$date), $day_count, date('Y',$date)));
      // Приводим к числа к формату 1 - понедельник, ..., 6 - суббота
      $dayofweek = $dayofweek - 1;
      if($dayofweek == -1) $dayofweek = 6;

      if($dayofweek == $i)
      {
        // Если дни недели совпадают,
        // заполняем массив $week
        // числами месяца
        $week[$num][$i] = $day_count;
        $day_count++;
      }
      else
      {
        $week[$num][$i] = "";
      }
    }

    // Последующие недели месяца
    while(true)
    {
      $num++;
      for($i = 0; $i < 7; $i++)
      {
        $week[$num][$i] = $day_count;
        $day_count++;
        // Если достигли конца месяца - выходим
        // из цикла
        if($day_count > $dayofmonth) break;
      }
      // Если достигли конца месяца - выходим
      // из цикла
      if($day_count > $dayofmonth) break;
    }

    // Выводим содержимое массива $week
    // в виде календаря
    // Выводим таблицу
    echo "<table class=table width=100% border=0 cellpadding=0 cellspacing=0>
            <tr class=header align=center><td align=center colspan=".(count($week) + 1).">Время посещения за ".date("Y.m", $date)."</td></tr>";
    for($j = 0; $j < 7; $j++)
    {
      if($j == 5 || $j == 6)
      {
        echo "<tr class=red>";
      }
      else
      {
        echo "<tr>";
      }
      echo "<td width=".(100/(count($week) + 1))."%>".$rus[$j]."</td>";
      for($i = 0; $i < count($week); $i++)
      {
        if(!empty($week[$i][$j]))
        {
          $dayofweek = mktime(0, 0, 0, date('m',$date), $week[$i][$j], date('Y',$date));
          if($dayofweek > $min_date && $dayofweek < time() - 3600*24)
          {
            echo "<td width=".(100/(count($week) + 1))."% align=center>
                    <a href=$_SERVER[PHP_SELF]?date=$dayofweek>".$week[$i][$j]."</a>
                  </td>";
          }
          else
          {
            echo "<td width=".(100/(count($week) + 1))."%  align=center>".$week[$i][$j]."</td>";
          }
        }
        else echo "<td>&nbsp;</td>";
      }
      echo "</tr>";
    }
    echo "</table>";
  }
?>