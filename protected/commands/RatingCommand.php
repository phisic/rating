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
                if ($pos = strpos($row['Keyword'], ','))
                    $row['Keyword'] = substr($row['Keyword'], 0, $pos);
                if ($pos = strpos($row['Keyword'], ' '))
                    $row['Keyword'] = '"' . $row['Keyword'] . '"';
                $row['Keyword'] = $row['Context'] . ' ' . $row['Keyword'];
                $keyword = urlencode($row['Keyword']);

                $url[0] = 'https://www.google.com/search?client=ubuntu&channel=fs&q=' . $keyword . '&ie=utf-8&oe=utf-8';
                $url[1] = 'https://www.google.com/#fp=fef978c8887d2f8f&psj=1&q=' . $keyword;
                $url[2] = 'https://www.google.com/search?sclient=psy-ab&q=' . $keyword . '&oq=' . $keyword . '&gs_l=hp.3...46404.46404.12.65758.1.1.0.0.0.0.188.188.0j1.1.0....0...1c.1.25.psy-ab..19.4.791.DMko7MTGOMk&pbx=1&bav=on.2,or.r_qf.&bvm=bv.51156542%2Cd.bGE%2Cpv.xjs.s.en_US.iz6Z5q8RWbs.O&fp=34b4cd05192a2aa8&biw=1366&bih=342&tch=1&ech=1&psi=q2AeUpezLIqJ4ATNq4FQ.1377722542206.15';
                $client = rand(0,1);
                echo $row['Keyword'] . ' C='.$client."\n";
                
                $p = new StringParser($px->grab($url[2], $client));
                $p = $p->cut('About Google');
                if (empty($p)) {
                    sleep(10);
                    continue;
                }
                $p->offset+=15;
                $rank = $p->between('About ', ' results');
                sleep(rand(0, 3));
                if (!$rank)
                    continue;
                $rank = $rank->remove(',')->get();
                $rank = ceil($rank/1000);
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

}

//https://www.google.co.uz/search?sclient=psy-ab&q=Teachers+%22Jean+Harris%22&oq=Teachers+%22Jean+Harris%22&gs_l=hp.3...46404.46404.12.65758.1.1.0.0.0.0.188.188.0j1.1.0....0...1c.1.25.psy-ab..19.4.791.DMko7MTGOMk&pbx=1&bav=on.2,or.r_qf.&bvm=bv.51156542%2Cd.bGE%2Cpv.xjs.s.en_US.iz6Z5q8RWbs.O&fp=34b4cd05192a2aa8&biw=1366&bih=342&tch=1&ech=1&psi=q2AeUpezLIqJ4ATNq4FQ.1377722542206.15
//https://www.google.co.uz/s?gs_rn=25&gs_ri=psy-ab&pq=teachers%20%22%20jean%20harris%22&cp=10&gs_id=6x1&xhr=t&q=Teachers%20%22Jean%20Harris%22&es_nrs=true&pf=p&sclient=psy-ab&oq=&gs_l=&pbx=1&bav=on.2,or.r_qf.&bvm=bv.51156542,d.bGE&fp=34b4cd05192a2aa8&biw=1366&bih=342&bs=1&tch=1&ech=2&psi=q2AeUpezLIqJ4ATNq4FQ.1377722542206.13
//https://www.google.co.uz/search?sclient=psy-ab&q=Teachers+%22Jean+Harris%22+and&oq=Teachers+%22Jean+Harris%22+and&gs_l=hp.3..33i29i30l4.106386.107684.13.111377.4.3.1.0.0.0.288.685.0j1j2.3.0....0...1c.1.25.psy-ab..20.7.1465.47ky3tHrIGE&pbx=1&bav=on.2,or.r_qf.&bvm=bv.51156542%2Cd.bGE%2Cpv.xjs.s.en_US.iz6Z5q8RWbs.O&fp=34b4cd05192a2aa8&biw=1366&bih=342&tch=1&ech=1&psi=q2AeUpezLIqJ4ATNq4FQ.1377722542206.17