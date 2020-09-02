<div id="story">
<?php if (!empty($links_array)) { ?>
<p>
<?php for ($p = 0; $p < $count_page; $p++) 
	{
		echo "["; ?>
		<a href="?view=<?php echo $_GET['view'] ?>&id_site=<?php echo $id_site ?>&page=<?php echo $p + 1 ?>"><?php echo $p + 1 ?></a>
		<?php echo "] ";
	}
?>
</p>

<p><?php 

	if ($_GET['view'] == "lost") 
	{
		?> Показаны оставшиеся ссылки сайта <?php 
	}
	elseif ($_GET['view'] == "run") 
	{
		?> Показаны просканированные ссылки сайта <?php 
	
	}
	else
	{
		?> Показаны все ссылки сайта <?php 		
	
	}


?></p>

<p><a href="<?php echo "link-list?id_site=" . $id_site . "&del=all" ?>">УДАЛИТЬ ВСЕ ССЫЛКИ</a></p>

<table  border="1">
  <tr>
    <th>№ п/п</th>
    <th>Ссылка</th>
    <th>Удал.</th>
	<th>Обновлена</th>
	<th>Блок</th>
  </td>
<?php for ($i = 0; $i < count($links_array); $i++) { ?>
  <tr>
    <td>
      <?php echo $links_array[$i]['id_link'] /* <?php echo $i + 1 + ($page - 1) * 5000 ?> */ ?> 
    </td>
    <td>
      <a href="<?php echo $links_array[$i]['link'] ?>"><?php echo $links_array[$i]['link'] ?></a>
    </td>
    <td>
      <a href="<?php echo "link-list?view=" . $_GET['view'] . "&id_site=" . $id_site . "&del=" . $links_array[$i]['id_link']?>">
        Удал.
      </a>
    </td>
    <td>
      <?php echo date('d.m.Y', $links_array[$i]['date_update']);  ?>
    </td>		
	<td>
		<?php if ($links_array[$i]['del'] == 0) { ?>
			<a href="<?php echo "link-list?view=" . $_GET['view'] . "&id_site=" . $id_site . "&block=" . $links_array[$i]['id_link']?>">
			Заблокировать
			</a>
		<?php } 
		else { ?>
			<a href="<?php echo "link-list?view=" . $_GET['view'] . "&id_site=" . $id_site . "&unblock=" . $links_array[$i]['id_link']?>">
			Разблокировать
			</a>		
		<?php } ?>
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
