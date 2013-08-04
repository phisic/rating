<?php

class ExportCommand extends CConsoleCommand {

    protected $forbesUrl = 'http://forbes.com/profile/';

    public function actionBillionaires() {
        $list = json_decode(file_get_contents('../data/billionaires.json'));
        $ratingName = 'Most Famous Billionaires in the World';
        $c = new CDbCriteria(array('select' => 'Id'));
        $c->addColumnCondition(array('Name' => $ratingName));
        $row = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryRow();
        if (empty($row)) {
            Yii::app()->db->getCommandBuilder()->createInsertCommand('rating', array(
                'Name' => $ratingName,
                'CategoryId' => 1,
                'C1' => 'Amount',
                'C2' => 'Age',
                'C3' => 'Source',
                'C4' => 'Country',
                'DateCreated'=> date('Y-m-d H:i:s')
            ))->execute();
            $rId = Yii::app()->db->getCommandBuilder()->getLastInsertID('rating');
        } else {
            $rId = $row['Id'];
        }
        
        foreach ($list as $i) {
            $url = $this->forbesUrl . $i[2];
            
            $c = new CDbCriteria(array('select'=>'Id'));
            $c->addColumnCondition(array('Keyword'=>$i[3], 'RatingId'=>$rId));
            $exist = Yii::app()->db->getCommandBuilder()->createFindCommand('rating_item', $c)->queryRow();
            if($exist)
                continue;
            
            $parser = new StringParser($url);
            $description = $parser->between('<p id="bio">', '</p>')->stripTags()->remove(' [...] more')->get();
            $img = $parser->between('<div class="logoImg">','</div>')->between('src="','"')->get();
            
            Yii::app()->db->getCommandBuilder()->createInsertCommand('rating_item', array(
                'RatingId' => $rId,
                'Keyword' => $i[3],
                'Description' => $description,
                'Image'=>$img,
                'C1' => $i[7],
                'C2' => $i[6],
                'C3' => $i[10],
                'C4' => $i[9]
            ))->execute();
        }
    }

}