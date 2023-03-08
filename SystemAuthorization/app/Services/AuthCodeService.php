<?php

namespace Plugins\SystemAuthorization\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;
use Plugins\SystemAuthorization\Repositories\AuthCodeRepository;
use Plugins\SystemAuthorization\Repositories\CustomerRepository;
use Plugins\SystemAuthorization\Models\AuthCode as AuthCodeModel;

class AuthCodeService
{
    protected $customerRepository;
    protected $authCodeRepository;

    public function __construct(CustomerRepository $customerRepository, AuthCodeRepository $authCodeRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->authCodeRepository = $authCodeRepository;
    }

    public function find(int $authCodeId): AuthCodeEntity
    {
        $authCodeModel = $this->authCodeRepository->findById($authCodeId);

        if (!$authCodeModel) {
            throw new \RuntimeException(sprintf('授权码编号：%s 不存', $authCodeId));
        }

        $authCodeEntity = $authCodeModel->toAuthCodeEntity();

        return $authCodeEntity;
    }

    public function findByCustomerIdAndAuthCodeType(int $customerId, string $authCodeType)
    {
        $customerModel = $this->customerRepository->findById($customerId);
        $customerEntity = $customerModel->toCustomerEntity();

        $authCodeEntity = new AuthCodeEntity();
        $authCodeEntity->setCustomerId($customerId);
        $authCodeEntity->setAuthCodeType($authCodeType);

        $authCodeModel = $this->authCodeRepository->findByCustomerIdAndAuthCodeType($authCodeEntity);
        if (!$authCodeModel) {
            throw new \RuntimeException(sprintf('客户不存在 类型的授权码', $customerEntity->getCustomerName(), $authCodeEntity->getAuthCodeType()));
        }

        $authCodeEntity = $authCodeModel->toAuthCodeEntity();

        return $authCodeEntity;
    }

    public function update(AuthCodeEntity $authCodeEntity): AuthCodeEntity
    {
        $authCodeModel = $this->authCodeRepository->findById($authCodeEntity->getAuthCodeId());
        if (!$authCodeModel) {
            throw new \RuntimeException(sprintf('授权码编号：%s 不存在', $authCodeEntity->getAuthCodeId()));
        }

        $authCodeEntity->setCustomerId($authCodeModel->customer_id);

        $authCodeModel = $this->authCodeRepository->updateAuthCodeInfo($authCodeEntity);
        return $authCodeModel->toAuthCodeEntity();
    }

    public function delete(AuthCodeEntity $authCodeEntity): int
    {
        $authCodeModel = $this->authCodeRepository->findById($authCodeEntity->getAuthCodeId());
        if (!$authCodeModel) {
            throw new \RuntimeException(sprintf('授权码编号：%s 不存在', $authCodeEntity->getAuthCodeId()));
        }

        return $this->authCodeRepository->delete($authCodeEntity);
    }
}