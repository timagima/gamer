<?php
namespace application\modules\administration\main;
use application\core\mvc\MainController;


class Controller extends MainController
{
    public function ActionIndex()
    {
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/main/index.tpl.php', $this->GetTplView(), 'index.tpl.php', true, $this->headerTxt, $this->model->CountQuery());

    }
}
