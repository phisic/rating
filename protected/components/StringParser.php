<?php

class StringParser {

    protected $buf = '';

    public function __construct($text, $isUrl = true) {
        if ($isUrl && (substr($text, 0, 7) == 'http://'))
            $this->buf = $this->getUrl($text);
        else
            $this->buf = $text;
    }

    public function get() {
        return $this->buf;
    }

    public function between($a, $c) {
        $t1 = strpos($this->buf, $a);
        if ($t1 === false)
            return '';
        $start = $t1+strlen($a);
        $t2 = strpos($this->buf, $c, $start);
        
        return new StringParser(substr($this->buf, $start, $t2 - $start), false);
    }
    
    public function remove($text){
        return  new StringParser(str_replace($text, '', $this->buf), false);
    }
    
    public function stripTags($allow=''){
        return  new StringParser(strip_tags($this->buf, $allow), false);
    }

    public function getUrl($url) {
        return file_get_contents($url);
    }

}