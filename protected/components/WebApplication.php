<?php

class WebApplication extends CWebApplication {

    /**
     * Merging yii framework and my class maps
     */
    protected function preinit() {
        Yii::$classMap += require(Yii::app()->basePath . '/config/classmap.php');
    }

    public function createSeoUrl($route, $string) {
        if (strlen($string) > 30)
            $string = substr($string, 0, 30);
        $string = strtolower(preg_replace(array('/[^a-z0-9\- ]/i', '/[ \-]+/'), array('', '-'), $string));
        $string = trim($string,'-');
        $pos = strrpos($route, '/');
        $param = substr($route, $pos + 1);
        $route = substr($route, 0, $pos + 1);
        return $this->createUrl($route . ($string ? $string . '-':'') . $param);
    }

}