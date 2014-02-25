<?php
namespace application\modules\guide;
use application\core\mvc\MainController;
use application\modules\guide\model;

class Controller extends MainController
{
    public $block, $model;
    function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax('Вы ошиблись при вводе данных');
    }

    public function ActionIndex()
    {

    }

    public function ActionGames($id)
    {
        if(isset($id) && $id > 0)
        {
            $data['obj'] = $this->model->GetGame($id);
            $this->headerTxt['title'] = $data['obj']->name;
            $this->headerTxt['description'] = $data['obj']->description;
            $this->headerTxt['keywords'] = $data['obj']->keywords;
            $this->view->Generate($this->arrTpl[0], 'guide/obj-game.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
        }
        else
        {
            $this->headerTxt['title'] = "Обзоры, гайды, вики игр  - GS11";
            $data['games'] = $this->model->ListGames();
            $this->view->Generate($this->arrTpl[0], 'guide/games.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
        }
    }
}