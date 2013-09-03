<h1><?=$rating['Name']?></h1>
<div class="list_item clearfix">
    <div class="col-lg-1 padding_left0"><strong>#</strong></div>
    <div class="col-lg-2 padding_left0"><strong>Image</strong></div>
    <div class="col-lg-4 padding_left0"><strong>Name</strong></div>
    <div class="col-lg-2 padding_left0" style='text-align: center;'><strong>Web score</strong></div>
    <div class="col-lg-2 padding_left0"><strong>+/-</strong></div>
    <div class="col-lg-1 padding_left0"><strong>Updated</strong></div>
</div>
    <?php foreach ($items as $n=>$i) {?>
    <div class="list_item clearfix">
            <div class="col-lg-1 padding_left0">
                <?=($n+1+$pager->offset)?>
            </div>
            <div class="col-lg-2 padding_left0"><img class="img-thumbnail" src="/rt/p<?=$i['Id'].'-200x200.jpg'?>"></div>
            <div class="col-lg-4 padding_left0">
                    <strong><?=$i['Keyword']?></strong>
                    <div><?=isset($i['Description'])?$i['Description']:''?></div>
            </div>
            <div class="col-lg-2 padding_left0" style="text-align: center;"><strong><?=$i['Rank']?></strong></div>
            <div class="col-lg-2 padding_left0">
                <?php if($i['RankDelta']==0){}?>
                
                <?php if($i['RankDelta']>0){?>
                <span class="label label-success">+<?=$i['RankDelta']?></span>
                <?php }elseif($i['RankDelta']<0){ ?>
                <span class="label label-danger"><?=$i['RankDelta']?></span>
                <?php }?>
            </div>
            <div class="col-lg-1 padding_left0"><?=$i['RankDate']?></div>
        </div> 
       
    <?php } ?>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'), 'pages' => $pager)); ?>