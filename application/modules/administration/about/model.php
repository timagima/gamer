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
    public function updateContact($id)
    {
        $query = $this->conn->dbh->prepare("UPDATE `message_contact` SET `index`= 1 WHERE `id` = $id");
        $query->execute();

    }
    public function getMessageContact()
    {
        $stmt = $this->conn->dbh->prepare("SELECT m.id_user, m.name_user, m.email, m.text, r.name_rubric, u.nick,m.id
        FROM message_contact m  LEFT JOIN rubric_contact r ON m.id_rubric = r.id
        LEFT JOIN users u ON m.id_user = u.id
        WHERE `index` = 0");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}