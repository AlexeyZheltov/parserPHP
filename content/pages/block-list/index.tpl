<div id="story">
<p>Знак % означает, что вместо него может идти любой набор символов</p>
<form method="post">

<p></p>
<table  border="1">
  <tr>
    <th>Наименование</th>
    <th>Значение</th>
  </td>
  <tr>
    <td>Id сайта (0-для всех)</td>
    <td><input name="id_site" type="text" value="<?php echo $_GET['id_site']; ?>"></td>
  </tr>
  <tr>
    <td>Исключение</td>
    <td><input name="like_str" type="text"></td>
  </tr>
</table>

<br />
<input type="submit" value="Добавить" name="add_block">
<p></p>
<input type="submit" value="Очистить БД" name="clear">
<p></p>
<?php if (!empty($blocks_array)) { ?>
<table  border="1">
  <tr>
    <th>№ п/п</th>
    <th>Id исключения</th>
    <th>Ссылка содержит</th>
    <th>Удалить</th>
  </td>
<?php for ($i = 0; $i < count($blocks_array); $i++) { ?>
  <tr>
    <td>
      <?php echo $i + 1 ?>
    </td>
    <td>
      <?php echo $blocks_array[$i]['id_block'] ?>
    </td>
    <td>
      <?php echo $blocks_array[$i]['like_str'] ?>
    </td>
    <td>
      <a href="<?php echo "?id_site=" . $id_site . "&del=" . $blocks_array[$i]['id_block']?>">Удалить</a>
    </td>
  </tr>
<?php } ?>
</table>
<?php }
else { ?>
<p>Ссылки отсутствуют</p>
<?php } ?>


</form>

</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
