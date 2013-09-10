<?php

class Helper extends CApplicationComponent {

    public $categories = array();
    public $categoryPointer = array();
    public $activeCategory = 0;

    public function init() {
        $p = array();
        $c = new CDbCriteria(array('order' => 'Level, Name'));
        foreach (Yii::app()->db->getCommandBuilder()->createFindCommand('category', $c)->queryAll() as $r) {
            $id = $r['Id'];
            $pid = $r['PId'];
            if (isset($p[$pid])) {
                $this->categoryPointer[$id] = &$this->categoryPointer[$pid]['sub'][];
                $this->categoryPointer[$id] = $r;
            } else {
                $this->categories[$r['Id']] = $r;
                $this->categoryPointer[$id] = &$this->categories[$r['Id']];
            }
        }
    }

    public function getChildCategories($category, &$childs) {
        if (isset($category['sub'])) {
            foreach ($category['sub'] as $ch) {
                $childs[] = $ch['Id'];
                if (isset($ch['sub']))
                    $this->getChildCategories($ch, $childs);
            }
        }
    }

    public function mainMenu() {
        $c = new CDbCriteria(array('select' => 'Name'));
        $c->addColumnCondition(array('PId' => 0));
        $categories = array_values($this->categories);
        $visible = 6;
        $m = '<ul class="nav navbar-nav">';
        for ($n = 0; $n < $visible; $n++) {
            $i = $categories[$n];
            $m .= '<li' . ($this->activeCategory == $i['Id'] ? ' class="active"' : '') . '><a href="' . Yii::app()->seoUrl('category', $i['Name']) . '">' . $i['Name'] . '</a></li>';
        }
        $cnt = count($categories);
        if ($cnt > $visible) {
            $m .= '<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Other <b class="caret"></b></a>';
            $m .= '<ul class="dropdown-menu">';
            for ($n = $visible; $n < $cnt; $n++) {
                $i = $categories[$n];
                $m .= '<li' . ($this->activeCategory == $i['Id'] ? ' class="active"' : '') . '><a href="' . Yii::app()->seoUrl('category', $i['Name']) . '">' . $i['Name'] . '</a></li>';
            }
            $m.='</ul>';
            $m .= '</li>';
        }
        $m .= '</ul>';
        return $m;
    }

    public function getShortRating($category = 0, $limit = 0, $offset = 0, $itemLimit = 5) {
        $c = new CDbCriteria(array('select' => 'Name,CategoryId,Id', 'order' => 'Weight Desc'));
        if (!empty($offset))
            $c->offset = $offset;
        if (!empty($category))
            $c->addInCondition(array('CategoryId', (array) $category));
        if ($limit)
            $c->limit = $limit;
        $rating = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
        $list = array();
        foreach ($rating as $r) {
            $c2 = new CDbCriteria();
            $c2->addColumnCondition(array('RatingId' => $r['Id']));
            $r['count'] = Yii::app()->db->getCommandBuilder()->createCountCommand('rating2item', $c2)->queryScalar();
            $list['Rating'][$r['Id']] = $r;
        }
        $ratings = array_keys($list['Rating']);
        $list['Items'] = $this->getRatingItems($ratings, $itemLimit);
        $list['Growing'] = $this->getItemsGrowing($ratings, $itemLimit);
        $list['Losing'] = $this->getItemsLosing($ratings, $itemLimit);
        return $list;
    }

    public function getRating($categoryId = 0, CDbCriteria $c = null) {
        if (!$c)
            $c = new CDbCriteria ();
        $c->select = 'Name,CategoryId,Id';
        $c->order = 'Weight Desc';

        if (!empty($this->categoryPointer[$categoryId])) {
            $categories[] = $categoryId;
            $this->getChildCategories($this->categoryPointer[$categoryId], $categories);
            $c->addInCondition('CategoryId', $categories);
        }

        return Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryAll();
    }

