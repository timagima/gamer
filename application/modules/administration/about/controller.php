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
        $data = $this->model->GetDataGameForever()[0];
        if(!empty($_POST['data']))
        {
            $this->model->EditDataGameForever($_POST['data']);
            print_r( $_POST['data']['deletedImg[]']);

            exit();
        }
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/games.edit.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionDelete()
    {
        $id = $_GET["id"];
        if ($id > 0)
        {
            $this->model->RemoveGameForever($id);
        }
            $this->Redirect("games", "administration.games");
        }

    public function ActionThanks()
    {
        $data = $this->model->ListThanks();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/thanks.list.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionEditThanks()
    {
        $data = $this->model->GetDataThanks()[0];
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/thanks.edit.tpl.php', '', 'index-admin.tpl.php',$data);
    }
    public function ActionAddThanks()
    {
        $this->PrepareFiles(self::$storageTemp);
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/thanks.edit.tpl.php', '', 'index-admin.tpl.php');

    }
}
