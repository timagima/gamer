<?php
namespace application\core\mvc;
use classes\OftenFunctions;

class MainView
{
    static $types_complexity = [
        'Люблю пройти на лёгком',
        'Выбираю нечто среднее',
        'Всегда всё самое тяжёлое',
    ];


    public $user = array();
    public function __construct($user = null)
    {
        $this->user = $user;
    }


    public function Generate($menuView, $contentView, $contentViewArr, $templateView, $data = false, $headerTxt = false, $countQuery = false)
    {
        /*
          if(is_array($data)) {
          // преобразуем элементы массива в переменные
          extract($data);
          }
         */

        include 'skins/tpl/' . $templateView;
        //echo 'Количество запросов: ' . $countQuery . '.<br>';
    }

    private function MaxStrWord($text, $counttext = 10, $sep = ' ')
    {
        $words = split($sep, $text);
        if (count($words) > $counttext)
            $text = join($sep, array_slice($words, 0, $counttext));
        return $text;
    }

    private function GetDateRu($param, $data)
    {
        $arrMonth = array(1 => 'Января', 2 => 'Февраля', 3 => 'Марта', 4 => 'Апреля', 5 => 'Мая', 6 => 'Июня', 7 => 'Июля', 8 => 'Августа', 9 => 'Сентября', 10 => 'Октября', 11 => 'Ноября', 12 => 'Декабря');
        $arrWeek = array('Monday' => 'Понедельник', 'Tuesday' => 'Вторник', 'Wednesday' => 'Среда', 'Thursday' => 'Четверг', 'Friday' => 'Пятница', 'Saturday' => 'Суббота', 'Sunday' => 'Воскресение');
        return ($param == 'month') ? $arrMonth[date('n', strtotime($data))] : $arrWeek[date('l', strtotime($data))];
    }
    /*
    private function TrimStr($string, $limit)
    {
        $substring_limited = substr($string, 0, $limit);
        return substr($substring_limited, 0, strrpos($substring_limited, ' '));
    }*/

    public function TrimStr($string, $countChars)
    {
        $countString = strlen($string);
        $str = ($countString <= $countChars) ? strip_tags($string) : strip_tags(substr($string, 0, strpos($string, " ", $countChars)));
        return $str;
    }
    public function ReturnText($param)
    {
        return ( isset($_REQUEST[$param]) ) ? $_REQUEST[$param] : '';
    }

    public function GetHappyBirthday()
    {
        $age = (int)((strtotime(date("d.m.Y")) - (int)$_SESSION['user-data']['birthday']) / 60 / 60 / 24 / 365) ;
        $age = (string)$age;
        $age = $age .' '. OftenFunctions::getCorrectStr($age, 'год', 'года', 'лет');
        return $age;
        // if($age{1} == "1")
        //     $str = " год";
        // else if($age{1} >= "2" && (string)$age{1} <= "4" && $age{0} > "1")
        //     $str = " года";
        // else
        //     $str = " лет";

        // return $age . $str;
    }

    /*
    *   ГОРОД
    */
    public function GetCity(){
        // echo "<pre>";
        // print_r($_SESSION);
        // exit();
        return (string)$_SESSION['user-data']['city'];
    }
    /*
    *   ИГРОВОЙ ОПЫТ
    */
    public function GetGameExp(){
        return (string)$_SESSION['user-data']['game_experience'];
    }
    /*
    *   ЛЮБИМЫЙ ЖАНР
    */
     public function GetLoveGenre(){
        return (string)$_SESSION['user-data']['love_genre'];
    }
    /*
    *   ПРЕДПОЧИТАЕМАЕ СЛОЖНОСТЬ
    */
     public function GetLoveComplexity(){
        return (string)$_SESSION['user-data']['love_complexity'];
    }
    /*
    *   ПРЕДПОЧИТАЕМАЕ СЛОЖНОСТЬ
    */
     public function GetLoveGame(){
        return (string)$_SESSION['user-data']['love_game'];
    }

    public function LinkTournament($game, $id)
    {
        $t = str_replace("'", "", strtolower($game));
        $t = str_replace(":", "", $t);
        $t = str_replace(" ", "-", $t);
        return "?t=".$t."&id=".$id;
    }

}
