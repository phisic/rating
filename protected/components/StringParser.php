<?php

class StringParser {

    protected $buf = '';
    public $offset = 0;
    protected static $proxy = 0;
    protected static $client = array(
        array(
            'Host: www.google.com',
            'Accept: */*',
            'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3',
            'Accept-Encoding: gzip,deflate',
            'Accept-Language: en-US,en;q=0.8',
            'Cookie: PREF=ID=c4c4285d83f832c6:U=6b262550f9a229a9:LD=en:TM=1362211581:LM=1372870960:GM=1:S=5OrGGZoI240JxD81; GDSESS=ID=654c1603fd6559fc:TM=1377751668:C=c:IP=89.146.104.222-:S=APGng0vWlPd3y-yKhu6ECr__DzsVlGN65w; NID=67=N9OYt-VNFigTFEyfX1OK3LIsOv423FxWSzmGWgMidsMXAK1cbyg3fR5x73LV1DNDnKdseL-iYEj3wb8oJIiMEaKV4TtX_kI5X7CYKIDkLhxIIGHElFJlypABDiFYul9LH6zXbxa9bQOpIfgTbGQvy5P4DJ2OGqwFRGbBdaY-ZSJeWbI7DyynS3Y; HSID=A8NAgJAqw9agGdNgq; APISID=P7iJ5FUlsWSzCn-j/Ais20q3wjKf8zOQZp; SID=DQAAANsAAACQDqoEc77cEmB2RM-prpC-Ahle5iWiANIYa33ilxFRESyIET9scvvH5bed6EIfhetHC4XlLL40DI21NHliafVvn6kSdcIOZbeUJ69xSX8P2zeahKTaSYieD6Or7Ac4WCSJWHyvtGiQ91QyaR4q7YkoXfKGBl9MZt2Qcy11GBto6irsehN8cdR2sCTp5lu8FdXNJEiXxxAIcroaihxjco8AuhaiTe8PvXSfJbmqUore2McG-H09n9K6ncPV8hjlSOVgwORRbQ6vf7lfMJe3PctC9zhu62jp8Igo2GTaR_iUrQ',
            'Referer: http://www.google.com/',
            'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.17 (KHTML, like Gecko) Ubuntu Chromium/24.0.1312.56 Chrome/24.0.1312.56 Safari/537.17',
            'X-Chrome-Variations: CMK1yQEIl7bJAQiYtskBCKS2yQEIp7bJAQi7tskBCNyDygE=',
        ),
        array(
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Encoding: gzip, deflate',
            'Accept-Language: en-US,en;q=0.5',
            'Connection: keep-alive',
            'Cookie: PREF=ID=2223fc803701a9c6:U=24c29306041da552:FF=0:LD=en:TM=1377712906:LM=1377756813:S=-bg4rPBcsIYnqFwb; NID=67=TAptg1VW9zkoIqenMaigDxN7RAl2NWdgQFPUHxqBS_3-dJIFtLkl_DOlphX8ikg4vbhOlKTMc4F86i52JQi2SmLMYJ9gZbvYblIkH2u0vQR7e6V03aC5tOAQlZZgjg8SH1pyrPrRsZf-FmlmNok8BEDyKeVAub_lO6-RSd1upyUWQCZAqBor6Q; GDSESS=ID=3e7edde6fc2c5d97:TM=1377756364:C=c:IP=89.146.104.222-:S=APGng0uNd8HFNSzn3T7izFbTg0g45grD-Q; S=account-recovery=P6iTi2aVDV8; SID=DQAAAKEAAADDlI_nT6Pn8XSKSxcYS_D7OjDQNdho8TPcm4CXi9B7mA9MZBoNORMpfqYyxmKTFwZBL-NcVvXGhTzT-O8ko6gEd3p64c-zG2A5Z7MGvk6fkHj5sDET9sgdMWWfYMHt32aiSApdok9n_Zmt62svHD58PwFAiIN32iYzqgU1p119g8ETVZea2FTBkc_0hYq-es71jfKrDrhe77a8vhY7oJWBs89TJW1piAdojr4zxYKf2A; HSID=ABgQ7jla8pdG-otZ3; SSID=A3gfWDyewNsL2qrua; APISID=52PlNameB0WwQqeZ/AbQBA0dtpwjRiPsep; SAPISID=xv4LTnf32FqsyHCo/AThi9jieKSyVSG3is',
            'Host: www.google.com',
            'Referer: https://www.google.com/search?biw=1366&bih=342&noj=1&sclient=psy-ab&q=PHP6&oq=PHP6&gs_l=serp.3...0.0.1.9088.0.0.0.0.0.0.0.0..0.0....0...1c..26.serp..0.0.0.cHxCzMBwQ7M',
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0',
        ),
        array(
            'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0',
            'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.5',
            'Accept-Encoding: deflate',
            'Host: www.google.com',
            //'Pragma: no-cache',
//'Cache-Control: no-cache',
            'Referer: https://www.google.com/'
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

    public function compatible_gzinflate($gzData) {

        if (substr($gzData, 0, 3) == "\x1f\x8b\x08") {
            $i = 10;
            $flg = ord(substr($gzData, 3, 1));
            if ($flg > 0) {
                if ($flg & 4) {
                    list($xlen) = unpack('v', substr($gzData, $i, 2));
                    $i = $i + 2 + $xlen;
                }
                if ($flg & 8)
                    $i = strpos($gzData, "\0", $i) + 1;
                if ($flg & 16)
                    $i = strpos($gzData, "\0", $i) + 1;
                if ($flg & 2)
                    $i = $i + 2;
            }
            return @gzinflate(substr($gzData, $i, -8));
        } else {
            return false;
        }
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

    public function grab($url, $client = 0) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::$client[$client]);
// $cook = 'PREF=ID=2223fc803701a9c6:U=24c29306041da552:FF=0:TM=1377712906:LM=1377719573:S=wZCm8AdfruxYZcOJ; NID=67=MSofsRpvzu38-1-WOMxZKT2ukoUceE21SLdzNhuKd1bTrxFG-SvO413Zx-7OD0InhLNGCkmKMDWXPDRL8ATM8o6JqJzGidvpGwhTgOIlAwboKxbDmrRNya6fSEaQj-xl; GDSESS=ID=2f3327048613e1ee:TM=1377719528:C=c:IP=89.146.104.222-:S=APGng0vARGksmDDSsiN9IA_ea6YrEPjF5w';
//curl_setopt($ch, CURLOPT_COOKIE, $cook);


        $response = curl_exec($ch);
        $response = $this->compatible_gzinflate($response);
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
        $client = rand(0, count(self::$client) - 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::$client[$client]);
        echo 'Proxy=' . $proxyName . ' Client=' . $client . "\n";

        curl_setopt($ch, CURLOPT_COOKIE, $proxy[$proxyName]['cook']);

        $response = curl_exec($ch);
        file_put_contents($proxyName, $response);
        curl_close($ch);
        return $response;
    }

}