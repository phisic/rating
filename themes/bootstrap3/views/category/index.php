<div class="row rating">

    <?php
    $cnt = ceil(count($ratings) / 4);
    foreach (array_chunk($ratings, $cnt) as $r3) {
        ?>
        <div class="col-sm-6 col-md-3">

            <?php foreach ($r3 as $r) { ?> 
                <div class="thumbnail">
                    <a href="<?=Yii::app()->seoUrl('rating',$r['Name']);?>" title="View full rating <?= $r['Name'] ?>">
                    <div><strong><?= $r['Name'] ?></strong></div>
                    <div><img alt="<?= $r['Name'] ?>" src="/rt/r<?= $r['Id'] ?>-200x200.jpg"></a></div>
                    <?php foreach ($r['i'][$r['Id']] as $n => $i) { ?>
                        <div><b>#<?= $n + 1 ?>.</b> <?= $i['Keyword'] ?></div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'), 'pages' => $pager)); ?>