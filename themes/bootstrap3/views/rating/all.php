<div class="container">
    <h1>Full rating list</h1>
</div>
<ul class="breadcrumb">
    <li><a href="/" title="back to front page"><i class="glyphicon glyphicon-home"></i></a></li>
</ul>
<div class="container">
    <div class="row">
        <?php
        foreach ($rating as $r) {
        ?>
            <div class="col-sm-6 col-md-3">
                <a href="<?=Yii::app()->seoUrl('rating',$r['Name'])?>"><?= $r['Name'] ?></a>
            </div>
        <?php
        }
        ?>
    </div>
</div>
