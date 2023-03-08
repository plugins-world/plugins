<?php

$privateKey = openssl_pkey_new(array(
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
));
openssl_pkey_export($privateKey, $pkeyout);

$publicKey = openssl_pkey_get_details($privateKey)['key'];

var_dump($privateKey);
var_dump($pkeyout);
var_dump($publicKey);
die;

echo '原始内容: '.$data."\n";

$pubkey = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1zPb8FxkuL9hxM843X58
4CrHIQr7YtTnbZwhSwbtCs907J3OnNBZbH6GvOQoqQ97JuhCVNSyzYc0CPsWzmc0
3jlpiQiUmvifwYvBu1pZq7FLekEpCPud2fcfzbqcjqYEo7Z9iIt4zqU8y1AMQF+Z
K4HNtJnbNyqPfsTrKIUw9kj0l0HHFstkq6qGhW0+iqsbPDsjY4JDRKP0tiaaXyme
Oy1rr2tzyCmONjkOzlIyw3BobcjjCrpBpjQKXEdWscWJjhD9NobNQ15Oiqa0JzkT
KHj6BlMdgKY8HVmaeRS0u/tbAP3ph29rz72RlmHxe+XpPKOSqfegFTZcpPevhoPM
kwIDAQAB
-----END PUBLIC KEY-----";
$prikey = "-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEA1zPb8FxkuL9hxM843X584CrHIQr7YtTnbZwhSwbtCs907J3O
nNBZbH6GvOQoqQ97JuhCVNSyzYc0CPsWzmc03jlpiQiUmvifwYvBu1pZq7FLekEp
CPud2fcfzbqcjqYEo7Z9iIt4zqU8y1AMQF+ZK4HNtJnbNyqPfsTrKIUw9kj0l0HH
Fstkq6qGhW0+iqsbPDsjY4JDRKP0tiaaXymeOy1rr2tzyCmONjkOzlIyw3Bobcjj
CrpBpjQKXEdWscWJjhD9NobNQ15Oiqa0JzkTKHj6BlMdgKY8HVmaeRS0u/tbAP3p
h29rz72RlmHxe+XpPKOSqfegFTZcpPevhoPMkwIDAQABAoIBAGNYL1ogbObUgp/G
QawObjtVxCM+3JndSxDQmJX4Fol9B68LkovVqtJo/m5IrXSODv4BDk32+qvilGTo
9LhH8KH9wvhdm6yGxcklaUPCC880w3Emj3j0HwS2Dlp8oTVA8rdY0U6thBFxOkVp
KJ63AxCQlZOfyxEGdsPAyAYmplmqr3Q3jlTk74nGJUA9n0ZkX7t010TNXVcS4FTA
3I+1FXYHX4QBw33X2Vj84Ur2dCSG7Lf2GcDXWLDOHyAWAyHEFN7hsGBFph7QTu0J
1dJ4xMqCAn3MyXogIjE8+2dhp/uh4DbzDPnftlmCkbgp+ssTGp893nwmLD+AtZME
2KBNLaECgYEA7fyXVKClr1t39O4E4AbX/LHPCG7fmowNCv1OyPUTN5JTuEx9ZPKW
r5WvBH5LgkBXDqByrd6GpsPA2Yk7SJpaMRlDjorMuXPNS/xYM/v/Wehm5C2Ovztn
sFwLLeOZ3K9gJWGuB5MQrP1+raw4YeOqvhGDeWAwZC872Lh/9uSBSmsCgYEA533J
NYzqWrMRDT9QaOfg8dSgYB/eiGTIeDyqhRogpY3rEWatE48pPaC9iSeZKz/EztYy
mnN9kGy1BfSqCpttqJ06Si8v/wZlAuqzraOuXaZu89DAYWTCSrCk0AXKl6DBOH4H
q9NlxEb1XV4bY1BtFAgBVZpcoVcAckILr3Ne4HkCgYEAprx92hDjhER1euj4CW1C
Dg0VnDbx+nl8+eIXPLxXxmuCtHECuaMs57/bay6BALTLSbgoIKDzfgtQJhj7rBZY
cmXc6xVb8eKsRzx5H5LCiN9Glz9D779TGkCipHf96JwGpKoXH79tw4WnJ06uAgdc
LOZgUr2NqeNd7qz1Gqll3BkCgYBdvd88EztXzUmrbqc2RCggZfUn19/6pa1Um2SG
D+WGhSja3BRcZk3SCgSWxPVOwT0GcVD+oKQJVywbJE+zietnK3xOTDuIb2N6QebO
+wiCHgKyMyekiPPw4QVsw9udeVilcsvSdgGw8Pctfw1iM1BomzFHJAI8x4mDu2EW
BIc4KQKBgFfWOJjLHGGJIZxq4Ib1KwXW3sdZvfR1032o8LxtAhuttSoNVmLrHDMD
RMTjGreucIWtI55/daiTykFa7cfaAVMo/4T/hIETMzgh6YifFKuKMKnwTLyXNGF9
yRFAwtADJ47TdwRzMMp50jPoBBLSwBxoj9rHw5RV4237F1O4f7Iv
-----END RSA PRIVATE KEY-----";

function String2Hex($string){
    $hex = '';
    for ($i=0; $i < strlen($string); $i++){
        $ord = ord($string[$i]);
        $hexCode = dechex($ord);
        $hex .= substr('0'.$hexCode, -2);
    }
    return $hex;
}

function signWkCode($data, $private_key) {
    // $pri = formatPriKey($private_key);
    $pri = $private_key;
    $res = openssl_private_encrypt($data, $encrypted, $pri);
    if(!$res) return false;
    return String2Hex($encrypted);
}

function formatPriKey($priKey) {
    $fKey = "-----BEGIN PRIVATE KEY-----\n";
    $len = strlen($priKey);
    for($i = 0; $i < $len; ) {
        $fKey = $fKey . substr($priKey, $i, 64) . "\n";
        $i += 64;
    }
    $fKey .= "-----END PRIVATE KEY-----";
    return $fKey;
}

// $data = '原始数据';
// $private_key = '私钥字符串';
$private_key = $prikey;
$wxcode = signWkCode($data, $private_key);
var_dump($wxcode);

$privateKey = openssl_pkey_new(array(
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
));

$publicKey = openssl_pkey_get_details($privateKey)['key'];
var_dump($privateKey);
var_dump($publicKey);