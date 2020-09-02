<?php
////////////////////////////////////////////////////////////////////////////////
// ФУНКЦИЯ ЗАЧИСЛЕНИЯ АБИТУРИЕНТОВ /////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

// ПОДГОТОВКА МАССИВА КОНКУРСОВ К ОБРАБОТКЕ ////////////////////////////////////
	// Запрашиваем все имеющиеся специальности
	$query="SELECT * FROM university_speciality";
	$result=mysql_query($query) or die (mysql_error());
	while ($row=mysql_fetch_assoc($result)){
		$specialties[]=$row;
	}

	// Запрашиваем все конкурсы абитуриентов
	$query="SELECT * FROM users_prioritets
	        ORDER BY lgota DESC, point DESC ";
	$result=mysql_query($query) or die (mysql_error());
	while ($row=mysql_fetch_assoc($result)){
		$competitions[]=$row;
	}

 	// Запрашиваем все конкурсы абитуриентов
	$query="SELECT * FROM users_prioritets
	        ORDER BY prioritet_competition";
 	$result=mysql_query($query) or die (mysql_error());
	while ($row=mysql_fetch_assoc($result)){
		$id_users=$row['id_users'];
		$prioritet_competition=$row['prioritet_competition'];
		$users_prioritets[$id_users][$prioritet_competition]['result']="н/д";
	}

	/* Составляем массив в котором студенты распределены по убыванию баллов в
	зависимости от выбранной специальности */
	$k=0;
	for ($i=0; $i<count($specialties); $i++){
		for ($j=0; $j<=count($competitions); $j++){
			if ($competitions[$j]['id_speciality']==$specialties[$i]['id_speciality']){
				$ratings[$i][$k]=$competitions[$j];
				$k++;
			}
		}
		$k=0;
	}

// ФУНКЦИЯ ПРЕДВАРИТЕЛЬНОГО ЗАЧИСЛЕНИЯ (Pre-enrollment function)////////////////
	/* Данная функция проводит зачисление абитуриентов на каждой отдельно взятой
	специальности, не обращая внимания на приоритеты абитуриентов */
	function PEF(){
		global $specialties, $ratings, $APE, $users_prioritets;
		for ($i=0; $i<count($specialties); $i++){
			$count_place=$specialties[$i]['count_of_students'];
			$count_budget=$specialties[$i]['budget'];
			$final_place=$count_place;
			for ($j=0; $j<count($ratings[$i]); $j++){
				$id_users=$ratings[$i][$j]['id_users'];
				$prioritet_competition=$ratings[$i][$j]['prioritet_competition'];
				// Если места окончательно закончились, то оставшиеся студенты получают статус "Окончательно не поступил"
				if ($final_place==0){
					$users_prioritets[$id_users][$prioritet_competition]['result']="Окончательно не поступил";
					continue;
				}
				// если нет мест переходим к следующей специальности
				if ($count_place==0){
					break;
				}

				// если имеется отметка об окончательном поступлении переходим к следующему абутуриенту
				if ($users_prioritets[$id_users][$prioritet_competition]['result']=="Окончательно поступил на бюджет"){
					$count_place--;
					$count_budget--;
					$final_place--;
					continue;
				}
				// если имеется отметка об окончательном поступлении переходим к следующему абутуриенту
				if ($users_prioritets[$id_users][$prioritet_competition]['result']=="Окончательно поступил на контракт"){
					$count_place--;
					$final_place--;
					continue;
				}
				// если имееися отметка что студент не будет поступать на эту специальность
				if ($users_prioritets[$id_users][$prioritet_competition]['result']=="Не будет поступать"){
					continue;
				}
				$APE[]=$ratings[$i][$j];

				if ($count_budget>0){
					$users_prioritets[$id_users][$prioritet_competition]['result']="Предварительно зачислен на бюджет";
					$count_budget--;
				}
				else {
					$users_prioritets[$id_users][$prioritet_competition]['result']="Предварительно зачислен на контракт";
				}
				$count_place--;
			}
			// Если места остались, то считаем, что специальность проработана
			if ($count_place>0){
				$enrollment++;
			}
			// если мест не осталось, завершаем обработку
			if ($final_place==0){
				$enrollment++;
			}
		}
		return $enrollment;
	}

// ФУНКЦИЯ ОКОНЧАТЕЛЬНОГО ЗАЧИСЛЕНИЯ (final enrollment function) ///////////////
	function FEF (){
		global $specialties, $ratings, $APE, $users_prioritets;
		for ($i=0; $i<count($APE); $i++){
			$id_users=$APE[$i]['id_users'];
			$new_prioritet=0;
			$count_enrollment=0;
			for ($j=1; $j<16; $j++){
				if (empty($users_prioritets[$id_users][$j])){
					continue;
				}
				if ($users_prioritets[$id_users][$j]['result']=="Окончательно не поступил"){
					continue;
				}
				$new_prioritet++;
				if ($users_prioritets[$id_users][$j]['result']=="н/д"){
					continue;
				}
				$count_enrollment++;
				if ($count_enrollment==1){
					if ($new_prioritet==1){
						if ($users_prioritets[$id_users][$j]['result']=="Предварительно зачислен на бюджет"){
							$users_prioritets[$id_users][$j]['result']="Окончательно поступил на бюджет";
						}
						if ($users_prioritets[$id_users][$j]['result']=="Предварительно зачислен на контракт") {
							$users_prioritets[$id_users][$j]['result']="Окончательно поступил на контракт";
						}
						$users_prioritets[$id_users][$j]['new_prioritet']=$new_prioritet;
					}
					else {
						$users_prioritets[$id_users][$j]['result']="Будет дальше претендовать";
						$users_prioritets[$id_users][$j]['new_prioritet']=$new_prioritet;
					}
				}
				else {
					$users_prioritets[$id_users][$j]['result']="Не будет поступать";
					$users_prioritets[$id_users][$j]['new_prioritet']=$new_prioritet;
				}
			}
		}
	}

	do {
		$enrollment=PEF();
		FEF();
	} while ($enrollment<count($specialties));

// ОБНОВЛЯЕМ БАЗУ ДАННЫХ ///////////////////////////////////////////////////////
//print_r($users_prioritets);
	foreach ($users_prioritets as $key => $id_users){
		$highest_priority_enrolled=0;
		foreach ($id_users as $key2 => $prioritet_competition){
			$result=$prioritet_competition['result'];
			$new_prioritet=$prioritet_competition['new_prioritet'];
			$query="UPDATE users_prioritets
			        SET result='$result',
			            new_prioritet='$new_prioritet',
			            highest_priority_enrolled='$highest_priority_enrolled'
			        WHERE id_users='$key' AND
			              prioritet_competition='$key2'
			        LIMIT 1";
			$result=mysql_query($query) or die (mysql_error());
			if ($highest_priority_enrolled==0 and $new_prioritet==1){
				$highest_priority_enrolled=1;
			}
		}
	}

?>