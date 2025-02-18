@extends('ClsLogger::layouts.master')

@section('content')
    <div class="container">
        <h1>Plugin: {{ config('cls-logger.name') }}</h1>

        <p>
            This view is loaded from plugin: {{ config('cls-logger.name') }}
        </p>

        <a href="{{ route('cls-logger.setting') }}">Go to the {{ config('cls-logger.name') }} plugin settings page.</a>
    </div>
@endsection
