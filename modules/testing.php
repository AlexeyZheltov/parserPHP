<?php
  /*  set_time_limit(0);
    $query = "SELECT * FROM search_words_test";
    $result = mysqlQuery($query);
    //$site = mysql_fetch_assoc($result);

    while ($site = mysql_fetch_assoc($result))
    {      $id_word = $site['id_word'];
      $res = explode(",", $site['words']);
      $num =  count ($res);
      $res = array_unique($res);
      $k = 0;
      for ($i = 0; $i < $num; $i++)
      {
        if (!empty($res[$i]))
        {
          $final[$k] = $res[$i];
          $k++;
        }
      }

      for ($i = 0; $i < count ($final); $i++)
      {        $query_insert = "INSERT INTO search_slovoform_test
                                        (id_word, word)
                         VALUE
                                ('$id_word', '" .  $final[$i] . "')";
        $result_insert = mysqlQuery($query_insert);      }
      unset($final);    }
      */
 /*
   $query = "DELETE FROM search_slovoform_test";
    $result = mysqlQuery($query);
 */
$url = "http://cars.auto.ru/";

$content = get_content($url);

// Собираем внутренние ссылки на данной странице ///////////////////////
        $links_in_site = get_links($url, $content, "http://auto.ru/");
dbg($links_in_site);
		foreach ($links_in_site as $val) {
          if(check_link($val, 96) == 0) {                             //            0,13 сек
            // если ссылки нет в БД - заносим ее
  //          echo $val . "</br>";
          }
        }
		
		
	
 //dbg(get_content($url));






?>