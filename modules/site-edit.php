<?php
  if (!empty($_GET['id_site']))
  {
    $id_site = $_GET['id_site'];
    if (empty($_POST)) {

      // Получаем данные скарируемого сайта
      $query = "SELECT * FROM parser_site_list
                WHERE id_site = '$id_site'
                LIMIT 1";
      $result = mysqlQuery($query);
      $res = mysql_fetch_assoc($result);
      foreach ($res as &$val)
      {      	$val = htmlspecialchars($val);      }

      $_REQUEST = $res;
    }

 	if (!empty($_POST['price_add']))
    {
      $query = "INSERT INTO parser_teg_price (start, end)
                       VALUE ('" . mysql_real_escape_string($_POST['price_start']) . "',
                              '" . mysql_real_escape_string($_POST['price_end']) . "')";
      $result = mysqlQuery($query);
    }

    if (!empty($_POST['name_add']))
    {
      $query = "INSERT INTO parser_teg_name (start, end)
                       VALUE ('" . mysql_real_escape_string($_POST['name_start']) . "',
                              '" . mysql_real_escape_string($_POST['name_end']) . "')";
      $result = mysqlQuery($query);
    }

    if (!empty($_POST['adres_add']))
    {
      $query = "INSERT INTO parser_teg_adress (start, end)
                       VALUE ('" . mysql_real_escape_string($_POST['adres_start']) . "',
                              '" . mysql_real_escape_string($_POST['adres_end']) . "')";
      $result = mysqlQuery($query);
    }


  	if (!empty($_POST['url']))
    {
      $content = get_content($_POST['url']);

      // Получаем данные
      $query = "SELECT * FROM parser_teg_name";
      $result = mysqlQuery($query);

	  $count_name_teg = 0;
      while ($res_teg = mysql_fetch_assoc($result))
      {
      	$end = $res_teg['end'];
      	$start = $res_teg['start'];
      	$teg_val = get_text($start, $end, $content);

      	if (!empty($teg_val))
      	{
      	  $count_name_teg++;
          $name[$count_name_teg]['value'] = format_text($teg_val);
		  if (strlen($name[$count_name_teg]['value']) > 255) {
			  $name[$count_name_teg]['value'] = substr($name[$count_name_teg]['value'], 0, 255) . ".....";
		  }
          $name[$count_name_teg]['start'] = htmlspecialchars($start);
          $name[$count_name_teg]['end']   = htmlspecialchars($end);
      	}

      }

	  $end = '>';
      $start = '<h1';
	  $teg_val = get_text($start, $end, $content);
	  
	  if (!empty($teg_val))
      	{
			$start = '<h1' . $teg_val . '>';
			
			$start = str_replace('\\', '', $start);
			$end = '</h1>';
			$teg_val = get_text($start, $end, $content);
	
			if (!empty($teg_val))
				{
				  $count_name_teg++;
				  $name[$count_name_teg]['value'] = format_text($teg_val);
				  if (strlen($name[$count_name_teg]['value']) > 255) {
					  $name[$count_name_teg]['value'] = substr($name[$count_name_teg]['value'], 0, 255) . ".....";
				  }
				  $name[$count_name_teg]['start'] = str_replace('\\', '', htmlspecialchars($start));
				  $name[$count_name_teg]['end']   = $end;
				}
      	}
	  

	  // Получаем данные
      $query = "SELECT * FROM parser_teg_price";
      $result = mysqlQuery($query);

	  $count_price_teg = 0;
      while ($res_teg = mysql_fetch_assoc($result))
      {
      	$end = $res_teg['end'];
      	$start = $res_teg['start'];
      	$teg_val = get_text($start, $end, $content);

      	if (!empty($teg_val))
      	{
      	  $count_price_teg++;
          $price[$count_price_teg]['value'] = format_text($teg_val);
		  if (strlen($price[$count_price_teg]['value']) > 255) {
			  $price[$count_price_teg]['value'] = substr($price[$count_price_teg]['value'], 0, 255) . ".....";
		  }
          $price[$count_price_teg]['start'] = htmlspecialchars($start);
          $price[$count_price_teg]['end']   = htmlspecialchars($end);
      	}

      }
	  
	  $end = '>';
      $start = 'http://schema.org/Offer';
	  $teg_val = get_text($start, $end, $content);
	  
	  if (!empty($teg_val))
      	{
			$start = 'http://schema.org/Offer' . $teg_val . '>';
			
			$start = str_replace('\\', '', $start);
			$end = '</';
			$teg_val = get_text($start, $end, $content);
	
			if (!empty($teg_val))
			{
			  $count_price_teg++;
			  $price[$count_price_teg]['value'] = format_text($teg_val);
			  if (strlen($price[$count_price_teg]['value']) > 1000) {
				  $price[$count_price_teg]['value'] = substr($price[$count_price_teg]['value'], 0, 1000) . ".....";
			  }
			  $price[$count_price_teg]['start'] = str_replace('\\', '', htmlspecialchars($start));
			  $price[$count_price_teg]['end']   = $end;
			}
			
			$end = '</div>';
			$teg_val = get_text($start, $end, $content);
			
			if (!empty($teg_val))
			{
				if (strlen(format_text($teg_val)) < strlen($price[$count_price_teg]['value'])) 
				{
					$price[$count_price_teg]['value'] = format_text($teg_val);
					if (strlen($price[$count_price_teg]['value']) > 1000) {
						  $price[$count_price_teg]['value'] = substr($price[$count_price_teg]['value'], 0, 1000) . ".....";
					  }
					$price[$count_price_teg]['start'] = str_replace('\\', '', htmlspecialchars($start));
					$price[$count_price_teg]['end']   = $end;	
				}
			}
      	}
		
		
		$end = '>';
      $start = 'class="price"';
	  $teg_val = get_text($start, $end, $content);
	  
	  if (!empty($teg_val))
      	{
			$start = 'class="price"' . $teg_val . '>';
			
			$start = str_replace('\\', '', $start);
			$end = '</';
			$teg_val = get_text($start, $end, $content);
	
			if (!empty($teg_val))
			{
			  $count_price_teg++;
			  $price[$count_price_teg]['value'] = format_text($teg_val);
			  if (strlen($price[$count_price_teg]['value']) > 1000) {
				  $price[$count_price_teg]['value'] = substr($price[$count_price_teg]['value'], 0, 1000) . ".....";
			  }
			  $price[$count_price_teg]['start'] = str_replace('\\', '', htmlspecialchars($start));
			  $price[$count_price_teg]['end']   = $end;
			}
			
      	}
	  

	  // Получаем данные
      $query = "SELECT * FROM parser_teg_coding";
      $result = mysqlQuery($query);

	  $count_coding_teg = 0;
      while ($res_teg = mysql_fetch_assoc($result))
      {
      	$end = $res_teg['end'];
      	$start = $res_teg['start'];
      	$teg_val = get_text($start, $end, $content);

      	if (!empty($teg_val))
      	{
      	  $count_coding_teg++;
          $coding[$count_coding_teg]['value'] = format_text($teg_val);
		  if (strlen($name[$count_coding_teg]['value']) > 1000) {
			  $coding[$count_coding_teg]['value'] = substr($coding[$count_coding_teg]['value'], 0, 1000) . ".....";
		  }
          $coding[$count_coding_teg]['start'] = htmlspecialchars($start);
          $coding[$count_coding_teg]['end']   = htmlspecialchars($end);
      	}

      }

	  // Получаем данные
      $query = "SELECT * FROM parser_teg_adress";
      $result = mysqlQuery($query);

	  $count_adress_teg = 0;
      while ($res_teg = mysql_fetch_assoc($result))
      {
      	$end = $res_teg['end'];
      	$start = $res_teg['start'];
      	$teg_val = get_text($start, $end, $content);

      	if (!empty($teg_val))
      	{
      	  $count_adress_teg++;
          $adress[$count_adress_teg]['value'] = format_text($teg_val);
		  if (strlen($adress[$count_adress_teg]['value']) > 1000) {
			  $adress[$count_adress_teg]['value'] = substr($adress[$count_adress_teg]['value'], 0, 1000) . "...";
		  }
          $adress[$count_adress_teg]['start'] = htmlspecialchars($start);
          $adress[$count_adress_teg]['end']   = htmlspecialchars($end);
      	}

      }
	  
	  
	  $end = '>';
      $start = '<nav';
	  $teg_val = get_text($start, $end, $content);
	  
	  if (!empty($teg_val))
      	{
			$start = '<nav' . $teg_val . '>';
			
			$start = str_replace('\\', '', $start);
			$end = '</nav>';
			$teg_val = get_text($start, $end, $content);
	
			if (!empty($teg_val))
				{
				  $count_adress_teg++;
				  $adress[$count_adress_teg]['value'] = format_text($teg_val);
				  if (strlen($adress[$count_adress_teg]['value']) > 1000) {
					  $adress[$count_adress_teg]['value'] = substr($adress[$count_adress_teg]['value'], 0, 1000) . "...";
				  }
				  $adress[$count_adress_teg]['start'] = str_replace('\\', '', htmlspecialchars($start));
				  $adress[$count_adress_teg]['end']   = $end;
				}
      	}
		
		
      foreach ($_REQUEST as &$val)
      {
        $val = htmlspecialchars($val);
      }
    }

	foreach($_POST as $key => $val )
    {
	  if (substr($key, 0, 9) == "coding_ok")
	  {
	      // Получаем данные скарируемого сайта
	      $query_save = "UPDATE parser_site_list
	                     SET
	                          coding = '" . mysql_real_escape_string($_POST['coding_teg_value' . substr($key, 9)]) . "'
	                     WHERE id_site = '$id_site'";
	      $result = mysqlQuery($query_save);
	    $_REQUEST['coding'] = htmlspecialchars($_POST['coding_teg_value' . substr($key, 9)]);
	  }

	  if (substr($key, 0, 7) == "name_ok")
	  {
	      // Получаем данные скарируемого сайта
	      $query_save = "UPDATE parser_site_list
	                     SET
	                          name_start = '" . mysql_real_escape_string($_POST['name_teg_start' . substr($key, 7)]) . "',
	                          name_end   = '" . mysql_real_escape_string($_POST['name_teg_end' . substr($key, 7)])   . "'
	                     WHERE id_site = '$id_site'";
	      $result = mysqlQuery($query_save);
	      $_REQUEST['name_start'] = htmlspecialchars($_POST['name_teg_start' . substr($key, 7)]);
	      $_REQUEST['name_end']   = htmlspecialchars($_POST['name_teg_end' . substr($key, 7)]);
	  }

	  if (substr($key, 0, 8) == "price_ok")
	  {
	  	  // Получаем данные скарируемого сайта
	      $query_save = "UPDATE parser_site_list
	                     SET
	                          price_start = '" . mysql_real_escape_string($_POST['price_teg_start' . substr($key, 8)]) . "',
	                          price_end   = '" . mysql_real_escape_string($_POST['price_teg_end' . substr($key, 8)])   . "'
	                     WHERE id_site = '$id_site'";
	      $result = mysqlQuery($query_save);
	      $_REQUEST['price_start'] = htmlspecialchars($_POST['price_teg_start' . substr($key, 8)]);
	      $_REQUEST['price_end']   = htmlspecialchars($_POST['price_teg_end' . substr($key, 8)]);

	  }

	  if (substr($key, 0, 9) == "adress_ok")
	  {
	  	  // Получаем данные скарируемого сайта
	      $query_save = "UPDATE parser_site_list
	                     SET
	                        adres_start = '" . mysql_real_escape_string($_POST['adress_teg_start' . substr($key, 9)]) . "',
	                        adres_end   = '" . mysql_real_escape_string($_POST['adress_teg_end' . substr($key, 9)])   . "'
	                     WHERE id_site = '$id_site'";
	      $result = mysqlQuery($query_save);
	      $_REQUEST['adres_start'] = htmlspecialchars($_POST['adress_teg_start' . substr($key, 9)]);
	      $_REQUEST['adres_end']   = htmlspecialchars($_POST['adress_teg_end' . substr($key, 9)]);
	  }
    }



    if (!empty($_POST['save']))
    {
      // Получаем данные скарируемого сайта
      $query_save = "UPDATE parser_site_list
                      SET link               = '" . mysql_real_escape_string($_POST['link'])               . "',
                          run                = '" . mysql_real_escape_string($_POST['run'])                . "',
                          stop_scan          = '" . mysql_real_escape_string($_POST['stop_scan'])          . "',
                          coding             = '" . mysql_real_escape_string($_POST['coding'])             . "',
                          block              = '" . mysql_real_escape_string($_POST['block'])              . "',
						  teg_correct        = '" . mysql_real_escape_string($_POST['teg_correct'])        . "',
                          last_update        = '" . mysql_real_escape_string($_POST['last_update'])        . "',
                          delay              = '" . mysql_real_escape_string($_POST['delay'])              . "',
                          price_start        = '" . mysql_real_escape_string($_POST['price_start'])        . "',
                          model_start        = '" . mysql_real_escape_string($_POST['model_start'])        . "',
                          date_start         = '" . mysql_real_escape_string($_POST['date_start'])         . "',
                          name_start         = '" . mysql_real_escape_string($_POST['name_start'])         . "',
                          date_made_start    = '" . mysql_real_escape_string($_POST['date_made_start'])    . "',
                          state_start        = '" . mysql_real_escape_string($_POST['state_start'])        . "',
                          price_end          = '" . mysql_real_escape_string($_POST['price_end'])          . "',
                          model_end          = '" . mysql_real_escape_string($_POST['model_end'])          . "',
                          date_end           = '" . mysql_real_escape_string($_POST['date_end'])           . "',
                          name_end           = '" . mysql_real_escape_string($_POST['name_end'])           . "',
                          date_made_end      = '" . mysql_real_escape_string($_POST['date_made_end'])      . "',
                          state_end          = '" . mysql_real_escape_string($_POST['state_end'])          . "',
                          adres_start        = '" . mysql_real_escape_string($_POST['adres_start'])        . "',
                          komment_start      = '" . mysql_real_escape_string($_POST['komment_start'])      . "',
                          kontact_name_start = '" . mysql_real_escape_string($_POST['kontact_name_start']) . "',
                          tel_start          = '" . mysql_real_escape_string($_POST['tel_start'])          . "',
                          topicality_start   = '" . mysql_real_escape_string($_POST['topicality_start'])   . "',
                          adres_end          = '" . mysql_real_escape_string($_POST['adres_end'])          . "',
                          komment_end        = '" . mysql_real_escape_string($_POST['komment_end'])        . "',
                          kontact_name_end   = '" . mysql_real_escape_string($_POST['kontact_name_end'])   . "',
                          tel_end            = '" . mysql_real_escape_string($_POST['tel_end'])            . "',
                          topicality_end     = '" . mysql_real_escape_string($_POST['topicality_end'])     . "'";
      for ($i = 1; $i < 11; $i++) {
        $query_save .= ",
                        COP" . $i . "_name      = '" . mysql_real_escape_string($_POST['COP' . $i . '_name'])      . "',
                        COP" . $i . "_val_start = '" . mysql_real_escape_string($_POST['COP' . $i . '_val_start']) . "',
                        COP" . $i . "_val_end   = '" . mysql_real_escape_string($_POST['COP' . $i . '_val_end'])   . "'";
      }
      $query_save .= "WHERE id_site = '$id_site'";

      $result = mysqlQuery($query_save);
	  
	  if (empty($_POST['url'])) {
    
		  foreach ($_REQUEST as &$val)
		  {
			$val = htmlspecialchars($val);
		  }     
	  
	  }    }

    if (!empty($_POST['check']))
    {      check_tegs_site($_POST['url'], $_POST);
     /*
      foreach ($_REQUEST as &$val)
      {
        $val = htmlspecialchars($val);
      } */    }
    /*
    elseif (!empty($_POST))
    {
      foreach ($_REQUEST as &$val)
      {
        $val = htmlspecialchars($val);
      }
    }
      */
  }

?>