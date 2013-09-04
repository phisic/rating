<h1><?=$i['Keyword']?></h1>
<img src="/rt/p<?=$i['Id']?>-500x500.jpg">
<?php
foreach ($r as $rating){
    echo '<a href="">'.$rating['Name'].'</a> ';
}
?>