<div class="container"><h1><?= $i['Keyword'] ?></h1></div>
<ul class="breadcrumb">
    <li><a href="/" title="back to front page"><i class="glyphicon glyphicon-home"></i></a></li>
    <li><a href="<?= Yii::app()->seoUrl('category', $i['Category']) ?>"><?= $i['Category'] ?></a></li>
    <li><a href="<?= Yii::app()->seoUrl('rating', $i['RatingName']) ?>"><?= $i['RatingName'] ?></a></li>
</ul>
<div class="container">
    <div class="row" style="padding-right: 10px;">
        <div class="col-md-3"><img class="img-thumbnail" alt="<?= $i['Keyword'] ?>" src="/rt/p<?= $i['Id'] ?>-300x300.jpg"></div>
        <div class="col-md-<?=count($ratings)>1?4:8?>" style="font-size:18px; margin-top: 10px;">
            <?php
                $rating = array_shift($ratings);
                echo '<div><strong>#' . $rating['Position'] . '</strong> in <a href="' . Yii::app()->seoUrl('rating', $rating['Name']) . '">' . $rating['Name'] . '</a> rating</div>';

                echo '<div><i class="glyphicon glyphicon-signal"></i> Web Rank: <strong>' . Yii::app()->rank($rating['Rank']) . '</strong> <a style="text-decoration: none;"><i style="font-size:16px;" class="glyphicon glyphicon-question-sign webrank"></i></a></div>';
                if ($rating['RankDelta'] == 0) {
                    $popularity = '<strong>stable</strong>';
                    $picon = 'glyphicon glyphicon-minus-sign';
                } elseif ($rating['RankDelta'] < 0) {
                    $popularity = '<strong>losing</strong> <span class="label label-danger">– ' . abs(Yii::app()->rank($rating['RankDelta'])) . '</span>';
                    $picon = 'glyphicon glyphicon-circle-arrow-down';
                } elseif ($rating['RankDelta'] > 0) {
                    $popularity = '<strong>growing</strong> <span class="label label-success">+ ' . Yii::app()->rank($rating['RankDelta']) . '</span>';
                    $picon = 'glyphicon glyphicon-circle-arrow-up';
                }
                echo '<div><i class="' . $picon . '"></i> Popularity is ' . $popularity . '</div>';
                echo '<div><i class="glyphicon glyphicon-calendar"></i> Last updated on ' . date('F j, Y', strtotime($rating['RankDate'])) . '</div><br>';
            ?>
            
        </div>
        <?php if($ratings){?>
        <div class="col-md-4" style="margin-top: 10px;">
         <strong>Also, <?=$i['Keyword']?> is participating in the following ratings</strong>
        <?php 
            foreach ($ratings as $rating){
                
                echo '<div><strong>#' . $rating['Position'] . '</strong> in <a href="' . Yii::app()->seoUrl('rating', $rating['Name']) . '">' . $rating['Name'] . '</a></div>';
                
            }
        ?>
        </div>
        <?php } ?>
    </div>
</div>
<br>
<ul class="breadcrumb">
    <?php
    foreach ($left as $l)
        echo '<li>' . $l['Position'] . '. <a href="' . Yii::app()->seoUrl('', $l['Keyword'] . '-' . $i['RatingId']) . '">' . $l['Keyword'] . '</a></li>';
    echo '<li><strong>' . $i['Position'] . '. ' . $i['Keyword'] . '</strong></li>';
    foreach ($right as $l)
        echo '<li>' . $l['Position'] . '. <a href="' . Yii::app()->seoUrl('', $l['Keyword'] . '-' . $i['RatingId']) . '">' . $l['Keyword'] . '</a></li>';
    ?>

</ul>
<?php if ($text) { ?>
    <div class="container">
        <h3>Useful information</h3>
        <?php
        foreach ($text as $t) {
            echo '<div class="container well"><div>' . strip_tags($t['Content'], '<p>,<b>,<i>') . '</div><p>Read more: <a target="_blank" rel="nofollow" href="' . Yii::app()->createUrl('site/external').'?id='.$t['Id'] . '">' . $t['Source'] . '</a></p></div>';
        }
        ?>
    </div>
    <?php } ?>