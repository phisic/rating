<?php

class RatingController extends Controller {

    public function actionIndex($rating = 'all') {
        if($rating=='all')
            $this->forward('all');
        if (!empty($rating)) {
            $name = Yii::app()->decodeSeoUrl($rating);
            $c = new CDbCriteria();
            $c->addColumnCondition(array('Name' => $name));
            $rating = Yii::app()->db->getCommandBuilder()->createFindCommand('rating', $c)->queryRow();
            $this->pageTitle = $rating['Name'];
            $Id = $rating['Id'];
        }
        if(empty($rating))
            throw new CHttpException(404);
        Yii::app()->helper->activeCategory = $rating['CategoryId'];
        $rating['Category'] = Yii::app()->helper->categories[$rating['CategoryId']]['Name'];
        $count = Yii::app()->helper->getItemCount($Id);
        $pager = new CPagination($count);
        $pager->pageSize = 50;
        $c2 = New CDbCriteria();
        $pager->applyLimit($c2);

        $items = Yii::app()->helper->getItems($Id, $c2);
        $this->render('index', array('items' => $items,'rating'=>$rating, 'pager' => $pager));
    }
    
    public function actionAll(){
        $rating = Yii::app()->helper->getRating();
        $this->render('all', array('rating'=>$rating));
    }
}