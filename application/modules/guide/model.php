<?php
namespace application\modules\guide;
use application\core\mvc\MainModel;
use PDO;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function ListGames()
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetGame($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games WHERE id = " . $id)->fetch(PDO::FETCH_OBJ);
    }

}