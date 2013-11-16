<?php
namespace application\modules\search;
use application\core\mvc\MainController as MainController;
use application\modules\search\model as Model;


class Controller extends MainController
{
    public $model;
    function __construct()
    {
        parent::__construct();
        $this->model = new Model();
    }

    public function ActionIndex($getStr)
    {
        // todo необходимо доработать страницу с поиском
        $this->headerTxt['title'] = 'Поиск в GS11';
        $arrGet = $this->ParseGet($getStr);
        if(isset($arrGet['s']))
            $this->headerTxt['title'] = urldecode($arrGet['s']) . ' - Поиск в GS11';
        $data['error'] = '';
        if(isset($_SESSION['auth']))
            $this->view->Generate('menu/auth-menu.tpl.php', 'search/index.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
        else
            $this->view->Generate('menu/main-menu.tpl.php', 'search/index.tpl.php', $this->GetTplView(), 'index.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }
}