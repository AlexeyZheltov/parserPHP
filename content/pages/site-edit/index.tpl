<div id="story">
<form method="post">
<p><b><a href="">Инструкция!!!!</a></b></p>
<p>Ссылка для определения тегов</p>
<input name="url" type="text" value="<?php echo (!empty($_REQUEST['url'])) ? $_REQUEST['url'] : "" ?>"><input type="submit" name="check" value="Проверить"><input type="submit" name="set" value="Определить"><input name= "save" type="submit" value="Сохранить">
<p>Автотеги</p>
<table  border="1">
  <tr>
    <th>Тег</th>
    <th>Значение</th>
    <th>Начало</th>
    <th>Конец</th>
    <th>Подтвердить</th>
  </tr>
<?php if (!empty($count_coding_teg)) {for ($i = 1; $i <= $count_coding_teg; $i++) { ?>
   <td>Кодировка <?php echo $i ?></td>
   <td><input name="coding_teg_value<?php echo $i ?>" type="text" value="<?php echo $coding[$i]['value'] ?>"></td>
   <td><input name="coding_teg_start<?php echo $i ?>" type="text" value="<?php echo $coding[$i]['start'] ?>"></td>
   <td><input name="coding_teg_end<?php echo $i ?>" type="text" value="<?php echo $coding[$i]['end'] ?>"></td>
   <td><input name="coding_ok<?php echo $i ?>" type="submit" value="Ok"></td>
  </tr>
<?php }
} ?>
<?php if (!empty($count_name_teg)) {for ($i = 1; $i <= $count_name_teg; $i++) { ?>
   <td>Наименование <?php echo $i ?></td>
   <td><?php echo $name[$i]['value'] ?></td>
   <td><input name="name_teg_start<?php echo $i ?>" type="text" value="<?php echo $name[$i]['start'] ?>"></td>
   <td><input name="name_teg_end<?php echo $i ?>" type="text" value="<?php echo $name[$i]['end'] ?>"></td>
   <td><input name="name_ok<?php echo $i ?>" type="submit" value="Ok"></td>
  </tr>
<?php }
} ?>
<?php if (!empty($count_price_teg)) {for ($i = 1; $i <= $count_price_teg; $i++) { ?>
   <td>Цена <?php echo $i ?></td>
   <td><?php echo $price[$i]['value'] ?></td>
   <td><input name="price_teg_start<?php echo $i ?>" type="text" value="<?php echo $price[$i]['start'] ?>"></td>
   <td><input name="price_teg_end<?php echo $i ?>" type="text" value="<?php echo $price[$i]['end'] ?>"></td>
   <td><input name="price_ok<?php echo $i ?>" type="submit" value="Ok"></td>
  </tr>
<?php }
} ?>
<?php if (!empty($count_adress_teg)) {for ($i = 1; $i <= $count_adress_teg; $i++) { ?>
   <td>Адрес <?php echo $i ?></td>

   <td><?php echo $adress[$i]['value'] ?></td>
   <td><input name="adress_teg_start<?php echo $i ?>" type="text" value="<?php echo $adress[$i]['start'] ?>"></td>
   <td><input name="adress_teg_end<?php echo $i ?>" type="text" value="<?php echo $adress[$i]['end'] ?>"></td>
   <td><input name="adress_ok<?php echo $i ?>" type="submit" value="Ok"></td>
  </tr>
<?php }
} ?>

 </table>
