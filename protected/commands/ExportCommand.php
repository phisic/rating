<?php

class ExportCommand extends CConsoleCommand {

    protected $forbesUrl = 'http://forbes.com/profile/';

    public function actionBillionaires() {
        $list = json_decode(file_get_contents('../data/billionaires.json'));
        $ratingName = 'Most Famous Billionaires in the World';
        $ratingId = $this->createRating($ratingName);

        foreach ($list as $i) {
            $url = $this->forbesUrl . $i[2];
            $c = new CDbCriteria(array('select' => 'Id'));
            $c->addColumnCondition(array('Keyword' => $i[3]));
            $exist = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryRow();
            if (!$exist) {
                $parser = new StringParser('');
                $description = $parser->url($url)->between('<p id="bio">', '</p>')->stripTags()->remove(' [...] more')->get();
                $img = $parser->between('<div class="logoImg">', '</div>')->between('src="', '"')->get();
                Yii::app()->db->getCommandBuilder()->createInsertCommand('item', array(
                    'Keyword' => $i[3],
                    'Image' => $img,
                    'Attributes' => serialize(array('Worth' => $i[7], 'Age' => $i[6], 'Source' => $i[10], 'Country' => $i[9])),
                    'DateCreated' => $date,
                ))->execute();
                $itemId = Yii::app()->db->getCommandBuilder()->getLastInsertID('item');
            }else
                $itemId = $exist['Id'];
            Yii::app()->db->getCommandBuilder()->createSQLCommand('REPLACE INTO rating2item (ItemId,RatingId) values (' . $itemId . ',' . $ratingId . ')')->execute();
        }
    }

    public function actionGoogleTrend() {
        $date = new DateTime('2004-01-01');
        $today = date('Ym');
        while (($start = $date->format('Ym')) != $today) {
            echo 'Date=' . $start . "\n";
            $r = file_get_contents('http://www.google.com/trends/topcharts/category?ajax=1&cid=&date=' . $start . '&geo=US');
            $r = json_decode($r, true);
            $newItems = 0;
            foreach ($r['data']['chartList'] as $c) {
                $name = 'Most Popular ' . $c['visibleName'];
                $rId = $this->createRating($name);

                foreach ($c['entityList'] as $i) {
                    $this->createItem($i['title'], isset($i['imageInfo']['url']) ? $i['imageInfo']['url'] : null, $rId);
                    $newItems++;
                }
            }
            $date->add(new DateInterval('P1M'));
            echo 'inserts=' . $newItems . "\n";
        }
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
        $date = date('Y-m-d H:i:s');
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
        }print_r($u);
        foreach ($u as $url => $categoryName) {
            $ratingId = $this->createRating('Most Famous ' . $categoryName);
            $page = 1;
            $url = 'http://www.biography.com' . $url . '/all?view=gallery&sort=last-name&page=';
            echo $categoryName . "\n";
            
            $fetch = true;
            while (true) {
                $pc = $p->url($url . $page)->between('<ul class = "gallery-list">', '<!-- Center: End -->');
                $pager = $p->between('<li class="current">', '</li>');
                $items = array();

                if (empty($pc)) {
                    while ($pInner = $p->reset()->between('<div class="center-second-column-content directory-name-listing clearfix">', '<div class="pagination">')) {
                        $pInner = $pInner->removeTags(array('div'))->between('<a href="', '" class="dot">');
                        if (empty($pInner))
                            continue;
                        $pc = $p->url('http://www.biography.com' . $pInner->get() . '/all')->between('<ul class = "gallery-list">', '<!-- Center: End -->');
                        while ($p2 = $pc->between('<h3 class="dot">', '</h3>')) {
                            $items[] = $p2->between('"', '"')->get();
                        }
                    }
                } else {
                    while ($p2 = $pc->between('<h3 class="dot">', '</h3>')) {
                        $items[] = $p2->between('"', '"')->get();
                    }
                }

                foreach ($items as $iUrl) {
                    $iUrl = 'http://www.biography.com' . str_replace('/people', '/print/profile', $iUrl);
                    echo $iUrl . "\n";
                    $name = $p->url($iUrl)->between('<span class="dot">', '</span>')->get();
                    $image = $p->reset()->between('<img src="', '"/>')->get();
                    echo "\t" . $name . "\n";
                    $iId = $this->createItem($name, $image, $ratingId);
                    $source = 'biography.com';
                    if (!$this->contentExist($source, $iId)) {
                        $p = $p->reset()->cut('<div class="main-content clearfix">');
                        $cont = $p->between('<p>', '</p>');
                        if (empty($cont))
                            $cont = $p->between('</h3>', '<h3>');
                        if ($cont) {
                            $data['Content'] = $cont->trim()->get();
                            $data['Source'] = $source;
                            $data['SourceUrl'] = $iUrl;
                            $data['ItemId'] = $iId;
                            $data['DateCreated'] = $date;
                            Yii::app()->db->getCommandBuilder()->createInsertCommand('item_text', $data)->execute();
                        } else {
                            echo 'NO CONTENT' . "\n";
                        }
                    }
                }

                if (empty($pager) || $pager->get() != $page)
                    break;
                $page++;
            }
        }
    }

    protected function createRating($name) {
        $date = date('Y-m-d H:i:s');
        $c1 = new CDbCriteria(array('select' => 'Id'));
        $c1->addColumnCondition(array('Name' => $name));

        $rexist = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c1)->queryRow();
        if (empty($rexist)) {
            Yii::app()->db->getCommandBuilder()->createInsertCommand('rating', array(
                'Name' => $name,
                'CategoryId' => 1,
                'DateCreated' => $date
            ))->execute();
            return Yii::app()->db->getCommandBuilder()->getLastInsertID('rating');
        }
        else
            return $rexist['Id'];
    }

    protected function createItem($keyword, $image, $ratingId = false, $attributes = null) {
        $date = date('Y-m-d H:i:s');
        $c2 = new CDbCriteria(array('select' => 'Id'));
        $c2->addColumnCondition(array('Keyword' => $keyword));
        $exist = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c2)->queryRow();
        if (empty($exist)) {
            $data = array(
                'Keyword' => $keyword,
                'Description' => '',
                'Image' => $image,
                'DateCreated' => $date,
                'Attributes' => $attributes,
            );
            Yii::app()->db->getCommandBuilder()->createInsertCommand('item', $data)->execute();

            $iId = Yii::app()->db->getCommandBuilder()->getLastInsertID('item');
        }
        else
            $iId = $exist['Id'];

        Yii::app()->db->getCommandBuilder()->createSQLCommand('replace into rating2item (ItemId,RatingId) values (' . $iId . ',' . $ratingId . ')')->execute();
        return $iId;
    }

    protected function contentExist($source, $itemId) {
        $c2 = new CDbCriteria(array('select' => 'Id'));
        $c2->addColumnCondition(array('Source' => $source, 'ItemId' => $itemId));
        $exist = Yii::app()->db->getCommandBuilder()->createFindCommand('item_text', $c2)->queryRow();
        return !empty($exist);
    }

}