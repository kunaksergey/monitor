<?php if (session_status() == PHP_SESSION_NONE) {  //Если сессия не стартовала, то запускаем сессию
	session_start();
}
if (!isset($_SESSION['login'])) die;  //Если не залогинился, то отменяем загрузку.
?>
<div class=menu_right>
		<!-- Шапка -->
		    <div id=content_head > Сальдо:</div>
		<!-- мастер таблица -->    
			<div  class=content >
		
<?php
/*Расчет Сальдо*/

$sql_saldo = sqltext_saldo();
/********************************************************************************/

$sth = ibase_query($dbh, $sql_saldo);


echo "<table> ";
while ($row = ibase_fetch_object($sth)) {
	if ($row->SALDO<0)
	{
		$div_class="negative";
	}
	else $div_class="positive";
	
	echo "<tr class><td>$row->CODE_NAME</td><TD></TD><td align=right><div class=$div_class>$row->SALDO</div></td></tr>";
}
	echo "</table>";
?>

</div>
</div>