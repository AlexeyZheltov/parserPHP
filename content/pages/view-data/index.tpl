<div id="story">
<?php if (!empty($data_array)) { ?>
<p>
<?php for ($p = 0; $p < $count_page; $p++) 
	{
		echo "["; ?>
		<a href="?id_site=<?php echo $id_site ?>&page=<?php echo $p + 1 ?>"><?php echo $p + 1 ?></a>
		<?php echo "] ";
	}
?>
</p>
<p>
		<a href="?id_site=<?php echo $id_site ?>&block=1">Заблокировать сайт</a>
</p>
<table  border="1">
  <tr>
    <th>№ п/п</th>
    <th>Наименование</th>
	<th>Модель</th>
	<th>Группа/Адрес</th>
	<th>Цена</th>
	<th>Дата объявления</th>
	<th>Ссылка</th>
	<th>Дата обновления</th>
  </td>
<?php for ($i = 0; $i < count($data_array); $i++) { ?>
  <tr>
    <td>
      <?php echo $i + 1 + ($page - 1) * 5000 ?>
    </td>
    <td style="word-wrap: break-word; max-width: 400px;">
      <?php echo $data_array[$i]['name'] ?>
    </td>
    <td style="word-wrap: break-word; max-width: 400px;">
      <?php echo $data_array[$i]['model'] ?>
    </td>
    <td style="word-wrap: break-word; max-width: 400px;">
      <?php echo $data_array[$i]['adres'] ?>
    </td>
    <td>
      <?php echo $data_array[$i]['price'] ?>
    </td>
    <td>
      <?php echo $data_array[$i]['date'] ?>
    </td>

    <td style="word-wrap: break-word; max-width: 600px;">
      <a href="<?php echo $data_array[$i]['link'] ?>"><?php echo $data_array[$i]['link'] ?></a>
    </td>
	<td style="word-wrap: break-word; max-width: 400px;">
      <?php echo $data_array[$i]['date_update'] ?>
    </td>
  </tr>
<?php } ?>
</table>
<?php }
else { ?>
<p>Ссылки отсутствуют</p>
<?php } ?>

</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
