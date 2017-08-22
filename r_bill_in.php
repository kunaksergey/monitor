<script type="text/javascript">

$(document).ready(function() {   

   var start_time=$('#start_time');
   var end_time=$('#end_time');
   
     //:при выборе start_time
   start_time.on('keyup',function(){
            	if(matchTime($('#start_time').val())){ $('a[name=run]').show();}
            	else $('a[name=run]').hide();
            	
   });
   
   //:при потере выборе end_time
  end_time.on('keyup',function(){
	  if(match($('#end_time').val())){ $('a[name=run]').show();}
            	else $('a[name=run]').hide();
   });


	$('#run').click(function() {
	
	img="<img src='images/load.gif' alt='Загрузка...'/>"; //Load.gif пока не получим данные
	$('#text').html(img);
		
		$('#content_head').text('Приход за период: '
					  +$('#start').val()
					  +'/'
					  +start_time.val()
					  +'-'
					  +$('#end').val()
					  +'/'
					  +end_time.val());
	          	$.ajax({
 			url: "r_bill_in_ms.php", // куда отправляем
 			cashe:false,
 			type: "POST",
 			data: ({start:$('#start').val(),
 			start_time:$('#start_time').val(),
 			end_time:$('#end_time').val(),
 			end:$('#end').val()}),
 			
 // после получения ответа сервера
 		success: function(data){ $('#text').html(data);}
 			});  
   
      }); 
 return false;
 
   });  
//конец Ajax
</script>

<div class=menu_left > 
<p style="text-transform:uppercase;text-align:center;" >Приход за период</p>
							<form action=>
									<ul class="input">c: 
									<input   id="start" type="text" value="<?php echo date("d.m.Y")?>" onfocus=this.select();lcs(this)
    									onclick=event.cancelBubble=true;this.select();lcs(this)>
    									<input   id="start_time" type="text" value="00:00:01" style="width:70px"/>
									</ul>
									
									<ul class="input">по:
									<input   id="end" type=text value="<?php echo date("d.m.Y")?>" onfocus=this.select();lcs(this)
    									onclick=event.cancelBubble=true;this.select();lcs(this)>
									<input   id="end_time" type="text" value="23:59:59" style="width:70px"/>
									</ul>
                          			  
									 <div id='run' class='botton'>
										Выполнить
									 </div>		
									 
									  <div id="content_data"></div>
									  
								</form>		
								
</div>	
	
<div class=menu_right>
		<!-- Шапка -->
		    <div id=content_head ></div>
		<!-- мастер таблица -->    
			<div id=text class=content ></div>
		
</div>

