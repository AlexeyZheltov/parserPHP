<div id="story">
<h1>Изменение меню сайта</h1>

<form method="post">
<table border = 1>
  <tr>
    <th>Название</th>
    <th>Ссылка</th>
    <th>Видимость</th>
    <th>Приоритет</th>
    <th>Удалить</th>
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

<p>Название страницы<input name="name_add" type="text" value=""></p>
<p>Ссылка на страницу<input name="link_add" type="text" value=""></p>
<p>Отобразить (1)/ скрыть (0) меню<input name="visible_add" type="text" value="1"></p>
<p>Приотите/очередность<input name="priority_add" type="text" value="<?php echo $stroka['priority'] + 1 ?>"></p>
<input type="submit" name="add" value="Добавить меню">

</form>
</div> <!-- End story -->
