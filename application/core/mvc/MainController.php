<?php
namespace application\core\mvc;
use application\core\mvc\MainModel;
use classes\Comments;
use classes\pagination;
use classes\url;
use classes\upload;
use classes\likes;
use PDO;


class MainController
{
    public $_p, $_g, $headerTxt, $arrTpl = array();
    public $mainModel, $view, $pagination, $model, $comments, $likes;
    public static $storageTemp = "storage/temp";
    public function __construct()
    {
        $this->_p = $_POST;
        $this->_g = $_GET;
        $user = (isset($_SESSION['user-data'])) ? $_SESSION['user-data'] : null;
        $this->headerTxt['description'] = '';
        $this->headerTxt['keywords'] = '';
        $this->view = new MainView($user);
        $this->model = new MainModel();
        $this->comments = new Comments();
        $this->likes = new Likes();
        $this->TplAuth();

        if(empty($_SESSION['auth']))
        {
            $this->model->AuthCookie();
        }
        if(!empty($_SESSION['auth']))
        {
            $this->model->GetRefreshDataUser();
        }

    }

    public function RedirectMain()
    {
        if (!$_SESSION['admin']['auth']) {
            header("Location: /");
            exit();
        }
    }
    protected function ExistSessionAuth()
    {
        if(isset($_SESSION['auth']) && $_SESSION['auth'] == '1')
            return true;
        header('Location: /');
        exit();
    }
    protected function AccessPageNotAuth()
    {
        if(!empty($_SESSION['user-data']) && !empty($_SESSION['auth']))
        {
            header("Location: /profile");
            exit();
        }
    }

    public function GetTplView()
    {
        return array('main' => 'main/show.tpl.php',
                               'advert-tpl' => 'advert/advert.tpl.php',
                               'tariff-block' => 'block/tariff.tpl.php',
                               'activate-modal' => 'modal/activate.tpl.php',
                               'change-tariff-modal' => 'modal/change-tariff.tpl.php',
                               'delete-modal' => 'modal/delete.tpl.php',
                               'distinguish-modal' => 'modal/distinguish.tpl.php',
                               'off-advert-modal' => 'modal/off-advert.tpl.php',
                               'prolong' => 'modal/prolong.tpl.php',
                               'up' => 'modal/up.tpl.php',
                               'voting-block' => 'block/voting-tpl-block.php');
    }
    public function Json($data)
    {
        print_r(json_encode($data));
        exit;
    }

    public function Redirect($action, $controller = null)
    {
        header("Location: " . Url::Action($action, $controller));
        exit();
    }
    protected function NavigationPage($sql, $rows, $links)
    {
        $this->pagination = new Pagination($sql, $rows, $links);
        $objResult = $this->pagination->PaginateAdvert();
        $data['obj'] = $objResult->fetchAll(PDO::FETCH_ASSOC);
        $data['navigation'] = $this->pagination->RenderFullNav();
        return $data;
    }


    protected function ParseGet($get)
    {
        $arrParam = explode("&", $get);
        foreach($arrParam as $val)
        {
            $arrVal = explode("=", $val);
            $arrUrl[$arrVal[0]] = $arrVal[1];
        }
        return $arrUrl;
    }
    public function RunAjax()
    {
        if( isset($this->_p['ajax-query']) )
        {
            $method    = $this->_p['method'];
            $typeClass = $this->_p['type-class'];
            $result    = $this->$typeClass->$method();
            if($result) echo  $result;
            exit();
        }
    }

    public function PrepareFiles($path)
    {
        if(!empty($_FILES))
        {
            $objUpload = new Upload($path);
            $method = $this->_p['method'];
            $objUpload->$method($path);
            exit();
        }
    }

    //Если переданы аргументы(группа лайков, ид оцениваемой записи) - метод возвращает информацию лайкал, дислайкал или не голосовал юзер;
    // Без аргументов метод заносит в БД о Выборе юзера(лайк, дислайк), информация передаётся аяксом
    public function GetSetUserLikes($idLikesGroup=false, $idRecord=false)
    {
        if($idLikesGroup!==false && $idRecord!==false){
            return $this->likes->GetUserLikesInfo($idLikesGroup, $idRecord);
        }
        if(isset($this->_p['method']))
        {
            $method = $this->_p['method'];
            $result = $this->likes->$method();
            if($result) echo  $result;
            exit();
        }
    }

    public function GetRecordLikes($idLikesGroup, $idRecord)
    {
        return $this->likes->GetRecordLikes($idLikesGroup, $idRecord);
    }

    public function TplAuth()
    {
        $this->arrTpl = (isset($_SESSION['auth'])) ? array('menu/auth-menu.tpl.php', 'index-auth.tpl.php') : array('menu/main-menu.tpl.php', 'index.tpl.php');
        return $this->arrTpl;
    }
	public function AddJs($file)
    {
        $arrUrl = explode("/", $_SERVER['REDIRECT_URL']);
        $path = ($arrUrl[1] == "administration") ? "/".$arrUrl[1]."/".$arrUrl[2] : "/".$arrUrl[1];
        $name = "/skins/tpl".$path. "/js/". $file.".js";
        $this->view->RenderJs($name);
    }
	public function AddCss($file)
    {
        $arrUrl = explode("/", $_SERVER['REDIRECT_URL']);
        $path = ($arrUrl[1] == "administration") ? "/".$arrUrl[1]."/".$arrUrl[2] : "/".$arrUrl[1];
        $name = "/skins/tpl".$path. "/css/". $file.".css";
        $this->view->RenderCss($name);
    }

}
