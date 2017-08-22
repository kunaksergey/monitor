<script>

$(function(){
$('a[name=run]').hide(); //скрываем поле "Выполнить"

//Получаем id выбранного из справочника
$( "#data" ).on( "click", 'a[name=item]', function() { 
	   var id=$(this).attr('id');
	   var client=$(this).text();
	   $('input[name=name_id]').val(id);
	   $('input[name=client]').val(client);
	   $('a[name=run]').show(); //после установки значений показали поле "Выполнить"
	 });
 
	
//Показываем справочник	
 $('a[name=show]').on("click",function(){ 					
	 $("#data").show();
	 getDic();
	 });

   
//очищаем поле input   
   $('a[name=clear]').on('click',function(){
			  $('input[name=client]').val('');
			  $('input[name=name_id]').val('0');
			  $('a[name=run]').hide();
  });

//Выполнить отчет
   $('a[name=run]').on("click",function(){ 					
	 $("#data").hide();

	 });

   		  
});



//При показе блока загружаем аяксом
function getDic(){
    
		  $.ajax({
 			url: "dic.php", // куда отправляем
 			cashe:false,
 			type: "POST",
 			data: ({dic:'DIC_CLIENT',
				    flag:'true'}), 			
 // после получения ответа сервера
		       success: function(data){$('#data').html(data);
		        
		       } //конец success
		  });// конец $.ajax	
 			  

}

function none(type)
{
param=document.getElementById(type);
if(param.style.display == "block") param.style.display = "none";
else param.style.display = "block";
}

</script>
<div class=menu_left > 
<p style="text-transform:uppercase;text-align:center;" >Детальный отчет о контрагенте</p>
	<form action=>
		<ul class="input">
		c: <input   id="start" type="text" value="<?php echo date("d.m.Y")?>" onfocus=this.select();lcs(this)
    		    onclick=event.cancelBubble=true;this.select();lcs(this)>
    		</ul>
    		
    		<ul class="input">	
		по: <input   id="end" type=text value="<?php echo date("d.m.Y")?>" onfocus=this.select();lcs(this)
    		    onclick=event.cancelBubble=true;this.select();lcs(this)>
                </ul>
                
                <ul class="input">
		клиент: <input type=textname name='client' id="client"  value="" />
				<input type="hidden" name="name_id" value="0"/>
		<a href=# name=clear >X</a>&nbsp
		<a href=# name=show >>></a>
    		</ul>		
									
		<ul class="input">				
		<a href=# name=run >Выполнить</a>
		</ul>
		<div id="content_data"></div>
	</form>		
								
</div>	

<div class=menu_right>
		<!-- Шапка -->
		    <div id=content_head ></div>
		<!-- мастер таблица -->    
			<div id=text class=content ></div>
		
</div>

<!-- Справочник required readonly-->
<div id="data" >
</div>
