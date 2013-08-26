<?php

class RatingCommand extends CConsoleCommand {

    public function actionGoogle() {
        $c = new CDbCriteria(array('select' => 'ri.ItemId,ri.RatingId,i.Keyword,ri.Rank,Context'));
        $c->join = 'JOIN rating2item ri ON ri.RatingId = t.Id JOIN item i ON i.Id = ri.ItemId';
        $c->addCondition('(RankDate < (now()-INTERVAL 3 DAY)) OR (RankDate Is NULL)');
        $c->order = 'RankDate';
        $c->limit = 100;
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
            foreach ($r as $row) {
                $out = '';
                if ($pos = strpos($row['Keyword'], ','))
                    $row['Keyword'] = substr($row['Keyword'], 0, $pos);
                $url = 'http://search.aol.com/aol/search?s_it=topsearchbox.search&v_t=comsearch51&q=' . urlencode($row['Keyword']);
                $cmd = 'lynx -source ' . $url; echo $cmd."\n";
                exec($cmd, $out);
                //usleep(500000);
                echo $row['Keyword'] . "\n";
                $out = join(' ', $out);
                file_put_contents('index.html', $out);
                $p = new StringParser($out);
                $rank = $p->between('About&nbsp;', '&nbsp;results');
                exit;
                if (!$rank)
                    continue;
                $rank = $rank->remove(',')->get();
                echo $rank . "\n";
                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('ItemId' => $row['ItemId'], 'RatingId'=>$row['RatingId']));
                
                $delta = 0;
                if(!is_null($row['Rank']))
                    $delta = $rank - $row['Rank'];
               
                $date = date('Y-m-d H:i:s');
                $r = Yii::app()->db->getCommandBuilder()->createInsertCommand('item_history', array('ItemId' => $row['ItemId'],'RatingId'=>$row['RatingId'], 'Rank' => $rank, 'DateCreated' => $date))->execute();
                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta'=>$delta), $c2)->execute();
            }
        } while ($r);
    }

    public function actionImage() {
        list($width, $height) = getimagesize($filename);
        $new_width = $width * $percent;
        $new_height = $height * $percent;


        $image_p = imagecreatetruecolor($new_width, $new_height);
        $image = imagecreatefromjpeg($filename);
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        imagejpeg($image_p, null, 100);
    }

}