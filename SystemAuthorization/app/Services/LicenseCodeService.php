<?php

namespace Plugins\SystemAuthorization\Services;

use ZhenMu\Support\Utils\RSA;
use MouYong\LaravelConfig\Models\Config;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;
use Plugins\SystemAuthorization\Entities\Customer as CustomerEntity;

class LicenseCodeService
{
    public function generateLicenseCode(CustomerEntity $customerEntity, AuthCodeEntity $authCodeEntity)
    {
        $customerInfo = [
            'companyId' => $customerEntity->getCustomerId(),
            'companyName' => $customerEntity->getCompanyName(),
            'date' => $authCodeEntity->getEndTime(),
            'id' => $customerEntity->getCustomerId(),
            'mobile' => $customerEntity->getMobile(false),
            'name' => $customerEntity->getCustomerName(),
        ];

        $rsaPrivateKey = Config::getValueByKey('rsa_private_key');
        if (!$rsaPrivateKey) {
            return null;
        }

        $licenseCode = RSA::encrypt($customerInfo, $rsaPrivateKey);

        return $licenseCode;
    }

    public function decrypt($licenseCode)
    {
        $rsaPubliKey = Config::getValueByKey('rsa_public_key');
        if (!$rsaPubliKey) {
            return null;
        }

        $licenseCodeInfo = RSA::decrypt($licenseCode, $rsaPubliKey);

        return $licenseCodeInfo;
    }
}