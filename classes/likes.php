<?php
namespace classes;
use application\core\config\config;
use PDO;

class Likes{
    public $conn;
    public $_p, $_g = array();

    public function __construct()
    {   $this->_p = $_POST;
        $this->_g = $_GET;
        $this->conn = Config::GetInstance();
    }

    public function Like()
    {
        $idUser=(int)$_SESSION['user-data']['id'];
        $likeObjTbl = "id_".$this->_p['table'];
        $likeObjId = (int)$this->_p['id'];
        $voted = $this->_p['voted'];
        if($voted === "false"){
            $sql = $this->conn->dbh->prepare("INSERT INTO likes (likes, dislikes, id_user, ".$likeObjTbl.") VALUES (1, 0, :idUser, :like_obj_id)");
        }else{
            $sql = $this->conn->dbh->prepare("UPDATE  likes SET likes=1, dislikes=0 WHERE id_user=:idUser AND ".$likeObjTbl."=:like_obj_id");
        }
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        //$sql->bindParam(":like_obj_tbl", $likeObjTbl, PDO::PARAM_STR);
        $sql->bindParam(":like_obj_id", $likeObjId, PDO::PARAM_STR);
        return($sql->execute())?"liked":"false";
    }

    public function Dislike()
    {
        $idUser=(int)$_SESSION['user-data']['id'];
        $likeObjTbl = "id_".$this->_p['table'];
        $likeObjId = (int)$this->_p['id'];
        $voted = $this->_p['voted'];
        if($voted === "false"){
            $sql = $this->conn->dbh->prepare("INSERT INTO likes (likes, dislikes, id_user, ".$likeObjTbl.") VALUES (0, 1, :idUser, :like_obj_id)");
        }else{
            $sql = $this->conn->dbh->prepare("UPDATE  likes SET likes=0, dislikes=1 WHERE id_user=:idUser AND ".$likeObjTbl."=:like_obj_id");
        }
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        //$sql->bindParam(":like_obj_tbl", $likeObjTbl, PDO::PARAM_STR);
        $sql->bindParam(":like_obj_id", $likeObjId, PDO::PARAM_STR);
        return($sql->execute())?"disliked":"false";
    }

    public function GetUserLikesInfo($field, $fieldId)
    {
        $fieldId = (int)$fieldId;
        $idUser=(int)$_SESSION['user-data']['id'];
        $sql = $this->conn->dbh->prepare("SELECT likes, dislikes FROM likes WHERE id_user=:idUser AND ".$field."=:fieldId");
        $sql->bindParam(":idUser", $idUser, PDO::PARAM_INT);
        $sql->bindParam(":fieldId", $fieldId, PDO::PARAM_INT);
        $sql->execute();
        return $sql->fetch(PDO:: FETCH_ASSOC);
    }


}