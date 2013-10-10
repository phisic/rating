<?php

class RatingCommand extends CConsoleCommand {

    public function actionGoogle() {
        $c = new CDbCriteria(array('select' => 'ri.ItemId,ri.RatingId,i.Keyword,ri.Rank,Context'));
        $c->join = 'JOIN rating2item ri ON ri.RatingId = t.Id JOIN item i ON i.Id = ri.ItemId';
        $c->addCondition('(RankDate < (now()-INTERVAL 3 DAY)) OR (RankDate Is NULL)');
        $c->order = 'RankDate';
        $c->limit = 100;
        $px = new StringParser();
        $client = 0;
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
            foreach ($r as $row) {
                $kpos = strpos($row['Keyword'], ' ');
                $cpos = strpos($row['Context'], ' ');
                if (!$kpos && !$cpos) {
                    $row['Keyword'] = urlencode($row['Keyword']);
                    $row['Context'] = urlencode($row['Context']);
                } else {
                    $row['Keyword'] = '"' . urlencode($row['Keyword']) . '"';
                    $row['Context'] = '"' . urlencode($row['Context']) . '"';
                }

                $keyword = $row['Context'] . ' ' . $row['Keyword'];

                if (!$kpos && !$cpos)
                    $keyword = '"' . $keyword . '"';

                $url[0] = 'https://www.google.com/search?client=ubuntu&channel=fs&q=' . $keyword . '&ie=utf-8&oe=utf-8';
                $url[1] = 'https://www.google.com/#fp=fef978c8887d2f8f&psj=1&q=' . $keyword;
                $url[2] = 'https://www.google.com/search?hl=en&output=search&sclient=psy-ab&q=' . $keyword . '&oq=' . $keyword . '&gs_l=hp.3..0l4.4119.4891.0.5556.3.3.0.0.0.0.316.882.2-1j2.3.0....0...1c.1.26.psy-ab..0.3.854.4RGm98ZZwO4&pbx=1&bav=on.2,or.r_qf.&bvm=bv.51495398%2Cd.bGE%2Cpv.xjs.s.en_US.M4-36_38X9A.O&fp=1eb900f563c207c1&biw=1366&bih=342&tch=1&ech=1&psi=ieMgUviBE-GQ4ASjz4CgAQ.1377887119049.3';
                $client = rand(0, 1);
                echo $keyword . ' C=' . $client . "\n";

                $p = new StringParser($px->grab($url[2], $client));
                $pm = $p->cut('About Google');
                if (empty($pm)) {
                    $rank = $p->between('About ', ' results');
                    if (empty($rank)) {
                        sleep(60);
                        continue;
                    }
                } else {
                    $pm->offset+=15;
                    $rank = $pm->between('About ', ' results');
                }
                //sleep(rand(0, 1));
                if (!$rank) {
                    echo $p->get();
                    continue;
                }
                $rank = $rank->remove(',')->get();
                $rank = ceil($rank / 1000);
                echo $rank . "\n";
                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('ItemId' => $row['ItemId'], 'RatingId' => $row['RatingId']));

                $delta = 0;
                if (!is_null($row['Rank']))
                    $delta = $rank - $row['Rank'];

                $date = date('Y-m-d H:i:s');
                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta' => $delta), $c2)->execute();
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

    public function actionBing() {
        $c = new CDbCriteria(array('select' => 'ri.ItemId,ri.RatingId,i.Key as Keyword,ri.Rank,Context'));
        $c->join = 'JOIN rating2item ri ON ri.RatingId = t.Id JOIN item i ON i.Id = ri.ItemId';
        $c->addCondition('((RankDate < (now()-INTERVAL 5 HOUR)) OR (RankDate Is NULL))');
        $c->order = 'RankDate';
        $c->limit = 100;
        $px = new StringParser();
        $client = 0;
        $pq = '';
        $date = date('Y-m-d H:i:s');
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
            foreach ($r as $row) {
                $delta = 0;
                $keyword = ($row['Context'] ? '"' . urlencode($row['Context']) . '"' . 'AND' : '') . '"' . urlencode($row['Keyword']) . '"';
                //$rank = file_get_contents('http://laptoptop7.com/t.php?q=' . $keyword);
                $rank = file_get_contents('http://rating/t.php?q=' . $keyword);
                
                echo $keyword.' = '.$rank . "\n";
                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('ItemId' => $row['ItemId'], 'RatingId' => $row['RatingId']));
                if (empty($rank)) {
                    if (empty($row['Rank']))
                        Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta' => $delta), $c2)->execute();
                    continue;
                }
                if (!is_null($row['Rank']))
                    $delta = $rank - $row['Rank'];

                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta' => $delta), $c2)->execute();
            }
        } while ($r);
        
