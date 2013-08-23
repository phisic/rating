<?php
class Helper extends CApplicationComponent {
    public $categories = array();
    public $categoryPointer = array();
    
    public function init(){
        $p = array();
        $c = new CDbCriteria(array('order'=>'Level, Name'));
        foreach (Yii::app()->db->getCommandBuilder()->createFindCommand('category', $c)->queryAll() as $r){
            $id = $r['Id'];
            $pid = $r['PId'];
            if(isset($p[$pid])){
                $this->categoryPointer[$id] = &$this->categoryPointer[$pid]['sub'][];
                $this->categoryPointer[$id] = $r;
            }
            else{
                $this->categories[$r['Id']] = $r;
                $this->categoryPointer[$id] = &$this->categories[$r['Id']];
            }
        }
    }
    
    public function getChildCategories($category, &$childs){
        if(isset($category['sub'])){
            foreach ($category['sub'] as $ch){
                $childs[] = $ch['Id'];
                if(isset($ch['sub']))
                    $this->getChildCategories($ch, $childs);
            }
        }
    }
    
    public function mainMenu(){
        $c = new CDbCriteria(array('select'=>'Name'));
        $c->addColumnCondition(array('PId'=>0));
        $m = '<ul class="nav navbar-nav">';
        foreach ($this->categories as $i){
            $m .= '<li><a href="'.Yii::app()->seoUrl('category', $i['Name']).'">'.$i['Name'].'</a></li>';
        }
        $m .= '</ul>';
        return $m;
    }
    
    public function getShortRating($category = 0, $limit = 0){
        $c = new CDbCriteria(array('select'=>'Name,CategoryId,Id','order' => 'Weight Desc'));
        if(!empty($category))
            $c->addInCondition(array('CategoryId', (array)$category));
        if($limit)
            $c->limit = $limit;
        
        $rating = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
        $list = array();
        foreach ($rating as $r){
            $list['Rating'][$r['Id']] = $r;
        }
        $ratings = array_keys($list['Rating']);
        $list['Items'] = $this->getItems($ratings);
        $list['Growing'] = $this->getItemsGrowing($ratings);
        $list['Losing'] = $this->getItemsLosing($ratings);
        return $list;
    }
    
    public function getRating($categoryId){
        $c = new CDbCriteria(array('select'=>'Name,CategoryId,Id','order' => 'Weight Desc'));
        if(!empty($this->categoryPointer[$categoryId])){
            $categories[] = $categoryId;
            $this->getChildCategories($this->categoryPointer[$categoryId], $categories);
            $c->addInCondition('CategoryId', $categories);
            
            return Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
        }
    }
    
    public function getItems($ratingIds, $limit=10){
        $items = $union = array();
        
        $s = '(SELECT Id,ri.RatingId,Keyword,Rank,RankDelta,Image, "" as Description FROM item t ';
        $s .= 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid ';
        $s .= 'ORDER BY t.Rank DESC LIMIT '.$limit .')';
        foreach($ratingIds as $rId){
            $union[] = str_replace(':rid', $rId, $s);
        }
        $sql = join(' UNION ', $union);
        foreach (Yii::app()->db->getCommandBuilder()->createSqlCommand($sql)->queryAll() as $i){
            $items[$i['RatingId']][] = $i;
        }
        return $items;
    }
    
    public function getItemsGrowing($ratingIds, $limit=10){
        $items = $union = array();
        
        $s = '(SELECT Id,ri.RatingId,Keyword,Rank,RankDelta,Image, "" as Description FROM item t ';
        $s .= 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid ';
        $s .= 'WHERE t.RankDelta > 0 ORDER BY t.RankDelta DESC LIMIT '.$limit .')';
        foreach($ratingIds as $rId){
            $union[] = str_replace(':rid', $rId, $s);
        }
        $sql = join(' UNION ', $union);
        foreach (Yii::app()->db->getCommandBuilder()->createSqlCommand($sql)->queryAll() as $i){
            $items[$i['RatingId']][] = $i;
        }
        
        return $items;
    }
    
    
    public function getItemsLosing($ratingIds, $limit=10){
        $items = $union = array();
        
        $s = '(SELECT Id,ri.RatingId,Keyword,Rank,RankDelta,Image, "" as Description FROM item t ';
        $s .= 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid ';
        $s .= 'WHERE t.RankDelta < 0 ORDER BY t.RankDelta ASC LIMIT '.$limit .')';
        foreach($ratingIds as $rId){
            $union[] = str_replace(':rid', $rId, $s);
        }
        $sql = join(' UNION ', $union);
        foreach (Yii::app()->db->getCommandBuilder()->createSqlCommand($sql)->queryAll() as $i){
            $items[$i['RatingId']][] = $i;
        }
        
        return $items;
    }
    
    
}