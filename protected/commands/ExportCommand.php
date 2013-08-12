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
        $r = file_get_contents('http://www.google.com/trends/topcharts/category?ajax=1&cid=&date=201307&geo=US');
        $r = json_decode($r, true);
        $date = date('Y-m-d H:i:s');
        $newItems = 0;
        foreach ($r['data']['chartList'] as $c) {
            $name = 'Most Popular ' . $c['visibleName'];
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
                echo $i['title'] . "\n";
                if (empty($exist)) {
                    $data = array(
                        'Keyword' => $i['title'],
                        'Description' => '',
                        'Image' => isset($i['imageInfo']['url']) ? $i['imageInfo']['url'] : null,
                        'DateCreated' => $date,
                    );
                    Yii::app()->db->getCommandBuilder()->createInsertCommand('item', $data)->execute();
                    $iId = Yii::app()->db->getCommandBuilder()->getLastInsertID('item');
                    Yii::app()->db->getCommandBuilder()->createInsertCommand('rating2item', array('ItemId' => $iId, 'RatingId' => $rId))->execute();
                    $newItems++;
                }
            }
        }
        echo 'inserts=' . $newItems . "\n";
    }

    public function actionWiki() {
        $c = new CDbCriteria(array('select' => 't.Id,Keyword'));
        $c->join = 'LEFT JOIN wiki_log it ON t.Id = it.ItemId';
        $c->addCondition('it.DateCreated < (NOW() - Interval 7 Day) OR it.dateCreated IS NULL');
        $c->limit = 100;
        $p = new StringParser();
        do {
            $r = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryAll();
            foreach ($r as $i) {
                echo $i['Keyword'] . "\n";
                $u = 'http://en.wikipedia.org/w/api.php?action=opensearch&format=json&search=' . urlencode($i['Keyword']) . '&namespace=0&suggest=';
                $articles = json_decode($p->url($u)->get(), true);
                if (isset($articles[1])) {
                    foreach ($articles[1] as $wikiTitle) {
                        $p->reset();
                        echo $wikiTitle . "\n";
                        $data = array();
                        $wu = 'http://en.wikipedia.org/wiki/' . strtr($wikiTitle, array(' ' => '_', '?' => '%3F'));
                        echo $wu . "\n";
                        $content = $p->url($wu)
                                ->between(
                                '<div id="mw-content-text" lang="en" dir="ltr" class="mw-content-ltr">', '<div class="visualClear"></div>'
                        );
                        if (empty($content))
                            continue;

                        $data['Content'] = $content->removeTags(array('script', 'table', 'div', 'ul', 'sup'))
                                ->stripTags('<b>,<i>,<ul>,<li>,<p>,<h2>,<h3>')
                                ->remove(array('<h2>Notes</h2>', '<h2>External links</h2>', '[edit source | edit]'))
                                ->get();
                        $data['Title'] = $wikiTitle;
                        $data['Source'] = 'wikipedia.org';
                        $data['SourceUrl'] = $wu;
                        $data['ItemId'] = $i['Id'];
                        $data['DateCreated'] = date('Y-m-d H:i:s');
                        Yii::app()->db->getCommandBuilder()->createInsertCommand('item_text', $data)->execute();
                    }
                }
                Yii::app()->db->getCommandBuilder()->createSQLCommand('REPLACE INTO wiki_log values(' . $i['Id'] . ',"' . date('Y-m-d H:i:s') . '")')->execute();
            }
        } while ($r);
    }

    public function actionBio() {
        $p = new StringParser();
        $pmenu = $p->url('http://www.biography.com/people')->between('<span>Best Known For</span>', '<li class="has-submenu mainItem">');
        $u = array();

        while ($a = $pmenu->between('<li class="no-submenu"><a href="">', '</a></li>')) {
            $h = $a->between('"', '"')->get();
            $t = $a->between('>', '<')->get();
            $u[$h] = $t;
        }

        $pmenu->reset();

        while ($a = $pmenu->between('<li class="no-submenu"><span>', '</span>')) {
            $h = $a->between('"', '"')->get();
            $t = $a->between('>', '<')->get();
            $u[$h] = $t;
        }
        foreach ($u as $url => $name) {
            $page = 1;
            $url = 'http://www.biography.com' . $url . '/all?view=gallery&sort=last-name&page=';
            echo $name . "\n";
            $fetch = true;
            while (true) {
                $pc = $p->url($url . $page)->between('<ul class = "gallery-list">', '<!-- Center: End -->');
                $pager = $p->between('<li class="current">', '</li>');
                
                $items = array();
                
                while ($p2 = $pc->between('<h3 class="dot">', '</h3>')) {
                    $items[] = $p2->between('"', '"')->get();
                }

                if (empty($pager) || $pager->get() != $page)
                    break;
                
                foreach ($items as $iUrl) {
                    $name = $p->url($iUrl)->between('<span class="dot"  id="name-of-profile-title" >', '</span>')->get();
                    $img = $p->between('<div id="profile-photo-container">', '</div>')->between('"', '"')->get();
                    echo $name.'-'.$img."\n";
                }
                
                $page++;
            }
        }
    }

}