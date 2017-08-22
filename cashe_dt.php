<?php
if (session_status() == PHP_SESSION_NONE) {  //Если сессия не стартовала, то запускаем сессию
	session_start();
}
if (!isset($_SESSION['login'])) die;  //Если не залогинился, то отменяем загрузку.

include_once 'connect.php'; 	 //Подключаемся к базе данных
include_once  'sql.inc';//Подключаем модуль с sql текстами
$sql_text=sql_cashe_dt($_POST['hd_id']);
$sth_dt = ibase_query($dbh, $sql_text); //получаем данные детализации чеков
$i=0;
$cashe_dt="<table ><hr><th>Документ</th><th align=right>Сумма</th>";
while ($row_dt = ibase_fetch_object($sth_dt))
{
	$cashe_dt.="<tr><td>$row_dt->TYPE</td><td align=right >".round($row_dt->SUM_ROW,2)."грн.</td></tr>";
	$i++;
}
$cashe_dt.="</table>";
if ($i<>0){echo $cashe_dt;} else {echo "Записи отсутствуют";}


?>