        $this->actionPosition();
    }
    
    public function actionPosition(){
        Yii::app()->db->getCommandBuilder()->createSqlCommand('update rating r set weight= (select max(rank) from rating2item ri join item i on ri.itemid=i.id where ri.ratingid=r.id) limit 1000000')->execute();
        foreach (Yii::app()->db->getCommandBuilder()->createFindCommand('rating', new CDbCriteria())->queryAll() as $r){
            Yii::app()->db->getCommandBuilder()->createSqlCommand('set @rownum := 0;')->execute();
            Yii::app()->db->getCommandBuilder()->createSqlCommand('update rating2item ri set ri.Position = (SELECT @rownum := @rownum + 1) where  ri.RatingId='.$r['Id'].' order by ri.Rank desc;')->execute();
        }
    }

    public function actionAol() {
        $c = new CDbCriteria(array('select' => 'ri.ItemId,ri.RatingId,i.Keyword,ri.Rank,Context'));
        $c->join = 'JOIN rating2item ri ON ri.RatingId = t.Id JOIN item i ON i.Id = ri.ItemId';
        $c->addCondition('(RankDate < (now()-INTERVAL 5 HOUR)) OR (RankDate Is NULL)');
        $c->order = 'RankDate';
        $c->limit = 100;
        $px = new StringParser();
        $client = 0;
        $pq = '';
        $date = date('Y-m-d H:i:s');
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
            foreach ($r as $row) {
                $row['Keyword'] = '"' . ($row['Keyword']) . '"';
                $row['Context'] = '"' . ($row['Context']) . '"';

                $keyword = urlencode($row['Context']) . '+' . urlencode($row['Keyword']);

                if (empty($pq))
                    $pq = $keyword;

                $url = 'http://search.aol.com/aol/search?s_it=topsearchbox.search&v_t=comsearch51&q=' . $keyword;
                echo $url . "\n";
                $pq = $keyword;

                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('ItemId' => $row['ItemId'], 'RatingId' => $row['RatingId']));
                $p = new StringParser($px->grab($url, $client));
                $rank = $p->between('About&nbsp;', '&nbsp;results');
                if (empty($rank)) {
                    $delta = 0;
                    if (empty($row['Rank']))
                        Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta' => $delta), $c2)->execute();
                    continue;
                }
                $rank = $rank->remove(',')->get();
                echo $rank . "\n";


                if (!is_null($row['Rank']))
                    $delta = $rank - $row['Rank'];

                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta' => $delta), $c2)->execute();
            }
        } while ($r);
    }

    public function actionYa() {
        $c = new CDbCriteria(array('select' => 'ri.ItemId,ri.RatingId,i.Keyword,ri.Rank,Context'));
        $c->join = 'JOIN rating2item ri ON ri.RatingId = t.Id JOIN item i ON i.Id = ri.ItemId';
        $c->addCondition('(RankDate < (now()-INTERVAL 20 HOUR)) OR (RankDate Is NULL)');
        $c->order = 'RankDate';
        $c->limit = 100;
        $px = new StringParser();
        $client = 0;
        $pq = '';
        $date = date('Y-m-d H:i:s');
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
            foreach ($r as $row) {
                $row['Keyword'] = '"' . ($row['Keyword']) . '"';
                $row['Context'] = '"' . ($row['Context']) . '"';

                $keyword = urlencode($row['Context']) . '+' . urlencode($row['Keyword']);

                if (empty($pq))
                    $pq = $keyword;

                $url = 'http://yandex.ru/yandsearch?text=' . $keyword . '&lr=10329';
                echo $url . "\n";
                $pq = $keyword;

                $c2 = new CDbCriteria();
                $c2->addColumnCondition(array('ItemId' => $row['ItemId'], 'RatingId' => $row['RatingId']));
                $p = new StringParser($px->grab($url, $client));
                $rank = $p->between('Яндекс: нашлось ', ' ответ');
                if (empty($rank))
                    $rank = $p->between('Яндекс: нашлась ', ' ответ');
                if (empty($rank))
                    $rank = $p->between('Яндекс: нашёлся ', ' ответ');

                if (empty($rank)) {
                    $delta = 0;
                    if (empty($row['Rank']))
                        Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta' => $delta), $c2)->execute();
                    //sleep(20);
                    continue;
                }
                $ra = explode(' ', $rank->get());

                if (count($ra) == 1 && isset($ra[0]) && is_numeric($ra[0])) {
                    $rank = $ra[0];
                } else {
                    switch ($ra[1]) {
                        case 'млн': $rank = $ra[0] * 1E6;
                            break;
                        case 'тыс.': $rank = $ra[0] * 1E3;
                            break;
                    }
                    echo $ra[1];
                }
                echo $rank . "\n";


                if (!is_null($row['Rank']))
                    $delta = $rank - $row['Rank'];

                $r = Yii::app()->db->getCommandBuilder()->createUpdateCommand('rating2item', array('Rank' => $rank, 'RankDate' => $date, 'RankDelta' => $delta), $c2)->execute();
            }
        } while ($r);
    }

}
