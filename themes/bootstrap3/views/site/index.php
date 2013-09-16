<div class="container" style="padding-top:15px;">
<div class="well">
    Presented ratings are not based on the decisions of any experts and professionals. 
    The rating is based on naturally on the opinions of all Internet users including you and me.
    That's why we named it open web rating. 
    Popularity is determined by a simple parameter we named it <b>"Web Rank"</b>. 
    "Web Rank" - reflects how the theme is popular on the Internet. 
    There is simple rule, the more people talk about certain theme in their comments, articles and reviews and it results higher web rank for this theme.
</div>
<table width="100%">
    <?php
    foreach ($list['Rating'] as $r) {
        ?>
        <tr>
            <td colspan="3">
                <h1><?= $r['Name'] ?></h1>
            </td>
        </tr>
        <tr>
            <td width="33%" valign="top">

                <span class="label label-info">Most popular</span>
                <?php
                $n = 0;
                foreach ($list['Items'][$r['Id']] as $k => $i) {
                    $n++;
                    ?>
                    <div class="list_item clearfix">
                        <div class="col-lg-4 padding_left0">
                            <img class="img-thumbnail" src="/rt/p<?= $i['Id'] . '-100x100.jpg' ?>">
                        </div>
                        <div class="col-lg-8 padding_left0">
                            <div>
                                <span class="label label-info">#<?= $n ?></span>
                                <strong><a href="<?= Yii::app()->seoUrl('', $i['Keyword'].'-'.$i['RatingId']); ?>"><?= $i['Keyword'] ?></a></strong>
                            </div>
                            <div>Web Rank: <strong><?= Yii::app()->rank($i['Rank']) ?></strong> </div>
                            <div>        
                                <?php if ($i['RankDelta'] > 0) { ?>
                                    <span class="label label-success">+<?= Yii::app()->rank($i['RankDelta']) ?></span>
                                <?php } elseif ($i['RankDelta'] < 0) { ?>
                                    <span class="label label-danger"><?= Yii::app()->rank($i['RankDelta']) ?></span>
                            <?php } ?>
                            </div>
        <?php if (isset($i['Description'])) { ?> <?= $i['Description'] ?>  <?php } ?>
                        </div>
                    </div> 
    <?php } ?>
            </td>
            <td width="33%" valign="top">
                <span class="label label-success">Growing popularity</span>
                <?php
                $n = 0;
                if (isset($list['Growing'][$r['Id']]))
                    foreach ($list['Growing'][$r['Id']] as $k => $i) {
                        $n++;
                        ?>
                        <div class="list_item clearfix">
                            <div class="col-lg-4 padding_left0">
                                <img class="img-thumbnail" src="/rt/p<?= $i['Id'] . '-100x100.jpg' ?>">
                            </div>
                            <div class="col-lg-8 padding_left0">
                                <div>
                                    <span class="label label-info">#<?= $n ?></span>
                                    <strong><a href="<?= Yii::app()->seoUrl('', $i['Keyword'].'-'.$i['RatingId']); ?>"><?= $i['Keyword'] ?></a></strong>
                                </div>
                                <div>Web Rank: <strong><?= Yii::app()->rank($i['Rank']) ?></strong></div>
                                <div><span class="label label-success">+<?= Yii::app()->rank($i['RankDelta']) ?></span></div>
            <?php if (isset($i['Description'])) { ?> <?= $i['Description'] ?>  <?php } ?>
                            </div>
                        </div> 
                    <?php } ?>

            </td>
            <td valign="top">
                <span class="label label-danger">Losing popularity</span>
    <?php
    $n = 0;
    if (isset($list['Losing'][$r['Id']]))
        foreach ($list['Losing'][$r['Id']] as $k => $i) {
            $n++;
            ?>
                        <div class="list_item clearfix">
                            <div class="col-lg-4 padding_left0">
                                <img class="img-thumbnail" src="/rt/p<?= $i['Id'] . '-100x100.jpg' ?>">
                            </div>
                            <div class="col-lg-8 padding_left0">
                                <div>
                                    <span class="label label-info">#<?= $n ?></span>
                                    <strong><a href="<?= Yii::app()->seoUrl('', $i['Keyword'].'-'.$i['RatingId']); ?>"><?= $i['Keyword'] ?></a></strong>
                                </div>
                                <div>Web Rank: <strong><?= Yii::app()->rank($i['Rank']) ?></strong> </div>
                                <div><span class="label label-danger"><?= Yii::app()->rank($i['RankDelta']) ?></span></div>
            <?php if (isset($i['Description'])) { ?> <?= $i['Description'] ?>  <?php } ?>
                            </div>
                        </div> 
            <?php } ?>

            </td>
        </tr>  
        <tr><td colspan="3">
                <div class="clearfix"></div><br /><div class="col-lg-5"><a href="<?= Yii::app()->seoUrl('rating', $r['Name']); ?>">View Full Rating</a> (<?= $r['count'] ?> items)</div>
            </td>
        </tr>
<?php } ?>
</table>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'), 'pages' => $pager)); ?>
</div>