<div id="story">
<p></p>
<?php if (!empty($site_array)) { 

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

<p>Количество сайтов, которые требуют обновления: <b><?php echo $row[0]; ?></b>.</p>

<p><a href="?id_site=all">Запуск обновления</a>    <a href="?check_tegs=1">Проверить теги</a></p>
<table  border="1">
  <tr>
    <th>ID</th>
    <th>Наименование</th>
	<th>Обновлено</th>
	<th>Всего</th>
    <th>Update</th>
	<th>Тэги</th>
    <th>Настройки</th>
    <th>Исключения</th>
	<th>Заблокировать</th>
  </td>
<?php for ($i = 0; $i < count($site_array); $i++) { ?>
  <tr>
    <td><?php echo $site_array[$i]['id_site'] ?></td>
    <td>
      <a href="<?php echo "/view-site?id=" . $site_array[$i]['id_site'] ?>"><?php echo $site_array[$i]['link'] ?></a>
    </td>
    
    <td>
      <a href="link-list?view=lost&id_site=<?php echo $site_array[$i]['id_site'] ?>"><?php echo number_format($site_array[$i]['prosses'], 0, ',', ' ') ?></a>
    </td>
	
    <td>
      <a href="view-data?id_site=<?php echo $site_array[$i]['id_site'] ?>"><?php echo number_format($site_array[$i]['all'], 0, ',', ' '); ?></a>
    </td>
    <td>
      <?php echo $site_array[$i]['last_update']; ?>
    </td>
	<td>
		<?php 
		if ($site_array[$i]['teg_correct'] == 0) 
		{
			echo "Теги не проверены";
		}
		elseif ($site_array[$i]['teg_correct'] == 1) 
		{
			echo "Теги корректны";
		}
		else
		{
			echo "Теги не корректны. Сканирование остановлено";
		}
		?>
    </td>
    <td>
      <a href="<?php echo "site-edit?id_site=" . $site_array[$i]['id_site']?>">Изменить</a>
    </td>
    <td>
      <a href="<?php echo "block-list?id_site=" . $site_array[$i]['id_site']?>">Изменить</a>
    </td>
    <td>
      <a href="<?php echo "?block=" . $site_array[$i]['id_site']?>">Заблокировать</a>
    </td>
  </tr>
<?php 

$data_count = $data_count + $site_array[$i]['all'];
$run_count = $run_count + $site_array[$i]['prosses'];
} ?>
  <tr>
    <th></th>
    <th>ИТОГО</th>

    <th><?php echo number_format($run_count, 0, ',', ' '); ?></th>
    <th><?php echo number_format($data_count, 0, ',', ' '); ?></th>
    <th></th>
	<th><?php echo number_format($run_count*100 / $data_count, 2, ',', ' ') . "%"; ?></th>

    <th></th>
	 <th></th> <th></th>
  </tr>
</table>

<?php }
else { ?>
<p>Сайты не требуют обновления</p>
<?php } ?>
</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
