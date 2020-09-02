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

<p>Количество сайтов, которые заблокированы пользователем: <b><?php echo $row[0]; ?></b>.</p>
<p>Сканирование данных сайтов не будет происходить до тех пор, пока в настройках сайта установлено значение "Заблокирован" = 1.</p>

<p><a href="?id_site=all">ЗАПУСК ВСЕХ САЙТОВ</a></p>
<table  border="1">
  <tr>
    <th>ID</th>
    <th>Наименование</th>
    <th>Ссылок</th>
    <th>Объявл.</th>
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
      <a href="link-list?view=lost&id_site=<?php echo $site_array[$i]['id_site'] ?>"><?php echo number_format($site_array[$i]['lost'], 0, ',', ' ') ?></a>
    </td>
    <td>
      <a href="view-data?id_site=<?php echo $site_array[$i]['id_site'] ?>"><?php echo number_format($site_array[$i]['database'], 0, ',', ' '); ?></a>
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

$lost_count = $lost_count + $site_array[$i]['lost'];
$data_count = $data_count + $site_array[$i]['database'];

} ?>

</table>

<?php }
else { ?>
<p>Сайты в БД отсутствуют</p>
<?php } ?>
</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
