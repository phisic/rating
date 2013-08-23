<div class="row-fluid">

    <?php
    $cnt = ceil(count($ratings) / 4);
    foreach (array_chunk($ratings, $cnt) as $r3) {
        ?>
        <div class="span3">
            <ul class="thumbnails">
                <?php foreach ($r3 as $r) { ?> 
                    <li>
                        <div class="thumbnail">
                            <div class="rating"><strong><?= $r['Name'] ?></strong></div>
                            <img src="/rt/r<?= $r['Id'] ?>-200x200.jpg">
                            <?php  foreach ($r['i'][$r['Id']] as $n=>$i){?>
                            <div><b>#<?=$n+1?>.</b> <?=$i['Keyword']?></div>
                            <?php }?>
                            <div><a href="">View full rating</a> 1267 items</div>
                            <br>
                            <div><label class="label-success label">Growing</label> <a href="">1267 items</a></div>
                            <div><label class="label-important label">Losing</label> <a href="">1267 items</a></div>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
    <?php } ?>
</div>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'), 'pages' => $pager)); ?>