<div class="well">Do you know what is most popular on world wide web?
    Be in touch about everything growing popularity.
    Everything published here is open and it means you can create your own popularity ratings, add missing items into existing ratings and share them with others.
    We build ratings for most popular on the web and we happy if you join to us.
</div>
<?php 
$rlist = Yii::app()->helper->getShortRating(0, 20);
foreach($rlist['Rating'] as $r){
    ?>
<div class="clearfix">
<h1><?=$r['Name']?></h1>
    <div class="col-lg-4 padding_left0">
        <span class="label label-info">Most popular</span>
        <?php $n=0; foreach ($rlist['Items'][$r['Id']] as $k=>$i){ $n++;?>
        <div class="list_item clearfix">
            <div class="col-lg-4 padding_left0">
                <img class="img-thumbnail" src="<?=$i['Image']?>">
            </div>
            <div class="col-lg-8 padding_left0">
                <div>
                    <span class="label label-info">#<?=$n?></span>
                    <strong><?=$i['Keyword']?></strong>
                </div>
                <div>Web Score: <strong><?=round($i['Rank']/1E6)?>K</strong> <span class="label label-success">+1.5K</span></div>
                <?php if(isset($i['Description'])){ ?> <?=$i['Description']?> <a href="">read more</a> <?php } ?>
            </div>
        </div> 
        <?php } ?>
    </div>
    <div class="col-lg-4 padding_left0">
        <span class="label label-success">Growing popularity</span>
        <?php $n=0; if(isset($rlist['Growing'][$r['Id']]))foreach ($rlist['Growing'][$r['Id']] as $k=>$i){ $n++;?>
        <div class="list_item clearfix">
            <div class="col-lg-4 padding_left0">
                <img class="img-thumbnail" src="<?=$i['Image']?>">
            </div>
            <div class="col-lg-8 padding_left0">
                <div>
                    <span class="label label-info">#<?=$n?></span>
                    <strong><?=$i['Keyword']?></strong>
                </div>
                <div>Web Score: <strong><?=round($i['Rank']/1E6)?>K</strong> <span class="label label-success">+1.5K</span></div>
                <?php if(isset($i['Description'])){ ?> <?=$i['Description']?> <a href="">read more</a> <?php } ?>
            </div>
        </div> 
        <?php } ?>

    </div>
    <div class="col-lg-4 padding_left0">
        <span class="label label-danger">Losing popularity</span>
        <?php $n=0; if(isset($rlist['Losing'][$r['Id']]))foreach ($rlist['Losing'][$r['Id']] as $k=>$i){ $n++;?>
        <div class="list_item clearfix">
            <div class="col-lg-4 padding_left0">
                <img class="img-thumbnail" src="<?=$i['Image']?>">
            </div>
            <div class="col-lg-8 padding_left0">
                <div>
                    <span class="label label-info">#<?=$n?></span>
                    <strong><?=$i['Keyword']?></strong>
                </div>
                <div>Web Score: <strong><?=round($i['Rank']/1E6)?>K</strong> <span class="label label-success">+1.5K</span></div>
                <?php if(isset($i['Description'])){ ?> <?=$i['Description']?> <a href="">read more</a> <?php } ?>
            </div>
        </div> 
        <?php } ?>

    </div>
</div>
<div class="clearfix"></div><br /><div class="col-lg-5"><a>View Full Rating</a> (1678 items)</div>
<?php } ?>