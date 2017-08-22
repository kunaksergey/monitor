<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
	
$(function(){
	//Скрываем справочник
	 $( "#dic" ).on( "click", 'a[name=close]', function() { 
	   $("#dic").hide();
	   	 });	
	   	 
	});	
function selectItem(groupId,name,flag){
	if (!flag) return; //false- 
 	 
		$.ajax({
 			url: "dic_item.php", // куда отправляем
 			cashe:false,
 			type: "POST",
 			data: ({groupId:groupId, //id группы
					name:name        //имя справочника
 			}),			
 // после получения ответа сервера
 		success: function(data){
			 if($('#dic_group').prev().attr('id')==='dic_item') return; //Если справочник уже добавлен-выходим
			 $('#dic_group').before(data);
			}
 		});  
 	}
</script>

<?php
//Скрипт выполняется только при POST
include_once 'sql.inc'; 	//файл с sql
include_once 'connect.php';     //соединение
include_once 'inc/lib.php';     //библиотеки

$dicName='DIC_CLIENT';				//справочник по умолчанию
$flag='true';  						// показывать ли элементы стправочника
$grp_id=0;                          //корень, от которого показывать


if (isset($_POST['dic'])){          //пришел справоник
	$dicName=$_POST['dic'];
	}
	
if (isset($_POST['flag'])){         //пришел ли флаг
	$flag=$_POST['flag'];
	}

if (isset($_POST['grp_id'])){           // пришла ли группа
	$grp_id=$_POST['grp_id'];
	}	

$dic=new Dic($dbh,$dicName); //экземпляр класса справочника
$str='<table>';
$result=$dic->getGroup($grp_id);   //получаем группу справочника
  //формируем группу для вывода
foreach ($result as $key=>$value){
      $name=trim($value['NAME']);   
      $str.="<tr><td><a href=# name=group onClick='selectItem({$value['ID']},\"{$dicName}\",{$flag}); return false;'
		     groupId={$value['ID']}>{$value['SEPARATOR']}{$name}</a></td></tr>";	
      }

$str.='</table>';



?>   
<link rel=stylesheet type="text/css" href="style.css"> <!-- подключение таблицы стилей -->

<div id=dic>

 <div id='dic_top'>	
	<a href=# name='close'>Закрыть</a> 
 </div>




<div>

<div id=dic_group>

	<div class='dic_head_content'>
			Группа
		 </div>
		 
		  <div id='dic_group_src' class='content' > 
		   <?=$str?>		
		 </div>
	</div>



</div>
