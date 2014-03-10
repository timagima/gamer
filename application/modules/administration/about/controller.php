<?php
namespace application\modules\administration\about;
use application\core\mvc\MainController;
use application\modules\administration\about\model;
use classes\SimpleImage;
use classes\upload;
use classes\url;



class Controller extends MainController
{

    //private static $storage_path = "storage/legend-game";
    //private static $storagePath = "storage/legend-game";
    public  $model,$rootDir;

    function __construct()
    {
        parent::__construct();
        $this->model = new Model();
    }
    public function ActionIndex()
    {
    }
    public function ActionMessage()
    {
        if(!empty($_POST['id']))
        {
            $this->model->UpdateContact($_POST['id']);
            exit();
        }
        $data['message_contact'] =  array_reverse($this->model->GetMessageContact());
        $data['count_message'] = $this->model->CountMessage();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/message.list.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionGames()
    {
        $data = $this->model->ListGameForever();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/games.list.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionAddGame()
    {

        $this->PrepareFiles(self::$storageTemp);
        if(!empty($_POST['data']))
        {
            $this->model->SetDataGameForever($_POST['data']);
            exit();
        }
        $data['games'] = $this->model->ListGames();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/games.edit.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionEditGame()
    {
        $this->PrepareFiles(self::$storageTemp);
        $data = $this->model->GetDataGameForever()[0];
        if(!empty($_POST['data']))
        {
            $this->model->EditDataGameForever($_POST['data'],$_POST['data'][0]['value']);
            exit();
        }
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/games.edit.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionDeleteGame()
    {
        $id = $_GET["id"];
        if ($id > 0)
        {
            $this->model->RemoveGameForever($id);
        }
            $this->Redirect("games", "administration.about");
        }
    public function ActionDeleteThanks()
    {
        $id = $_GET["id"];
        if ($id > 0)
        {
            $this->model->RemoveDataThanks($id);
        }
        $this->Redirect("thanks", "administration.about");
    }


    public function ActionThanks()
    {
        $data = $this->model->ListThanks();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/thanks.list.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionEditThanks()
    {
        $this->PrepareFiles(self::$storageTemp);
        if(!empty($_POST['data']))
        {
            $this->model->EditDataThanks($_POST['data'],$_POST['data'][0]['value']);
            exit();
        }

        $data = $this->model->GetDataThanks()[0];
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/thanks.edit.tpl.php', '', 'index-admin.tpl.php',$data);

    }
    public function ActionAddThanks()
    {
        $this->PrepareFiles(self::$storageTemp);
        if(!empty($_POST['data']))
        {
            $this->model->SetDataThanks($_POST['data']);
            exit();
        }
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/thanks.edit.tpl.php', '', 'index-admin.tpl.php');

    }
}
