<?php
namespace application\modules\tournament;
use application\core\mvc\MainController;
use application\modules\tournament\model;
use classes\SimpleImage;
use classes\url;


class Controller extends MainController
{
    public $block, $model, $sms;
    private static $storage_path = "storage/img-tournament/";
    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax();
    }

    public function ActionChat()
    {
        if ($_POST['action'] == 'get_chat_message')
        {
            $chat_log_data = $this->model->GetChatMsg($_POST['last_act']);
            if ($chat_log_data['log']!="")
            {
                $message_code = $chat_log_data['log'];
                $last_act = $chat_log_data['last_act'];
                $message_code = $message_code;
                $data_str = array('its_ok' => 1, 'message_code' => $message_code, 'last_act' => $last_act);
            }
            else
            {
                $data_str = array('its_ok' => 0);
            }
            echo json_encode($data_str);

        }
        else
        {
            $chat_log_data = $this->model->GetChatMsg();
            $message_code = $chat_log_data['log'];
            $last_act = $chat_log_data['last_act'];
        }
        exit();
    }


    public function ActionIndex()
    {
        // todo переписать текущий метод
        // todo сделать проверку на существование страницы если нету, редикрект

        if($_SERVER['REQUEST_URI'] == '/tournament/?t=dota-2')
        {
            header("Location: /tournament/?t=dota-2&id=9&page=internal");
            exit();
        }
        $data['tournament'] = $this->model->GetTournament();
        $this->headerTxt['title'] = $data['tournament']->title;
        $this->headerTxt['keywords'] = $data['tournament']->keywords;
        $this->headerTxt['description'] = $data['tournament']->description;
        $page = 'tournament/index.tpl.php';
        $_SESSION['id_tournament'] = (int)$this->_g['id'];
        if(isset($_SESSION['auth']))
        {
            $data['table-members'] = $this->model->TableMembers();
            $resMyTournament = $this->model->ConfirmParticipation();
            $data['my-tournament'] = $resMyTournament;
            // здесь необходимо сделать условие для конкурсов
            if($_GET['page'] == "external" )
            {
                $data['members'] = $this->model->GetMembers();
                if(!empty($resMyTournament) && $resMyTournament->game_over == 0)
                {
                    /*$data['my'] = ($resMyTournament->id_opponent == 0) ? $this->model->SearchOpponent($resMyTournament->stage) : $this->model->ConfirmParticipation();
                    $_SESSION['user']['stage'] = $resMyTournament->stage;
                    $data['settings-tournament'] = $this->model->SettingsTournament($resMyTournament->stage);
                    $data['winner'] = $this->model->GetWinner($resMyTournament->stage);*/
                    $data['my'] = false;

                    if($data['my'])
                    {
                        $data['settings-tournament'] = $this->model->SettingsTournament($data['my']->stage);
                        $page = 'tournament/member.tpl.php';
                    }
                    else
                    {
                        $page = 'tournament/member-not-opponent.tpl.php';
                        $page = 'tournament/contest.tpl.php';
                    }

                }
                else if($resMyTournament->game_over == 1)
                {
                    $data['settings-tournament'] = $this->model->SettingsTournament($resMyTournament->stage);
                    $page = 'tournament/game-over.tpl.php';
                }
            }

        }
        $this->view->Generate($this->arrTpl[0], $page, $this->arrTpl[1], $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());

    }
    public function ActionUpload()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $objImage = new SimpleImage();
            foreach ($_FILES as $key => $value)
            {
                $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
                $name = self::$storage_path . md5(microtime() + rand(0, 10000));
                $fileName = $name . $ext;
                $fileName_b = $name . "_b" . $ext;
                $objImage->load($value['tmp_name'])->square_crop(360)->save($fileName);
                $objImage->load($value['tmp_name'])->save($fileName_b);
                $rootApp = Url::RootApp();
                $this->Json(array("result" => "success", "filename" => $rootApp . $fileName, "filename_b" => $rootApp . $fileName_b));
            }
        }
        exit();
    }


    public function ActionTournamentsList()
    {
        $this->headerTxt['title'] = "Список турниров - GS11";
        $this->headerTxt['keywords'] = "cписок турниров, последние турниры, предстоящие турниры";
        $this->headerTxt['description'] = "Представляем вашему вниманию список будущих турниров, пожалуйста ознакомтесь с данной информацией";
        $data = $this->model->ListTournaments();
        $this->view->Generate($this->arrTpl[0], 'tournament/tournaments-list.tpl.php', true, $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
    }
    public function ActionWinnersList()
    {
        $this->headerTxt['title'] = "Список победителей конкурсов и турниров - GS11";
        $this->headerTxt['keywords'] = "победители турниров, победители конкурсов";
        $this->headerTxt['description'] = "Представляем вашему вниманию список всех прошедших победителей турниров и конкурсов на сайте GS11";
        $data = $this->model->ListWinners();
        $this->view->Generate($this->arrTpl[0], 'tournament/winners-list.tpl.php', true, $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
    }
    public function ActionWinner()
    {
        $data = $this->model->WinnerPage($_GET['id']);
        $this->headerTxt['title'] = $data->title;
        $this->headerTxt['keywords'] = $data->keywords;
        $this->headerTxt['description'] = $data->description;
        $this->view->Generate($this->arrTpl[0], 'tournament/winner.tpl.php', true, $this->arrTpl[1], $data, $this->headerTxt, $this->model->CountQuery());
    }






}