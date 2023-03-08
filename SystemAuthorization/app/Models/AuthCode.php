<?php

namespace Plugins\SystemAuthorization\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ZhenMu\Support\Traits\DateTime;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;

class AuthCode extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function fromAuthCodeEntity(AuthCodeEntity $authCodeEntity): AuthCode
    {
        $authCodeModel = new AuthCode();
        $authCodeModel->customer_id = $authCodeEntity->getCustomerId();
        $authCodeModel->auth_code_type = $authCodeEntity->getAuthCodeType();
        $authCodeModel->auth_code = $authCodeEntity->getAuthCode();
        $authCodeModel->is_permanent = $authCodeEntity->getIsPermanent();
        $authCodeModel->start_time = $authCodeEntity->getStartTime();
        $authCodeModel->end_time = $authCodeEntity->getEndTime();
        $authCodeModel->is_expired = $authCodeEntity->getIsExpired();
        $authCodeModel->system_domain = $authCodeEntity->getSystemDomain();
        $authCodeModel->ip = $authCodeEntity->getIp();
        $authCodeModel->last_use_time = $authCodeEntity->getLastUseTime();
        $authCodeModel->status = $authCodeEntity->getStatus();

        return $authCodeModel;
    }

    public function toAuthCodeEntity(): AuthCodeEntity
    {
        $authCodeEntity = new AuthCodeEntity();
        $authCodeEntity->setAuthCodeId($this->id);
        $authCodeEntity->setCustomerId($this->customer_id);
        $authCodeEntity->setAuthCodeType($this->auth_code_type);
        $authCodeEntity->setAuthCode($this->auth_code);
        $authCodeEntity->setIsPermanent($this->is_permanent);
        $authCodeEntity->setStartTime($this->start_time);
        $authCodeEntity->setEndTime($this->end_time);
        $authCodeEntity->setIsExpired($this->is_expired);
        $authCodeEntity->setSystemDomain($this->system_domain);
        $authCodeEntity->setIp($this->ip);
        $authCodeEntity->setLastUseTime($this->last_use_time);
        $authCodeEntity->setStatus($this->status);

        return $authCodeEntity;
    }

    public function remove()
    {
        return $this->delete();
    }

    public function revoke()
    {
        return $this->update([
            'status' => 4, // 撤销
        ]);
    }

    public function isValid()
    {
        $remainTime = DateTime::remainTime($this->end_time);

        return (bool) $remainTime;
    }

    public function isExpired()
    {
        return !$this->isValid();
    }
}
