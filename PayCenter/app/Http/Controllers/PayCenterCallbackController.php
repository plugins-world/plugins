<?php

namespace Plugins\PayCenter\Http\Controllers;

use Illuminate\Routing\Controller;

class PayCenterCallbackController extends Controller
{
    public function wechatPayCallback()
    {
        // {
        //     "id": "e405c009-1723-5c6f-984e-66e2580f7022",
        //     "create_time": "2023-07-21T00:13:05+08:00",
        //     "resource_type": "encrypt-resource",
        //     "event_type": "TRANSACTION.SUCCESS",
        //     "summary": "支付成功",
        //     "resource": {
        //         "original_type": "transaction",
        //         "algorithm": "AEAD_AES_256_GCM",
        //         "ciphertext": "v7NokRgfkUrhuHoIapAZtdCbN0vU8yKkF/lvWGMtgeYpFolHRQEFo1WHhYHzv2/cTWPC8MsAxXztcZ/k/ncLji4BV7ZlYWlcPZWexNVGKH0J/V8EW52FMMUnc/um01jo6zDHzXfiogEvIETWTR3Je1+ZOtFsmZOXbDpboa8J3SYvIIlmDyeZKWdCgrSXjNqnuQGzczDqcnWYZ3H3gADr4JrwzdQ0Bko3qlNxNXc8nqLErzsfIFItFtvA0/4yDnc7ttr5wPQ6DPRfOxUZMbJxYb/bwYSTCliFj2TR1R8kCtjVmSJAET3JAKxcWzG7j7Y0wuGoAZTebh0Mee8WeclJOFUsrxfu1BJqxixxNumERem9AoEuepstieyDzSOk2eZ/PIDK5J7uejm126SxXCI6OlnTxCrr90KIiujJTTTxY42BiMJwTwALu+5K9AHgRLgg+wdJmA6tc2LHmx3Kak/sQ+j2S/IUvf+Vd2amq6csjrXyr8L6QpVU6xlEDBYz5ZsfBRWoXaLhR57TJ2Twxw5XHalpneK+j8XX/Sy3lDROMcmjRPOmlxSpobCXOIQ=",
        //         "associated_data": "transaction",
        //         "nonce": "rPIL6BRB9aG0"
        //     }
        // }
        info('request class: '. __CLASS__);
        info('request method: '. request()->method());
        info('receive wechatpay callback', request()->all());
    }
    
    public function aliPayCallback()
    {
        info('request class: '. __CLASS__);
        info('request method: '. request()->method());
        info('receive alipay callback', request()->all());
    }
    
    public function uniPayCallback()
    {
        info('request class: '. __CLASS__);
        info('request method: '. request()->method());
        info('receive unipay callback', request()->all());
    }
}
