<?php
    // 粤tv（原触电新闻）php代码
    $pk = $_GET['pk'];
    $ts = round(microtime(true) * 1000);
    
/*
广东卫视4K,2567
广东卫视,1182
广东珠江,1183
广东体育,1184
广东民生,1185
广东新闻,1186
嘉佳卡通,1187
大湾区卫视（海外版）,1191
大湾区卫视,1197
广东4K超高清,1198
广东影视,1199
广东少儿,1200
广东移动,2463
深圳卫视,1206
广州综合,1175
广州新闻,1176
广州法治,2531
潮州新闻综合,1224
湛江新闻综合,1225
中山综合,1239
茂名综合,1247
江门综合,1248
揭阳综合,1254
汕尾新闻综合,1261
韶关新闻综合,2386
云浮综合,2389
东莞新闻综合,2395
清远新闻综合,2400
梅州综合,2401
河源综合,2402
惠东综合频道,2404
开平综合,2405
开平生活,2406
广宁综合,2414
化州综合,2440
鹤山综合,2441
廉江台,2445
英德新闻综合频道,2447
普宁台,2450
紫金台,2452
连州综合,2455
阳春综合,2458
怀集综合,2462
惠阳电视台,2470
信宜公共,2472
徐闻台,2474
阳西综合,2476
台山台,2479
新会综合,2490
罗定综合,2491
东源频道,2497
吴川综合,2499
乐昌电视台,2503
潮安综合,2523

四会电台,2410
廉江电台,2411
海丰县广播电视台,2413
广宁电台,2430
工布江达县广播电视台,2439
鹤山电台,2442
惠东综合广播,2443
英德电台,2446
化州电台,2449
连州电台,2457
阳春电台,2459
海丰电台,2464
陆河县广播电视台,2465
螺河之声,2466
高要电台,2467
信宜电台,2473
徐闻电台,2475
惠阳电台,2478
台山电台,2480
恩平电台,2481
新兴电台,2484
怀集电台,2486
遂溪电台,2487
新会电台,2489
和平电台,2493
东源电台,2498
吴川电台,2500
普宁电台,2501
乐昌电台,2504
广东新闻广播,2512
珠江经济,2513
音乐之声,2514
交通之声,2515
文体广播,2517
南方生活,2518
城市之声,2519
博罗人民广播电台,2521
潮安电台,2524

*/
    
    $pubKey = "MFwwDQYJKoZIhvcNAQEBBQADSwAwSAJBALLUiZV6DVmAcJGOsWzftnYxDVpIdTlQynYeTtq5Z1ZzUteINPX24GyeetbYjnIT8pq0IdXGEjjBtngvddR0YaMCAwEAAQ==";
    $pubKey = "-----BEGIN PUBLIC KEY-----\n".wordwrap($pubKey,64,"\n",true)."\n-----END PUBLIC KEY-----";
    $randIMEI = substr(md5(rand(10000000,99999999)),rand(0,15),16);
    
    openssl_public_encrypt("IMEI_".$randIMEI,$encData,$pubKey);

    $userid = 'SYS_APP_3BiJfLzAKrlB3eoPkXnMwBepXhNn';//这里的userid是已删除部分字符的不完整userid，请填入自己的userid
    
    // JWT文件处理
    $filename = "触电jwt.txt";
    $initialJwt = "eyJhbGciOiJIUzI1NiJ9.eyJzdWIiOiI1Njc1OTM5MyIsInVzZXJSb2xlcyI6Im5vdFNwZWNpZmllZCIsImlzcyI6Ind3dy5pdG91Y2h0di5jbiIsInVzZXJUeXBlIjoiaXRvdWNodHZBcHAiLCJleHAiOjE3NTQ1NzkxODcsImlhdCI6MTc1MTk4NzE4NywidXNlcklkIjoiU1lTX0FQUF8zQmlKZkx6QUtybEIzZW9Qa1huTXdCZXBY.heW7gacYRZZjN0BLqqaCSmkLYHKU3BtRaWQrO04wSfw";//这里的Jwt是已删除部分字符的不完整Jwt，请填入自己的Jwt
    
    // 初始化或读取JWT文件，保持存储和更新最新的jwt
    if (!file_exists($filename)) {
        file_put_contents($filename, $initialJwt);
        $jwt = $initialJwt;
    } else {
        $content = file_get_contents($filename);
        if (empty($content) || strpos($content, "eyJhbGciOiJIUzI1NiJ9") !== 0) {
            file_put_contents($filename, $initialJwt);
            $jwt = $initialJwt;
        } else {
            $jwt = $content;
        }
    }
    
    // 设置请求头
    $headers = [
        'content-type: application/json; charset=utf-8',
        "referer: https://android.itouchtv.cn/".$randIMEI,
        "X-ITOUCHTV-A01: ".base64_encode($encData),
        "X-ITOUCHTV-CLIENT: NEWS_APP",
        'User-Agent: Mozilla/5.0 (Linux; Android 9; BVL-AN16 Build/PQ3A.190605.02111920; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/91.0.4472.114 Safari/537.36',
        "X-ITOUCHTV-APP-VERSION: 6.0.0",
        "X-ITOUCHTV-Ca-Timestamp: $ts",
        "X-ITOUCHTV-Ca-Key: 04039368653554864194910691389924",
        'X-ITOUCHTV-USER-ID: ' . $userid,
        'Authorization: Bearer ' . $jwt,
    ];
    
    $signkey = "qmiHeB9bKgowHqxRv0prc2cPN2EwXL1HOYu3DPiYCcaYxyxdFIyT5mAfBmr0UKPO";
    
    // 获取新的JWT令牌
    $bstrURL = "https://api.itouchtv.cn/userservice/v2/appUser/".$userid."/jwt";
    $sign = base64_encode(hash_hmac("SHA256","POST\n$bstrURL\n$ts\n",$signkey,true));
    $headers[] = "X-ITOUCHTV-Ca-Signature:$sign";
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $bstrURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    
    if ($data !== false) {
        $responseData = json_decode($data, true);
        if (isset($responseData['jwt'])) {
            $newJwt = $responseData['jwt'];
            file_put_contents($filename, $newJwt);
            $jwt = $newJwt;
        }
    }
    
    // 移除最后一个签名头
    array_pop($headers);
    
    // 获取节点参数
    $bstrURL = "https://tcdn-api.itouchtv.cn/getParam";
    $sign = base64_encode(hash_hmac("SHA256","GET\n$bstrURL\n$ts\n",$signkey,true));
    $headers[] = "X-ITOUCHTV-Ca-Signature:$sign";
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $bstrURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    
    $json = json_decode($data);
    $node = $json->node ?? null;
    array_pop($headers);
    array_pop($headers);
    
    // WebSocket连接获取wsnode
    if ($node) {
        $contextOptions = ['ssl' => ["verify_peer"=>false,"verify_peer_name"=>false]];
        $context = stream_context_create($contextOptions);
        $sock = stream_socket_client("ssl://tcdn-ws.itouchtv.cn:3800", $errno, $errstr, 1, STREAM_CLIENT_CONNECT, $context);
        
        if ($sock) {
            stream_set_timeout($sock, 1);
            $wssData = json_encode(['route' => 'getwsparam', 'message' => $node]);
            $key = base64_encode(substr(md5(mt_rand(1, 999)), 0, 16));
            
            $header = "GET /connect HTTP/1.1\r\n";
            $header .= "Host: tcdn-ws.itouchtv.cn:3800\r\n";
            $header .= "Upgrade: websocket\r\n";
            $header .= "Sec-WebSocket-Key: $key\r\n";
            fwrite($sock, $header . "\r\n");
            
            $handshake = stream_get_contents($sock);
            
            if (strstr($handshake, 'Sec-Websocket-Accept')) {
                fwrite($sock, encode($wssData));
                $param = stream_get_contents($sock);
                $param = substr($param, 4);
                $json = json_decode($param);
                $wsnode = $json->wsnode ?? null;
            }
            fclose($sock);
        }
    }
    
    // 获取频道列表或播放地址
    $bstrURL = 'https://api.itouchtv.cn/liveservice/v5/tvChannelList?number=0&node='.$wsnode.'&tvChannelTypes=0&from=0';
    $sign = base64_encode(hash_hmac("SHA256","GET\n$bstrURL\n$ts\n",$signkey,true));
    $headers[] = 'Authorization: Bearer ' . $jwt;
    $headers[] = "X-ITOUCHTV-Ca-Signature:$sign";
    
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $bstrURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER => $headers,
    ]);
    $data = curl_exec($ch);
    curl_close($ch);
    
    // 输出结果
    if($pk == '') {
        $json = json_decode($data);
        foreach($json->tvChannelList as $out) {
            echo ($out->name.','.$out->pk.'<br />');
        }
    } else {
        preg_match('/pk":'.$pk.',.*?"url":"(.*?)"/i',$data,$result);
        $playURL = $result[1] ?? '';
        if ($playURL) {
            header("location:$playURL");
        }
    }
    
    // WebSocket编码函数
    function encode($data) {
        $len = strlen($data);
        $head[0] = 129;
        $mask = [];
        for ($j = 0; $j < 4; $j++) {
            $mask[] = mt_rand(1, 128);
        }
        $split = str_split(sprintf('%016b', $len), 8);
        $head[1] = 254;
        $head[2] = bindec($split[0]);
        $head[3] = bindec($split[1]);
        $head = array_merge($head, $mask);
        foreach ($head as $k => $v) {
            $head[$k] = chr($v);
        }
        $mask_data = '';
        for ($j = 0; $j < $len; $j++) {
            $mask_data .= chr(ord($data[$j]) ^ $mask[$j % 4]);
        }
        return implode('', $head) . $mask_data;
    }
?>