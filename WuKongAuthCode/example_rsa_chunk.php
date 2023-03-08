<?php

// 定义 RSA 加密的密钥长度
define("KEY_SIZE", 2048);

// 生成 RSA 加密的公钥和私钥
$res = openssl_pkey_new(array(
    "private_key_bits" => KEY_SIZE,
    "private_key_type" => OPENSSL_KEYTYPE_RSA,
));

// 将私钥转换为字符串
openssl_pkey_export($res, $privateKey);

// 从私钥中得到公钥
$publicKey = openssl_pkey_get_details($res)["key"];

// 需要加密的数据
$plaintext = "Hello, World!";

// RSA 加密，将需要加密的数据按照最大加密长度分块
$chunkSize = KEY_SIZE / 8 - 11;
$output = "";
while ($plaintext) {
    $chunk = substr($plaintext, 0, $chunkSize);
    $plaintext = substr($plaintext, $chunkSize);
    openssl_public_encrypt($chunk, $encrypted, $publicKey);
    $output .= $encrypted;
}

// 对 RSA 加密后的数据进行解密
$plaintext = "";
while ($output) {
    $chunk = substr($output, 0, KEY_SIZE / 8);
    $output = substr($output, KEY_SIZE / 8);
    openssl_private_decrypt($chunk, $decrypted, $privateKey);
    $plaintext .= $decrypted;
}

// 输出解密后的数据
echo $plaintext;

?>
