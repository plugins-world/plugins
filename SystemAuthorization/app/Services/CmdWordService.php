<?php

namespace Plugins\SystemAuthorization\Services;

use ZhenMu\Support\Utils\RSA;
use MouYong\LaravelConfig\Models\Config;
use Plugins\SystemAuthorization\Models\AuthCode as AuthCodeModel;
use Plugins\SystemAuthorization\Models\Customer as CustomerModel;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;

class CmdWordService
{
    use \Fresns\CmdWordManager\Traits\CmdWordResponseTrait;

    protected  $customerService;
    protected  $authCodeService;
    protected  $licenseCodeService;

    public function __construct()
    {
        $this->customerService = app(CustomerService::class);
        $this->authCodeService = app(AuthCodeService::class);
        $this->licenseCodeService = app(LicenseCodeService::class);
    }

    public function issueCode(array $wordBody)
    {
        try {
            $customerEntity = $this->customerService->addLicenseCode($wordBody['customer_id'], $wordBody['auth_code_type']);
            $authCodeEntity = $customerEntity->getAuthCodes()[0];
        } catch (\Throwable $e) {
            // 用户已经有了 auth_code_type 类型的授权码
            $authCodeEntity = $this->authCodeService->findByCustomerIdAndAuthCodeType($wordBody['customer_id'], $wordBody['auth_code_type']);
        }

        return $this->success([
            'auth_code' => $authCodeEntity->getAuthCode(),
        ]);
    }

    public function revokeCode(array $wordBody)
    {
        $data = [];
        $data['customer_id'] = $wordBody['customer_id'];
        $data['auth_code'] = $wordBody['auth_code'];

        $customer = CustomerModel::where('id', $data['customer_id'])->first();
        if (!$customer) {
            return $this->failure(404, "未找到 {$data['customer_id']} 相关客户信息");
        }

        $authCode = AuthCodeModel::where([
            'customer_id' => $data['customer_id'],
            'auth_code' => $data['auth_code'],
        ])->first();
        if (!$authCode) {
            return $this->failure(404, "未找到 {$data['auth_code']} 相关授权码信息");
        }

        $authCode->revoke();

        return $this->success();
    }

    public function updateAuthCode(int $customerId)
    {
        $authCode = AuthCodeModel::where('customer_id', $customerId)->first();
        if (!$authCode) {
            return null;
        }

        $customer = CustomerModel::find($authCode['customer_id']);

        $customerInfo = [
            'companyId' => $customer['id'],
            'companyName' => $customer['company_name'],
            'date' => $authCode->end_time,
            'id' => $customer['id'],
            'mobile' => $customer['mobile'],
            'name' => $customer['customer_name'],
        ];

        $rsaPrivateKey = Config::getValueByKey('rsa_private_key');
        $wkCode = RSA::encrypt($customerInfo, $rsaPrivateKey);

        $authCode->update([
            'is_expired' => 0,
            'status' => 2,
            'auth_code' => $wkCode,
        ]);
    }

    public function validateCode(array $wordBody)
    {
        $authCodeDecryptData = $this->licenseCodeService->decrypt($wordBody['auth_code']);
        $authCodeInfo = json_decode($authCodeDecryptData, true) ?? [];

        if (!$authCodeInfo) {
            return $this->failure('授权信息不正确');
        }

        $authCodeEntity = $this->authCodeService->findByCustomerIdAndAuthCodeType($authCodeInfo['id'], $wordBody['auth_code_type']);

        return $this->success([
            'auth_code_type' => $authCodeEntity->getAuthCodeType(),
            'auth_code' => $authCodeEntity->getAuthCode(),
        ]);
    }

    public function removeCode(array $wordBody)
    {
        $authCodeDecryptData = $this->licenseCodeService->decrypt($wordBody['auth_code']);
        $authCodeInfo = json_decode($authCodeDecryptData, true) ?? [];

        if (!$authCodeInfo) {
            return $this->failure('授权信息不正确');
        }

        $authCodeEntity = $this->authCodeService->findByCustomerIdAndAuthCodeType($authCodeInfo['id'], $wordBody['auth_code_type']);

        $this->authCodeService->delete($authCodeEntity);

        return $this->success();
    }
}