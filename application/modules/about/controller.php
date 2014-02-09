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

        $result = $_GET['page'];
        $this->headerTxt['title']='Промо страница';
        $data['error'] = '';


        if($result == ''){
            $this->view->Generate($this->arrTpl[0], 'about/legend-tournament-promo.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data,  $this->headerTxt, $this->model->CountQuery());
             exit();
        }
        else if($result == '/about/promo'){
            include $_SERVER['DOCUMENT_ROOT'] . 'skins/tpl/about/legend-tournament-promo.tpl.php';
            exit();
        }
        else if($result == 'next-tournament-promo'){
            $this->view->Generate($this->arrTpl[0], 'about/next-tournament-promo.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data,  $this->headerTxt, $this->model->CountQuery());
        }
         else if($result == '?page=next-tournament-promo'){
           include $_SERVER['DOCUMENT_ROOT'] . 'skins/tpl/about/next-tournament-promo.tpl.php';
         exit();
         }
        else if($result == 'winner-promo'){
            $this->view->Generate($this->arrTpl[0], 'about/winner-promo.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data,  $this->headerTxt, $this->model->CountQuery());
        }
         else if($result == '?page=winner-promo'){
            include $_SERVER['DOCUMENT_ROOT'] . 'skins/tpl/about/winner-promo.tpl.php';
         exit();
         }




    }







}


