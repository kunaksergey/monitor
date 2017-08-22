<?php
class Gant{
	
	public function show($row)
	{

		
		
		$diff=round((strtotime($row->DATE_STOP)-strtotime($row->DATE_START))/3600,0);  //Считаем разницу в датах с точностью до часов. Если что округляем с помощью round
	
		$diff_hour=round((strtotime($row->DATE_STOP)-strtotime($row->DATE_START))/86400,0);//Считает разницу в датах с точностью до дней
		$diff_start=round((date("H",strtotime($row->DATE_START))*60+date("i",strtotime($row->DATE_START)))/60);//Округляем часы.. Если <29 минут, то в меньшую сторону, если >39 то в большую
		
				
		$width=strval($diff*2+$diff_hour-1)."px"; 
		//Считаем длинну интервала в пикселях 1час-2px
		$width_start=strval($diff_start*2)."px";          //Считаем отклонения начала в 1час-2px
		
		
		switch ($row->STATUS) {
			case 0:
				$color="#FF00FF";
				break;
			case 1:
				$color="#00FFFF";
				break;
			case 2:
				$color="#C0C0C0";
				break;
			case 3:
				$color="#FF0000";
				break;
			case 4:
				$color="#FFFFFF";
				break;
		}
		
		
		
		return "<div class=yyy style=\"background:linear-gradient(to top, $color, #FFFFFF);width:$width;margin:-35px 0px 0px $width_start;\" >
               [$row->NUM] $row->CODE_NAME
								 <span>[$row->NUM] $row->CODE_NAME<br>
								       $row->DATE_START_DEFAULT - $row->DATE_STOP_DEFAULT<br>
								 </span>
		      </div>"; 
		
		
	}
	
	
	
}



?>

