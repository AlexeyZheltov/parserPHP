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
  // Левин А.В. (loki_angel@mail.ru)
  // Кузнецов М.В. (kuznetsov@softtime.ru)
  // Голышев С.В. (softtime@softtime.ru)
  ////////////////////////////////////////////////////////////
  // Последний заархивированный день
  function begin_day_arch($tbl, $tbl_arch, $column = 'putdate')
  {
    // Месяц
    if(substr($tbl_arch, -5) == 'month') $interval = '+ INTERVAL 1 MONTH';
    // Неделя
    if(substr($tbl_arch, -4) == 'week') $interval = '+ INTERVAL 7 DAY';

    // Получаем последнюю дату, которая была заархивирована
    $query = "SELECT UNIX_TIMESTAMP(MAX($column{$interval})) FROM $tbl_arch";
    $last_date = query_result($query);
    if(empty($last_date))
    {
      $query = "SELECT UNIX_TIMESTAMP(DATE(MIN(putdate))) AS data FROM $tbl";
      $begin_date = query_result($query);
      if(!empty($begin_date))
      {
        // Если запуск первый - берём дату из $tbl
        $last_date = $begin_date;
      }
      else
      {
        // Иначе берём текущие сутки
        $last_date = time();
      }
    }
    return $last_date;
  }
?>