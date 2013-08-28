<?php

class StringParser {

    protected $buf = '';
    public $offset = 0;
    protected static $proxy = 0;
    protected static $client = array(
        array(
            'Connection: keep-alive',
            'Cache-Control: max-age=0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.17 (KHTML, like Gecko) Ubuntu Chromium/24.0.1312.56 Chrome/24.0.1312.56 Safari/537.17',
            'Accept-Encoding: deflate,sdch',
            'Accept-Language: en-US,en;q=0.8',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3',
        ),
        array(
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Accept-Encoding: deflate',
            'Connection: keep-alive',
            'Pragma: no-cache',
            'Cache-Control: no-cache',
        ),
    );

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

    public function trim() {
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

    public function cut($a) {
        $t1 = strpos($this->buf, $a, $this->offset);
        if ($t1 === false)
            return null;
        return new StringParser(substr($this->buf, $t1));
    }

    public function remove($text) {
        return new StringParser(str_replace($text, '', $this->buf));
    }

    public function removeTags(array $tags) {
        $pattern = array();
        foreach ($tags as $tag) {
            $pattern[] = '@<' . $tag . '[^>]*?>.*?</' . $tag . '>@si';
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

    protected function getContent($url) {
        $h = md5($url);
        $c = new CDbCriteria(array('select' => 'Content'));
        $c->addColumnCondition(array('Hash' => $h));
        $c->addCondition('DateCreated > (NOW() - INTERVAL 3 MONTH)');
        if (!($cont = Yii::app()->db->getCommandBuilder()->createFindCommand('text_cache', $c)->queryScalar())) {
            $cont = file_get_contents($url);
            Yii::app()->db->getCommandBuilder()->createInsertCommand('text_cache', array('Hash' => md5($url), 'Url' => $url, 'Content' => $cont, 'DateCreated' => date('Y-m-d H:i:s')))->execute();
        }
        return $cont;
    }

    public function grab($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:20.0) Gecko/20100101 Firefox/20.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Accept-Encoding: deflate',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'Connection: Close'
        ));

        $response = curl_exec($ch);
        file_put_contents('1.txt', $response);
        curl_close($ch);
        return $response;
    }

    public function proxy($q) {
        $proxy = include(Yii::app()->basePath . '/config/proxy.php');
        $k = array_keys($proxy);
        if (self::$proxy == count($k)) {
            self::$proxy = 0;
        }
        $proxyName = $k[self::$proxy];
        self::$proxy++;
        
        $url = str_replace('{$q}', $q, $proxy[$proxyName]['url']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        $client = rand(0, count(self::$client)-1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::$client[$client]);
        echo 'Proxy=' . $proxyName .' Client='.$client. "\n";

        curl_setopt($ch, CURLOPT_COOKIE, $proxy[$proxyName]['cook']);

        $response = curl_exec($ch);
        file_put_contents($proxyName, $response);
        curl_close($ch);
        return $response;
    }

}