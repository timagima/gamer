<?php
namespace application\modules\error;
use application\core\mvc\MainController as MainController;
class Controller extends MainController
{
    public function ActionIndex()
    {
        $this->view->Generate(false, false, $this->GetTplView(), '404.tpl.php');
    }
}