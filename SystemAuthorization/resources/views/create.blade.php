@extends('SystemAuthorization::layouts.master')

@section('content')
@include('SystemAuthorization::layouts.nav', ['links' => [
  '客户' => route('auth.index'),
  '新增' => '#',
]])

<div class="p-5">
  @include('SystemAuthorization::layouts.tips', ['links' => [
    '新增客户',
  ]])
</div>

<div class="mx-auto max-w-6xl my-8">
  <!--
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
  <div class="mt-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
      <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
          <h3 class="text-base font-semibold leading-6 text-gray-900">客户信息</h3>
          <p class="mt-1 text-sm text-gray-600">客户的基本资料。</p>
        </div>
      </div>
      <div class="mt-5 md:col-span-2 md:mt-0">
        @php
        $url = route('auth.index');
        if (!empty($edit)) {
          $url = route('auth.update', ['auth' => $customerEntity?->getCustomerId()]);
        }
        @endphp

        <form action="{{$url}}" method="POST">
          @if(!empty($edit))
          @method('put')
          @endif

          @csrf
          <div class="overflow-hidden shadow sm:rounded-md">
            <div class="bg-white px-4 py-5 sm:p-6">
              <div class="grid grid-cols-6 gap-6">

                <div class="col-span-6 sm:col-span-3">
                  <label for="customer_type" class="block text-sm font-medium text-gray-700">客户类型</label>
                  <select id="customer_type" name="customer_type" value="{{ old('customer_type', $customerEntity?->getCustomerType() ?? null) }}" class="mt-1 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    <option value="1">个人</option>
                    <option value="2">公司</option>
                  </select>
                </div>

                <div class="col-span-6 sm:col-span-3"></div>

                <div class="col-span-6 sm:col-span-3">
                  <label for="customer_name" class="block text-sm font-medium text-gray-700">客户名</label>
                  <input type="text" name="customer_name" value="{{ old('customer_name', $customerEntity?->getCustomerName() ?? null) }}" id="customer_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                  @error('customer_name')
                  <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                  @enderror
                </div>

                <div class="col-span-6 sm:col-span-3">
                  <label for="mobile" class="block text-sm font-medium text-gray-700">手机号</label>
                  <input type="text" name="mobile" value="{{ old('mobile', $customerEntity?->getMobile(false) ?? null) }}" id="mobile" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                  @error('mobile')
                  <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                  @enderror
                </div>

                <div class="col-span-6 sm:col-span-3">
                  <label for="company_name" class="block text-sm font-medium text-gray-700">公司名</label>
                  <p class="mt-2 text-sm text-gray-500">个人客户可不填公司名。</p>
                  <input type="text" name="company_name" value="{{ old('company_name', $customerEntity?->getCompanyName() ?? null) }}" id="company_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="">
                </div>

                <div class="col-span-6">
                  <label for="remark" class="block text-sm font-medium text-gray-700">备注</label>
                  <div class="mt-1">
                    <textarea id="remark" name="remark" rows="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="">{{ old('remark', $customerEntity?->getRemark(false) ?? null) }}</textarea>
                  </div>
                </div>

              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
              <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">保存</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="hidden sm:block" aria-hidden="true">
    <div class="py-5">
      <div class="border-t border-gray-200"></div>
    </div>
  </div>

  <div class="mt-10 sm:mt-0 hidden">
    <div class="md:grid md:grid-cols-3 md:gap-6">
      <div class="md:col-span-1">
        <div class="px-4 sm:px-0">
          <h3 class="text-base font-semibold leading-6 text-gray-900">授权信息</h3>
          <p class="mt-1 text-sm text-gray-600">客户系统的授权信息。</p>
        </div>
      </div>
      <div class="mt-5 md:col-span-2 md:mt-0">
        <form action="#" method="POST">
          <div class="shadow sm:overflow-hidden sm:rounded-md">
            <div class="space-y-6 bg-white px-4 py-5 sm:p-6">

              <div class="grid grid-cols-2 gap-6">
                <div>
                  <label for="start_time" class="block text-sm font-medium text-gray-700">授权有效期开始时间</label>
                  <input type="text" name="start_time" id="start_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" readonly>
                </div>

                <div>
                  <label for="end_time" class="block text-sm font-medium text-gray-700">授权有效期结束时间</label>
                  <input type="text" name="end_time" id="end_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" readonly>
                </div>
              </div>

              <div class="grid grid-cols-4 gap-6">
                <div class="col-span-3 sm:col-span-2">
                  <label for="system_domain" class="block text-sm font-medium text-gray-700">客户站点</label>
                  <div class="mt-1 flex rounded-md shadow-sm">
                    <span class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500">http://</span>
                    <input type="text" name="system_domain" id="system_domain" class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="www.example.com" readonly>
                  </div>
                </div>

                <div class="col-span-3 sm:col-span-2">
                  <label for="ip" class="block text-sm font-medium text-gray-700">客户端 IP</label>
                  <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" name="ip" id="ip" class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="10.0.8.1" readonly>
                  </div>
                </div>
              </div>

              <div>
                <label for="about" class="block text-sm font-medium text-gray-700">授权码</label>
                <div class="mt-1">
                  <textarea id="about" name="about" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="系统自动生成" readonly></textarea>
                </div>
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
              <!-- <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">保存</button> -->
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>
@endsection