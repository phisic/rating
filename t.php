<?php

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
    'Cookie: ffPromotion_view_count=1; ffPromotion_start_time=1376114248285; ffPromotion_closed=true; clickstreamid=-1500956724331561030; sinvtype=comsearch51; s_vi=[CS]v1|28B81C55851D281A-60000132E0005F4B[CE]; s_pers=%20s_fid%3D5622CA8929913AEF-09DFEF74A082DD25%7C1441170123088%3B%20s_getnr%3D1378098123109-Repeat%7C1441170123109%3B%20s_nrgvo%3DRepeat%7C1441170123116%3B; UNAUTHID=1.cd43df93dc774cc9bd290e70af201a8c.ded8; s_guid="d6f065ae74ea4c668114fb59a5369a0f:100813"; MVT_TBP="c1|258|20130810|20130902|WR_IncAdFAdAtr_WbRs13:11"; VWCUKP300=L0/Q103538_21358_135_081913_2_082013_613142x613018x081913x1x1_613141x613017x081913x1x1; s_sess=%20s_cc%3Dtrue%3B%20s_sq%3D%3B; CUNAUTHID=1.cd43df93dc774cc9bd290e70af201a8c.ded8; MVT_TBV=c1|886; rs_timezone=18000000',
    'User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:19.0) Gecko/20100101 Firefox/19.0',
);

$url = 'http://search.aol.com/aol/search?s_it=topsearchbox.search&v_t=comsearch51&q=%22Animal%22+%22Cat%22';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
curl_setopt($ch, CURLOPT_HTTPHEADER, $h);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_HEADER, 1);

$response = curl_exec($ch);
curl_close($ch);echo $response;exit;
echo compatible_gzinflate($response);exit;
$r = explode("\n", $header);
foreach ($r as $h){
    if(!empty($h) && strpos($h, 'Set-Cookie: ') !== false){
        $cook = explode(';', substr($h, 12));
        $cook = $cook[0];
        echo $cook;
    }
}