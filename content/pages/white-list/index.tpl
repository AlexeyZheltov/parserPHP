<div id="story">
<p>Знак % означает, что вместо него может идти любой набор символов</p>
<form method="post">
<?php if (!empty($whites_array)) { ?>
<table  border="1">
  <tr>
    <th>№ п/п</th>
    <th>Id исключения</th>
    <th>Ссылка содержит</th>
    <th>Удалить</th>
  </td>
<?php for ($i = 0; $i < count($whites_array); $i++) { ?>
  <tr>
    <td>
      <?php echo $i + 1 ?>
    </td>
    <td>
      <?php echo $whites_array[$i]['id_white'] ?>
    </td>
    <td>
      <?php echo $whites_array[$i]['like_str'] ?>
    </td>
    <td>
      <a href="<?php echo "?id_site=" . $id_site . "&del=" . $whites_array[$i]['id_white']?>">Удалить</a>
    </td>
  </tr>
<?php } ?>
</table>
<?php }
else { ?>
<p>Ссылки отсутствуют</p>
<?php } ?>

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
<input type="submit" value="Добавить" name="add_white">
<p></p>
<input type="submit" value="Очистить БД" name="clear">
</form>

</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
