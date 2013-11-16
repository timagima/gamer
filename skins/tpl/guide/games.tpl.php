<style>
    .list-main-games{padding: 35px 35px 10px 35px;}
    .list-main-games img{width: 400px;}
    .list-main-games-right{margin-left: 20px;}
</style>
<div class="contents">
<?php
    $i = 0;
foreach($data['games'] as $r)
{
    $i++;
    $class = ($i%2 == 0) ? "list-main-games-right" : "";
    echo "<a class='left list-main-games ".$class."' href='/guide/games/".$r->id."'><img src='/storage/".$r->source_img."' title='".$r->name."' /></a>";
}
?>
</div>