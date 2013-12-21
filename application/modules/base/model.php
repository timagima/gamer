<?php
namespace application\modules\base;
use application\core\mvc\MainModel;
use PDO;
use classes\OftenFunctions;

class Model extends MainModel
{

    public function __construct()
    {
        parent::__construct();
    }
    public function AddCompletedGame()
    {
        //$result = [];
        //$city       = $this -> _p['city'];
        //$check_city = $this -> conn -> dbh -> query("SELECT name FROM city WHERE name = '". $city ."' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
        //if ( !empty($check_city) ) {

            $game = $this -> _p['game'];
            $gameLevel = $this -> _p['game-level'];
            $gameDescription = $this -> _p['game-description'];
            $idGame = $this -> conn -> dbh -> query("SELECT id FROM games WHERE name='" . $game . "'")->fetchAll(PDO::FETCH_ASSOC);
            $idGame = (int)$idGame[0]['id'];
            $idLevel = $this -> conn -> dbh -> query("SELECT id FROM level WHERE name='" . $gameLevel . "' AND id_game=".$idGame)->fetchAll(PDO::FETCH_ASSOC);
            $idLevel = (int)$idLevel[0]['id'];
            $idUser = $_SESSION['user-data']['id']*1;
            $stmt = $this -> conn -> dbh -> prepare("INSERT INTO user_completed_games (id_user, id_game, id_level, about_game) VALUES(:idUser, :idGame, :idLevel, :gameDescription)");
            $stmt -> bindParam(":idUser",          $idUser, PDO::PARAM_STR);
            $stmt -> bindParam(":idGame",          $idGame, PDO::PARAM_STR);
            $stmt -> bindParam(":idLevel",         $idLevel, PDO::PARAM_STR);
            $stmt -> bindParam(":gameDescription", $gameDescription, PDO::PARAM_INT);
            $getQuery = $stmt -> execute() or die(false);;
            if($getQuery){
                $result['game_success'] = true;
                return json_encode($result);
            }else{
                $result['game_success'] = false;
                return json_encode($result);
            }
            //$result['city_success'] = true;
            //$this -> GetRefreshDataUser();
        //} else {
        //    $result['city_success'] = false;
        //}
        //return json_encode($result);

    }

    public function GetUserCompletedGames()
    {
        return $this->conn->dbh->query("SELECT ucg.num_quest, ucg.start_date, ucg.end_date, ucg.post_date, ucg.about_game, games.name as game, games.source_img_b, games.source_img_s, level.name as level, level.description as level_description
                                        FROM user_completed_games ucg, games, level, users
                                        WHERE ucg.id_user=users.id
                                        AND ucg.id_game=games.id
                                        AND ucg.id_level=level.id
                                        AND users.id=".$_SESSION['user-data']['id']." ORDER BY game")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function GetGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetGenre()
    {
        return $this->conn->dbh->query("SELECT * FROM genre")->fetchAll(PDO::FETCH_OBJ);
    }
    public function GetRanks()
    {
        return $this->conn->dbh->query("SELECT * FROM user_rank WHERE `numeric` <= '".$_SESSION['user-data']['complete_games']."' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }
    public function GetAwards()
    {
        return $this->conn->dbh->query("SELECT * FROM user_award WHERE id_user <= '".$_SESSION['user-data']['id']."' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
    }

    public function DeleteAvatar()
    {
        $this->conn->dbh->exec("UPDATE users SET img_avatar = '', img_avatar_b = '' WHERE id = ".(int)$_SESSION['user-data']['id']);
        $this->GetRefreshDataUser();
    }

    public function SetAvatar($fileSmall, $fileBig)
    {
        $stmt = $this->conn->dbh->prepare("UPDATE users SET img_avatar = ?, img_avatar_b = ? WHERE id = ".(int)$_SESSION['user-data']['id']);
        $stmt->execute(array($fileSmall, $fileBig));
        $this->GetRefreshDataUser();
    }
    public function SetDir($dir)
    {
        $stmt = $this->conn->dbh->prepare("UPDATE users SET path = ? WHERE id = ?");
        $stmt->execute(array($dir, $_SESSION['user-data']['id']));
        $this->GetRefreshDataUser();
    }


      // ПОЛУЧАЕТ НАЗВАНИЕ ГОРОДОВ

    public function GetCities() {
        $result = [];

        $query =  $this -> _p['query'];
        $limit = !empty($this -> _p['limit']) ? $this -> _p['limit'] : '10';

        if ( !empty($query) ) {
            $query = OftenFunctions::getCorrectText($query);
            $query = ' AND city.name like "%'. $query.'%"';

            $sql   = "SELECT DISTINCT city.name FROM city WHERE city.socr in ('г', 'п', 'аул') ". $query ." LIMIT ". $limit;
            $sql   = $this -> conn -> dbh -> query($sql);
            foreach ( $sql as $value ) {
                $result['suggestions'][] = $value[0];
            }
        }

        return json_encode($result);
    }

    public function GetGame() {
        $result = [];

        $query =  $this -> _p['query'];
        $limit = !empty($this -> _p['limit']) ? $this -> _p['limit'] : '10';

        if ( !empty($query) ) {
            //$query = OftenFunctions::getCorrectText($query);
            $query = ' games.name like "%'. $query.'%"';

            $sql   = "SELECT DISTINCT games.name FROM games WHERE ". $query ." LIMIT ". $limit;
            $sql   = $this -> conn -> dbh -> query($sql);
            foreach ( $sql as $value ) {
                $result['suggestions'][] = $value[0];
            }
        }

        return json_encode($result);
    }

    public function GetGameLevel() {
        $result = array();
        $game =  $this -> _p['game'];
        //$game = "half - life";
        if ( !empty($game) ) {
            $sql   = "SELECT level.name FROM level, games WHERE level.id_game=games.id AND games.name='".$game."'";
            $sql   = $this -> conn -> dbh -> query($sql);
            foreach($sql as $level){
                $result[] = $level[0];
            }
        }
        return json_encode($result);
    }

    public function GetIpAdress()
    {

    }
}

