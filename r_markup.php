<script>
<!-- ОТЧЕТ О НАЦЕНКЕ ТОВАРОВ -->	

$(function(){
 
 //Выбираем тип наценки
 $('input[name=markup_type]').change(function(){
	 var radio=$(this).val(), //выбранная radio button
         markup=$("input[name=markup_value]"); //поле наценки
         
     if ((radio==='hi') || (radio==='low')){
		markup.show();  //скрыть input markup
	 }
	 else markup.hide(); //показать input markup
	 
	 });
	
//Выбираем группу	
$( "#data" ).on( "click", 'a[name=group]', function() { 
	   var id=$(this).attr('groupId');
	   var name_grp=$(this).text();            //получаем имя группы из справочника
	   $('input[name=grp_id]').val(id);        //заполнение скрытого поля grp_id
	   $('#text_item').text(name_grp.trim());  //вывод в поле меню
	   $("#data").hide(); //скрыть справочник после выбора
	 });
 
	
//Показываем справочник	
 $("#dic_show").on("click",function(){ 		
	 getDic();			//получаем справочник
	 $("#data").show();

	});	
	
	


//Выполнить
 $('#run').on("click",function(){ 		
	getMarkup(); //получаем наценки
	});	
	
	
});	

//Наценки
function getMarkup(){
	
	img="<img src='images/load.gif' alt='Загрузка...'/>"; //Load.gif пока не получим данные
	$('#text').html(img);
	
	grp_id=$('input[name=grp_id]').val();   //id группы
	type=$('input[name=markup_type]:checked').val(); //тип наценки
	markup=$('input[name=markup_value]').val(); //значение наценки
	
	$.ajax({
 			url: "r_markup_ms.php", // куда отправляем
 			cashe:false,
 			type: "POST",
 			data: ({
				    grp_id:grp_id,   //id группы прайс-листа
				    type:type,       //тип выборки
				    markup:markup    //наценка
				}), 			
 // после получения ответа сервера
		       success: function(data){$('#text').html(data);
		        
		       } //конец success
		  });// конец $.ajax	
}


//Получаем справочник
function getDic(){
    
		  $.ajax({
 			url: "dic.php", // куда отправляем
 			cashe:false,
 			type: "POST",
 			data: ({dic:'DIC_PRICE_LIST',
		    flag:'false',   //не показывать детализацию
		    grp_id:'1'     //корень по умолчанию
				}), 			
// после получения ответа сервера
		       success: function(data){$('#data').html(data);
		        
		       } //конец success
		  });// конец $.ajax	
 }

</script>	

<!--Панель выбора-->
<div class=menu_left > 
<p style="text-transform:uppercase;text-align:center;" >Наценки</p>
	
		<ul>
		   <li>
				<input name="markup_type" type="radio" value="all" checked/>Все</li>	
		   <li>
			   <input name="markup_type" type="radio" value="min"/>Меньше себестоимости</li>
		   <li>
			   <input name="markup_type" type="radio" value="hi" /> Наценка &gt; </li>
		   <li>
			   <input name="markup_type" type="radio" value="low" /> Наценка &lt; </li>
		   <li>
			  <input name="markup_value" class=value type="text" value="0" /> </li>	   	   
		</ul>
		
		<input  type="hidden" name="grp_id" value="1"/>
	 
 <!--Панель кнопок-->	      
     
      <div id='dic_show' class='botton'>
		Справочник
      </div>
     
     <div id='run' class='botton'>
		Выполнить
	 </div>
	 
  <!--Вывод наименование группы в панель-->  
  <div id="text_item" ></div> 
   
</div>
<!--Конец Панель выбора-->

<!--Правое меню-->
<div class=menu_right>
		<!-- Шапка -->
		    <div id=content_head >Наценка:</div>
		<!-- мастер таблица -->    
			<div id=text class=content ></div>
		
</div>
<!--Конец правого меню-->

<!-- Справочник required readonly-->
<div id="data" >
</div>
