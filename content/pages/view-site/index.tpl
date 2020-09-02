<div id="story">
<?php if (!empty($site_array)) { ?>
<p>

</p>
<table  border="1">
  <tr>
    <th>Id сайта</th>
    <td>
      <?php echo $site_array['id_site'] ?>
    </td>	
  </tr>
  <tr>
    <th>Название сайта</th>
    <td>
		<a href="<?php echo $site_array['link'] ?>"><?php echo $site_array['link'] ?></a>
    </td>	
  </tr>
  <tr>
    <th>Дата последнего обновления сайта</th>
    <td>
      <?php echo $site_array['last_update'] ?>
    </td>	
  </tr>
  <tr>
    <th>Статус</th>
    <td>
      <?php echo $site_array['status'] ?>
    </td>	
  </tr>
  
 <?php if ($site_array['run'] == 1) { ?> 
  <tr>
    <th>Количество отсканированных ссылок</th>
    <td>
      <?php echo $site_array['prosses'] ?>
    </td>	
  </tr>
  <tr>
    <th>Последняя просканированная ссылка</th>
    <td>
		<a href="<?php echo $site_array['last_link'] ?>"><?php echo $site_array['last_link'] ?></a>
    </td>	
  </tr>
  <tr>
    <th>Дата последнего прохождения ссылки</th>
    <td>
      <?php echo $site_array['time_last_link'] ?>
    </td>	
  </tr>
  <tr>
    <th>Количество неотсканированных ссылок</th>
    <td>
      <?php echo $site_array['lost'] ?>
    </td>	
  </tr>
  <?php } ?> 
  <tr>
    <th>Общее количество ссылок сайта</th>
    <td>
      <?php echo $site_array['all'] ?>
    </td>	
  </tr>
  <tr>
    <th>Количество обявлений сайта</th>
    <td>
      <?php echo $site_array['database'] ?>
    </td>	
  </tr> 
  
  <tr>
    <th>Из них новых объявлений</th>
    <td>
     
    </td>	
  </tr>  

  <tr>
    <th>Последнее полученное объявление</th>
    <td>
		<a href="<?php echo $site_array['date_link'] ?>"><?php echo $site_array['date_link'] ?></a>
    </td>	
  </tr>
  
  <tr>
    <th>Последнее объявление получено в</th>
    <td>
      <?php echo $site_array['date_update'] ?>
    </td>	
  </tr>

  
  <tr>
    <th>Количество заблокированных в результате обработки</th>
    <td>
      <?php echo $site_array['delete'] ?>
    </td>	
  </tr>  
</table>
<?php }
else { ?>
<p>Ссылки отсутствуют</p>
<?php } ?>

</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
