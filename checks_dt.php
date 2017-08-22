<?php
//Запрос на детализацию чеков
//$id_check = (int)$_POST['id_check'];  // количество записей, получаемых за один раз
include_once 'sql.inc';
include_once 'connect.php';
$sql_checks_dt=sql_checks_dt($_POST['hd_id']);
$sth_dt = ibase_query($dbh, $sql_checks_dt); //получаем данные детализации чеков
$sum=0;
echo "<table ><tr><th colspan=4>№".$_POST['number']."   ". $_POST['code_name']."</th></tr><tr><th>Наименование</th><th align=right>Кол-во</th><th align=right>Цена</th><th align=right>Сумма</th></tr>";
while ($row_dt = ibase_fetch_object($sth_dt))
 {
echo "<tr class=content><td width=300>$row_dt->NAME</td><td align=right width=95>".round($row_dt->CNT,2)."</td><td align=right width=95>".round($row_dt->PRICE,2)."</td><td align=right width=95>".round($row_dt->SUM_,2)."</td></tr>";
$sum+=$row_dt->SUM_;
}
echo "</table><hr><table><th  align=right>Всего:$sum грн.</th></table>";


?>