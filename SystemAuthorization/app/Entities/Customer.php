<?php

namespace Plugins\SystemAuthorization\Entities;

class Customer
{
    protected $customerId;
    protected $customerType;
    protected $customerName;
    protected $mobile;
    protected $companyName;
    protected $remark;

    protected $authCodes;

    /**
     * Get the value of customerId
     */ 
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Set the value of customerId
     *
     * @return  self
     */ 
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;

        return $this;
    }

    /**
     * Get the value of customerType
     */ 
    public function getCustomerType()
    {
        return $this->customerType;
    }

    /**
     * Set the value of customerType
     *
     * @return  self
     */ 
    public function setCustomerType($customerType)
    {
        $this->customerType = $customerType;

        return $this;
    }

    /**
     * Get the value of customerName
     */ 
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set the value of customerName
     *
     * @return  self
     */ 
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get the value of mobile
     */ 
    public function getMobile($mask = true)
    {
        if ($mask && $this->mobile) {
            return \ZhenMu\Support\Utils\Str::maskNumber($this->mobile);
        }

        return $this->mobile;
    }

    /**
     * Set the value of mobile
     *
     * @return  self
     */ 
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get the value of companyName
     */ 
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set the value of companyName
     *
     * @return  self
     */ 
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get the value of remark
     */ 
    public function getRemark()
    {
        return $this->remark;
    }

    /**
     * Set the value of remark
     *
     * @return  self
     */ 
    public function setRemark($remark)
    {
        $this->remark = $remark;

        return $this;
    }

    /**
     * Get the value of authCodes
     */ 
    public function getAuthCodes()
    {
        return $this->authCodes ?? [];
    }

    /**
     * Set the value of authCodes
     *
     * @return  self
     */ 
    public function addAuthCode(AuthCode $authCode)
    {
        $this->authCodes[] = $authCode;

        return $this;
    }

    public function setAuthCodes($customerAuthCodeModels = [])
    {
        /** @var AuthCodeModel $customerAuthCodeModel */
        foreach ($customerAuthCodeModels as $customerAuthCodeModel) {
            $authCodeEntity = $customerAuthCodeModel->toAuthCodeEntity();
            $this->addAuthCode($authCodeEntity);
        }
    }

    public function getAuthCodeCount()
    {
        return count($this->getAuthCodes());
    }
}