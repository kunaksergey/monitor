<?php if (session_status() == PHP_SESSION_NONE) {  //Если сессия не стартовала, то запускаем сессию
	session_start();
}
if (!isset($_SESSION['login'])) die;  //Если не залогинился, то отменяем загрузку.
?>
<script>
$(document).ready(function() {
	 $('tr[name=modal]').click(function(e) {
		 //Асинхронная загрузка с сервера с помощью Ajax   
                                  $.ajax({
        									url: "cashe_dt.php", // куда отправляем
            								cashe:false,
            								type: "POST",
            								data: ({hd_id : $(this).attr('hd_id')}),
        // после получения ответа сервера
        								success: function(data){ $('#content_dt').html(data);}
       									}); 
                               
    	
	 });
});
//конец Ajax
</script>			
											
<?php 
include_once 'connect.php'; 	 //Подключаемся к базе данных
include_once  'sql.inc';//Подключаем модуль с sql текстами

// Запрос на шапку чеков
$sql_checks=sql_cashe($_POST['start'],$_POST['end'],$_POST['id']);
$sth = ibase_query($dbh, $sql_checks); //получаем данные чеков
$cashe='';
$i=0;
$cashe.="<table><th>Дата</th><th>№</th><th>Тип</th><th>Касса</th><th>Контрагент</th><th>Статья</th><th>Сумма</th>";
while ($row = ibase_fetch_object($sth)) {
	$cashe.= "

	<tr  height=20 href=#dialog name=modal hd_id=\"$row->ID\">
	<td>".date("d.m.Y H:i:s",strtotime($row->DATE_TIME))."</td>
	<td>$row->NUM</td>
	<td>$row->TYPE</td>
	<td>$row->NAME</td>
	<td>$row->CONTRAGENT</td>
	<td>$row->IO_ITEMS</td>
	<td>$row->DOC_SUM</td>
	<tr>";
	$i++;
}
$cashe.="</table>";
if ($i<>0){echo $cashe;} else {echo "Записи отсутствуют";}

?>