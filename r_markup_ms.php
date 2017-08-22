<?php
include_once 'sql.inc'; 	//файл с sql
include_once 'connect.php';     //соединение
include_once 'inc/lib.php';     //библиотеки

$grp_id=1; //id группы
$type='all'; //тип наценки
$markup=0; //значение наценки
$prev='';  
$str='';

if( isset ($_POST['grp_id']))
$grp_id=(int)$_POST['grp_id'];

if(isset($_POST['type']))
$type=$_POST['type'];

if(isset($_POST['markup']))
$markup=(int)$_POST['markup'];

$price=new Price($dbh);

$arr=$price->getMarkup($grp_id,$type,$markup);

foreach ($arr as $value){
	
	if ($value['PRICE_GNAME']!=$prev){
   
        if ($str!='') $str.="</table>"; 
        		
		$str.="<table><caption>{$value['PRICE_GNAME']}</caption><tr><th>Наименование</th><th>Цена закупки</th><th>Цена продажи</th><th>Наценка</th></tr>";	
		$prev=$value['PRICE_GNAME'];		
	} 
	$str.="<tr><td>{$value['GOODS_NAME']}</td><td>{$value['LAST_IN_PRICE']}</td><td>{$value['PRICE']}</td><td>{$value['NACENKA']}</td></tr>";
	}

echo $str;

?>




   	

