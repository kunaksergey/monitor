<?php
/*Приход за период*/
include_once 'sql.inc';
include_once 'connect.php';

$start= $_POST['start']." ".$_POST['start_time'];
$end  = $_POST['end']." ".$_POST['end_time'];

$sql_bill_in = sqltext_r_bill_in($start,$end);
/*******************************************************************************/
$sth = ibase_query($dbh, $sql_bill_in);
$head="";
echo "<table ><tr><th>Наименование</th><th>Кол-во</th><th>Ед.</th></tr> ";
while ($row = ibase_fetch_object($sth)) {
	if ($row->NAME_P<>$head) echo "<tr><th>$row->NAME_P</th></tr>";
	echo "<tr class><td>$row->NAME</td><td align=right>".round($row->CNT,3)."</td><td align=right>$row->UNIT</td></tr>";
	$head=$row->NAME_P;
}
	echo "</table>";
?>

