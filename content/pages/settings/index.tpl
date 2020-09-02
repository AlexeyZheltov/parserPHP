<div id="story">
<p></p>
<form method="post">
<table  border="1">
  <tr>
    <th>ID</th>
    <th>Наименование настройки</th>
    <th>Значение</th>
  </tr>

<?php for ($i = 0; $i < count($settings_array); $i++) { ?>
  <tr>
    <td>


      <?php echo $settings_array[$i]['id']; ?>
    </td>
    <td>
		<input size="50" name="name_<?php echo $_REQUEST[$i]['id'] ?>"  type="text" value="<?php echo (!is_null($_REQUEST[$i]['name']))        ? $_REQUEST[$i]['name']        : "" ?>">
    </td>
    <td>
		<input size="150" name="value_<?php echo $_REQUEST[$i]['id'] ?>" type="text" value="<?php echo (!is_null($_REQUEST[$i]['value']))        ? $_REQUEST[$i]['value']        : "" ?>">
    </td>
  </tr>
<?php } ?>
</table>

<p><input name= "save" type="submit" value="Сохранить"></p>

<p><b>ОБНОВЛЕНИЕ ПАРСЕРА</b></p>

<p><input name= "update" type="submit" value="Обновить"></p>

</form>

</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->