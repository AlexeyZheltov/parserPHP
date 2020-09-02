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
  $title = 'Понедельный отчёт';
  try
  {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // Постраничная навигация
    if(empty($_GET['page'])) $page = 1;
    else $page = $_GET['page'];

    // Извлекаем количество страниц
    $query = "SELECT COUNT(DISTINCT putdate_begin)
              FROM $tbl_arch_ip_week";
    $total = query_result($query);

    $page_link = 3;
    $first = ($page - 1)*$pnumber;

    // Выводим ссылки на другие страницы
    pager($page,
          $total,
          $pnumber,
          $page_link,
          "");
    echo "<br><br>";

    // Извлекаем данные для текущей страницы
    $query = "SELECT UNIX_TIMESTAMP(putdate_begin) as putdate_begin,
                     UNIX_TIMESTAMP(putdate_end) as putdate_end
              FROM $tbl_arch_time_week
              GROUP BY putdate_begin
              ORDER BY putdate_begin DESC
              LIMIT $first, $pnumber";
    $arh = mysql_query($query);
    if(!$arh)
    {
       throw new ExceptionMySQL(mysql_error(),
                                $query,
                               "Ошибка извлечения недельной статистики");
    }
    if(mysql_num_rows($arh))
    {
      echo "<table class=table width=100% border=0 cellpadding=0 cellspacing=0>
              <tr class=header align=center>
                <td align=center width=".(100/12)."%>Дата</td>
                <td align=center width=".(100/12)."%>Ссылка</td>
              </tr>";
      while($hits = mysql_fetch_array($arh))
      {
        // Формируем дату
        $date_table = date("d.m",$hits['putdate_begin'])." - ".date("d.m",$hits['putdate_end']);
        echo "<tr>
                <td align=center>$date_table</td>
                <td align=center><a href=$_SERVER[PHP_SELF]?date=$hits[putdate_begin]>смотреть</a></td>
              </tr>";
      }
      echo "</table><br><br>";
    }

    // Если параметр $_GET['date'] не пуст, запрашиваем IP-адреса
    // за этот день
    if(!empty($_GET['date']))
    {
      $_GET['date'] = intval($_GET['date']);

      // Извлекаем время посещения
      $query = "SELECT * FROM $tbl_arch_deep_week
                WHERE putdate_begin LIKE '".date("Y-m-d",$_GET['date'])."%'";
      $ipt = mysql_query($query);
      if(!$ipt)
      {
         throw new ExceptionMySQL(mysql_error(),
                                  $query,
                                 "Ошибка извлечения недельной статистики");
      }
      if(mysql_num_rows($ipt))
      {
        echo "<table class=table width=100% border=0 cellpadding=0 cellspacing=0>
                <tr class=header align=center>
                  <td width=200>Число просмотренных страниц</td>
                  <td width=150>Посетителей</td>
                  <td>Гистограмма</td>
                </tr>";
        $arch_deep = mysql_fetch_array($ipt);
        unset($arch_deep['id_client'], $arch_deep['putdate']);
        $total = array_sum($arch_deep);
        echo "<tr>
                <td>1 страница</td>
                <td>".$arch_deep['visit1']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_deep['visit1']/$total)."% height=6></td>
              </tr>\r\n";
        echo "<tr>
                <td>2 страницы</td>
                <td>".$arch_deep['visit2']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_deep['visit2']/$total)."% height=6></td>
              </tr>\r\n";
        echo "<tr>
                <td>3 страницы</td>
                <td>".$arch_deep['visit3']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_deep['visit3']/$total)."% height=6></td>
              </tr>\r\n";
        echo "<tr>
                <td>4 страницы</td>
                <td>".$arch_deep['visit4']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_deep['visit4']/$total)."% height=6></td>
              </tr>\r\n";
        for($i = 5; $i < 11; $i++)
        {
          echo "<tr>
                  <td>$i страниц</td>
                  <td>".$arch_deep['visit'.$i]."</td>
                  <td><img src=images/parm.gif border=0 width=".(100*$arch_deep['visit'.$i]/$total)."% height=6></td>
                </tr>\r\n";
        }
        for($i = 10; $i < 100; $i = $i + 10)
        {
          echo "<tr>
                  <td>от ".$i." до ".($i+10)." страниц</td>
                  <td>".$arch_deep['visit'.($i+10)]."</td>
                  <td><img src=images/parm.gif border=0 width=".(100*$arch_deep['visit'.($i+10)]/$total)."% height=6></td>
                </tr>\r\n";
        }
        echo "<tr>
                <td>более 100 страниц</td>
                <td>".$arch_deep['visitmore']."</td>
                <td><img src=images/parm.gif border=0 width=".(100*$arch_deep['visitmore']/$total)."% height=6></td>
              </tr>\r\n";
      }
      echo "</table>";
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
?>
