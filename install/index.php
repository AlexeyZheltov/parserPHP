<?php
/*******************************************************************************
* УСТАНОВКА САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.2
* @date 21.07.2012
*******************************************************************************/

  // Устанавливаем уровень ошибок
  error_reporting(E_ALL);

  // Установка переменной доступа к файлам
  define('GVS_ACCSESS', true);

  // Подключаем SoftTime FrameWork
  require_once("class.config.install.php");

  if (empty($_POST)) {
    // Переменные формы
    if (empty($_REQUEST['server']))     $_REQUEST['server']     = "localhost";
    if (empty($_REQUEST['name']))     $_REQUEST['name']     = "";
    if (empty($_REQUEST['login']))    $_REQUEST['login']     = "root";
  }



  // Создаем форму
  $server = new field_text_english("server",
                         "Сервер БД",
                         true,
                         $_REQUEST['server']);

  $name = new field_text_english("name",
                         "Название Базы данных",
                         true,
                         $_REQUEST['name']);

  $login = new field_text_english("login",
                          "Login",
                          true,
                         $_REQUEST['login']);

  $password = new field_password("password",
                            "password",
                             false,
                             $_REQUEST['password']);

  $form = new form(array ("server"     => $server,
                          "name"     => $name,
                          "login"    =>  $login,
                          "password"  => $password),
                    "Создать базу данных",
                    "field");
////////////////////////////////////////////////////////////////////////////////

  if (!empty($_POST))
  {    // Проверяем корректность заполнения HTML-формы
    // и обрабатываем текстовые поля
    $error = $form->check();

    if (empty($error))
    {      $s_db_name = $_POST['name'];
      $db1   = @mysql_connect($_POST['server'], $_POST['login'], $_POST['password']);
      if($db1)
      {
        //получаем список баз данных
        $db_list = mysql_list_dbs($db1);
        while ($row = mysql_fetch_object($db_list))
        {
          $dbs[] = $row->Database;
        }

        //создаем базу, если ее нет
        if(!in_array($_POST['name'], $dbs))
        {
          $query = "CREATE DATABASE " . $_POST['name'];
          $res = mysql_query($query, $db1);
          if(mysql_affected_rows($db1) < 1)
            $error[] = "Ошибка создания БД";
        }
       // mysql_select_db($s_db_name, $db1);


        if (load_db_dump ("dump.sql", $_POST['server'], $_POST['login'], $_POST['password'], $s_db_name) == 1)
        {         $ok = "БД успешно создана";        }
      }
      else {      	$error[] = "Невозможно подключиться";      }    }
  }

   function load_db_dump($file, $sqlserver, $user, $pass, $name_db)
   {
     $sql=mysql_connect($sqlserver,$user,$pass);
     mysql_select_db($name_db);
     $a=file($file);
     foreach ($a as $n => $l) {
       $str .= $l;
       if (substr(trim($l),-1) == ";") {         $query[] = $str;
         $str = "";       }     }

     foreach ($query as $q) {
       mysql_query($q);
     }
     return 1;
   }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Установка сайта</title>
  <meta content="ru" http-equiv="Content-Language" />
  <meta http-equiv="Content-Type" content="text/html; utf-8" />
  <link href="/content/css/main/index.css" rel="stylesheet" type="text/css"/>
</head> <!-- End head-->
<p>Программа установки сайта</p>
<?php
  // Выводим сообщения об ошибках если они имеются
  if(!empty($error))
  {
    foreach($error as $err)
    {
      echo "<p><span style=\"color:red\">$err</span><br></p>";
    }
  }

  if(!empty($ok))
  {
      echo "<p><span style=\"color:green\">$ok</span><br></p>";
  }
    // Выводим HTML-форму
  $form->print_form();


?>