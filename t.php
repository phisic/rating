<?php

$keyword = $_GET['q'];

function compatible_gzinflate($gzData) {

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

$h = array(
    'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Accept-Encoding: gzip, deflate',
        'Accept-Language: en-US,en;q=0.5',
        'Connection: keep-alive',
        'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0',
        'Cookie: _SS=C=22.0&SID=C8A72D3D8AFF483898B1DD8B1785D153&bIm=426&nhIm=34-;DUP=Q=b-MV8MiBMzH1edEyx7-r&T=179085863&IG=68772954e61d422da67f4b2a87fe7613&V=1&A=2;',
);

$url = 'http://www.bing.com/search?q=' . urlencode($keyword);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec($ch);
curl_close($ch);
$response = compatible_gzinflate($response);
$c = ' results';
$a = '<span class="sb_count" id="count">';
$t1 = strpos($response, $a);
if ($t1 === false)
    exit();

$start = $t1 + strlen($a);
$t2 = strpos($response, $c, $start);
if($t2){
    echo str_replace(',','',substr($response, $start, $t2 - $start));
}