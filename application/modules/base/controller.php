<?php
namespace application\modules\base;
use application\core\mvc\MainController;
use application\modules\base\model;
use classes\SimpleImage;
use classes\likes;


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
        $this->AddCss("style");
        $data['check-user-id'] = (int)$_SESSION['user-data']['id'];
        $data['user-completed-games'] = $this->model->GetUserCompletedGames();
        $data['type-complete-game'] = $this->model->GetTypeCompleteGame();
        $this->headerTxt['title'] = "Пройденные игры - GS11";
        $this->view->Generate('menu/auth-menu.tpl.php', 'base/add-completed-games.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionView($idGame)
    {
        if($idGame){
            $this->GetSetUserLikes();
            $idUser = (int)$this->_g['iduser'];
            $data = $this->model->GetGameView($idGame, $idUser);
            $data['user-likes'] = $this->GetSetUserLikes(2, $data['id_ucg']);
            $data['ucg-likes'] = $this->likes->GetRecordLikes(2, $data['id_ucg']);
            $this->headerTxt['title'] = "$data[game] - GS11";
            $this->view->Generate('menu/auth-menu.tpl.php', 'base/game-view.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        }
    }

    public function ActionEdit($idGame)
    {
        $this->PrepareFiles(self::$storageTemp);
        if($idGame){
            $this->AddJs("ajax");
            $data = $this->model->GetGameView($idGame);
            $data['userGameImg'] = $this->model->GetUserImgGame($idGame);
            $data['levelsArray'] = $this->model->GetLevels($idGame);
            $data['typesCompletedGameArray'] = $this->model->GetTypeCompleteGame();
            $this->headerTxt['title'] = "$data[game] Редактировать - GS11";
            $this->view->Generate('menu/auth-menu.tpl.php', 'base/game-edit.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        }
    }

    public function ActionSaveChanges()
    {
        $this->model->UpdateAddedGame();
        $this->model->UploadUserGameImg();
        $this->model->RemoveUserImgGame($this->_p['deletedImg']);
        $data = "";
        $this->view->Generate('menu/auth-menu.tpl.php', 'base/game-chanched.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionUsers()
    {
        $data['users-completed-game'] = $this->model->GetUsersCompletedGame();
        $this->view->Generate('menu/auth-menu.tpl.php', 'base/users.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionUserGames($idUser)
    {
        $this->AddCss("style");
        $data['user-completed-games'] = $this->model->GetUserCompletedGames($idUser);
        $this->headerTxt['title'] = "Игры пользователя {$data['user-completed-games'][0]['nick']} - GS11";
        $this->view->Generate('menu/auth-menu.tpl.php', 'base/add-completed-games.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }



}