<b><p>Основные данные сайта</p></b>
<table  border="1">
  <tr>
    <th>Ссылка</th>
    <th>Идет сканирование</th>
    <th>Остановка = 1</th>
    <th>Кодировка</th>
    <th>Заблокирован</th>
    <th>Последнее обновление</th>
    <th>Задержка времени</th>
	<th>Проверка тегов</th>

  </tr>

  <tr>
    <td><input name="link"         type="text" value="<?php echo (!is_null($_REQUEST['link']))        ? $_REQUEST['link']        : "" ?>"></td>
    <td><input name="run"          type="text" value="<?php echo (!is_null($_REQUEST['run']))         ? $_REQUEST['run']         : "" ?>"></td>
    <td><input name="stop_scan"    type="text" value="<?php echo (!is_null($_REQUEST['stop_scan']))   ? $_REQUEST['stop_scan']   : "" ?>"></td>
    <td><input name="coding"       type="text" value="<?php echo (!is_null($_REQUEST['coding']))      ? $_REQUEST['coding']      : "" ?>"></td>
    <td><input name="block"        type="text" value="<?php echo (!is_null($_REQUEST['block']))       ? $_REQUEST['block']       : "" ?>"></td>
    <td><input name="last_update"  type="text" value="<?php echo (!is_null($_REQUEST['last_update'])) ? $_REQUEST['last_update'] : "" ?>"></td>
    <td><input name="delay"        type="text" value="<?php echo (!is_null($_REQUEST['delay']))       ? $_REQUEST['delay']       : "" ?>"></td>
	<td><input name="teg_correct"  type="text" value="<?php echo (!is_null($_REQUEST['teg_correct'])) ? $_REQUEST['teg_correct'] : "" ?>"></td>

  </tr>
</table>


<b><p>Основные теги</p></b>
<table  border="1">
  <tr>
    <th>Тег</th>
    <th>Цена <input name="price_add"     type="submit" value="+"></th>
    <th>Модель <input name="model_add"     type="submit" value="+"></th>
    <th>Дата предложения <input name="date_add"      type="submit" value="+"></th>
    <th>Наименование <input name="name_add"      type="submit" value="+"></th>
    <th>Дата выпуска <input name="date_made_add" type="submit" value="+"></th>
    <th>Состояние <input name="state_add"     type="submit" value="+"></th>
  </tr>

  <tr>
    <td>Начало</td>
    <td><input name="price_start"     type="text" value="<?php echo (!is_null($_REQUEST['price_start']))     ? $_REQUEST['price_start']     : "" ?>"></td>
    <td><input name="model_start"     type="text" value="<?php echo (!is_null($_REQUEST['model_start']))     ? $_REQUEST['model_start']     : "" ?>"></td>
    <td><input name="date_start"      type="text" value="<?php echo (!is_null($_REQUEST['date_start']))      ? $_REQUEST['date_start']      : "" ?>"></td>
    <td><input name="name_start"      type="text" value="<?php echo (!is_null($_REQUEST['name_start']))      ? $_REQUEST['name_start']      : "" ?>"></td>
    <td><input name="date_made_start" type="text" value="<?php echo (!is_null($_REQUEST['date_made_start'])) ? $_REQUEST['date_made_start'] : "" ?>"></td>
    <td><input name="state_start"     type="text" value="<?php echo (!is_null($_REQUEST['state_start']))     ? $_REQUEST['state_start']     : "" ?>"></td>
  </tr>

  <tr>
    <td>Конец</td>
    <td><input name="price_end"     type="text" value="<?php echo (!is_null($_REQUEST['price_end']))     ? $_REQUEST['price_end']     : "" ?>"></td>
    <td><input name="model_end"     type="text" value="<?php echo (!is_null($_REQUEST['model_end']))     ? $_REQUEST['model_end']     : "" ?>"></td>
    <td><input name="date_end"      type="text" value="<?php echo (!is_null($_REQUEST['date_end']))      ? $_REQUEST['date_end']      : "" ?>"></td>
    <td><input name="name_end"      type="text" value="<?php echo (!is_null($_REQUEST['name_end']))      ? $_REQUEST['name_end']      : "" ?>"></td>
    <td><input name="date_made_end" type="text" value="<?php echo (!is_null($_REQUEST['date_made_end'])) ? $_REQUEST['date_made_end'] : "" ?>"></td>
    <td><input name="state_end"     type="text" value="<?php echo (!is_null($_REQUEST['state_end']))     ? $_REQUEST['state_end']     : "" ?>"></td>
  </tr>
  <tr>
