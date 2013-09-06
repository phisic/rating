<h1><?=$rating['Name']?></h1>
<table class="table table-condensed">
    <tr>
    <th>#</th>
    <th>Image</th>
    <th>Name</th>
    <th>Web Rank</th>
    <th>Updated</th>
</tr>
    <?php foreach ($items as $n=>$i) {?>
   <tr>
            <td>
                <?=($n+1+$pager->offset)?>
            </td>
            <td><a href="<?=Yii::app()->seoUrl('', $i['Keyword']);?>"><img alt="<?=$i['Keyword']?>" class="img-thumbnail" src="/rt/p<?=$i['Id'].'-200x200.jpg'?>"></a></td>
            <td>
                    <a href="<?=Yii::app()->seoUrl('', $i['Keyword']);?>"><strong><?=$i['Keyword']?></strong></a>
                    <div><?=isset($i['Description'])?$i['Description']:''?></td>
            </td>
            <td><strong><?=$i['Rank']?></strong>
                <?php if($i['RankDelta']==0){}?>
                
                <?php if($i['RankDelta']>0){?>
                <span class="label label-success">+<?=$i['RankDelta']?></span>
                <?php }elseif($i['RankDelta']<0){ ?>
                <span class="label label-danger"><?=$i['RankDelta']?></span>
                <?php }?>
            </td>
            <td><?=date('j/m/y',strtotime($i['RankDate']))?></td>
       </tr>
       
    <?php } ?>
    </table>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'), 'pages' => $pager)); ?>