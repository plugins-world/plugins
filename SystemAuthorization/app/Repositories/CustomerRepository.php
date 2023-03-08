<?php

namespace Plugins\SystemAuthorization\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Plugins\SystemAuthorization\Entities\Customer as CustomerEntity;
use Plugins\SystemAuthorization\Models\Customer as CustomerModel;

class CustomerRepository
{
    protected $customer;

    public function __construct()
    {
        $this->customer = new CustomerModel();
    }

    public function findById(int $id): ?CustomerModel
    {
        return $this->customer->where('id', $id)->first();
    }

    public function findByMobile(string $mobile): ?CustomerModel
    {
        return $this->customer->where('mobile', $mobile)->first();
    }

    public function findByCustomerName(string $name): ?CustomerModel
    {
        return $this->customer->where('customer_name', $name)->first();
    }

    public function query(array $params): Collection|LengthAwarePaginator
    {
        $data = $this->customer
            ->orderByDesc('created_at')
            ->result(\request('per_page', 20));
        
        return $data;
    }

    public function save(CustomerEntity $customerEntity): ?CustomerModel
    {
        $customerModel = $this->customer->fromCustomerEntiy($customerEntity);

        $customerModel->save();

        return $customerModel;
    }

    public function update(CustomerEntity $customerEntity): ?CustomerModel
    {
        $customerModel = $this->findById($customerEntity->getCustomerId());
        
        $customerModel->update($this->customer->fromCustomerEntiy($customerEntity)->toArray());

        return $customerModel;
    }

    public function delete(CustomerEntity $customerEntity): int
    {
        $customerModel = $this->findById($customerEntity->getCustomerId());
        
        return $customerModel->delete();
    }
}
