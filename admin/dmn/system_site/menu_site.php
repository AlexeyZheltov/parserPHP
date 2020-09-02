<?php
/*******************************************************************************
* ИЗМЕНЕНИЕ МЕНЮ САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.2
* @date 25.07.2012
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

  // Данные переменные определяют название страницы и подсказку.
  $title = 'Меню сайта';
  $pageinfo = '<p class=help>Здесь можно добавить новый пункт меню,
               отредактировать или удалить
               существующий.</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");
////////////////////////////////////////////////////////////////////////////////

  // Удаление меню
  if (!empty($_GET['del']))
  {
    $query = "DELETE FROM site_menu
              WHERE id = '" . $_GET['del'] . "'";
    $result = mysqlQuery($query);
  }
////////////////////////////////////////////////////////////////////////////////

  // Сохранение изменений
  if (!empty($_POST['save']))
  {
    // Получаем меню
    $query = "SELECT * FROM site_menu
              ORDER BY priority";
    $result = mysqlQuery($query);

    while ($res_menu = mysql_fetch_assoc($result))
    {
      $SITE['menu'][] = $res_menu;
    }

    $i = 0;

    foreach ($SITE['menu'] as $stroka)
    {
      $i++;
      $query = "UPDATE site_menu
                SET name     = '" . $_POST['cell_' . $i . '_2'] . "',
                    link     = '" . $_POST['cell_' . $i . '_3'] . "',
                    visible  = '" . $_POST['cell_' . $i . '_4'] . "',
                    priority = '" . $_POST['cell_' . $i . '_5'] . "'
                WHERE id = '" . $stroka['id'] . "'";
      $result = mysqlQuery($query);
    }

    unset ($SITE['menu']);
  }
////////////////////////////////////////////////////////////////////////////////

  // Получаем меню
  $query = "SELECT * FROM site_menu
            ORDER BY priority";
  $result = mysqlQuery($query);

  while ($res_menu = mysql_fetch_assoc($result))
  {
    $SITE['menu'][] = $res_menu;
  }
////////////////////////////////////////////////////////////////////////////////
?>
<p><a href="menu_add.php">Добавить новый пункт меню</a></p>
<form method="post">
 <table width="100%"
        class="table"
        border="0"
        cellpadding="0"
        cellspacing="0">
  <tr class="header" align="center">
    <td>Название</td>
    <td>Ссылка</td>
    <td>Видимость</td>
    <td>Приоритет</td>
    <td>Действия</td>
  </tr>

  <?php $i = 0; $j = 0; ?>
  <?php foreach ($SITE['menu'] as $stroka)
  {
  $i++?>
  <tr>
    <?php foreach ($stroka as $stolbec)
    {
      $j++;
      if ($j == 1) { continue; }
      ?>
    <td><input name="cell_<?php echo $i?>_<?php echo $j ?>" value="<?php echo $stolbec ?>"></td>
    <?php } ?>
    <td><a href="?del=<?php echo $stroka['id'] ?>">Удалить</a></td>
  </tr>
  <?php $j = 0;
  } ?>

</table>
<p></p>
<input type="submit" name="save" value="Сохранить изменения">
<?php
  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>
