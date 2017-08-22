<?php 
class DB {
  protected static $_instance;
  private $host='192.168.2.98:D:\DB\db.fdb'; //адресс БД
  private $user='SYSDBA';		     //имя пользователя
  private $password ='masterkey';            //пароль
  private $dbh; 
  function __construct(){
  $this->dbh = @ibase_connect($this->host, $this->user, $this->password); //создаем указатель на подключение
  }


static function getInstance(){

if (null === self::$_instance) {
            // создаем новый экземпляр
            self::$_instance = new self();
        }
        // возвращаем созданный или существующий экземпляр
        return self::$_instance->dbh;
 
 
}

}


$time =0;					   
$dbh=DB::getInstance();
if (!$dbh) {
	echo('Ошибка соединения: ');exit();         //если connect=false   прерываем программу  
}
?>