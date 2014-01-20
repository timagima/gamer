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
        $this->AddJs("ajax");
        $data['games'] = $this->model->GetGames();
        $data['user-completed-games'] = $this->model->GetUserCompletedGames();
        $data['type-complete-game'] = $this->model->GetTypeCompleteGame();
        $this->headerTxt['title'] = "Пройденные игры - GS11";
        $this->view->Generate('menu/auth-menu.tpl.php', 'base/add-completed-games.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionView($idGame)
    {
        if($idGame){
            $data = $this->model->GetGameView($idGame);
            $this->headerTxt['title'] = "$data[game] - GS11";
            $this->view->Generate('menu/auth-menu.tpl.php', 'base/game-view.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        }
    }

    public function ActionEdit($idGame)
    {
        if($idGame){
            $this->AddJs("ajax");
            $data = $this->model->GetGameView($idGame);
            $data['levelsArray'] = $this->model->GetLevels($idGame);
            $data['typesCompletedGameArray'] = $this->model->GetTypeCompleteGame();
            $this->headerTxt['title'] = "$data[game] Редактировать - GS11";
            $this->view->Generate('menu/auth-menu.tpl.php', 'base/game-edit.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        }
    }



}