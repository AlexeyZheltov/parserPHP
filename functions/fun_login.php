<?php




	// Если пользователь уже авторизован переносим его на страницу с профилем
	if (!empty($_SESSION['user_id'])){
		header("Location: users/profile.php");
		exit();
	}
	// Действия при нажатии кнопки регистрация
	if ($_POST['registr']==true){
			header("Location: registration.php");
			exit();
	}
	// Действия при нажатии кнопки вход
	if ($_POST['enter']==true){
		// Предотвращение SQL-инъекции
		if (!get_magic_quotes_gpc()){
			$login=mysql_escape_string($_POST['login']);
		}
		else{
			$login=$_POST['login'];
		}
		$password=md5($_POST['password']);
		// Запрос на поиск имени и пароля пользователя
		$query="SELECT id FROM users WHERE login='$login' AND password='$password' LIMIT 1";
		$result=mysql_query($query) or die (mysql_error());
		// Если запрос выполнен
		if (mysql_num_rows($result)){
			$res=mysql_fetch_array($result);
			// Запоминаем ID пользователя
			$_SESSION['user_id']=$res['id'];
			header("Location: users/profile.php");
			exit();
		}
		// Если пользователь ошибся
		else{
			$_SESSION['error_enter']="Неправильный логин или пароль";
			header("Location: login.php");
			exit();
		}
	}
	//ДОБАВИТЬ ИЗОБРАЖЕНИЕ ОТ БОТОВ
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Студенческая ВОЛНА - ВХОД</title>
	<meta content="ru" http-equiv="Content-Language" />
	<meta content="text/html; charset=windows-1251" http-equiv="Content-Type" />

	<link href="css/login.css" rel="stylesheet" type="text/css" />
	<link href="images/favicon.ico" rel=" icon" type="image/x-icon" />

	<link href="css/studwave.css" rel="stylesheet" type="text/css"/>

	<style type="text/css">
	</style>
</head>

<body style="margin: 0px">
	<div id="container">
		<div id="top">
			<img alt="Студенческая ВОЛНА" height="100" src="images/Banner_800x100_end.jpg" width="800" />
		</div> <!-- End top-->

		<div id="leftconteiner">

			<div id="menu">
				<ul>
					<li><a style="width: 93px" href="index.php">Главная</a></li>
					<li><a style="width: 80px" href="vuzi2.php">Вузам</a></li>
					<li><a style="width: 135px" href="">Абитуриентам</a></li>
					<li><a style="width: 122px" href="">Студентам</a></li>
					<li><a style="width: 150px" href="">Рекламодателям</a></li>
				</ul>
			</div> <!-- End menu-->

			<div id="story">

				<h1>Вход на сайт</h1>
					<form method="post">
						<p><span>Логин/Email:</span><input name="login" type="text"/></p>
						<p><span>Пароль:</span><input name="password" type="password"/></p>
						<p style="text-align:center">
							<input name="enter" type="submit" value="Войти" style="width: 65px"/>
							<a href="registration.php">
							<input name="registr" type="button" value="Регистрация" style="width: 100px" onclick="FP_goToURL(/*href*/'registration.php')"  /></a>
						</p>
					</form>

					<p style="text-align:center"> <?php echo $_SESSION['error_enter']; unset($_SESSION['error_enter']) ?> </p>
			</div> <!-- End story -->
		</div><!-- End leftconteiner-->

		<div id="sidebar" style="height: 195px">

				<p>Место для рекламы </p>

		</div> <!-- End sidebar -->

		<div class="clear">&nbsp;</div>
		<div id="footer">Сopyright Студенческая Волна © 2010
		</div> <!-- End footer -->

	</div> <!-- End container -->
</body>
</html>
