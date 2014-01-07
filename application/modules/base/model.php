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

    //Функия принимает массив(из 3-х ячеек) и возвращает Юникс метку времени
    public function MakeUnixTime($date){
        if(is_array($date)){
            $date[0] = intval($date[0]);
            $date[1] = intval($date[1]);
            $date[2] = intval($date[2]);
            $date[0] = ( ($date[0]<0) || ($date[0]>31) ) ? 1 : $date[0];
            $date[1] = ( ($date[1]<0) || ($date[1]>12) ) ? 1 : $date[1];
            $date[2] = ( ($date[2]<1974) || ($date[2]>2014) ) ? 2014 : $date[2];
            return mktime(12, 0, 0, $date[1], $date[0], $date[2]);
        }else{
            return $date;
        }
    }
    //Добавление пройденной игры в БД
    public function AddCompletedGame()
    {
        $gameStartDate = $this -> _p['game-start-date'];
        $gameEndDate = $this -> _p['game-end-date'];
        $notGameStartDate = $this -> _p['not-game-start'];
        $notGameEndDate = $this -> _p['not-game-end'];
        $gameStartDate = ($notGameStartDate === "true" ) ? "" : explode("-", $gameStartDate, 3);
        $gameStartDate = $this -> MakeUnixTime($gameStartDate);
        $gameEndDate = ($notGameEndDate === "true" ) ? "" : explode("-", $gameEndDate, 3);
        $gameEndDate = $this -> MakeUnixTime($gameEndDate);
        $idLevelGame = explode("\$", $this -> _p['game-level']);
        $idGame = (int)$idLevelGame[1];
        $idLevel = (int)$idLevelGame[0];
        $idUser = (int)$_SESSION['user-data']['id'];
        $gameDescription = $this -> _p['game-description'];
        $postDate = time();
        $checkGame = $this -> conn -> dbh -> query("SELECT id_game FROM user_completed_games WHERE id_game=".$idGame." AND id_user=".$idUser) -> fetch(PDO::FETCH_ASSOC);
        if($checkGame===false){
            $stmt = $this -> conn -> dbh -> prepare("INSERT INTO user_completed_games (id_user, id_game, id_level, about_game, start_date, end_date, post_date) VALUES(:idUser, :idGame, :idLevel, :gameDescription, :startDate, :endDate, :postDate)");
            $stmt -> bindParam(":idUser",          $idUser, PDO::PARAM_INT);
            $stmt -> bindParam(":idGame",          $idGame, PDO::PARAM_INT);
            $stmt -> bindParam(":idLevel",         $idLevel, PDO::PARAM_INT);
            $stmt -> bindParam(":startDate",       $gameStartDate, PDO::PARAM_INT);
            $stmt -> bindParam(":endDate",         $gameEndDate, PDO::PARAM_INT);
            $stmt -> bindParam(":postDate",        $postDate, PDO::PARAM_INT);
            $stmt -> bindParam(":gameDescription", $gameDescription, PDO::PARAM_STR);
            $getQuery = $stmt -> execute();
            return ("addGame");
        }else{
            return ("isGame");
        }
    }

    //Получение пройденных игр из БД
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

    //Автокомплит названия игры при наборе в модальной форме
    public function GetGame() {
        $result = [];
        $query =  "%{$this->_p['query']}%";
        $limit = !empty($this -> _p['limit']) ? (int)$this -> _p['limit'] : 10;
        if ( !empty($query) ) {
            $sql = $this->conn->dbh->prepare('SELECT DISTINCT games.name FROM games WHERE games.name like :game LIMIT :limit');
            $sql->bindParam(":game", $query, PDO::PARAM_STR);
            $sql->bindParam(":limit", $limit, PDO::PARAM_INT);
            $sql->execute();
            foreach ( $sql as $value ) {
                $result['suggestions'][] = $value[0];
            }
        }
        return json_encode($result);
    }

    //Получение уровней сложности, в зависимости от игры
    public function GetGameLevel() {
        $result = array();
        $game =  $this -> _p['game'];
        if ( !empty($game) ) {
            $sql = $this -> conn -> dbh -> prepare("SELECT l.name, l.id as id_level, g.id as id_game FROM `level` l LEFT JOIN `games` g ON g.id=l.id_game WHERE g.name=:game");
            $sql->bindParam(":game", $game, PDO::PARAM_STR);
            $sql->execute();
            foreach($sql as $level){
                $result[] = $level['name']."$".$level['id_level']."$".$level['id_game'];
            }
        }
        return json_encode($result);
    }

    public function GetIpAdress()
    {

    }
}

