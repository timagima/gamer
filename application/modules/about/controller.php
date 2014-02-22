<?php
namespace application\modules\about;
use application\core\mvc\MainController;
use application\modules\about\model;
class Controller extends MainController
{
    public $block, $model;
    function __construct() {
        parent::__construct();
        $this->model = new Model();
    }
    public function ActionIndex()
    {



    }
    public function ActionTariff()
    {
        $this->headerTxt['title'] = 'Детальная информация о тарифных планах - GS11';
        $this->view->Generate($this->arrTpl[0], 'about/tariff.tpl.php', $this->GetTplView(), $this->arrTpl[1], false, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionCompany()
    {
        $this->headerTxt['title'] = 'Компания - GS11';
        $this->headerTxt['keywords'] = 'компания GS11, всё о gs11, кто gs11';
        $this->headerTxt['description'] = 'Компания GS11 была создана в 2012 году, тогда она лишь только зарождалась и не представляла из себя всё то, что мы можем представить вам сегодня.';
        $this->view->Generate($this->arrTpl[0], 'about/company.tpl.php', $this->GetTplView(), $this->arrTpl[1], false, $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionContact()
    {
        $data['success'] = '';
        $data['error'] = '';
        if(isset($this->_p['message']))
        {
            if (strlen($this->_p['message']) < 20)
                $data['error'] = "Ваше сообщение должно быть содержательного характера, не менее 20 символов";
            else
            {
                $this->model->InsertAboutMessage();
                $data['success'] = "Ваше сообщение успешно отправленно модератору.";
            }
        }
        $this->headerTxt['title'] = 'Контакты информационного альянса «Медиана» — Метросфера.ру';
        $this->view->Generate('menu/main-menu.tpl.php', 'about/contact.tpl.php', $this->GetTplView(), 'index-auth.tpl.php', $data, $this->headerTxt, $this->model->CountQuery());
    }
    public function ActionOffer()
    {
        $this->headerTxt['title'] = 'Оферта — GS11';
        $data['error'] = '';
        $this->view->Generate($this->arrTpl[0], 'about/offer.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
    }
    public function ActionPromo()
    {
        $this->AddCss("style");
        $this->AddJs("script");
        $resGet = $_GET['page'];

        $arrFileTpl = array("1"=>"/skins/tpl/about/legend-tournament-promo.tpl.php",
        "2"=>"/skins/tpl/about/next-tournament-promo.tpl.php",
        "3"=>"/skins/tpl/about/winner-promo.tpl.php");
        if(!empty($_POST['id']))
        {
            include $_SERVER['DOCUMENT_ROOT'].$arrFileTpl[$_POST['id']];
            exit();
        }
        if($resGet == 'legendary-tournament')
        {
            $pageTitle = 'Легендарные Off-line турниры';
            $pageUrl = 'about/legend-tournament-promo.tpl.php';
        }
        if($resGet == 'next-tournament' )
        {
            $pageTitle = 'Ближайшие Off-line турниры';
            $pageUrl = 'about/next-tournament-promo.tpl.php';
        }
        if($resGet == 'winner')
        {
            $pageTitle = 'Победители Off-line турниров';
            $pageUrl = 'about/winner-promo.tpl.php';
        }
        $this->headerTxt['title']= $pageTitle;
        $data['error'] = '';
        $this->view->Generate($this->arrTpl[0], $pageUrl, $this->GetTplView(), $this->arrTpl[1], $data,  $this->headerTxt, $this->model->CountQuery());


    }
}


