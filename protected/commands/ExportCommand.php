<?php

class ExportCommand extends CConsoleCommand {

    protected $forbesUrl = 'http://forbes.com/profile/';

    public function actionBillionaires() {
        $list = json_decode(file_get_contents('../data/billionaires.json'));
        $ratingName = 'Most Famous Billionaires in the World';
        $date = date('Y-m-d H:i:s');
        $c = new CDbCriteria(array('select' => 'Id'));
        $c->addColumnCondition(array('Name' => $ratingName));
        $row = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryRow();
        if (empty($row)) {
            Yii::app()->db->getCommandBuilder()->createInsertCommand('rating', array(
                'Name' => $ratingName,
                'CategoryId' => 1,
                'DateCreated' => $date
            ))->execute();
            $ratingId = Yii::app()->db->getCommandBuilder()->getLastInsertID('rating');
        } else {
            $ratingId = $row['Id'];
        }

        foreach ($list as $i) {
            $url = $this->forbesUrl . $i[2];

            $c = new CDbCriteria(array('select' => 'Id'));
            $c->addColumnCondition(array('Keyword' => $i[3]));
            $exist = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryRow();
            if ($exist)
                continue;

            $parser = new StringParser('');
            $description = $parser->url($url)->between('<p id="bio">', '</p>')->stripTags()->remove(' [...] more')->get();
            $img = $parser->between('<div class="logoImg">', '</div>')->between('src="', '"')->get();

            Yii::app()->db->getCommandBuilder()->createInsertCommand('item', array(
                'Keyword' => $i[3],
                'Description' => $description,
                'Image' => $img,
                'Attributes' => serialize(array('Worth' => $i[7], 'Age' => $i[6], 'Source' => $i[10], 'Country' => $i[9])),
                'DateCreated' => $date,
            ))->execute();
            $itemId = Yii::app()->db->getCommandBuilder()->getLastInsertID('item');
            Yii::app()->db->getCommandBuilder()->createInsertCommand('rating2item', array(
                'RatingId' => $ratingId,
                'ItemId' => $itemId,
            ))->execute();
        }
    }

    public function actionGoogleTrend() {
        $r = file_get_contents('../data/category');
        $r = json_decode($r, true);
        $date = date('Y-m-d H:i:s');
        foreach ($r['data']['chartList'] as $c) {
            $name = 'Most Popular '.$c['visibleName'];
            $c1 = new CDbCriteria(array('select' => 'Id'));
            $c1->addColumnCondition(array('Name' => $name));

            $rexist = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c1)->queryRow();
            if (empty($rexist)) {
                Yii::app()->db->getCommandBuilder()->createInsertCommand('rating', array(
                    'Name' => $name,
                    'CategoryId' => 1,
                    'DateCreated' => $date
                ))->execute();
                $rId = Yii::app()->db->getCommandBuilder()->getLastInsertID('rating');
            }
            else
                $rId = $rexist['Id'];

            foreach ($c['entityList'] as $i) {
                $c2 = new CDbCriteria(array('select' => 'Id'));
                $c2->addColumnCondition(array('Keyword' => $i['title']));
                $exist = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c2)->queryRow();
                echo $i['title']."\n";
                if (empty($exist)) {
                    $data = array(
                        'Keyword' => $i['title'],
                        'Description' => '',
                        'Image' => isset($i['imageInfo']['url']) ? $i['imageInfo']['url'] : null,
                        'DateCreated' => $date,
                    );
                    Yii::app()->db->getCommandBuilder()->createInsertCommand('item', $data)->execute();
                    $iId = Yii::app()->db->getCommandBuilder()->getLastInsertID('item');
                    Yii::app()->db->getCommandBuilder()->createInsertCommand('rating2item', array('ItemId'=>$iId,'RatingId'=>$rId))->execute();
                }
            }
        }
    }

}