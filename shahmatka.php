<?php
if (session_status() == PHP_SESSION_NONE) {  //Если сессия не стартовала, то запускаем сессию
	session_start();
}
if (!isset($_SESSION['login'])) die;  //Если не залогинился, то отменяем загрузку.

if (!isset($_GET['start'])){
	$_GET['start']=date("d.m.Y",mktime(0, 0, 0, date("m")  , date("d")-15, date("Y")));	
}
if (!isset($_GET['end'])){
	$_GET['end']=date("d.m.Y",mktime(0, 0, 0, date("m")  , date("d")+15, date("Y")));
}
?>
<html>
<head>
<link rel=stylesheet type="text/css" href="style.css">

</head>
<div class=menu_left style="float:left;margin-right:20px;"> 
<p style="text-transform:uppercase;text-align:center;" >Шахматка</p>
							<form  name=myForm method="GET" action="<?php echo $_SERVER['PHP_SELF']?>">
							       <input name="doc" type="hidden" value=<?php echo $_GET['doc']; ?>>
									<ul class="input">c: <input  name="start" id="start"   type="text" value="<?php echo $_GET['start'];?>" onfocus=this.select();lcs(this)
    									onclick=event.cancelBubble=true;this.select();lcs(this)>
    								</ul>
    								<ul class="input">	
										по: <input  name="end" id="end"  type=text value="<?php echo $_GET['end'];?>" onfocus=this.select();lcs(this)
    									onclick=event.cancelBubble=true;this.select();lcs(this)>
                                    </ul>
                              		
							
									<div id='run' class='botton' onclick="document.forms['myForm'].submit(); return false;" >
										Выполнить
									</div>
									  
								</form>		
								
</div>	


<?php
include_once 'gant.php';
$week= array(                            //Дни на русском языке   
	   1=>'Пн',2=>'Вт',3=>'Ср',4=>'Чт',5=>'Пт',6=>'Сб',7=>'Вс'
);
$monthes = array(                       //Месяцы на русском языке
		1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель',
		5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август',
		9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь'
);
$param=array(                          //Массив параметров для работы и передачи в класс
	'start'=>'16.08.2014', 	//начало периода
	'end'=>'16.09.2014',  	//конец периода
	'td_width_first'=>'100',//ширина первой ячейки
	'td_width'=>'48',    	//ширина ячейки.
	'td_height'=>'80'	 	//высота ячейки  
);
$today = getdate();  //Текущая дата
$param['start']=$_GET['start'];
$param['end']=$_GET['end'];
$sql_room=sqltext_room(); //запрос справочника номеров
$sql_reservation=sqltext_reservation($param['start'],$param['end']);// запрос  журнала резервирования 
$sth_room = ibase_query($dbh, $sql_room);     //справочник номеров;
$sth_room_reservation=ibase_query($dbh, $sql_reservation); //журнал резервирования
$interval=(strtotime($param['end'])-strtotime($param['start']))/3600/24+1; //интервал в днях
$width_div=$param['td_width_first']+$interval*$param['td_width']+$interval+$param['td_width_first']+700; //ширина div блока
$glob=array(); //основная матрица;
$head="<th></th>"; //шапка с месяцами
$head_date="<th width=".$param['td_width_first'].">Наименование</th>"; //шапка с числами
$content=""; //собственно данные

//Формируем массив номерноего фонда ID=>NAME
while ($row = ibase_fetch_object($sth_room)){
	$rooms[$row->ID]=$row->NAME;
}

//
//Формируем 2-х мерный массив [ID номера]=>"число"
for($i=0;$i<$interval;$i++){

$curent =mktime(0, 0, 0, date("m",strtotime($param['start']))  , date("d",strtotime($param['start']))+$i, date("Y",strtotime($param['start']))); //текущий день выборки
$curent_f=date("d.m.Y",$curent);        ////текущий день выборки/Формат '01.08.2014'
$weekday=$week[date("N",$curent)];  //Формат Пн
$day=date("d",$curent); //Формат 01
$month=$monthes[date("n",$curent)];// Формат Август
$year=date("Y",$curent);            //Формат 2014
$month_f=$month.",".$year;             //Формат Август,2014
if (!isset($top[$month_f])) {$top[$month_f]=0;}  
$top[$month_f]++; //считаем сколько дней в каждом месяце
$head_date.="<th>$weekday<br>$day</th>";

foreach ($rooms as $key=>$value)  {
	$glob[$key][$curent_f]="<span >$curent_f</span>";
	
    if ($curent_f==date("d.m.Y",$today[0]))	
    {   $width_start=strval($today['hours']*2)."px";
       	$glob[$key][$curent_f].="<div style=\"background:yellow;position:absolute;width:2px;height:80px; margin:-40px 0px 0px $width_start;\"> </div>";} //обозначаем текущий день
    }
}


foreach ($top as $key=>$value)
{
 $head.="<th colspan=$value>$key</th>";	 //формируем шапку месяцев
}

// Перебираем журнал зарезервированных номеров
while ($row = ibase_fetch_object($sth_room_reservation))
{
    //Анализиуем даты. Если они выходят за границы, то устанавливаем граничные значения.
	if (strtotime($row->DATE_START)<strtotime($param['start']))  //
	{$row->DATE_START=$param['start'];}
	if (strtotime($row->DATE_STOP)>strtotime($param['end']))
	{$row->DATE_STOP=$param['end'];}
	
	$str_d=date("d.m.Y",strtotime($row->DATE_START));        //Формат '01.08.2014'
	$a=new Gant(); //создаем объект поселения
	$glob[$row->ROOM_ID][$str_d].=$a->Show($row);
	 
}





foreach ($glob as $key=>$cont)
{
	
	$content.="<tr height=".$param['td_height']." ><td class=gant_td>".$rooms[$key]."</td>";
	
			foreach ($cont as $key2=>$value1)
   				 {
   			         
   				 	
   					$content.="<td width=".$param['td_width'].">$value1</td> ";
                    
   				 } 
   				 
   				 $content.="<td class=gant_td>".$rooms[$key]."</td></tr>";
}              
              
	           		
    
			                 



//********************************************************************

$text="";
$text.="<div id=gant style=\"width:".$width_div."px;\"><table>";  //формируем таблицу щахматки
$text.= "<tr>".$head."<th></th></tr>";  
$text.="<tr>".$head_date."<th width=".$param['td_width_first'].">Наименование</th></tr>";
$text.= $content;
$text.="</table><div>";
echo $text;
 
?>
