<?php
/*******************************************************************************
* ИЗМЕНЕНИЕ РАЗДЕЛОВ САЙТА
* @author Желтов Алексей
* @copyright ©
* @version 1.0.2
* @date 27.07.2012
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
  $title = 'Список разделов сайта';
  $pageinfo = '<p class=help>Здесь отображаются все разделы и страницы сайта.</p>';

  // Включаем заголовок страницы
  require_once("../utils/top.php");

  // По умолчанию главный раздел
  if (is_null($_GET['current'])) { $_GET['current'] = 1; }

  // Сохранение изменений
  if (!empty($_POST['save']))
  {
    // Получаем список разделов
    $query = "SELECT * FROM site_sections
              WHERE previous = '" . $_GET['current'] . "'";

    $result = mysqlQuery($query);

    while ($res_sections = mysql_fetch_assoc($result))
    {
      $SITE['current'][] = $res_sections;
    }

    $i = 0;

    foreach ($SITE['current'] as $stroka)
    {
      $query = "UPDATE site_sections
                SET name     = '" . $_POST['name_'     . $stroka['id']] . "',
                    visible  = '" . $_POST['visible_'  . $stroka['id']] . "',
                    keywords = '" . $_POST['keywords_' . $stroka['id']] . "',
                    title    = '" . $_POST['title_'    . $stroka['id']] . "',
                    template = '" . $_POST['template_' . $stroka['id']] . "',
                    content  = '" . $_POST['content_'  . $stroka['id']] . "',
                    module   = '" . $_POST['module_'   . $stroka['id']] . "'
                WHERE id = '" . $stroka['id'] . "'";
      $result = mysqlQuery($query);
    }

    unset ($SITE['current']);
  }
////////////////////////////////////////////////////////////////////////////////

   // Удаление раздела
  if (!empty($_GET['del']))
  {
    $query = "DELETE FROM site_sections
              WHERE id       = '" . $_GET['del'] . "'
               OR  previous = '" . $_GET['del'] . "'";
    $result = mysqlQuery($query);
  }
////////////////////////////////////////////////////////////////////////////////

  // Получаем разделы в текущем
  $query = "SELECT * FROM site_sections
            WHERE previous = '" . $_GET['current'] . "'";
  $result = mysqlQuery($query);

  while ($res_sections = mysql_fetch_assoc($result))
  {

    $SITE['current'][] = $res_sections;
  }
////////////////////////////////////////////////////////////////////////////////

  // Данные текущего раздела
  $current = $_GET['current'];
  $query = "SELECT * FROM site_sections
            WHERE id = '$current'
            LIMIT 1";
  $result = mysqlQuery($query);

  while ($res_sections = mysql_fetch_assoc($result))
  {
    $SITE['previous'] = $res_sections;
  }
////////////////////////////////////////////////////////////////////////////////

  // Дерево разделов
  $i = 0;
  $previous = $current;
  while ($previous != 0) {
    $query = "SELECT name, previous FROM site_sections
          WHERE id = '$previous'
          LIMIT 1";
    $result = mysqlQuery($query);

    if ($res_sections = mysql_fetch_assoc($result))
    {
      $sections[$i] = $res_sections;
      $previous = $res_sections['previous'];
      $i++;
    }
    else
    {
      $previous = 0;
    }
  }

  if (!empty($sections)) {
  $sections = array_reverse($sections);
  $sections[0]['name'] = substr(GVS_HOST, 0, -1);
  $str = "";
    foreach ($sections as $section)
    {
      $str .= $section['name'] . "/";
    }
  }
////////////////////////////////////////////////////////////////////////////////

?>



<h2>Раздел
  <?php if (isset($SITE['previous']['previous'])) { ?>
    <a href="<?php echo $str ?>"><?php echo $str ?></a>
  <?php }
  else { ?>
  Корень сайта
  <?php } ?></h2>

<?php if (isset($SITE['previous']['previous'])) { ?>
  <p>
  <a href="?current=<?php echo $SITE['previous']['previous'] ?>">Перейти на предыдущий</a>
  &nbsp;&nbsp;&nbsp;
  <a href="section_add.php?id=<?php echo $SITE['previous']['id'] ?>">Добавить раздел</a>
  </p>
<?php } ?>

  <?php if (!empty($SITE['current'])) { ?>
 <table width="100%"
        class="table"
        border="0"
        cellpadding="0"
        cellspacing="0">
  <tr class="header" align="center">
    <td>Название</td>
    <td>Видимость</td>
    <td>Заголовок</td>
    <td>Перейти</td>
    <td>Изменить</td>
    <td>Удалить</td>
  </tr>


   <?php foreach ($SITE['current'] as $stroka)
   { ?>
   <tr>
     <td><?php echo $stroka['name'] ?></td>
     <td><?php echo $stroka['visible'] ?></td>
     <td><?php echo $stroka['title'] ?></td>
     <td><a href="?current=<?php echo $stroka['id'] ?>">&gt;</a></td>
     <td><a href="section_edit.php?id=<?php echo $stroka['id'] ?>">Изменить</td>
     <td>
     <?php if ($stroka['id'] != 1) { ?>
       <a href="?del=<?php echo $stroka['id'] ?>">Х</a>
     <?php } ?>
     </td>


   </tr>
   <?php } ?>


</table>


  <p></p>

  <?php }
  else
  {
    echo "<h3>Подразделы отсутствуют</h3>";
  }
  // Включаем завершение страницы
  require_once("../utils/bottom.php");
?>
