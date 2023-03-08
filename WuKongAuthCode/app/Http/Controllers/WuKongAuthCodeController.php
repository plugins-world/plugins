<?php

namespace Plugins\WuKongAuthCode\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use MouYong\LaravelConfig\Models\Config;
use ZhenMu\Support\Traits\ResponseTrait;
use ZhenMu\Support\Utils\RSA;
use Plugins\SystemAuthorization\Models\Customer;

class WuKongAuthCodeController extends Controller
{
    use ResponseTrait;

    public function authCodeIssue()
    {
        \request()->validate([
            'mobile' => 'required',
            'system_domain' => 'required',
            'ip' => 'required',
        ]);

        $mobile = \request('mobile');

        # {"companyId":1198775893045153792,"companyName":"SARD11","date":"2022-01-10","id":"S9IZKIHN","mobile":"15288723344","name":"admin"}
        $customer = Customer::where('mobile', $mobile)->first();
        if (!$customer) {
            return $this->fail("未找到客户 {$mobile} 相关信息");
        }
        
        $start_time = date('Y-m-d H:i:s');
        $end_time = strtotime('+1 month', strtotime($start_time));
        $end_time = date('Y-m-d H:i:s', $end_time);
        $customerInfo = [
            'companyId' => $customer['id'],
            'companyName' => $customer['company_name'],
            'date' => $end_time,
            'id' => $customer['id'],
            'mobile' => $mobile,
            'name' => $customer['customer_name'],
        ];

        $rsaPrivateKey = Config::getValueByKey('rsa_private_key');
        $wkCode = RSA::encrypt($customerInfo, $rsaPrivateKey);
        
        // 调用命令字，签发授权码
        $wordBody = [];
        $wordBody['mobile'] = $mobile;

        $wordBody['customer_id'] = $customer['id'];
        $wordBody['auth_code_type'] = 'crm';
        $wordBody['auth_code'] = $wkCode;
        $wordBody['is_permanent'] = false;
        $wordBody['start_time'] = $start_time;
        $wordBody['end_time'] = $end_time;
        $wordBody['is_expired'] = false;
        $wordBody['system_domain'] = \request('system_domain');
        $wordBody['ip'] = \request('ip');
        $wordBody['last_use_time'] = null;
        $wordBody['status'] = 1;

        $fresnsResp = \FresnsCmdWord::plugin('SystemAuthorization')->issueCode($wordBody);
        if ($fresnsResp->isErrorResponse()) {
            return $this->fail($fresnsResp->getMessage());
        }

        return $this->success([
            'wk_code' => $fresnsResp->getData('auth_code'),
        ]);
    }

    public function authCodeRevoke()
    {
        \request()->validate([
            'customer_id' => 'required',
            'auth_code' => 'required',
        ]);

        $wordBody = [];
        $wordBody['customer_id'] = \request('customer_id');
        $wordBody['auth_code'] = \request('auth_code');
        $fresnsResp = \FresnsCmdWord::plugin('SystemAuthorization')->revokeCode($wordBody);

        return $this->success($fresnsResp->getData());
    }

    public function authCodeValidate()
    {
        \request()->validate([
            'auth_code' => 'required',
        ]);

        $wordBody = [];
        $wordBody['auth_code'] = \request('auth_code');
        $wordBody['auth_code_type'] = \request('auth_code_type', 'crm');

        $fresnsResp = \FresnsCmdWord::plugin('SystemAuthorization')->validateCode($wordBody);

        return $this->success([
            'wk_code' => $fresnsResp->getData('auth_code'),
        ]);
    }
}
