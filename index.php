<?php
include 'inc/login.inc.php'; //подключаем журнализацию
include 'inc/lib.php';//подключаем библиотеку
session_start();//стартуем сессию для авторизации
header("HTTP/1.0 401 Unauthorized");
if (isset($_GET['exit'])){
	savelog("{$_SESSION['login']} is out");
	unset($_SESSION['login']);//закрытие сессии по логину
	session_destroy();//удаление сессии}
    header("Location: index.php"); //перегружаем страницу
}

?>
<script src="calendar_ru.js" type="text/javascript"></script> <!-- подключение календаря -->
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> <!-- подключение Ajax -->
<script src='js/lib.js'></script>
<html>
<head>
<meta charset="UTF-8">
<title>GoldenSand reports</title>
<link rel=stylesheet type="text/css" href="style.css"> <!-- подключение таблицы стилей -->
</head>

<?php
require_once 'connect.php'; 	 //Подключаемся к базе данных
require_once  'sql.inc';//Подключаем модуль с sql текстами
require 'auth.php';  //Если не залогинились, то пытаемся авторизироваться
echo "<div id=login>Are you logged as ".$_SESSION['login'] ."<a href=?exit>   Выход</a></div>"; 
include_once  '_menu_.html'; 			 //Меню
 if (isset($_GET['doc'])){           //Если выбран документ-
  savelog($_SESSION['login'] ."/".$_GET['doc']);
  
 include $_GET['doc'].".php";        //Загружаем документ   
 }?>
