<style>
    ul{margin:0;padding:0;};
    ul li{margin:0;padding:0;};
</style>

<?
function drawMenu($menu,$vertical=true){
    $style = '';
    if(!$vertical)
        $style = " style='display:inline;margin-right:15px;'";
    echo "<ul>";
    foreach($menu as $item){
        echo "<li$style>";
        echo "<a href='{$item['href']}'>{$item['link']}</a>";
        echo "</li>";
    }
    echo "</ul>";
};
/*Меню*/
$leftMenu = array(
    array('link'=>'Домой','href'=>'index.php'),
    array('link'=>'О нас','href'=>'about.php'),
    array('link'=>'Контакты','href'=>'contact.php'),
    array('link'=>'Таблица умножения','href'=>'table.php'),
    array('link'=>'Калькулятор','href'=>'calc.php')
);
/***********************************************/
if($_SERVER['REQUEST_METHOD']=='POST'){
    $cols = abs((int)$_POST['cols']);
    $rows = abs((int)$_POST['rows']);
    $color = trim(strip_tags($_POST['color']));
}
if(! isset($cols)){$cols=10;};
if(! isset($rows)){$rows=10;};
if(! isset($color)){$color='red';};

/*Таблица умножения*/
function drawTable($cols,$rows,$color){
    static $cnt;
    $cnt++;
    echo "Таблица рисуется $cnt раз.";
    echo "<table border='1'>";
    for($td=1; $td <= $rows; $td++){
        echo "<tr>";
        for($tr=1; $tr <= $cols; $tr++){
            if($tr==1 or $td==1)
                echo "<th style='background:$color;'>".$td*$tr."</th>";
            else
                echo "<td>".$td*$tr."</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
}


?>
<?

drawMenu($leftMenu);
drawMenu($leftMenu,false);



?>
<?
$output='';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $n1 = (int)$_POST['num1'];
    $n2 = (int)$_POST['num2'];
    $op = trim(strip_tags($_POST['operator']));
    $output = "$n1 $op $n2 = ";
    switch($op){
        case '+': $output .= $n1 + $n2;break;
        case '-': $output .= $n1 - $n2;break;
        case '*': $output .= $n1 * $n2;break;
        case '/':
            if($n2 === 0)
                $output = 'Деление на 0 запрещено!';

            else
                $output .= $n1 / $n2;
            break;
        default: $output = "Неизвестный оператор: '$op''";
    }
}
if($output)
    echo "<h3>Результат: $output</h3>";
?>
<form action="" method="post">
    <label>Число 1: </label><br>
    <input type="text"name="num1" value="<?=$n1?>"/><br>
    <label>Оператор: </label><br>
    <input type="text" name="operator" value="<?=$op?>"/><br>
    <label>Число 2: </label><br>
    <input type="text" name="num2" value="<?=$n2?>"/><br>
    <input type="submit" value="Считать"/>

</form>
<form action="<?=$_SERVER['REQUEST_URI']?>" method="post">
    <label>Количество колонок: </label><br>
    <input name="cols" type="text" value=""><br>
    <label>Количество строк: </label><br>
    <input name="rows" type="text" value=""><br>
    <label>Цвет: </label><br>
    <input name="color" type="text" value=""><br>
    <br>
    <input type="submit" value="Создать">
</form>

