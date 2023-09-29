@extends('SmsAuth::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('sms-auth.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('sms-auth.name') }}
        </p>

        <a href="{{ route('sms-auth.setting') }}">Go to the {{ config('sms-auth.name') }} plugin settings page.</a>
    </div>
@endsection
