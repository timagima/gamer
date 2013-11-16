<?php

namespace application\modules\administration\news;

use application\core\mvc\MainModel as MainModel;
use PDO;
use classes\render as Render;

class Model extends MainModel
{
    public function __construct()
    {
        parent::__construct();
    }

    public function GetData($page = 1)
    {
        return $this->conn->dbh->query("SELECT id, date, header FROM news ORDER BY date DESC LIMIT 0, 50 ")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function Add($params)
    {
        $query = $this->conn->dbh->prepare("
            INSERT INTO news
            SET
                date = :date,
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
                ");
        $parts = explode('.', $params['date']);
        $date = $parts[2] . $parts[1] . $parts[0];
        $query->bindParam(":date", $date, PDO::PARAM_STR);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":short", $params['short'], PDO::PARAM_STR);
        $query->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":event", $params['event'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->bindParam(":source_img_top", $params['source_img_top'], PDO::PARAM_STR);
        //$eventparts = explode('.', $params['event_date']);
        //$event_date = $eventparts[2].$eventparts[1].$eventparts[0];
        $event_date = Render::ToDbDate($params['event_date']);
        $query->bindParam(":event_date", $event_date != "" ? $event_date : null, PDO::PARAM_STR);
        $query->execute();
        $id = $this->conn->dbh->lastInsertId();
        return $this->GetById($id);
    }

    function Edit($params)
    {
        $query = $this->conn->dbh->prepare("
            UPDATE news
            SET
                date = :date,
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
        $parts = explode('.', $params['date']);
        $date = $parts[2] . $parts[1] . $parts[0];
        $query->bindParam(":id", $params['id'], PDO::PARAM_INT);
        $query->bindParam(":date", $date, PDO::PARAM_STR);
        $query->bindParam(":header", $params['header'], PDO::PARAM_STR);
        $query->bindParam(":short", $params['short'], PDO::PARAM_STR);
        $query->bindParam(":text", $params['text'], PDO::PARAM_STR);
        $query->bindParam(":title", $params['title'], PDO::PARAM_STR);
        $query->bindParam(":description", $params['description'], PDO::PARAM_STR);
        $query->bindParam(":keywords", $params['keywords'], PDO::PARAM_STR);
        $query->bindParam(":event", $params['event'], PDO::PARAM_STR);
        $query->bindParam(":event_date", $params['event_date'], PDO::PARAM_STR);
        $query->bindParam(":source_img", $params['source_img'], PDO::PARAM_STR);
        $query->bindParam(":source_img_top", $params['source_img_top'], PDO::PARAM_STR);
        //$eventparts =  explode('.', $params['event_date']);
        $event_date = Render::ToDbDate($params['event_date']); //$eventparts[2].$eventparts[1].$eventparts[0];
        $query->bindParam(":event_date", $event_date != "" ? $event_date : null, PDO::PARAM_STR);
        $query->execute();

        return $this->GetById($params['id']);
    }

    function GetImages($id)
    {
        $sql = "SELECT id, news_id, source_img as filename, source_img_b as filename_b  FROM news_images WHERE news_id = :news_id ";
        $query = $this->conn->dbh->prepare($sql);
        $query->bindParam(":news_id", $id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }

    function AddImage($id, $img, $img_b)
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


    function GetById($id)
    {
        return $this->conn->dbh->query("SELECT *  FROM news WHERE id=" . $id)->fetch();
    }

    function Delete($id)
    {
        $query = $this->conn->dbh->prepare("DELETE FROM news WHERE id=:id");
        $query->bindParam(":id", $id, PDO::PARAM_INT);
        return $query->execute();
    }
}