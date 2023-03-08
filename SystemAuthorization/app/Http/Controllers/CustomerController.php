<?php

namespace Plugins\SystemAuthorization\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ZhenMu\Support\Traits\ResponseTrait;
use Plugins\SystemAuthorization\Entities\Customer as CustomerEntity;
use Plugins\SystemAuthorization\Services\CustomerService;

class CustomerController extends Controller
{
    use ResponseTrait;

    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        $customerEntities = $this->customerService->getList(\request()->all());

        return view('SystemAuthorization::index', [
            'customerEntities' => $customerEntities,
        ]);
    }

    public function create()
    {
        return view('SystemAuthorization::create', [
            'customerEntity' => null,
        ]);
    }

    public function store()
    {
        \request()->validate([
            'customer_type' => 'required|in:1,2',
            'customer_name' => 'required|max:32',
            'mobile' => 'nullable|max:32',
            'company_name' => 'nullable|max:32',
            'remark' => 'nullable|max:255',
        ], [
            'customer_name.required' => '请提供客户名',
            'mobile.required' => '请提供手机号',
        ]);

        $customerEntity = new CustomerEntity();
        $customerEntity->setCustomerType(\request('customer_type'));
        $customerEntity->setCustomerName(\request('customer_name'));
        $customerEntity->setMobile(\request('mobile'));
        $customerEntity->setCompanyName(\request('company_name'));
        $customerEntity->setRemark(\request('remark'));

        $customerEntity = $this->customerService->addCustomer($customerEntity);

        return redirect(route('auth.index'))->with([
            'tips' => sprintf('客户：%s 添加成功', $customerEntity->getCustomerName()),
        ]);
    }

    public function show(int $customerId)
    {
        $customerEntity = $this->customerService->find($customerId);

        return view('SystemAuthorization::create', [
            'customerEntity' => $customerEntity,
            'edit' => true,
        ]);
    }

    public function update(int $customerId)
    {
        \request()->validate([
            'customer_type' => 'required|in:1,2',
            'customer_name' => 'required|max:32',
            'mobile' => 'nullable|max:32',
            'company_name' => 'nullable|max:32',
            'remark' => 'nullable|max:255',
        ], [
            'customer_name.required' => '请提供客户名',
            'mobile.required' => '请提供手机号',
        ]);

        $customerEntity = new CustomerEntity();
        $customerEntity->setCustomerId($customerId);
        $customerEntity->setCustomerType(\request('customer_type'));
        $customerEntity->setCustomerName(\request('customer_name'));
        $customerEntity->setMobile(\request('mobile'));
        $customerEntity->setCompanyName(\request('company_name'));
        $customerEntity->setRemark(\request('remark'));

        $this->customerService->update($customerEntity);

        return redirect(route('auth.index'))->with([
            'tips' => sprintf('客户：%s 更新成功', $customerEntity->getCustomerName()),
        ]);
    }

    public function destroy(int $customerId)
    {
        $customerEntity = $this->customerService->find($customerId);

        $this->customerService->delete($customerEntity);

        return back()->with([
            'tips' => sprintf('客户：%s 删除成功', $customerEntity->getCustomerName()),
            'tips_type' => 'success',
        ]);
    }
}
