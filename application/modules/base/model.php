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
    public function MakeUnixTime($date)
    {
        if (is_array($date)) {
            $date[0] = intval($date[0]);
            $date[1] = intval($date[1]);
            $date[2] = intval($date[2]);
            $date[0] = (($date[0] < 0) || ($date[0] > 31)) ? 1 : $date[0];
            $date[1] = (($date[1] < 0) || ($date[1] > 12)) ? 1 : $date[1];
            $date[2] = (($date[2] < 1974) || ($date[2] > 2014)) ? 2014 : $date[2];
            return mktime(12, 0, 0, $date[1], $date[0], $date[2]);
        } else {
            return 0;
        }
    }

    //Добавление пройденной игры в БД
    public function AddCompletedGame()
    {
        //todo сделать проверку начало и конца прохождения игры, согласно логики времени
        $gameStartDate = $this->_p['game-start-date'];
        $gameEndDate = $this->_p['game-end-date'];
        $gameStartDate = (((int)$gameStartDate) > 0) ? $this->MakeUnixTime(explode("-", $gameStartDate, 3)) : 0;
        $gameEndDate = (((int)$gameEndDate) > 0) ? $this->MakeUnixTime(explode("-", $gameEndDate, 3)) : 0;
        $idLevelGame = explode("\$", $this->_p['game-level']);
        $idGame = (isset($idLevelGame[1]))?(int)$idLevelGame[1]:0;
        $idLevel = (int)$idLevelGame[0];
        $questQount = (int)$this->_p['quest-qount'];
        $idGamePassing = (int)$this->_p['game-passing'];
        $idUser = (int)$_SESSION['user-data']['id'];
        $gameDescription = $this->_p['game-description'];
        $postDate = time();
        $checkAddedGame = $this->conn->dbh->query("SELECT id_game FROM user_completed_games WHERE id_game=" . $idGame . " AND id_user=" . $idUser)->fetch(PDO::FETCH_ASSOC);
        $checkGame = $this->conn->dbh->query("SELECT name FROM games WHERE id = ".$idGame)->fetch(PDO::FETCH_ASSOC);
        if ($checkAddedGame === false && $checkGame !== false) {
            $stmt = $this->conn->dbh->prepare("INSERT INTO user_completed_games (id_user, id_game, id_level, about_game, start_date, end_date, post_date, num_quest, id_type_completed_game) VALUES(:idUser, :idGame, :idLevel, :gameDescription, :startDate, :endDate, :postDate, :questQount, :idGamePassing)");
            $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
            $stmt->bindParam(":idGame", $idGame, PDO::PARAM_INT);
            $stmt->bindParam(":idLevel", $idLevel, PDO::PARAM_INT);
            $stmt->bindParam(":startDate", $gameStartDate, PDO::PARAM_INT);
            $stmt->bindParam(":endDate", $gameEndDate, PDO::PARAM_INT);
            $stmt->bindParam(":postDate", $postDate, PDO::PARAM_INT);
            $stmt->bindParam(":questQount", $questQount, PDO::PARAM_INT);
            $stmt->bindParam(":idGamePassing", $idGamePassing, PDO::PARAM_INT);
            $stmt->bindParam(":gameDescription", $gameDescription, PDO::PARAM_STR);
            $getQuery = $stmt->execute();
            return ("addGame");
        } elseif ($checkAddedGame !== false) {
            return ("isGame");
        } elseif ($checkGame === false) {
            return ("notGame");
        }
    }

    //Изменение ранее добавленной игры
    public function UpdateAddedGame()
    {
        //todo сделать проверку начало и конца прохождения игры, согласно логики времени
        $gameStartDate = $this->_p['game-start-date'];
        $gameEndDate = $this->_p['game-end-date'];
        $gameStartDate = (((int)$gameStartDate) > 0) ? $this->MakeUnixTime(explode("-", $gameStartDate, 3)) : 0;
        $gameEndDate = (((int)$gameEndDate) > 0) ? $this->MakeUnixTime(explode("-", $gameEndDate, 3)) : 0;
        $idGame = (int)$this->_p['game-id'];
        $idLevel = (int)$this->_p['level-id'];
        $questQount = (int)$this->_p['quest-qount'];
        $idGamePassing = (int)$this->_p['game-passing'];
        $idUser = (int)$_SESSION['user-data']['id'];
        $gameDescription = $this->_p['game-description'];

        $stmt = $this->conn->dbh->prepare("UPDATE user_completed_games
                                            SET id_level=:idLevel, id_type_completed_game=:idGamePassing, num_quest=:questQount, start_date=:startDate, end_date=:endDate, about_game=:gameDescription
                                            WHERE id_user=:idUser AND id_game=:idGame");
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idGame", $idGame, PDO::PARAM_INT);
        $stmt->bindParam(":idLevel", $idLevel, PDO::PARAM_INT);
        $stmt->bindParam(":startDate", $gameStartDate, PDO::PARAM_INT);
        $stmt->bindParam(":endDate", $gameEndDate, PDO::PARAM_INT);
        $stmt->bindParam(":questQount", $questQount, PDO::PARAM_INT);
        $stmt->bindParam(":idGamePassing", $idGamePassing, PDO::PARAM_INT);
        $stmt->bindParam(":gameDescription", $gameDescription, PDO::PARAM_STR);
        $getQuery = $stmt->execute();
        return ("GameUpdated");
    }

    //Получение пройденных игр из БД
    public function GetUserCompletedGames()
    {
        return $this->conn->dbh->query("SELECT ucg.num_quest, ucg.start_date, ucg.end_date, ucg.post_date, ucg.about_game,
                                            games.name as game, games.source_img_b, games.source_img_s, games.id,
                                            level.name as level, level.description as level_description,
                                            tcg.name as type_complete_game,
                                            genre.name as genre
                                        FROM user_completed_games ucg
                                        LEFT JOIN users ON users.id=ucg.id_user
                                        LEFT JOIN games ON games.id=ucg.id_game
                                        LEFT JOIN level ON level.id=ucg.id_level
                                        LEFT JOIN type_complete_game tcg ON tcg.id=ucg.id_type_completed_game
                                        LEFT JOIN genre ON genre.id=games.genre_id
                                        WHERE users.id=" . $_SESSION['user-data']['id'] . " ORDER BY game")->fetchAll(PDO::FETCH_ASSOC);
    }

    //Получение информации о пройденной игре пользователя по ИД игры
    public function GetGameView($idGame)
    {
        return $this->conn->dbh->query("SELECT ucg.num_quest, ucg.start_date, ucg.end_date, ucg.post_date, ucg.about_game, ucg.id_game,
                                            games.name as game, games.source_img_b, games.source_img_s,
                                            level.name as level, level.description as level_description,
                                            tcg.name as type_complete_game,
                                            genre.name as genre
                                        FROM user_completed_games ucg
                                        LEFT JOIN users ON users.id=ucg.id_user
                                        LEFT JOIN games ON games.id=ucg.id_game
                                        LEFT JOIN level ON level.id=ucg.id_level
                                        LEFT JOIN type_complete_game tcg ON tcg.id=ucg.id_type_completed_game
                                        LEFT JOIN genre ON genre.id=games.genre_id
                                        WHERE users.id=" . $_SESSION['user-data']['id'] . " AND ucg.id_game=".$idGame." ORDER BY game ")->fetch(PDO::FETCH_ASSOC);

    }

    //Получение игр из БД
    public function GetGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }

    //Получение массива типов прохождения игр
    public function GetTypeCompleteGame()
    {
        return $this->conn->dbh->query("SELECT * FROM type_complete_game")->fetchAll(PDO::FETCH_ASSOC);
    }

    //Получение массива уровней сложности игры по её ИД
    public function GetLevels($idGame)
    {
        return $this->conn->dbh->query("SELECT id, name FROM level WHERE id_game=".$idGame)->fetchAll(PDO::FETCH_ASSOC);
    }

    //Автокомплит названия игры при наборе в модальной форме
    public function GetGame()
    {
        $result = [];
        $query = "%{$this->_p['query']}%";
        $limit = !empty($this->_p['limit']) ? (int)$this->_p['limit'] : 10;
        if (!empty($query)) {
            $sql = $this->conn->dbh->prepare('SELECT DISTINCT games.name FROM games WHERE games.name like :game LIMIT :limit');
            $sql->bindParam(":game", $query, PDO::PARAM_STR);
            $sql->bindParam(":limit", $limit, PDO::PARAM_INT);
            $sql->execute();
            foreach ($sql as $value) {
                $result['suggestions'][] = $value[0];
            }
        }
        return json_encode($result);
    }

    //Получение уровней сложности, в зависимости от игры
    public function GetGameLevel()
    {
        $result = array();
        $game = $this->_p['game'];
        if (!empty($game)) {
            $sql = $this->conn->dbh->prepare("SELECT l.name, l.id as id_level, g.id as id_game FROM `level` l LEFT JOIN `games` g ON g.id=l.id_game WHERE g.name=:game");
            $sql->bindParam(":game", $game, PDO::PARAM_STR);
            $sql->execute();
            foreach ($sql as $level) {
                $result[] = $level['name'] . "$" . $level['id_level'] . "$" . $level['id_game'];
            }
            //$test = $this->CheckAddedGames($level[2]);
            //$result[] = $this->CheckAddedGames($level[2]);

        }
        return json_encode($result);
    }

    //Метод проверки наличия игры в таблеце "Пройденных игр"
    public function CheckAddedGames($idGame)
    {
        $idGame = (int)$idGame;
        $idUser = (int)$_SESSION['user-data']['id'];
        $stmt = $this->conn->dbh->prepare("SELECT id_game FROM user_completed_games WHERE id_game = :idGame AND id_user= :idUser");
        $stmt->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $stmt->bindParam(":idGame", $idGame, PDO::PARAM_INT);
        $stmt->execute();
        return (count($stmt->fetchAll())>0) ? "true" : "false";
    }
}

