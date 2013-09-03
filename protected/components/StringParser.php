<?php

class StringParser {

    protected $buf = '';
    public $offset = 0;
    protected static $proxy = 0;
    protected static $cuse = array();
    protected static $cook = array();
    protected static $marker = 0;
    protected static $client = array();

    public function __construct($text = '') {
        $this->buf = $text;
        if (empty(self::$client))
            self::$client = include(Yii::app()->basePath . '/config/agent.php');
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

    protected function startGoogle() {
        $url = 'https://www.google.com';
        self::$cook[self::$marker] = array();
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, self::$client[self::$marker]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_HEADER, 1);

        $response = curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);

        $header = substr($response, 0, $size);
        $r = explode("\n", $header);
        foreach ($r as $h) {
            if (!empty($h) && strpos($h, 'Set-Cookie: ') !== false) {
                $cook = explode(';', substr($h, 12));
                self::$cook[self::$marker][] = $cook[0];
            }
        }
    }

    public function grab($url, $client = 0) {
        if (self::$marker == count(self::$client))
            self::$marker = 0;

        if (isset(self::$cuse[self::$marker]) && self::$cuse[self::$marker] == 100)
            self::$cuse[self::$marker] = 0;
        if (empty(self::$cuse[self::$marker])) {
            self::$cuse[self::$marker] = 0;
            //$this->startGoogle();
        }
        
        $c = self::$client[self::$marker];
        //if(!empty(self::$cook[self::$marker]))
        //    $c[] = 'Cookie: SSID=A3MdpZI9tlmWz0_Qy;SID=DQAAAK8AAAAzz6eSyFIhrcUP63XAqoSajRi-dZjUKd0LUOUVB8LPb_M7C1ejQSIrJ8ApSlpIQyEOoMswbKN8hEMkxV0s9M1G6dGR7sDpkh9drtQXhm5uImIIwtbi3xrMbscuPTIg5_8YJoyWhfY81vsgbQ0KJP1kMzZ9QYZaIPufKHiEg_VfdFqXRHbhOd33xD2yT1Gk5zWQVkVB-fxvEcz4H3uUwXLGHXIjQji4MXdknQ3x6k-Wbg;SAPISID=GpFm3wj_SQf3yY2Q/A5L2IbG16uqa_j7-a;S=gmail=pvBKq931kEj6kTrlQn0y4A;PREF=ID=098d336001b3ca59:U=ca6989646731181f:FF=0:LD=en:TM=1377801402:LM=1377804211:GM=1:S=Vf7XtfcylB3QFHcs;NID=67=qjxxx1vpwHEUa7aAeKJxGxA8KzM48cZTuzmWOaJukg8eWRbc7QRKAvC-Eo-2_gPglH79TcwLnAo51W56nDr70v4z94TRshhEqY6PWu1N-ZbxI0WYP3O5Htolf0Dw472wy5QNG9t63ZDqnlikHDQPS7mgzP4wiaUY3w;HSID=AjxSv3yWdUJHaQ9qh;GX=DQAAALIAAABUn8T5XEnZBoJTCL58AHXlX15TRsxJ-jX8avM8n9YQgCX4ywe-TDbHOXTbegYXy27wrWjbibPmcdd0iYG9NJoyP5zpNipcxOWjGiPV_j7uG85OaIP7lae9DvZYOhbLnHxOlP3b2FamaJwaQeboSDHLdZbA6IKbQ4vmbd360H51Ohg6-YEkXAv0c99TLb1g33I7yUKre-NN4_fU1hc_Nww4lieheLioq46ZuuM4BJodvV_Kol6cSNfpAQjIIElbmpw;GMAIL_AT=AF6bupPLtgRBOhlrExt69DEA-_V69Om8Og;GDSESS=ID=d4034c6b5ec63dae:TM=1377803891:C=c:IP=89.146.104.222-:S=APGng0tUo_t1hs5Xzyib3mhzyTKK32GX-A;BEAT=APfa0box2pG2TqrC6nh_Vdz1S2gAd31VMJGAOJrMfZBE2t52eruPx4AECo5mOBs5UjbByOY9KWtk;APISID=83j6lAiIjgj7YP8J/A9XueEH5tvFub4dnS;N_T=sess%3D55caa75ca279f155%26v%3D2%26c%3D6cb9996c%26s%3D521f9fba%26t%3DD%3A0%3A%7Cm%3A0%3A%26sessref%3Dhttps%253A%252F%252Fmail.google.com%252F_%252Fmail-static%252F_%252Fjs%252Fmain%252Fm_i%252Ct%252Cit%252Frt%253Dh%252Fver%253DW6qHKDTagR4.en.%252Fsv%253D1%252Fam%253D%2521v74bplXLnk_0RcHS2-1dRapnn_X8l5tcaS9vZGhkBaN1OGHV4JAdzoAcXpTBQrFvzIwkrfWktw%252Fd%253D1;';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $c);

        $response = curl_exec($ch);
        $response = $this->compatible_gzinflate($response);
        file_put_contents(self::$marker.'.txt', $response);
        curl_close($ch);
        self::$cuse[self::$marker]++;
        self::$marker++;
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