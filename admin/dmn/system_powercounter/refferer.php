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

  // Заголовок страницы
  $title = 'Рефереры';
  $pageinfo = 'На этой странице вы можете видеть статистику
  по количеству рефереров, т.е. переходов на ваш сайт с
  других сайтов.';
  try
  {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // Элемент постраничной навигации
    if(empty($_GET['page'])) $page = 1;
    else $page = intval($_GET['page']);

    // Выводим таблицу с реферерами
    refferer(1, 0, $page, $pnumber);
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
  function refferer($begin, $end, $page, $pnumber)
  {
    // Объявляем имена таблиц глобальными
    global $tbl_refferer, $tbl_pages;
    // Формируем WHERE-условие для временного интервала
    $where = where_interval($begin, $end);

    $page_link = 3;
    $start = ($page - 1)*$pnumber;
    // Общее количество записей
    $query = "SELECT COUNT(DISTINCT name)
              FROM $tbl_refferer $where";
    $total = query_result($query);

    // Выводим ссылки на другие страницы
    pager($page,
          $total,
          $pnumber,
          $page_link,
          "");
    echo "<br><br>";

    // Извлекаем позиции для текущей страницы
    $query = "SELECT name,
                     COUNT(name) AS hits,
                     id_page
              FROM $tbl_refferer
              $where
              GROUP BY name
              ORDER BY hits DESC
              LIMIT $start, $pnumber";
    $ref = mysql_query($query);
    $i = $start + 1;
    if(!$ref)
    {
      throw new ExceptionMySQL(mysql_error(),
                               $query,
                              "Ошибка при выполнении запроса");
    }
    if(mysql_num_rows($ref))
    {
      echo "<table class=table width=100% border=0 cellpadding=0 cellspacing=0>
              <tr class=header align=center>
                <td widht=50 align=center>Номер</td>
                <td>Реферер</td>
                <td>Число обращений</td>
                <td>Страница</td>
              </tr>";
      while($refferer = mysql_fetch_array($ref))
      {
        if(empty($refferer['name'])) continue;
        // Извлекаем название страницы
        $query = "SELECT * FROM $tbl_pages
                  WHERE id_page = $refferer[id_page]";
        $pag = mysql_query($query);
        if(!$pag)
        {
          throw new ExceptionMySQL(mysql_error(),
                                   $query,
                                  "Ошибка при выполнении запроса");
        }
        if(mysql_num_rows($pag))
        {
          $page = mysql_fetch_array($pag);
          if(empty($page['title']))
          {
            $title = "http://{$_SERVER[SERVER_NAME]}{$page[name]}";
          }
          else $title = $page['title'];
        }
        echo "<tr>
              <td>$i</td>
              <td>".htmlspecialchars($refferer['name'])."</td>
              <td align=center>$refferer[hits]</td>
              <td><a href=http://{$_SERVER[SERVER_NAME]}{$page[name]}>$title</a></td>
              </tr>";
        $i++;
      }
      echo "</table>";
    }
  }
?>