@extends('SystemAuthorization::layouts.master')

@section('content')
@include('SystemAuthorization::layouts.nav', ['links' => [
'客户' => route('auth.index'),
'授权码列表' => '#',
]])

<div class="p-5">
  @include('SystemAuthorization::layouts.tips')
</div>

<div class="container p-5">

  <div class="px-6 lg:px-8 mt-5">
    <div class="sm:flex sm:items-center">
      <div class="sm:flex-auto">
        <h1 class="text-xl font-semibold text-gray-900">客户 {{ $customerEntity->getCustomerName() }} 的授权码</h1>
      </div>
      <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <form action="{{ route('auth-codes.store') }}" method="post" class="inline">
          @csrf
          <input type="hidden" name="customer_id" value="{{ \request('customer_id') }}" />
          <input type="hidden" name="auth_code_type" value="{{ \request('auth_code_type', 'crm') }}" />
          <button class="block rounded-md bg-indigo-600 py-1.5 px-3 text-center text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            新增授权码
          </button>
        </form>
      </div>
    </div>
    <div class="mt-8 flow-root">
      <div class="-my-2 -mx-6 overflow-x-auto lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
          <table class="min-w-full divide-y divide-gray-300">
            <thead>
              <tr>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">授权信息</th>
                <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">授权码</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">授权码状态</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">授权时间</th>
                <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">有效期</th>
                <th scope="col" class="relative py-3.5 pl-3 pr-6 text-right sm:pr-0">操作</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              @foreach($customerEntity->getAuthCodes() ?? [] as $authCodeEntity)
              <tr>
                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <div class="text-gray-900">授权域名: {{ $authCodeEntity->getSystemDomain() ?? '-' }}</div>
                  <div class="text-gray-900">授权 IP: {{ $authCodeEntity->getIp() ?? '-' }}</div>
                </td>

                <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm sm:pl-0">
                  <div class="flex items-center">
                    <div class="ml-4">
                      <div class="font-medium text-gray-900">{{ \Str::limit($authCodeEntity->getAuthCode(), 50) }}</div>
                    </div>
                  </div>
                </td>

                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <div class="text-gray-900">
                    <div class="text-gray-500">
                      @php
                      $statusText = match($authCodeEntity->getStatus()) {
                      default => '未知状态',
                      1 => '未使用',
                      2 => '已使用',
                      3 => '已到期',
                      4 => '已撤销',
                      };
                      @endphp

                      {{ $statusText }}
                    </div>
                  </div>
                </td>

                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <div class="text-gray-900">
                    <div class="text-gray-500">
                      @if($authCodeEntity->getIsPermanent())
                      永久授权
                      @else
                      {{ $authCodeEntity->getStartTime() }} ~ {{ $authCodeEntity->getEndTime() }}
                      @endif
                    </div>
                  </div>
                </td>

                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                  <div class="text-gray-900">
                    <div class="text-gray-500">
                      @if($authCodeEntity->getIsPermanent())
                      永久
                      @else
                      @php
                      $remainTimeText = \ZhenMu\Support\Traits\DateTime::secondsToTime($authCodeEntity->getEndTime());
                      @endphp

                      {{ $remainTimeText ?? '-' }}
                      @endif
                    </div>
                  </div>
                </td>

                <td class="relative whitespace-nowrap py-4 pl-3 pr-6 text-right text-sm font-medium sm:pr-0">
                  <div class="inline-flex">
                    <a href="{{ route('auth-codes.show', ['auth_code' => $authCodeEntity->getAuthCodeId()]) }}" class="px-1 text-indigo-600 hover:text-indigo-900">编辑</a>

                    <form action="{{ route('auth-codes.destroy', ['auth_code' => $authCodeEntity->getAuthCodeId()]) }}" method="post" class="px-1">
                      @csrf
                      @method('DELETE')
                      <button class="text-indigo-600 hover:text-indigo-900">删除</button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
              <!-- More authCodes... -->
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection