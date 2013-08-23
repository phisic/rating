<?php

class WebApplication extends CWebApplication {

    /**
     * Merging yii framework and my class maps
     */
    protected function preinit() {
        Yii::$classMap += require(Yii::app()->basePath . '/config/classmap.php');
    }

    public function seoUrl($route, $string) {
        $string = strtolower(str_replace(array(' ', '&'), array('_','a-n-d'), $string));
        $string = trim($string,'_');
        
        return $this->createUrl($route.'/'.$string);
    }
    
    public function decodeSeoUrl($url){
        return str_replace(array('_', 'a-n-d'), array(' ','&'), $url);
    }

}