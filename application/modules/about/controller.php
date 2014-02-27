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
        $data['users_winner'] = $this->model->GetLastWinner();
        $data['near_tournaments'] = $this->model->GetLastTournament();
        $data['count_users'] = $this->model->CountUsers();
        $this->AddCss("style");
        $this->AddJs("script");

        $arrFileTpl = array("legend-tournament"=>"/skins/tpl/about/legend-tournament-promo.tpl.php",
        "next-tournament"=>"/skins/tpl/about/next-tournament-promo.tpl.php",
        "winner"=>"/skins/tpl/about/winner-promo.tpl.php");
        if(!empty($_POST['page']))
        {
            include $_SERVER['DOCUMENT_ROOT'].$arrFileTpl[$_POST['page']];
            exit();
        }

        $arrInfoTpl = array(
            "legend-tournament"=> array("Легендарные Off-line турниры","off-line турниры, PC игры, GS11","Компания GS11 дает возможность вам, участвовать в off-line турнирах по всем PC играм.","about/legend-tournament-promo.tpl.php"),
            "next-tournament"=> array("Ближайшие Off-line турниры","Ближайшие туриниы","Следите за предстоящими off-line турнирами.","about/next-tournament-promo.tpl.php"),
            "winner"=> array("Победители Off-line турниров","победители, off-line турниры","Здесь вы можете узнать о победтелях турниров на нашем сайте","about/winner-promo.tpl.php")
        );
        foreach($arrInfoTpl as $key => $value ){
            if($key == $_GET['page']){
                list($this->headerTxt['title'],$this->headerTxt['keywords'],
                    $this->headerTxt['description'],$pageUrl) = $arrInfoTpl[$key];
            }
        }

        $data['error'] = '';
        $this->view->Generate($this->arrTpl[0], $pageUrl, $this->GetTplView(), $this->arrTpl[1], $data,  $this->headerTxt, $this->model->CountQuery());
    }

    public function ActionGamesForever()
    {
        $data['games_forever'] = $this->model->GetRandomGame();
        $this->headerTxt['title'] = 'Игры навсегда';
        $data['error'] = '';
        $this->view->Generate($this->arrTpl[0], 'about/games-forever.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
    }
    public function ActionThanks()
    {
        $data['thanks_info'] = $this->model->GetInfoThanks();
        if(!empty($_POST['id'])){
            foreach($data['thanks_info'] as $key => $value){
                if($value['name_partner'] == $_POST['id']){
                    echo json_encode($data['thanks_info'][$key]);
                }
            }
            exit();
        }
        $this->headerTxt['title'] = 'Благодарности';
        $data['error'] = '';
        $this->view->Generate($this->arrTpl[0], 'about/thanks.tpl.php', $this->GetTplView(), $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
    }

}


