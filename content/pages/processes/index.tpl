<div id="story">
<p></p>
<?php if (!empty($site_array)) { 

 $lost_count = 0;
 $data_count = 0;
 $run_count  = 0;
?>

<?php for ($p = 0; $p < $count_page; $p++) 
	{
		echo "["; ?>
		<a href="?page=<?php echo $p ?>"><?php echo $p ?></a>
		<?php echo "] ";
	}
?>

<p><a href="?id_site=all">ЗАПУСК ВСЕХ САЙТОВ</a></p>
<table  border="1">
  <tr>
    <th>ID</th>
    <th>Наименование</th>
    <th>Поиск</th>
	<th>Ссылок</th>
	<th>Объявлений</th>
    <th>Update</th>
    <th>Настройки</th>
    <th>Исключения</th>
  </td>
<?php for ($i = 0; $i < count($site_array); $i++) { ?>
  <tr
    <?php
    // Если выполняется процесс обновления выделяем серым
    if ($site_array[$i]['run'] == 1) { ?>
      bgcolor="#C0C0C0"
    <?php } ?>
    <?php
    // Если не требует обновления - зеленым
    if ($site_array[$i]['update_now'] == 0) { ?>
      bgcolor="#80FF80"
    <?php }
   // если заблокирован
    elseif ($site_array[$i]['block'] == 1) { ?>
      bgcolor="#D3FEDF"
    <?php }
    // если требует обновления и не обновляется
    elseif ($site_array[$i]['run'] != 1) { ?>
      bgcolor="#FF8080"
    <?php } ?>
    >
    <td>
      <?php echo $site_array[$i]['id_site'] ?>
    </td>
    <td>
      <a href="<?php echo "/view-site?id=" . $site_array[$i]['id_site'] ?>"><?php echo $site_array[$i]['link'] ?></a>
    </td>
    <td>
      <?php
       if ($site_array[$i]['run'] == 1)
       {         echo '<a href="?stop_site=' . $site_array[$i]['id_site'] . '">Стоп</a>';       }
       else
       {         echo '<a href="?id_site=' . $site_array[$i]['id_site'] . '">Пуск</a>';       }
       ?>
    </td>
	<td>
      <?php echo $site_array[$i]['lost']; ?>
    </td>
	<td>
      <?php echo $site_array[$i]['database']; ?>
    </td>
	<td>
      <?php echo $site_array[$i]['last_update']; ?>
    </td>
    <td>
      <a href="<?php echo "site-edit?id_site=" . $site_array[$i]['id_site']?>">Изменить</a>
    </td>
    <td>
      <a href="<?php echo "block-list?id_site=" . $site_array[$i]['id_site']?>">Изменить</a>
    </td>
  </tr>
<?php 



} ?>

</table>

<?php }
else { ?>
<p>Сайты в БД отсутствуют</p>
<?php } ?>
</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
