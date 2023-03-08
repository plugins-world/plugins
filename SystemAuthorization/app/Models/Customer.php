<?php

namespace Plugins\SystemAuthorization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Plugins\SystemAuthorization\Entities\Customer as CustomerEntity;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fromCustomerEntiy(CustomerEntity $customerEntity): Customer
    {
        $customerModel = new Customer();
        $customerModel->id = $customerEntity->getCustomerId();
        $customerModel->customer_type = $customerEntity->getCustomerType();
        $customerModel->customer_name = $customerEntity->getCustomerName();
        $customerModel->mobile = $customerEntity->getMobile(false);
        $customerModel->company_name = $customerEntity->getCompanyName();
        $customerModel->remark = $customerEntity->getRemark();

        return $customerModel;
    }

    public function toCustomerEntity(): CustomerEntity
    {
        $customer = new CustomerEntity();
        $customer->setCustomerId($this->id);
        $customer->setCustomerType($this->customer_type);
        $customer->setCustomerName($this->customer_name);
        $customer->setMobile($this->mobile);
        $customer->setCompanyName($this->company_name);
        $customer->setRemark($this->remark);

        return $customer;
    }
}
