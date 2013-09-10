<div class="container"><h1><?= $i['Keyword'] ?></h1></div>
<ul class="breadcrumb">
    <li><a href="/" title="back to front page"><i class="glyphicon glyphicon-home"></i></a></li>
    <li><a href="<?= Yii::app()->seoUrl('category', $i['Category']) ?>"><?= $i['Category'] ?></a></li>
    <li><a href="<?= Yii::app()->seoUrl('rating', $i['RatingName']) ?>"><?= $i['RatingName'] ?></a></li>
</ul>
<div class="container">
    <div class="row">
        <div class="col-md-4"><img class="img-thumbnail" alt="<?= $i['Keyword'] ?>" src="/rt/p<?= $i['Id'] ?>-300x300.jpg"></div>
        <div class="col-md-8" style="font-size:16px;">
            <?php
            foreach ($ratings as $rating) {
                echo '<div style="font-size:20px;"><strong style="font-size:25px;">#' . $rating['Position'] . '</strong> in <a href="' . Yii::app()->seoUrl('rating', $rating['Name']) . '">' . $rating['Name'] . '</a></div>';

                echo '<div><i class="glyphicon glyphicon-signal"></i> Web Rank: <strong>' . Yii::app()->rank($rating['Rank']) . '</strong></div>';
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
            }
            ?>
        </div>
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
        <h3>Useful descriptions</h3>
        <?php
        foreach ($text as $t) {
            echo '<div class="container"><div>' . strip_tags($t['Content'], '<p>,<b>,<i>') . '</div>Source: <noindex><a rel="nofollow" href="' . $t['SourceUrl'] . '">' . $t['Source'] . '</a></noindex></div>';
        }
        ?>
    </div>
    <?php } ?>