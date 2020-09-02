<?php
	/*******************************************************************************
	* Библиотека общих функций
	* @author Zheltiy
	* @copyright © Студенческая ВОЛНА 2011
	* @version 1.3
	* @date 19.12.2011
	*******************************************************************************/

	// Генерация страницы ошибки при доступе вне системы
	if(!defined('GVS_ACCSESS')) {
		header("HTTP/1.1 404 Not Found");
		exit(file_get_contents('../404.html'));
	}

	// Класс устанавливающий начало и окончание времени
	class Timer {
		var $start_time, $time;
		// Старт таймера
		function start_time() {
			$start_time       = microtime();
			// разделяем секунды и миллисекунды (становятся значениями начальных ключей массива-списка)
			$start_array      = explode(" ",$start_time);
			// это и есть стартовое время
			$this->start_time = $start_array[1] + $start_array[0];
		}

		// Остановка таймера
		function end_time() {
			$end_time   = microtime();
			$end_array  = explode(" ", $end_time);
			$end_time   = $end_array[1] + $end_array[0];
			// вычитаем из конечного времени начальное
			$this->time = $end_time - $this->start_time;
		}

		// Печать таймера
		function print_time() {
			printf("Страница сгенерирована за %f секунд", $this->time);
		}

		// Получение времени
		function get_time() {			return $this->time;
		}
	}

	// Логирование
	class LogFile {
		var $end_memory_usage, $start_memory_usage;

		// отметка начала памяти
		function start_memory() {
			$this->start_memory_usage = memory_get_usage();
		}
		function end_memory() {
			$this->end_memory_usage = memory_get_usage();
		}
		function get_log($str, $id) {
			$memory_usage = "Расход памяти:  " . number_format($this->end_memory_usage, 0, '.', ',') . " байт\n";
			$mem = $this->end_memory_usage;
			// Добавляем данные объявления в БД date
			$query_insert = "INSERT INTO parser_log (num_str, id_site, memory_usage, mem)
						   VALUE ('" . $str . "', '" . $id . "', '". $memory_usage . "', '" . $mem . "')";
			$result = mysqlQuery($query_insert);
		}
	}
	
	// Функция проверяющая частоту обновления страницы
	function fast_update() {
		global $error_msg;
		$timer_page_update = new Timer;

		// Запускаем таймер
		$timer_page_update->start_time();

		// Если переменная не установлена. То записываем в нее время когда создана страница
		if (empty($_SESSION['page_time'])) {
			$_SESSION['page_time']             = $timer_page_update->start_time;
			$_SESSION['page_fastupdate_count'] = 0;
		}
		else { // Иначе проверяем частоту обновления			// если частатота превышена выводим страницу ошибки
			if ($timer_page_update->start_time - $_SESSION['page_time'] < 1) {
				$_SESSION['page_fastupdate_count']++;
				if ($_SESSION['page_fastupdate_count'] > GVS_FAST_UPDATE) {
					$error_msg = 'Вы делаете слишком частые обращения к серверу';
					include (GVS_ERROR);
					exit();
				}
			}
			else { // Если частота нормальная сбрасываем счетчик
				$_SESSION['page_fastupdate_count'] = 0;
			}

			// запичываем новое время создания страницы
			$_SESSION['page_time'] = $timer_page_update->start_time;
		}
	}

	// Получение текста внутри тега
	function get_text($start, $end, $text) {
		if (empty($start) or empty($end)) return 0;

		$get_text = get_teg('|' . $start . '(.*)' . $end . '|Umsi', $text);
		$len = strlen($get_text);
		$len_start = strlen($start);
		$len_end = strlen($end);

		return mysql_real_escape_string(substr($get_text, $len_start, $len - $len_end - $len_start));
	
		
	}

	function get_teg($reg, $text) {
		preg_match_all ($reg, $text, $get_teg);
		if (!empty($get_teg[0][0])) {
			return $get_teg[0][0];
		}
	}
		
	// функция получения внутренних ссылок на странице
	function get_links($link, $content, $site) {
		if (substr($link, 0, 12) == "https://www.") {
			$url = substr($link, 12);
		}
		elseif (substr($link, 0, 11) == "http://www.") {
			$url = substr($link, 11);
		}
		elseif (substr($link, 0, 8) == "https://") {
			$url = substr($link, 8);
		}
		elseif (substr($link, 0, 7) == "http://") {
			$url = substr($link, 7);
		}
		elseif (substr($link, 0, 4) == "www.") {
			$url = substr($link, 4);
			$link = "http://" . $link;
		}
		else {
			$url = $link;
			$link = "http://" . $link;
		}

		$parse_link = parse_url($link);

		if (!empty($parse_link['path'])) {
			$parse_link['end_parth'] = substr($parse_link['path'], 0, strripos($parse_link['path'], "/") + 1);
		}

		$parse_site = parse_url($site);
		$vnut    = array();	
		$vnech   = array();

		preg_match_all('|<a [^<>]*href=(.*)[ >]|Umsi', $content, $matches);

		$i=-1;
		$len_link = strlen($link);

		foreach ($matches[1] as $val) {
			$i++;
			$out_link = 1;

			$val = str_replace ('"', "", $val);
			$val = str_replace ("'", "", $val);
			$val = str_replace ("\'", "", $val);
			$val = str_replace ("&quot;", "", $val);

			// Ссылка на предыдущий раздел
			if (substr($val, 0, 3) == "../") continue;

			// Пропускаем страницы со скриптами
			if (substr($val, 0, 1) == "?" or substr($val, 0, 1) == "#") continue;
			

			if (substr($val, 0, 4) == "http") {
				$parse_link_val = parse_url($val);
				if (!empty($parse_link_val['host'])) {
								
					if ($parse_link_val['host'] == $parse_link['host']) {
						$vnut[] = $val;
					}
					else {
						// Внешняя ссылка
						if (stripos($parse_link_val['host'], $parse_site['host']) === false ) continue;
						$vnut[] = $val;
					}
				}
				else {	// "Некорректная ссылка
					continue;
				}
			}
			elseif (substr($val, 0, 1) == "/") {
				$vnut[] = $parse_link['scheme'] . "://" . $parse_link['host'] . $val;
			}
			elseif (substr($val, 0, 2) == "./") {
				$vnut[] = $parse_link['scheme'] . "://" . $parse_link['host'] . substr($val, 1);
			}
			elseif (stripos($val, "\\") !== false) {
				continue;
			}
			else {
				$vnut[] = $parse_link['scheme'] . "://" . $parse_link['host'] . "/" . $val;
			}

		}

		$vnut  = array_unique ($vnut);
		return $vnut;

		$vnech = array_unique ($vnech);
	}

	// функция проверки ссылки в БД
	function check_link($url, $id_site) {
		if (mb_strlen($url) > 250) {
			return 1;
		}
		
		// Ищем ссылки-исключения
		$query = "SELECT * FROM parser_block_list
				  WHERE id_site = '$id_site' OR id_site = 0";
		$result = mysqlQuery($query);
		while ($res_block = mysql_fetch_assoc($result)) {
			$str_like = str_replace ("%", "", $res_block['like_str']);
			$str_like = str_replace ("\\", "", $str_like);
			$pos = strpos(mb_strtolower($url), $str_like);
			if ($pos === false) continue;
			return 1; // ссылка заблокирована
		}
		$url = trim($url);
		
		// Предотвращаем повторы
		$query = "SELECT * FROM parser_link_list
				  WHERE id_site = '$id_site' AND
						link    = '$url'
				  LIMIT 1";
		$result = mysqlQuery($query);
		if ($res = mysql_fetch_assoc($result)) return 1; // ссылка найдена в БД
		
		// Проверяем белый список
		$query = "SELECT * FROM parser_white_list
				  WHERE id_site = '$id_site' OR id_site = 0";
		$result = mysqlQuery($query);
		$res = 0;
		while ($res_white = mysql_fetch_assoc($result)) {
			$str_like = str_replace ("%", "", $res_white['like_str']);
			$str_like = str_replace ("\\", "", $str_like);
			$pos = strpos($url, $str_like);
			if ($pos === false) {
				$res = 1;  
			}
			else {
				return 0; // ссылка белая 
			}
		}
		
		if ($res == 0) return 0; // ссылка ,белая
		return 1; // ссылка не белая
	}

	// функция возвращает массив данных объявления
	function get_data($content, $res_site, $res_link = NULL) {
		// Если страница не пуста
		if (empty($content)) return NULL;
	
		// ЦЕНА //////////////////////////////////////////////////////////////////
		$end = $res_site['price_end'];
		$start = $res_site['price_start'];
		$price = get_text($start, $end, $content);

		// НАИМЕНОВАНИЕ //////////////////////////////////////////////////////////
		$end = $res_site['name_end'];
		$start = $res_site['name_start'];
		$name = get_text($start, $end, $content);

		// МОДЕЛЬ ////////////////////////////////////////////////////////////////
		$end = $res_site['model_end'];
		$start = $res_site['model_start'];
		$model = get_text($start, $end, $content);

		// ДАТА ПРЕДЛОЖЕНИЯ //////////////////////////////////////////////////////
		$end = $res_site['date_end'];
		$start = $res_site['date_start'];
		$date = get_text($start, $end, $content);

		// АДРЕС/МЕСТОПОЛОЖЕНИЕ //////////////////////////////////////////////////
		$end = $res_site['adres_end'];
		$start = $res_site['adres_start'];
		$adres = get_text($start, $end, $content);

		// КОММЕНТАРИИ ///////////////////////////////////////////////////////////
		$end = $res_site['komment_end'];
		$start = $res_site['komment_start'];
		$komment = get_text($start, $end, $content);

		// ДАТА ВЫПУСКА //////////////////////////////////////////////////////////
		$end = $res_site['date_made_end'];
		$start = $res_site['date_made_start'];
		$date_made = get_text($start, $end, $content);

		// КОНТАКТНОЕ ИМЯ ////////////////////////////////////////////////////////
		$end = $res_site['kontact_name_end'];
		$start = $res_site['kontact_name_start'];
		$kontact_name = get_text($start, $end, $content);

		// ТЕЛЕФОН ///////////////////////////////////////////////////////////////
		$end = $res_site['tel_end'];
		$start = $res_site['tel_start'];
		$tel = get_text($start, $end, $content);

		// АКТУЛЬНОСТЬ ///////////////////////////////////////////////////////////
		$end = $res_site['topicality_end'];
		$start = $res_site['topicality_start'];
		$topicality = get_text($start, $end, $content);

		// СОСТОЯНИЕ   ///////////////////////////////////////////////////////////
		$end = $res_site['state_end'];
		$start = $res_site['state_start'];
		$state = get_text($start, $end, $content);

		$date_update = date("Y-m-d H:i:s");

		//ПУСТОЙ КОНТЕНТ 
		if (empty($name) and empty($price)) return NULL;

		for ($i = 1; $i < 11; $i++) {			$end = $res_site['COP' . $i . '_val_end'];
			$start = $res_site['COP' . $i . '_val_start'];

			if ($end == "" or $start == "") {
				$data['COP' . $i . '_name'] = "";
				$data['COP' . $i . '_val'] = "";
				continue;
			}

			$data['COP' . $i . '_name'] = format_text($res_site['COP' . $i . '_name']);
			$data['COP' . $i . '_val'] = format_text(get_text($start, $end, $content));		}

		$data['price']        = format_text($price);
		$data['name']         = format_text($name);
		$data['model']        = format_text($model);
		$data['date']         = format_text($date);
		$data['state']        = format_text($state);
		$data['adres']        = format_text($adres);
		$data['komment']      = format_text($komment);
		$data['date_made']    = format_text($date_made);
		$data['kontact_name'] = format_text($kontact_name);
		$data['tel']          = format_text($tel);
		$data['topicality']   = format_text($topicality);
		$data['date_update']  = format_text($date_update);

		if (!empty($res_site['id_site'])) {			$data['id_site']    = $res_site['id_site'];
		}
		if (!is_null($res_link)) {			$data['link']        = $res_link['link'];
			$data['id_link']     = $res_link['id_link'];		}
		if (empty($data['name']) and !empty($data['model'])) {
			$data['name'] = $data['model'];
		}
		if (empty($data['date'])) {
			$data['date'] = $date_update;
		}
		return $data;
	}

	// функция, выводящая кодировку файла
	function search_file_kod ($content) {
		
		$charsets = array ( 'w' => 0, 'k' => 0, 'i' => 0, 'm' => 0, 'a' => 0, 'c' => 0, 'u' => 0 );

		// Windows-1251
		$search_l_w = "~([\270])|([\340-\347])|([\350-\357])|([\360-\367])|([\370-\377])~s";
		$search_U_w = "~([\250])|([\300-\307])|([\310-\317])|([\320-\327])|([\330-\337])~s";

		// Koi8-r
		$search_l_k = "~([\243])|([\300-\307])|([\310-\317])|([\320-\327])|([\330-\337])~s";
		$search_U_k = "~([\263])|([\340-\347])|([\350-\357])|([\360-\367])|([\370-\377])~s";

		// Iso-8859-5
		$search_l_i = "~([\361])|([\320-\327])|([\330-\337])|([\340-\347])|([\350-\357])~s";
		$search_U_i = "~([\241])|([\260-\267])|([\270-\277])|([\300-\307])|([\310-\317])~s";

		// X-mac-cyrillic
		$search_l_m = "~([\336])|([\340-\347])|([\350-\357])|([\360-\367])|([\370-\370])|([\337])~s";
		$search_U_m = "~([\335])|([\200-\207])|([\210-\217])|([\220-\227])|([\230-\237])~s";

		// Ibm866
		$search_l_a = "~([\361])|([\240-\247])|([\250-\257])|([\340-\347])|([\350-\357])~s";
		$search_U_a = "~([\360])|([\200-\207])|([\210-\217])|([\220-\227])|([\230-\237])~s";

		// Ibm855
		$search_l_c = "~([\204])|([\234])|([\236])|([\240])|([\242])|([\244])|([\246])|([\250])|".
		"([\252])|([\254])|([\265])|([\267])|([\275])|([\306])|([\320])|([\322])|".
		"([\324])|([\326])|([\330])|([\340])|([\341])|([\343])|([\345])|([\347])|".
		"([\351])|([\353])|([\355])|([\361])|([\363])|([\365])|([\367])|([\371])|([\373])~s";
		$search_U_c = "~([\205])|([\235])|([\237])|([\241])|([\243])|([\245])|([\247])|([\251])|".
		"([\253])|([\255])|([\266])|([\270])|([\276])|([\307])|([\321])|([\323])|".
		"([\325])|([\327])|([\335])|([\336])|([\342])|([\344])|([\346])|([\350])|".
		"([\352])|([\354])|([\356])|([\362])|([\364])|([\366])|([\370])|([\372])|([\374])~s";

		// Utf-8
		$search_l_u = "~([\xD1\x91])|([\xD1\x80-\x8F])|([\xD0\xB0-\xBF])~s";
		$search_U_u = "~([\xD0\x81])|([\xD0\x90-\x9F])|([\xD0\xA0-\xAF])~s";

		try {
			if ( preg_match_all ($search_l_w, $content, $arr, PREG_PATTERN_ORDER)) { $charsets['w'] += count ($arr[0]) * 3; }
			if ( preg_match_all ($search_U_w, $content, $arr, PREG_PATTERN_ORDER)){ $charsets['w'] += count ($arr[0]); }

			if ( preg_match_all ($search_l_k, $content, $arr, PREG_PATTERN_ORDER)) { $charsets['k'] += count ($arr[0]) * 3; }
			if ( preg_match_all ($search_U_k, $content, $arr, PREG_PATTERN_ORDER)){ $charsets['k'] += count ($arr[0]); }

			if ( preg_match_all ($search_l_i, $content, $arr, PREG_PATTERN_ORDER)) { $charsets['i'] += count ($arr[0]) * 3; }
			if ( preg_match_all ($search_U_i, $content, $arr, PREG_PATTERN_ORDER)){ $charsets['i'] += count ($arr[0]); }

			if ( preg_match_all ($search_l_m, $content, $arr, PREG_PATTERN_ORDER)) { $charsets['m'] += count ($arr[0]) * 3; }
			if ( preg_match_all ($search_U_m, $content, $arr, PREG_PATTERN_ORDER)){ $charsets['m'] += count ($arr[0]); }

			if ( preg_match_all ($search_l_a, $content, $arr, PREG_PATTERN_ORDER)) { $charsets['a'] += count ($arr[0]) * 3; }
			if ( preg_match_all ($search_U_a, $content, $arr, PREG_PATTERN_ORDER)){ $charsets['a'] += count ($arr[0]); }

			if ( preg_match_all ($search_l_c, $content, $arr, PREG_PATTERN_ORDER)) { $charsets['c'] += count ($arr[0]) * 3; }
			if ( preg_match_all ($search_U_c, $content, $arr, PREG_PATTERN_ORDER)){ $charsets['c'] += count ($arr[0]); }

			if ( preg_match_all ($search_l_u, $content, $arr, PREG_PATTERN_ORDER)) { $charsets['u'] += count ($arr[0]) * 3; }
			if ( preg_match_all ($search_U_u, $content, $arr, PREG_PATTERN_ORDER)){ $charsets['u'] += count ($arr[0]); }
		}
		catch (Exception $e) {
			echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
		}

		arsort ($charsets);
		$key = key($charsets);
		if ( max ($charsets)==0 ){ return 'unknown'; }
		elseif ( $key == 'w' ){ return 'Windows-1251'; }
		elseif ( $key == 'k' ){ return 'Koi8-r'; }
		elseif ( $key == 'i' ){ return 'Iso-8859-5'; }
		elseif ( $key == 'm' ){ return 'mac'; }
		elseif ( $key == 'a' ){ return 'ibm866';}
		elseif ( $key == 'c' ){ return 'ibm855';}
		elseif ( $key == 'u' ){ return 'utf-8'; }
	}

	// Функция преобразования текста
	// Функция удаляет html теги двойные пробелы и прочее
	function format_text($string) {
		if (empty($string)) return "";
		// Удаляем html теги
		$string = strip_tags($string);

		// Перенос строк \r\n
		$string = str_replace('\r\n', PHP_EOL, $string);

		// Перенос строк \n
		$string = str_replace('\n', PHP_EOL, $string);

		// Замена двух пробелов подряд
		while (strstr($string, '  ')) {
			$string = str_replace('  ', ' ', $string);
		}

		// Удаление двух переносов текста подряд
		while (strstr($string, PHP_EOL . PHP_EOL)) {
			$string = str_replace(PHP_EOL . PHP_EOL, PHP_EOL, $string);
		}

		// Удаляем перенос строки если он стоит в начале или конце строки
		$string = trim($string);
		return $string;	}

	// Функция возвращает текстовое содержимое любого сайта
	function get_content($url) {

		if($ch = curl_init()) {
			
			$headers = [
					'Accept-Language: en-US,en;q=0.9,ru-RU;q=0.8,ru;q=0.7',
					'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8',
					'User-Agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
					];


			$browser['language'] = "en-us,en;q=0.5";
			$browser['user_agent'] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36";
			$browser['connection'] = "close";

			// Задаем ссылку
			curl_setopt($ch, CURLOPT_URL, $url);

			// указываем заголовки для браузера
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); /* array(
							"User-Agent: {$browser['user_agent']}",
							"Accept-Language: {$browser['language']}",
							"Connection: {$browser['connection']}",
							"Vary: Accept-Encoding",
							"Content-Type: text/html"
						));*/

			// Скачанные данные не выводить поток
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

			curl_setopt($ch, CURLOPT_COOKIEFILE, GVS_ROOT . "cookie.txt");
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			//curl_setopt($ch, CURLOPT_VERBOSE, 1);
			//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip');


			// Скачиваем
			$content = curl_exec($ch);

			// Закрываем соединение
			curl_close ($ch);
		}

		$coding = search_file_kod($content);

		//  Смена кодировки при необходимости 
		if ($coding != "utf-8" and $coding != "unknown") {
			$content = iconv($coding, "utf-8//IGNORE", $content);
		}
		return $content;	}

	// Проверяет необходимость обновления ссылок/предложений
	/* возвращает 1 если необходимо обновление
	* возвращает 0 если обновление не требуется
	* @author Zheltiy
	* @copyright
	* @version 1.0
	* @date
	*/
	function check_update_now($last_update,$standing_max) {
		//Разбиваем значение даты на части
		$time_part_1 = explode(" ",$last_update);
		$time_part_2 = explode("-",$time_part_1[0]);
		$time_part_3 = explode(":",$time_part_1[1]);

		//Определяем элементы времени
		$year  = $time_part_2[0]; $month   = $time_part_2[1]; $day     = $time_part_2[2];
		$hours = $time_part_3[0]; $minutes = $time_part_3[1]; $seconds = $time_part_3[2];

		//Определяем время воследнего посещения в секундах
		$last_update  =  mktime($hours,$minutes,$seconds,$month,$day,$year);

		//Определяем текущее время в секундах
		$now = new Timer;
		$now->start_time();

		//Определяем время простоя
		$standing = $now->start_time-$last_update;

		if ($standing > $standing_max) {
			return 1;
		}
		else {
			return 0;
		}
	}

	// Сканирует сайт, ищет новые ссылки, собирает и обновляет данные объявлений
	function scan_site($id_site) {

		set_time_limit(0);
		$count_scan = 0;
		$time_summ = 0;

		// Получаем данные скарируемого сайта
		$query = "SELECT * FROM parser_site_list
				  WHERE id_site = '$id_site'
				  LIMIT 1";
		$result = mysqlQuery($query);
		$site = mysql_fetch_assoc($result);
		if ($site == false) {			echo "Сайт с id = " . $id_site . " отсутствует в БД";
			return 0;		}

		$coding = $site['coding'];  // Кодировка сайта

		// Проверяем идет ли уже в данный момент обновление сайта 
		if ($site['run'] == 1) return 0;

		// Проверяем заблокирован ли сайт 
		if ($site['block'] == 1) return 0;
		
		$SITE['settings']['period_update'] = PERIOD_UPDATE;	          // Частота обновления сайтов
		$period_update = 60*60*24*$SITE['settings']['period_update']; // Частота обновления ссылок

		// Если сайт не требует обновления
		If (check_update_now($site['last_update'], $period_update) == 0) return 0; 

		// Если сайт требует проверки тегов
		if ($site['teg_correct'] == 2) {
			echo "Сайт с id = " . $id_site . " трубует проверки тегов";
			return 0; // завершаем процесс сканирования
		}

		// Если сайт запускается впервые заносим главную страницу в список ссылок
		$query = "SELECT COUNT(*) FROM parser_link_list
				  WHERE id_site = '$id_site'";
		$result = mysqlQuery($query);
		$row = mysql_fetch_row($result);
		if ($row[0] == 0) {			$query = "INSERT INTO parser_link_list (link, id_site)
						   VALUE ('" . $site['link'] . "',
								  '" . $site['id_site'] . "')";
			$result = mysqlQuery($query);		}

		// Объявляем что обновление сайта началось
		$query = "UPDATE parser_site_list
				  SET run = 1
				  WHERE id_site = '$id_site'";
		$result = mysqlQuery($query);
		$timer_prosses = new Timer;              // Объект - таймер процесса
		$timer_prosses -> start_time();          // Включаем таймер

		
		/********************ПРОХОДИМ ПО ССЫЛКАМ САЙТА*****************************/
		$stop = 0;
		$teg_correct = 0;
		do {
		  
			// Если пользователь завершил процесс - выходим
			if (stop_scan($id_site) == 1) {		
				// Завершаем сканирование,
				// Объявляем, что обновление ссылок сайта закончилось
				$query_up = "UPDATE parser_site_list
							 SET run = 0
							 WHERE id_site = '$id_site'";
				$result_up = mysqlQuery($query_up);
				echo "Процесс сканирования сайта id = " . $id_site . " остановлен пользователем";
				return 0;			}

			// Ищем не просканированную ссылку
			$date_update = date("Y-m-d H:i:s");     // текущая дата
			$query = "SELECT * FROM parser_link_list
						 WHERE id_site = '$id_site' AND
							   block != 1 AND
							   run   != 1 AND
							   del   != 1
						 LIMIT 1";
			$result = mysqlQuery($query);

			// Если нет неотсканированной ссылки - выходим		
			if ($link = mysql_fetch_assoc($result)) { }
			else { 
				break;
			}

			$timer_prosses -> end_time();                     // Останавливаем таймер
			$time_prosses = $timer_prosses -> get_time();     // Получаем время процесса
			$time = $site['delay'] - $time_prosses;           // Определяем оставшееся время задержки

			$count_scan++;                           // Количество отсканированных ссылок
			$time_summ = $time_summ + $time_prosses; // Суммарное время обновления сайта
			$time_mid = $time_summ/$count_scan;      // Среднее время, на ссылку
			$link_name = $link['link'];              // Переменная с текущей ссылкой сканирования
			$id_link   = $link['id_link'];           // Переменная с текущим id ссылки

			// Случайно задерживаем выполнение кода
			if ($time > 0) {
				$time = rand(0, $time);
				sleep ($time);
			}

			// Снова включаем таймер 
			$timer_prosses -> start_time();

			// Отмечаем факт начала сканирования ссылки
			$query_up = "UPDATE parser_link_list
						 SET run         = 1,
							 date_update = '$date_update',
							 num_update  = num_update + 1
						 WHERE id_link = '$id_link'
						 LIMIT 1";
			$result_up = mysqlQuery($query_up);

			// Показываем имя ссылки пользователю
			$query_up = "UPDATE parser_site_list
						 SET last_link         = '$link_name',
							 time_last_link = '$date_update',
							 period_update = '$time_mid'
						 WHERE id_site = '$id_site'
						 LIMIT 1";
			$result_up = mysqlQuery($query_up);

			// Получение текста страницы
			$content = get_content ($link_name);

			// Если контент пуст - переходим к следующей ссылке
			if (empty($content)) {
				if ($link['content_empty'] > 4) {
					// Если контент пуст 5 раз, блокируем ссылку					$query_update = "UPDATE parser_link_list
									 SET content_empty         = content_empty + 1,
										 date_update           = '$date_update',
										 block                 = 1,
										 num_update_last_price = 0
									 WHERE id_link = '$id_link'
									 LIMIT 1";
				}
				else {
					// Иначе увеличиваем счетчик пустого контента					$query_update = "UPDATE parser_link_list
									 SET content_empty         = content_empty + 1,
										 date_update           = '$date_update',
										 block                 = 0,
										 num_update_last_price = 0
									 WHERE id_link = '$id_link'
									 LIMIT 1";				}

				$result = mysqlQuery($query_update);
				unset($content); //Освобождаем память
				continue;
			}

			// Собираем внутренние ссылки на данной странице
			$links_in_site = get_links($link_name, $content, $site['link']);

			// Проверяем найденные ссылки на наличие их в базе данных
			foreach ($links_in_site as $val) {
				$val = trim($val);
				if(check_link($val, $id_site) == 0) {
					$query_update  = "INSERT INTO parser_link_list (id_site,  link, link_from)
										VALUE        ('$id_site', '$val', '$id_link')";
					$result_update = mysqlQuery($query_update);
				}
			}

			// Получаем данные из объявления
			$data = get_data($content, $site, $link);

			if ($site['teg_correct'] == 0) {
				if ($data === NULL or empty($data['price']) or empty($data['name'])) {
					if ($link['isdata'] == 1) {
						// Если ранее по ссылке было объявление
						$teg_correct++;
						if ($teg_correct > 5) {
							// Теги не корректны
							$query_teg = "UPDATE parser_site_list
											 SET teg_correct = 2,
												 run         = 0
											 WHERE id_site = '$id_site'
											 LIMIT 1";
							$result = mysqlQuery($query_teg);

							// Объявляем, что обновление ссылок сайта закончилось
							$query_up = "UPDATE parser_link_list
										 SET run = 0
										 WHERE id_site = '$id_site'";
							$result_up = mysqlQuery($query_up);

							echo "Процесс сканирования сайта id = " . $id_site . " остановлен. Необходима проверка тегов";
							return 0;
						}
					}
				}
				else {
					if (!empty($data['price']) and !empty($data['name'])) {
						// Если есть объявление - показываем, что теги корректны
						$query_teg = "UPDATE parser_site_list
										SET teg_correct = 1
										WHERE id_site = '$id_site'
										LIMIT 1";
						$result = mysqlQuery($query_teg);
						$site['teg_correct'] = 1;
					}
				}
			}

			// Проверяем данные 
			if (check_data($data, $link) == 0) {
				unset($content); //Освобождаем память				continue;
			}

			// Заносим данные в БД ссылок и объявлений, запоминаем id обявления ////
			$id_date = insert_data($data);

			// Сохраняем принтскрин   //////////////////////////////////////////////
			create_file ($id_date, $content, $coding);

			unset($content); //Освобождаем память

		} while ($stop != 1);


		// Объявляем, что обновление ссылок сайта закончилось
		$query_up = "UPDATE parser_site_list
					 SET run = 0,
						 teg_correct = 0,
						 last_update = '$date_update'
					 WHERE id_site = '$id_site'";
		$result_up = mysqlQuery($query_up);

		// Объявляем, что обновление ссылок сайта закончилось
		$query_up = "UPDATE parser_link_list
					 SET run = 0
					 WHERE id_site = '$id_site'";
		$result_up = mysqlQuery($query_up);

		echo "Сайт с id = " . $id_site . " обновлен";
		return 1;
	}

	// Проверяет корректность полученных данных объявления
	function check_data($data, $link) {
		$id_link = $link['id_link'];
		// Если данные пусты обновляем таблицу link_list
		if ($data === NULL or empty($data['price']) or empty($data['name'])) {
			$query_up = "UPDATE parser_link_list
					   SET isdata = 0
					   WHERE id_link = '$id_link'
					   LIMIT 1";
			$res_up   = mysqlQuery($query_up);
			return 0;
		}

		// Если ничего не изменилось переходим к следующей ссылке
		if ($link['last_price'] == $data['price'] and $link['date_price'] == $data['date']) return 0;

		// Если данные не пусты обновляем таблицу link_list
		$query_up = "UPDATE parser_link_list
					 SET isdata = 1,
						 last_price = '" . $data['price'] . "',
						 date_price = '" . $data['date'] . "'
					 WHERE id_link = '$id_link'
					 LIMIT 1";
		$res_up   = mysqlQuery($query_up);
		return 1;
	}

	// Сохраняет данные объявления в БД
	function insert_data($data) {
		// Добавляем данные объявления в БД date
		$query_insert = "INSERT INTO parser_data (name,
												  model,
												  link,
												  date,
												  price,
												  date_update,
												  adres,
												  komment,
												  date_made,
												  kontact_name,
												  tel,
												  topicality,
												  id_site,
												  id_link,";
		for ($i = 1; $i < 11; $i++) {
			$query_insert .= "COP" . $i . "_name,
							COP" . $i . "_val,";
		}
		$query_insert .= "state)
						 VALUE ('" . $data['name']        . "',
								'" . $data['model']        . "',
								'" . $data['link']        . "',
								'" . $data['date']         . "',
								'" . $data['price']        . "',
								'" . $data['date_update']  . "',
								'" . $data['adres']        . "',
								'" . $data['komment']      . "',
								'" . $data['date_made']    . "',
								'" . $data['kontact_name'] . "',
								'" . $data['tel']          . "',
								'" . $data['topicality']   . "',
								'" . $data['id_site']      . "',
								'" . $data['id_link']      . "',";
		for ($i = 1; $i < 11; $i++) {
			$query_insert .= "'" . $data['COP' . $i . '_name'] . "',
							'" . $data['COP' . $i . '_val']  . "',";
		}

		$query_insert .= "'" . $data['state'] . "')";
		$result = mysqlQuery($query_insert);

		return mysql_insert_id();
	}

	// Создает директрию и сохраняет файл с номером объявления
	// $coding - кодировка в которой написан сайт
	function create_file ($id_date, $content, $coding) {
		$SITE['settings']['PrtScnDir'] = PrtScnDir;
		// Раскладываем по попкам
		$name_file = $SITE['settings']['PrtScnDir'] . floor($id_date/1000) . "\\" . $id_date . ".html";

		if (is_dir($SITE['settings']['PrtScnDir'] . floor($id_date/1000)) == false) {
			mkdir($SITE['settings']['PrtScnDir'] . floor($id_date/1000));		}

		$fp = fopen($name_file, "a");

		/*if ($coding != "utf-8") {
			$coding_teg = $coding . "//IGNORE";
			$content = iconv("utf-8", $coding_teg, $content);
		}
*/
		$test = fwrite($fp, $content); // Запись в файл
		fclose($fp); //Закрытие файла
	}

	// Проверяет теги сайта
	function check_tegs_site($url, $data) {		// получаем контент
		$content = get_content($url);
		// Получаем данные объявления
		dbg (get_data($content, $data));
		
		//dbg($content);	}

	// Остановка процесса сканирования
	function stop_scan($id_site) {		// Получаем данные скарируемого сайта
		$query = "SELECT stop_scan FROM parser_site_list
				  WHERE id_site = '$id_site' AND stop_scan = 1
				  LIMIT 1";
		$result = mysqlQuery($query);
		$stop = mysql_fetch_assoc($result);

		if ($stop == false) return 0; // Остановка не требуется
		return 1;	}

	// Определение скорости прохода по ссылкам
	function get_speed_scan_link($id_site, $time) {
		if ($id_site == 0) {
			$query = "SELECT COUNT(*)*60/$time As Speed FROM `parser_link_list` WHERE `date_update` > (NOW() - INTERVAL $time MINUTE)";
		}
		else {
			$query = "SELECT COUNT(*)*60/$time As Speed FROM `parser_link_list` WHERE id_site = '$id_site' AND `date_update` > (NOW() - INTERVAL $time MINUTE)";
		}
		$result = mysqlQuery($query);
		$res = mysql_fetch_assoc($result);
		return $res['Speed'];
	}

	// Определение скорости сбора объявлений 
	function get_speed_scan_data($id_site, $time) {
		if ($id_site == 0) {
			$query = "SELECT COUNT(*)*60/$time As Speed FROM `parser_data` WHERE `date_update` > (NOW() - INTERVAL $time MINUTE)";
		}
		else {
			$query = "SELECT COUNT(*)*60/$time As Speed FROM `parser_data` WHERE id_site = '$id_site' AND `date_update` > (NOW() - INTERVAL $time MINUTE)";
		}
		$result = mysqlQuery($query);
		$res = mysql_fetch_assoc($result);
		return $res['Speed'];
	}

	function udpate_link_whith_data($period_update) {
		
		
		$query = "UPDATE parser_process SET run = 1, date_start = NOW(), date_update = NOW() WHERE id = 1";
		$result = mysqlQuery($query);
		
		$query = "SELECT * FROM parser_site_list WHERE teg_correct = 1 AND ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_update)) > " . $period_update . "*60*60*24 )";
		$result = mysqlQuery($query);
		while ($res = mysql_fetch_assoc($result)) {
			$SITES[] = $res;
		}	


		$timer_prosses = new Timer;              // Объект - таймер процесса
		$timer_prosses -> start_time();          // Включаем таймер
		
		do {
			$count = count($SITES);
			
			foreach ($SITES as $key=> $site) {
				
				$query = "UPDATE parser_process SET date_update = NOW() WHERE id = 1";
				$result = mysqlQuery($query);
		
				$query = "SELECT * FROM parser_link_list 
								   WHERE id_site = '".$site['id_site']."' AND 
										 isdata = '1' AND run = 0
								   LIMIT 1";
				$result = mysqlQuery($query);

				if ($link = mysql_fetch_assoc($result)) {
					
				}
				else {		
					$date_update = date("Y-m-d H:i:s");
					// Объявляем, что обновление ссылок сайта закончилось
					$query_up = "UPDATE parser_site_list
								 SET run = 0,
									 teg_correct = 0,
									 last_update = '$date_update'
								 WHERE id_site = '".$site['id_site']."'";
					$result_up = mysqlQuery($query_up);

					// Объявляем, что обновление ссылок сайта закончилось
					$query_up = "UPDATE parser_link_list
								 SET run = 0
								 WHERE id_site = '".$site['id_site']."'";
					$result_up = mysqlQuery($query_up);
				
					unset($SITES[$key]); 
					break;
				}

				$timer_prosses -> end_time();                     // Останавливаем таймер
				$time_prosses = $timer_prosses -> get_time();     // Получаем время процесса
				$time = $site['delay'] - $time_prosses;           // Определяем оставшееся время задержки

				$count_scan++;                           // Количество отсканированных ссылок
				$time_summ = $time_summ + $time_prosses; // Суммарное время обновления сайта
				$time_mid  = $time_summ/$count_scan;      // Среднее время, на ссылку
				$link_name = $link['link'];              // Переменная с текущей ссылкой сканирования
				$id_link   = $link['id_link'];           // Переменная с текущим id ссылки

				// Случайно задерживаем выполнение кода
				if ($count == 1 && $time > 0) {
					$time = rand(0, $time);
					sleep ($time);
				}

				// Снова включаем таймер 
				$timer_prosses -> start_time();

				// Отмечаем факт начала сканирования ссылки
				$query_up = "UPDATE parser_link_list
							 SET run         = 1,
								 date_update = '$date_update',
								 num_update  = num_update + 1
							 WHERE id_link = '$id_link'
							 LIMIT 1";
				$result_up = mysqlQuery($query_up);

				// Показываем имя ссылки пользователю
				$query_up = "UPDATE parser_site_list
							 SET last_link         = '$link_name',
								 time_last_link = '$date_update',
								 period_update = '$time_mid'
							 WHERE id_site = '$id_site'
							 LIMIT 1";
				$result_up = mysqlQuery($query_up);

				// Получение текста страницы
				$content = get_content ($link_name);

				
				// Если контент пуст - переходим к следующей ссылке
				if (empty($content)) {
					if ($link['content_empty'] > 4) {
						// Если контент пуст 5 раз, блокируем ссылку
						$query_update = "UPDATE parser_link_list
										 SET content_empty         = content_empty + 1,
											 date_update           = '$date_update',
											 block                 = 1,
											 num_update_last_price = 0
										 WHERE id_link = '$id_link'
										 LIMIT 1";
					}
					else {
						// Иначе увеличиваем счетчик пустого контента
						$query_update = "UPDATE parser_link_list
										 SET content_empty         = content_empty + 1,
											 date_update           = '$date_update',
											 block                 = 0,
											 num_update_last_price = 0
										 WHERE id_link = '$id_link'
										 LIMIT 1";
					}

					$result = mysqlQuery($query_update);
					unset($content); //Освобождаем память
					continue;
				}


				// Получаем данные из объявления
				$data = get_data($content, $site, $link);

				// Проверяем данные 
				if (check_data($data, $link) == 0) {
					unset($content); //Освобождаем память
					continue;
				}

				// Заносим данные в БД ссылок и объявлений, запоминаем id обявления ////
				$id_date = insert_data($data);

				// Сохраняем принтскрин   //////////////////////////////////////////////
				create_file ($id_date, $content, $coding);

				unset($content); //Освобождаем память
				
			}	
		} while (count($SITES) > 0);
		
		$query = "UPDATE parser_process SET run = 0 WHERE id = 1";
		$result = mysqlQuery($query);
		
	}
	
	// проверка корректности тегов на сайтах
	function check_site_on_correct_tegs($period_update) {
		$query = "SELECT * FROM parser_site_list WHERE teg_correct = 0 AND ((UNIX_TIMESTAMP() - UNIX_TIMESTAMP(last_update)) > " . $period_update . "*60*60*24 )";
		$result = mysqlQuery($query);
		while ($res = mysql_fetch_assoc($result)) {
			check_correct_tegs($res['id_site']);
		}	
	}
	
	
	// Проверка корректности тегов по 10 объявлениям
	function check_correct_tegs($id_site) {
		$site = get_site($id_site);
		
		// получаем 10 ссылок с объявлениями
		$query = "SELECT * FROM parser_data
				  WHERE id_site = '$id_site'
				  LIMIT 10";
		$result = mysqlQuery($query);
		while ($res = mysql_fetch_assoc($result)) {
			$content = get_content($res['link']);
			if (empty($content)) continue;
			
			// Получаем данные из объявления
			$data = get_data($content, $site);
			unset($content);
			
			if ($data === NULL or empty($data['price']) or empty($data['name'])) continue;
			
			$query = "UPDATE parser_site_list SET `teg_correct` = 1 WHERE id_site = '$id_site'";
			$result = mysqlQuery($query);
			return true;
		}
		return false;
	}
	
	// Получаем данные сайта
	function get_site($id_site) {	
		$query = "SELECT * FROM parser_site_list
				  WHERE id_site = '$id_site'
				  LIMIT 1";
		$result = mysqlQuery($query);
		return mysql_fetch_assoc($result);
	}
	
	
?>