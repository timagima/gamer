<?php
namespace application\modules\about;
use application\core\mvc\MainModel as MainModel;
use PDO;


class Model extends MainModel
{
    function __construct()
    {
        parent::__construct();

    }

    public function InsertAboutMessage()
    {
        $query = $this->conn->dbh->prepare("INSERT INTO main_about_msg SET user_id = ?, msg = ?");
        $query->execute(array($_SESSION['user-data']['id'], $this->_p['message']));
    }
    public function GetLastTournament()
    {


    }
    public function GetLastWinner()
    {
        $stmt = $this->conn->dbh->prepare("SELECT u.img_avatar, u.first_name, u.last_name, u.nick
         FROM info_winner i  LEFT JOIN users u ON u.id = i.id_user ORDER BY i.id DESC LIMIT 3");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
