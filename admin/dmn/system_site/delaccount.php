<?php
/******************************************************************************
* Удаление системного аккаунта
* @author Желтов Алексей
* @copyright ©
* @version 1.0.1
* @date 27.07.2011
*******************************************************************************/

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

  // Проверяем GET-параметр, предотвращая SQL-инъекцию
  $_GET['id_account'] = intval($_GET['id_account']);

  try
  {
    // Проверяем не удаляется ли последний аккаунт -
    // если он будет удалён в систему нельзя будет войти
    $query = "SELECT COUNT(*) FROM system_accounts";
    $acc = mysql_query($query);
    if(!$acc)
    {
      throw new ExceptionMySQL(mysql_error(),
                               $query,
                              "Ошибка удаления
                               пользователя");
    }
    if(mysql_result($acc, 0) > 1)
    {
      $query = "DELETE FROM system_accounts
                WHERE id_account=".$_GET['id_account'];
      if(mysql_query($query))
      {
        header("Location: index.php?page=".$_GET['page']);
      }
      else
      {
        throw new ExceptionMySQL(mysql_error(),
                                 $query,
                                "Ошибка удаления
                                 пользователя");
      }
    }
    else
    {
      throw new Exception("Нельзя удалить
                           единственный аккаунт");
    }
  }
  catch(ExceptionMySQL $exc)
  {
    require("../utils/exception_mysql.php");
  }
  catch(Exception $exc)
  {
    require("../utils/exception.php");
  }
?>