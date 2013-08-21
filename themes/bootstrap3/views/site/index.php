<div class="well">Do you know what is most popular on world wide web?
    Be in touch about everything growing popularity.
    Everything published here is open and it means you can create your own popularity ratings, add missing items into existing ratings and share them with others.
    We build ratings for most popular on the web and we happy if you join to us.
</div>
<?php 
$rlist = Yii::app()->helper->getShortRating(0, 20);
foreach($rlist['Rating'] as $r){
    ?>
<h1><?=$r['Name']?></h1>
<div class="row-fluid vlist">
    <div class="span4">
        <div class="row-fluid"><div class="span4"><span class="label label-info">Most popular</span></div></div> 
        <?php $n=0; foreach ($rlist['Items'][$r['Id']] as $k=>$i){ $n++;?>
        <div class="row-fluid vlist">
            <div class="span4">
                <img src="<?=$i['Image']?>">
            </div>
            <div class="span8">
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
    <div class="span4">
        <div class="row-fluid"><div class="span4"><span class="label label-success">Growing popularity</span></div></div> 
        <?php $n=0; if(isset($rlist['Growing'][$r['Id']]))foreach ($rlist['Growing'][$r['Id']] as $k=>$i){ $n++;?>
        <div class="row-fluid vlist">
            <div class="span4">
                <img src="<?=$i['Image']?>">
            </div>
            <div class="span8">
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
    <div class="span4">
        <div class="row-fluid"><div class="span4"><span class="label label-important">Losing popularity</span></div></div> 
        <?php $n=0; if(isset($rlist['Losing'][$r['Id']]))foreach ($rlist['Losing'][$r['Id']] as $k=>$i){ $n++;?>
        <div class="row-fluid vlist">
            <div class="span4">
                <img src="<?=$i['Image']?>">
            </div>
            <div class="span8">
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
<div class="row-fluid"><div class="span5"><a>View Full Rating</a> (1678 items)</div></div>
<?php } ?>

<h1>Most Famous Billionaires on the planet</h1>
<div class="row-fluid vlist">
    <div class="span4">
        <div class="row-fluid"><div class="span4"><span class="label label-info">Most popular</span></div></div> 
        <div class="row-fluid vlist">
            <div class="span4">
                <img src="http://t1.gstatic.com/images?q=tbn:ANd9GcRv_a_Ob5prt3l6EbaIbrwS5aEDd8XSXmz4VQqPWVKKBuq0X4PoQxzjdkttlaQmyTUAQaI3AgWsNQ">
            </div>
            <div class="span8">
                <div>
                    <span class="label label-info">#1</span>
                    <strong>Jared Remy</strong>
                </div>
                <div>Web Score: 27000</div>
                Jared W. Remy was arraigned in Waltham District
            </div>
        </div>    
        <div class="row-fluid">
            <div class="span4">
                <img src="http://t1.gstatic.com/images?q=tbn:ANd9GcRv_a_Ob5prt3l6EbaIbrwS5aEDd8XSXmz4VQqPWVKKBuq0X4PoQxzjdkttlaQmyTUAQaI3AgWsNQ">
            </div>
            <div class="span8">
                <div>
                    <span class="label label-info">#1</span>
                    <strong>Jared Remy</strong>
                </div>
                <div>Web Score: 27000</div>
                Jared W. Remy was arraigned in Waltham District
            </div>
        </div> 

    </div>
    <div class="span4">
        <div class="row-fluid"><div class="span4"><span class="label label-success">Growing popularity</span></div></div> 
        <div class="row-fluid vlist">
            <div class="span4">
                <img src="http://t1.gstatic.com/images?q=tbn:ANd9GcRv_a_Ob5prt3l6EbaIbrwS5aEDd8XSXmz4VQqPWVKKBuq0X4PoQxzjdkttlaQmyTUAQaI3AgWsNQ">
            </div>
            <div class="span8">
                <div>
                    <span class="label label-info">#1</span>
                    <strong>Jared Remy</strong>
                </div>
                <div>Web Score: 27000</div>
                Jared W. Remy was arraigned in Waltham District
            </div>
        </div>    
        <div class="row-fluid">
            <div class="span4">
                <img src="http://t1.gstatic.com/images?q=tbn:ANd9GcRv_a_Ob5prt3l6EbaIbrwS5aEDd8XSXmz4VQqPWVKKBuq0X4PoQxzjdkttlaQmyTUAQaI3AgWsNQ">
            </div>
            <div class="span8">
                <div>
                    <span class="label label-info">#1</span>
                    <strong>Jared Remy</strong>
                </div>
                <div>Web Score: 27000</div>
                Jared W. Remy was arraigned in Waltham District
            </div>
        </div> 

    </div>
    <div class="span4">
        <div class="row-fluid"><div class="span4"><span class="label label-important">Losing popularity</span></div></div> 
        <div class="row-fluid vlist">
            <div class="span4">
                <img src="http://t1.gstatic.com/images?q=tbn:ANd9GcRv_a_Ob5prt3l6EbaIbrwS5aEDd8XSXmz4VQqPWVKKBuq0X4PoQxzjdkttlaQmyTUAQaI3AgWsNQ">
            </div>
            <div class="span8">
                <div>
                    <span class="label label-info">#1</span>
                    <strong>Jared Remy</strong>
                </div>
                <div>Web Score: 27000</div>
                Jared W. Remy was arraigned in Waltham District
            </div>
        </div>    
        <div class="row-fluid">
            <div class="span4">
                <img src="http://t1.gstatic.com/images?q=tbn:ANd9GcRv_a_Ob5prt3l6EbaIbrwS5aEDd8XSXmz4VQqPWVKKBuq0X4PoQxzjdkttlaQmyTUAQaI3AgWsNQ">
            </div>
            <div class="span8">
                <div>
                    <span class="label label-info">#1</span>
                    <strong>Jared Remy</strong>
                </div>
                <div>Web Score: 27000</div>
                Jared W. Remy was arraigned in Waltham District
            </div>
        </div> 

    </div>
</div>
<div class="row-fluid"><div class="span5"><a>View Full Rating</a> (1678 items)</div></div>