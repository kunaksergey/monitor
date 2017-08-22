<script>
$(document).ready(function() {
	 $('tr[name=modal]').click(function(e) {
	  	 //Асинхронная загрузка с сервера с помощью Ajax   
	  	              
	  	                           $.ajax({
        									url: "checks_dt.php", // куда отправляем
            								cashe:false,
            								type: "POST",
            								data: ({hd_id : $(this).attr('hd_id'),number:$(this).attr('number'),code_name:$(this).attr('code_name')}),
        // после получения ответа сервера
        								success: function(data){ $('#content_dt').html(data);}
       									});
	    
	    });
});

//конец Ajax
</script>


<?php 
include_once 'sql.inc';
include_once 'connect.php';

// Запрос на шапку чеков
$sql_text=sql_checks($_POST['start'],$_POST['end'],$_POST['id']);
$sth = ibase_query($dbh, $sql_text); //получаем данные чеков
$checks='';
$i=0;
$checks.="<table><tr><th>Чек-№</th><th>Дата</th><th>ФИО</th><th>Подразделение</th><th>Сумма ориг.</th><th>Сумма</th><th>Наличными</th><th>Карточкой</th></tr>";
while ($row = ibase_fetch_object($sth)) {
     


	$checks.= "
 
	<tr height=20 href=#dialog name=modal hd_id=\"$row->ID\" number=\"$row->NUM\" code_name=\"$row->CODE_NAME\">
	<td >$row->NUM</td>
	<td>$row->DATE_TIME</td>
	<td id=hren>$row->CODE_NAME</td>
	<td id=hren>$row->SUBDIVISION</td>
	<td align=right>$row->SUM_OF_ORIG_SUM</td>
	<td align=right>$row->SUM_OF_SUM_</td>
	<td align=right>$row->SUM_OF_CACHE</td>
	<td align=right>$row->SUM_OF_CARD</td>
	   <tr>";
$i++;
}
	$checks.="</table>";
if ($i<>0){echo $checks;} else {echo "Записи отсутствуют";}

	
 ?>