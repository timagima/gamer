<?php
namespace application\modules\main;
use application\core\mvc\MainController as MainController;
use application\modules\main\model as Model;
use classes\captcha as Captcha;


class Controller extends MainController
{    
    public $block, $model, $sms;
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax();
        $this->ExistAuth();
    }

    public function ActionIndex()
    {
        $data['error'] = '';
        $this->headerTxt['title'] = 'GS11';
        $this->headerTxt['description'] = 'Принимайте участие в турнирах по легендарным играм, выигрывая крупные денежные призы, ведите и читайте личные дневники о пройденных играх, а также многое другое мирового гейминга.';
        $this->headerTxt['keywords'] = 'GS11, ГС11, социальная сеть, иговые блоги, игровые турниры';
        $this->view->Generate('menu/main-menu.tpl.php', 'main/show.tpl.php', $this->GetTplView(), 'index.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionRestore($getStr)
    {
        $data['error'] = '';
        $this->headerTxt['title'] = 'Восстановление аккаунта — GS11';
        if(!empty($getStr))
        {
            $arrGet = $this->ParseGet($getStr);
            if (isset($arrGet['key']) && isset($arrGet['hash']))
            {
                $_SESSION['restore-key'] = $arrGet['key'];
                $_SESSION['restore-hash'] = $arrGet['hash'];
                $_SESSION['restore-id-user'] = $this->model->ExistEmailRestore($arrGet);
                $data['exist-pass-restore'] = ($_SESSION['restore-id-user']) ? true : false;
            }
        }
        else if(isset($_SESSION['session-restore']) && $_SESSION['session-restore'] == true)
        {
            unset($_SESSION['session-restore']);
            $_SESSION['exist-pass-restore-phone'] = true;
            $data['exist-pass-restore'] = true;
        }
        $this->view->Generate('menu/main-menu.tpl.php', 'main/restore.tpl.php', $this->GetTplView(), 'index.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }
    public function ActionCaptcha()
    {
        // todo сломалась капча решение(всё зависит от обработчика ошибок, когда он включён с капчей проблемы) по фиксить
        Captcha::Init();
        exit();
    }
}