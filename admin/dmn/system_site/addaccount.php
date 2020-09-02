<?php


/******************************************************************************
* Добавление системного аккаунта
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 27.07.2011
*******************************************************************************/

  ////////////////////////////////////////////////////////////
  // 2006-2007 (C) IT-студия SoftTime (http://www.softtime.ru)
  ////////////////////////////////////////////////////////////

  // Выставляем уровень обработки ошибок
  Error_Reporting(E_ALL & ~E_NOTICE);

  // Установка переменной доступа к файлам
  define('GVS_ACCSESS', true);

  // Подключаем общий конфигурационный файл
  include str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']) . "/config.php";

  // Подключаем блок авторизации
  require_once("../utils/security_mod.php");

  // Подключаем SoftTime FrameWork
  require_once("../../config/class.config.dmn.php");

  // Подключаем генератор паролей
  require_once("../utils/utils.password.php");

  // Генерируем новый пароль
  $pass_example = generate_password(10);

  try
  {
    $name = new field_text_english("name",
                                  "Имя пользователя",
                                  true,
                                  $_POST['name']);
    $pass = new field_password("pass",
                               "Пароль",
                               true,
                               $_POST['pass'],
                               255,
                               41,
                               "",
                               "Напирмер, $pass_example");
    $passag = new field_password("passag",
                               "Повтор пароля",
                               true,
                               $_POST['passag'],
                               255,
                               41,
                               "",
                               "Напирмер, $pass_example");
    $page = new field_hidden_int("page",
                                 false,
                                 $_REQUEST['page']);

    $form = new form(array("name"   => $name,
                           "pass"   => $pass,
                           "passag" => $passag,
                           "page"   => $page),
                     "Добавить",
                     "field");

    if(!empty($_POST))
    {
      // Проверяем корректность заполнения HTML-формы
      // и обрабатываем текстовые поля
      $error = $form->check();

      if($form->fields['pass']->value != $form->fields['passag']->value)
      {
        $error[] = "Пароли не равны";
      }
      // Проверяем не регистрировался ли ранее пользователь
      // с запрашиваемым электронным адресом
      $query = "SELECT COUNT(*) FROM system_accounts
              WHERE name = '{$form->fields[name]->value}'";
      $acc = mysql_query($query);
      if(!$acc)
      {
        throw new ExceptionMySQL(mysql_error(),
                                 $query,
                                 "Ошибка добавления нового
                                  пользователя");
      }
      if(mysql_result($acc, 0))
      {
        $error[] = "Пользователь с именем
                   {$form->fields[name]->value} уже
                   зарегистрирован";
      }

      // Если ошибок нет добавляем нового пользователя
      if(empty($error))
      {
        $query = "INSERT INTO system_accounts
                  VALUES (NULL,
                         '{$form->fields[name]->value}',
                          MD5('{$form->fields[pass]->value}'))";
        if(!mysql_query($query))
        {
          throw new ExceptionMySQL(mysql_error(),
                                   $query,
                                  "Ошибка добавления нового
                                   пользователя");
        }

        // Перегружаем страницу, для сброса POST-данных
        header("Location: index.php?page=".$form->fields['page']->value);

        exit();
      }
    }

    // Включаем заголовок страницы
    $title = "Добавление аккаунта";
    $pageinfo = '<p class=help>Имя пользователя и пароль может содержать
                 только английские символы</p>';
    require_once("../utils/top.php");

    // Выводим сообщения об ошибках если они имеются
    if(!empty($error))
    {
      foreach($error as $err)
      {
        echo "<span style=\"color:red\">$err</span><br>";
      }
    }
    // Выводим HTML-форму
    $form->print_form();
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

  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>