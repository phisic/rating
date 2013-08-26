<h1><?=$rating['Name']?></h1>
    <?php foreach ($items as $n=>$i) {?>
    <div class="row">
            <div class="span1">
                #<?=$n+1?>
            </div>
            <div><img class="img-thumbnail" src="<?=$i['Image']?>"></div>
            <div class="col-lg-8 padding_left0">
                <div>
                    <span class="label label-info">#<?=$n+1?></span>
                    <strong><?=$i['Keyword']?></strong>
                </div>
                <div>Web Score: <strong><?=round($i['Rank']/1E6)?>K</strong> <span class="label label-success">+1.5K</span></div>
                <?php if(isset($i['Description'])){ ?> <?=$i['Description']?> <a href="">read more</a> <?php } ?>
            </div>
        </div> 
       
    <?php } ?>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'), 'pages' => $pager)); ?>