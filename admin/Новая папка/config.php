<?php
  ///////////////////////////////////////////////////
  // уЙУФЕНБ БЧФПТЙЪБГЙЙ HTTP-Basic БЧФПТЙЪБГЙЙ
  // 2003-2005 (C) IT-УФХДЙС SoftTime (http://www.softtime.ru)
  // уЙНДСОПЧ й.ч. (simdyanov@softtime.ru)
  ///////////////////////////////////////////////////
  $dblocation = "localhost";
  $dbname = "templ";
  $dbuser = "root";
  $dbpasswd = "";
  $dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);
  if (!$dbcnx) exit("<p>К сожалению, не доступен сервер MySQL</p>");
  if (!@mysql_select_db($dbname,$dbcnx)) exit("<p>К сожалению, не доступна база данных</p>");
?>