</table>

<b><p>Дополнительные теги</p></b>
<table  border="1">
  <tr>
    <th>Тег</th>
    <th>Адрес <input name="adres_add"        type="submit" value="+"></th>
    <th>Комментарий <input name="komment_add"      type="submit" value="+"></th>
    <th>Контактное имя <input name="kontact_name_add" type="submit" value="+"></th>
    <th>Телефон <input name="tel_add"          type="submit" value="+"></th>
    <th>Актуальность <input name="topicality_add"   type="submit" value="+"></th>
  </tr>

  <tr>
    <td>Начало</td>
    <td><input name="adres_start"        type="text" value="<?php echo (!is_null($_REQUEST['adres_start']))        ? $_REQUEST['adres_start']        : "" ?>"></td>
    <td><input name="komment_start"      type="text" value="<?php echo (!is_null($_REQUEST['komment_start']))      ? $_REQUEST['komment_start']      : "" ?>"></td>
    <td><input name="kontact_name_start" type="text" value="<?php echo (!is_null($_REQUEST['kontact_name_start'])) ? $_REQUEST['kontact_name_start'] : "" ?>"></td>
    <td><input name="tel_start"          type="text" value="<?php echo (!is_null($_REQUEST['tel_start']))          ? $_REQUEST['tel_start']          : "" ?>"></td>
    <td><input name="topicality_start"   type="text" value="<?php echo (!is_null($_REQUEST['topicality_start']))   ? $_REQUEST['topicality_start']   : "" ?>"></td>
  </tr>

  <tr>
    <td>Конец</td>
    <td><input name="adres_end"        type="text" value="<?php echo (!is_null($_REQUEST['adres_end']))        ? $_REQUEST['adres_end']        : "" ?>"></td>
    <td><input name="komment_end"      type="text" value="<?php echo (!is_null($_REQUEST['komment_end']))      ? $_REQUEST['komment_end']      : "" ?>"></td>
    <td><input name="kontact_name_end" type="text" value="<?php echo (!is_null($_REQUEST['kontact_name_end'])) ? $_REQUEST['kontact_name_end'] : "" ?>"></td>
    <td><input name="tel_end"          type="text" value="<?php echo (!is_null($_REQUEST['tel_end']))          ? $_REQUEST['tel_end']          : "" ?>"></td>
    <td><input name="topicality_end"   type="text" value="<?php echo (!is_null($_REQUEST['topicality_end']))   ? $_REQUEST['topicality_end']   : "" ?>"></td>
  </tr>
</table>

<b><p>Ценообразующие параметры (ЦОП)</p></b>
<table  border="1">
  <tr>
    <th>№</th>
    <th>Наименование ЦОП</th>
    <th>Начало тега ЦОП</th>
    <th>Конец тега ЦОП</th>
  </tr>

  <?php for ($i = 1; $i < 11; $i++) { ?>
    <tr>
      <td><?php echo $i ?></td>
      <td><input name="COP<?php echo $i?>_name"      type="text" value="<?php echo (!is_null($_REQUEST['COP' . $i . '_name']))      ? $_REQUEST['COP' . $i . '_name']      : "" ?>"></td>
      <td><input name="COP<?php echo $i?>_val_start" type="text" value="<?php echo (!is_null($_REQUEST['COP' . $i . '_val_start'])) ? $_REQUEST['COP' . $i . '_val_start'] : "" ?>"></td>
      <td><input name="COP<?php echo $i?>_val_end"   type="text" value="<?php echo (!is_null($_REQUEST['COP' . $i . '_val_end']))   ? $_REQUEST['COP' . $i . '_val_end']   : "" ?>"></td>
    </tr>
  <?php }?>
</table>

<p></p>



</form>


<?php if (!empty($page_msg)) { ?>
  <p><?php echo $page_msg ?></p>
<?php } ?>
</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
