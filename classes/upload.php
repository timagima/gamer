<?php
namespace classes;
use classes\SimpleImage;
class Upload extends SimpleImage
{

    public function __construct()
    {

    }

    public function UploadImgTinyMce($path)
    {
        foreach ($_FILES as $key => $value)
        {
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $path . "/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            if($_POST['type-file'] == "img")
                $this->load($value['tmp_name'])->save($fileName);
            else
                $this->UploadFile($value['tmp_name'], $fileName);
        }
        echo $fileName;
    }
    public function UploadImg($path)
    {
        foreach ($_FILES as $key => $value)
        {
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $path . "/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            if($_POST['type-file'] == "img")
                $this->load($value['tmp_name'])->save($fileName);
            else
                $this->UploadFile($value['tmp_name'], $fileName);
        }
        echo $fileName;
    }

    private function UploadFile($tmpName, $fileName)
    {
        move_uploaded_file($tmpName, $fileName);
    }
}