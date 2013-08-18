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
    
    public function shortRating($category){
        $c = new CDbCriteria(array('select'=>'Name,CategoryId,Id','order' => 'Weight Desc'));
        $rating = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
        foreach ($rating as $r){
            
        }
    }
    
    public function getItems($limit=5){
        $c = new CDbCriteria(array('select'=>'Name,CategoryId,Id','order' => 'Weight Desc'));
        Yii::app()->db->getCommandBuilder()->createFindCommand('Item', $c)->queryAll();
    }
}