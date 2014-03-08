<?php
namespace application\modules\administration\about;
use application\core\mvc\MainController;
use application\modules\administration\about\model;
use classes\SimpleImage;
use classes\url;

class Controller extends MainController
{
    public $block, $model;

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
        if(!empty($_POST['id'])){

                $this->model->updateContact($_POST['id']);
                exit();

        }
        $data['message_contact'] =  array_reverse($this->model->getMessageContact());
        $data['count_message'] = $this->model->countMessage();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/about/message.list.tpl.php', '', 'index-admin.tpl.php',$data);
    }
}
