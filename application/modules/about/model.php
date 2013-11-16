<?php
namespace application\modules\about;
use application\core\mvc\MainModel as MainModel;

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
}
