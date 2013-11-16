<?php
namespace application\modules\administration\games;
use application\core\mvc\MainModel as MainModel;
use classes\render as Render;
use PDO;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    /* Начало добавление списка игр*/
    public function ListGames()
    {
        return $this->conn->dbh->query("SELECT * FROM games")->fetchAll(PDO::FETCH_OBJ);
    }
    /* Конец добавление списка игр*/

    public function SearchGameAjax()
    {
        $search = "%".$this->_p['search-game']."%";
        $stmt = $this->conn->dbh->prepare("SELECT * FROM games WHERE name LIKE :game");
        $stmt->bindParam(":game", $search, PDO::FETCH_ASSOC);
        $stmt->execute();
        return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
    public function GetGame($id)
    {
        return $this->conn->dbh->query("SELECT * FROM games WHERE id=".$id)->fetch(PDO::FETCH_OBJ);
    }
    public function GetMainPageGame($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games WHERE id_game = ".$id)->fetch(PDO::FETCH_OBJ);
    }

    public function AddMainPageGame($objGame)
    {
        $dateReleaseWorld = strtotime($this->_p['date_release_world']);
        $dateReleaseRussia = strtotime($this->_p['date_release_russia']);
        $query = $this->conn->dbh->prepare("INSERT INTO main_page_games SET date = UNIX_TIMESTAMP(NOW()), id_game = :id_game, name = :name, game_mode = :game_mode,
                text = :text, title = :title, description = :description, keywords = :keywords,
                date_release_world = :date_release_world,
                date_release_russia = :date_release_russia,
                publisher = :publisher,
                publisher_link = :publisher_link,
                publisher_russia = :publisher_russia,
                publisher_russia_link = :publisher_russia_link,
                developer = :developer,
                developer_link = :developer_link,
                official_site = :official_site,
                official_site_link = :official_site_link,
                game_engine = :game_engine,
                genre = :genre,
                distribution = :distribution,
                video_link = :video_link,
                sr_os = :sr_os, sr_cpu = :sr_cpu, sr_ram = :sr_ram, sr_video = :sr_video, sr_hdd = :sr_hdd,
                source_img = :source_img, source_img_small = :source_img_small");
        $query->bindParam(":name", $this->_p['name'], PDO::PARAM_STR);
        $query->bindParam(":game_mode", $this->_p['game_mode'], PDO::PARAM_STR);
        $query->bindParam(":id_game", $objGame->id, PDO::PARAM_INT);
        $query->bindParam(":text", $this->_p['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $this->_p['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $this->_p['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $this->_p['keywords'], PDO::PARAM_STR);
        $query->bindParam(":date_release_world", $dateReleaseWorld, PDO::PARAM_INT);
        $query->bindParam(":date_release_russia", $dateReleaseRussia, PDO::PARAM_INT);
        $query->bindParam(":publisher", $this->_p['publisher'], PDO::PARAM_STR);
        $query->bindParam(":publisher_link", $this->_p['publisher_link'], PDO::PARAM_STR);
        $query->bindParam(":publisher_russia", $this->_p['publisher_russia'], PDO::PARAM_STR);
        $query->bindParam(":publisher_russia_link", $this->_p['publisher_russia_link'], PDO::PARAM_STR);
        $query->bindParam(":developer", $this->_p['developer'], PDO::PARAM_STR);
        $query->bindParam(":developer_link", $this->_p['developer_link'], PDO::PARAM_STR);
        $query->bindParam(":official_site", $this->_p['official_site'], PDO::PARAM_STR);
        $query->bindParam(":official_site_link", $this->_p['official_site_link'], PDO::PARAM_STR);
        $query->bindParam(":game_engine", $this->_p['game_engine'], PDO::PARAM_STR);
        $query->bindParam(":genre", $this->_p['genre'], PDO::PARAM_STR);
        $query->bindParam(":distribution", $this->_p['distribution'], PDO::PARAM_STR);
        $query->bindParam(":video_link", $this->_p['video_link'], PDO::PARAM_STR);
        $query->bindParam(":sr_os", $this->_p['sr_os'], PDO::PARAM_STR);
        $query->bindParam(":sr_cpu", $this->_p['sr_cpu'], PDO::PARAM_STR);
        $query->bindParam(":sr_ram", $this->_p['sr_ram'], PDO::PARAM_STR);
        $query->bindParam(":sr_video", $this->_p['sr_video'], PDO::PARAM_STR);
        $query->bindParam(":sr_hdd", $this->_p['sr_hdd'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $objGame->source_img, PDO::PARAM_STR);
        $query->bindParam(":source_img_small", $objGame->source_img_small, PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        //return $this->GetById($id);

    }
    public function EditMainPageGame($objGame)
    {
        $dateReleaseWorld = strtotime($this->_p['date_release_world']);
        $dateReleaseRussia = strtotime($this->_p['date_release_russia']);
        $query = $this->conn->dbh->prepare("UPDATE main_page_games SET `name` = :name, game_mode = :game_mode, text = :text, title = :title,
        description = :description,
        keywords = :keywords,
        date_release_world = :date_release_world,
        date_release_russia = :date_release_russia,
        publisher = :publisher,
        publisher_link = :publisher_link,
        publisher_russia = :publisher_russia,
        publisher_russia_link = :publisher_russia_link,
        developer = :developer,
        developer_link = :developer_link,
        official_site = :official_site,
        official_site_link = :official_site_link,
        game_engine = :game_engine,
        genre = :genre,
        distribution = :distribution,
        video_link = :video_link,
        sr_os = :sr_os, sr_cpu = :sr_cpu, sr_ram = :sr_ram, sr_video = :sr_video, sr_hdd = :sr_hdd,
        source_img = :source_img, source_img_small = :source_img_small WHERE id = :id");
        $query->bindParam(":id", $this->_p['id'], PDO::PARAM_INT);
        $query->bindParam(":name", $this->_p['name'], PDO::PARAM_STR);
        $query->bindParam(":game_mode", $this->_p['game_mode'], PDO::PARAM_STR);
        $query->bindParam(":text", $this->_p['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $this->_p['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $this->_p['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $this->_p['keywords'], PDO::PARAM_STR);
        $query->bindParam(":date_release_world", $dateReleaseWorld, PDO::PARAM_INT);
        $query->bindParam(":date_release_russia", $dateReleaseRussia, PDO::PARAM_INT);
        $query->bindParam(":publisher", $this->_p['publisher'], PDO::PARAM_STR);
        $query->bindParam(":publisher_link", $this->_p['publisher_link'], PDO::PARAM_STR);
        $query->bindParam(":publisher_russia", $this->_p['publisher_russia'], PDO::PARAM_STR);
        $query->bindParam(":publisher_russia_link", $this->_p['publisher_russia_link'], PDO::PARAM_STR);
        $query->bindParam(":developer", $this->_p['developer'], PDO::PARAM_STR);
        $query->bindParam(":developer_link", $this->_p['developer_link'], PDO::PARAM_STR);
        $query->bindParam(":official_site", $this->_p['official_site'], PDO::PARAM_STR);
        $query->bindParam(":official_site_link", $this->_p['official_site_link'], PDO::PARAM_STR);
        $query->bindParam(":game_engine", $this->_p['game_engine'], PDO::PARAM_STR);
        $query->bindParam(":genre", $this->_p['genre'], PDO::PARAM_STR);
        $query->bindParam(":distribution", $this->_p['distribution'], PDO::PARAM_STR);
        $query->bindParam(":video_link", $this->_p['video_link'], PDO::PARAM_STR);
        $query->bindParam(":sr_os", $this->_p['sr_os'], PDO::PARAM_STR);
        $query->bindParam(":sr_cpu", $this->_p['sr_cpu'], PDO::PARAM_STR);
        $query->bindParam(":sr_ram", $this->_p['sr_ram'], PDO::PARAM_STR);
        $query->bindParam(":sr_video", $this->_p['sr_video'], PDO::PARAM_STR);
        $query->bindParam(":sr_hdd", $this->_p['sr_hdd'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $objGame->source_img, PDO::PARAM_STR);
        $query->bindParam(":source_img_small", $objGame->source_img_small, PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        //return $this->GetById($id);

    }


    public function GetData($page = 1)
    {
        return $this->conn->dbh->query("SELECT id, date, header FROM quest_guide_games ORDER BY date DESC LIMIT 0, 10 ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetFilterData($filter)
    {
        $conditions = array();
        $conditions[] = "1=1";
        if ($filter["year"] != "")
            $conditions[] = "YEAR(date)=" . $filter["year"];
        if ($filter["month"] != "")
            $conditions[] = "MONTH(date)=" . $filter["month"];
        if ($filter["day"] != "")
            $conditions[] = "DATE(date)='" . date("Y-m-d", $filter["day"]) . "'";
        $where = " WHERE " . implode(" AND ", $conditions);
        $limit = "LIMIT 0, 10";
        $page = $filter["page"];
        if ($page > 1)
            $limit = "LIMIT " . (($page - 1) * 10) . ", 10";
        $sql = "SELECT id, date, header FROM news " . $where . " ORDER BY date DESC, id DESC " . $limit;
        $query = $this->conn->dbh->prepare($sql);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function GetFilterCount($filter)
    {
        $conditions = array();
        $conditions[] = "1=1";
        if ($filter["year"] != "")
            $conditions[] = "YEAR(date)=" . $filter["year"];
        if ($filter["month"] != "")
            $conditions[] = "MONTH(date)=" . $filter["month"];
        if ($filter["day"] != "")
            $conditions[] = "DATE(date)='" . date("Y-m-d", $filter["day"]) . "'";
        $where = " WHERE " . implode(" AND ", $conditions);
        $sql = "SELECT count(*) FROM news " . $where;
        return $query = $this->conn->dbh->query($sql)->fetchColumn(0);
    }

    public function Add($params)
    {
        $query = $this->conn->dbh->prepare("INSERT INTO quest_guide_games
            SET date = :date, game_id = :game_id, header = :header, short = :short,
                text = :text, title = :title, description = :description, keywords = :keywords,
                source_img = :source_img, source_img_top = :source_img_top");
        $date = strtotime($params['date']);
        $query->bindParam(":date", $date, PDO::PARAM_INT);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":game_id", $params['game_id'], PDO::PARAM_INT);
        $query->bindParam(":short", $params['short'], PDO::PARAM_STR);
        $query->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->bindParam(":source_img_top", $params['source_img_top'], PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        return $this->GetById($id);
    }

    public function Edit($params)
    {
        $query = $this->conn->dbh->prepare("
            UPDATE quest_guide_games
            SET
                date = :date,
                game_id = :game_id,
                header = :header,
                short = :short,
                text = :text,
                title = :title,
                description = :description,
                keywords = :keywords,
                event = :event,
                event_date = :event_date,
                source_img = :source_img,
                source_img_top = :source_img_top
            WHERE id=:id
                ");

        $date = strtotime($params['date']);
        $query->bindParam(":id", $params['id'], PDO::PARAM_INT);
        $query->bindParam(":date", $date, PDO::PARAM_INT);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":game_id", $params['game_id'], PDO::PARAM_INT);
        $query->bindParam(":short", $params['short'], PDO::PARAM_STR);
        $query->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->bindParam(":source_img_top", $params['source_img_top'], PDO::PARAM_STR);
        $query->execute();

        return $this->GetById($params['id']);
    }

    public function GetImages($id)
    {
        $sql = "SELECT id, news_id, source_img as filename, source_img_b as filename_b  FROM news_images WHERE news_id = :news_id ";
        $query = $this->conn->dbh->prepare($sql);
        $query->bindParam(":news_id", $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function AddImage($id, $img, $img_b)
    {
        $query = $this->conn->dbh->prepare("
            INSERT INTO news_images
              SET news_id = :news_id,
                source_img = :source_img,
                source_img_b = :source_img_b");
        $query->bindParam(":news_id", $id, PDO::PARAM_INT);
        $query->bindParam(":source_img", $img, PDO::PARAM_STR);
        $query->bindParam(":source_img_b", $img_b, PDO::PARAM_STR);
        $query->execute();
    }

    function DeleteImage($news_id, $img)
    {
        $query = $this->conn->dbh->prepare("
            DELETE FROM news_images
              WHERE news_id = :news_id
                AND source_img=:source_img
                ");
        $query->bindParam(":news_id", $news_id, PDO::PARAM_INT);
        $query->bindParam(":source_img", $img, PDO::PARAM_STR);
        $query->execute();
    }


    public function GetById($id)
    {
        return $this->conn->dbh->query("SELECT *  FROM quest_guide_games WHERE id=" . $id)->fetch();
    }

    function Delete($id)
    {
        $query = $this->conn->dbh->prepare("DELETE FROM quest_guide_games WHERE id=:id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }
}