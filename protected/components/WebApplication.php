<?php

class WebApplication extends CWebApplication {

    /**
     * Merging yii framework and my class maps
     */
    protected function preinit() {
        Yii::$classMap += require(Yii::app()->basePath . '/config/classmap.php');
    }

    public function seoUrl($route, $string) {
        $string = strtolower(preg_replace(array('/[^a-z0-9\- ]/i', '/[ \-]+/'), array('', '_'), $string));
        $string = trim($string,'_');
        
        return $this->createUrl($route.'/'.$string);
    }

}