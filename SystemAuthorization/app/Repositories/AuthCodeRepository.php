<?php

namespace Plugins\SystemAuthorization\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;
use Plugins\SystemAuthorization\Models\AuthCode as AuthCodeModel;

class AuthCodeRepository
{
    protected $authCode;

    public function __construct()
    {
        $this->authCode = new AuthCodeModel();
    }

    public function findById(int $id)
    {
        return $this->authCode->where('id', $id)->first();
    }

    public function getAuthCodesByCustomerIds(array $authCodeIds)
    {
        return $this->authCode->whereIn('customer_id', $authCodeIds)->get();
    }

    public function query(array $params): Collection|LengthAwarePaginator
    {
        $data = $this->authCode
            ->result(\request('per_page', 20));
        
        return $data;
    }

    public function findByCustomerIdAndAuthCodeType(AuthCodeEntity $authCodeEntity): ?AuthCodeModel
    {
        return $this->authCode->where([
            'customer_id' => $authCodeEntity->getCustomerId(),
            'auth_code_type' => $authCodeEntity->getAuthCodeType(),
        ])->first();
    }

    public function save(AuthCodeEntity $authCodeEntity): ?AuthCodeModel
    {
        $authCodeModel = $this->authCode->fromAuthCodeEntity($authCodeEntity);

        $authCodeModel->save();

        return $authCodeModel;
    }

    public function update(AuthCodeEntity $authCodeEntity): ?AuthCodeModel
    {
        $authCodeModel = $this->findById($authCodeEntity->getAuthCodeId());
        
        $authCodeModel->update($this->authCode->fromAuthCodeEntity($authCodeEntity)->toArray());

        return $authCodeModel;
    }

    public function updateAuthCodeInfo(AuthCodeEntity $authCodeEntity): ?AuthCodeModel
    {
        $authCodeModel = $this->findById($authCodeEntity->getAuthCodeId());
        
        $authCodeModel->update([
            'auth_code' => $authCodeEntity->getAuthCode(),
            'start_time' => $authCodeEntity->getStartTime(),
            'end_time' => $authCodeEntity->getEndTime(),
        ]);

        return $authCodeModel;
    }

    public function delete(AuthCodeEntity $authCodeEntity): int
    {
        $authCodeModel = $this->findById($authCodeEntity->getAuthCodeId());
        
        return $authCodeModel->delete();
    }
}
