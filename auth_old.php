<?php if(isset($_POST['login']))
{
$sql_text=sqltext_user_passwod($_POST['login'],$_POST['password']);
$sth = ibase_query($dbh, $sql_text); 
while ($row = ibase_fetch_object($sth)) {
	$_SESSION['login']=$row->USER_LOGIN;

}

}



else {echo '  <div class=auth>
				<form  method="POST" autocomplete="off"> 
  					Логин <input name="login" type="text" value= "" required><br><br> 
  					Пароль <input name="password" type="password" value= "" required><br> 
  						   <input name="submit" type="submit" value="Войти"> 
  				</form>
			  </div>';
       die;
      }
?>
