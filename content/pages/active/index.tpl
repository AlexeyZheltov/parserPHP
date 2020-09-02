<div id="story">
	<?php if (!empty($site_array)) { 
		$lost_count = 0;
		$data_count = 0;
		$run_count  = 0;
	?>

	<p>В настоящий момент запущено <b><?php echo count($site_array); ?> </b> сайтов.</p>
	<p>Количество запущенных сайтов может отображаться не правильно в связи с некорректным закрытием парсера.</p>

	<table  border="1" style="width: 100%;">
		<tr>
			<th style="width: 10px;">ID</th>
			<th style="width: 150px;">Наименование</th>
			<th style="width: 35px;">Поиск</th>
			<th style="width: 50px;">Run</th>
			<th style="width: 50px;">Left</th>
			<th style="width: 50px;">Объявл.</th>
			<th style="width: 50px;">Настр.</th>
			<th style="width: 50px;">Искл.</th>
			<th style="width: 25px;">Ссылок<br/>в час</th>
			<th style="width: 25px;">Объявл<br/>в час</th>
			<th style="width: 10px;">Последняя ссылка</th>
			<th style="width: 105px;">Пройдена</th>
		</tr>
		<?php for ($i = 0; $i < count($site_array); $i++) { ?>
		
		<tr>
			<td><?php echo $site_array[$i]['id_site'] ?></td>
			<td style="word-wrap: break-word;"><a href="<?php echo "/view-site?id=" . $site_array[$i]['id_site'] ?>"><?php echo $site_array[$i]['link'] ?></a></td>
			<td>
				<?php
					if (date("Y-m-d H:i:s", strtotime($site_array[$i]['time_last_link']) + 60*5) < date("Y-m-d H:i:s")) {
						echo "Скорее всего сканирование не идет!<br/>";
					}					echo '<a href="?stop_site=' . $site_array[$i]['id_site'] . '">Стоп</a>';
				?>
			</td>
			<td><a href="link-list?view=run&id_site=<?php echo $site_array[$i]['id_site'] ?>"><?php echo number_format($site_array[$i]['prosses'], 0, ',', ' ') ?></a></td>
			<td>
				<?php 
				if($site_array[$i]['database']/$site_array[$i]['prosses'] <0.5 and $site_array[$i]['lost'] > 5000) {
					echo "Много лишних ссылок, проверьте исключения!";
				} 
				?>
				<a href="link-list?view=lost&id_site=<?php echo $site_array[$i]['id_site'] ?>"><?php echo number_format($site_array[$i]['lost'], 0, ',', ' ') ?></a>
			</td>
			<td>
				<a href="view-data?id_site=<?php echo $site_array[$i]['id_site'] ?>"><?php echo number_format($site_array[$i]['database'], 0, ',', ' '); ?></a>
			</td>
			<td><a href="<?php echo "site-edit?id_site=" . $site_array[$i]['id_site']?>">Изм</a></td>
			<td>
				<a href="<?php echo "block-list?id_site=" . $site_array[$i]['id_site']?>">Чер</a>/
				<a href="<?php echo "white-list?id_site=" . $site_array[$i]['id_site']?>">Бел</a>
			</td>
			<td><?php echo number_format($site_array[$i]['Speed-link'], 0, ',', ' ');?></td>
			<td>
				<?php 
				if ($site_array[$i]['teg_correct'] == 0) {
					echo "Wait";
				}
				elseif ($site_array[$i]['teg_correct'] == 1) {
					echo number_format($site_array[$i]['Speed-data'], 0, ',', ' ');
				}
				else {
					echo "Stop";
				}
				?>
			</td>
			<td style="word-wrap: break-word;"><a href="<?php echo $site_array[$i]['last_link'] ?>"><?php echo $site_array[$i]['last_link'] ?></a></td>
			<td><?php echo $site_array[$i]['time_last_link'] ?></td>
		</tr>
		<?php
			$lost_count = $lost_count + $site_array[$i]['lost'];
			$data_count = $data_count + $site_array[$i]['database'];
			$run_count = $run_count + $site_array[$i]['prosses'];
		} ?>
		<tr>
			<th></th>
			<th>ИТОГО</th>
			<th></th>
			<th><?php echo number_format($run_count, 0, ',', ' '); ?></th>
			<th><?php echo number_format($lost_count, 0, ',', ' '); ?></th>
			<th><?php echo number_format($data_count, 0, ',', ' '); ?></th>
			<th></th>
			<th><?php echo number_format($data_count / ($run_count + $lost_count), 2, ',', ' '); ?></th>
			<th></th>
			<th></th>
			<th>
				Скорость прохода по ссылкам: <?php echo number_format($ScanInfo['Speed-link'], 0, ',', ' '); ?> в час<br/>
				Скорость сбора объявлений: <?php echo number_format($ScanInfo['Speed-data'], 0, ',', ' '); ?> в час<br/>
			</th>		
			<th></th>
		</tr>
	</table>
	<?php }
	else { ?>
	<p>В данный момент сканирование сайтов не производится</p>
	<?php } ?>
</div> <!-- End story -->
<div id="sidebar">

</div> <!-- End sidebar -->
