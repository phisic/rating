<?php
foreach(getallheaders() as $k=>$v){
    echo "'".$k.': '.$v."',\n";
}exit;
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
    'Cookie: PREF=ID=3dfc1943f982490d:U=02eb6ec626e215b8:FF=0:LD=en:TM=1349154828:LM=1364804385:GM=1:S=l-HkQ8LgfcpnFh1P; NID=67=LHwMraf5zGL24BZB0bZFM5S1lqabTvSZ6HeoWNt1SpuNUdTyOe8G5YTuD2GHzDJf3stA_FASFzPMBZ5Tj0XRyFSYeN2XABjUqqwqBIZNzH5YVhaTf3BFxW70Id610CabJEnkqeU6IcOOlm91Z1nP0r3ZgqdbytsFcs-hmJUe-aMGtA-R06kDMYMmNpkZhHJQoARjPA7AED1JO7LXhzHf4QXWNm-s7zthEO-V1ek; SID=DQAAAO0AAACLjASE42d3LurfXzhArzfnPPvRnUvMce9fH5CPrK2axOYl_NiJdtoF26tb87NQp8LkDsTLjuvo_qSDnzcsmjpb5mkjQbUCga6o6yH-T-g5MfnX2N2qmJsVA_b9TiFF-5ceyKMUGwi8KchmBCNuCqbmVR9ZLwBZgYKrGiIcAzexb0ORlyplMLO9xG9S_J7LUeCBYls9og160LbI0U0MAU5iCS2oLxDDa3RDbFapm2syHxbV71xct8Q6wjD6fprRqwFqLeyvZqIZ3qwgNs_35GNc4tq3WAjQQP1uBx87gWTwvZnhvpWsex3etOfVo_StYxs; HSID=Awvf1aWIyyur8A5jh; SSID=AIOTe4TutZe6QCszo; APISID=PvdIwkOIQRFWcj6m/A7afp9jMYYzgj7wLY; SAPISID=xKd9Znq021z331GJ/AkKbtUuABiTSrxOue; SS=DQAAAO4AAADbg2ieOyWnu4V5rZhGgf4CnwYgrndYwTtzutSdbP7EwW6R3iNREmRNNr1NmQ5LEDWg52jdEHvLFLQv0UrTI5wYtiB9Y1Q8R9T8-3bmWK9bgXbhr99X9i2p8_mW4_D2AjzFxbdcUIDTYezBlMX1ZlqpmCAs8EP9jlzitkW6uCR55rGbVIN4WFKSFj742aGvz2bw65D2wyQq4TGC5a4DtZ94S62TpKDdfeiFZfC1oBBycY0EopK8a29cW1LfgZiiTkV_S69dtudgx6l512Grk9m0Q1tsaoq3xJgWZZwNH9OiHQpu20_j7ZlKKYDS8c7K-Ys; __utma=1.2009231670.1375893466.1375893466.1375893466.1; __utmz=1.1375893466.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)',
);

$url = 'https://www.google.com/search?client=ubuntu&channel=fs&q=php+get+response+headers&ie=utf-8&oe=utf-8';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);

$response = curl_exec($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);

$header = substr($response, 0, $header_size);
$body = substr($response, $header_size);

print_r($header);

$response = compatible_gzinflate($body);

echo $response;

