<?php 
if (!isset($_SESSION['login'])){
    if($_SERVER['REQUEST_METHOD']=='POST'){
      $login=trim(strip_tags($_POST['login']));   //Получаем логин из формы
      $passwd=trim(strip_tags($_POST['password']));	//Получаем пароль из формы
      $sql_text=sqltext_user_passwod($login,$passwd);
      $check=false;
      $sth = ibase_query($dbh, $sql_text); 
	while ($row = ibase_fetch_object($sth)) {$login=$row->USER_LOGIN;$id=$row->ID;$check=true;} 
		if ($check) { //Если запись есть,
		$_SESSION['login']=$login;  //то $_SESSION['login']=login
		$_SESSION['ID']=$id;
		savelog("$login is autorized"); //пишем в логи
				      }
		else savelog("$login incorrect"); //Пишем в логи
						
	 header("Location: index.php"); //перегружаем страницу
			      }

            else {echo '  <div class=auth>        
					<form  method="POST" autocomplete="off"> 
  					Логин <input name="login" type="text" value= "" required><br><br> 
  					Пароль <input name="password" type="password" value= "" required><br> 
  					 <input name="submit" type="submit" value="Войти"> 
  					</form>
			  		</div>'; 
					die; //после формы прекращаем загузку
		  		 }
		  
}
		  
		  ?>
