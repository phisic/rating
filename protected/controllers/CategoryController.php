<?php

class CategoryController extends Controller {
    
    public function filters() {
        return array(
            array(
                'CHttpCacheFilter',
                'lastModified' => Yii::app()->db->createCommand("SELECT MAX(`RankDate`) FROM rating2item")->queryScalar(),
            ),
        );
    }
    
    public function actionIndex($category = 0) {
        if (!empty($category)) {
            $name = Yii::app()->decodeSeoUrl($category);
            $c = new CDbCriteria();
            $c->addColumnCondition(array('Name' => $name));
            $category = Yii::app()->db->getCommandBuilder()->createFindCommand('category', $c)->queryRow();
            $this->pageTitle = $category['Name'];
            $categoryId = $category['Id'];
        }  else {
            $categoryId = 0;
        }
        Yii::app()->helper->activeCategory = $categoryId;
        $count = Yii::app()->helper->getRatingCount($categoryId);
        $pager = new CPagination($count);
        $pager->pageSize = 40;
        $c2 = New CDbCriteria();
        $pager->applyLimit($c2);

        $ratings = Yii::app()->helper->getRating($categoryId, $c2);
        foreach ($ratings as &$r) {
            $r['i'] = Yii::app()->helper->getRatingItems(array($r['Id']), 3);
        }

        $this->render('index', array('ratings' => $ratings, 'pager' => $pager));
    }

}