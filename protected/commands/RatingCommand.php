<?php

class RatingCommand extends CConsoleCommand {

    public function actionGoogle() {
        $c = new CDbCriteria(array('select' => 'Id,Keyword'));
        $c->addCondition('(RankDate < (now()-INTERVAL 3 DAY)) OR (RankDate Is NULL)');
        $c->order = 'RankDate';
        $c->limit = 100;
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryAll();
            foreach ($r as $row) {
                $out = '';
                if($pos = strpos($row['Keyword'], ','))
                   $row['Keyword'] = substr ($row['Keyword'], 0, $pos);
                $url = '\'http://search.aol.com/aol/search?s_it=topsearchbox.search&v_t=comsearch51&q=%22'.urlencode($row['Keyword']).'%22\'';
                $cmd = 'lynx -source '.$url;
                exec($cmd, $out);
                //usleep(500000);
                echo $row['Keyword'] . "\n";
                $out = join(' ', $out);
                file_put_contents('index.html', $out);
                $p = new StringParser($out);
                $rank = $p->between('About&nbsp;', '&nbsp;results');
                if(!$rank)
                    continue;
                $rank = $rank->remove(',')->get();
                echo $rank."\n";
                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('Id' => $row['Id']));
                $date = date('Y-m-d H:i:s');
                $r = Yii::app()->db->getCommandBuilder()->createInsertCommand('item_history', array('ItemId' => $row['Id'], 'Rank' => $rank, 'DateCreated' => $date))->execute();
                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('item', array('Rank' => $rank, 'RankDate'=>$date), $c2)->execute();
            }
        } while ($r);
    }

}