<?php
namespace application\modules\administration\games;
use application\core\mvc\MainModel as MainModel;
use classes\render as Render;
use PDO;

class Model extends MainModel
{
    private $game, $sourceImgType;
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
    public function GetDifficulty($id)
    {
        return $this->conn->dbh->query("SELECT * FROM level WHERE id_game=".$id. " ORDER BY id ASC")->fetchAll(PDO::FETCH_OBJ);
    }

    /* Начало работа со справочниками по играм */
    public function ListGenre()
    {
        $stmt = $this->conn->dbh->prepare("SELECT * FROM genre");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
    public function AddMainGame()
    {
        $sourceImgB = $this->MoveImg($this->_p['source_img_b'], true);
        $sourceImgS = $this->MoveImg($this->_p['source_img_s'], true);
        $stmt = $this->conn->dbh->prepare("INSERT INTO games SET name = :name, genre_id = :genre_id, source_img_b = :source_img_b, source_img_s = :source_img_s");
        $stmt->bindParam(":name", $this->_p['name'], PDO::PARAM_STR);
        $stmt->bindParam(":genre_id", $this->_p['genre_id'], PDO::PARAM_INT);
        $stmt->bindParam(":source_img_b", $sourceImgB, PDO::PARAM_STR);
        $stmt->bindParam(":source_img_s", $sourceImgS, PDO::PARAM_STR);
        $stmt->execute();
        $id = $this->conn->dbh->lastInsertId();
        $this->AddDifficulty($id);
    }
    public function UpdateMainGame()
    {
        // проверять каждое поле не поменялось ли, а дальше если поменялось обновлять его если такого поля и вовсе нету, то добавить
        $this->DeleteDifficulty();
        $this->UpdateDifficulty();
        $this->game = $this->GetGame($this->_p['id']);
        $sourceImgB = $this->WorkImg($this->game->source_img_b, $this->_p['source_img_b']);
        $sourceImgS = $this->WorkImg($this->game->source_img_s,  $this->_p['source_img_s']);
        $stmt = $this->conn->dbh->prepare("UPDATE games SET name = :name, genre_id = :genre_id, source_img_b = :source_img_b, source_img_s = :source_img_s WHERE id = :id");
        $stmt->bindParam(":name", $this->_p['name'], PDO::PARAM_STR);
        $stmt->bindParam(":genre_id", $this->_p['genre_id'], PDO::PARAM_INT);
        $stmt->bindParam(":source_img_b", $sourceImgB, PDO::PARAM_STR);
        $stmt->bindParam(":source_img_s", $sourceImgS, PDO::PARAM_STR);
        $stmt->bindParam(":id", $this->_p['id'], PDO::PARAM_INT);
        $stmt->execute();
    }


    private function UpdateDifficulty()
    {
        if(count($this->_p['name-difficulty']) == count($this->_p['description-difficulty']))
        {
            $arrLevel = $this->GetDifficulty($this->_p['id']);
            for($i = -1; $i < count($this->_p['name-difficulty']); ++$i)
            {
                $name = ($arrLevel[$i]->name == $this->_p['name-difficulty'][$i]) ? $arrLevel[$i]->name : $this->_p['name-difficulty'][$i];
                $description = ($arrLevel[$i]->description == $this->_p['description-difficulty'][$i]) ? $arrLevel[$i]->description : $this->_p['description-difficulty'][$i];
                if($name != "" || $description != "")
                {
                    $stmt = $this->conn->dbh->prepare("UPDATE level SET `name` = :name, description = :description WHERE id_game = :id_game AND id = :id");
                    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
                    $stmt->bindParam(":description", $description, PDO::PARAM_STR);
                    $stmt->bindParam(":id_game", $this->_p['id'], PDO::PARAM_INT);
                    $stmt->bindParam(":id", $arrLevel[$i]->id, PDO::PARAM_INT);
                    $stmt->execute();
                }
                if($i == count($arrLevel)-1)
                    break;
            }
            if(count($this->_p['name-difficulty']) > count($arrLevel))
            {
                $this->AddDifficulty($this->_p["id"], ++$i);
            }
        }
    }

    private function DeleteDifficulty()
    {
        if(!empty($this->_p['delete-field']))
        {
            $strId = "";
            foreach($this->_p['delete-field'] as $r)
            {
                $strId .= $r.",";
            }
            $arrId = substr($strId, 0, strlen($strId) - 1);
            $stmt = $this->conn->dbh->prepare("DELETE FROM level WHERE FIND_IN_SET(id, :id)");
            $stmt->bindParam(":id", $arrId, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    private function AddDifficulty($id, $i = 0)
    {
        if(count($this->_p['name-difficulty']) == count($this->_p['description-difficulty']))
        {

            for($i; $i < count($this->_p['name-difficulty']); $i++)
            {
                if($this->_p['name-difficulty'][$i] != "" || $this->_p['name-difficulty'][$i] != "")
                {
                    $stmt = $this->conn->dbh->prepare("INSERT INTO level SET id_game = :id_game, name = :name, description = :description");
                    $stmt->bindParam(":id_game", $id, PDO::PARAM_INT);
                    $stmt->bindParam(":name", $this->_p['name-difficulty'][$i], PDO::PARAM_STR);
                    $stmt->bindParam(":description", $this->_p['description-difficulty'][$i], PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
        }
    }

    private function PrepareDifficulty()
    {
        /*foreach($this->_p['name-difficulty'] as $name)
        {
            foreach($this->_p['description-difficulty'] as $description)
            {
                 $param = $name . "#" .  $description;
            }
            $arr[] = $param;
        }
        return $arr;*/
    }

    private function WorkImg($SourceImgType,  $file)
    {
        $this->sourceImgType = $SourceImgType;
        if($SourceImgType != $file)
        {
            $sourceImg = $this->MoveImg($file, true);
        }
        else
        {
            if($this->game->name != $this->_p['name'])
            {
                $sourceImg = $this->RenameImg($file);
            }
            if($this->game->genre_id != $this->_p['genre_id'])
            {
                $sourceImg = $this->MoveImg($file);
            }
            if($this->game->name == $this->_p['name'] && $this->game->genre_id == $this->_p['genre_id'])
            {
                $sourceImg = $file;
            }
        }
        return $sourceImg;
    }

    private function RenameImg($file)
    {
        $fullPath = $this->PrepareImg($file);
        rename($_SERVER["DOCUMENT_ROOT"] . "storage".$file, $_SERVER["DOCUMENT_ROOT"] . "storage".$fullPath);
        return $fullPath;
    }
    private function MoveImg($file, $param = false)
    {
        if(empty($file))
        {
            unlink($_SERVER["DOCUMENT_ROOT"] . "storage".$this->sourceImgType);
        }
        else
        {
            $file = (!$param) ? "storage".$file : $file;
            $fullPath = $this->PrepareImg($file);
            copy( $_SERVER["DOCUMENT_ROOT"] . $file, "storage".$fullPath);
            unlink($_SERVER["DOCUMENT_ROOT"] . $file);
            return $fullPath;
        }

    }

    private function PrepareImg($file)
    {
        if(!empty($file))
        {
            $searchCharName = array(" ", "’", "-", "'", ":", "&");
            $searchCharFile = array(" ", "’");
            $pathGenre = str_replace($searchCharFile, "", mb_strtolower($this->GetGenre()));
            $fileName = str_replace($searchCharName, "_", mb_strtolower($this->_p['name']));
            $arrFileName = explode("_", $fileName);
            $name = "";
            foreach($arrFileName as $r)
            {
                if($r != "")
                {
                    $name .= trim($r."_");
                }
            }
            $fileName = preg_replace('%[^A-Za-zА-Яа-я0-9_]%', '', substr($name, 0, -1));
            $extArr = explode(".", $file);
            $typeImg = ($extArr[1] == "png") ? "_s" : "_b";
            $fullPath = "/source_img_base_game/".$pathGenre. "/".$fileName. $typeImg .  ".". $extArr[1];
            return $fullPath;
        }
    }

    private function GetGenre()
    {
        $stmt = $this->conn->dbh->prepare("SELECT name FROM genre WHERE id = :id");
        $stmt->bindParam(":id", $this->_p['genre_id'], PDO::PARAM_INT);
        $stmt->execute();
        $arr = $stmt->fetch(PDO::FETCH_OBJ);
        return $arr->name;
    }
    /* Конец работа со справочниками по играм */



    public function GetMainPageGame($id)
    {
        return $this->conn->dbh->query("SELECT * FROM main_page_games WHERE id_game = ".$id)->fetch(PDO::FETCH_OBJ);
    }

    public function AddMainPageGame($objGame)
    {
        $dateReleaseWorld = strtotime($this->_p['date_release_world']);
        $dateReleaseRussia = strtotime($this->_p['date_release_russia']);
        $query = $this->conn->dbh->prepare("INSERT INTO main_page_games SET date = UNIX_TIMESTAMP(NOW()), id_game = :id_game, game_mode = :game_mode,
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
                distribution = :distribution,
                video_img = :video_img,
                video_link = :video_link,
                sr_os = :sr_os, sr_cpu = :sr_cpu, sr_ram = :sr_ram, sr_video = :sr_video, sr_hdd = :sr_hdd");
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
        $query->bindParam(":distribution", $this->_p['distribution'], PDO::PARAM_STR);
        $query->bindParam(":video_img", $this->_p['video_img'], PDO::PARAM_STR);
        $query->bindParam(":video_link", $this->_p['video_link'], PDO::PARAM_STR);
        $query->bindParam(":sr_os", $this->_p['sr_os'], PDO::PARAM_STR);
        $query->bindParam(":sr_cpu", $this->_p['sr_cpu'], PDO::PARAM_STR);
        $query->bindParam(":sr_ram", $this->_p['sr_ram'], PDO::PARAM_STR);
        $query->bindParam(":sr_video", $this->_p['sr_video'], PDO::PARAM_STR);
        $query->bindParam(":sr_hdd", $this->_p['sr_hdd'], PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        //return $this->GetById($id);

    }
    public function EditMainPageGame($objGame)
    {
        $this->UpdateMainPageGameRubric();
        $this->UploadMainPageRubricImg();
        $dateReleaseWorld = strtotime($this->_p['date_release_world']);
        $dateReleaseRussia = strtotime($this->_p['date_release_russia']);
        $query = $this->conn->dbh->prepare("UPDATE main_page_games SET  game_mode = :game_mode, text = :text, title = :title,
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
        distribution = :distribution,
        video_img = :video_img,
        video_link = :video_link,
        sr_os = :sr_os, sr_cpu = :sr_cpu, sr_ram = :sr_ram, sr_video = :sr_video, sr_hdd = :sr_hdd WHERE id = :id");
        $query->bindParam(":id", $this->_p['id'], PDO::PARAM_INT);
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
        $query->bindParam(":distribution", $this->_p['distribution'], PDO::PARAM_STR);
        $query->bindParam(":video_img", $this->_p['video_img'], PDO::PARAM_STR);
        $query->bindParam(":video_link", $this->_p['video_link'], PDO::PARAM_STR);
        $query->bindParam(":sr_os", $this->_p['sr_os'], PDO::PARAM_STR);
        $query->bindParam(":sr_cpu", $this->_p['sr_cpu'], PDO::PARAM_STR);
        $query->bindParam(":sr_ram", $this->_p['sr_ram'], PDO::PARAM_STR);
        $query->bindParam(":sr_video", $this->_p['sr_video'], PDO::PARAM_STR);
        $query->bindParam(":sr_hdd", $this->_p['sr_hdd'], PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        //return $this->GetById($id);

    }

    public function GetGameRubrics($id)
    {
        $result = $this->conn->dbh->query("SELECT * FROM main_page_games_rubric WHERE id_main_page_game=".$id)->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function UpdateMainPageGameRubric()
    {
        if(isset($this->_p['new-rubrics'])){
            $idGame=$this->_p['id-game'];
            foreach($this->_p['new-rubrics'] as $rubric){
                $sql = $this->conn->dbh->prepare("INSERT INTO main_page_games_rubric SET id_main_page_game=:id_game, rubric=:rubric");
                $sql->bindParam(":id_game", $idGame, PDO::PARAM_INT);
                $sql->bindParam(":rubric", $rubric, PDO::PARAM_STR);
                $sql->execute();
            }
        }

        if(isset($this->_p['deleted-rubrics'])){
            $i = 0;
            $rubricIdString = '';
            foreach($this->_p['deleted-rubrics'] as $rubricId){
                $rubricIdString .= ($i==0) ? $rubricId  : ",".$rubricId ;
                $i++;
            }
            $path = $this->conn->dbh->prepare("SELECT rubric_img_s, rubric_img_b  FROM main_page_games_rubric WHERE FIND_IN_SET(id, :id)");
            $path->bindParam(":id", $rubricIdString, PDO::PARAM_STR);
            $path->execute();
            $pathDelImgs = $path->fetchAll(PDO::FETCH_ASSOC);
            foreach($pathDelImgs as $pathImg){
                $a = unlink(substr($pathImg['rubric_img_s'], 1));
                $b = unlink(substr($pathImg['rubric_img_b'], 1));
            }

            $sql = $this->conn->dbh->prepare("DELETE FROM main_page_games_rubric WHERE FIND_IN_SET(id, :id)");
            $sql->bindParam(":id", $rubricIdString, PDO::PARAM_STR);
            $sql->execute();
        }

    }

    public function UploadMainPageRubricImg()
    {
        if( isset($this->_p['deletedImg']) && is_array($this->_p['deletedImg']) ){
            $i=0;
            $delId ='';
            foreach($this->_p['deletedImg'] as $delImgId){
                $delImgArray = explode('$', $delImgId);
                $delImgS = substr($delImgArray[1], 1);
                $delImgB = str_replace("_s", "_b", $delImgS);
                if( unlink($delImgS) && unlink($delImgB) ){
                    $delId .= ($i===0) ? $delImgArray[0] : ','.$delImgArray[0];
                    $i++;
                }
            }
            $sql = $this->conn->dbh->prepare("UPDATE main_page_games_rubric SET rubric_img_b=null, rubric_img_s=NULL WHERE FIND_IN_SET(id, :id)");
            $sql->bindParam(":id", $delId, PDO::PARAM_STR);
            $sql->execute();
        }

        if(is_array($this->_p['id-rubrics'])){
            foreach($this->_p['id-rubrics'] as $id){
                $imgName = "img-files-".$id;
                if(!empty($this->_p[$imgName])){
                    $imgS = "storage/guide-games/" . $this->_p['id-game'] . "/" . basename($this->_p[$imgName]);
                    $imgB = str_replace("_s", "_b", $imgS);
                    $oldImgS = $this->_p[$imgName];
                    $oldImgB = str_replace("_s", "_b", $oldImgS);
                    if(rename($oldImgS, $imgS) && rename($oldImgB, $imgB)){
                        $imgS = "/".$imgS;
                        $imgB = "/".$imgB;
                        $sql = $this->conn->dbh->prepare("UPDATE main_page_games_rubric SET rubric_img_s=:rubricImgS, rubric_img_b=:rubricImgB WHERE id=:id");
                        $sql->bindParam(":id", $id, PDO::PARAM_INT);
                        $sql->bindParam(":rubricImgS", $imgS, PDO::PARAM_STR);
                        $sql->bindParam(":rubricImgB", $imgB, PDO::PARAM_STR);
                        $sql->execute();
                    }
                }
            }
        }


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