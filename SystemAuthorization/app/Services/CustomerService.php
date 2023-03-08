<?php

namespace Plugins\SystemAuthorization\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use Plugins\SystemAuthorization\Entities\Customer as CustomerEntity;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;
use Plugins\SystemAuthorization\Repositories\AuthCodeRepository;
use Plugins\SystemAuthorization\Repositories\CustomerRepository;
use Plugins\SystemAuthorization\Models\Customer as CustomerModel;

class CustomerService
{
    protected $customerRepository;
    protected $authCodeRepository;

    /** @var LicenseCodeService */
    protected $licenseCodeService;

    public function __construct(CustomerRepository $customerRepository, AuthCodeRepository $authCodeRepository)
    {
        $this->customerRepository = $customerRepository;
        $this->authCodeRepository = $authCodeRepository;

        $this->licenseCodeService = app(LicenseCodeService::class);
    }

    public function addCustomer(CustomerEntity $customerEntity): CustomerEntity
    {
        $customerModel = $this->customerRepository->findByCustomerName($customerEntity->getCustomerName());

        if ($customerModel) {
            throw new \RuntimeException(sprintf('客户：%s 已存在', $customerEntity->getCustomerName()));
        }

        $customerModel = $this->customerRepository->save($customerEntity);
        return $customerModel->toCustomerEntity();
    }

    public function find(int $customerId): CustomerEntity
    {
        $customerModel = $this->customerRepository->findById($customerId);

        if (!$customerModel) {
            throw new \RuntimeException(sprintf('客户：%s 不存在', $customerId));
        }

        $customerEntity = $customerModel->toCustomerEntity();
        $customerAuthCodeModels = $this->authCodeRepository->getAuthCodesByCustomerIds([$customerEntity->getCustomerId()]);

        $customerEntity->setAuthCodes(
            $customerAuthCodeModels->where('customer_id', $customerEntity->getCustomerId())->all()
        );

        return $customerEntity;
    }

    public function findByAuthCodeId(int $authCodeId)
    {
        $authCodeModel = $this->authCodeRepository->findById($authCodeId);
        if (!$authCodeModel) {
            throw new \RuntimeException(sprintf('授权码：%s 不存在', $authCodeModel->getAuthCodeId()));
        }

        return $this->find($authCodeModel->customer_id);
    }

    public function update(CustomerEntity $customerEntity): CustomerEntity
    {
        $customerModel = $this->customerRepository->findById($customerEntity->getCustomerId());
        if (!$customerModel) {
            throw new \RuntimeException(sprintf('客户：%s 不存在', $customerEntity->getCustomerName()));
        }

        $customerModel = $this->customerRepository->update($customerEntity);
        return $customerModel->toCustomerEntity();
    }

    public function delete(CustomerEntity $customerEntity): int
    {
        $customerModel = $this->customerRepository->findById($customerEntity->getCustomerId());
        if (!$customerModel) {
            throw new \RuntimeException(sprintf('客户：%s 不存在', $customerEntity->getCustomerName()));
        }

        return $this->customerRepository->delete($customerEntity);
    }

    public function getList(array $params)
    {
        $customerModels = $this->customerRepository->query($params);

        $authCodes = $this->authCodeRepository->getAuthCodesByCustomerIds(
            $customerIds = $customerModels->pluck('id')->all()
        );

        $data = [];
        /** @var CustomerModel $customerModel */
        foreach ($customerModels as $customerModel) {
            /** @var CustomerEntity $customerEntity */
            $customerEntity = $customerModel->toCustomerEntity();

            $customerEntity->setAuthCodes(
                $authCodes->where('customer_id', $customerEntity->getCustomerId())->all()
            );

            $data[] = $customerEntity;
        }

        if ($customerModels instanceof LengthAwarePaginator) {
            return new LengthAwarePaginator(
                $data,
                $customerModels->total(),
                $customerModels->perPage(),
                $customerModels->currentPage(),
                $customerModels->getOptions()
            );
        }

        return $data;
    }

    public function addLicenseCode($customerId, $authCodeType)
    {
        $customerEntity = $this->find($customerId);
        if (!$customerEntity) {
            throw new \RuntimeException(sprintf('客户编号 %s 不存在', $customerEntity->getCustomerId()));
        }

        $authCodeEntity = new AuthCodeEntity();
        $authCodeEntity->setAuthCodeId(null);
        $authCodeEntity->setCustomerId($customerEntity->getCustomerId());
        $authCodeEntity->setAuthCodeType($authCodeType);
        $authCodeEntity->setIsPermanent(false);
        $authCodeEntity->setStartTime(date('Y-m-d H:i:s'));
        $authCodeEntity->setEndTime(date('Y-m-d H:i:s', strtotime('-30 day')));
        $authCodeEntity->setIsExpired(false);
        $authCodeEntity->setSystemDomain(null);
        $authCodeEntity->setIp(null);
        $authCodeEntity->setLastUseTime(null);
        $authCodeEntity->setStatus(1);

        $licenseCode = $this->licenseCodeService->generateLicenseCode($customerEntity, $authCodeEntity);

        $authCodeEntity->setAuthCode($licenseCode);

        if ($this->authCodeRepository->findByCustomerIdAndAuthCodeType($authCodeEntity)) {
            throw new \RuntimeException(sprintf('客户 %s 已有授权码', $customerEntity->getCustomerName()));
        }

        $this->authCodeRepository->save($authCodeEntity);

        $customerEntity->addAuthCode($authCodeEntity);

        return $this->find($customerEntity->getCustomerId());
    }
}
