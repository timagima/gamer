<?php
namespace application\modules\administration\games;
use application\core\mvc\MainController;
use application\modules\administration\games\model;
use classes\url;

class Controller extends MainController
{
    private static $storage_path = "storage/guide-games/111/";
    private static $storagePath = "storage/guide-games/";
    private $filter = array("year" => "", "month" => "", "day" => "", "page" => 1);
    public $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new Model();
        $this->RunAjax();
    }

    public function ActionIndex()
    {
        $this->Redirect("search-guide-game", "administration/games");
    }

    /* Начало добавление списка игр в справочник */
    public function ActionAddMainListGame()
    {
        $this->PrepareFiles(self::$storageTemp);
        if(empty($_GET['action']))
        {
            $tplGames = 'administration/games/list-games.tpl.php';
            $data = $this->model->ListGames();
        }
        else
        {
            if($_GET['action'] == "edit")
            {
                $data['game'] = $this->model->GetGame($_GET['id']);
                $data['difficulty'] = $this->model->GetDifficulty($_GET['id']);
            }
            $data['genre'] = $this->model->ListGenre();
            $tplGames = 'administration/games/edit-games.tpl.php';
        }
        $this->view->Generate('menu/admin-menu.tpl.php', $tplGames, $this->GetTplView(), 'index-admin.tpl.php', $data);
    }


    public function ActionEditMainGame()
    {
        if(!empty($this->_p['name']))
        {
            ($_POST['id'] > 0) ? $this->model->UpdateMainGame() : $this->model->AddMainGame();
            $this->Redirect("add-main-list-game", "administration.games");
        }
        else
        {
            echo "Заполните поле имя";
        }
    }
    /* Конец добавление списка игр в справочник */



    /* Начало основные игро-обзоры */

    public function ActionSearchGuideGame()
    {
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/search-guide-game.tpl.php', '', 'index-admin.tpl.php');
    }

    public function ActionGuideGame($id)
    {
        if(isset($id) && $id > 0){
            $data['game'] = $this->model->GetGame($id);
            $data['rubrics'] = $this->model->GetGameRubrics($id);
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/guide-game.tpl.php', '', 'index-admin.tpl.php', $data);
        }else {
            echo "Игра не найдена";
        }

    }

    public function ActionGameRubricArticles($id=false)
    {
        $id=(int)$id;
        if(isset($this->_g['id'])){
            if(!empty($this->_p) && $this->_p['id']>0){
                $this->model->EditGameRubricArticle($this->_p);
            }
            $data["rows"] = ($id==false) ? $this->model->GetRubricArticles($this->_g['id']) : $this->model->GetRubricArticles($id);
            $data["game-rubric"] = ($id==false) ? $this->model->GetGameRubricInfo($this->_g['id']) : $this->model->GetGameRubricInfo($id);
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/game-rubric-articles.tpl.php', '', 'index-admin.tpl.php', $data);
        }elseif($this->_p['id']==="0"){
            $this->model->AddRubricArticle($this->_p);
            header("Location: http://".$_SERVER['SERVER_NAME']."/administration/games/game-rubric-articles/?id=".$this->_p['id_rubric']);
            //$data["rows"] = $this->model->GetRubricArticles($this->_p['id_rubric']);
            //$data["game-rubric"] = $this->model->GetGameRubricInfo($this->_p['id_rubric']);
            //$this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/game-rubric-articles.tpl.php', '', 'index-admin.tpl.php', $data);
        }else{
            echo "404 - Not found";
        }

    }

    public function ActionEditGameRubricArticle()
    {
        $this->PrepareFiles("storage/guide-games/".$_GET['id-game']);
        $data = $this->model->GetGameRubricArticleInfo($this->_g['id']);
        $data['game-rubric']['id'] = $data['id_game'];
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/edit-game-rubric-article.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionDeleteRubricArticle()
    {
        if(isset($this->_g['id-article'])){
            $this->model->DeleteRubricArticle($this->_g['id-article']);
            header("Location: http://".$_SERVER['SERVER_NAME']."/administration/games/game-rubric-articles/?id=".$this->_g['id']);
            //$this->ActionGameRubricArticles($this->_g['id']);
        }

    }

    public function ActionCreateRubricArticle()
    {
        $this->PrepareFiles("storage/guide-games/".$_GET['id']);
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "keywords" => "", "description" => "", "title" => "", "text" => "");
        $data['game-rubric'] = $this->model->GetGameRubricInfo($this->_g['id']);
        $data["id_mpg_rubric"] = $data['game-rubric']['id_rubric'];
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/edit-game-rubric-article.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    /* Конец основные игро-обзоры */




    // todo всё что ниже нужно разбирать
    public function ActionSearchGames($param)
    {
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/search.tpl.php', '', 'index-admin.tpl.php');
    }

    public function ActionMainPage()
    {
        // todo: Добавить проверку на игру
        if(isset($_GET['id']))
        {
            $this->PrepareFiles("storage/guide-games/".$_GET['id']);
            $data['game'] = $this->model->GetGame($_GET['id']);
            $data['main-page'] = $this->model->GetMainPageGame($_GET['id']);
            $data['rubrics'] =  $this->model->GetGameRubrics($_GET['id']);
            $data['screenshot'] =  $this->model->GetMainPageScreenshot($_GET['id']);
            $data['screenshot-count'] = 6;
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/main-page.tpl.php', '', 'index-admin.tpl.php', $data);
        }

    }

    public function ActionMainPageEdit() // редактирование главной стринцы для игр
    {
        $game = $this->model->GetGame($this->_p['id-game']);
        if($this->_p['id']>0)
        {
            $this->model->EditMainPageGame($game);
        }
        else
        {
            $this->model->AddMainPageGame($game);
        }
        $this->Redirect("index");
    }

    public function ActionAddQuest()
    {
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "event_date" => "", "short" => "", "text" => "", "title" => "", "description" => "", "keywords" => "");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }
    public function ActionAddGame()
    {
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "event_date" => "", "short" => "", "text" => "", "title" => "", "description" => "", "keywords" => "");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/game.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionEditGame()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->PrepareImages();
            if ($this->_p["id"] > 0)
                $res = $this->model->Edit($this->_p);
            else
                $res = $this->model->Add($this->_p);
            $this->DeleteImages($res["id"]);
            $this->Redirect("index");
        } else {
            $data = $this->model->GetById($_GET["id"]);
            if ($data["source_img_top"])
                $data["source_img"] = array(array("filename" => $data["source_img_top"], "filename_b" => $data["source_img"]));
            $data["publ_images"] = $this->model->GetImages($_GET["id"]);
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/game.tpl.php', '', 'index-admin.tpl.php', $data);
        }
    }

    public function ActionUploadIMgMainPage()
    {
        /*if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            if(count($_FILES) > 9)
            {
                print_r(json_encode(array("error" => "Количество файлов не должно превышать 10")));
                exit();
            }
            $idGuide = $this->model->GetMainPageGame($this->_g['id']);
            $objImage = new SimpleImage();
            foreach ($_FILES as $key => $value)
            {
                $this->UploadImg($objImage, $value, $idGuide);
                $rootApp = Url::RootApp();
                // нужен вывод для одной картинки и для нескольких с условием если одна загрузилась, то её отображаем и сразу же грузим следующую

                //$this->Json(array("result" => "success", "filename" => $rootApp . $fileName, "filename_b" => $rootApp . $fileName_b));
                else
                {
                    $arrFile['big'][] = $fileName_b;
                    $arrFile['small'][] = $fileName;
                }
            }
            print_r(json_encode($arrFile));
            unset($_SESSION['multi-load']);
        }
        exit();*/
    }

    private function UploadImg($objImage, $value, $idGuide)
    {
        $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
        $name = self::$storagePath . $idGuide->id . "/"  . md5(microtime() + rand(0, 10000));
        $fileName = $name . $ext;
        $fileName_b = $name . "_b" . $ext;
        $objImage->load($value['tmp_name'])->square_crop(100)->save($fileName);
        $objImage->load($value['tmp_name'])->save($fileName_b);
    }

    public function ActionFilter()
    {
        $this->filter["year"] = (int)$_REQUEST["year"];
        $this->filter["month"] = (int)$_REQUEST["month"];
        $this->filter["day"] = strtotime($_REQUEST["day"]);
        $this->filter["page"] = isset($_REQUEST["page"]) ? (int)$_REQUEST["page"] : 1;
        $data["count"] = $this->model->GetFilterCount($this->filter);
        $data["rows"] = $this->model->GetFilterData($this->filter);
        $data["current_page"] = $this->filter["page"];
        $this->Json($data);
    }

    public function ActionCreate()
    {
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "event_date" => "", "short" => "", "text" => "", "title" => "", "description" => "", "keywords" => "");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/edit.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionEdit()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->PrepareImages();
            if ($this->_p["id"] > 0)
                $res = $this->model->Edit($this->_p);
            else
                $res = $this->model->Add($this->_p);
            $this->DeleteImages($res["id"]);
            $this->Redirect("index");
        } else {
            $data = $this->model->GetById($_GET["id"]);
            if ($data["source_img_top"])
                $data["source_img"] = array(array("filename" => $data["source_img_top"], "filename_b" => $data["source_img"]));
            $data["publ_images"] = $this->model->GetImages($_GET["id"]);
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/games/edit.tpl.php', '', 'index-admin.tpl.php', $data);
        }
    }

    public function ActionDelete()
    {
        $id = $_REQUEST["id"];
        if ($id > 0)
        {
            $res = $this->model->Delete($id);
            return $res;
        }
        return Route::ErrorPage404();
    }

    public function ActionUpload()
    {
        // todo сделать хранилище для временных картинок добавляем картинку во временную папку, после чего пишем в базу запись о картинке, и сохраняем
        // todo перенести пути хранение файлов в текущий метод сделав в шаблоне некий hidden
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if(!empty($this->_p["multi-load"]))
            {
                $_SESSION['multi-load'] = 1;
                exit();
            }
            if(count($_FILES) > 9)
            {
                print_r(json_encode(array("error" => "Количество файлов не должно превышать 10")));
                exit();
            }
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
                if(empty($_SESSION['multi-load']))
                    $this->Json(array("result" => "success", "filename" => $rootApp . $fileName, "filename_b" => $rootApp . $fileName_b));
                else
                {
                    $arrFile['big'][] = $fileName_b;
                    $arrFile['small'][] = $fileName;
                }
            }
            print_r(json_encode($arrFile));
            unset($_SESSION['multi-load']);
        }
        exit();
    }

    private function PrepareImages()
    {
        $images = json_decode($this->_p['img-main']);
        if (count($images) == 0)
        {
            $this->_p['source_img'] = "";
            $this->_p['source_img_top'] = "";
        }
        else
        {
            $this->_p['source_img'] = $images->filename_b;
            $this->_p['source_img_top'] = $images->filename;
        }
    }

    private function DeleteImages($id)
    {
        $deleteImages = json_decode($this->_p["source_img_delete"]);
        if ($deleteImages)
        {
            foreach ($deleteImages as $image)
            {
                unlink($_SERVER['DOCUMENT_ROOT'] . $image->filename);
                unlink($_SERVER['DOCUMENT_ROOT'] . $image->filename_b);
            }
        }
        $deleteImagesList = json_decode($this->_p["publ_images_delete"]);

        foreach ($deleteImagesList as $image)
        {
            unlink($_SERVER['DOCUMENT_ROOT'] . $image->filename);
            unlink($_SERVER['DOCUMENT_ROOT'] . $image->filename_b);
            $this->model->DeleteImage($id, $image->filename);
        }
    }


}