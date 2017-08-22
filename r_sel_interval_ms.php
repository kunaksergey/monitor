<?php
/*Продажа за период*/
include_once 'sql.inc';
include_once 'connect.php';

$start= $_POST['start']." ".$_POST['start_time'];
$end  = $_POST['end']." ".$_POST['end_time'];
$id=$_POST['id'];
if ($_POST['checked']=='false')
{$sql_sel = sqltext_r_sel_interval($start,$end,$id);}
else $sql_sel = sqltext_r_sel_interval_dt($start,$end,$id);
/********************************************************************************/
$sth = ibase_query($dbh, $sql_sel);
echo "<table ><tr><th>Наименование</th><th>Кол-во</th><th>Ед.</th> </tr>";
$head="";
while ($row = ibase_fetch_object($sth)) {
	if ($row->NAME_P<>$head) echo "<tr><th>$row->NAME_P</th></tr>";
	echo "<tr class><td>$row->NAME</td><td align=right>".round($row->CNT,3)."</td><td align=right>$row->UNIT</td></tr>";
	$head=$row->NAME_P;
}
	echo "</table>";
?>
