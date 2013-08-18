<?php

class RatingCommand extends CConsoleCommand {

    public function actionGoogle() {
        $c = new CDbCriteria(array('select' => 'Id,Keyword'));
        $c->addCondition('RankDate < (now()-INTERVAL 3 DAY)');
        $c->order = 'RankDate';
        $c->limit = 100;
        do {echo 'Here!';
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryAll();
            foreach ($r as $row) {
                $out = '';
                if($pos = strpos($row['Keyword'], ','))
                   $row['Keyword'] = substr ($row['Keyword'], 0, $pos);
                
                $cmd = 'lynx -source google.com/search?q=\"' . urlencode($row['Keyword']) . '\"';
                exec($cmd, $out);
                usleep(200000);
                echo $row['Keyword'] . "\n";
                $p = new StringParser(join(' ', $out));
                $rank = $p->between('About', 'results')->remove(',')->get();
                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('Id' => $row['Id']));
                $date = date('Y-m-d H:i:s');
                $r = Yii::app()->db->getCommandBuilder()->createInsertCommand('item_history', array('ItemId' => $row['Id'], 'Rank' => (int) $rank, 'DateCreated' => $date))->execute();
                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('item', array('Rank' => (int) $rank, 'RankDate'=>$date), $c2)->execute();
            }
        } while ($r);
    }

}