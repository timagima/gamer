<?php
namespace application\core\mvc;
use application\core\config\config;
use classes\cryptography;
use classes\Plinq;



use PDO;
class MainModel
{
    public $conn, $ipUser, $crypt, $plinq;
    public $_p, $_g = array();
    public function __construct()
    {


        $this->_p = $_POST;
        $this->_g = $_GET;
        $this->ipUser = $_SERVER['REMOTE_ADDR'];
        $this->conn = Config::GetInstance();
        $this->crypt = new Cryptography();
        if(empty($_SESSION['auth']))
            $this->AuthCookie();
    }

    /* Модель обычно включает методы выборки данных, это могут быть:
            > методы нативных библиотек pgsql или mysql;
            > методы библиотек, реализующих абстракицю данных. Например, методы библиотеки PEAR MDB2;
            > методы ORM;
            > методы для работы с NoSQL;
            > и др. */

    public function GetData(){}

    public function CountQuery()
    {
        $count = $this->conn->dbh->countQuery;
        return ($count != 0) ? $count : 0;
    }
    private function AuthCookie()
    {
        if(isset($_COOKIE['key']) && isset($_COOKIE['hash']))
        {
            $arrKey = explode("=", base64_decode($this->crypt->HexToStr($_COOKIE['key'])));
            $id = (int)$arrKey[0];
            $time = (int)$arrKey[1];
            $stmt = $this->conn->dbh->prepare("SELECT id FROM users WHERE id = :id AND temp_key = :hash AND date_last_visit = :time");
            $stmt->bindParam(":hash", $_COOKIE['hash'], PDO::PARAM_STR);
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->bindParam(":time", $time, PDO::PARAM_INT);
            $stmt->execute();
            if($stmt->rowCount() == 1)
            {
                $hash = md5(rand() . date("d.m.Y H:i"));
                $query = $this->conn->dbh->prepare("UPDATE users SET temp_key = ?, date_last_visit = UNIX_TIMESTAMP(NOW()), num_try_error_auth = 0, ip = ? WHERE id = ?");
                $query->execute(array($hash, $this->ipUser, $id));
                $arrData = $this->conn->dbh->query("SELECT * FROM users WHERE id = " . $id)->fetch(PDO::FETCH_ASSOC);
                $this->RememberMe($hash, $arrData['id'], $arrData['date_last_visit']);
                $_SESSION['user-data'] = $arrData;
                $_SESSION['auth'] = 1;
                $this->ConfirmAuth();
            }
            else
            {
                return false;
            }
        }
        return false;
    }
    protected function RememberMe($hash, $id, $date)
    {
        $key = $this->crypt->StrToHex(base64_encode($id .'='. $date));
        setcookie("hash", $hash, time() + 3600 * 24 * 365);
        setcookie("key", $key, time() + 3600 * 24 * 365);
    }

    protected function RedirectAuth($data)
    {
        session_destroy();
        unset($_SESSION);
        session_start();
        $_SESSION['user-data'] = $data;
        $_SESSION['auth'] = 1;
        echo "auth-true";
        exit();
    }
    private function ConfirmAuth()
    {
        if(isset($_SESSION['auth']) && $_SESSION['auth'] == '1')
        {
            header('Location: /profile');
            exit();
        }
    }
    public function ExistPhone()
    {
        $stmt = $this->conn->dbh->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->execute(array($_SESSION['sms-phone']));
        return ($stmt->rowCount() > 0) ? true : false;

    }
    public function ExistEmail()
    {
        $stmt = $this->conn->dbh->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute(array($this->_p['email']));
        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function Mail($subject, $to, $from, $object)
    {
        $object->CreateTo($to);
        $object->CreateSubject($subject);
        $object->CreateFrom($from);
        $object->SetHtml();
        $object->SendMail();
    }
    public function GetTplMailMsg($user, $email, $url, $path)
    {
        // метод формирует шаблон отправленного email
        ob_start();
        include $_SERVER['DOCUMENT_ROOT'] . $path;
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    public function GetRefreshDataUser()
    {
        $stmt = $this->conn->dbh->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute(array($_SESSION['user-data']['id']));
        $_SESSION['user-data'] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

}