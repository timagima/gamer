<?php
namespace classes;
use classes\SimpleImage;
class Upload extends SimpleImage
{
    private $fileName;

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
            $this->fileName = $key;
            $ext = "." . pathinfo($value['name'], PATHINFO_EXTENSION);
            $name = $path . "/" . md5(microtime() + rand(0, 10000));
            $fileName = $name . $ext;
            if($key == "source_img_s")
            {
                $this->ImgIconGame($value, $fileName, $ext);
            }
            else if($key == "source_img_b")
            {
                $this->ImgLicenceGame($value, $fileName, $ext);
            }
            else
            {
                if($_POST['type-file'] == "img")
                    $this->load($value['tmp_name'])->save($fileName);
                else
                    $this->UploadFile($value['tmp_name'], $fileName);
            }
        }
        echo json_encode(array($fileName, $this->fileName));
    }

    private function ImgIconGame($value, $fileName, $ext)
    {
        if($ext == ".png")
        {
            $this->load($value['tmp_name'])->square_crop(128)->save($fileName);
        }
        else
        {
            echo json_encode(array("Формат файла только PNG"));
            exit();
        }
    }

    private function ImgLicenceGame($value, $fileName, $ext)
    {
        if($ext == ".jpg")
        {
            $this->load($value['tmp_name'])->square_crop(400)->save($fileName);
        }
        else
        {
            echo json_encode(array("Формат файла только JPG"));
            exit();
        }
    }

    private function UploadFile($tmpName, $fileName)
    {
        move_uploaded_file($tmpName, $fileName);
    }
}