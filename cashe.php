<?php if (session_status() == PHP_SESSION_NONE) {  //Если сессия не стартовала, то запускаем сессию
	session_start();
}
if (!isset($_SESSION['login'])) die;  //Если не залогинился, то отменяем загрузку.
?>
<script >

$(document).ready(function() {   

	$('#run').click(function() {
		$('#content_head').html($('#myselect').find(':selected').text()+'/Касса: '+$('#start').val()+'-'+$('#end').val());
	
	          	$.ajax({
 			url: "cashe_ms.php", // куда отправляем
 			cashe:false,
 			type: "POST",
 			data: ({start:$('#start').val(),end:$('#end').val(),id:$("#myselect").val()}),
 			
 // после получения ответа сервера
 		success: function(data){ $('#content').html(data);}
 			});  
     	e.preventDefault();
      }); 
 return false;
 
   });  
//конец Ajax
</script>

<div class=menu_left >
<p style="text-transform:uppercase;text-align:center;" >Касса</p>
							<form action=>
									<ul class="input">c: <input   id="start" type="text" value="<?php echo date("d.m.Y")?>" onfocus=this.select();lcs(this)
    									onclick=event.cancelBubble=true;this.select();lcs(this)>
    								</ul>
    								<ul class="input">	
										по: <input   id="end" type=text value="<?php echo date("d.m.Y")?>" onfocus=this.select();lcs(this)
    									onclick=event.cancelBubble=true;this.select();lcs(this)>
                                    </ul>
                                    <ul class="input">
    									   	<select id="myselect" class="select" size=1>
													<option selected=selected value=0>Все</option>
													<option  value=11>Атриум</option>
													<option  value=13>Центральный</option>
													<option  value=4>Отель</option>
													<option  value=12>Офис</option>
											</select>
									</ul>
									 <div id='run' class='botton'>
										Выполнить
									 </div>	
								</form>		
								
</div>	
	
<div class=menu_right>
		<!-- Шапка -->
		    <div id=content_head ></div>
		<!-- Мастер таблица -->    
			<div id=content class=content ></div>
		<!-- Детализация -->
			<div id=content_dt class=content></div>
</div>

