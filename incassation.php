<div class=menu_right>
		<!-- Шапка -->
		    <div id=content_head > Инкассация:</div>
		<!-- мастер таблица -->    
			<div  class=content >
<?php
/*Инкасация*/

$sql_incassation = sqltext_incassation();
$sql_kassa=sqltext_kassa();
/********************************************************************************/

$sth = ibase_query($dbh, $sql_incassation);
$sth_kassa=ibase_query($dbh, $sql_kassa);
echo "<table><th>Подразделение</th><th>Сумма</th> ";
$summ=0;
while ($row = ibase_fetch_object($sth)) {
	if ($row->DOC_SUM_<0)
	{
		$div_class="negative";
	}
	else $div_class="positive";
		
	echo "<tr class=content><td>$row->NAME</td><td align=right><div class=$div_class>$row->DOC_SUM_</div></td></tr>";
	$summ+=$row->DOC_SUM_;
}
	echo "</table><hr><table><th  align=right>Всего:".round($summ,2)." грн.</th></table>";
	
	echo "<table><th>Подразделение</th><th>Сумма</th> ";
	?>
	</div>
	
	<!-- Шапка -->
		    <div id=content_head > Касса:</div>
		<!-- мастер таблица -->
	<div  class=content >
	
<?php 	
/*Касса*/
while ($row = ibase_fetch_object($sth_kassa)) {
		if ($row->DOC_SUM_<0)
		{
			$div_class="negative";
		}
		else $div_class="positive";
	
		echo "<tr class=content><td>$row->NAME</td><td align=right><div class=$div_class>$row->DOC_SUM_</div></td></tr>";
	
	}
	echo "</table>";

?>
</div>		
</div>	