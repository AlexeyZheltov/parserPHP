<div id="story">
<h1>Изменение разделов сайта</h1>

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
  </p>
<?php } ?>
<form method="post">
  <?php if (!empty($SITE['current'])) { ?>
<table border = 1>
  <tr>
    <th>Название</th>
    <th>Видимость</th>
    <th>keywords</th>
    <th>Заголовок</th>
    <th>Шаблон</th>
    <th>Контент</th>
    <th>Програмный модуль</th>
    <th></th>
    <th></th>
  </tr>


   <?php foreach ($SITE['current'] as $stroka)
   { ?>
   <tr>
     <td><input name="name_<?php echo $stroka['id'] ?>" value="<?php echo $stroka['name'] ?>"></td>
     <td><input name="visible_<?php echo $stroka['id'] ?>" value="<?php echo $stroka['visible'] ?>"></td>
     <td><input name="keywords_<?php echo $stroka['id'] ?>" value="<?php echo $stroka['keywords'] ?>"></td>
     <td><input name="title_<?php echo $stroka['id'] ?>" value="<?php echo $stroka['title'] ?>"></td>
     <td><input name="template_<?php echo $stroka['id'] ?>" value="<?php echo $stroka['template'] ?>"></td>
     <td><input name="content_<?php echo $stroka['id'] ?>" value="<?php echo $stroka['content'] ?>"></td>
     <td><input name="module_<?php echo $stroka['id'] ?>" value="<?php echo $stroka['module'] ?>"></td>

     <td>
     <?php if ($stroka['id'] != 1) { ?>
       <a href="?del=<?php echo $stroka['id'] ?>">Х</a>
     <?php } ?>
     </td>

     <td><a href="?current=<?php echo $stroka['id'] ?>">-&gt;</a></td>
   </tr>
   <?php } ?>


</table>


<p></p>
<input type="submit" name="save" value="Сохранить изменения">
<?php } ?>

<?php if ($SITE['previous']['id'] != 0) {  ?>
  <p>Добавить страницу в текущий раздел</p>
  <p>Название страницы<input name="name_add" type="text" value=""></p>
  <p>Отобразить (1)/ скрыть (0) страницу<input name="visible_add" type="text" value="1"></p>
  <p>keywords<input name="keywords_add" type="text" value="<?php echo $SITE['previous']['keywords'] ?>"></p>
  <p>Шаблон<input name="template_add" type="text" value="<?php echo $SITE['previous']['template'] ?>"></p>
  <p>Контент<input name="content_add" type="text" value="<?php echo $SITE['previous']['content'] ?>"></p>
  <p>Програмный модуль<input name="module_add" type="text" value="<?php echo $SITE['previous']['module'] ?>"></p>

  <input type="submit" name="add" value="Добавить раздел">
<?php } ?>
</form>
</div> <!-- End story -->
