<?php
/*******************************************************************************
* Защитный код
* @author Zheltiy
* @copyright © Студенческая ВОЛНА 2011
* @version 1.0
* @date 26.07.2011
*******************************************************************************/
	session_start();

	// ИСХОДНЫЕ ДАННЫЕ ///////////////////////////////////////////////////////////
	// Ширина изображения
	$width  = 150;
	// Высота изображения
	$height = 50;
	// Количество символов в коде
	$sign   = 5;
	// Плотность точек
	$ro=($height*$width)/2000;

	// Символы, используемые в коде
	$letters = array('a','b','c','d','e','f',
						    	 'g','h','j','k','m','n',
								   'p','q','r','s','t','u',
								   'v','w','x','y','z','2',
								   '3','4','5','6','7','8','9');
  // Компоненты для RGB-цвета
	$figures = array('50','70','90','110',
									 '130','150','170','190','210');

	// Создаем пустое изображение
	$img = imagecreatetruecolor($width, $height);
	// Заливаем фон белым цветом
	$fon = imagecolorallocate($img, 255, 255, 255);
	imagefill($img, 0, 0, $fon);

	// СОЗДАНИЕ ЗАЩИТНОГО КОДА ///////////////////////////////////////////////////
	// Заливаем фон точками
	for($j=0; $j<$width; $j++){
		for($i=0; $i<$ro; $i++){
			// Формируем случайный цвет
			$color = imagecolorallocatealpha(
								$img,
								$figures[rand(0,count($figures)-1)],
								$figures[rand(0,count($figures)-1)],
								$figures[rand(0,count($figures)-1)],
								rand(10,30));
			// Устанавливаем случайную точку случайного цвета
			imagesetpixel($img,
								    rand(0,$width),
								    rand(0,$height),
								    $color);
		}
	}

  	// Защитный код
	$code="";
	// Накладываем защитный код
	for($i=0; $i<$sign; $i++){
		//Ориентир
		$h = 1;
		//Рисуем
		$color = imagecolorallocatealpha(
				     $img,
						 $figures[rand(0,count($figures)-1)],
						 $figures[rand(0,count($figures)-1)],
						 $figures[rand(0,count($figures)-1)],
						 rand(10,30));

		// Генерируем случайный символ
		$letter = $letters[rand(0,sizeof($letters)-1)];

		// Формируем координаты для вывода символа
		if(empty($x)) {			$x = $width*0.08;
		}
		else {			$x = $x + ($width*0.8)/$sign+rand(0,$width*0.01);
		}

		if($h == rand(1,2)){			$y = (($height*1)/4) + rand(0,$height*0.1);
		}
		else{			$y = (($height*1)/4) - rand(0,$height*0.1);
		}
		// Запоминаем символ в переменной $code
		$code .= $letter;
		// Изменяем регистр символа
		if($h == rand(0,1)) $letter = strtoupper($letter);
		// Выводим символ на изображение
		imagestring($img, 6 ,$x, $y, $letter, $color);
	}

	// Помещаем защитный код в сессию
	//$_SESSION['code'] = $code;
   $_SESSION['code'] = 1;
  // Выводим изображение
  header ("Content-type: image/jpeg");
  imagejpeg($img);


?>