<?php

class StringParser {

    protected $buf = '';
    public $offset = 0;

    public function __construct($text = '') {
        $this->buf = $text;
    }

    public function get() {
        return $this->buf;
    }

    public function reset() {
        $this->offset = 0;
        return $this;
    }
    
    public function trim(){
        $this->buf = trim($this->buf, " \t\n");
        return $this;
    }

    public function between($a, $c) {
        $t1 = strpos($this->buf, $a, $this->offset);
        if ($t1 === false)
            return null;
        $start = $t1 + strlen($a);
        $t2 = strpos($this->buf, $c, $start);
        $this->offset = $t2 + strlen($c);
        return new StringParser(substr($this->buf, $start, $t2 - $start));
    }
    
    public function cut($a){
        $t1 = strpos($this->buf, $a, $this->offset);
        if ($t1 === false)
            return null;
        return new StringParser(substr($this->buf, $t1));
    }
    
    public function remove($text) {
        return new StringParser(str_replace($text, '', $this->buf));
    }
    
    public function removeTags(array $tags){
        $pattern = array();
        foreach ($tags as $tag){
            $pattern[] = '@<'.$tag.'[^>]*?>.*?</'.$tag.'>@si';                
        }
        return new StringParser(preg_replace($pattern, '', $this->buf), false);
    }

    public function stripTags($allow = '') {
        return new StringParser(strip_tags($this->buf, $allow), false);
    }

    public function url($url) {
        $this->buf = $this->getContent($url);
        $this->reset();
        return $this;
    }
    
    protected function getContent($url){
        $h = md5($url);
        $c = new CDbCriteria(array('select'=>'Content'));
        $c->addColumnCondition(array('Hash'=>$h));
        $c->addCondition('DateCreated > (NOW() - INTERVAL 3 MONTH)');
        if(!($cont = Yii::app()->db->getCommandBuilder()->createFindCommand('text_cache', $c)->queryScalar())){
            $cont = file_get_contents($url);
            Yii::app()->db->getCommandBuilder()->createInsertCommand('text_cache', array('Hash'=>md5($url), 'Url'=>$url,'Content'=>$cont, 'DateCreated'=>date('Y-m-d H:i:s')))->execute();
        }
        return $cont;
    }

}