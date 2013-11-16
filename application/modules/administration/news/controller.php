<?php

namespace application\modules\administration\news;
use application\core\mvc\MainController as MainController;
use application\modules\administration\news\model as Model;
use classes\SimpleImage;
use classes\url;

class Controller extends MainController
{
    private static $storage_path = "storage/publications/news/";
    private $filter = array("year" => "", "month" => "", "day" => "", "page" => 1);
    public $model;

    function __construct()
    {
        parent::__construct();
        $this->model = new Model();
    }

    public function ActionIndex()
    {
        $data["rows"] = $this->model->GetData();
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/news/index.tpl.php', '', 'index-admin.tpl.php', $data);
    }

    public function ActionFilter($param)
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
        $data = array("id" => 0, "date" => date("d.m.Y"), "header" => "", "event_date" => "");
        $this->view->Generate('menu/admin-menu.tpl.php', 'administration/news/edit.tpl.php', '', 'index-admin.tpl.php', $data);
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
            $this->AddImages($res["id"]);
            $this->Redirect("index");
        } else {
            $data = $this->model->GetById($_GET["id"]);
            if ($data["source_img_top"])
                $data["source_img"] = array(array("filename" => $data["source_img_top"], "filename_b" => $data["source_img"]));
            $data["img"] = $this->model->GetImages($_GET["id"]);
            $this->view->Generate('menu/admin-menu.tpl.php', 'administration/news/edit.tpl.php', '', 'index-admin.tpl.php', $data);
        }
    }

    public function ActionDelete()
    {
        $id = $_REQUEST["id"];
        if ($id > 0) {
            $res = $this->model->Delete($id);
            return $res;
        }
        return Route::ErrorPage404();
    }

    public function ActionUpload()
    {
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

    private function AddImages($id)
    {
        $addImages = json_decode($this->_p["add-images"]);
        if ($addImages) {
            foreach ($addImages->small as $small) {
                foreach($addImages->big as $big)
                {
                    $this->model->AddImage($id, $small, $big);
                }

            }
        }
    }
//{"big":["storage\/publications\/news\/380e9f583959a62cdb95da96eb9e3b41_b.jpg","storage\/publications\/news\/0d0e2876440fdd50ad9ac6c57d0a5d53_b.jpg","storage\/publications\/news\/4c2af903ebf6e04d90773c92e7d385cc_b.jpg","storage\/publications\/news\/1576b910ac71ae7702e43b6f86d3299e_b.jpg","storage\/publications\/news\/e4b120fc862a21278c7ec2348fbe434f_b.jpg"],"small":["storage\/publications\/news\/380e9f583959a62cdb95da96eb9e3b41.jpg","storage\/publications\/news\/0d0e2876440fdd50ad9ac6c57d0a5d53.jpg","storage\/publications\/news\/4c2af903ebf6e04d90773c92e7d385cc.jpg","storage\/publications\/news\/1576b910ac71ae7702e43b6f86d3299e.jpg","storage\/publications\/news\/e4b120fc862a21278c7ec2348fbe434f.jpg"]}

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