<?php

class SiteController extends Controller {

    /**
     * Declares class-based actions.
     */
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex() {
        $this->pageTitle = Yii::app()->name . ', find your ' . Yii::app()->params['category'];
        $count = Yii::app()->helper->getRatingCount(0);
        $pager = new CPagination($count);
        $pager->pageSize = 5;

        $list = Yii::app()->helper->getShortRating(0, $pager->getLimit(), $pager->getOffset());
        $this->render('index', array('list' => $list, 'pager' => $pager));
    }

    public function actionItem($keyword) {
        $name = Yii::app()->decodeSeoUrl($keyword);
        $c = new CDbCriteria();
        $c->addColumnCondition(array('Keyword' => $name));
        $i = Yii::app()->db->getCommandBuilder()->createFindCommand('item', $c)->queryRow();
        if (!empty($i)) {
            $this->pageTitle = $i['Keyword'];
        } else {
            throw new CHttpException(404);
        }
        echo 'hello~';
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact() {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                        "Reply-To: {$model->email}\r\n" .
                        "MIME-Version: 1.0\r\n" .
                        "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    public function actionAjaxLogin() {
        $model = new LoginForm;
        $result = array('success' => false);
        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            if ($model->validate() && $model->login())
                $result = array('success' => true, 'url' => Yii::app()->user->returnUrl);
        }

        echo CJSON::encode($result);
    }

    public function actionAjaxRegister() {
        $model = new RegistrationForm;
        $result = array('success' => false);
        // collect user input data
        if (isset($_POST['RegistrationForm'])) {
            $model->attributes = $_POST['RegistrationForm'];
            if ($model->validate() && $model->register())
                $result = array('success' => true, 'url' => Yii::app()->user->returnUrl);
        }

        echo CJSON::encode($result);
    }

    /**
     * Displays the login page
     */
    public function actionLogin($service) {

        $model = new LoginForm;

        if (isset($service)) {
            $authIdentity = Yii::app()->eauth->getIdentity($service);

            if ($authIdentity->authenticate()) {
                $model->username = $authIdentity->getAttribute('email');
                if ($model->login(true))
                    $this->redirect('/');
            }
            // Something went wrong, redirect to login page
            $this->redirect(array('/'));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

}