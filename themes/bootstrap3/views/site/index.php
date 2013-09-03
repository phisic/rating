<div class="well">Do you know what is most popular on world wide web?
    Be in touch about everything growing popularity.
    Everything published here is open and it means you can create your own popularity ratings, add missing items into existing ratings and share them with others.
    We build ratings for most popular on the web and we happy if you join to us.
</div>
<?php

foreach ($list['Rating'] as $r) {
    ?>
    <div class="clearfix">
        <h1><?= $r['Name'] ?></h1>
        <div class="col-lg-4 padding_left0">
            <span class="label label-info">Most popular</span>
            <?php $n = 0;
            foreach ($list['Items'][$r['Id']] as $k => $i) {
                $n++; ?>
                <div class="list_item clearfix">
                    <div class="col-lg-4 padding_left0">
                        <img class="img-thumbnail" src="/rt/p<?=$i['Id'].'-200x200.jpg'?>">
                    </div>
                    <div class="col-lg-8 padding_left0">
                        <div>
                            <span class="label label-info">#<?= $n ?></span>
                            <strong><a href="<?=Yii::app()->seoUrl('', $i['Keyword']);?>"><?= $i['Keyword'] ?></a></strong>
                        </div>
                        <div>Web Score: <strong><?= round($i['Rank']) ?>K</strong> 
                                <?php if ($i['RankDelta'] > 0) { ?>
                                    <span class="label label-success">+<?= $i['RankDelta'] ?></span>
                                <?php } elseif($i['RankDelta'] < 0) { ?>
                                    <span class="label label-danger"><?= $i['RankDelta'] ?></span>
                                <?php } ?>
                        </div>
                <?php if (isset($i['Description'])) { ?> <?= $i['Description'] ?> <a href="">read more</a> <?php } ?>
                    </div>
                </div> 
    <?php } ?>
        </div>
        <div class="col-lg-4 padding_left0">
            <span class="label label-success">Growing popularity</span>
    <?php $n = 0;
    if (isset($list['Growing'][$r['Id']])) foreach ($list['Growing'][$r['Id']] as $k => $i) {
            $n++; ?>
                    <div class="list_item clearfix">
                        <div class="col-lg-4 padding_left0">
                            <img class="img-thumbnail" src="/rt/p<?= $i['Id'] . '-200x200.jpg' ?>">
                        </div>
                        <div class="col-lg-8 padding_left0">
                            <div>
                                <span class="label label-info">#<?= $n ?></span>
                                <strong><?= $i['Keyword'] ?></strong>
                            </div>
                            <div>Web Score: <strong><?= round($i['Rank']) ?>K</strong> 
                                    <span class="label label-success">+<?= $i['RankDelta'] ?></span>
                            </div>
            <?php if (isset($i['Description'])) { ?> <?= $i['Description'] ?> <a href="">read more</a> <?php } ?>
                        </div>
                    </div> 
                <?php } ?>

        </div>
        <div class="col-lg-4 padding_left0">
            <span class="label label-danger">Losing popularity</span>
    <?php $n = 0;
    if (isset($list['Losing'][$r['Id']])) foreach ($list['Losing'][$r['Id']] as $k => $i) {
            $n++; ?>
                    <div class="list_item clearfix">
                        <div class="col-lg-4 padding_left0">
                            <img class="img-thumbnail" src="/rt/p<?=$i['Id'].'-200x200.jpg'?>">
                        </div>
                        <div class="col-lg-8 padding_left0">
                            <div>
                                <span class="label label-info">#<?= $n ?></span>
                                <strong><?= $i['Keyword'] ?></strong>
                            </div>
                            <div>Web Score: <strong><?= round($i['Rank']) ?>K</strong> 
                                    <span class="label label-danger"><?= $i['RankDelta'] ?></span>
                            </div>
            <?php if (isset($i['Description'])) { ?> <?= $i['Description'] ?> <a href="">read more</a> <?php } ?>
                        </div>
                    </div> 
        <?php } ?>

        </div>
    </div>
<div class="clearfix"></div><br /><div class="col-lg-5"><a href="<?=Yii::app()->seoUrl('rating',$r['Name']);?>">View Full Rating</a> (<?=$r['count']?> items)</div><br />
<?php } ?>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'), 'pages' => $pager)); ?>