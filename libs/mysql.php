<?php
/******************************************************************************/
/*                                                                            */
/*                                                                            */
/******************************************************************************/

  // Модуль безопасности
  if(!defined('GVS_ACCSESS'))require_once(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/libs/mod_security.php");


  // Функция для запроса к БД
  function mysqlQuery($sql, $print = false) {
    $result = mysql_query($sql, GVS_CONNECT);
    if($result === false || $print) {
      $error = mysql_error();
      $trace = debug_backtrace();

      $head = $error ?'<b style="color:red">MySQL error: </b><br>
      <b style="color:green">'. $error .'</b><br><br>':NULL;

      $error_log = date("Y-m-d h:i:s") .' '. $head .'
      <b>Query: </b><br>
      <pre><span style="color:#CC0000">'     . $trace[0]['args'][0] .'</pre></span><br><br>
      <b>File: </b><b style="color:#660099">'. $trace[0]['file']    .'</b><br>
      <b>Line: </b><b style="color:#660099">'. $trace[0]['line']    .'</b>';

      // Если идет тестирование сайта, то выводим ошибку на экран
      if(defined('GVS_RELIASE')){ die($error_log); }

      // Иначе записываем в файл
      file_put_contents(GVS_ROOT .'log/mysql.log', strip_tags($error_log) ."\n", FILE_APPEND);
      header("HTTP/1.1 404 Not Found");
      die(file_get_contents(GVS_ROOT .'/404.html'));
    }
    else {
      return $result;
    }
  }

?>