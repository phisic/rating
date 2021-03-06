<div class="container">
<h1><?=$rating['Name']?></h1>
</div>
<ul class="breadcrumb">
		<li><a href="/" title="back to front page"><i class="glyphicon glyphicon-home"></i></a></li>
				<li><a href="<?=Yii::app()->seoUrl('category', $rating['Category'])?>"><?=$rating['Category']?></a></li>
		
	</ul>
<div class="container">
<table class="table table-condensed">
    <tr>
    <th style="width:2%;">#</th>
    <th style="width:20%;">Image</th>
    <th style="width:55%;">Name</th>
    <th style="width:10%;" class="text-center">Web Rank <a style="text-decoration: none;"><i style="font-size:16px;" class="glyphicon glyphicon-question-sign webrank"></i></a></th>
    <th style="width:13%;">Date Updated</th>
</tr>
    <?php foreach ($items as $n=>$i) {?>
   <tr>
            <td>
                <strong><?=($n+1+$pager->offset)?></strong>
            </td>
            <td><a href="<?=Yii::app()->seoUrl('', $i['Keyword'].'-'.$rating['Id']);?>"><img alt="<?=$i['Keyword']?>" class="img-thumbnail" src="/rt/p<?=$i['Id'].'-200x200.jpg'?>"></a></td>
            <td>
                    <a href="<?=Yii::app()->seoUrl('', $i['Keyword'].'-'.$rating['Id']);?>"><strong><?=$i['Keyword']?></strong></a>
                    <div class="hidden-xs"><?=isset($i['Description'])?$i['Description']:''?></div>
            </td>
            <td class="text-center"><strong><?=Yii::app()->rank($i['Rank'])?></strong>
                <?php if($i['RankDelta']==0){}?>
                
                <?php if($i['RankDelta']>0){?>
                <span class="label label-success">+<?=Yii::app()->rank($i['RankDelta'])?></span>
                <?php }elseif($i['RankDelta']<0){ ?>
                <span class="label label-danger"><?=Yii::app()->rank($i['RankDelta'])?></span>
                <?php }?>
            </td>
            <td><?=date('F j, Y',strtotime($i['RankDate']))?></td>
       </tr>
       
    <?php } ?>
    </table>
<?php $this->widget('CLinkPager', array('htmlOptions' => array('class' => 'pager'),'header'=>false, 'pages' => $pager)); ?>
</div>