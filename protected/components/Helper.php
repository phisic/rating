<?php
class Helper extends CApplicationComponent {
    public function mainMenu(){
        $c = new CDbCriteria(array('select'=>'Name'));
        $c->addColumnCondition(array('PId'=>0));
        $m = '<ul class="nav">';
        foreach (Yii::app()->db->getCommandBuilder()->createFindCommand('category', $c)->queryAll() as $i){
            $m .= '<li><a href="'.Yii::app()->seoUrl('category', $i['Name']).'">'.$i['Name'].'</a></li>';
        }
        $m .= '</ul>';
        return $m;
    }
    
    public function shortRating(){
        $c = new CDbCriteria(array('select'=>'Name,CategoryId,Id','order' => 'Weight Desc', 'limit'=>20));
        $rating = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
        $list = array();
        foreach ($rating as $r){
            $r['Items'] = $this->getItems($r['Id']);
            $list[$r['Id']] = $r;
        }
        return $list;
    }
    
    public function getItems($ratingId, $limit=15){
        $c = new CDbCriteria(array('select'=>'Id,Keyword,Rank,RankDelta,Image','order' => 'Rank Desc', 'limit'=>$limit));
        $c->join = 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId='.$ratingId;
        $items = array();
        foreach (Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryAll() as $row){
            $items[$row['Id']] = $row;
        }
        $ids = array_keys($items);
        $c = new CDbCriteria(array('select'=>'left(Content, 200) as Description, ItemId'));
        $c->addInCondition('ItemId', $ids);
        foreach(Yii::app()->db->getCommandBuilder()->createFindCommand('item_text', $c)->queryAll() as $row){
            $items[$row['ItemId']]['Description'] = $row['Description'];
        }
        return $items;
    }
    
    
    
}