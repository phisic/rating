<h1><?=$i['Keyword']?></h1>
<img alt="<?=$i['Keyword']?>" src="/rt/p<?=$i['Id']?>-300x300.jpg">
<?php
foreach ($ratings as $rating){
    echo '#'.$rating['Position'].' in <a href="">'.$rating['Name'].'</a><br/> ';
}

foreach ($text as $t){
    echo '<div><div>'.$t['Content'].'</div>Source: <noindex><a rel="nofollow" href="'.$t['SourceUrl'].'">'.$t['Source'].'</a></noindex></div>';
}

?>