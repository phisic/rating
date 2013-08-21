<?php

class CategoryController extends Controller {

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex($category) {
        $name = Yii::app()->decodeSeoUrl($category);
        $c = new CDbCriteria();
        $c->addColumnCondition(array('Name'=>$name));
        $category = Yii::app()->db->getCommandBuilder()->createFindCommand('category', $c)->queryRow();
        $this->pageTitle = $category['Name'];
        
        $this->render('index');
    }
}