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

  $title = 'IP&nbsp;адреса';
  $pageinfo = 'На этой странице вы можете видеть IP-адреса
  посетителей, соответствующие этим адресам доменные имена
  хостов, количество обращений с данного IP-адреса, процент
  обращений с этого IP-адреса от общего количества обращений
  и последнее время обращения с этого IP-адреса. Нажав на
  подсвеченный IP-адрес можно получить информацию о том,
  на кого он зарегистрирован. ';

  try
  {
    // Включаем заголовок страницы
    require_once("../utils/topcounter.php");

    // Запрашиваем уникальные ip-адреса из базы данных
    // отсортиврованные по времени
    if(empty($_GET['page'])) $page = 1;
    else $page = $_GET['page'];
    // Вычисляем начало вывода
    $begin = ($page - 1)*$pnumber;

    $tmp = "";
    if(!empty($_GET['id_page']))
      $tmp = " AND id_page=$_GET[id_page]";

    $page_link = 3;
    // Определяем число посетителей с уникальными IP-адресами
    // за последние сутки

    // Выводим количество посетителей с уникальными IP-адресами
    if(defined("IP_UNIQUE_USE"))
    {
      // Все IP-адреса
      $query = "SELECT COUNT(*) FROM $tbl_ip_unique
                ORDER BY putdate DESC";
    }
    else
    {
      // Текущие IP-адреса
      $query = "SELECT COUNT(distinct ip) FROM $tbl_ip
                WHERE systems != 'none' AND
                systems != 'robot_yandex' AND
                systems != 'robot_google' AND
                systems != 'robot_rambler' AND
                systems != 'robot_aport' AND
                systems != 'robot_msnbot' AND
                putdate LIKE CONCAT(DATE_FORMAT(NOW(),'%Y-%m-%d'), '%') $tmp";
    }
    $total = query_result($query);

    // Выводим ссылки на другие страницы
    pager($page,
          $total,
          $pnumber,
          $page_link,
          "&id_page=$_GET[id_page]");
    echo "<br>";

    // Выводим сами ip-адреса
    ?>
      <br><br><table class="table" border="0" cellpadding="0" cellspacing="0">
         <tr class="header" align="center">
           <td>№</td>
           <td>IP-адрес</td>
           <td>Хост</td>
           <td>Регион</td>
           <td>Город</td>
           <td>Всего посещений</td>
           <td>Последнее&nbsp;обращение</td>
         </tr>
    <?php
    if(defined("IP_UNIQUE_USE"))
    {
      // Все IP-адреса
      $query = "SELECT INET_NTOA(ip) AS ip,
                       putdate,
                       total AS hits FROM $tbl_ip_unique
                ORDER BY putdate DESC
                LIMIT $begin, $pnumber";
    }
    else
    {
      // Текущие IP-адреса
      $query = "SELECT INET_NTOA(ip) AS ip,
                       max(putdate) AS putdate,
                       count(id_ip) AS hits FROM $tbl_ip
                WHERE
                systems != 'none' AND
                systems != 'robot_yandex' AND
                systems != 'robot_google' AND
                systems != 'robot_rambler' AND
                systems != 'robot_aport' AND
                systems != 'robot_msnbot' AND
                putdate LIKE CONCAT(DATE_FORMAT(NOW(),'%Y-%m-%d'), '%') $tmp
                GROUP BY ip
                ORDER BY putdate DESC
                LIMIT $begin, $pnumber";
    }
    $ips = mysql_query($query);
    if(!$ips)
    {
      throw new ExceptionMySQL(mysql_error(),
                               $query,
                              "Ошибка при обращении
                               к таблице IP-адресов");
    }
    if(mysql_num_rows($ips) > 0)
    {
      $i=1;
      while($ip = mysql_fetch_array($ips))
      {
        $query = "SELECT city_name, region_name
                  FROM $tbl_ip_compact, $tbl_cities, $tbl_regions
                  WHERE INET_ATON('$ip[ip]') BETWEEN init_ip AND end_ip AND
                        $tbl_cities.city_id = $tbl_ip_compact.city_id AND
                        $tbl_cities.region_id = $tbl_regions.region_id";
        $reg = mysql_query($query);
        if(!$reg)
        {
          throw new ExceptionMySQL(mysql_error(),
                                   $query,
                                  "Ошибка при определении
                                   местоположения IP-адреса");
        }
        $region = mysql_fetch_array($reg);
        echo "<tr>
              <td>$i</td>
              <td><a href='pages.php?nav=1&ip=$ip[ip]'>$ip[ip]</a></td>";
        if(HOST_BY_ADDR) echo "<td>".(@gethostbyaddr($ip['ip']))."</td>";
        else echo "<td align=center>-</td>";
        if ($region['city_name'])
          echo "<td>$region[city_name]</td>";
        else echo "<td>нет данных</td>";
        if ($region['region_name'])
          echo "<td>$region[region_name]</td>";
        else echo "<td>нет данных</td>";
        echo "<td>$ip[hits]</td><td>$ip[putdate]</td>";
        $i++;
      }
    }
    echo "</table>";
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
?>