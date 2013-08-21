<?php

class CategoryController extends Controller {

    public function actionIndex($category) {
        $name = Yii::app()->decodeSeoUrl($category);
        $c = new CDbCriteria();
        $c->addColumnCondition(array('Name' => $name));
        $category = Yii::app()->db->getCommandBuilder()->createFindCommand('category', $c)->queryRow();
        $this->pageTitle = $category['Name'];
        $ratings = Yii::app()->helper->getRating($category['Id']);
        
        $this->render('index', array('ratings' => $ratings));
    }

}