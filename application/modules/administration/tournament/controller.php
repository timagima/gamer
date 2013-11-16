<?php
namespace application\modules\administration\tournament;
use application\core\mvc\MainController;
use application\modules\administration\tournament\model;
use classes\SimpleImage;
use classes\url;
use classes\upload;

class Controller extends MainController
{
    private static $storage_path = "storage/img-tournament/186/";
    public $model, $upload;

    public function __construct()
    {
        $this->upload = new Upload();
        parent::__construct();
        $this->model = new Model();
    }

    public function ActionIndex()
    {
        $data = $this->model->ListTournaments();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournaments-list.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    // начало управление турнирами
    public function ActionTournaments()
    {
        $data = $this->model->ListTournaments();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournaments-list.tpl.php', '', 'index-admin.tpl.php', $data);
    }
    public function ActionEditTournament()
    {
        $data['tournament'] = $this->model->GetTournament();
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionCreateTournament()
    {
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionSaveTournament()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->_p["id"] > 0)
                $res = $this->model->EditTournament($this->_p);
            else
                $res = $this->model->AddTournament($this->_p);
            $this->Redirect("index");
        } else {
            $data = $this->model->GetById($_GET["id"], "tournaments");
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
        }
    }




    // конец управление турнирами


    // начало победители турниров
    public function ActionWinnerTournament()
    {
        if (!empty($_FILES))
        {
            $path = "storage/winner";
            $objUpload = new Upload();
            $objUpload->UploadImgTinyMce($path);
            exit();
        }

        $data['winner'] = $this->model->GetWinner();
        $data['member-tournament'] = $this->model->ListMemberTournament();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/winner-tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }
    public function ActionSaveWinner()
    {
        $this->model->AddWinner($this->_p);
        $this->Redirect("index");
    }
    private function Upload()
    {
        $objImage = new SimpleImage();
        foreach ($_FILES as $key => $value)
        {
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = "i/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            $objImage->load($value['tmp_name'])->square_crop(360)->save($fileName);
        }
    }

    /*public function ActionEditTournament()
    {
        $data['tournament'] = $this->model->GetTournament();
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionCreateTournament()
    {
        $data['games'] = $this->model->ListGames();
        $data['state-tournament'] = array(1=>"Скоро открытие", 2=>"Регистариция на турнир", 3=>"Турнир начался", 4=>"Завершен");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionSaveTournament()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->_p["id"] > 0)
                $res = $this->model->EditTournament($this->_p);
            else
                $res = $this->model->AddTournament($this->_p);
            $this->Redirect("index");
        } else {
            $data = $this->model->GetById($_GET["id"], "tournaments");
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/tournament-edit.tpl.php', '', 'index-admin.tpl.php', $data);
        }
    }*/
    // конец победители турниров

    public function ActionCreate()
    {
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "event_date" => "");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/tournament/edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }



    public function ActionDelete()
    {
        $id = $_REQUEST["id"];
        if ($id > 0) {
            $res = $this->model->Delete($id);
            return $res;
        }
        return Route::ErrorPage404();
    }

    public function ActionUpload()
    {
        $objImage = new SimpleImage();
        $ext = "." . pathinfo($_FILES['file-0']['name'], PATHINFO_EXTENSION);
        if($this->_p['name'] == "" || !preg_match("/^[^а-я]+$/", $this->_p['name']))
        {
            $name = self::$storage_path . md5(microtime() + rand(0, 10000));
        }
        else
        {
            $name = self::$storage_path . str_replace(" ", "_", mb_strtolower($this->_p['name']));
        }
        $fileName = $name . $ext;
        $objImage->load($_FILES['file-0']['tmp_name'])->save($fileName);
        $this->Json(array("result" => "success", "filename" => "/" . $fileName));
    }
}