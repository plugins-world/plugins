@extends('EasySms::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('easy-sms.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('easy-sms.name') }}
        </p>

        <a href="{{ route('easy-sms.setting') }}">Go to the {{ config('easy-sms.name') }} plugin settings page.</a>
    </div>
@endsection
