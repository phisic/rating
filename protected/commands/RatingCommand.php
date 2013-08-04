<?php

class RatingCommand extends CConsoleCommand {

    public function actionGoogle() {
        $c = new CDbCriteria(array('select' => 'Id,Keyword'));
        $c->addCondition('Rating is null');
        $c->limit = 100;
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('rating_item', $c)->queryAll();
            foreach ($r as $row) {
                $out = '';
                if($pos = strpos($row['Keyword'], ','))
                   $row['Keyword'] = substr ($row['Keyword'], 0, $pos);
                
                $cmd = 'lynx -source google.com/search?q=\"' . urlencode($row['Keyword']) . '\"';
                exec($cmd, $out);
                echo $row['Keyword'] . "\n";
                $p = new StringParser(join(' ', $out));
                $rating = $p->between('About', 'results')->remove(',')->get();
                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('Id' => $row['Id']));
                $r = Yii::app()->db->getCommandBuilder()->createInsertCommand('rating_item_history', array('RatingId' => $row['Id'], 'Rating' => (int) $rating, 'Date' => date('Y-m-d H:i:s')))->execute();
                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating_item', array('Rating' => (int) $rating), $c2)->execute();
            }
        } while ($r);
    }

}