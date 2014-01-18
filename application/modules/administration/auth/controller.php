<?php
namespace application\modules\administration\auth;
use application\core\mvc\MainController as MainController;
use application\modules\administration\auth\model as Model;


class Controller extends MainController
{    
    public $model;
    public $headerTxt = array();
    function __construct()
    {   
        parent::__construct();          
        $this->model = new Model();
        $this->headerTxt['keywords'] = '';
        $this->headerTxt['description'] = '';
        $this->headerTxt['title'] = 'Админ центр — GS11';
    }

	
    public function ActionIndex()
    {        
        if(isset($this->_p['auth']))
        {
            $_SESSION['admin'] = $this->model->Auth();
            if(isset($_SESSION['admin']))
               $_SESSION['admin']['auth'] = '1';
        }        
        $this->AuthRedirect();
        $data = $this->model->GetData();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/auth/show.tpl.php', $this->GetTplView(), 'index.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());

    }
    

    private function AuthRedirect()
    {
        if(isset($_SESSION['admin']['auth']) && $_SESSION['admin']['auth'] == '1')
        {
            header("Location: /administration/main");
            exit();
        }
    }
}
