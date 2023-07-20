@extends('PayCenter::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('pay-center.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('pay-center.name') }}
        </p>

        <a href="{{ route('pay-center.setting') }}">Go to the {{ config('pay-center.name') }} plugin settings page.</a>
    </div>
@endsection
