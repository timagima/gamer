<?php
namespace application\modules\base;
use application\core\mvc\MainController;
use application\modules\base\model;
use classes\SimpleImage;


class Controller extends MainController
{
    public $block, $model, $sms;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->ExistSessionAuth();
        $this->RunAjax();
    }

    public function ActionIndex()
    {
        $data['games'] = $this->model->GetGames();
        $data['user-completed-games'] = $this->model->GetUserCompletedGames();
        $data['genre'] = $this->model->GetGenre();
        $data['rank']  = $this->model->GetRanks();
        $data['award'] = $this->model->GetAwards();
        $this->headerTxt['title'] = "Пройденные игры - GS11";
        $this->view->Generate('menu/auth-menu.tpl.php', 'base/add-completed-games.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }



}