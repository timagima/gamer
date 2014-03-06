<?php
namespace application\modules\administration\about;
use application\core\mvc\MainModel;
use classes\render;
use PDO;

class Model extends MainModel
{
    function __construct()
    {
        parent::__construct();

    }

    public function countMessage()
    {
        $stmt = $this->conn->dbh->prepare("SELECT  COUNT(`index`) FROM message_contact WHERE `index` = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_NUM);
    }
    public function updateContact()
    {
        $query = $this->conn->dbh->prepare("UPDATE `message_contact` SET `index`= 1");
        $query->execute();

    }
    public function getMessageContact()
    {
        $stmt = $this->conn->dbh->prepare("SELECT m.id_rubric, m.id_user, m.name_user, m.email, m.text
        FROM rubric_contact r LEFT JOIN message_contact m ON m.id_rubric = r.id WHERE `index` = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}