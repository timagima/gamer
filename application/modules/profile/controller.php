<?php
namespace application\modules\profile;
use application\core\mvc\MainController;
use application\modules\profile\model;
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
        $data['genre'] = $this->model->GetGenre();
        $data['rank']  = $this->model->GetRanks();
        $data['award'] = $this->model->GetAwards();
        $this->headerTxt['title'] = $this->view->user['first_name'] . " " . $this->view->user['last_name'] . "  профиль пользователя - GS11";
        $this->view->Generate('menu/auth-menu.tpl.php', 'profile/index.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }
    // todo Сделать проверку для пользователей у которых нету телефона
    // todo Сделать проверку для модальных окон для тех кто заполнил нужно возвращать данные в модальном окне те которые были заполнены
    public function ActionDeleteAvatar()
    {
        unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar'] . "_b.jpg");
        unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar'] . "_s.jpg");
        unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar'] . ".jpg");
        unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar']);
        unlink($_SERVER['DOCUMENT_ROOT'] . $_SESSION['user-data']['img_avatar']);
        unlink($_SERVER['DOCUMENT_ROOT'] . $_SESSION['user-data']['img_avatar_b']);
        $this->model->DeleteAvatar();
        exit();
    }
    public function ActionUploadAvatar()
    {
        if($_SESSION['user-data']['path'] == '')
        {
            $this->CreateDir();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $this->Upload();
        }
        exit();
    }
    public function ActionReloadAvatar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar'] . "_b.jpg");
            unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar'] . "_s.jpg");
            unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar'] . ".jpg");
            unlink($_SERVER['DOCUMENT_ROOT'] ."storage" . $_SESSION['user-data']['img_avatar']);
            unlink($_SERVER['DOCUMENT_ROOT'] . $_SESSION['user-data']['img_avatar']);
            unlink($_SERVER['DOCUMENT_ROOT'] . $_SESSION['user-data']['img_avatar_b']);
            if($_SESSION['user-data']['path'] == '')
            {
                $this->CreateDir();
            }
            $this->Upload();
        }
        exit();
    }


    private function CreateDir()
    {

        $randSecondParam = substr("999999999", 0, -strlen($_SESSION['user-data']['id']));
        $randFirstParam = "1";
        for($i = 0; $i<strlen($randSecondParam)-1; $i++)
        {
            $randFirstParam .= "0";
        }
        $dir = $_SESSION['user-data']['id'] . rand($randFirstParam, $randSecondParam);
        mkdir("storage/user_img/" . $dir, 0775);
        $this->model->SetDir($dir);
        chdir($_SERVER['DOCUMENT_ROOT']);
    }
    public function ActionExit()
    {
        session_destroy();
        unset($_SESSION);
        setcookie("hash", "", time() -1, "/");
        setcookie("key", "", time() -1, "/");
        header("Location: /");
        exit();
    }
    private function Upload()
    {
        $objImage = new SimpleImage();
        foreach ($_FILES as $key => $value)
        {
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = "storage/user_img/".$_SESSION['user-data']['path']."/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            $fileName_b = $name . "_b" . $ext;
            $objImage->load($value['tmp_name'])->square_crop(360)->save($fileName);
            $objImage->load($value['tmp_name'])->save($fileName_b);
            $this->model->SetAvatar("/".$fileName, "/".$fileName_b);
            $this->Json(array("result" => "success", "filename" => "/" . $fileName, "filename_b" => "/" . $fileName_b));
        }
    }


}