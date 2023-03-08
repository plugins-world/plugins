<?php

namespace Plugins\SystemAuthorization\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Plugins\SystemAuthorization\Entities\Customer as CustomerEntity;
use Plugins\SystemAuthorization\Entities\AuthCode as AuthCodeEntity;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\SystemAuthorization\Models\AuthCode as AuthCodeModel;
use Plugins\SystemAuthorization\Models\Customer as CustomerModel;
use Plugins\SystemAuthorization\Services\CmdWordService;
use Plugins\SystemAuthorization\Services\CustomerService;
use Plugins\SystemAuthorization\Services\AuthCodeService;
use Plugins\SystemAuthorization\Services\LicenseCodeService;

class AuthCodeController extends Controller
{
    use ResponseTrait;

    /** @var CustomerService */
    protected $customerService;

    /** @var AuthCodeService */
    protected $authCodeService;

    /** @var LicenseCodeService */
    protected $licenseCodeService;

    public function __construct()
    {
        $this->customerService = app(CustomerService::class);
        $this->authCodeService = app(AuthCodeService::class);
        $this->licenseCodeService = app(LicenseCodeService::class);
    }

    public function index()
    {
        \request()->validate([
            'customer_id' => 'required',
        ]);

        $customerEntity = $this->customerService->find(\request('customer_id'));

        return view('SystemAuthorization::auth-codes.index', [
            'customerEntity' => $customerEntity,
        ]);
    }

    public function create()
    {
        return view('SystemAuthorization::auth-codes.create');
    }

    public function store()
    {
        \request()->validate([
            'customer_id' => 'required|integer:32',
            'auth_code_type' => 'required|in:"crm"',
        ], [
            'customer_id.required' => '请提供客户信息',
        ]);

        $customerEntity = $this->customerService->addLicenseCode(\request('customer_id'), \request('auth_code_type'));

        return redirect(route('auth-codes.index', ['customer_id' => $customerEntity->getCustomerId()]))->with([
            'tips' => sprintf('授权码已生成'),
        ]);
    }

    public function show(int $authCodeId)
    {
        $customerEntity = $this->customerService->findByAuthCodeId($authCodeId);

        return view('SystemAuthorization::auth-codes.create', [
            'customerEntity' => $customerEntity,
            'authCode' => $customerEntity->getAuthCodes()[0] ?? null,
            'edit' => true,
        ]);
    }

    public function update(int $authCodeId)
    {
        \request()->validate([
            'auth_code' => 'required',
            'start_time' => 'nullable', // 开始时间
            'end_time' => 'nullable', // 结束时间
        ]);

        $authCodeEntity = new AuthCodeEntity();
        $authCodeEntity->setAuthCodeId($authCodeId);
        $authCodeEntity->setStartTime(\request('start_time'));
        $authCodeEntity->setEndTime(\request('end_time'));
        $authCodeEntity->setAuthCode(\request('auth_code'));

        $authCodeDecryptData = $this->licenseCodeService->decrypt(\request('auth_code'));
        $authCodeInfo = json_decode($authCodeDecryptData, true) ?? [];

        if (strtotime($authCodeInfo['date']) !== strtotime($authCodeEntity->getEndTime())) {
            $customerEntity = $this->customerService->find($authCodeInfo['id']);

            $newAuthCode = $this->licenseCodeService->generateLicenseCode($customerEntity, $authCodeEntity);

            $authCodeEntity->setAuthCode($newAuthCode);
        }
        $authCodeEntity = $this->authCodeService->update($authCodeEntity);

        return redirect(route('auth-codes.index', ['customer_id' => $authCodeEntity->getCustomerId()]))->with([
            'tips' => sprintf('授权码：%s 更新成功', $authCodeEntity->getAuthCodeId()),
        ]);
    }

    public function destroy(int $authCodeId)
    {
        $authCodeEntity = $this->authCodeService->find($authCodeId);

        $this->authCodeService->delete($authCodeEntity);

        return back()->with([
            'tips' => sprintf('授权码：%s 删除成功', $authCodeEntity->getAuthCodeId()),
            'tips_type' => 'success',
        ]);
    }
}
