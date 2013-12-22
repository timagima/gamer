<?php
namespace application\core\config;
use application\core\db\MyPDO as DB;
use PDO;

class Config
{
    public $dbh;
    public $_p = array();
    private static $instances;
    private $dsn = 'mysql:host=localhost; dbname=gamer; connection_timeout=15';
    private $user = 'root';
    private $password = 'root';

    private function __construct()
    {
        $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
        $this->dbh = new DB($this->dsn, $this->user, $this->password, $options);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function GetInstance()
    {
        if (empty(self::$instances))
        {
            $object = __CLASS__;
            self::$instances = new $object;
        }
        return self::$instances;
    }
    public function GlobalVar(){}
    //Comment
}
?>
