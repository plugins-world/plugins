<?php

namespace Plugins\SystemAuthorization\Entities;

class AuthCode
{
    protected $authCodeId;
    protected $customerId;
    protected $authCodeType;
    protected $authCode;
    protected $isPermanent;
    protected $startTime;
    protected $endTime;
    protected $isExpired;
    protected $systemDomain;
    protected $ip;
    protected $lastUseTime;
    protected $status;
    protected $customer;

    /**
     * Get the value of authCodeId
     */ 
    public function getAuthCodeId()
    {
        return $this->authCodeId;
    }

    /**
     * Set the value of authCodeId
     *
     * @return  self
     */ 
    public function setAuthCodeId($authCodeId)
    {
        $this->authCodeId = $authCodeId;

        return $this;
    }

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
     * Get the value of authCodeType
     */ 
    public function getAuthCodeType()
    {
        return $this->authCodeType;
    }

    /**
     * Set the value of authCodeType
     *
     * @return  self
     */ 
    public function setAuthCodeType($authCodeType)
    {
        $this->authCodeType = $authCodeType;

        return $this;
    }

    /**
     * Get the value of authCode
     */ 
    public function getAuthCode()
    {
        return $this->authCode;
    }

    /**
     * Set the value of authCode
     *
     * @return  self
     */ 
    public function setAuthCode($authCode)
    {
        $this->authCode = $authCode;

        return $this;
    }

    /**
     * Get the value of isPermanent
     */ 
    public function getIsPermanent()
    {
        return $this->isPermanent;
    }

    /**
     * Set the value of isPermanent
     *
     * @return  self
     */ 
    public function setIsPermanent($isPermanent)
    {
        $this->isPermanent = $isPermanent;

        return $this;
    }

    /**
     * Get the value of startTime
     */ 
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set the value of startTime
     *
     * @return  self
     */ 
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get the value of endTime
     */ 
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set the value of endTime
     *
     * @return  self
     */ 
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get the value of isExpired
     */ 
    public function getIsExpired()
    {
        return $this->isExpired;
    }

    /**
     * Set the value of isExpired
     *
     * @return  self
     */ 
    public function setIsExpired($isExpired)
    {
        $this->isExpired = $isExpired;

        return $this;
    }

    /**
     * Get the value of systemDomain
     */ 
    public function getSystemDomain()
    {
        return $this->systemDomain;
    }

    /**
     * Set the value of systemDomain
     *
     * @return  self
     */ 
    public function setSystemDomain($systemDomain)
    {
        $this->systemDomain = $systemDomain;

        return $this;
    }

    /**
     * Get the value of ip
     */ 
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set the value of ip
     *
     * @return  self
     */ 
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get the value of lastUseTime
     */ 
    public function getLastUseTime()
    {
        return $this->lastUseTime;
    }

    /**
     * Set the value of lastUseTime
     *
     * @return  self
     */ 
    public function setLastUseTime($lastUseTime)
    {
        $this->lastUseTime = $lastUseTime;

        return $this;
    }

    /**
     * Get the value of status
     */ 
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */ 
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param mixed $customer
     */
    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
    }
}