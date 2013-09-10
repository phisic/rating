<?php

class WebApplication extends CWebApplication {

    /**
     * Merging yii framework and my class maps
     */
    protected function preinit() {
        Yii::$classMap += require(Yii::app()->basePath . '/config/classmap.php');
    }

    public function seoUrl($route, $string) {
        $string = strtolower(str_replace(array(' ', '&'), array('_', 'a-n-d'), $string));
        $string = trim($string, '_') . '.html';

        return $this->createUrl($route . '/' . $string);
    }

    public function decodeSeoUrl($url) {
        return str_replace(array('_', 'a-n-d', '.html'), array(' ', '&', ''), $url);
    }

    function rank($n, $precision = 2) {
        if ($n < 1000) {
            // Anything less than a million
            $n_format = number_format($n);
        }elseif($n < 1000000){
            $n_format = number_format($n / 1000, $precision) . 'K';
        } else if ($n < 1000000000) {
            // Anything less than a billion
            $n_format = number_format($n / 1000000, $precision) . 'M';
        } else {
            // At least a billion
            $n_format = number_format($n / 1000000000, $precision) . 'B';
        }
        $ex = explode('.',$n_format);
        if(isset($ex[1]) && substr($ex[1], 0, 2) == '00')
            return $ex[0].substr($ex[1], 2, 1);
            
        return $n_format;
    }

}