    public function getRatingCount($categoryId = 0) {
        $c = new CDbCriteria();
        if (!empty($this->categoryPointer[$categoryId])) {
            $categories[] = $categoryId;
            $this->getChildCategories($this->categoryPointer[$categoryId], $categories);
            $c->addInCondition('CategoryId', $categories);
        }
        return Yii::app()->db->getCommandBuilder()->createCountCommand('rating', $c)->queryScalar();
    }

    public function getRatingItems($ratingIds, $limit = 10) {
        $items = $union = array();
        $s = '(SELECT Id,ri.RatingId,Keyword,ri.Rank,ri.RankDelta,Image, "" as Description FROM item t ';
        $s .= 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid ';
        $s .= 'ORDER BY ri.Rank DESC LIMIT ' . $limit . ')';
        foreach ($ratingIds as $rId) {
            $union[] = str_replace(':rid', $rId, $s);
        }
        $sql = join(' UNION ', $union);
        foreach (Yii::app()->db->getCommandBuilder()->createSqlCommand($sql)->queryAll() as $i) {
            $items[$i['RatingId']][] = $i;
        }
        return $items;
    }

    public function getItems($ratingId, CDbCriteria $c) {
        $c->select = 't.Id,ri.RatingId,Keyword,ri.Rank,ri.RankDelta,Image, ri.RankDate';
        $c->join = 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid';
        $c->order = 'ri.Rank DESC';
        $c->params[':rid'] = $ratingId;
        $ids = $list = array();
        foreach (Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryAll() as $r) {
            $list[$r['Id']] = $r;
        }
        $c2 = new CDbCriteria(array('select' => 'ItemId,substr(Content,1,500) as Description'));
        $c2->addInCondition('ItemId', array_keys($list));
        foreach (Yii::app()->db->getCommandBuilder()->createFindCommand('item_text', $c2)->queryAll() as $t) {
            $list[$t['ItemId']]['Description'] = strip_tags($t['Description']);
        }

        return array_merge(array(), $list);
        ;
    }

    public function getItemCount($ratingId) {
        $c = new CDbCriteria();
        $c->join = 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid';
        $c->params[':rid'] = $ratingId;
        return Yii::app()->db->getCommandBuilder()->createCountCommand('item', $c)->queryScalar();
    }

    public function getItemsGrowing($ratingIds, $limit = 10) {
        $items = $union = array();

        $s = '(SELECT Id,ri.RatingId,Keyword,ri.Rank,ri.RankDelta,Image, "" as Description FROM item t ';
        $s .= 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid ';
        $s .= 'WHERE ri.RankDelta > 0 ORDER BY ri.RankDelta DESC LIMIT ' . $limit . ')';
        foreach ($ratingIds as $rId) {
            $union[] = str_replace(':rid', $rId, $s);
        }
        $sql = join(' UNION ', $union);
        foreach (Yii::app()->db->getCommandBuilder()->createSqlCommand($sql)->queryAll() as $i) {
            $items[$i['RatingId']][] = $i;
        }

        return $items;
    }

    public function getItemsLosing($ratingIds, $limit = 10) {
        $items = $union = array();

        $s = '(SELECT Id,ri.RatingId,Keyword,ri.Rank,ri.RankDelta,Image, "" as Description FROM item t ';
        $s .= 'JOIN rating2item ri ON ri.ItemId = t.Id and ri.RatingId=:rid ';
        $s .= 'WHERE ri.RankDelta < 0 ORDER BY ri.RankDelta ASC LIMIT ' . $limit . ')';
        foreach ($ratingIds as $rId) {
            $union[] = str_replace(':rid', $rId, $s);
        }
        $sql = join(' UNION ', $union);
        foreach (Yii::app()->db->getCommandBuilder()->createSqlCommand($sql)->queryAll() as $i) {
            $items[$i['RatingId']][] = $i;
        }

        return $items;
    }

}