<?php
namespace application\modules\administration\tournament;
use application\core\mvc\MainModel;
use PDO;
use classes\render;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }


    public function GetData()
    {
        return $this->conn->dbh->query("SELECT * FROM heroes_games LIMIT 0, 500 ")->fetchAll(PDO::FETCH_ASSOC);
    }


    // начало управление турнирами
    public function ListTournaments()
    {
        return $this->conn->dbh->query("SELECT * FROM tournaments LIMIT 0, 500 ")->fetchAll(PDO::FETCH_OBJ);
    }
    public function ListGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }
    public function ListMemberTournament()
    {
        return $this->conn->dbh->query("SELECT mt.*, u.nick  FROM history_members_tournaments mt LEFT JOIN users u ON u.id = mt.id_user WHERE mt.id_tournament = " . (int)$this->_g['id'])->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetTournament()
    {
        return $this->conn->dbh->query("SELECT * FROM tournaments WHERE id = " . (int)$this->_g['id'])->fetch(PDO::FETCH_OBJ);
    }

    public function AddTournament($params)
    {
        $startDateReg = strtotime($params['start_date_reg']);
        $endDateReg = strtotime($params['end_date_reg']);
        $startDate = strtotime($params['start_date']);
        $endDate = strtotime($params['end_date']);
        $query = $this->conn->dbh->prepare("INSERT INTO tournaments SET id_game = :id_game, state = :state, pay = :pay, header = :header, rules = :rules, video_rules = :video_rules,
                                            start_date_reg = :start_date_reg, end_date_reg = :end_date_reg, start_date = :start_date, end_date = :end_date,
                                            title = :title, keywords = :keywords, description = :description, source_img = :source_img");

        $query->bindParam(":id_game", $params['id_game'], PDO::PARAM_INT);
        $query->bindParam(":start_date_reg", $startDateReg, PDO::PARAM_INT);
        $query->bindParam(":end_date_reg", $endDateReg, PDO::PARAM_INT);
        $query->bindParam(":start_date", $startDate, PDO::PARAM_INT);
        $query->bindParam(":end_date", $endDate, PDO::PARAM_INT);
        $query->bindParam(":state", $params['state'], PDO::PARAM_INT);
        $query->bindParam(":pay", $params['pay'], PDO::PARAM_INT);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":rules", $params['rules'], PDO::PARAM_STR);
        $query->bindParam(":video_rules", $params['video_rules'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        return $this->GetById($params['id'], "tournaments");
    }

    public function EditTournament($params)
    {
        $startDateReg = strtotime($params['start_date_reg']);
        $endDateReg = strtotime($params['end_date_reg']);
        $startDate = strtotime($params['start_date']);
        $endDate = strtotime($params['end_date']);
        $query = $this->conn->dbh->prepare("UPDATE tournaments SET id_game = :id_game, state = :state, pay = :pay, header = :header, rules = :rules, video_rules = :video_rules,
                                            start_date_reg = :start_date_reg, end_date_reg = :end_date_reg, start_date = :start_date, end_date = :end_date,
                                            title = :title, keywords = :keywords, description = :description, source_img = :source_img WHERE id=:id");
        $query->bindParam(":id", $params['id'], PDO::PARAM_INT);
        $query->bindParam(":id_game", $params['id_game'], PDO::PARAM_INT);
        $query->bindParam(":start_date_reg", $startDateReg, PDO::PARAM_INT);
        $query->bindParam(":end_date_reg", $endDateReg, PDO::PARAM_INT);
        $query->bindParam(":start_date", $startDate, PDO::PARAM_INT);
        $query->bindParam(":end_date", $endDate, PDO::PARAM_INT);
        $query->bindParam(":state", $params['state'], PDO::PARAM_INT);
        $query->bindParam(":pay", $params['pay'], PDO::PARAM_INT);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":rules", $params['rules'], PDO::PARAM_STR);
        $query->bindParam(":video_rules", $params['video_rules'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        return $this->GetById($params['id'], "tournaments");
    }



    public function GetWinner()
    {
        return $this->conn->dbh->query("SELECT * FROM winners WHERE id_tournament = " . (int)$this->_g['id'])->fetch(PDO::FETCH_OBJ);
    }


    public function AddWinner($params)
    {
        $rowCount = $this->conn->dbh->query("SELECT id FROM winners WHERE id_tournament = ".(int)$params['id_tournament'])->rowCount();
        $query = ($rowCount == 1)
            ? "UPDATE winners SET winner = :id_user, text = :text, title = :title, keywords = :keywords, description = :description WHERE id_tournament = :id_tournament"
            : "INSERT INTO winners SET winner = :id_user, text = :text, title = :title, keywords = :keywords, description = :description, id_tournament = :id_tournament";
        $query = $this->conn->dbh->prepare($query);
        $query->bindParam(":id_tournament", $params['id_tournament'], PDO::PARAM_INT);
        $query->bindParam(":id_user", $params['winner'], PDO::PARAM_INT);
        $query->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->execute();
    }

    // конец управление турнирами


    /*public function Add($params)
    {
        $query = $this->conn->dbh->prepare("INSERT INTO heroes_games SET id_game = 186, name = :name, description = :description, source_img = :source_img");
        $query->bindParam(":name", $params['name'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        return $this->GetById($id);
    }

    public function Edit($params)
    {
        $query = $this->conn->dbh->prepare("UPDATE heroes_games SET id_game = 186, name = :name, description = :description, source_img = :source_img WHERE id=:id");
        $query->bindParam(":id", $params['id'], PDO::PARAM_INT);
        $query->bindParam(":name", $params['name'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->execute();
        return $this->GetById($params['id']);
    }*/





    public function GetById($id, $table)
    {
        return $this->conn->dbh->query("SELECT *  FROM ".$table." WHERE id=" . $id)->fetch();
    }

    public function Delete($id)
    {
        $query = $this->conn->dbh->prepare("DELETE FROM heroes_games WHERE id=:id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }
}