@extends('SystemAuthorization::layouts.master')

@section('content')
@include('SystemAuthorization::layouts.nav', ['links' => [
  '客户' => route('auth.index'),
]])

<div class="p-5">
  @include('SystemAuthorization::layouts.tips', ['links' => [
    '客户',
  ]])
</div>

<div class="container p-5">

  <div class="px-6 lg:px-8 mt-5">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">客户</h1>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <a href="{{ route('auth.create') }}" class="block rounded-md bg-indigo-600 py-1.5 px-3 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          添加客户
        </a>
      </div>
    </div>
    <div class="mt-8 flow-root">
      <div class="-my-2 -mx-6 overflow-x-auto lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
              <tr>
                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">客户</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">手机号</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">授权信息</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">备注</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right sm:pr-0">操作</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              @foreach($customerEntities ?? [] as $customerEntity)
              <tr>
                <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm sm:pl-0">
                  <div class="flex items-center">
                    <div class="ml-4">
                      <div class="font-medium text-gray-900">{{ $customerEntity->getCustomerName() }}</div>
                      <div class="text-gray-500">{{ $customerEntity->getCompanyName() }}</div>
                    </div>
                  </div>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <div class="text-gray-900">
                    <div class="text-gray-500">{{ $customerEntity->getMobile() ?? '-' }}</div>
                  </div>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <div class="text-gray-900">
                    数量: {{ $customerEntity->getAuthCodeCount() }}
                  </div>
                </td>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <div class="text-gray-900">{{ $customerEntity->getRemark() ?? '-' }}</div>
                </td>
                <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium sm:pr-0">
                  <div class="inline-flex">
                    <a href="{{ route('auth.show', ['auth' => $customerEntity->getCustomerId()]) }}" class="px-1 text-indigo-600 hover:text-indigo-900">编辑</a>
                    <a href="{{ route('auth-codes.index', ['customer_id' => $customerEntity->getCustomerId()]) }}" class="px-1 text-indigo-600 hover:text-indigo-900">查看授权码</a>

                    <form action="{{ route('auth.destroy', ['auth' => $customerEntity->getCustomerId()]) }}" method="post" class="px-1">
                      @csrf
                      @method('DELETE')
                      <button class="text-indigo-600 hover:text-indigo-900">删除</button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
              <!-- More customer... -->
            </tbody>
          </table>
          {{ $customerEntities->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection