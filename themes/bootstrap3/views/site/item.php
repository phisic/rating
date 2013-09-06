<div class="container"><h1><?=$i['Keyword']?></h1></div>
<ul class="breadcrumb">
		<li><a href="/abernant-al"><i class="icon-home"></i></a> </li>
				<li><span class="divider">/</span> Top Reviews</li>
		
	</ul>
<div class="container">
<div class="row">
<div class="col-md-4"><img alt="<?=$i['Keyword']?>" src="/rt/p<?=$i['Id']?>-300x300.jpg"></div>
<div class="col-md-8">
<?php
foreach ($ratings as $rating){
    echo '<div style="font-size:20px;"><strong style="font-size:28px;">#'.$rating['Position'].'</strong> in <a href="">'.$rating['Name'].'</a></div>';
    echo '<div>Web Rank: <strong>'.$rating['Rank'].'</strong></div>';
    if($rating['RankDelta']==0)
        $popularity = '<strong>stable</strong>';
    elseif($rating['RankDelta']< 0)
        $popularity = '<strong>losing</strong> <span class="label label-danger">-'.$rating['RankDelta'] .'</span>';
    elseif($rating['RankDelta'] > 0)
        $popularity = '<strong>growing</strong> <span class="label label-success">+'.$rating['RankDelta'] .'</span>';
    echo '<div>Popularity is '.$popularity.'</div>';
    echo '<div>Last updated on '.date('m.d.y',  strtotime($rating['RankDate'])).'</div>';
}
?>
</div>
</div>

<?php
foreach ($text as $t){
    echo '<div><div>'.$t['Content'].'</div>Source: <noindex><a rel="nofollow" href="'.$t['SourceUrl'].'">'.$t['Source'].'</a></noindex></div>';
}

?>
</div>